<?php
/* *
 * Ameden Web Engine – Content Management System <http://engine.ameden.net/>
 * Copyright © 2013 Vladislav Balandin <http://www.ameden.net/>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

session_start();

ini_set('error_reporting',E_ALL);
ini_set('display_errors',true);
ini_set('html_errors',true);

define('AMEDEN',true);
define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/');
include(ROOT.'engine/classes/functions.class.php');
include(ROOT.'engine/classes/db.class.php');
include(ROOT.'install/messages.php');
$all = array('title' => '', 'content' => '');
$e = NULL;
$step = 0;

if(isset($_POST['goToStep1'])) $step = 1;

if(isset($_POST['goToStep2'])) {
	if(empty($_POST['title']) || empty($_POST['description']) || empty($_POST['tags'])) {
		$e = MSG('ALL_FIELDS_REQUIRED');
		$step = 1;
	} else {
		$_SESSION['title'] = addslashes($_POST['title']);
		$_SESSION['description'] = addslashes($_POST['description']);
		$_SESSION['tags'] = addslashes($_POST['tags']);
		$step = 2;
	}
}

if(isset($_POST['goToStep3'])) {
	if(empty($_POST['db_host']) || empty($_POST['db_user']) || empty($_POST['db_pass'])  || empty($_POST['db_name'])) {
		$e = MSG('ALL_FIELDS_REQUIRED');
		$step = 2;
	} else {
		$_SESSION['db_host'] = addslashes($_POST['db_host']);
		$_SESSION['db_user'] = addslashes($_POST['db_user']);
		$_SESSION['db_pass'] = addslashes($_POST['db_pass']);
		$_SESSION['db_name'] = addslashes($_POST['db_name']);
		$step = 3;
	}
}

if(isset($_POST['goToFinish'])) {
	if(empty($_POST['name']) || empty($_POST['birth']) || empty($_POST['username']) || empty($_POST['mail']) || empty($_POST['password']) || empty($_POST['repassword'])) {
		$e = MSG('ALL_FIELDS_REQUIRED');
		$step = 3;
	} elseif(addslashes($_POST['repassword']) != addslashes($_POST['password'])) {
		$e = MSG('PASSWORDS_DO_NOT_MATCH');
		$step = 3;
	} else {
		$db->connect($_SESSION['db_host'],$_SESSION['db_user'],$_SESSION['db_pass'],$_SESSION['db_name']);
		$db->query('CREATE TABLE IF NOT EXISTS `awe_bans` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$db->query('CREATE TABLE IF NOT EXISTS `awe_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `newsid` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$db->query('CREATE TABLE IF NOT EXISTS `awe_config` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `setting` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=19;');
		$db->query('INSERT INTO `awe_config` (`id`, `setting`, `value`) VALUES
(1, \'title\', \''.addslashes($_SESSION['title']).'\'),
(2, \'description\', \''.addslashes($_SESSION['description']).'\'),
(3, \'tags\', \''.addslashes($_SESSION['tags']).'\'),
(4, \'die_reason\', \'На сайте ведутся Технические работы! Заходите к нам позже!\'),
(5, \'admin_mail\', \''.addslashes($_POST['mail']).'\'),
(6, \'theme\', \'default\'),
(7, \'timezone\', \'Europe/Moscow\'),
(8, \'ximg_width\', \'200px\'),
(9, \'ximg_height\', \'200px\'),
(10, \'news_onpage\', \'10\'),
(11, \'comments_onpage\', \'10\'),
(12, \'admin_group\', \'6\'),
(13, \'die_site\', \'false\'),
(14, \'reg_one_ip\', \'true\'),
(15, \'write_user_passwords\', \'false\'),
(16, \'reg_mail_accept\', \'true\'),
(17, \'hide_login_box\', \'true\'),
(18, \'send_mail_oncomment\', \'false\');');
		$db->query('CREATE TABLE IF NOT EXISTS `awe_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ximage` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `shortstory` text NOT NULL,
  `fullstory` text NOT NULL,
  `date` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `views` varchar(255) NOT NULL DEFAULT \'0\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$db->query('CREATE TABLE IF NOT EXISTS `awe_passwords` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$db->query('CREATE TABLE IF NOT EXISTS `awe_pm` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `read` varchar(255) NOT NULL DEFAULT \'0\',
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$db->query('CREATE TABLE IF NOT EXISTS `awe_static` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `url` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$db->query('CREATE TABLE IF NOT EXISTS `awe_tickets` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `story` text NOT NULL,
  `important` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$db->query('CREATE TABLE IF NOT EXISTS `awe_users` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `checked` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL DEFAULT \'1\',
  `token` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `referal` varchar(255) NOT NULL,
  `regip` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `regdate` varchar(255) NOT NULL,
  `permissions` varchar(255) NOT NULL DEFAULT \'0;0\',
  `lastdate` varchar(255) NOT NULL,
  `birth` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2;');
		$db->query('INSERT INTO `awe_users` (`id`, `username`, `name`, `checked`, `password`, `group`, `token`, `mail`, `referal`, `regip`, `ip`, `regdate`, `permissions`, `lastdate`, `birth`) VALUES
(1, \''.addslashes($_POST['username']).'\', \''.addslashes($_POST['name']).'\', \'1\', \''.$functions->crypt(addslashes($_POST['password'])).'\', \'6\', \'\', \''.addslashes($_POST['mail']).'\', \'\', \''.$_SERVER['REMOTE_ADDR'].'\', \'\', \''.date('d.m.Y, в H:i').'\', \'1;1\', \'\', \''.addslashes($_POST['birth']).'\');');
		file_put_contents(ROOT.'engine/data/dbconfig.php','<?php
/* *
 * Ameden Web Engine – Content Management System <http://engine.ameden.net/>
 * Copyright © 2013 Vladislav Balandin <http://www.ameden.net/>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

if(!defined(\'AMEDEN\')) die(\'У вас нет прав на выполнение данного файла!\');

$dbconfig = array(
	\'db_prefix\'             => \'awe\',
	\'db_host\'               => \''.addslashes($_SESSION['db_host']).'\',
	\'db_user\'               => \''.addslashes($_SESSION['db_user']).'\',
	\'db_pass\'               => \''.addslashes($_SESSION['db_pass']).'\',
	\'db_base\'               => \''.addslashes($_SESSION['db_name']).'\'
);
?>');
		unset($_SESSION['title']);
		unset($_SESSION['description']);
		unset($_SESSION['tags']);
		unset($_SESSION['db_host']);
		unset($_SESSION['db_user']);
		unset($_SESSION['db_pass']);
		unset($_SESSION['db_name']);
		$step = 4;
	}
}

switch($step) {
	
	case 0:
		$all['content'] = file_get_contents(ROOT.'install/steps/start.tpl');
		$all['title'] = 'Начало установки';
	break;
	
	case 1:
		$all['content'] = file_get_contents(ROOT.'install/steps/step1.tpl');
		$all['title'] = 'Шаг 1 - Данные о сайте';
	break;
	
	case 2:
		$all['content'] = file_get_contents(ROOT.'install/steps/step2.tpl');
		$all['title'] = 'Шаг 2 - Соединение с MySQL';
	break;
	
	case 3:
		$all['content'] = file_get_contents(ROOT.'install/steps/step3.tpl');
		$all['title'] = 'Шаг 3 - Аккаунт администратора';
	break;
	
	case 4:
		$all['content'] = file_get_contents(ROOT.'install/steps/finish.tpl');
		$all['title'] = 'Завершение установки';
	break;
	
}

$main = file_get_contents(ROOT.'install/main.tpl');

if(isset($e)) $main = str_replace(array('[message]','[/message]'),'',$main);
else $main = preg_replace('~\[message\](.*?)\[/message\]~is','',$main);
$main = str_replace('{message}',$e,$main);

$main = str_replace('{headers}','<script type="text/javascript" src="/engine/include/jQuery-1.9.1.min.js"></script>'.PHP_EOL.'		<meta http-equiv="content-type" content="text/html; charset=\'windows-1251\'">'.PHP_EOL.'		<meta name="robots" content="noindex,nofollow">'.PHP_EOL.'		<title>Установка скрипта » '.$all['title'].'</title>',$main);
$main = str_replace('{THEME}','/install',$main);
$main = str_replace('{title}',$all['title'],$main);
$main = str_replace('{content}',$all['content'],$main);

$main = str_replace('{title}',(isset($_POST['title']))?addslashes($_POST['title']):'',$main);
$main = str_replace('{description}',(isset($_POST['description']))?addslashes($_POST['description']):'',$main);
$main = str_replace('{tags}',(isset($_POST['tags']))?addslashes($_POST['tags']):'',$main);

$main = str_replace('{db_host}',(isset($_POST['db_host']))?addslashes($_POST['db_host']):'localhost:3306',$main);
$main = str_replace('{db_user}',(isset($_POST['db_user']))?addslashes($_POST['db_user']):'',$main);
$main = str_replace('{db_pass}',(isset($_POST['db_pass']))?addslashes($_POST['db_pass']):'',$main);
$main = str_replace('{db_name}',(isset($_POST['db_name']))?addslashes($_POST['db_name']):'',$main);

$main = str_replace('{name}',(isset($_POST['name']))?addslashes($_POST['name']):'',$main);
$main = str_replace('{birth}',(isset($_POST['birth']))?addslashes($_POST['birth']):'',$main);
$main = str_replace('{username}',(isset($_POST['username']))?addslashes($_POST['username']):'',$main);
$main = str_replace('{mail}',(isset($_POST['mail']))?addslashes($_POST['mail']):'',$main);

echo $main;
?>