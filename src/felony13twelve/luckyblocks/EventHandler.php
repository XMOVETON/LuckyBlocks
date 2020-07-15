<?php

namespace felony13twelve\luckyblocks;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\command\ConsoleCommandSender;

/**
 * Class EventHandler
 * @package felony13twelve\luckyblocks
 *
 * @author  <felony13twelve@gmail.com> <Tg:@felony13twelve>
 * @version 1.0.0
 */
class EventHandler implements Listener {

    public function playerInteract (PlayerInteractEvent $event) {
        $plugin = LuckyBlocks::getInstance();
        $level = $plugin->getServer()->getDefaultLevel();

        $pos = $plugin->getConfig()['Position'];

        $player = $event->getPlayer();
        $block = $event->getBlock();

        $blockX = $block->getFloorX();
        $blockY = $block->getFloorY();
        $blockZ = $block->getFloorZ();

        for($x = $pos['Pos1']['x']; $x <= $pos['Pos2']['x']; $x++){
            for($y = $pos['Pos1']['y']; $y <= $pos['Pos2']['y']; $y++){
                for($z = $pos['Pos1']['z']; $z <= $pos['Pos2']['z']; $z++){
                    if ($x == $blockX && $y == $blockY && $z == $blockZ) {
                        foreach ($plugin->getConfig()['LuckyBlocks'] as $key => $value) {
                            $id = $value['id'];
                            
                            if ($block->getId() == $id) {
                                $level->setBlockIdAt($block->getX(), $block->getY(), $block->getZ(), 0);
                                $particle = new DestroyBlockParticle(new Vector3($block->getX(), $block->getY(), $block->getZ()), Block::get($block->getId()));
                                $level->addParticle($particle);

                                $prize = ($value['prizes'][array_rand($value['prizes'])]);

                                $command = str_replace(['{player}', '{prize}'], [$player->getName(), $prize], $value['command']);

                                $plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);

                                $str = str_replace('{prize}', $prize, $value['message']);
                                $player->sendMessage($str);
                            }
                        }
                    }
                }
            }
        }
    }
}