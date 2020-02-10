<?php

namespace hmmhmmmm\mail;

use pocketmine\Player;
use pocketmine\utils\Config;

class PlayerData{

   public function __construct(Mail $plugin, $name){
      $this->plugin = $plugin;         
      $this->name = $name;
   }
   public function getPlugin(): Mail{
      return $this->plugin;
   }
   public function getName(): string{
      return $this->name;
   }
   public function isData(): bool{
      $playerName = strtolower($this->getName());
      return file_exists($this->getPlugin()->getDataFolder()."account/$playerName.yml");
   }
   public function register(): void{
      $playerName = strtolower($this->getName());
      $data = new Config($this->getPlugin()->getDataFolder()."account/$playerName.yml", Config::YAML, array()); 
      $data->setNested("mail.count", 0);
      $data->setNested("mail.message", []);
      $data->save();     
   }
   public function getConfig(){
      $playerName = strtolower($this->getName());
      $path = $this->getPlugin()->getDataFolder()."account/$playerName.yml";
      if(file_exists($path)){
         $config = new Config($path, Config::YAML, array());
         return $config;
      }
      return null;
   }
}