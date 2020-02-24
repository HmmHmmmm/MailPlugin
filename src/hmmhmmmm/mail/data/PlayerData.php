<?php

namespace hmmhmmmm\mail\data;

use hmmhmmmm\mail\Mail;

use pocketmine\utils\Config;

class PlayerData{
   private $plugin;
   private $name = "Steve";

   public function __construct(Mail $plugin, string $name){
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
      $data->setNested("mail.players", []);
      $data->save();     
   }
   public function getConfig(): Config{
      $playerName = strtolower($this->getName());
      $path = $this->getPlugin()->getDataFolder()."account/$playerName.yml";
      if(file_exists($path)){
         $config = new Config($path, Config::YAML, array());
         return $config;
      }
      return null;
   }
   public function update(): void{
      $playerName = strtolower($this->getName());
      $data = new Config($this->getPlugin()->getDataFolder()."account/$playerName.yml", Config::YAML, array()); 
      $playerData = $data->getAll();
      if(!isset($playerData["mail"]["players"])){
         $playerData["mail"]["players"] = [];
      }
      $data->setAll($playerData);
      $data->save();
   }
}