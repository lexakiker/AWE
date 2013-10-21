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

$query = $database->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_users` WHERE `referal`=\''.$user->getArray('username').'\'');
if($user->checkLogged()) {
	$all['p-title'] = 'Личный кабинет';
} else { $all['p-title'] = 'Вход в систему'; }

if(isset($_POST['login-send'])) {
	switch($user->checkAuth($functions->strip($_POST['username']), $functions->strip($_POST['password']))) {
		case 1: $error = $templates->info('error', LANG('ALL_FIELDS_REQUIRED')); break;
		case 2: $error = $templates->info('error', LANG('AUTHORIZATION_ERROR')); break;
		case 3: $error = $templates->info('error', LANG('ACCOUNT_IS_BANNED')); break;
		case 4: $error = $templates->info('error', LANG('ACCOUNT_IS_NOT_CONFIRM')); break;
		case 5:
			$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$functions->strip($_POST['username']).'\' AND `password`=\''.$functions->strip($functions->crypt($_POST['password'])).'\'');
			$resource = $database->fetch_array($query);
			if(isset($_POST['remember'])) {
				$token = $functions->generateToken($functions->strip($_POST['username']));
				setcookie('token', $token, time()+2592000, '/', $_SERVER['HTTP_HOST']);
				$database->query('UPDATE `'.DB_PREFIX.'_users` SET `token`=\''.$token.'\' WHERE `username`=\''.$functions->strip($_POST['username']).'\'');
			}
			$_SESSION['username'] = $resource['username'];
			$functions->spaceTo('/'.ENGINE_PATH.'account/');
		break;
	}
}

if(isset($_POST['data-send'])) {
	switch($user->checkData($functions->strip($_POST['name']), $functions->strip($_POST['mail']))) {
		case 1: $error = $templates->info('error', LANG('ALL_FIELDS_REQUIRED')); break;
		case 11: $error = $templates->info('error', LANG('NAME_BIG_LENGTH')); break;
		case 2: $error = $templates->info('error', LANG('EMAIL_IS_INVALID')); break;
		case 3:
			$database->query('UPDATE `'.DB_PREFIX.'_users` SET `name`=\''.$functions->strip($_POST['name']).'\', `mail`=\''.$functions->strip($_POST['mail']).'\' WHERE `username`=\''.$user->getArray('username').'\'');
			$error = $templates->info('success', LANG('DATA_WERE_CHANGED'));
		break;
	}
}

if(isset($_POST['changePassword-send'])) {
	switch($user->checkChange_password($functions->strip($_POST['old-password']), $functions->strip($_POST['new-password']), $functions->strip($_POST['re-new-password']))) {
		case 1: $error = $templates->info('error', LANG('ALL_FIELDS_REQUIRED')); break;
		case 2: $error = $templates->info('error', LANG('OLDPASSWORD_IS_INVALID')); break;
		case 3: $error = $templates->info('error', LANG('NEWPASSWORD_IS_INVALID')); break;
		case 4: $error = $templates->info('error', LANG('PASSWORDS_DO_NOT_MATCH')); break;
		case 5:
			$error = $templates->info('success', LANG('PASSWORD_WERE_CHANGED'));
			if($database->getParam('write-user-passwords') == 'true') { $database->query('UPDATE `'.DB_PREFIX.'_password` SET `password`=\''.$functions->strip($_POST['new-password']).'\' WHERE `username`=\''.$user->getArray('username').'\''); }
			$database->query('UPDATE `'.DB_PREFIX.'_users` SET `password`=\''.$functions->crypt($functions->strip($_POST['new-password'])).'\' WHERE `username`=\''.$user->getArray('username').'\'');
		break;
	}
}

$birth_arr = explode('.', $user->getArray('birth'));
$birthday = false;
if($functions->curDay()+1 == $birth_arr[0] && $functions->curMonth() == $birth_arr[1]) {
	$birth = LANG('TOMORROW_BIRTHDAY');
} else {
	if($functions->curDay() == $birth_arr[0] && $functions->curMonth() == $birth_arr[1]) {
		$birthday = true;
		$birth = LANG('NOW_BIRTHDAY');
		$age = $functions->curYear()-$birth_arr[2];
		// Здесь можно прописать какой нибудь бонус для именинника, а затем написать про него в "account.tpl" между тегами "[birthday]" :)
		// Ps. Можно еще написать систему, что-бы если именинника не было на сайте в день его рождения, то бонус зачислился бы позже...
	} else { $birth = $user->getArray('birth'); }
}

$templates->load('account.tpl', 'account');
if($database->getParam('hide-login-box') == 'true') { $all['login'] = ''; }
if($user->getArray('referal') != '') {
	$templates->assign('str', array('[referal]', '[/referal]'), '', 'account');
	$templates->assign('str', '{referal}', $user->getArray('referal'), 'account');
	$templates->assign('str', '{referal-link}', '/user/'.$user->getArray('referal').'/', 'account');
} else { $templates->assign('preg', '~\[referal\](.*?)\[/referal\]~is', '', 'account'); }
if($birthday) {
	$templates->assign('str', array('[birthday]', '[/birthday]'), '', 'account');
	$templates->assign('str', '{age}', $age, 'account');
} else { $templates->assign('preg', '~\[birthday\](.*?)\[/birthday\]~is', '', 'account'); }
$templates->assign('str', '{name}', $user->getArray('name'), 'account');
$templates->assign('str', '{mail}', $user->getArray('mail'), 'account');
$templates->assign('str', '{name-value}', isset($_POST['name'])?$functions->strip($_POST['name']):$user->getArray('name'), 'account');
$templates->assign('str', '{mail-value}', isset($_POST['mail'])?$functions->strip($_POST['mail']):$user->getArray('mail'), 'account');
$templates->assign('str', '{username}', isset($_POST['username'])?$functions->strip($_POST['username']):$user->getArray('username'), 'account');
$templates->assign('str', '{password}', isset($_POST['password'])?$functions->strip($_POST['password']):'', 'account');
$templates->assign('str', '{group}', $user->getGroup($user->getArray('username')), 'account');
$templates->assign('str', '{reg-ip}', $user->getArray('reg-ip'), 'account');
$templates->assign('str', '{birth}', $birth, 'account');
$templates->assign('str', '{reg-date}', $user->getArray('reg-date'), 'account');
$templates->assign('str', '{now-ip}', $user->getArray('ip'), 'account');
$templates->assign('str', '{last-date}', $user->getArray('last-date'), 'account');
$templates->assign('str', '{referers}', $database->result($query), 'account');
$templates->assign('str', '{invite-link}', 'http://'.$_SERVER['HTTP_HOST'].'/invite/'.$user->getArray('id').'/', 'account');
$all['p-content'] = $templates->display('account');
$all['p-info'] = $error;
?>