<?php

declare(strict_types=1);

namespace App\Engines;

use App\Enums\FixtureStatus;
use App\Models;
use App\Services\Rng;
use Exception;

final class MatchEngine
{
    private Rng $rng;

    private Models\Fixture $game;

    private ?Models\Lineup $hometeamLineup;

    private ?Models\Lineup $awayteamLineup;

    /** @var array<int, Models\Player> */
    private array $hometeamPlayers = [];

    /** @var array<int, Models\Player> */
    private array $awayteamPlayers = [];

    /** @var array<int, array{int, string}> */
    private array $preCalculatedEventTimes = [];

    private int $eventTimeIndex = 0;

    /** @var array<int, int> */
    private array $recentlyUsedPlayers = [];

    private int $playerCooldownEvents = 3;

    private bool $eventsAlreadyGenerated = false;

    // Novos sistemas da Fase 1
    private FieldZones $fieldZones;

    private DynamicFatigue $fatigueSystem;

    private MomentumSystem $momentumSystem;

    public function __construct(Models\Fixture $game)
    {
        $this->game = $game;

        // Inicializar RNG determinístico com seed baseada no ID da partida
        $seed = $this->game->id + ($this->game->hometeam_id * 1000) + ($this->game->awayteam_id * 100);
        $this->rng = new Rng($seed);

        // Inicializar novos sistemas da Fase 1
        $this->fieldZones = new FieldZones();
        $this->fatigueSystem = new DynamicFatigue();
        $this->momentumSystem = new MomentumSystem();

        // Pré-calcular todos os tempos de eventos para evitar repetições
        $this->preCalculateEventTimes();

        $this->hometeamLineup = Models\Lineup::where(
            'club_id',
            $this->game->hometeam_id
        )->first();

        $this->awayteamLineup = Models\Lineup::where(
            'club_id',
            $this->game->awayteam_id
        )->first();

        // Validar se ambos os times têm lineups antes de iniciar a partida
        if (! $this->validateLineups()) {
            throw new Exception("Não é possível iniciar a partida: Um ou ambos os times não possuem formação (lineup) configurada. Time Casa: {$this->game->hometeam->name}, Time Visitante: {$this->game->awayteam->name}");
        }

        // If game has not started yet
        if ($this->game->isAboutToStart) {
            $this->startGame();
            // Gerar eventos assim que o jogo começar
            $this->winningChances();

            // Criar apitos automaticamente durante a simulação
            $this->createAutomaticWhistles();
        }

        if ($this->game->isTimeForHalftime) {
            $this->halftime();
        }

        if ($this->game->isTimeForSecondHalf) {
            $this->startSecondHalf();
        }

        // winningChances() já é chamado quando o jogo inicia (linha 69)
        // Removendo esta chamada duplicada para evitar eventos em dobro
        // endGame() já é chamado em createAutomaticWhistles() para evitar duplicação
    }

    public function endGame(): void
    {
        if ($this->game->isAboutToEnd) {
            // 🔊 APITO FINAL DO JUIZ
            $this->createRefereeWhistle(90, 'full_time', '🏁 APITO FINAL! Fim de jogo!');

            $this->game->status = FixtureStatus::ENDED;
            $this->game->save();

            $hometeamRow = Models\TournamentStanding::where('club_id', $this->game->hometeam_id)
                ->where('group_id', $this->game->group_id)
                ->first();

            $awayteamRow = Models\TournamentStanding::where('club_id', $this->game->awayteam_id)
                ->where('group_id', $this->game->group_id)
                ->first();

            // Hometeam win
            if ($this->game->hometeam_score > $this->game->awayteam_score) {
                $hometeamRow->won += 1;
                $hometeamRow->points += 3;

                $awayteamRow->lost += 1;
            } // Awayteam win
            elseif ($this->game->hometeam_score < $this->game->awayteam_score) {
                $awayteamRow->won += 1;
                $awayteamRow->points += 3;

                $hometeamRow->lost += 1;
            } // Tie
            elseif ($this->game->hometeam_score === $this->game->awayteam_score) {
                $hometeamRow->tie += 1;
                $awayteamRow->tie += 1;
                $hometeamRow->points += 1;
                $awayteamRow->points += 1;
            }

            dump(
                $this->game->hometeam->name.
                ' '.$this->game->hometeam_score.
                '-'.$this->game->awayteam_score.
                ' '.$this->game->awayteam->name
            );

            $hometeamRow->scored += $this->game->hometeam_score;
            $hometeamRow->conceded += $this->game->awayteam_score;
            $awayteamRow->scored += $this->game->awayteam_score;
            $awayteamRow->conceded += $this->game->hometeam_score;

            $hometeamRow->save();
            $awayteamRow->save();
        }
    }

    private function startGame(): void
    {
        if (is_null($this->game->hometeam_score)) {
            $this->game->hometeam_score = 0;
        }

        if (is_null($this->game->awayteam_score)) {
            $this->game->awayteam_score = 0;
        }

        $this->game->status = FixtureStatus::ACTIVE;

        $this->game->save();

        // 🔊 APITO INICIAL DO JUIZ
        $this->createRefereeWhistle(0, 'start', '🔊 APITO INICIAL! A partida começou!');

        printf((string) $this->game->id);
    }

    private function halftime(): void
    {
        // 🔊 APITO DE FIM DO PRIMEIRO TEMPO
        $this->createRefereeWhistle(45, 'halftime', '⏱️ FIM DO PRIMEIRO TEMPO! Intervalo.');

        $this->game->status = FixtureStatus::WAITING_FOR_SECOND_HALF;
        $this->game->save();
    }

