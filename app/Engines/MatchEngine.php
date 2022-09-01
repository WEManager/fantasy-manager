<?php

namespace App\Engines;

use App\Models\GameEvent;
use App\Models\Player;
use App\Models\TournamentGame;
use App\Models\TournamentGameEvent;
use App\Models\TournamentStanding;
use Illuminate\Support\Facades\Cache;

class MatchEngine {
  private $game;

  public function __construct(TournamentGame $game) {
    $game->load(['awayTeam', 'homeTeam', 'group']);

    for($i=1; $i < 12; $i++) {
      if (gettype($game->homeLineup->{'player_' . $i}) === 'integer') {
        $game->homeLineup->{'player_' . $i} = Player::find($game->homeLineup->{'player_' . $i});
      }

      if (gettype($game->awayLineup->{'player_' . $i}) === 'integer') {
        $game->awayLineup->{'player_' . $i} = Player::find($game->awayLineup->{'player_' . $i});
      }
    }

    $this->game = $game;

    // If game has not started yet
    if ($this->game->isAboutToStart) {
      $this->startGame();
      $this->generateChances();
    }

    if ($this->game->isTimeForHalftime) {
      $this->halftime();
    }

    if ($this->game->isTimeForSecondHalf) {
      $this->startSecondHalf();
    }

    if (
      time() >= strtotime($this->game->start_time) &&
      !$this->game->isAboutToEnd &&
      !$this->game->isAboutToStart
    ) {
      $this->winningChances();
    }

    if ($this->game->isAboutToEnd) {
        $this->endGame();
    }
  }

  private function startGame() {
    if (is_null($this->game->hometeam_score))
      $this->game->hometeam_score = 0;

    if (is_null($this->game->awayteam_score))
      $this->game->awayteam_score = 0;

    $this->game->status = '1';

    $this->game->save();
  }

  private function halftime() {
    $this->game->status = '3';

    $this->game->save();
  }

  private function startSecondHalf() {
    $this->game->status = '1';

    $this->game->save();
  }

  public function endGame(){
    if ($this->game->isAboutToEnd) {
      $this->game->status = '2';
      $this->game->save();

      $hometeamRow = TournamentStanding::where('club_id', $this->game->hometeam_id)
          ->where('group_id', $this->game->group_id)
          ->first();

      $awayteamRow = TournamentStanding::where('club_id', $this->game->awayteam_id)
          ->where('group_id', $this->game->group_id)
          ->first();

        // Hometeam win
      if ($this->game->hometeam_score > $this->game->awayteam_score) {
        $hometeamRow->won += 1;
        $hometeamRow->points += 3;

        $awayteamRow->lost += 1;
      } // Awayteam win
      else if ($this->game->hometeam_score < $this->game->awayteam_score) {
        $awayteamRow->won += 1;
        $awayteamRow->points += 3;

        $hometeamRow->lost += 1;
      } // Tie
      else if ($this->game->hometeam_score == $this->game->awayteam_score) {
        $hometeamRow->tie += 1;
        $awayteamRow->tie += 1;
        $hometeamRow->points += 1;
        $awayteamRow->points += 1;
      }

      dump($this->game->hometeam->name .
        ' ' . $this->game->hometeam_score .
        '-' . $this->game->awayteam_score .
        ' ' . $this->game->awayteam->name
      );

      $hometeamRow->scored += $this->game->hometeam_score;
      $hometeamRow->conceded += $this->game->awayteam_score;
      $awayteamRow->scored += $this->game->awayteam_score;
      $awayteamRow->conceded += $this->game->hometeam_score;

      $hometeamRow->save();
      $awayteamRow->save();
    }
  }

  private function winningChances() {
    $hometeamSkills = $this->teamStrength('home');
    $awayteamSkills = $this->teamStrength('away');

    $totalSkill = $hometeamSkills + $awayteamSkills;

    if (rand(0, $totalSkill) <= $hometeamSkills) {
        $this->createChance('hometeam', 'awayteam');
    } else {
        $this->createChance('awayteam', 'hometeam');
    }
  }

