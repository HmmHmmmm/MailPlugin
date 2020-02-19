<?php

namespace hmmhmmmm\mail;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

class ReportCommand extends Command implements PluginIdentifiableCommand{
   private $plugin;
   public function __construct(Mail $plugin){
      parent::__construct("report");
      $this->plugin = $plugin;
      $this->setPermission("report.command.report");
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
   
   public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
      if(!$this->testPermission($sender)){
         return true;
      }
      if(!$sender instanceof Player){
         $this->sendConsoleError($sender);
         return true;
      }
      if(empty($args)){
         $this->getPlugin()->getForm()->Report($sender);
         return true;
      }
      return true;
   }
}