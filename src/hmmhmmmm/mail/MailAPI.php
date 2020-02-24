<?php

namespace hmmhmmmm\mail;

interface MailAPI{

   /*
   * ตัวเชื่อมต่อ API
   *
   * @ return Mail
   */
   public static function getInstance(): Mail;
   
   /*
   * API รับจำนวนข้อความของผู้เล่นที่มีอยู่ทั้งหมด
   * วิธีใช้ Mail::getInstance()->getCountMail($player->getName())
   *
   * @ return int
   */
   public function getCountMail(string $name): int;
}