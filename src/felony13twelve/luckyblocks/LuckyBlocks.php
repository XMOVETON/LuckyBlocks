<?php

namespace felony13twelve\luckyblocks;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use felony13twelve\luckyblocks\command\LBCommand;
use felony13twelve\luckyblocks\Game\LuckyGame;
use felony13twelve\luckyblocks\Scheduler\LuckyTask;

/**
 * Class LuckyBlocks
 * @package felony13twelve\luckyblocks
 *
 * @author  <felony13twelve@gmail.com> <Tg:@felony13twelve>
 * @version 1.0.0
 */
class LuckyBlocks extends PluginBase {

    const KEY_ALGO = "haval160,4";

    /** @var Config $config */
    private $config = null;

    /** @var LuckyGame */
    public $game;

    /** @var LuckyBlocks $instance */
    private static $instance;

    public function onLoad () {
        self::$instance = & $this;
    }

    public function onEnable () {
        $f = $this->getDataFolder();
        $this->initResource();

        $this->saveResource('config.yml');
        if (!(isset($this->config))) {
            $this->config = (new Config($f . 'config.yml', Config::YAML));
        }

        // random key generator
        $ctx = hash_init(self::KEY_ALGO);
        hash_update($ctx, rand(0, getrandmax()));

        $this->getServer()->getScheduler()->scheduleRepeatingTask(new LuckyTask($this), 20 * $this->getConfig()['Time']);

        $this->getServer()->getCommandMap()->register(hash_final($ctx), new LBCommand('lb'));
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler(), $this);

        $this->game = new LuckyGame;
    }

    private function initResource () {
        if (!(is_dir($this->getDataFolder()))) {
            @mkdir($this->getDataFolder());
        }
    }

    /**
     * @param array $pos
     * @return void
     */
    public function setPosition (array $pos) {
        $data = $this->getConfig();

        if (isset($data['Position'])) {
            $config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
            $config->set('Position', array(
                'Pos1' => array(
                    'x' => $pos['Pos1']['x'],
                    'y' => $pos['Pos1']['y'],
                    'z' => $pos['Pos1']['z']
                ),
                'Pos2' => array(
                    'x' => $pos['Pos2']['x'],
                    'y' => $pos['Pos2']['y'],
                    'z' => $pos['Pos2']['z']
                )
            ));
            $config->save();
        }
        return null;
    }

    /**
     * @param string $key
     * @param array $args
     * @return string
     */
    public function getMessage ($key, $args = []) {
        $message = "";
        foreach ($this->getConfig()['LuckyBlocks'] as $key => $value) {
            $message = $value['message'];
            foreach ($args as $arg => $value) {
                $message = str_replace('{' . $arg . '}', $value, $message);
            }
        }
        return $message;
    }

    /**
     * @return array
     */
    public function getConfig () {
        return $this->config->getAll();
    }

    /**
     * @return LuckyBlocks
     */
    public static function getInstance () : LuckyBlocks {
        return self::$instance;
    }
}