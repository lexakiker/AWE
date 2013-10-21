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

require_once(dirname(__FILE__).'/defines.php');
require_once(dirname(__FILE__).'/engine/engine.php');

$parse_arr = array(
	'links' => array(
		'{main-link}' => '/'.ENGINE_PATH,
		'{admin-link}' => '/'.ENGINE_PATH.'admin/',
		'{registration-link}' => '/'.ENGINE_PATH.'registration/',
		'{forgot-link}' => '/'.ENGINE_PATH.'forgot-password/',
		'{terms-link}' => '/'.ENGINE_PATH.'terms/',
		'{cabinet-link}' => '/'.ENGINE_PATH.'account/',
		'{statistics-link}' => '/'.ENGINE_PATH.'statistics/',
		'{feed-back-link}' => '/'.ENGINE_PATH.'feed-back/',
		'{logout-link}' => '/'.ENGINE_PATH.'logout/index/',
		'{profile-link}' => '/'.ENGINE_PATH.'user/'.$user->getArray('username').'/'
	),
	'main' => array(
		'{p-content}' => $all['p-content'],
		'{p-title}' => $all['p-title'],
		'{p-info}' => $all['p-info'],
		'{site-name}' => $database->getParam('title').$all['p-suffix'],
		'{login}' => $all['p-login'],
		'{p-description}' => $database->getParam('description'),
		'{p-keywords}' => $database->getParam('keywords'),
		'{THEME}' => '/'.ENGINE_PATH.'themes/'.$database->getParam('theme'),
		'{version}' => VERSION,
		
		'{p-headers}' => '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="Author" content="RevenHell">
		<meta name="Description" content="'.$database->getParam('description').'">
		<meta name="Keywords" content="'.$database->getParam('keywords').'">
		<meta name="Generator" content="Ameden Web Engine ('.VERSION.')">
		<meta name="Robots" content="'.$all['p-robots'].'">
		<script type="text/javascript" src="/'.ENGINE_PATH.'engine/include/js/jQuery-1.9.1.min.js"></script>'
	)
);

$templates->load('main.tpl', 'index');

require_once(ROOT.'/engine/res-modules.php');

$templates->assign('str', array_keys($parse_arr['main']), array_values($parse_arr['main']), 'index');
$templates->assign('callback', '/\{include-file="(.*?)"\}/', array($templates, 'includeFile'), 'index');

$templates->assign('str', array_keys($parse_arr['links']), array_values($parse_arr['links']), 'index');

$templates->assign('callback', '/\{config="(.*?)"\}/', 'getParam', 'index');
$templates->assign('callback', '~\[info="(.*?)"](.*?)\[\/info]~is', array($templates, 'infoTag'), 'index');
$templates->assign('callback', '~\[module="(.*?)"](.*?)\[\/module]~is', array($api, 'checkModule'), 'index');
$templates->assign('callback', '~\[available="(.*?)"](.*?)\[\/available]~is', array($functions, 'available'), 'index');

$templates->assign('preg', '/\{static-page="(.*?)"\}/', '/do/$1/', 'index');
$templates->assign('preg', '/\{\* (.*?) \*}/', '', 'index');

if($user->userAdmin() || $user->getPermission('allow-admin') == '1') {
	$templates->assign('str', array('[admin]','[/admin]'), '', 'index');
} else {
	$templates->assign('preg', '~\[admin\](.*?)\[/admin\]~is', '', 'index');
}

if($user->checkLogged()) {
	$templates->assign('str', array('[logged]', '[/logged]'), '', 'index');
	$templates->assign('preg', '~\[!logged\](.*?)\[/!logged\]~is', '', 'index');
} else {
	$templates->assign('str', array('[!logged]', '[/!logged]'), '', 'index');
	$templates->assign('preg', '~\[logged\](.*?)\[/logged\]~is', '', 'index');
}

echo $templates->display('index', true);

$database->close();
?>