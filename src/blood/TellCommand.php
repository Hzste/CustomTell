<?php

namespace blood;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class TellCommand extends Command {

	public function __construct() {
        parent::__construct("tell", "Send a private message to a player", "/tell <player> <message>", ["w", "msg"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if(!isset($args[1])){
			$sender->sendMessage(CustomTell::getInstance()->getMessage('tell-usage'));
			return false;
		}
		$name = array_shift($args);
		$player = CustomTell::getInstance()->getServer()->getPlayerByPrefix($name);
		if($player === $sender){
			$sender->sendMessage("You cannot send a private message to yourself!");
			return false;
		}
		if(!$player){
			$sender->sendMessage(CustomTell::getInstance()->getMessage('invalid-player'));
			return false;
		}
		$message = implode(" ", $args);
		$senderName = $sender->getName();
		$playerName = $player->getName();
		if(CustomTell::getInstance()->getMessage('use-you')){
			$senderName = "You";
			$playerName = "You";
		}
		$sender->sendMessage(str_replace(["{sender}", "{receiver}", "{msg}"], [$senderName, $player->getName(), $message], CustomTell::getInstance()->getMessage('message-sent')));
		$player->sendMessage(str_replace(["{sender}", "{receiver}", "{msg}"], [$sender->getName(), $playerName, $message], CustomTell::getInstance()->getMessage('message-sent')));
		CustomTell::getInstance()->setLastSend($sender->getName(), $player->getName());
        return true;
    }
}