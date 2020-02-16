<?php

namespace hmmhmmmm\mail;

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
   public function MailMenu(Player $player, string $content = ""): void{
      $form = $this->getPlugin()->getFormAPI()->createSimpleForm(function ($player, $data){
         if(!($data === null)){
            if($data == 0){
               if(!$player->hasPermission("mail.command.write")){
                  $text = "§cเกิดข้อผิดพลาด\n§eคุณไม่สามารถใช้งานได้";                 
                  $this->MailMenu($player, $text);
                  return;
               }
               $this->MailWrite($player);
            }
            if($data == 1){
               if(!$player->hasPermission("mail.command.see")){
                  $text = "§cเกิดข้อผิดพลาด\n§eคุณไม่สามารถใช้งานได้";                 
                  $this->MailMenu($player, $text);
                  return;
               }
               if($this->getPlugin()->getCountMailPlayers($player->getName()) == 0){
                  $text = "§cเกิดข้อผิดพลาด\n§eคุณยังไม่ได้ส่งข้อความหาใคร";                 
                  $this->MailMenu($player, $text);
                  return;
               }
               $this->MailSeeAll($player);
            }
            if($data == 2){
               if(!$player->hasPermission("mail.command.read") && !$player->hasPermission("mail.command.readall")){
                  $text = "§cเกิดข้อผิดพลาด\n§eคุณไม่สามารถใช้งานได้";                 
                  $this->MailMenu($player, $text);
                  return;
               }
               if($this->getPlugin()->getMailSenderCount($player->getName()) == 0){
                  $text = "§cเกิดข้อผิดพลาด\n§eไม่มีใครส่งข้อความหาคุณ";
                  $this->MailMenu($player, $text);
                  return;
               }
               $this->MailReadAll($player);
            }
            if($data == 3){
               if(!$player->hasPermission("mail.command.clearall")){
                  $text = "§cเกิดข้อผิดพลาด\n§eคุณไม่สามารถใช้งานได้";                 
                  $this->MailMenu($player, $text);
                  return;
               }
               $this->MailClearAll($player);
            }
         }
      });
      $form->setTitle($this->getPrefix()." Menu");
      $form->setContent($content);
      $form->addButton("§fส่งข้อความหาผู้เล่น");
      $form->addButton("§fดูข้อความที่เคยส่งไป");
      $form->addButton("§fคุณมี (§a".$this->getPlugin()->getCountMail($player->getName())."§f) ข้อความจากทั้งหมด");
      $form->addButton("§cลบข้อความของผู้ที่ส่งมาทั้งหมด");
      $form->sendToPlayer($player);
   }
   public function MailWrite(Player $player, string $content = ""): void{
      $form = $this->getPlugin()->getFormAPI()->createCustomForm(function ($player, $data){
         if($data == null){
            return;
         }
         $name = explode(" ", $data[0]); 
         if($name[0] == null){
            $text = "§cเกิดข้อผิดพลาด\n§eกรุณาเขียนชื่อใหม่";                  
            $this->MailWrite($player, $text);                  
            return;
         }
         $playerData = $this->getPlugin()->getPlayerData($name[0]);
         if(!$playerData->isData()){
            $text = "§cเกิดข้อผิดพลาด\n§eไม่พบชื่อของผู้เล่น";
            $this->MailWrite($player, $text);                  
            return;
         }
         $this->getPlugin()->addMail($playerData->getName(), $player, $data[1], false);
         $pOnline = $this->getPlugin()->getServer()->getPlayer($name[0]);
         if($pOnline instanceof Player){
            $this->MailAdd($pOnline, $player->getName());
         }
      });
      $form->setTitle($this->getPrefix()." Write");
      $form->addInput("§eชื่อผู้เล่น");
      $form->addInput("§eข้อความที่จะส่ง");
      $form->addLabel($content);
      $form->sendToPlayer($player);
   }
   public function MailSeeMsg(Player $player, string $senderName, string $content = ""): void{
      $senderName = strtolower($senderName);
      $pOnline = $this->getPlugin()->getServer()->getPlayer($senderName);
      $form = $this->getPlugin()->getFormAPI()->createCustomForm(function ($player, $data) use ($senderName, $content, $pOnline){
         if(!($data === null)){
            if($data[1] == null){
               $text = "§cเกิดข้อผิดพลาด\n§eกรุณาเขียนใหม่\n".$content;
               $this->MailSeeMsg($player, $senderName, $text);
               return;
            }
            $this->getPlugin()->addMail($senderName, $player, $data[1], false);
            if($pOnline instanceof Player){
               $this->MailAdd($pOnline, $player->getName());
            }
         }
         
      });
      if($pOnline instanceof Player){
         $online = "§aออนไลน์";
      }else{
         $online = "§cออฟไลน์";
      }
      $form->setTitle("§l§fห้องแชทของ §e".$senderName." §fตอนนี้ ".$online." §fอยู่");		          	
      $form->addLabel($content);
      $form->addInput("", "ส่งข้อความ");
      $form->sendToPlayer($player);
   }
   public function MailSeeAll(Player $player, string $content = ""): void{
       foreach($this->getPlugin()->getMailPlayers($player->getName()) as $senderName){
         $array[] = $senderName;
      }
      $form = $this->getPlugin()->getFormAPI()->createSimpleForm(function ($player, $data) use ($array){
         if(!($data === null)){
            $name = $array[$data];
            if(!$this->getPlugin()->isMailSender($name, strtolower($player->getName()))){
               $text = "§cเกิดข้อผิดพลาด\n§eไม่พบข้อความของคุณ? คุณไม่ได้ส่งข้อความไปหาคนนี้ หรือ เค้าลบข้อความของคุณไปแล้ว";                 
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
      $form = $this->getPlugin()->getFormAPI()->createCustomForm(function ($player, $data) use ($senderName, $content, $pOnline){
         if(!($data === null)){
            if($data[1] == 0){
               if($data[2] == null){
                  $text = "§cเกิดข้อผิดพลาด\n§eกรุณาเขียนใหม่\n".$content;
                  $this->MailReadMsg($player, $senderName, $text);                  
                  return;
               }
               $this->getPlugin()->addMail($senderName, $player, $data[2], false);
               if($pOnline instanceof Player){
                  $this->MailAdd($pOnline, $player->getName());
               }
            }
            if($data[1] == 1){
               if($data[2] == null){
                  $text = "§cเกิดข้อผิดพลาด\n§eกรุณาเขียนใหม่\n".$content;
                  $this->MailReadMsg($player, $senderName, $text);                  
                  return;
               }
               if(!is_numeric($data[2])){
                  $text = "§cเกิดข้อผิดพลาด\n§eกรุณาเขียนให้เป็นตัวเลข\n".$content;
                  $this->MailReadMsg($player, $senderName, $text);                  
                  return;
               }
               $msgCount = (int) $data[2];
               $this->getPlugin()->delMailSender($player, strtolower($senderName), $msgCount);
            }
         }
      });
      if($pOnline instanceof Player){
         $online = "§aออนไลน์";
         $pOnline->sendMessage($this->getPrefix()." ผู้เล่น §b".$player->getName()." §fได้อ่านข้อความของคุณแล้ว!");
      }else{
         $online = "§cออฟไลน์";
      }
      $form->setTitle("§l§fห้องแชทของ §e".$senderName." §fตอนนี้ ".$online." §fอยู่");
      $form->addLabel($content);
      $form->addDropdown("เมนู", ["ตอบกลับ", "ลบข้อความ\nกรุณาใส่หมายเลขข้อความ"]); 
      $form->addInput("");
      $form->sendToPlayer($player);
   }
   public function MailReadAll(Player $player, string $content = ""){
       foreach($this->getPlugin()->getMailSender($player->getName()) as $senderName){
         $array[] = $senderName;
      }
      $form = $this->getPlugin()->getFormAPI()->createSimpleForm(function ($player, $data) use ($array){
         if(!($data === null)){
            $name = $array[$data];
            $count = $this->getPlugin()->getCountMail($player->getName()) - $this->getPlugin()->getCountMailSender($player->getName(), $name);
            $this->getPlugin()->setCountMail($player->getName(), $count);
            $this->getPlugin()->setCountMailSender($player->getName(), $name, 0);
            foreach($this->getPlugin()->getMailSenderWrite($player->getName(), $name) as $msgCount2){
               $this->getPlugin()->setMailRead($player->getName(), $name, $msgCount2, "§aอ่านแล้ว");
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
      $form = $this->getPlugin()->getFormAPI()->createModalForm(function ($player, $data){
         if(!($data === null)){
            if($data == 1){//ปุ่ม1
               $this->getPlugin()->resetMail($player->getName());
               $player->sendMessage($this->getPrefix()." §aลบข้อความของผู้ที่ส่งมาทั้งหมดเรียบร้อย!");
            }
            if($data == 0){//ปุ่ม2
            }
         }
      });
      $form->setTitle($this->getPrefix()." ClearAll");
      $text = "§fคุณแน่ใจแล้วใช่มั้ย? ที่จะล้างข้อความทั้งหมด\nหากล้างแล้ว §cข้อความของผู้เล่นที่ส่งมาหาคุณจะหายทั้งหมด\n§fแต่ ข้อความที่คุณส่งหาผู้เล่นจะไม่หาย";
      $form->setContent($text);
      $form->setButton1("§aตกลง"); 
      $form->setButton2("§cยกเลิก");
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
      $form = $this->getPlugin()->getFormAPI()->createModalForm(function ($player, $data) use ($senderName){
         if(!($data === null)){
            if($data == 1){//ปุ่ม1
               $name = $senderName;
               $count = $this->getPlugin()->getCountMail($player->getName()) - $this->getPlugin()->getCountMailSender($player->getName(), $name);
               $this->getPlugin()->setCountMail($player->getName(), $count);
               $this->getPlugin()->setCountMailSender($player->getName(), $name, 0);
               foreach($this->getPlugin()->getMailSenderWrite($player->getName(), $name) as $msgCount2){
                  $this->getPlugin()->setMailRead($player->getName(), $name, $msgCount2, "§aอ่านแล้ว");
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
      $text = $this->getPrefix()." ผู้เล่น [".$groupSender."§f] §d".$sender->getName()." §fได้ส่งข้อความหาคุณ\nคุณต้องการอ่านหรือไม่?";
      $form->setContent($text);
      $form->setButton1("§aอ่าน"); 
      $form->setButton2("§eเก็บไว้");
      $form->sendToPlayer($player);
   }
   public function Report(Player $player, string $content = ""): void{
      $senderName = $this->getPlugin()->getConfig()->getNested("report.name");
      $name = $senderName;
      if($this->getPlugin()->isMailSender($name, $player->getName())){
         foreach($this->getPlugin()->getMailSenderWrite($name, $player->getName()) as $msgCount2){
            $array2[] = $this->getPlugin()->readMail($name, $player->getName(), $msgCount2);
         }
         $msg = implode("\n", $array2);
         $content = "§f[§bReport§f] คุณสามารถ report ได้ดังนี้\n§e1.§fแจ้งให้เพิ่มระบบได้\n§e2.§fแจ้งให้แก้บัคในเซิฟต่างๆ #อันนี้มีรางวัลให้\n§e3.§fฝากข้อความหาแอดมินได้\n§e4.§fแจ้งปัญหาต่างๆ\n§a[ข้อความที่คุณเคยแจ้งไป]\n".$msg;
      }else{
         $content = "§f[§bReport§f] คุณสามารถ report ได้ดังนี้\n§e1.§fแจ้งให้เพิ่มระบบได้\n§e2.§fแจ้งให้แก้บัคในเซิฟต่างๆ #อันนี้มีรางวัลให้\n§e3.§fฝากข้อความหาแอดมินได้\n§e4.§fแจ้งปัญหาต่างๆ";
      }
      $pOnline = $this->getPlugin()->getServer()->getPlayer($senderName);
      $form = $this->getPlugin()->getFormAPI()->createCustomForm(function ($player, $data) use ($senderName, $content, $pOnline){
         if(!($data === null)){
            if($data[1] == 0){
               if($data[2] == null){
                  $text = "§cเกิดข้อผิดพลาด\n§eกรุณาเขียนใหม่\n".$content;
                  $this->Report($player, $text);
                  return;
               }
               $this->getPlugin()->addMail($senderName, $player, $data[2], false);
               if($pOnline instanceof Player){
                  $this->MailAdd($pOnline, $player->getName());
               }
            }
            if($data[1] == 1){
               if(!$this->getPlugin()->isMailSender($player->getName(), $senderName)){
                  $text = "§cเกิดข้อผิดพลาด\n§eแอดมินยังไม่ได้ตอบกลับ";
                  $this->Report($player, $text);
                  return;
               }
               $name = $senderName;
               $count = $this->getPlugin()->getCountMail($player->getName()) - $this->getPlugin()->getCountMailSender($player->getName(), $name);
               $this->getPlugin()->setCountMail($player->getName(), $count);
               $this->getPlugin()->setCountMailSender($player->getName(), $name, 0);
               foreach($this->getPlugin()->getMailSenderWrite($player->getName(), $name) as $msgCount2){
                  $this->getPlugin()->setMailRead($player->getName(), $name, $msgCount2, "§aอ่านแล้ว");
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
         $online = "§aออนไลน์";
      }else{
         $online = "§cออฟไลน์";
      }
      $form->setTitle("§l§bReport ตอนนี้ผู้ดูแล ".$online." §fอยู่");
      $form->addLabel($content);
      $form->addDropdown("เมนู", ["ส่งข้อความ", "ดูข้อความที่แอดมินตอบกลับ\nแล้วกด Submit"]); 
      $form->addInput("");
      $form->sendToPlayer($player);
   }}