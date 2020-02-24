<?php

namespace hmmhmmmm\mail\ui;

use hmmhmmmm\mail\Mail;
use hmmhmmmm\mail\libs\jojoe77777\FormAPI\CustomForm;
use hmmhmmmm\mail\libs\jojoe77777\FormAPI\ModalForm;
use hmmhmmmm\mail\libs\jojoe77777\FormAPI\SimpleForm;

use pocketmine\Player;

class Form{
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
   
   public function createCustomForm(?callable $function = null): CustomForm{
      return new CustomForm($function);
   }
   public function createSimpleForm(?callable $function = null): SimpleForm{
      return new SimpleForm($function);
   }
   public function createModalForm(?callable $function = null): ModalForm{
      return new ModalForm($function);
   }

   public function MailMenu(Player $player, string $content = ""): void{
      $form = $this->createSimpleForm(function ($player, $data){
         if(!($data === null)){
            if($data == 0){
               if(!$player->hasPermission("mail.command.write")){
                  $text = $this->getPlugin()->getLanguage()->getTranslate("form.menu.error1");
                  $this->MailMenu($player, $text);
                  return;
               }
               $this->MailWrite($player);
            }
            if($data == 1){
               if(!$player->hasPermission("mail.command.see")){
                  $text = $this->getPlugin()->getLanguage()->getTranslate("form.menu.error1");
                  $this->MailMenu($player, $text);
                  return;
               }
               if($this->getPlugin()->getCountMailPlayers($player->getName()) == 0){
                  $text = $this->getPlugin()->getLanguage()->getTranslate("form.menu.error2");
                  $this->MailMenu($player, $text);
                  return;
               }
               $this->MailSeeAll($player);
            }
            if($data == 2){
               if(!$player->hasPermission("mail.command.read") && !$player->hasPermission("mail.command.readall")){
                  $text = $this->getPlugin()->getLanguage()->getTranslate("form.menu.error1");
                  $this->MailMenu($player, $text);
                  return;
               }
               if($this->getPlugin()->getMailSenderCount($player->getName()) == 0){
                  $text = $this->getPlugin()->getLanguage()->getTranslate("form.menu.error3");
                  $this->MailMenu($player, $text);
                  return;
               }
               $this->MailReadAll($player);
            }
            if($data == 3){
               if(!$player->hasPermission("mail.command.clearall")){
                  $text = $this->getPlugin()->getLanguage()->getTranslate("form.menu.error1");
                  $this->MailMenu($player, $text);
                  return;
               }
               $this->MailClearAll($player);
            }
         }
      });
      $form->setTitle($this->getPrefix()." Menu");
      $form->setContent($content);
      $form->addButton($this->getPlugin()->getLanguage()->getTranslate("form.menu.button1"));
      $form->addButton($this->getPlugin()->getLanguage()->getTranslate("form.menu.button2"));
      $form->addButton($this->getPlugin()->getLanguage()->getTranslate("form.menu.button3", [$this->getPlugin()->getCountMail($player->getName())]));
      $form->addButton($this->getPlugin()->getLanguage()->getTranslate("form.menu.button4"));
      $form->sendToPlayer($player);
   }
   public function MailWrite(Player $player, string $content = ""): void{
      $form = $this->createCustomForm(function ($player, $data){
         if($data == null){
            return;
         }
         $name = explode(" ", $data[1]); 
         if($name[0] == null){
            $text = $this->getPlugin()->getLanguage()->getTranslate("form.write.error1");
            $this->MailWrite($player, $text);
            return;
         }
         $playerData = $this->getPlugin()->getPlayerData($name[0]);
         if(!$playerData->isData()){
            $text = $this->getPlugin()->getLanguage()->getTranslate("form.write.error2");
            $this->MailWrite($player, $text);
            return;
         }
         $message = explode(" ", $data[2]); 
         if($message[0] == null){
            $text = $this->getPlugin()->getLanguage()->getTranslate("form.write.error3");
            $this->MailWrite($player, $text);
            return;
         }
         $message = $data[2];
         $this->getPlugin()->addMail($playerData->getName(), $player, $message, false);
         $pOnline = $this->getPlugin()->getServer()->getPlayer($name[0]);
         if($pOnline instanceof Player){
            $this->MailAdd($pOnline, $player->getName());
         }
      });
      $form->setTitle($this->getPrefix()." Write");
      $form->addLabel($content);
      $form->addInput($this->getPlugin()->getLanguage()->getTranslate("form.write.input1"));
      $form->addInput($this->getPlugin()->getLanguage()->getTranslate("form.write.input2"));
      $form->sendToPlayer($player);
   }
   public function MailSeeMsg(Player $player, string $senderName, string $content = ""): void{
      $senderName = strtolower($senderName);
      $pOnline = $this->getPlugin()->getServer()->getPlayer($senderName);
      $form = $this->createCustomForm(function ($player, $data) use ($senderName, $content, $pOnline){
         if(!($data === null)){
            $message = explode(" ", $data[1]); 
            if($message[0] == null){
               $text = $this->getPlugin()->getLanguage()->getTranslate("form.seemsg.error1")."\n".$content;
               $this->MailSeeMsg($player, $senderName, $text);
               return;
            }
            $message = $data[1];
            $this->getPlugin()->addMail($senderName, $player, $message, false);
            if($pOnline instanceof Player){
               $this->MailAdd($pOnline, $player->getName());
            }
         }
         
      });
      if($pOnline instanceof Player){
         $online = $this->getPlugin()->getLanguage()->getTranslate("form.seemsg.online");
      }else{
         $online = $this->getPlugin()->getLanguage()->getTranslate("form.seemsg.offline");
      }
      $form->setTitle($this->getPlugin()->getLanguage()->getTranslate("form.seemsg.title", [$senderName, $online]));
      $form->addLabel($content);
      $form->addInput("", $this->getPlugin()->getLanguage()->getTranslate("form.seemsg.input1"));
      $form->sendToPlayer($player);
   }
   public function MailSeeAll(Player $player, string $content = ""): void{
       foreach($this->getPlugin()->getMailPlayers($player->getName()) as $senderName){
         $array[] = $senderName;
      }
      $form = $this->createSimpleForm(function ($player, $data) use ($array){
         if(!($data === null)){
            $name = $array[$data];
            if(!$this->getPlugin()->isMailSender($name, strtolower($player->getName()))){
               $text = $this->getPlugin()->getLanguage()->getTranslate("form.seeall.error1");
               $this->MailSeeAll($player, $text);
               return;
            }
            foreach($this->getPlugin()->getMailSenderWrite($name, strtolower($player->getName())) as $msgCount2){
               $array2[] = $this->getPlugin()->readMail($name, strtolower($player->getName()), $msgCount2);
            }
            $msg = implode("\n", $array2);
            $this->MailSeeMsg($player, $name, $msg);
         }
      });
      $form->setTitle($this->getPrefix()." SeeAll");
      $form->setContent($content);
      for($i = 0; $i < count($array); $i++){
         $form->addButton($array[$i]);
      }
      $form->sendToPlayer($player);
   }
   public function MailReadMsg(Player $player, string $senderName, string $content = ""): void{
      $senderName = strtolower($senderName);
      $pOnline = $this->getPlugin()->getServer()->getPlayer($senderName);
      $form = $this->createCustomForm(function ($player, $data) use ($senderName, $content, $pOnline){
         if(!($data === null)){
            if($data[1] == 0){
               $message = explode(" ", $data[2]); 
               if($message[0] == null){
                  $text = $this->getPlugin()->getLanguage()->getTranslate("form.readmsg.error1")."\n".$content;
                  $this->MailReadMsg($player, $senderName, $text);
                  return;
               }
               $message = $data[2];
               $this->getPlugin()->addMail($senderName, $player, $message, false);
               if($pOnline instanceof Player){
                  $this->MailAdd($pOnline, $player->getName());
               }
            }
            if($data[1] == 1){
               $msgCount = explode(" ", $data[2]); 
               if($msgCount[0] == null && !is_numeric($msgCount[0])){
                  $text = $this->getPlugin()->getLanguage()->getTranslate("form.readmsg.error2")."\n".$content;
                  $this->MailReadMsg($player, $senderName, $text);
                  return;
               }
               $msgCount = (int) $data[2];
               $this->getPlugin()->delMailSender($player, strtolower($senderName), $msgCount);
            }
         }
      });
      if($pOnline instanceof Player){
         $online = $this->getPlugin()->getLanguage()->getTranslate("form.readmsg.online");
         $pOnline->sendMessage($this->getPrefix()." ".$this->getPlugin()->getLanguage()->getTranslate("form.readmsg.complete", [$player->getName()]));
      }else{
         $online = $this->getPlugin()->getLanguage()->getTranslate("form.readmsg.offline");
      }
      $form->setTitle($this->getPlugin()->getLanguage()->getTranslate("form.readmsg.title", [$senderName, $online]));
      $form->addLabel($content);
      $form->addDropdown($this->getPlugin()->getLanguage()->getTranslate("form.readmsg.dropdown1.title"), [$this->getPlugin()->getLanguage()->getTranslate("form.readmsg.dropdown1.step1"), $this->getPlugin()->getLanguage()->getTranslate("form.readmsg.dropdown1.step2")]); 
      $form->addInput("");
      $form->sendToPlayer($player);
   }
   public function MailReadAll(Player $player, string $content = ""){
       foreach($this->getPlugin()->getMailSender($player->getName()) as $senderName){
         $array[] = $senderName;
      }
      $form = $this->createSimpleForm(function ($player, $data) use ($array){
         if(!($data === null)){
            $name = $array[$data];
            $count = $this->getPlugin()->getCountMail($player->getName()) - $this->getPlugin()->getCountMailSender($player->getName(), $name);
            $this->getPlugin()->setCountMail($player->getName(), $count);
            $this->getPlugin()->setCountMailSender($player->getName(), $name, 0);
            foreach($this->getPlugin()->getMailSenderWrite($player->getName(), $name) as $msgCount2){
               $this->getPlugin()->setMailRead($player->getName(), $name, $msgCount2, true);
            }
            foreach($this->getPlugin()->getMailSenderWrite($player->getName(), $name) as $msgCount2){
               $array2[] = $this->getPlugin()->readMail($player->getName(), $name, $msgCount2);
            }
            $msg = implode("\n", $array2);
            $this->MailReadMsg($player, $name, $msg);
         }
      });
      $form->setTitle($this->getPrefix()." ReadAll");
      $form->setContent($content);         
      for($i = 0; $i < count($array); $i++){
         $form->addButton($this->getPlugin()->listMail($player->getName(), $array[$i]));
      }
      $form->sendToPlayer($player);
   }
   public function MailClearAll(Player $player): void{
      $form = $this->createModalForm(function ($player, $data){
         if(!($data === null)){
            if($data == 1){//ปุ่ม1
               $this->getPlugin()->resetMail($player->getName());
               $player->sendMessage($this->getPrefix()." ".$this->getPlugin()->getLanguage()->getTranslate("form.clearall.complete"));
            }
            if($data == 0){//ปุ่ม2
            }
         }
      });
      $form->setTitle($this->getPrefix()." ClearAll");
      $text = $this->getPlugin()->getLanguage()->getTranslate("form.clearall.content");
      $form->setContent($text);
      $form->setButton1($this->getPlugin()->getLanguage()->getTranslate("form.clearall.button1")); 
      $form->setButton2($this->getPlugin()->getLanguage()->getTranslate("form.clearall.button2"));
      $form->sendToPlayer($player);
   }
   public function MailAdd(Player $player, string $senderName): void{
      $senderName = strtolower($senderName);
      $sender = $this->getPlugin()->getServer()->getPlayer($senderName);
      if(!$sender instanceof Player){
         $groupSender = "§4Owner";
      }else{
         if($sender->isOp()){
            $groupSender = "§2Admin";
         }else{
            $groupSender = "§ePlayer";
         }
      }
      $form = $this->createModalForm(function ($player, $data) use ($senderName){
         if(!($data === null)){
            if($data == 1){//ปุ่ม1
               $name = $senderName;
               $count = $this->getPlugin()->getCountMail($player->getName()) - $this->getPlugin()->getCountMailSender($player->getName(), $name);
               $this->getPlugin()->setCountMail($player->getName(), $count);
               $this->getPlugin()->setCountMailSender($player->getName(), $name, 0);
               foreach($this->getPlugin()->getMailSenderWrite($player->getName(), $name) as $msgCount2){
                  $this->getPlugin()->setMailRead($player->getName(), $name, $msgCount2, true);
               }
               foreach($this->getPlugin()->getMailSenderWrite($player->getName(), $name) as $msgCount2){
                  $array2[] = $this->getPlugin()->readMail($player->getName(), $name, $msgCount2);
               }
               $msg = implode("\n", $array2);
               $this->MailReadMsg($player, $name, $msg);
            }
            if($data == 0){//ปุ่ม2
            }
         }
      });
      $form->setTitle($this->getPrefix()." Add");
      $text = $this->getPrefix()." ".$this->getPlugin()->getLanguage()->getTranslate("form.add.content", [$groupSender, $sender->getName()]);
      $form->setContent($text);
      $form->setButton1($this->getPlugin()->getLanguage()->getTranslate("form.add.button1")); 
      $form->setButton2($this->getPlugin()->getLanguage()->getTranslate("form.add.button2"));
      $form->sendToPlayer($player);
   }
   public function Report(Player $player, string $content = ""): void{
      $senderName = $this->getPlugin()->getConfig()->getNested("report.name");
      $name = $senderName;
      $playerData = $this->getPlugin()->getPlayerData($name);
      if(!$playerData->isData()){
         $text = $this->getPlugin()->getLanguage()->getTranslate("form.report.error3", [$name]);
         $this->MessageUI($player, $text);
         return;
      }
      if($this->getPlugin()->isMailSender($name, strtolower($player->getName()))){
         foreach($this->getPlugin()->getMailSenderWrite($name, strtolower($player->getName())) as $msgCount2){
            $array2[] = $this->getPlugin()->readMail($name, strtolower($player->getName()), $msgCount2);
         }
         $msg = implode("\n", $array2);
         $content = $this->getPlugin()->getLanguage()->getTranslate("form.report.content")."\n§a[".$this->getPlugin()->getLanguage()->getTranslate("form.report.reportmsg")."]\n".$msg;
      }else{
         $content = $this->getPlugin()->getLanguage()->getTranslate("form.report.content");
      }
      $pOnline = $this->getPlugin()->getServer()->getPlayer($senderName);
      $form = $this->createCustomForm(function ($player, $data) use ($senderName, $content, $pOnline){
         if(!($data === null)){
            $senderName = strtolower($senderName);
            if($data[1] == 0){
               $message = explode(" ", $data[2]); 
               if($message[0] == null){
                  $text = $this->getPlugin()->getLanguage()->getTranslate("form.report.error1")."\n".$content;
                  $this->Report($player, $text);
                  return;
               }
               $message = $data[2];
               $this->getPlugin()->addMail($senderName, $player, $message, false);
               if($pOnline instanceof Player){
                  $this->MailAdd($pOnline, $player->getName());
               }
            }
            if($data[1] == 1){
               if(!$this->getPlugin()->isMailSender($player->getName(), $senderName)){
                  $text = $this->getPlugin()->getLanguage()->getTranslate("form.report.error2");
                  $this->Report($player, $text);
                  return;
               }
               $name = $senderName;
               $count = $this->getPlugin()->getCountMail($player->getName()) - $this->getPlugin()->getCountMailSender($player->getName(), $name);
               $this->getPlugin()->setCountMail($player->getName(), $count);
               $this->getPlugin()->setCountMailSender($player->getName(), $name, 0);
               foreach($this->getPlugin()->getMailSenderWrite($player->getName(), $name) as $msgCount2){
                  $this->getPlugin()->setMailRead($player->getName(), $name, $msgCount2, true);
               }
               foreach($this->getPlugin()->getMailSenderWrite($player->getName(), $name) as $msgCount2){
                  $array2[] = $this->getPlugin()->readMail($player->getName(), $name, $msgCount2);
               }
               $msg = implode("\n", $array2);
               $this->MailReadMsg($player, $name, $msg);
            }
         }
      });
      if($pOnline instanceof Player){
         $online = $this->getPlugin()->getLanguage()->getTranslate("form.report.online");
      }else{
         $online = $this->getPlugin()->getLanguage()->getTranslate("form.report.offline");
      }
      $form->setTitle($this->getPlugin()->getLanguage()->getTranslate("form.report.title", [$online]));
      $form->addLabel($content);
      $form->addDropdown($this->getPlugin()->getLanguage()->getTranslate("form.report.dropdown1.title"), [$this->getPlugin()->getLanguage()->getTranslate("form.report.dropdown1.step1"), $this->getPlugin()->getLanguage()->getTranslate("form.report.dropdown1.step2")]); 
      $form->addInput("");
      $form->sendToPlayer($player);
   }
   public function MessageUI(Player $player, string $content = ""): void{
      $form = $this->createSimpleForm(function ($player, $data){
         if($data === null){
            return;
         }
      });
      $form->setTitle($this->getPrefix()." Message UI");
      $form->setContent($content);
      $form->addButton($this->getPlugin()->getLanguage()->getTranslate("form.clearall.button1"));
      $form->sendToPlayer($player);
   }
   
}