    private function startSecondHalf(): void
    {
        // 🔊 APITO DE INÍCIO DO SEGUNDO TEMPO
        $this->createRefereeWhistle(45, 'second_half_start', '🔊 SEGUNDO TEMPO! A partida recomeça!');

        // NOVO: Resetar momentum no início do segundo tempo
        $this->momentumSystem->resetMomentum();

        $this->game->status = FixtureStatus::ACTIVE;
        $this->game->save();
    }

    private function winningChances(): void
    {
        // Evitar múltiplas execuções que criam eventos duplicados
        if ($this->eventsAlreadyGenerated) {
            return;
        }

        // Verificar se os lineups existem
        if (! $this->hometeamLineup || ! $this->awayteamLineup) {
            return;
        }

        // Carregar jogadores em cache para evitar múltiplas consultas
        $this->hometeamPlayers = [];
        $this->awayteamPlayers = [];

        for ($i = 1; $i < 12; $i++) {
            $hometeamPlayerId = $this->hometeamLineup->{'player_'.$i};
            $awayteamPlayerId = $this->awayteamLineup->{'player_'.$i};

            if ($hometeamPlayerId) {
                $this->hometeamPlayers[$i] = Models\Player::find($hometeamPlayerId);
            }
            if ($awayteamPlayerId) {
                $this->awayteamPlayers[$i] = Models\Player::find($awayteamPlayerId);
            }
        }

        $hometeamSkills = $this->teamStrength('hometeam');
        $awayteamSkills = $this->teamStrength('awayteam');
        $totalSkill = $hometeamSkills + $awayteamSkills;

        // NOVA ABORDAGEM: Gerar todos os eventos de uma vez com tempos pré-calculados
        foreach ($this->preCalculatedEventTimes as $index => $eventTime) {
            [$minute, $period] = $eventTime;

            // Determinar qual time ataca baseado em skills
            $creators = ($this->rng->range(0, $totalSkill) <= $hometeamSkills) ? 'hometeam' : 'awayteam';
            $defenders = ($creators === 'hometeam') ? 'awayteam' : 'hometeam';

            // Forçar o RNG a avançar antes de cada evento para garantir variação
            $this->rng->range(1, 1000); // "Queimar" um número aleatório

            // Criar evento diretamente
            $this->createChanceWithTime($creators, $defenders, $minute, $period);
        }

        // Marcar que eventos já foram gerados para evitar duplicações
        $this->eventsAlreadyGenerated = true;
    }

    /**
     * Cria um evento com tempo específico
     */
    private function createChanceWithTime(string $creators, string $defenders, int $minute, string $period): void
    {
        // Não criar eventos após o apito final (90')
        if ($minute > 90) {
            return;
        }

        // Selecionar jogadores específicos para o lance baseado em posições
        $attackingPlayer = $this->selectPlayerForAction($creators, 'attack');
        $defendingPlayer = $this->selectPlayerForAction($defenders, 'defend');

        // NOVO: Aplicar fadiga aos jogadores
        if ($attackingPlayer) {
            $actionIntensity = DynamicFatigue::getActionIntensity('attack');
            DynamicFatigue::updatePlayerFatigue($attackingPlayer, $actionIntensity, 1.0, true);
            $attackingPlayer = DynamicFatigue::applyFatigueToPlayer($attackingPlayer);
        }

        if ($defendingPlayer) {
            $actionIntensity = DynamicFatigue::getActionIntensity('defend');
            DynamicFatigue::updatePlayerFatigue($defendingPlayer, $actionIntensity, 1.0, true);
            $defendingPlayer = DynamicFatigue::applyFatigueToPlayer($defendingPlayer);
        }

        // NOVO: Aplicar momentum aos jogadores
        if ($attackingPlayer) {
            $attackingPlayer = $this->momentumSystem->applyMomentumToPlayer($attackingPlayer, $creators);
        }
        if ($defendingPlayer) {
            $defendingPlayer = $this->momentumSystem->applyMomentumToPlayer($defendingPlayer, $defenders);
        }

        // Calcular força do ataque vs defesa usando jogadores específicos
        $attackStrength = $this->calculatePlayerActionStrength($attackingPlayer, 'attack');
        $defenseStrength = $this->calculatePlayerActionStrength($defendingPlayer, 'defend');

        $totalStrength = $attackStrength + $defenseStrength;

        // Adicionar elemento de sorte/aleatoriedade
        $randomFactor = $this->rng->range(0, 30); // 30% de aleatoriedade
        $attackChance = $attackStrength + $randomFactor;

        if ($attackChance >= ($totalStrength / 2)) {
            // Ataque bem-sucedido - escolher tipo de ação
            $actionType = $this->determineActionType($attackingPlayer, $creators);
            $string = $this->executeScoringChance($attackingPlayer, $defendingPlayer, $creators, $defenders, $actionType);
        } else {
            // Defesa bem-sucedida
            $string = $defenders.':BREAKS_IN_MIDFIELD:';
            $this->counterAttack($defenders, $creators);
        }

        if (isset($string)) {
            // NOVO: Determinar zona do campo e contexto
            $actionType = $this->extractActionType($string);
            $fieldPosition = FieldZones::calculateFieldPosition($creators, $actionType, $attackingPlayer, $defendingPlayer);
            $zone = FieldZones::determineZone($creators, $actionType, $fieldPosition);
            $context = FieldZones::determineContext($zone, $actionType);

            // NOVO: Atualizar momentum baseado no evento
            $isPositive = str_contains($string, 'SCORES:') || str_contains($string, 'SUCCESS:');
            $this->momentumSystem->updateMomentum($creators, $actionType, $isPositive);

            // Substituir placeholders pelos nomes reais dos jogadores
            $finalEventString = $this->formatEventWithPlayerNames($string, $attackingPlayer, $defendingPlayer, $creators, $defenders);

            // Incluir nomes dos jogadores envolvidos e novos dados
            $playersInvolved = [
                'period' => $period,
                'attacking_player' => $attackingPlayer ? $attackingPlayer->know_as : null,
                'defending_player' => $defendingPlayer ? $defendingPlayer->know_as : null,
                'attacking_player_id' => $attackingPlayer ? $attackingPlayer->id : null,
                'defending_player_id' => $defendingPlayer ? $defendingPlayer->id : null,
                'action_type' => $actionType,
                // NOVO: Dados dos sistemas da Fase 1
                'field_zone' => $zone,
                'field_context' => $context,
                'field_position' => $fieldPosition,
                'attacking_player_fatigue' => $attackingPlayer ? ($attackingPlayer->current_fatigue ?? 0.0) : null,
                'defending_player_fatigue' => $defendingPlayer ? ($defendingPlayer->current_fatigue ?? 0.0) : null,
                'home_momentum' => $this->momentumSystem->getMomentum('hometeam'),
                'away_momentum' => $this->momentumSystem->getMomentum('awayteam'),
            ];

            Models\TournamentGameEvent::create([
                'minute' => $minute,
                'fixture_id' => $this->game->id,
                'event_description_string' => $finalEventString,
                'involved_persons' => json_encode($playersInvolved),
            ]);
        }
    }

