<?php

namespace hmmhmmmm\mail;

use hmmhmmmm\mail\cmd\MailCommand;
use hmmhmmmm\mail\cmd\ReportCommand;
use hmmhmmmm\mail\data\Language;
use hmmhmmmm\mail\data\PlayerData;
use hmmhmmmm\mail\listener\EventListener;
use hmmhmmmm\mail\scheduler\MailTask;
use hmmhmmmm\mail\ui\Form;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

class Mail extends PluginBase implements MailAPI{
   private static $instance = null;
   private $prefix = "?";
   private $facebook = "§cไม่มี";
   private $youtube = "§cไม่มี";
   private $discord = "§cไม่มี";
   private $language = null;
   private $form = null;
   private $formapi = null;
   public $array = [];
   
   public $langClass = [
      "thai",
      "english"
   ];
   
   public static function getInstance(): Mail{
      return self::$instance;
   }
   public function onLoad(){
      self::$instance = $this;
   } 
   
   public function onEnable(){
      @mkdir($this->getDataFolder());
      @mkdir($this->getDataFolder()."account/");
      @mkdir($this->getDataFolder()."language/");
      $this->saveDefaultConfig();
      $this->prefix = "Mail";
      $this->facebook = "https://bit.ly/39ULjqk";
      $this->youtube = "https://bit.ly/2HL1j28";
      $this->discord = "https://discord.gg/n6CmNr";
      $this->form = new Form($this);
      $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
      $this->getScheduler()->scheduleRepeatingTask(new MailTask($this), 20);
      $cmd = [
         new MailCommand($this),
         new ReportCommand($this)
      ];
      foreach($cmd as $command){
         $this->getServer()->getCommandMap()->register($command->getName(), $command);
      }
      $langConfig = $this->getConfig()->getNested("language");
      if(!in_array($langConfig, $this->langClass)){
         $this->getLogger()->error("§cNot found language ".$langConfig.", Please try ".implode(", ", $this->langClass));
         $this->getServer()->getPluginManager()->disablePlugin($this);
      }else{
         $this->language = new Language($this, $langConfig);
      }
      if($this->getServer()->getPluginManager()->getPlugin("FormAPI") === null){
         $this->getLogger()->critical($this->language->getTranslate("notfound.plugin", ["FormAPI"]));
         $this->getServer()->getPluginManager()->disablePlugin($this);
      }else{
         $this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
      }
   }
   public function getPrefix(): string{
      return "§e[§d".$this->prefix."§e]§f";
   }
   public function getFacebook(): string{
      return $this->facebook;
   }
   public function getYoutube(): string{
      return $this->youtube;
   }
   public function getDiscord(): string{
      return $this->discord;
   }
   public function getLanguage(): Language{
      return $this->language;
   }
   public function getForm(): Form{
      return $this->form;
   }
   public function getFormAPI(): Plugin{
      return $this->formapi;
   }
   public function getPlayerData(string $name): PlayerData{
      return new PlayerData($this, $name);
   }
   public function getPluginInfo(): string{
      $author = implode(", ", $this->getDescription()->getAuthors());
      $arrayText = [
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.name", [$this->getDescription()->getName()]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.version", [$this->getDescription()->getVersion()]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.author", [$author]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.description"),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.facebook", [$this->getFacebook()]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.youtube", [$this->getYoutube()]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.website", [$this->getDescription()->getWebsite()]),
         $this->getPrefix()." ".$this->getLanguage()->getTranslate("plugininfo.discord", [$this->getDiscord()]),
      ];
      return implode("\n", $arrayText);
   }
   public function isMail(string $name): bool{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();       
      return isset($data["mail"]);        
   }
   public function getMailSenderCount(string $name): int{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();
      return count($data["mail"]["message"]);
   }
   public function getMailSender(string $name): array{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();
      return array_keys($data["mail"]["message"]);
   }
   public function isMailSender(string $name, string $senderName): bool{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();       
      return isset($data["mail"]["message"][$senderName]);
   }
   
   public function setCountMail(string $name, int $count): void{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig();      
      $data->setNested("mail.count", $count);
      $data->save();
   }
   public function getCountMail(string $name): int{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();      
      return $data["mail"]["count"];
   }
   public function isCountMailSender(string $name, string $senderName): bool{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();       
      return isset($data["mail"]["message"][$senderName]["count"]);           }     
   public function setCountMailSender(string $name, string $senderName, int $count): void{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig();      
      $data->setNested("mail.message.".$senderName.".count", $count);
      $data->save();
   }
   public function getCountMailSender(string $name, string $senderName): int{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();       
      return $data["mail"]["message"][$senderName]["count"];
   }
   public function getCountMailSenderWrite(string $name, string $senderName): int{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();
      return count($data["mail"]["message"][$senderName]["write"]);
   }
   public function getMailSenderWrite(string $name, string $senderName): array{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();
      return array_keys($data["mail"]["message"][$senderName]["write"]);     
   }
   public function isCountMailSenderWrite(string $name, string $senderName, int $msgCount): bool{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();
      return isset($data["mail"]["message"][$senderName]["write"][$msgCount]);
   }     
   public function getMailRead(string $name, string $senderName, int $msgCount): string{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();       
      return $data["mail"]["message"][$senderName]["write"][$msgCount]["read"];
   }
   public function setMailRead(string $name, string $senderName, int $msgCount, bool $read = false): void{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig();
      if($read){
         if($this->getLanguage()->getLang() == "thai"){
            $data->setNested("mail.message.".$senderName.".write.".$msgCount.".read", "§aอ่านแล้ว");
         }
         if($this->getLanguage()->getLang() == "english"){
            $data->setNested("mail.message.".$senderName.".write.".$msgCount.".read", "§aRead already");
         }
      }else{
         if($this->getLanguage()->getLang() == "thai"){
            $data->setNested("mail.message.".$senderName.".write.".$msgCount.".read", "§cยังไม่อ่าน");
         }
         if($this->getLanguage()->getLang() == "english"){
            $data->setNested("mail.message.".$senderName.".write.".$msgCount.".read", "§cNot yet read");
         }
      }
      $data->save();
   }
   public function getMailMsg(string $name, string $senderName, int $msgCount): string{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();       
      return $data["mail"]["message"][$senderName]["write"][$msgCount]["msg"];
   }
   public function setMailMsg(string $name, string $senderName, int $msgCount, string $message): void{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig();       
      $data->setNested("mail.message.".$senderName.".write.".$msgCount.".msg", $message);
      $data->save();
   }
   public function getCountMailPlayers(string $name): int{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();
      return count($data["mail"]["players"]);
   }
   public function getMailPlayers(string $name): array{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();
      return $data["mail"]["players"];
   }
   public function setMailPlayers(string $name, string $senderName): void{
      $playerName = strtolower($name);
      $path = $this->getDataFolder()."account/$playerName.yml";
      $config = new Config($path, Config::YAML, array());
      $data = $config->getAll();
      $senderName = strtolower($senderName);
      if(!(in_array($senderName, $data["mail"]["players"]))){
         $data["mail"]["players"][] = $senderName;
      }
      $config->setAll($data);
      $config->save();
   }
   public function addMail(string $name, Player $sender, string $message, bool $tip = true): void{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig()->getAll();       
      $senderName = strtolower($sender->getName());
      if(!$sender instanceof Player){
         $groupSender = "§4Owner";
      }else{
         if($sender->isOp()){
            $groupSender = "§2Admin";
         }else{
            $groupSender = "§ePlayer";
         }
      }
      $message1 = $this->getLanguage()->getTranslate("addmail.message1", [$groupSender, $senderName, date("d/m/Y H:i:s"), $message]);
      if($this->isMailSender($name, $senderName)){
         $msgWrite = $this->getMailSenderWrite($name, $senderName); 
         $msgCount = $this->getCountMailSenderWrite($name, $senderName) + 1;
         for($i = 0; $i < $this->getCountMailSenderWrite($name, $senderName); $i++){//อันนี้แม่งง่ายแต่คิดอยู่นาน
            if($this->isCountMailSenderWrite($name, $senderName, $msgCount)){
               $msgCount++;
            }
         }
      }else{
         $msgCount = 1;
      }
      if($this->isCountMailSender($name, $senderName)){
         $count = $this->getCountMailSender($name, $senderName) + 1;
      }else{
         $count = 1;
      }
      $this->setCountMailSender($name, $senderName, $count);
      $this->setMailRead($name, $senderName, $msgCount, false);
      $this->setMailMsg($name, $senderName, $msgCount, $message1);
      $count = $this->getCountMail($name) + 1;
      $this->setCountMail($name, $count);
      $this->setMailPlayers($senderName, $name);
      $player = $this->getServer()->getPlayer($name); 
      $sender->sendMessage($this->getPrefix()." ".$this->getLanguage()->getTranslate("addmail.message2", [$name, $message]));
      if($player instanceof Player){
         if($tip){
            $player->sendMessage($this->getPrefix()." ".$this->getLanguage()->getTranslate("addmail.message3", [$senderName, $senderName]));
            $player->addTitle(($this->getLanguage()->getTranslate("addmail.titleline1")), ($this->getLanguage()->getTranslate("addmail.titleline2", [$senderName])));
         }
      }
   }     
   public function readMail(string $name, string $senderName, int $msgCount): string{
      return $this->getLanguage()->getTranslate("readmail", [$msgCount, $this->getMailRead($name, $senderName, $msgCount), $this->getMailMsg($name, $senderName, $msgCount)]);
   }
   public function listMail(string $name, string $senderName): string{
      return $this->getLanguage()->getTranslate("listmail", [$senderName, $this->getCountMailSender($name, $senderName)]);
   }
   public function countMail(string $name): string{
      return $this->getPrefix()." ".$this->getLanguage()->getTranslate("countmail", [$this->getCountMail($name)]);
   }
   public function removeCountMailSender(string $name, string $senderName): void{
      if(!($this->getCountMailSender($name, $senderName) == 0)){
         $count = $this->getCountMailSender($name, $senderName) - 1;
         $this->setCountMailSender($name, $senderName, $count);
         $count = $this->getCountMail($name) - 1;
         $this->setCountMail($name, $count);
      }
   }
   public function removeMailSender(string $name, string $senderName, int $msgCount): void{
      $playerName = strtolower($name);
      $path = $this->getDataFolder()."account/$playerName.yml";
      $config = new Config($path, Config::YAML, array());
      $data = $config->getAll();       
      unset($data["mail"]["message"][$senderName]["write"][$msgCount]);
      $config->setAll($data);       
      $config->save();       
   }
   public function delMailSender(Player $player, string $senderName, int $msgCount): void{
      if(!$this->isMailSender($player->getName(), $senderName)){
         $player->sendMessage($this->getPrefix()." ".$this->getLanguage()->getTranslate("mail.command.clear.error3", [$senderName]));
         return;
      }
      if(!$this->isCountMailSenderWrite($player->getName(), $senderName, $msgCount)){
         $player->sendMessage($this->getPrefix()." ".$this->getLanguage()->getTranslate("mail.command.clear.error4", [$msgCount]));
         return;
      }
      $this->removeCountMailSender($player->getName(), $senderName);
      $this->removeMailSender($player->getName(), $senderName, $msgCount);
      $player->sendMessage($this->getPrefix()." ".$this->getLanguage()->getTranslate("mail.command.clear.complete", [$senderName, $msgCount]));
   }
   public function resetMail(string $name): void{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig();       
      $data->setNested("mail.count", 0);
      $data->setNested("mail.message", []);
      $data->save();
   }
}