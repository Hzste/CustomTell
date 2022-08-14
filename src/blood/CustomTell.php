<?php

declare(strict_types=1);

namespace blood;

use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;

class CustomTell extends PluginBase {
	
	private static $instance;
	private Config $config;

	public $lastSend = [];

	protected function onLoad(): void {
        self::$instance = $this;
    }

	public function onEnable() : void {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
		$this->config = $this->getConfig();
		$this->getLogger()->info("§aCustomTell has been enabled!");
		$commandMap = $this->getServer()->getCommandMap();
        $commandMap->unregister($commandMap->getCommand("tell"));
		$commandMap->register("tell", new TellCommand());
		$commandMap->register("reply", new ReplyCommand());
	}
	
	public function onDisable() : void {
		$this->getLogger()->info("§cCustomTell has been disabled!");
	}

	public static function getInstance(): self {
        return self::$instance;
    }

	public function getMessage($messageId){
		return $this->config->get($messageId);
	}

	public function setLastSend(string $sender, string $target){
        $this->lastSend[$target] = $sender;
    }

    public function getLastSend(string $name){
        if(isset($this->lastSend[$name])){
            return $this->lastSend[$name];
        }else{
            return null;
        }
    }
}