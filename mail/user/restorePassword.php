<?php
use app\models\db\Setting;
use app\models\db\Block;

/** @var $url */
?>
Добрый день!<br>
Вы запросили восстановление пароля к личному кабинету на сайте <?= Setting::get('SITE.NAME') ?><br>
Перейдите по <a href='<?= $url ?>'>ссылке</a> для восстановления пароля.<br>
<br><br>
Компания <?= Setting::get('SITE.NAME') ?><br>
<?= Block::getView('CONTACT.PHONE') ?><br>
<br><br>
Данное письмо отправлено автоматически, просьба не отвечать на него.<br>
<br><br>
<?= Setting::get('SITE.URL') ?><br>