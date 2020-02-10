<?php

namespace hmmhmmmm\mail;

use pocketmine\scheduler\Task;

class MailTask extends Task{
   private $plugin;
   public function __construct(Mail $plugin){
      $this->plugin = $plugin;
   }
   public function getPlugin(): Mail{
      return $this->plugin;
   }
   public function onRun($currentTick){
      foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $player){
         if((Time() % 180) == 0){
            $player->sendMessage($this->getPlugin()->countMail($player->getName()));
         }
      }
   }
}