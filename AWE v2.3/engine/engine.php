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

if(!defined('AMEDEN')) die('У вас нет прав на выполнение данного файла!');

ini_set('error_reporting',E_ALL);
ini_set('display_errors',true);
ini_set('html_errors',true);

define('USER_IP',$_SERVER['REMOTE_ADDR']);
define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/');

define('VERSION','2.3');
define('COPYRIGHT','<!-- Ameden Web Engine ('.VERSION.') © 2012-2013 | www.awe.ameden.net -->');

include(ROOT.'engine/data/messages.php');
include(ROOT.'engine/data/mail_messages.php');
if(file_exists(ROOT.'engine/data/dbconfig.php')) {
	include(ROOT.'engine/data/dbconfig.php');
	define('DB_PREFIX',$dbconfig['db_prefix']);
}

include(ROOT.'engine/classes/functions.class.php');
include(ROOT.'engine/classes/db.class.php');
if(!file_exists(ROOT.'engine/data/dbconfig.php')) $functions->spaceTo('/install/install.php');
$db->connect($dbconfig['db_host'],$dbconfig['db_user'],$dbconfig['db_pass'],$dbconfig['db_base']);
function getParam($input) { global $db, $functions; if($db->getParam($input[1]) != '') return $db->getParam($input[1]); else return $functions->mistake('Ошибка! Параметра "'.$input[1].'" не существует!',false); }
date_default_timezone_set($db->getParam('timezone'));

include(ROOT.'engine/classes/tmp.class.php');
include(ROOT.'engine/classes/mail.class.php');
include(ROOT.'engine/classes/user.class.php');
$user->update();

$action = (isset($_GET['action']))?$_GET['action']:'';

if($action != 'admin') {
	$all = array('offline' => '', 'title' => '', 'info' => '', 'content' => '', 'login' => str_replace('{username}',$user->get_array('username'),file_get_contents(ROOT.'themes/'.$db->getParam('theme').'/login.tpl')));
	if($db->getParam('die_site') == 'true') {
		if($user->get_array('group') == $db->getParam('admin_group') || $user->get_permission('allow_offline') == '1' || $user->get_permission('allow_admin') == '1') $all['offline'] = ' [offline]';
		else {
			$tmp->theme('offline.tpl','index');
			$tmp->assign('{reason}',$db->getParam('die_reason'),'index');
			$tmp->clear('index');
		}
	}
}

switch($action) {
	
 	case 'admin': include(ROOT.'engine/modules/admin.php'); die(); break;
	
	default: include(ROOT.'engine/modules/news.short.php'); break;
	
	case 'news': include(ROOT.'engine/modules/news.full.php'); break;
	
	case 'viewuser': include(ROOT.'engine/modules/viewuser.php'); break;
	
 	case 'statistics': include(ROOT.'engine/modules/statistics.php'); break;
	
 	case 'registration': include(ROOT.'engine/modules/registration.php'); break;
	
 	case 'account': include(ROOT.'engine/modules/account.php'); break;
	
 	case 'static': include(ROOT.'engine/modules/static.php'); break;
	
 	case 'forgotpass': include(ROOT.'engine/modules/forgotpass.php'); break;
	
 	case 'feedback': include(ROOT.'engine/modules/feedback.php'); break;
	
	case 'activation': include(ROOT.'engine/modules/activation.php'); break;
	
 	case 'terms':
		$tmp->theme('terms.tpl','terms');
		$all['content'] = $tmp->display('terms');
		$all['title'] = 'Правила';
	break;
	
	case 'logout':
		if(isset($_GET['url'])) {
			$user->logout();
			if($_GET['url'] == 'admin') $functions->spaceTo('/admin');
			elseif($_GET['url'] == 'index') $functions->spaceTo('/');
		}
	break;
	
	case 'error404':
		$tmp->theme('error404.tpl','index');
		$tmp->assign('{url}','http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],'index');
		$tmp->clear('index');
	break;
	
 	case 'noperight':
		$all['title'] = 'Ошибка';
		$all['info'] = $tmp->info('info',$messages['NOPE_RIGHT']);
	break;
	
}
?>