    private function createChance(string $creators, string $defenders, bool $isCounter = false): void
    {
        // Selecionar jogadores específicos para o lance baseado em posições
        $attackingPlayer = $this->selectPlayerForAction($creators, 'attack');
        $defendingPlayer = $this->selectPlayerForAction($defenders, 'defend');

        // Calcular força do ataque vs defesa usando jogadores específicos
        $attackStrength = $this->calculatePlayerActionStrength($attackingPlayer, 'attack');
        $defenseStrength = $this->calculatePlayerActionStrength($defendingPlayer, 'defend');

        $totalStrength = $attackStrength + $defenseStrength;

        // Adicionar elemento de sorte/aleatoriedade
        $randomFactor = $this->rng->range(0, 30); // 30% de aleatoriedade
        $attackChance = $attackStrength + $randomFactor;

        if ($attackChance >= ($totalStrength / 2)) {
            // Ataque bem-sucedido - escolher tipo de ação
            $actionType = $this->determineActionType($attackingPlayer, $creators);
            $string = $creators.':GOES_TO_ATTACK:';
            $string .= $this->executeScoringChance($attackingPlayer, $defendingPlayer, $creators, $defenders, $actionType);
        } elseif (! $isCounter) {
            $string = $defenders.':BREAKS_IN_MIDFIELD:';
            $this->counterAttack($defenders, $creators);
        }

        if (isset($string)) {
            // Obter próximo tempo de evento pré-calculado
            [$minute, $period] = $this->getNextEventTime();

            // Substituir placeholders pelos nomes reais dos jogadores
            $finalEventString = $this->formatEventWithPlayerNames($string, $attackingPlayer, $defendingPlayer, $creators, $defenders);

            // Incluir nomes dos jogadores envolvidos
            $playersInvolved = [
                'period' => $period,
                'attacking_player' => $attackingPlayer ? $attackingPlayer->know_as : null,
                'defending_player' => $defendingPlayer ? $defendingPlayer->know_as : null,
                'attacking_player_id' => $attackingPlayer ? $attackingPlayer->id : null,
                'defending_player_id' => $defendingPlayer ? $defendingPlayer->id : null,
                'action_type' => $this->extractActionType($string),
            ];

            Models\TournamentGameEvent::create([
                'minute' => $minute,
                'fixture_id' => $this->game->id,
                'event_description_string' => $finalEventString,
                'involved_persons' => json_encode($playersInvolved),
            ]);
        }
    }

    private function scoringChance(string $creators, string $defenders): string
    {
        $attackingForwards = $this->getAttackerSkills($creators);
        $defendingBacks = $this->getDefendingSkills($defenders);

        $totalSkills = $attackingForwards + $defendingBacks;

        // Probabilidade de gol mais balanceada - 20% dos ataques podem resultar em gol
        $goalChance = ($attackingForwards * 20) / max($totalSkills, 1);

        if ($this->rng->range(0, 100) <= $goalChance) {

            $returnString = $creators.':SCORES:';
            $this->game->{$creators.'_score'} = is_null($this->game->{$creators.'_score'}) ? 1 : $this->game->{$creators.'_score'} + 1;
            $this->game->{$defenders.'_score'} = is_null($this->game->{$defenders.'_score'}) ? 0 : $this->game->{$defenders.'_score'};
            $this->game->save();

            // Notify both teams about a goal

        } else {
            $returnString = $defenders.':DEFENDS_SUCCESSFULLY:';
        }

        return $returnString;
    }

    private function counterAttack(string $creators, string $defenders): void
    {
        // REMOVIDO: contra-ataques estavam causando loops infinitos
        // Contra-ataques serão eventos independentes em vez de recursivos
    }

