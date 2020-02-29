## MailPlugin

[![](https://poggit.pmmp.io/shield.state/MailPlugin)](https://poggit.pmmp.io/p/MailPlugin) [![](https://poggit.pmmp.io/shield.api/MailPlugin)](https://poggit.pmmp.io/p/MailPlugin) [![](https://poggit.pmmp.io/shield.dl/MailPlugin)](https://poggit.pmmp.io/p/MailPlugin)


[Language English](#english)

[Language Thai](#thai)

This plugin uses Libraries `FormAPI` No need to install that plugin
Just load this plugin as .phar


# English


**Features of plugin**<br>
- Is a plugin send message find players (Can send messages offline have)
- Can see my messages that sent to players, See if he read it?
- Can store messages and delete messages
- have gui form
- have report
- Have language thai and english (You can edit the language you don't like at, `/resources/language`)


**How to use**<br>
- Sample clip https://youtu.be/BML6U6NXe4E)


**Command**<br>
- `/mail` : open gui form
- `/mail write <PlayerName>` : And type in chat to send a message to that player
- `/mail read <NamePlayerWhoSentMessage>` : Read the messages of players that have been submitted.
- `/mail read-all` : Read the messages of players that have been submitted All.
- `/mail clear <NamePlayerWhoSentMessage> <Message number>` : To delete that message
- `/mail clear-all` : To delete all submitted player messages
- `/mail see <PlayerName>` : See my messages, Did he read it?
- `/report` : Notify administrator


**Images**<br>
![1](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/1en.jpg)

![2](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/2en.jpg)

![3](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/3en.jpg)

![4](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/4en.jpg)

![5](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/5en.jpg)

![6](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/6en.jpg)


# Thai


**คุณสมบัติปลั๊กอิน**<br>
- เป็นปลั๊กอินส่งข้อความหาผู้เล่น (สามารถส่งข้อความแบบออฟไลน์ได้)
- สามารถดูข้อความที่เราส่งไปหาผู้เล่นว่า เค้าอ่านรึยัง?
- สามารถเก็บข้อความและลบข้อความได้
- มี gui form
- มี report
- มีภาษา thai และ english (สามารถแก้ไขภาษาที่คุณไม่ชอบได้ที่ `/resources/language`)


**วิธีใช้งาน**<br>
- คลิปตัวอย่าง https://youtu.be/BML6U6NXe4E


**Command**<br>
- `/mail` : เปิด gui form
- `/mail write <ชื่อผู้เล่น>` : แล้วพิมที่แชทเขียนข้อความเพื่อส่งข้อความให้ผู้เล่นคนนั้น
- `/mail read <ชื่อผู้ที่ส่งข้อความ>` : อ่านข้อความผู้ที่ส่งมา
- `/mail read-all` : อ่านข้อความผู้ที่ส่งมาทั้งหมด
- `/mail clear <ชื่อผู้ที่ส่งข้อความ> <หมายเลขข้อความ>` : เพื่อลบข้อความนั้น
- `/mail clear-all` : เพื่อลบข้อความของผู้ที่ส่งมาทั้งหมด
- `/mail see <ชื่อผู้เล่น>` : เพื่อดูข้อความที่เราส่งไปว่าเค้าอ่านรึยัง?
- `/report` : แจ้งแอดมิน


**Images**<br>
![1](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/1th.jpg)

![2](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/2th.jpg)

![3](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/3th.jpg)

![4](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/4th.jpg)

![5](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/5th.jpg)

![6](https://github.com/HmmHmmmm/MailPlugin/blob/master/images/3.0/6th.jpg)


# Config
```
#Language
#thai=ภาษาไทย
#english=English language
language: english


#Name of players who will receive messages report
report:
  name: HmmHmmmm
```
  

# Permissions
```
/*
*
* Command /mail it can be typed by everyone.
*
*/
mail.command.info:
  default: op
mail.command.write:
  default: true
mail.command.read:
  default: true
mail.command.readall:
  default: true
mail.command.clear:
  default: true
mail.command.clearall:
  default: true
mail.command.see:
  default: true
report.command:
  default: true
```