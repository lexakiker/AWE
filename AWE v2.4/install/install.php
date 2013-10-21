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
define('INC_CHECK', true);

$error = '';
$step = 0;

require_once(dirname(dirname(__FILE__)).'/defines.php');
require_once(ROOT.'engine/classes/functions.class.php');
require_once(ROOT.'engine/classes/time-zone.class.php');
$functions->initialize();
$time_zone->set('Europe/Moscow');

require(ROOT.'engine/classes/database.class.php');
require(ROOT.'engine/classes/templates.class.php');
$templates->setPath(ROOT.'install/style/');

$ALL_FIELDS_REQUIRED = 'Не все поля заполнены.';

function strip($string) {
	return addslashes($string);
}

if(isset($_POST['goToStep1'])) { $step = 1; }

if(isset($_POST['goToStep2'])) {
	if(empty($_POST['title']) || empty($_POST['description']) || empty($_POST['keywords'])) {
		$error = $ALL_FIELDS_REQUIRED;
		$step = 1;
	} else {
		$_SESSION['title'] = strip($_POST['title']);
		$_SESSION['description'] = strip($_POST['description']);
		$_SESSION['keywords'] = strip($_POST['keywords']);
		$step = 2;
	}
}

if(isset($_POST['goToStep3'])) {
	if(empty($_POST['db-host']) || empty($_POST['db-user']) || empty($_POST['db-base']) || empty($_POST['db-prefix'])) {
		$error = $ALL_FIELDS_REQUIRED;
		$step = 2;
	} else {
		$_SESSION['db-host'] = strip($_POST['db-host']);
		$_SESSION['db-user'] = strip($_POST['db-user']);
		$_SESSION['db-pass'] = strip($_POST['db-pass']);
		$_SESSION['db-base'] = strip($_POST['db-base']);
		$_SESSION['db-prefix'] = strip($_POST['db-prefix']);
		if(isset($_POST['drop-tables'])) {
			$_SESSION['drop-tables'] = true;
		} else { $_SESSION['drop-tables'] = false; }
		$step = 3;
	}
}