    private function teamStrength(string $teamKey): int
    {

        $defense = $this->getDefendingSkills($teamKey);
        $midfield = $this->getMidfieldSkills($teamKey);
        $attack = $this->getAttackerSkills($teamKey);

        return $defense + $midfield + $attack;
    }

    private function getDefendingSkills(string $teamKey): int
    {
        $totalValue = 0;

        foreach ($this->{$teamKey.'Lineup'}->getAttributes() as $key => $value) {
            if (mb_strpos($key, 'position_') !== false) {
                $number = explode('_', $key);
                $playerNumber = (int) $number[1];

                $player = $this->{$teamKey.'Players'}[$playerNumber] ?? null;
                if (! $player) {
                    continue;
                }

                // if is defender
                if (mb_substr($value, -1) === 'D') {
                    if (mb_substr($value, 0, 1) === 'C') {
                        $skills = $player->central_defending;
                        $totalValue += (int) ($skills[$value] ?? 0);
                    } else {
                        $skills = $player->wide_defending;
                        $totalValue += (int) ($skills[$value] ?? 0);
                    }
                }
            }
        }

        return $totalValue;
    }

    private function getMidfieldSkills(string $teamKey): int
    {
        $totalValue = 0;

        foreach ($this->{$teamKey.'Lineup'}->getAttributes() as $key => $value) {
            if (mb_strpos($key, 'position_') !== false) {
                $number = explode('_', $key);
                $playerNumber = (int) $number[1];

                $player = $this->{$teamKey.'Players'}[$playerNumber] ?? null;
                if (! $player) {
                    continue;
                }

                // if is midfielder
                if (mb_substr($value, -1) === 'M') {
                    if (mb_substr($value, 0, 1) === 'C') {
                        $skills = $player->central_midfielder;
                        $totalValue += (int) ($skills[$value] ?? 0);
                    } else {
                        $skills = $player->wide_midfielder;
                        $totalValue += (int) ($skills[$value] ?? 0);
                    }
                }
            }
        }

        return $totalValue;
    }

    private function getAttackerSkills(string $teamKey): int
    {
        $totalValue = 0;

        foreach ($this->{$teamKey.'Lineup'}->getAttributes() as $key => $value) {
            if (mb_strpos($key, 'position_') !== false) {
                $number = explode('_', $key);
                $playerNumber = (int) $number[1];

                $player = $this->{$teamKey.'Players'}[$playerNumber] ?? null;
                if (! $player) {
                    continue;
                }

                // if is attacker
                if (mb_substr($value, -1) === 'F') {
                    if (mb_substr($value, 0, 1) === 'C') {
                        $skills = $player->central_attacker;
                        $totalValue += (int) ($skills[$value] ?? 0);
                    } else {
                        $skills = $player->wide_attacker;
                        $totalValue += (int) ($skills[$value] ?? 0);
                    }
                }
            }
        }

        return $totalValue;
    }

    private function generateChances(): void
    {
        // Usar os tempos pré-calculados para criar GameEvents variados
        foreach ($this->preCalculatedEventTimes as $eventTime) {
            [$minute, $period] = $eventTime;

            // Criar evento do jogo se o modelo existir
            if (class_exists('App\Models\GameEvent')) {
                Models\GameEvent::create([
                    'fixture_id' => $this->game->id,
                    'event_time' => date('Y-m-d H:i:s', strtotime($this->game->start_time.' + '.$minute.' minutes')),
                ]);
            }
        }
    }

    /**
     * Pré-calcula uma lista de tempos variados para eventos
     */
    private function preCalculateEventTimes(): void
    {
        $this->preCalculatedEventTimes = [];
        $usedMinutes = [];

        // Gerar entre 12-20 eventos para uma partida mais dinâmica
        $totalEvents = $this->rng->range(12, 20);

        $attempts = 0;
        while (count($this->preCalculatedEventTimes) < $totalEvents && $attempts < 100) {
            $attempts++;

            // 50% primeiro tempo, 50% segundo tempo
            $isFirstHalf = $this->rng->range(1, 100) <= 50;

            if ($isFirstHalf) {
                // Primeiro tempo (1-45min + acréscimos)
                $minute = $this->rng->range(1, 45);
                $period = 'first';

                // Acréscimos do primeiro tempo (15% de chance após minuto 40)
                if ($minute >= 40 && $this->rng->range(1, 100) <= 15) {
                    $minute = 45 + $this->rng->range(1, 5);
                    $period = 'first_extra';
                }
            } else {
                // Segundo tempo (45-90min + acréscimos)
                $minute = $this->rng->range(45, 90);
                $period = 'second';

                // Acréscimos do segundo tempo (20% de chance após minuto 85)
                if ($minute >= 85 && $this->rng->range(1, 100) <= 20) {
                    $minute = 90 + $this->rng->range(1, 5); // Máximo 5 minutos de acréscimo
                    $period = 'second_extra';
                }
            }

            // Evitar minutos duplicados (pequena variação permitida)
            $minuteKey = $minute.'_'.$period;
            if (! isset($usedMinutes[$minuteKey])) {
                $this->preCalculatedEventTimes[] = [$minute, $period];
                $usedMinutes[$minuteKey] = true;
            }
        }

        // Se não conseguiu gerar suficientes eventos únicos, adicionar alguns forçados
        if (count($this->preCalculatedEventTimes) < 10) {
            for ($minute = 5; $minute <= 85; $minute += 8) {
                $period = $minute < 45 ? 'first' : 'second';
                $this->preCalculatedEventTimes[] = [$minute, $period];
                if (count($this->preCalculatedEventTimes) >= 15) {
                    break;
                }
            }
        }

        // Ordenar por minuto para eventos mais realistas
        usort($this->preCalculatedEventTimes, function ($a, $b) {
            return $a[0] <=> $b[0];
        });

        // Adicionar pequena variação aleatória para evitar todos no mesmo segundo
        $this->preCalculatedEventTimes = array_map(function ($eventTime) {
            $variation = $this->rng->range(0, 2); // 0-2 minutos de variação
            $eventTime[0] += $variation;

            return $eventTime;
        }, $this->preCalculatedEventTimes);
    }