  private function createChance($creators, $defenders, $isCounter = false) {
    $attackingMidfield = $this->getMidfieldSkills($creators);
    $defendingMidfield = $this->getMidfieldSkills($defenders);

    $totalMidfield = $attackingMidfield + $defendingMidfield;

    if (rand(0, $totalMidfield) <= $attackingMidfield) {
      $string = $creators . ':GOES_TO_ATTACK:';
      $string .= $this->scoringChance($creators, $defenders);
    } elseif (!$isCounter) {
      $string = $defenders . ':BREAKS_IN_MIDFIELD:';
      $string .= $this->counterAttack($defenders, $creators);
    }

    if (isset($string)) {
      $minute = (time() - strtotime($this->game->start_time)) / 60;
      if ($minute > 45) {
        $minute = $minute - 15;
      }

      TournamentGameEvent::create([
        'minute' => $minute,
        'tournament_game_id' => $this->game->id,
        'event_description_string' => $string,
      ]);
    }
  }

  private function scoringChance($creators, $defenders) {
    $attackingForwards = $this->getAttackerSkills($creators);

    $defendingBacks = $this->getDefendingSkills($defenders);

    $totalSkills = $attackingForwards + $defendingBacks;

    if (rand(0, $totalSkills) <= $attackingForwards) {
      $returnString = $creators . ':SCORES:';

      $this->game->{$creators . '_score'} = is_null($this->game->{$creators . '_score'}) ? 1 : $this->game->{$creators . '_score'} + 1;

      $this->game->{$defenders . '_score'} = is_null($this->game->{$defenders . '_score'}) ? 0 : $this->game->{$defenders . '_score'};

      $this->game->save();

      // Notify both teams about a goal
    } else {
      $returnString = $defenders . ':DEFENDS_SUCCESSFULLY:';
    }

    return $returnString;
  }

  private function counterAttack($creators, $defenders) {
    if (rand(1, 5) <= 2) {
      $this->createChance($creators, $defenders, true);
    }
  }

  private function teamStrength($teamKey) {
    $defense = $this->getDefendingSkills($teamKey);
    $midfield = $this->getMidfieldSkills($teamKey);
    $attack = $this->getAttackerSkills($teamKey);

    return $defense + $midfield + $attack;
  }

  private function getDefendingSkills($teamKey) {
    $totalValue = 0;

    $lineup = $teamKey === 'home' ? $this->game->homeLineup : $this->game->awayLineup;

    for ($i=1; $i < 12; $i++) { 
      $position = $lineup->{'position_' . $i};
      $player = $lineup->{'player_' . $i};

      if (substr($position, -1) === 'D') {
        if (substr($position, 0, 1) === 'C') {
          $totalValue += $player->centralDefending[$position];
        } else {
          $totalValue += $player->wideDefending[$position];
        }
      }
    }

    return $totalValue;
  }

  private function getMidfieldSkills($teamKey) {
    $totalValue = 0;

    $lineup = $teamKey === 'home' ? $this->game->homeLineup : $this->game->awayLineup;

    for ($i=1; $i < 12; $i++) { 
      $position = $lineup->{'position_' . $i};
      $player = $lineup->{'player_' . $i};

      if (substr($position, -1) === 'M') {
        if (substr($position, 0, 1) === 'C') {
          $totalValue += $player->centralMidfielder[$position];
        } else {
          $totalValue += $player->wideMidfielder[$position];
        }
      }
    }

    return $totalValue;
  }

  private function getAttackerSkills(string $teamKey) {
    $totalValue = 0;

    $lineup = $teamKey === 'home' ? $this->game->homeLineup : $this->game->awayLineup;

    for ($i=1; $i < 12; $i++) { 
      $position = $lineup->{'position_' . $i};
      $player = $lineup->{'player_' . $i};

      if (substr($position, -1) === 'F') {
        if (substr($position, 0, 1) === 'C') {
          $totalValue += $player->centralAttacker[$position];
        } else {
          $totalValue += $player->wideAttacker[$position];
        }
      }
    }

    return $totalValue;
  }

  protected function generateChances(): void {
    // Generate 10 to 26 chances in a game
    $chances = rand(10, 26);

    for ($i = 0; $i < $chances; $i++) {
      $minute = rand(1, 90);

      if ($minute > 45) $minute = $minute + 15;

      GameEvent::create([
        'game_id' => $this->game->id,
        'event_time' => date('Y-m-d H:i:s', strtotime($this->game->start_time . ' + ' . $minute . ' minutes')),
      ]);
    }
  }
}
