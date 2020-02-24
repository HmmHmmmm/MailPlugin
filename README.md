## MailPlugin

[Thai](README.md)
[English](README_EN.md)


## Language Thai

```diff
- ปลั๊กอินนี้จะไม่ทำงาน หากคุณไม่ได้ลงปลั๊กอิน FormAPI
```

#Dowload Plugin FormAPI [Click here](https://poggit.pmmp.io/p/FormAPI)


**คุณสมบัติปลั๊กอิน**<br>
- เป็นปลั๊กอินส่งข้อความหาผู้เล่น (สามารถส่งข้อความแบบออฟไลน์ได้)
- สามารถดูข้อความที่เราส่งไปหาผู้เล่นว่า เค้าอ่านรึยัง?
- สามารถเก็บข้อความและลบข้อความได้
- มี gui form
- มี report
- มีภาษา thai english (สามารถแก้ไขภาษาที่คุณไม่ชอบได้ที่/resources/language)


**วิธีใช้งาน**<br>
- คลิปตัวอย่าง [คลิก](https://youtu.be/BML6U6NXe4E)


## ดาวโหลด
| version  | plugin support                        | Download  zip/phar                                                 |
| ---- | ------------------------------------ | ---------------------------------------------------------- |
| 1.0  | GenisysPro api 3.0.1 mcpe 1.1 [Click here](https://github.com/GenisysPro/GenisysPro) | [คลิก](https://github.com/HmmHmmmm/MailPlugin/releases/1.0) |
| 2.0  | pocketmine api 3.11.0 mcpe 1.14 [Click here](https://github.com/pmmp/PocketMine-MP) | [คลิก](https://github.com/HmmHmmmm/MailPlugin/releases/2.0) |
| 2.1  | pocketmine api 3.11.0 mcpe 1.14 [Click here](https://github.com/pmmp/PocketMine-MP) | [คลิก](https://github.com/HmmHmmmm/MailPlugin/releases/2.1) |
| 3.0  | pocketmine api 3.11.0 mcpe 1.14 [Click here](https://github.com/pmmp/PocketMine-MP) | [คลิก](https://github.com/HmmHmmmm/MailPlugin/releases/3.0) |


**Config**<br>
```
#Language
#>thai=ภาษาไทย
#>english=English language
language: thai


#ชื่อผู้เล่นที่จะรับข้อความ report
report:
  name: HmmHmmmm
```


**Command**<br>
- /mail : เปิด gui form
- /mail info : เครดิตผู้สร้าง
- /mail write <ชื่อผู้เล่น> : แล้วพิมที่แชทเขียนข้อความเพื่อส่งข้อความให้ผู้เล่นคนนั้น
- /mail read <ชื่อผู้ที่ส่งข้อความ> : อ่านข้อความผู้ที่ส่งมา
- /mail read-all : อ่านข้อความผู้ที่ส่งมาทั้งหมด
- /mail clear <ชื่อผู้ที่ส่งข้อความ> <หมายเลขข้อความ> : เพื่อลบข้อความนั้น
- /mail clear-all : เพื่อลบข้อความของผู้ที่ส่งมาทั้งหมด
- /mail see <ชื่อผู้เล่น> : เพื่อดูข้อความที่เราส่งไปว่าเค้าอ่านรึยัง?
- /report : แจ้งแอดมิน


**API**<br>
```php
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
```


## Images
![icon](images/3.0/Screenshot_2020-02-24-13-46-24-210_com.mojang.minecraftpe.jpg)
![icon](images/3.0/Screenshot_2020-02-24-13-46-07-051_com.mojang.minecraftpe.jpg)
![icon](images/3.0/Screenshot_2020-02-24-13-46-42-096_com.mojang.minecraftpe.jpg)
![icon](images/3.0/Screenshot_2020-02-24-13-47-11-379_com.mojang.minecraftpe.jpg)
![icon](images/3.0/Screenshot_2020-02-24-13-58-50-672_com.mojang.minecraftpe.jpg)


## LICENSE
ใบอนุญาต GPL-3.0 [license](https://github.com/HmmHmmmm/MailPlugin/blob/master/LICENSE)