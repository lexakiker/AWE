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

$username = $functions->strip($_GET['username']);
$code = $functions->strip($_GET['code']);
$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$username.'\'');
$resource = $database->fetch_array($query);
$all['p-robots'] = 'noindex,nofollow';

$templates->load('activation.tpl','index');
if(isset($code) && isset($username)) {
	if($resource['checked'] == '1') {
		$templates->assign('str', '{message}', LANG('ACCOUNT_ALREADY_ACTIVATED'), 'index');
	} elseif($code == $functions->crypt('--'.$resource['password'].'--')) {
		$database->query('UPDATE `'.DB_PREFIX.'_users` SET `checked`=\'1\' WHERE `username`=\''.$username.'\'');
		$templates->assign('str', '{message}', LANG('ACCOUNT_WERE_ACTIVATED'), 'index');
	} else {
		$templates->assign('str', '{message}', LANG('ACCOUNT_ACTIVATION_ERROR'), 'index');
	}
} else {
	$templates->assign('str', '{message}', LANG('ACCOUNT_ACTIVATION_ERROR'), 'index');
}
$templates->clear('index');
?>