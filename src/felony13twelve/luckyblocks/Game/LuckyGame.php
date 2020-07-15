<?php

namespace felony13twelve\luckyblocks\Game;

use felony13twelve\luckyblocks\LuckyBlocks;

/**
 * Class LuckyGame
 * @package felony13twelve\luckyblocks
 *
 * @author  <felony13twelve@gmail.com> <Tg:@felony13twelve>
 * @version 1.0.0
 */
class LuckyGame {

    public function spawnLuckyBlock () {
        $plugin = LuckyBlocks::getInstance();
        $level = $plugin->getServer()->getDefaultLevel();

        $x = $this->getPosition()['pos']['x'];
        $y = $this->getPosition()['pos']['y'];
        $z = $this->getPosition()['pos']['z'];

        $luckyblock = $plugin->getConfig()['LuckyBlocks'];

        if ($x > 0 && $y > 0 && $z > 0) {
            foreach ($luckyblock as $key => $value) {
                $change = $value['change'];
                $roll = mt_rand(1, 100);
                
                if ($roll <= $change) {
                    $level->setBlockIdAt($x, $y, $z, $value['id']);
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getPosition () {
        $data = LuckyBlocks::getInstance()->getConfig()['Position'];

        if (isset($data['Pos1']) && isset($data['Pos2'])) {
            $x = round(rand($data['Pos1']['x'], $data['Pos2']['x']));
            $y = $data['Pos1']['y'];
            $z = round(rand($data['Pos1']['z'], $data['Pos2']['z']));

            $pos = [
                'pos' => [
                    'x' => $x,
                    'y' => $y,
                    'z' => $z
                ]
            ];
            return $pos;
        }
        return null;
    }
}