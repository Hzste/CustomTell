<?php

namespace blood;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ReplyCommand extends Command {

	public function __construct() {
        parent::__construct("reply", "Reply to a private message", "/reply <message>", ["r"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if(!isset($args[0])){
			$sender->sendMessage(CustomTell::getInstance()->getMessage('reply-usage'));
			return false;
		}
		$lastSend = CustomTell::getInstance()->getLastSend($sender->getName());
		if($lastSend === null) {
            $sender->sendMessage(CustomTell::getInstance()->getMessage('empty-reply'));
            return false;
        }
		$server = CustomTell::getInstance()->getServer();
		$lastSendPlayer = $server->getPlayerByPrefix($lastSend);
		if($lastSendPlayer === null || !$lastSendPlayer instanceof CommandSender){
			$sender->sendMessage(CustomTell::getInstance()->getMessage('invalid-player'));
            return false;
        }
		$message = implode(" ", $args);
		$senderName = $sender->getName();
		$playerName = $lastSendPlayer->getName();
		if(CustomTell::getInstance()->getMessage('use-you')){
			$senderName = "You";
			$playerName = "You";
		}
		$sender->sendMessage(str_replace(["{sender}", "{receiver}", "{msg}"], [$senderName, $lastSendPlayer->getName(), $message], CustomTell::getInstance()->getMessage('message-sent')));
		$lastSendPlayer->sendMessage(str_replace(["{sender}", "{receiver}", "{msg}"], [$sender->getName(), $playerName, $message], CustomTell::getInstance()->getMessage('message-sent')));
        return true;
    }
}