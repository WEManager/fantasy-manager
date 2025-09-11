<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Lineup extends Model
{
    protected $casts = [
        'club_id' => 'int',
    ];

    protected $fillable = [
        'club_id',
        'position_1', 'player_1',
        'position_2', 'player_2',
        'position_3', 'player_3',
        'position_4', 'player_4',
        'position_5', 'player_5',
        'position_6', 'player_6',
        'position_7', 'player_7',
        'position_8', 'player_8',
        'position_9', 'player_9',
        'position_10', 'player_10',
        'position_11', 'player_11',
        'substitute_1', 'substitute_2', 'substitute_3',
        'substitute_4', 'substitute_5', 'substitute_6',
    ];

    protected $appends = ['players'];

    /**
     * Transforma os dados do lineup em um array associativo posição => jogador
     *
     * @return array<string, Player|null>
     */
    public function getPlayersAttribute(): array
    {
        $players = [];

        for ($i = 1; $i <= 11; $i++) {
            $position = $this->{'position_'.$i};
            $playerId = $this->{'player_'.$i};

            if ($position && $playerId) {
                $player = Player::find($playerId);
                if ($player) {
                    $players[$position] = $player;
                }
            }
        }

        return $players;
    }

    /** @return BelongsTo<Club, $this> */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
