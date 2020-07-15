<?php

namespace felony13twelve\luckyblocks\Scheduler;

use pocketmine\scheduler\PluginTask;

use felony13twelve\luckyblocks\LuckyBlocks;

/**
 * Class LuckyTask
 * @package felony13twelve\luckyblocks
 *
 * @author  <felony13twelve@gmail.com> <Tg:@felony13twelve>
 * @version 1.0.0
 */
class LuckyTask extends PluginTask {

    public function onRun ($time) {
        LuckyBlocks::getInstance()->game->spawnLuckyBlock();
    }
}