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
define('AMEDEN',true);
require(dirname(__FILE__).'/engine/engine.php');

$tmp->theme('main.tpl','index');
$tmp->assign('{headers}','<link rel="stylesheet" href="/themes/'.$db->getParam('theme').'/engine/css/engine.css">'.PHP_EOL.'		<script type="text/javascript" src="/engine/include/jQuery-1.9.1.min.js"></script>'.PHP_EOL.'		<meta http-equiv="content-type" content="text/html; charset=\'windows-1251\'">'.PHP_EOL.'		<meta name="author" content="RevenHell">'.PHP_EOL.'		<meta name="description" content="'.$db->getParam('description').'">'.PHP_EOL.'		<meta name="keywords" content="'.$db->getParam('tags').'">'.PHP_EOL.'		<meta name="generator" content="Ameden Web Engine '.VERSION.'">'.PHP_EOL.'		<meta name="robots" content="index,follow">'.PHP_EOL.'		<title>'.$all['title'].' » '.$db->getParam('title').$all['offline'].'</title>','index');
$tmp->assign('{info}',$all['info'],'index');
$tmp->assign('{content}',$all['content'],'index');
$tmp->assign('{login}',$all['login'],'index');
$tmp->preg_callback('/\{include_file="(.*?)\"}/',array($tmp,'file_inc'),'index');
$tmp->preg_callback('/\{config="(.*?)"\}/','getParam','index');
$tmp->preg_assign('/\{static_page="(.*?)"\}/','/do/$1','index');
$tmp->assign('{THEME}','/themes/'.$db->getParam('theme'),'index');
$tmp->assign('{logout-link}','/logout/index','index');
$tmp->assign('{admin-link}','/admin','index');
$tmp->assign('{registration-link}','/registration','index');
$tmp->assign('{forgot-link}','/forgotpass','index');
$tmp->assign('{terms-link}','/terms','index');
$tmp->assign('{cabinet-link}','/account','index');
$tmp->assign('{statistics-link}','/statistics','index');
$tmp->assign('{feedback-link}','/feedback','index');
$tmp->assign('{version}',VERSION,'index');
$tmp->assign('{profile-link}','/user/'.$user->get_array('username'),'index');
if($user->get_array('group') == $db->getParam('admin_group') || $user->get_permission('allow_admin') == '1') $tmp->assign(array('[admin]','[/admin]'),'','index');
else $tmp->preg_assign('~\[admin\](.*?)\[/admin\]~is','','index');
if($user->check_logged()) {
	$tmp->assign(array('[logged]','[/logged]'),'','index');
	$tmp->preg_assign('~\[!logged\](.*?)\[/!logged\]~is','','index');
} else {
	$tmp->assign(array('[!logged]','[/!logged]'),'','index');
	$tmp->preg_assign('~\[logged\](.*?)\[/logged\]~is','','index');
}
echo $tmp->display('index',true);

$db->close();
?>