<?php

namespace hmmhmmmm\mail\listener;

use hmmhmmmm\mail\Mail;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener{
   private $plugin;
   
   public function __construct(Mail $plugin){
      $this->plugin = $plugin;
   }
   public function getPlugin(): Mail{
      return $this->plugin;
   }
   public function getPrefix(): string{
      return $this->getPlugin()->getPrefix();
   }
   public function onPlayerLogin(PlayerLoginEvent $event){
      $player = $event->getPlayer();
      $playerData = $this->getPlugin()->getPlayerData($player->getName());
      if(!$playerData->isData()){
         $playerData->register();
      }else{
         $playerData->update();
      }
   }
   public function onPlayerChat(PlayerChatEvent $event){
      $player = $event->getPlayer();
      $message = $event->getMessage();
      if(isset($this->getPlugin()->array[$player->getName()])){
         $event->setCancelled(true);
         $this->getPlugin()->addMail($this->getPlugin()->array[$player->getName()], $player, $message);
         unset($this->getPlugin()->array[$player->getName()]);
      }
   }}