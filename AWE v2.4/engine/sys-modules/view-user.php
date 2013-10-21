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

$username = isset($_GET['username'])?$_GET['username']:null;
$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$functions->strip($username).'\'');
$resource = $database->fetch_array($query);

if(empty($username) || $username != $resource['username']) {
	$all['p-content'] = $templates->info('error', LANG('USER_NOT_FOUND'));
	$all['p-title'] = 'Ошибка';
} else {
	$referers_query = $database->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_users` WHERE `referal`=\''.$username.'\'');
	if($resource['group'] == '0' || $resource['checked'] == '0') {
		$all['p-content'] = $templates->info('info', LANG('USER_BANNED'));
		$all['p-title'] = 'Информация';
	} else {
		$templates->load('view-user.tpl', 'view-user');
		if($resource['referal'] != '') {
			$templates->assign('str', array('[referal]', '[/referal]'), '', 'view-user');
			$templates->assign('str', '{referal}', $resource['referal'], 'view-user');
			$templates->assign('str', '{referal-link}', '/user/'.$resource['referal'].'/', 'view-user');
		} else { $templates->assign('preg', '~\[referal\](.*?)\[/referal\]~is', '', 'view-user'); }
		$templates->assign('str', '{name}', $resource['name'], 'view-user');
		$templates->assign('str', '{username}', $resource['username'], 'view-user');
		$templates->assign('str', '{group}', $user->getGroup($resource['username']), 'view-user');
		$templates->assign('str', '{reg-date}', $resource['reg-date'], 'view-user');
		$templates->assign('str', '{last-date}', ($resource['last-date'] != '')?$resource['last-date']:'---', 'view-user');
		$templates->assign('str', '{referers}', $database->result($referers_query), 'view-user');
		$all['p-content'] = $templates->display('view-user');
		$all['p-title'] = $resource['username'];
	}
}
?>