if(isset($_POST['goToFinish'])) {
	if(empty($_POST['name']) || empty($_POST['birth']) || empty($_POST['username']) || empty($_POST['admin-mail']) || empty($_POST['password'])) {
		$error = $ALL_FIELDS_REQUIRED;
		$step = 3;
	} else {
		if(empty($_SESSION['db-host'])) { $functions->spaceTo('/'.ENGINE_PATH.'install/install.php'); }
		$database->connect($_SESSION['db-host'], $_SESSION['db-user'], $_SESSION['db-pass'], $_SESSION['db-base']);
		if($_SESSION['drop-tables']) {
			// $database->query('DROP TABLE IF EXISTS `'.$_SESSION['db-prefix'].'_bans`;');
			// $database->query('DROP TABLE IF EXISTS `'.$_SESSION['db-prefix'].'_pm`;');
			// $database->query('DROP TABLE IF EXISTS `'.$_SESSION['db-prefix'].'_tickets`;');
			$database->query('DROP TABLE IF EXISTS `'.$_SESSION['db-prefix'].'_comments`;');
			$database->query('DROP TABLE IF EXISTS `'.$_SESSION['db-prefix'].'_config`;');
			$database->query('DROP TABLE IF EXISTS `'.$_SESSION['db-prefix'].'_news`;');
			$database->query('DROP TABLE IF EXISTS `'.$_SESSION['db-prefix'].'_passwords`;');
			$database->query('DROP TABLE IF EXISTS `'.$_SESSION['db-prefix'].'_static`;');
			$database->query('DROP TABLE IF EXISTS `'.$_SESSION['db-prefix'].'_users`;');
		}
		$database->query('CREATE TABLE `'.$_SESSION['db-prefix'].'_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `news-id` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$database->query('CREATE TABLE IF NOT EXISTS `'.$_SESSION['db-prefix'].'_config` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `setting` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=19;');
		$database->query('INSERT INTO `'.$_SESSION['db-prefix'].'_config` (`id`, `setting`, `value`) VALUES
(1, \'title\', \''.$_SESSION['title'].'\'),
(2, \'description\', \''.$_SESSION['description'].'\'),
(3, \'keywords\', \''.$_SESSION['keywords'].'\'),
(4, \'off-reason\', \'На сайте ведутся Технические работы! Заходите к нам позже!\'),
(5, \'admin-mail\', \''.strip($_POST['admin-mail']).'\'),
(6, \'theme\', \'default\'),
(7, \'time-zone\', \'Europe/Moscow\'),
(8, \'x-img-width\', \'200px\'),
(9, \'x-img-height\', \'200px\'),
(10, \'news-on-page\', \'10\'),
(11, \'comments-on-page\', \'10\'),
(12, \'admin-group\', \'6\'),
(13, \'off-site\', \'false\'),
(14, \'reg-one-ip\', \'true\'),
(15, \'write-user-passwords\', \'false\'),
(16, \'reg-mail-accept\', \'true\'),
(17, \'send-mail-oncomment\', \'false\'),
(18, \'comment-max-sym\', \'140\');');
		$database->query('CREATE TABLE IF NOT EXISTS `'.$_SESSION['db-prefix'].'_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `x-image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short-story` text NOT NULL,
  `full-story` text NOT NULL,
  `date` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `views` varchar(255) NOT NULL DEFAULT \'0\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$database->query('CREATE TABLE IF NOT EXISTS `'.$_SESSION['db-prefix'].'_passwords` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$database->query('CREATE TABLE IF NOT EXISTS `'.$_SESSION['db-prefix'].'_static` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `url` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;');
		$database->query('CREATE TABLE IF NOT EXISTS `'.$_SESSION['db-prefix'].'_users` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `checked` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL DEFAULT \'1\',
  `token` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `referal` varchar(255) NOT NULL,
  `reg-ip` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `reg-date` varchar(255) NOT NULL,
  `permissions` varchar(255) NOT NULL DEFAULT \'0;0\',
  `last-date` varchar(255) NOT NULL,
  `birth` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2;');
		$database->query('INSERT INTO `'.$_SESSION['db-prefix'].'_users` (`id`, `username`, `name`, `checked`, `password`, `group`, `token`, `mail`, `referal`, `reg-ip`, `ip`, `reg-date`, `permissions`, `last-date`, `birth`) VALUES
(1, \''.strip($_POST['username']).'\', \''.strip($_POST['name']).'\', \'1\', \''.$functions->crypt(strip($_POST['password'])).'\', \'6\', \'\', \''.strip($_POST['admin-mail']).'\', \'\', \''.$_SERVER['REMOTE_ADDR'].'\', \'\', \''.$functions->curDate().'\', \'1;1\', \'\', \''.strip($_POST['birth']).'\');');
		file_put_contents(ROOT.'engine/data/db-config.php','<?php
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

if(!defined(\'INC_CHECK\')) { die(\'Scat!\'); }

$db_config = array(
	\'db-prefix\'             => \''.$_SESSION['db-prefix'].'\',
	\'db-host\'               => \''.$_SESSION['db-host'].'\',
	\'db-user\'               => \''.$_SESSION['db-user'].'\',
	\'db-pass\'               => \''.$_SESSION['db-pass'].'\',
	\'db-base\'               => \''.$_SESSION['db-base'].'\'
);
?>');
		unset($_SESSION['title']);
		unset($_SESSION['description']);
		unset($_SESSION['keywords']);
		unset($_SESSION['db-host']);
		unset($_SESSION['db-user']);
		unset($_SESSION['db-pass']);
		unset($_SESSION['db-base']);
		unset($_SESSION['db-prefix']);
		unset($_SESSION['drop-tables']);
		$step = 4;
	}
}

switch($step) {
	
	case 0:
		$templates->load('start.tpl', 'start');
		$all['p-content'] = $templates->display('start');
		$all['p-title'] = 'Начало установки';
	break;
	
	case 1:
		$templates->load('step1.tpl', 'step1');
		$templates->assign('str', '{title}', isset($_POST['title'])?strip($_POST['title']):'', 'step1');
		$templates->assign('str', '{description}', isset($_POST['description'])?strip($_POST['description']):'', 'step1');
		$templates->assign('str', '{keywords}', isset($_POST['keywords'])?strip($_POST['keywords']):'', 'step1');
		$all['p-content'] = $templates->display('step1');
		$all['p-title'] = 'Шаг 1 - Данные о сайте';
	break;
	
	case 2:
		$templates->load('step2.tpl', 'step2');
		$templates->assign('str', '{db-host}', isset($_POST['db-host'])?strip($_POST['db-host']):'localhost:3306', 'step2');
		$templates->assign('str', '{db-user}', isset($_POST['db-user'])?strip($_POST['db-user']):'', 'step2');
		$templates->assign('str', '{db-pass}', isset($_POST['db-pass'])?strip($_POST['db-pass']):'', 'step2');
		$templates->assign('str', '{db-base}', isset($_POST['db-base'])?strip($_POST['db-base']):'', 'step2');
		$templates->assign('str', '{db-prefix}', isset($_POST['db-prefix'])?strip($_POST['db-prefix']):'awe', 'step2');
		$all['p-content'] = $templates->display('step2');
		$all['p-title'] = 'Шаг 2 - Соединение с MySQL';
	break;
	
	case 3:
		$templates->load('step3.tpl', 'step3');
		$templates->assign('str', '{name}', isset($_POST['name'])?strip($_POST['name']):'', 'step3');
		$templates->assign('str', '{birth}', isset($_POST['birth'])?strip($_POST['birth']):'', 'step3');
		$templates->assign('str', '{username}', isset($_POST['username'])?strip($_POST['username']):'', 'step3');
		$templates->assign('str', '{admin-mail}', isset($_POST['admin-mail'])?strip($_POST['admin-mail']):'', 'step3');
		$all['p-content'] = $templates->display('step3');
		$all['p-title'] = 'Шаг 3 - Аккаунт администратора';
	break;
	
	case 4:
		$templates->load('finish.tpl', 'finish');
		$templates->assign('str', '{admin-panel}', '/'.ENGINE_PATH.'admin/', 'finish');
		$templates->assign('str', '{show-site}', '/'.ENGINE_PATH, 'finish');
		$all['p-content'] = $templates->display('finish');
		$all['p-title'] = 'Завершение установки';
	break;
	
}

$templates->load('main.tpl', 'index');

if($error != '') {
	$templates->assign('str', array('[message]', '[/message]'), '', 'index');
	$templates->assign('str', '{message}', $error, 'index');
} else { $templates->assign('preg', '~\[message\](.*?)\[/message\]~is', '', 'index'); }

$templates->assign('str', '{p-content}', $all['p-content'], 'index');
$templates->assign('str', '{THEME}', '/'.ENGINE_PATH.'install/style', 'index');
$templates->assign('str', '{version}', VERSION, 'index');
$templates->assign('str', '{site-name}', 'Установка скрипта', 'index');
$templates->assign('str', '{p-title}', $all['p-title'], 'index');
$templates->assign('str', '{p-headers}', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="Author" content="RevenHell">
		<meta name="Generator" content="Ameden Web Engine ('.VERSION.')">
		<meta name="Robots" content="noindex,nofollow">
		<script type="text/javascript" src="/'.ENGINE_PATH.'engine/include/js/jQuery-1.9.1.min.js"></script>', 'index');

echo $templates->display('index');
?>