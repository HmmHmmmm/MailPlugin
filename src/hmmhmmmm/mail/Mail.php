<?php

namespace hmmhmmmm\mail;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

class Mail extends PluginBase implements Listener{
   private static $instance = null;
   private $prefix = "?";
   private $facebook = "§cไม่มี";
   private $youtube = "§cไม่มี";
   private $form = null;
   private $formapi = null;
   public $array = [];

   public static function getInstance(){
      return self::$instance;
   }
   public function onLoad(){
      self::$instance = $this;
   } 
   public function onEnable(){
      @mkdir($this->getDataFolder());
      @mkdir($this->getDataFolder()."account/");
      $this->saveDefaultConfig();
      $this->prefix = "Mail";
      $this->facebook = "https://m.facebook.com/phonlakrit.knaongam.1";
      $this->youtube = "https://m.youtube.com/channel/UCtjvLXDxDAUt-8CXV1eWevA";
      $this->form = new Form($this);
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      $this->getScheduler()->scheduleRepeatingTask(new MailTask($this), 20);
      $cmd = [
         new MailCommand($this),
         new ReportCommand($this)
      ];
      foreach($cmd as $command){
         $this->getServer()->getCommandMap()->register($command->getName(), $command);
      }
      if($this->getServer()->getPluginManager()->getPlugin("FormAPI") === null){
         $this->getLogger()->critical("§cปลั๊กนี้จะไม่ทำงาน กรุณาลงปลั๊กอิน FormAPI");
         $this->getServer()->getPluginManager()->disablePlugin($this);
      }else{
         $this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
      }
      $this->getServer()->getLogger()->info($this->getPluginInfo());
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
   public function getPluginInfo(): string{
      $author $this->getDescription()->getAuthors();
      $text = "\n".$this->getPrefix()." ชื่อปลั๊กอิน ".$this->getDescription()->getName()."\n".$this->getPrefix()." เวอร์ชั่น ".$this->getDescription()->getVersion()."\n".$this->getPrefix()." รายชื่อผู้สร้าง ".implode(", ", $author)."\n".$this->getPrefix()." คำอธิบายของปลั๊กอิน: ปลั๊กอินนี้ทำแจก โปรดอย่าเอาไปขาย *หากจะเอาไปแจกต่อโปรดให้เครดิตด้วย*\n".$this->getPrefix()." เฟสบุ๊ค ".$this->getFacebook()."\n".$this->getPrefix()." ยูทูป ".$this->getYoutube()."\n".$this->getPrefix()." เว็บไซต์ ".$this->getDescription()->getWebsite();
      return $text;   }
   public function getForm(): Form{
      return $this->form;
   }
   public function getFormAPI(): Plugin{
      return $this->formapi;
   }
   public function getPlayerData(string $name): PlayerData{
      return new PlayerData($this, $name);
   }
   public function onPlayerLogin(PlayerLoginEvent $event){
      $player = $event->getPlayer();
      $playerData = $this->getPlayerData($player->getName());
      if(!$playerData->isData()){
         $playerData->register();
      }else{
         $playerData->update();
      }
   }
   public function onPlayerChat(PlayerChatEvent $event){
      $player = $event->getPlayer();
      $message = $event->getMessage();
      if(isset($this->array[$player->getName()])){
         $event->setCancelled(true);                  
         $this->addMail($this->array[$player->getName()], $player, $message);
         unset($this->array[$player->getName()]);
      }
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
   public function setMailRead(string $name, string $senderName, int $msgCount, string $message = "§cยังไม่อ่าน"): void{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig();       
      $data->setNested("mail.message.".$senderName.".write.".$msgCount.".read", $message);
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
      $message1 = "§fจาก [".$groupSender."§f] §e".$senderName." §fวันที่/เวลา §a".date("d/m/Y H:i:s")."\n§fได้เขียนว่า §b".$message;
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
      $this->setMailRead($name, $senderName, $msgCount, "§cยังไม่อ่าน");
      $this->setMailMsg($name, $senderName, $msgCount, $message1);
      $count = $this->getCountMail($name) + 1;
      $this->setCountMail($name, $count);
      $this->setMailPlayers($senderName, $name);
      $player = $this->getServer()->getPlayer($name); 
      $sender->sendMessage($this->getPrefix()." §aได้ส่งข้อความให้กับ ".$name." แล้ว คุณได้เขียนข้อความว่า ".$message);
      if($player instanceof Player){
         if($tip){
            $player->sendMessage($this->getPrefix()." คุณมี §a1§fข้อความใหม่! จากผู้เล่นชื่อ §e".$senderName." §fพิม /mail read ".$senderName." อ่านดูสิ!"); 
            $player->addTitle(("§fคุณมี §a1§fข้อความใหม่!"), ("§fจากผู้เล่นชื่อ §e".$senderName." §fพิม /mail อ่านดูสิ!"));
         }
      }
   }     
   public function readMail(string $name, string $senderName, int $msgCount): string{
      return "§fหมายเลขข้อความที่ §b".$msgCount." ".$this->getMailRead($name, $senderName, $msgCount)."\n".$this->getMailMsg($name, $senderName, $msgCount)."\n§f-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-";
   }
   public function listMail(string $name, string $senderName): string{
      return "§f".$senderName." §f(§a".$this->getCountMailSender($name, $senderName)."§f) ข้อความ";
   }
   public function countMail(string $name): string{
      return $this->getPrefix()." คุณมี (§a".$this->getCountMail($name)."§f) ข้อความจากทั้งหมด พิม /mail อ่านดูสิ!";
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
         $player->sendMessage("§cไม่พบข้อความของ ".$senderName);
         return;
      }
      if(!$this->isCountMailSenderWrite($player->getName(), $senderName, $msgCount)){
         $player->sendMessage("§cไม่พบหมายเลขข้อความ ".$msgCount);
         return;
      }
      $this->removeCountMailSender($player->getName(), $senderName);
      $this->removeMailSender($player->getName(), $senderName, $msgCount);
      $player->sendMessage($this->getPrefix()." §aได้ลบข้อความของ ".$senderName." หมายเลขข้อความ ".$msgCount." สำเร็จ");
   }
   public function resetMail(string $name): void{
      $playerData = $this->getPlayerData($name);
      $data = $playerData->getConfig();       
      $data->setNested("mail.count", 0);
      $data->setNested("mail.message", []);
      $data->save();
   }
}