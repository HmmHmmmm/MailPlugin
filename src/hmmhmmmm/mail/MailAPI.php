<?php

namespace hmmhmmmm\mail;

interface MailAPI{

   public static function getInstance(): Mail;

   public function getCountMail(string $name): int;
}