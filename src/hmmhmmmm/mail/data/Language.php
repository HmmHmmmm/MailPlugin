<?php

namespace hmmhmmmm\mail\data;

use hmmhmmmm\mail\Mail;

use pocketmine\utils\Config;

class Language{
   private $plugin = null;
   private $data = null;
   private $lang = "?";
   
   private $langEnglish = [
      "reset" => false,
      "notfound.plugin" => "§cThis plugin will not work. Please install the plugin %s",
      "plugininfo.name" => "§fName plugin %s",
      "plugininfo.version" => "§fVersion %s",
      "plugininfo.author" => "§fList of creators %s",
      "plugininfo.description" => "§fDescription of the plugin. §eis a plugin free. Please do not sell. If you redistribute it, please credit the creator. :)",
      "plugininfo.facebook" => "§fFacebook %s",
      "plugininfo.youtube" => "§fYoutube %s",
      "plugininfo.website" => "§fWebsite %s",
      "plugininfo.discord" => "§fDiscord %s",
      "addmail.message1" => "§fFrom [%s§f] §e%s §fdate/time §a%s\n§fWrote that §b%s",
      "addmail.message2" => "§aSent a message to %s And you wrote that %s",
      "addmail.message3" => "§fYou have §a1 §fNew message! From the player name §e%s §fPrint §d/mail read %s §fRead it!",
      "addmail.titleline1" => "§fYou have §a1 §fNew message!",
      "addmail.titleline2" => "§fFrom the player name §e%s §fPrint §d/mail §fRead it!",
      "readmail" => "§fMessage number §b%s %s\n%s\n§f-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-",
      "listmail" => "§f%s §f(§a%s§f) Message",
      "countmail" => "§fYou have (§a%s§f) Messages from all, Print §d/mail §fRead it!",
      "mail.command.consoleError" => "§cSorry: commands can be typed only in the game.",
      "mail.command.permissionError" => "§cSorry: You cannot type this command.",
      "mail.command.sendHelp.empty" => "§eYou can see more commands by typing /mail help",
      "mail.command.sendHelp.countmail" => "§fYou have (§a%s§f) Messages from all",
      "mail.command.sendHelp.listplayer" => "§eList of players that sent you messages:",
      "mail.command.sendHelp.notfoundmessage" => "§cwithout",
      "mail.command.info.usage" => "/mail info",
      "mail.command.info.description" => "§fCredit of the plugin creator",
      "mail.command.write.usage" => "/mail write <Player name>",
      "mail.command.write.description" => "§fAnd type in chat to send a message to that player",
      "mail.command.write.error1" => "§cTry: %s",
      "mail.command.write.complete" => "§aPlease type chat to write a message.",
      "mail.command.read.usage" => "/mail read <Name player who sent message>",
      "mail.command.read.description" => "§fRead the messages of players that have been submitted.",
      "mail.command.read.error1" => "§cTry: %s",
      "mail.command.read.error2" => "§cSorry: No one has sent you a message.",
      "mail.command.read.error3" => "§cSorry: The player name that sent the message was not found.",
      "mail.command.read.complete" => "§fThe players §b%s §fReading your message",
      "mail.command.readall.usage" => "/mail read-all",
      "mail.command.readall.description" => "§fRead the messages of players that have been submitted All.",
      "mail.command.readall.error1" => "§cSorry: No one has sent you a message.",
      "mail.command.readall.error2" => "§cSorry: The player name that sent the message was not found.",
      "mail.command.readall.complete" => "§fThe players §b%s §fReading your message",
      "mail.command.clear.usage" => "/mail clear <Name player who sent message> <Message number>",
      "mail.command.clear.description" => "§fTo delete that message",
      "mail.command.clear.error1" => "§cTry: %s",
      "mail.command.clear.error2" => "§c<Message number> Please write as numbers.",
      "mail.command.clear.error3" => "§cNo message found of %s",
      "mail.command.clear.error4" => "§cMessage number not found %s",
      "mail.command.clear.complete" => "§aHas deleted the message of %s In message number %s Successfully",
      "mail.command.clearall.usage" => "/mail clear-all",
      "mail.command.clearall.description" => "§fTo delete all submitted player messages",
      "mail.command.clearall.complete" => "§aAll messages of players have been deleted!",
      "mail.command.see.usage" => "/mail see <Player name>",
      "mail.command.see.description" => "§fSee my messages, Did he read it?",
      "mail.command.see.error1" => "§cTry: %s",
      "mail.command.see.error2" => "§cSorry: Your message cannot be found? You didn't send a message to this person or He has deleted gone your message.",
      "report.command.consoleError" => "§cSorry: commands can be typed only in the game.",
      "report.command.permissionError" => "§cSorry: You cannot type this command.",
      "playerdata.notfoundname" => "§cPlayer name not found",
      "form.menu.error1" => "§cAn error has occurred\n§eYou can not use work",
      "form.menu.error2" => "§cAn error has occurred\n§eYou haven't sent a message to anyone yet.",
      "form.menu.error3" => "§cAn error has occurred\n§eNo one has sent you a message.",
      "form.menu.button1" => "§fSend messages to players",
      "form.menu.button2" => "§fSee messages that have been sent",
      "form.menu.button3" => "§fYou have (§a%s§f) Messages from all",
      "form.menu.button4" => "§c[Delete all come submitted player messages.]",
      "form.write.error1" => "§cAn error has occurred\n§e<Player name> Need to put",
      "form.write.error2" => "§cAn error has occurred\n§ePlayer name not found",
      "form.write.error3" => "§cAn error has occurred\n§e<Message to send> Need to put",
      "form.write.input1" => "§ePlayer name",
      "form.write.input2" => "§eMessage to send",
      "form.seemsg.error1" => "§cAn error has occurred\n§e<Message to send> Need to put",
      "form.seemsg.online" => "§aOnline",
      "form.seemsg.offline" => "§cOffline",
      "form.seemsg.title" => "§l§fChat room of §e%s §fNow %s",
      "form.seemsg.input1" => "Send message",
      "form.seeall.error1" => "§cAn error has occurred\n§eYour message cannot be found? You didn't send a message to this person or He has deleted gone your message.",
      "form.readmsg.error1" => "§cAn error has occurred\n§e<Reply> Need to put",
      "form.readmsg.error2" => "§cAn error has occurred\n§e<Delete message> Need to put and written as a number",
      "form.readmsg.online" => "§aOnline",
      "form.readmsg.offline" => "§cOffline",
      "form.readmsg.complete" => "§fThe players §b%s §fReading your message",
      "form.readmsg.title" => "§l§fChat room of §e%s §fNow %s",
      "form.readmsg.dropdown1.title" => "menu",
      "form.readmsg.dropdown1.step1" => "Reply",
      "form.readmsg.dropdown1.step2" => "Delete message\nPlease enter the message number.",
      "form.clearall.complete" => "§aHas deleted all messages of players come submitted Successfully",
      "form.clearall.content" => "§fYou are sure, right? That will clear all messages\nIf washed, §cAll player messages sent to you will be lost.\n§fBut the messages you send to players will not disappear",
      "form.clearall.button1" => "§aOk",
      "form.clearall.button2" => "§cCancel",
      "form.add.content" => "§fThe players [%s§f] §d%s §fHas sent you a message\nDo you want to read?",
      "form.add.button1" => "§aRead",
      "form.add.button2" => "§eKeep",
      "form.report.content" => "§f[§bReport§f] You can report as follows\n§e1.§fInform to add the system\n§e2.§fNotify to correct bugs on various servers, There rewards.\n§e3.§fCan leave a message find the admin\n§e4.§fReport various problems",
      "form.report.reportmsg" => "Messages that you have previously reported",
      "form.report.error1" => "§cAn error has occurred\n§e<Send message> Need to put",
      "form.report.error2" => "§cAn error has occurred\n§eThe administrator has not yet responded.",
      "form.report.error3" => "§cAn error has occurred\n§ePlayer name not found %s To receive the report",
      "form.report.online" => "§aOnline",
      "form.report.offline" => "§cOffline",
      "form.report.title" => "§l§bReport §fNow caretaker %s",
      "form.report.dropdown1.title" => "menu",
      "form.report.dropdown1.step1" => "Send message",
      "form.report.dropdown1.step2" => "View the message that the administrator replied to.\n§aThen press Submit"
   ];
   
   
   private $langThai = [
      "reset" => false,
      "notfound.plugin" => "§cปลั๊กนี้จะไม่ทำงาน กรุณาลงปลั๊กอิน %s",
      "plugininfo.name" => "§fปลั๊กอินชื่อ %s",
      "plugininfo.version" => "§fเวอร์ชั่น %s",
      "plugininfo.author" => "§fรายชื่อผู้สร้าง %s",
      "plugininfo.description" => "§fคำอธิบายของปลั๊กอิน §eเป็นปลั๊กอินทำแจกกรุณาอย่าเอาไปขาย ถ้าจะแจกต่อโปรดให้เครดิตผู้สร้างด้วย :)",
      "plugininfo.facebook" => "§fเฟสบุ๊ค %s",
      "plugininfo.youtube" => "§fยูทูป %s",
      "plugininfo.website" => "§fเว็บไซต์ %s",
      "plugininfo.discord" => "§fดิสคอร์ด %s",
      "addmail.message1" => "§fจาก [%s§f] §e%s §fวันที่/เวลา §a%s\n§fได้เขียนว่า §b%s",
      "addmail.message2" => "§aได้ส่งข้อความให้กับ %s แล้ว คุณได้เขียนว่า %s",
      "addmail.message3" => "§fคุณมี §a1§fข้อความใหม่! จากผู้เล่นชื่อ §e%s §fพิมพ์ §d/mail read %s §fอ่านดูสิ!",
      "addmail.titleline1" => "§fคุณมี §a1§fข้อความใหม่!",
      "addmail.titleline2" => "§fจากผู้เล่นชื่อ §e%s §fพิมพ์ §d/mail §fอ่านดูสิ!",
      "readmail" => "§fหมายเลขข้อความที่ §b%s %s\n%s\n§f-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-",
      "listmail" => "§f%s §f(§a%s§f) ข้อความ",
      "countmail" => "§fคุณมี (§a%s§f) ข้อความจากทั้งหมด พิมพ์ §d/mail §fอ่านดูสิ!",
      "mail.command.consoleError" => "§cขออภัย: คำสั่งสามารถพิมพ์ได้เฉพาะในเกมส์",
      "mail.command.permissionError" => "§cขออภัย: คุณไม่สามารถพิมพ์คำสั่งนี้ได้",
      "mail.command.sendHelp.empty" => "§eคุณสามารถดูคำสั่งเพิ่มเติมได้โดยพิมพ์ /mail help",
      "mail.command.sendHelp.countmail" => "§fคุณมี (§a%s§f) ข้อความจากทั้งหมด",
      "mail.command.sendHelp.listplayer" => "§eรายชื่อผู้เล่นที่ส่งข้อความมาหาคุณ:",
      "mail.command.sendHelp.notfoundmessage" => "§cไม่มี",
      "mail.command.info.usage" => "/mail info",
      "mail.command.info.description" => "§fเครดิตผู้สร้างปลั๊กอิน",
      "mail.command.write.usage" => "/mail write <ชื่อผู้เล่น>",
      "mail.command.write.description" => "§fแล้วพิมพ์ที่แชทเพื่อส่งข้อความให้ผู้เล่นคนนั้น",
      "mail.command.write.error1" => "§cลอง: %s",
      "mail.command.write.complete" => "§aกรุณาพิมพ์ที่แชทเพื่อเขียนข้อความ",
      "mail.command.read.usage" => "/mail read <ชื่อผู้ที่ส่งข้อความ>",
      "mail.command.read.description" => "§fอ่านข้อความของผู้เล่นที่ส่งมา",
      "mail.command.read.error1" => "§cลอง: %s",
      "mail.command.read.error2" => "§cขออภัย: ยังไม่มีใครส่งข้อความมาหาคุณ",
      "mail.command.read.error3" => "§cขออภัย: ไม่พบชื่อผู้เล่นที่ส่งข้อความ",
      "mail.command.read.complete" => "§fผู้เล่น §b%s §fกำลังอ่านข้อความของคุณ",
      "mail.command.readall.usage" => "/mail read-all",
      "mail.command.readall.description" => "§fอ่านข้อความของผู้เล่นที่ส่งมาทั้งหมด",
      "mail.command.readall.error1" => "§cขออภัย: ยังไม่มีใครส่งข้อความมาหาคุณ",
      "mail.command.readall.error2" => "§cขออภัย: ไม่พบชื่อผู้เล่นที่ส่งข้อความ",
      "mail.command.readall.complete" => "§fผู้เล่น §b%s §fกำลังอ่านข้อความของคุณ",
      "mail.command.clear.usage" => "/mail clear <ชื่อผู้ที่ส่งข้อความ> <หมายเลขข้อความ>",
      "mail.command.clear.description" => "§fเพื่อลบข้อความนั้น",
      "mail.command.clear.error1" => "§cลอง: %s",
      "mail.command.clear.error2" => "§c<หมายเลขข้อความ> กรุณาเขียนให้เป็นตัวเลข",
      "mail.command.clear.error3" => "§cไม่พบข้อความของ %s",
      "mail.command.clear.error4" => "§cไม่พบหมายเลขข้อความ %s",
      "mail.command.clear.complete" => "§aได้ลบข้อความของ %s ในหมายเลขข้อความ %s สำเร็จ",
      "mail.command.clearall.usage" => "/mail clear-all",
      "mail.command.clearall.description" => "§fเพื่อลบข้อความของผู้เล่นที่ส่งมาทั้งหมด",
      "mail.command.clearall.complete" => "§aได้ลบข้อความของผู้เล่นที่ส่งมาทั้งหมดเรียบร้อย!",
      "mail.command.see.usage" => "/mail see <ชื่อผู้เล่น>",
      "mail.command.see.description" => "§fเพื่อดูข้อความของเราที่ส่งไป ว่าเค้าอ่านรึยัง?",
      "mail.command.see.error1" => "§cลอง: %s",
      "mail.command.see.error2" => "§cขออภัย: ไม่พบข้อความของคุณ? คุณไม่ได้ส่งข้อความไปหาคนนี้ หรือ เค้าลบข้อความของคุณไปแล้ว",
      "report.command.consoleError" => "§cขออภัย: คำสั่งสามารถพิมพ์ได้เฉพาะในเกมส์",
      "report.command.permissionError" => "§cขออภัย: คุณไม่สามารถพิมพ์คำสั่งนี้ได้",
      "playerdata.notfoundname" => "§cไม่พบชื่อผู้เล่น",
      "form.menu.error1" => "§cเกิดข้อผิดพลาด\n§eคุณไม่สามารถใช้งานได้",
      "form.menu.error2" => "§cเกิดข้อผิดพลาด\n§eคุณยังไม่ได้ส่งข้อความหาใคร",
      "form.menu.error3" => "§cเกิดข้อผิดพลาด\n§eไม่มีใครส่งข้อความหาคุณ",
      "form.menu.button1" => "§fส่งข้อความหาผู้เล่น",
      "form.menu.button2" => "§fดูข้อความที่เคยส่งไป",
      "form.menu.button3" => "§fคุณมี (§a%s§f) ข้อความจากทั้งหมด",
      "form.menu.button4" => "§c[ลบข้อความของผู้ที่ส่งมาทั้งหมด]",
      "form.write.error1" => "§cเกิดข้อผิดพลาด\n§e<ชื่อผู้เล่น> จำเป็นต้องใส่",
      "form.write.error2" => "§cเกิดข้อผิดพลาด\n§eไม่พบชื่อผู้เล่น",
      "form.write.error3" => "§cเกิดข้อผิดพลาด\n§e<ข้อความที่จะส่ง> จำเป็นต้องใส่",
      "form.write.input1" => "§eชื่อผู้เล่น",
      "form.write.input2" => "§eข้อความที่จะส่ง",
      "form.seemsg.error1" => "§cเกิดข้อผิดพลาด\n§e<ส่งข้อความ> จำเป็นต้องใส่",
      "form.seemsg.online" => "§aออนไลน์",
      "form.seemsg.offline" => "§cออฟไลน์",
      "form.seemsg.title" => "§l§fห้องแชทของ §e%s §fตอนนี้ %s §fอยู่",
      "form.seemsg.input1" => "ส่งข้อความ",
      "form.seeall.error1" => "§cเกิดข้อผิดพลาด\n§eไม่พบข้อความของคุณ? คุณไม่ได้ส่งข้อความไปหาคนนี้ หรือ เค้าลบข้อความของคุณไปแล้ว",
      "form.readmsg.error1" => "§cเกิดข้อผิดพลาด\n§e<ตอบกลับ> จำเป็นต้องใส่",
      "form.readmsg.error2" => "§cเกิดข้อผิดพลาด\n§e<ลบข้อความ> จำเป็นต้องใส่และเขียนให้เป็นตัวเลข",
      "form.readmsg.online" => "§aออนไลน์",
      "form.readmsg.offline" => "§cออฟไลน์",
      "form.readmsg.complete" => "§fผู้เล่น §b%s §fกำลังอ่านข้อความของคุณ",
      "form.readmsg.title" => "§l§fห้องแชทของ §e%s §fตอนนี้ %s §fอยู่",
      "form.readmsg.dropdown1.title" => "เมนู",
      "form.readmsg.dropdown1.step1" => "ตอบกลับ",
      "form.readmsg.dropdown1.step2" => "ลบข้อความ\nกรุณาใส่หมายเลขข้อความ",
      "form.clearall.complete" => "§aได้ลบข้อความของผู้ที่ส่งมาทั้งหมดเรียบร้อย!",
      "form.clearall.content" => "§fคุณแน่ใจแล้วใช่มั้ย? ที่จะล้างข้อความทั้งหมด\nหากล้างแล้ว §cข้อความของผู้เล่นที่ส่งมาหาคุณจะหายทั้งหมด\n§fแต่ ข้อความที่คุณส่งหาผู้เล่นจะไม่หาย",
      "form.clearall.button1" => "§aตกลง",
      "form.clearall.button2" => "§cยกเลิก",
      "form.add.content" => "§fผู้เล่น [%s§f] §d%s §fได้ส่งข้อความหาคุณ\nคุณต้องการอ่านหรือไม่?",
      "form.add.button1" => "§aอ่าน",
      "form.add.button2" => "§eเก็บไว้",
      "form.report.content" => "§f[§bReport§f] คุณสามารถ report ได้ดังนี้\n§e1.§fแจ้งให้เพิ่มระบบได้\n§e2.§fแจ้งให้แก้บัคในเซิฟต่างๆ อันนี้มีรางวัลให้\n§e3.§fฝากข้อความหาแอดมินได้\n§e4.§fแจ้งปัญหาต่างๆ",
      "form.report.reportmsg" => "ข้อความที่คุณเคยแจ้งไป",
      "form.report.error1" => "§cเกิดข้อผิดพลาด\n§e<ส่งข้อความ> จำเป็นต้องใส่",
      "form.report.error2" => "§cเกิดข้อผิดพลาด\n§eแอดมินยังไม่ได้ตอบกลับ",
      "form.report.error3" => "§cเกิดข้อผิดพลาด\n§eไม่พบชื่อผู้เล่น %s ที่จะรับข้อความ report",
      "form.report.online" => "§aออนไลน์",
      "form.report.offline" => "§cออฟไลน์",
      "form.report.title" => "§l§bReport §fตอนนี้ผู้ดูแล %s §fอยู่",
      "form.report.dropdown1.title" => "เมนู",
      "form.report.dropdown1.step1" => "ส่งข้อความ",
      "form.report.dropdown1.step2" => "ดูข้อความที่แอดมินตอบกลับ\n§aแล้วกด Submit"
   ];
   

   public function __construct(Mail $plugin, string $lang){
      $this->plugin = $plugin;
      $this->lang = $lang;
      $this->data = new Config($this->plugin->getDataFolder()."language/$this->lang.yml", Config::YAML, array());
      $d = $this->data->getAll();
      if(!isset($d["reset"])){
         $this->reset();
      }else{
         if($d["reset"]){
            $this->reset();
         }
      }
   }
   public function getPlugin(): Mail{
      return $this->plugin;
   }
   public function getData(): Config{
      return $this->data;
   }
   public function getLang(): string{
      return $this->lang;
   }
   public function reset(): void{
      $data = $this->getData();
      if($this->getLang() == "thai"){
         foreach($this->langThai as $key => $value){
            $data->setNested($key, $value);
         }
      }
      if($this->getLang() == "english"){
         foreach($this->langEnglish as $key => $value){
            $data->setNested($key, $value);
         }
      }
      $data->save();
   }
   
   public function getTranslate(string $key, array $arrayValue = []): string{
      $data = $this->getData();
      if(!empty($arrayValue)){
         return vsprintf($data->getNested($key), $arrayValue);
      }
      return $data->getNested($key);
   }
}