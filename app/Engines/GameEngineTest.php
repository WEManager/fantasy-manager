<?php
namespace App\Engines;

class GameEngineTest {

    public $expected = 0;
    public $unexpected = 0;

    public function __construct($amount = 100)
    {
        for ($i=0;$i<$amount;$i++) {
            $engine = new GameEngine();
            if ($engine->score['hometeam'] > $engine->score['awayteam']) {

                if ($engine->chances['hometeam'] > $engine->chances['awayteam']) {
                    $this->expected++;
                } else {
                    $this->unexpected++;
                }
            } elseif ($engine->score['hometeam'] < $engine->score['awayteam']) {
                if ($engine->chances['hometeam'] < $engine->chances['awayteam']) {
                    $this->expected++;
                } else {
                    $this->unexpected++;
                }
            } else {
                if (
                    $engine->chances['hometeam']+1 >= $engine->chances['awayteam'] ||
                    $engine->chances['hometeam']-1 <= $engine->chances['awayteam']
                ) {
                    $this->expected++;
                } else {
                    $this->unexpected++;
                }
            }
        }
    }
}