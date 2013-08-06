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

$username = (isset($_GET['username']))?$_GET['username']:'';
$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$functions->strip($username).'\'');
$resource = $db->fetch_array($query);
if(empty($username) || $username != $resource['username']) {
	$all['content'] = $tmp->info('info',MSG('USER_NOT_FOUND'));
	$all['title'] = 'Информация';
} else {
	$tmp->theme('viewuser.tpl','viewuser');
	
	if($resource['referal'] != '') $tmp->assign(array('[referal]','[/referal]'),'','viewuser');
	else $tmp->preg_assign('~\[referal\](.*?)\[/referal\]~is','','viewuser');
	
	$tmp->assign('{name}',$resource['name'],'viewuser');
	$tmp->assign('{username}',$resource['username'],'viewuser');
	$tmp->assign('{group}',$user->get_group($resource['username']),'viewuser');
	$tmp->assign('{referal}',$resource['referal'],'viewuser');
	$tmp->assign('{regdate}',$resource['regdate'],'viewuser');
	$tmp->assign('{lastdate}',$resource['lastdate'],'viewuser');
	
	$all['content'] = $tmp->display('viewuser');
	$all['title'] = $resource['username']; 
}
?>