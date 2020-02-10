<?php

namespace hmmhmmmm\mail;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

class MailCommand extends Command implements PluginIdentifiableCommand{
   private $plugin;
   public function __construct(Mail $plugin){
      parent::__construct("mail");
      $this->plugin = $plugin;
      $this->setPermission("mail.command.mail");
   }
   public function getPlugin(): Plugin{
      return $this->plugin;
   }
   public function sendConsoleError(CommandSender $sender): void{
      $sender->sendMessage("§cขออภัย: คำสั่งสามารถพิมพ์ได้เฉพาะในเกมส์");
   }
   public function sendPermissionError(CommandSender $sender): void{
      $sender->sendMessage("§cขออภัย: คุณไม่สามารถพิมพ์คำสั่งนี้ได้");
   }
   public function getPrefix(): string{
      return $this->getPlugin()->getPrefix();
   }
   public function sendHelp(CommandSender $sender): void{
      $sender->sendMessage($this->getPrefix()." : §fCommand");           
      $sender->sendMessage("§a/mail write <ชื่อผู้เล่น> : §fแล้วพิมที่แชทเขียนข้อความเพื่อส่งข้อความให้ผู้เล่นคนนั้น");
      $sender->sendMessage("§a/mail read <ชื่อผู้ที่ส่งข้อความ> : §fอ่านข้อความผู้ที่ส่งมา");
      $sender->sendMessage("§a/mail read-all : §fอ่านข้อความผู้ที่ส่งมาทั้งหมด");
      $sender->sendMessage("§a/mail clear <ชื่อผู้ที่ส่งข้อความ> <หมายเลขข้อความ> : §fเพื่อลบข้อความนั้น");
      $sender->sendMessage("§a/mail clear-all : §fเพื่อลบข้อความของผู้ที่ส่งมาทั้งหมด");
      $sender->sendMessage("§a/mail see <ชื่อผู้เล่น> : §fเพื่อดูข้อความที่เราส่งไปว่าเค้าอ่านรึยัง?");
      $sender->sendMessage("§fคุณมี (§a".$this->getPlugin()->getCountMail($sender->getName())."§f) ข้อความ");
      $sender->sendMessage("§eรายชื่อ ผู้ที่ส่งข้อความ มาหาคุณ:");
      if($this->getPlugin()->getMailSenderCount($sender->getName()) == 0){
         $sender->sendMessage("§cไม่มี");
      }else{
         foreach($this->getPlugin()->getMailSender($sender->getName()) as $playerName){
            $sender->sendMessage($this->getPlugin()->listMail($sender->getName(), $playerName));
         }
      }
   }
   public function execute(CommandSender $sender, $commandLabel, array $args){
      if(!$this->testPermission($sender)){
         return true;
      }
      if(!$sender instanceof Player){
         $this->sendConsoleError($sender);
         return true;
      }
      if(empty($args)){
         $this->sendHelp($sender);
         return true;
      }
      $sub = array_shift($args);            
      if(isset($sub)){
         switch($sub){
            case "write":
               if(count($args) < 1){
                  $sender->sendMessage("§cลอง: /mail write <ชื่อผู้เล่น>");
                  return true;
               }
               $name = array_shift($args);
               $playerData = $this->getPlugin()->getPlayerData($name);
               if(!$playerData->isData()){
                  $sender->sendMessage($this->getPrefix()." §cไม่พบชื่อผู้เล่น");
                  return true;
               }
               $this->getPlugin()->array[$sender->getName()] = $playerData->getName();
               $sender->sendMessage($this->getPrefix().": §aกรุณาพิมพ์ที่แชทเพื่อเขียนข้อความ");
               break;
            case "read":
               if(count($args) < 1){
                  $sender->sendMessage("§cลอง: /mail read <ชื่อผู้ที่ส่งข้อความ>");
                  return true;
               }
               if($this->getPlugin()->getMailSenderCount($sender->getName()) == 0){
                  $sender->sendMessage("§cขออภัย: ยังไม่มีใครส่งข้อความมาหาคุณ");
                  return true;
               }
               $playerName = array_shift($args);
               $playerName = strtolower($playerName);
               if(!$this->getPlugin()->isMailSender($sender->getName(), $playerName)){
                  $sender->sendMessage("§cขออภัย: ไม่พบชื่อผู้ที่ส่งข้อความ");
                  return true;
               }
               $count = $this->getPlugin()->getCountMail($sender->getName()) - $this->getPlugin()->getCountMailSender($sender->getName(), $playerName);
               $this->getPlugin()->setCountMail($sender->getName(), $count);
               $this->getPlugin()->setCountMailSender($sender->getName(), $playerName, 0);
               foreach($this->getPlugin()->getMailSenderWrite($sender->getName(), $playerName) as $msgCount2){
                  $this->getPlugin()->setMailRead($sender->getName(), $playerName, $msgCount2, "§aอ่านแล้ว");
               }
               foreach($this->getPlugin()->getMailSenderWrite($sender->getName(), $playerName) as $msgCount2){
                  $sender->sendMessage($this->getPlugin()->readMail($sender->getName(), $playerName, $msgCount2));
               }
               $player = $this->getPlugin()->getServer()->getPlayer($playerName);
               if($player instanceof Player){
                  $player->sendMessage($this->getPrefix()." §b".$sender->getName()." §fได้อ่านข้อความของคุณแล้ว!");
               }
               break;
            case "read-all":
               if($this->getPlugin()->getMailSenderCount($sender->getName()) == 0){
                  $sender->sendMessage("§cขออภัย: ยังไม่มีใครส่งข้อความมาหาคุณ");
                  return true;
               }
               foreach($this->getPlugin()->getMailSender($sender->getName()) as $playerName){
                  if(!$this->getPlugin()->isMailSender($sender->getName(), $playerName)){
                     $sender->sendMessage("§cขออภัย: ไม่พบชื่อผู้ที่ส่งข้อความ");
                     return true;
                  }
                  $count = $this->getPlugin()->getCountMail($sender->getName()) - $this->getPlugin()->getCountMailSender($sender->getName(), $playerName);
                  $this->getPlugin()->setCountMail($sender->getName(), $count);
                  $this->getPlugin()->setCountMailSender($sender->getName(), $playerName, 0);
                  foreach($this->getPlugin()->getMailSenderWrite($sender->getName(), $playerName) as $msgCount2){
                     $this->getPlugin()->setMailRead($sender->getName(), $playerName, $msgCount2, "§aอ่านแล้ว");
                  }
                  foreach($this->getPlugin()->getMailSenderWrite($sender->getName(), $playerName) as $msgCount2){
                     $sender->sendMessage($this->getPlugin()->readMail($sender->getName(), $playerName, $msgCount2));
                  }
                  $player = $this->getPlugin()->getServer()->getPlayer($playerName);
                  if($player instanceof Player){
                     $player->sendMessage($this->getPrefix()." §b".$sender->getName()." §fได้อ่านข้อความของคุณแล้ว!");
                  }
               }
               break;
            case "clear":                        
               if(count($args) < 2){
                  $sender->sendMessage("§cลอง: /mail clear <ชื่อผู้ที่ส่งข้อความ> <หมายเลขข้อความ>");
                  return true;
               }
               $name = array_shift($args);                            
               $msgCount = array_shift($args);
               if(!is_numeric($msgCount)){
                  $sender->sendMessage("§cลอง: /mail clear <ชื่อผู้ที่ส่งข้อความ> <หมายเลขข้อความ>");
                  return true;
               }
               $this->getPlugin()->delMailSender($sender, strtolower($name), $msgCount);
               break;       
            case "clear-all":                        
               $this->getPlugin()->resetMail($sender->getName());
               $sender->sendMessage($this->getPrefix()." §aลบข้อความของผู้ที่ส่งมาทั้งหมดเรียบร้อย!");
               break;
            case "see":
               if(count($args) < 1){
                  $sender->sendMessage("§cลอง: /mail see <ชื่อผู้เล่น>");
                  return true;
               }
               $name = array_shift($args);
               $playerData = $this->getPlugin()->getPlayerData($name);
               if(!$playerData->isData()){
                  $sender->sendMessage($this->getPrefix()." §cไม่พบชื่อผู้เล่น");
                  return true;
               }
               if(!$this->getPlugin()->isMailSender($playerData->getName(), strtolower($sender->getName()))){
                  $sender->sendMessage("§cขออภัย: ไม่พบข้อความของคุณ? คุณไม่ได้ส่งข้อความไปหาคนนี้ หรือ เค้าลบข้อความของคุณไปแล้ว");
                  return true;
               }
               foreach($this->getPlugin()->getMailSenderWrite($playerData->getName(), strtolower($sender->getName())) as $msgCount2){
                  $sender->sendMessage($this->getPlugin()->readMail($playerData->getName(), strtolower($sender->getName()), $msgCount2));
               }
               break;
            default:
               $this->sendHelp($sender);
               break;
         }
      }
      return true;
   }
}