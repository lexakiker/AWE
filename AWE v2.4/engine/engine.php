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

if(!defined('INC_CHECK')) { die('Scat!'); }

include(ROOT.'engine/data/lang.php');
include(ROOT.'engine/data/mail.php');
if(file_exists(ROOT.'engine/data/db-config.php')) {
	include(ROOT.'engine/data/db-config.php');
	define('DB_PREFIX', $db_config['db-prefix']);
	$db_inc = false;
} else { $db_inc = true; }

include(ROOT.'engine/classes/functions.class.php');
include(ROOT.'engine/classes/time-zone.class.php');
if($db_inc) { $functions->spaceTo('/'.ENGINE_PATH.'install/install.php'); }
$functions->initialize();

include(ROOT.'engine/classes/database.class.php');
$database->connect($db_config['db-host'], $db_config['db-user'], $db_config['db-pass'], $db_config['db-base']);
$time_zone->set($database->getParam('time-zone'));

function getParam($paramName) {
	global $database, $functions;
	if($database->getParam($paramName[1]) != '') {
		return $database->getParam($paramName[1]);
	} else {
		return $functions->mistake('Ошибка! Параметра "<b>'.$paramName[1].'</b>" не существует!', false);
	}
}

include(ROOT.'engine/classes/api.class.php');
include(ROOT.'engine/classes/templates.class.php');
include(ROOT.'engine/classes/mail.class.php');
include(ROOT.'engine/classes/user.class.php');
$user->update();

if($action != 'admin') { $templates->setPath(ROOT.'themes/'.$database->getParam('theme').'/'); }

if($action == 'error-404') {
	$all['robots'] = 'noindex,nofollow';
	if(strlen($_SERVER['REQUEST_URI']) > 30) {
		$url = substr($_SERVER['REQUEST_URI'], 30);
		$url = strlen($_SERVER['REQUEST_URI'])-strlen($url);
		$url = substr($_SERVER['REQUEST_URI'], 0, 1+$url).'...';
	} else { $url = $_SERVER['REQUEST_URI']; }
	$templates->load('error-404.tpl', 'index');
	$templates->assign('str', '{substr-url}', 'http://'.$_SERVER['HTTP_HOST'].'/'.ENGINE_PATH.substr($url, 1), 'index');
	$templates->assign('str', '{full-url}', 'http://'.$_SERVER['HTTP_HOST'].'/'.ENGINE_PATH.substr($_SERVER['REQUEST_URI'], 1), 'index');
	$templates->clear('index');
}

if($action != 'admin') {
	$templates->load('login.tpl', 'login');
	if(strlen($user->getArray('username')) > 10) {
		$username = substr($user->getArray('username'), 10);
		$username = strlen($user->getArray('username'))-strlen($username);
		$username = substr($user->getArray('username'), 0, $username-3).'...';
	} else { $username = $user->getArray('username'); }
	$templates->assign('str', '{substr-username}', $username, 'login');
	$templates->assign('str', '{full-username}', $user->getArray('username'), 'login');
	$all['p-login'] = $templates->display('login');
	if($database->getParam('off-site') == 'true') {
		if($user->userAdmin() || $user->getPermission('allow-offline') == '1' || $user->getPermission('allow-admin') == '1') {
			$all['p-suffix'] = ' [offline]';
		} else {
			$templates->load('off-line.tpl', 'index');
			$templates->assign('str', '{off-reason}', $database->getParam('off-reason'), 'index');
			$templates->clear('index');
		}
	}
}

switch($action) {
	
 	case 'admin':           include(SYS_MODULES_ROOT.'admin.php');           break;
	case 'news':            include(SYS_MODULES_ROOT.'news-full.php');       break;
	case 'view-user':       include(SYS_MODULES_ROOT.'view-user.php');       break;
 	case 'statistics':      include(SYS_MODULES_ROOT.'statistics.php');      break;
 	case 'registration':    include(SYS_MODULES_ROOT.'registration.php');    break;
 	case 'account':         include(SYS_MODULES_ROOT.'account.php');         break;
 	case 'static':          include(SYS_MODULES_ROOT.'static.php');          break;
 	case 'forgot-password': include(SYS_MODULES_ROOT.'forgot-password.php'); break;
 	case 'feed-back':       include(SYS_MODULES_ROOT.'feed-back.php');       break;
	case 'activation':      include(SYS_MODULES_ROOT.'activation.php');      break;
 	case 'terms':           include(SYS_MODULES_ROOT.'terms.php');           break;
	case 'logout':          include(SYS_MODULES_ROOT.'logout.php');          break;
	case 'invite':          include(SYS_MODULES_ROOT.'invite.php');          break;
	case 'nope-right':      include(SYS_MODULES_ROOT.'nope-right.php');      break;
	
	default:                include(SYS_MODULES_ROOT.'news-short.php');      break;
	
}
?>