    /**
     * Obtém o próximo tempo de evento pré-calculado
     *
     * @return array{int, string} [minuto, período]
     */
    private function getNextEventTime(): array
    {
        // Se acabaram os tempos pré-calculados, calcular um novo
        if ($this->eventTimeIndex >= count($this->preCalculatedEventTimes)) {
            return $this->calculateEventTime(0);
        }

        $eventTime = $this->preCalculatedEventTimes[$this->eventTimeIndex];
        $this->eventTimeIndex++;

        return $eventTime;
    }

    /**
     * Calcula o minuto e período do evento baseado na distribuição aleatória equilibrada
     *
     * @return array{int, string} [minuto, período]
     */
    private function calculateEventTime(float $elapsedMinutes): array
    {
        // Distribuição simples e equilibrada: 50% primeiro tempo, 50% segundo tempo
        $isFirstHalf = $this->rng->range(1, 100) <= 50;

        if ($isFirstHalf) {
            // Primeiro tempo (1-45min + acréscimos)
            $minute = $this->rng->range(1, 45);
            $period = 'first';

            // Acréscimos do primeiro tempo (20% de chance após minuto 40)
            if ($minute >= 40 && $this->rng->range(1, 100) <= 20) {
                $minute = 45 + $this->rng->range(1, 5);
                $period = 'first_extra';
            }
        } else {
            // Segundo tempo (46-90min + acréscimos)
            $minute = $this->rng->range(46, 90);
            $period = 'second';

            // Acréscimos do segundo tempo (25% de chance após minuto 85)
            if ($minute >= 85 && $this->rng->range(1, 100) <= 25) {
                $minute = 90 + $this->rng->range(1, 8);
                $period = 'second_extra';
            }
        }

        return [$minute, $period];
    }

