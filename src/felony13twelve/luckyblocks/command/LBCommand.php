<?php

namespace felony13twelve\luckyblocks\Command;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use felony13twelve\luckyblocks\LuckyBlocks;

/**
 * Class LBCommand
 * @package felony13twelve\luckyblocks
 *
 * @author  <felony13twelve@gmail.com> <Tg:@felony13twelve>
 * @version 1.0.0
 */
class LBCommand extends Command {

    /** @var array */
    private $pos1 = array();

    /** @var array */
    private $pos2 = array();

    public function execute (CommandSender $player, $label, array $args) {
        if ($player instanceof Player) {
            if (!($player->isOp())) {
                return true;
            }
            if (count($args) == 0) {
                $player->sendMessage($this->help());
                return true;
            }
            if (count($args) > 1) {
                $player->sendMessage($this->help());
                return true;
            }
            
            switch (strtolower($args[0])) {
                case '1':
                case 'pos1':
                    $this->pos1[$player->getName()] = array(
                        $player->getFloorX(),
                        $player->getFloorY(),
                        $player->getFloorZ()
                    );
                    $player->sendMessage('§aТочка 1 установлена');

                    if (isset($this->pos1[$player->getName()]) && isset($this->pos2[$player->getName()])) {
                        $player->sendMessage('§eСоздайте арену - /lb create');
                    }
                    break;
                
                case '2':
                case 'pos2':
                    $this->pos2[$player->getName()] = array(
                        $player->getFloorX(),
                        $player->getFloorY(),
                        $player->getFloorZ()
                    );
                    $player->sendMessage('§aТочка 2 установлена');

                    if (isset($this->pos1[$player->getName()]) && isset($this->pos2[$player->getName()])) {
                        $player->sendMessage('§eСоздайте арену - /lb create');
                    }
                    break;

                case 'create':
                    if (isset($this->pos1[$player->getName()]) && isset($this->pos2[$player->getName()])) {
                        $pos1Y = $this->pos1[$player->getName()][1];
                        $pos2Y = $this->pos2[$player->getName()][1];
                        if ($pos1Y != $pos2Y) {
                            $this->resetSettings($player);
                            $player->sendMessage('§cКоордината "Y", должна совпадать с 1-й, 2-й точкой!');
                            return true;
                        }
                        $pos1 = $this->pos1[$player->getName()];
                        $pos2 = $this->pos2[$player->getName()];

                        $min[0] = min($pos1[0], $pos2[0]);
                        $max[0] = max($pos1[0], $pos2[0]);
                        $min[1] = min($pos1[1], $pos2[1]);
                        $max[1] = max($pos1[1], $pos2[1]);
                        $min[2] = min($pos1[2], $pos2[2]);
                        $max[2] = max($pos1[2], $pos2[2]);

                        $pos = array(
                            'Pos1' => array(
                                'x' => $min[0],
                                'y' => $min[1],
                                'z' => $min[2]
                            ),
                            'Pos2' => array(
                                'x' => $max[0],
                                'y' => $max[1],
                                'z' => $max[2]
                            )
                        );

                        LuckyBlocks::getInstance()->setPosition($pos);
                        $this->resetSettings($player);
                        $player->sendMessage('Спавн §l§eLuckyBlocks§r успешно создан!');
                        return true;
                    }
                    $player->sendMessage('§cСоздайте 1-ю и 2-ю точку!');
                    break;

                default:
                    $player->sendMessage($this->help());
                    break;
            }
        }
    }

    /**
     * @param Player $player
     */
    private function resetSettings (Player $player) {
        if (isset($this->pos1[$player->getName()])) {
            unset($this->pos1[$player->getName()]);
        }
        if (isset($this->pos2[$player->getName()])) {
            unset($this->pos2[$player->getName()]);
        }
    }

    /**
     * @return string
     */
    private function help () {
        $message = [
            'Использование - /lb <pos1|pos2|create>'
        ];

        return implode("\n", $message);
    }
}