    /**
     * Valida se ambos os times possuem lineups configurados
     */
    private function validateLineups(): bool
    {
        // Verificar se os lineups existem
        if (! $this->hometeamLineup || ! $this->awayteamLineup) {
            return false;
        }

        // Verificar se os lineups têm todos os 11 jogadores
        for ($i = 1; $i <= 11; $i++) {
            $hometeamPlayer = $this->hometeamLineup->{'player_'.$i};
            $awayteamPlayer = $this->awayteamLineup->{'player_'.$i};

            if (! $hometeamPlayer || ! $awayteamPlayer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Seleciona um jogador específico para uma ação baseado no tipo e posição
     */
    private function selectPlayerForAction(string $teamKey, string $actionType): ?Models\Player
    {
        // Definir posições preferenciais baseadas no tipo de ação
        $preferredPositions = [
            'attack' => ['CF', 'LF', 'RF', 'CLF', 'CRF', 'CM', 'CRM', 'CLM', 'LD', 'RD'],
            'defend' => ['CD', 'CLD', 'CRD', 'LD', 'RD', 'CM', 'CRM', 'CLM'],
            'midfield' => ['CM', 'CRM', 'CLM', 'LM', 'RM', 'CF', 'LF', 'RF'],
        ];

        $positions = $preferredPositions[$actionType] ?? $preferredPositions['midfield'];

        // NOVA LÓGICA: Coletar TODOS os jogadores elegíveis
        $eligiblePlayers = [];

        // Primeiro: jogadores nas posições preferenciais
        foreach ($positions as $position) {
            $playerNumber = $this->getPlayerNumberInPosition($teamKey, $position);
            if ($playerNumber && isset($this->{$teamKey.'Players'}[$playerNumber])) {
                $eligiblePlayers[] = $this->{$teamKey.'Players'}[$playerNumber];
            }
        }

        // Se poucos jogadores elegíveis, adicionar mais do campo
        if (count($eligiblePlayers) < 3) {
            for ($i = 2; $i <= 11; $i++) { // 2-11 para pular goleiro
                if (isset($this->{$teamKey.'Players'}[$i])) {
                    $player = $this->{$teamKey.'Players'}[$i];
                    // Evitar duplicatas
                    if (! in_array($player, $eligiblePlayers, true)) {
                        $eligiblePlayers[] = $player;
                    }
                }
            }
        }

        // FILTRAR jogadores recentemente usados para aumentar variação
        $availablePlayers = array_filter($eligiblePlayers, function ($player) {
            return ! in_array($player->id, $this->recentlyUsedPlayers);
        });

        // Se todos estão em cooldown, usar os elegíveis normais
        if (empty($availablePlayers)) {
            $availablePlayers = $eligiblePlayers;
        }

        // SELEÇÃO ALEATÓRIA com peso baseado na ação
        if (! empty($availablePlayers)) {
            $selectedPlayer = null;

            // 70% de chance de escolher aleatoriamente, 30% por habilidade
            if ($this->rng->range(1, 100) <= 70) {
                // Seleção totalmente aleatória para máxima variação
                $selectedPlayer = $this->rng->pick($availablePlayers);
            } else {
                // Seleção por habilidade (mantém algum realismo)
                $selectedPlayer = $this->selectBestPlayerForAction($availablePlayers, $actionType);
            }

            // Adicionar ao cooldown
            if ($selectedPlayer) {
                $this->addPlayerToCooldown($selectedPlayer->id);
            }

            return $selectedPlayer;
        }

        return null;
    }

    /**
     * Adiciona um jogador ao cooldown para evitar repetições
     */
    private function addPlayerToCooldown(int $playerId): void
    {
        // Adicionar jogador ao início da lista
        array_unshift($this->recentlyUsedPlayers, $playerId);

        // Manter apenas os últimos N jogadores
        if (count($this->recentlyUsedPlayers) > $this->playerCooldownEvents) {
            $this->recentlyUsedPlayers = array_slice($this->recentlyUsedPlayers, 0, $this->playerCooldownEvents);
        }
    }

    /**
     * Seleciona o melhor jogador baseado em habilidades específicas
     */
    /**
     * @param  array<int, Models\Player>  $players
     */
    private function selectBestPlayerForAction(array $players, string $actionType): ?Models\Player
    {
        $bestPlayer = null;
        $bestStrength = 0;

        foreach ($players as $player) {
            $strength = $this->calculatePlayerActionStrength($player, $actionType);
            if ($strength > $bestStrength) {
                $bestStrength = $strength;
                $bestPlayer = $player;
            }
        }

        return $bestPlayer;
    }

    /**
     * Cria apitos automáticos durante a simulação
     */
    private function createAutomaticWhistles(): void
    {
        // Apito de fim do primeiro tempo (45')
        $this->createRefereeWhistle(45, 'halftime', '⏱️ FIM DO PRIMEIRO TEMPO! Intervalo.');

        // Apito de início do segundo tempo (45')
        $this->createRefereeWhistle(45, 'second_half_start', '🔊 SEGUNDO TEMPO! A partida recomeça!');

        // Apito final (90') - apenas marcar o jogo como finalizado
        $this->createRefereeWhistle(90, 'full_time', '🏁 APITO FINAL! Fim de jogo!');

        // Marcar jogo como finalizado
        $this->game->status = FixtureStatus::ENDED;
        $this->game->save();
    }

    /**
     * Cria evento de apito do juiz
     */
    private function createRefereeWhistle(int $minute, string $whistleType, string $message): void
    {
        $playersInvolved = [
            'period' => $this->determinePeriodFromMinute($minute),
            'whistle_type' => $whistleType,
            'referee_action' => true,
        ];

        Models\TournamentGameEvent::create([
            'minute' => $minute,
            'fixture_id' => $this->game->id,
            'event_description_string' => $message,
            'involved_persons' => json_encode($playersInvolved),
        ]);
    }

    /**
     * Determina o período baseado no minuto
     */
    private function determinePeriodFromMinute(int $minute): string
    {
        if ($minute === 0) {
            return 'start';
        }
        if ($minute < 45) {
            return 'first';
        }
        if ($minute === 45) {
            return 'second_start';
        }
        if ($minute <= 90) {
            return 'second';
        }

        return 'second_extra';

    }

    /**
     * Encontra o número do jogador em uma posição específica
     */
    private function getPlayerNumberInPosition(string $teamKey, string $position): ?int
    {
        $lineup = $this->{$teamKey.'Lineup'};
        if (! $lineup) {
            return null;
        }

        for ($i = 1; $i <= 11; $i++) {
            if ($lineup->{'position_'.$i} === $position) {
                return $i;
            }
        }

        return null;
    }

    /**
     * Calcula a força de um jogador para uma ação específica
     */
    private function calculatePlayerActionStrength(?Models\Player $player, string $actionType): int
    {
        if (! $player) {
            return 50;
        } // Valor médio se não há jogador

        switch ($actionType) {
            case 'attack':
                return (int) (
                    ($player->stats['technical']['finishing'] ?? 50) * 0.3 +
                    ($player->stats['technical']['dribbling'] ?? 50) * 0.25 +
                    ($player->stats['technical']['ball_control'] ?? 50) * 0.2 +
                    ($player->stats['mental']['att_position'] ?? 50) * 0.15 +
                    ($player->stats['mental']['composure'] ?? 50) * 0.1
                );

            case 'defend':
                return (int) (
                    ($player->stats['technical']['defensive_awareness'] ?? 50) * 0.3 +
                    ($player->stats['technical']['standing_tackle'] ?? 50) * 0.25 +
                    ($player->stats['mental']['interceptions'] ?? 50) * 0.2 +
                    ($player->stats['physical']['strength'] ?? 50) * 0.15 +
                    ($player->stats['mental']['aggression'] ?? 50) * 0.1
                );

            case 'pass':
                return (int) (
                    ($player->stats['technical']['short_passing'] ?? 50) * 0.4 +
                    ($player->stats['technical']['long_passing'] ?? 50) * 0.3 +
                    ($player->stats['mental']['vision'] ?? 50) * 0.2 +
                    ($player->stats['mental']['composure'] ?? 50) * 0.1
                );

            default:
                return (int) (($player->stats['technical']['ball_control'] ?? 50) +
                            ($player->stats['mental']['composure'] ?? 50)) / 2;
        }
    }

    /**
     * Determina o tipo de ação baseado nas características do jogador
     */
    private function determineActionType(?Models\Player $player, string $teamKey): string
    {
        if (! $player) {
            return 'pass';
        }

        $finishing = $player->stats['technical']['finishing'] ?? 50;
        $dribbling = $player->stats['technical']['dribbling'] ?? 50;
        $passing = $player->stats['technical']['short_passing'] ?? 50;

        // Probabilidades baseadas nos atributos (aumentadas para testar goleiro)
        $shotProb = $finishing / 100.0 * 0.5; // Max 50% chance
        $dribbleProb = $dribbling / 100.0 * 0.2; // Max 20% chance

        $rand = $this->rng->uniform();

        if ($rand < $shotProb) {
            return 'shot';
        }
        if ($rand < $shotProb + $dribbleProb) {
            return 'dribble';
        }

        return 'pass';

    }

    /**
     * Executa uma chance de gol com jogadores específicos
     */
    private function executeScoringChance(?Models\Player $attacker, ?Models\Player $defender, string $creators, string $defenders, string $actionType): string
    {
        if (! $attacker) {
            return $this->scoringChance($creators, $defenders);
        }

        switch ($actionType) {
            case 'shot':
                return $this->executeShot($attacker, $defender, $creators, $defenders);
            case 'dribble':
                return $this->executeDribble($attacker, $defender, $creators, $defenders);
            case 'pass':
                return $this->executePass($attacker, $defender, $creators, $defenders);
            default:
                return $this->scoringChance($creators, $defenders);
        }
    }

    /**
     * Executa uma finalização
     */
    private function executeShot(?Models\Player $attacker, ?Models\Player $defender, string $creators, string $defenders): string
    {
        if (! $attacker) {
            return $defenders.':DEFENDS_SUCCESSFULLY:';
        }

        // NOVO: Determinar zona do campo para o chute
        $fieldPosition = FieldZones::calculateFieldPosition($creators, 'shot', $attacker, $defender);
        $zone = FieldZones::determineZone($creators, 'shot', $fieldPosition);
        $context = FieldZones::determineContext($zone, 'shot');
        $zoneModifiers = FieldZones::getZoneModifiers($zone, 'shot');

        $shotPower = $this->calculatePlayerActionStrength($attacker, 'attack');
        $defensePower = $defender ? $this->calculatePlayerActionStrength($defender, 'defend') : 50;

        // Adicionar goleiro na equação
        $goalkeeperPower = 0;
        if ($defenders === 'hometeam' && isset($this->hometeamPlayers[1])) {
            $gk = $this->hometeamPlayers[1];
            $goalkeeperPower = (int) (
                ($gk->stats['goalkeeping']['gk_reflexes'] ?? 50) * 0.3 +
                ($gk->stats['goalkeeping']['gk_positioning'] ?? 50) * 0.3 +
                ($gk->stats['goalkeeping']['gk_diving'] ?? 50) * 0.4
            );
        } elseif ($defenders === 'awayteam' && isset($this->awayteamPlayers[1])) {
            $gk = $this->awayteamPlayers[1];
            $goalkeeperPower = (int) (
                ($gk->stats['goalkeeping']['gk_reflexes'] ?? 50) * 0.3 +
                ($gk->stats['goalkeeping']['gk_positioning'] ?? 50) * 0.3 +
                ($gk->stats['goalkeeping']['gk_diving'] ?? 50) * 0.4
            );
        }

        $totalDefense = ($defensePower + $goalkeeperPower) / 2;

        // NOVO: Usar xG baseado na zona do campo
        $baseXG = FieldZones::getExpectedGoals($zone, $context, $attacker);
        $successChance = $baseXG * 100; // Converter para porcentagem

        // Aplicar modificadores da zona
        $successChance *= $zoneModifiers['shot_accuracy'];

        // Determinar resultado do chute com eventos específicos
        $shotRoll = $this->rng->range(0, 100);

        if ($shotRoll < $successChance) {
            // GOAL!
            $this->game->{$creators.'_score'} = is_null($this->game->{$creators.'_score'}) ? 1 : $this->game->{$creators.'_score'} + 1;
            $this->game->{$defenders.'_score'} = is_null($this->game->{$defenders.'_score'}) ? 0 : $this->game->{$defenders.'_score'};
            $this->game->save();

            return $creators.':SCORES:';
        }
        if ($shotRoll < ($successChance + 25)) {
            // Defesa espetacular do goleiro (25% adicional)
            return 'GOALKEEPER_SAVE:';
        }
        if ($shotRoll < ($successChance + 40)) {
            // Chute para fora (15% adicional)
            return 'SHOT_WIDE:';
        }

        // Bloqueio da defesa (restante)
        return $defenders.':DEFENDS_SUCCESSFULLY:';

    }

    /**
     * Executa um drible
     */
    private function executeDribble(?Models\Player $attacker, ?Models\Player $defender, string $creators, string $defenders): string
    {
        if (! $attacker) {
            return $defenders.':DEFENDS_SUCCESSFULLY:';
        }

        $dribbleSkill = $attacker->stats['technical']['dribbling'] ?? 50;
        $defenseSkill = $defender ? ($defender->stats['technical']['standing_tackle'] ?? 50) : 50;

        $successChance = ($dribbleSkill / ($dribbleSkill + $defenseSkill)) * 100;

        if ($this->rng->range(0, 100) < $successChance) {
            // Drible bem-sucedido - 40% chance de finalização direta
            if ($this->rng->range(0, 100) < 40) {
                return $this->executeShot($attacker, $defender, $creators, $defenders);
            }

            return 'DRIBBLE_SUCCESS:';

        }

        return $defenders.':DEFENDS_SUCCESSFULLY:';

    }

    /**
     * Executa um passe decisivo
     */
    private function executePass(?Models\Player $attacker, ?Models\Player $defender, string $creators, string $defenders): string
    {
        if (! $attacker) {
            return $defenders.':DEFENDS_SUCCESSFULLY:';
        }

        $passSkill = $this->calculatePlayerActionStrength($attacker, 'pass');
        $interceptSkill = $defender ? ($defender->stats['mental']['interceptions'] ?? 50) : 50;

        $successChance = ($passSkill / ($passSkill + $interceptSkill)) * 100;

        if ($this->rng->range(0, 100) < $successChance) {
            // Passe bem-sucedido - 25% chance de assistência para gol
            if ($this->rng->range(0, 100) < 25) {
                // Selecionar outro atacante para finalizar
                $finisher = $this->selectPlayerForAction($creators, 'attack');

                return $this->executeShot($finisher, $defender, $creators, $defenders);
            }

            return 'PASS_SUCCESS:';

        }

        return $defenders.':DEFENDS_SUCCESSFULLY:';

    }

    /**
     * Formata o evento com nomes reais dos jogadores
     */
    private function formatEventWithPlayerNames(string $eventString, ?Models\Player $attackingPlayer, ?Models\Player $defendingPlayer, string $creators, string $defenders): string
    {
        $hometeamName = $this->game->hometeam->name;
        $awayteamName = $this->game->awayteam->name;
        $attackingPlayerName = $attackingPlayer ? $attackingPlayer->know_as : 'Jogador';
        $defendingPlayerName = $defendingPlayer ? $defendingPlayer->know_as : 'Jogador';

        // Substituir nomes dos times
        $formatted = str_replace(['hometeam', 'awayteam'], [$hometeamName, $awayteamName], $eventString);

        // Adicionar nomes dos jogadores baseado no tipo de evento
        if (mb_strpos($eventString, 'SCORES:') !== false) {
            $formatted = "⚽ GOOOOL! {$attackingPlayerName} ({$this->getTeamName($creators)}) marca!";
        } elseif (mb_strpos($eventString, 'GOALKEEPER_SAVE:') !== false) {
            $goalkeeperName = $this->getGoalkeeperName($defenders);
            $formatted = "🥅 DEFESAÇA! {$goalkeeperName} ({$this->getTeamName($defenders)}) faz uma defesa espetacular no chute de {$attackingPlayerName}";
        } elseif (mb_strpos($eventString, 'SHOT_WIDE:') !== false) {
            $formatted = "🎯 {$attackingPlayerName} ({$this->getTeamName($creators)}) finaliza, mas a bola passa longe do gol";
        } elseif (mb_strpos($eventString, 'DRIBBLE_SUCCESS:') !== false) {
            $formatted = "🏃 {$attackingPlayerName} ({$this->getTeamName($creators)}) passa pelo adversário com um belo drible";
        } elseif (mb_strpos($eventString, 'PASS_SUCCESS:') !== false) {
            $formatted = "📤 {$attackingPlayerName} ({$this->getTeamName($creators)}) faz um passe decisivo";
        } elseif (mb_strpos($eventString, 'DEFENDS_SUCCESSFULLY:') !== false) {
            $formatted = "🛡️ {$defendingPlayerName} ({$this->getTeamName($defenders)}) intercepta o ataque de {$attackingPlayerName}";
        } elseif (mb_strpos($eventString, 'GOES_TO_ATTACK:') !== false) {
            $formatted = "⚡ {$attackingPlayerName} ({$this->getTeamName($creators)}) parte para o ataque";
        } elseif (mb_strpos($eventString, 'BREAKS_IN_MIDFIELD:') !== false) {
            $formatted = "🔄 {$attackingPlayerName} ({$this->getTeamName($creators)}) recupera a posse de bola no meio-campo";
        }

        return $formatted;
    }

    /**
     * Obtém o nome do time baseado na chave
     */
    private function getTeamName(string $teamKey): string
    {
        return $teamKey === 'hometeam' ? $this->game->hometeam->name : $this->game->awayteam->name;
    }

    /**
     * Obtém o nome do goleiro do time especificado
     */
    private function getGoalkeeperName(string $teamKey): string
    {
        if ($teamKey === 'hometeam' && isset($this->hometeamPlayers[1])) {
            return $this->hometeamPlayers[1]->know_as;
        }
        if ($teamKey === 'awayteam' && isset($this->awayteamPlayers[1])) {
            return $this->awayteamPlayers[1]->know_as;
        }

        return 'Goleiro';
    }

    /**
     * Extrai o tipo de ação do evento
     */
    private function extractActionType(string $eventString): string
    {
        if (mb_strpos($eventString, 'SCORES:') !== false) {
            return 'goal';
        }
        if (mb_strpos($eventString, 'GOALKEEPER_SAVE:') !== false) {
            return 'goalkeeper_save';
        }
        if (mb_strpos($eventString, 'SHOT_WIDE:') !== false) {
            return 'shot_wide';
        }
        if (mb_strpos($eventString, 'DRIBBLE_SUCCESS:') !== false) {
            return 'dribble';
        }
        if (mb_strpos($eventString, 'PASS_SUCCESS:') !== false) {
            return 'pass';
        }
        if (mb_strpos($eventString, 'DEFENDS_SUCCESSFULLY:') !== false) {
            return 'defense';
        }
        if (mb_strpos($eventString, 'BREAKS_IN_MIDFIELD:') !== false) {
            return 'recovery';
        }

        return 'generic';

    }
}
