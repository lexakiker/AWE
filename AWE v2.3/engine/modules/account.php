<?php
/* *
 * Ameden Web Engine Ц Content Management System <http://engine.ameden.net/>
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

if(!defined('AMEDEN')) die('” вас нет прав на выполнение данного файла!');

$e = '';

if(isset($_POST['login_send'])) {
	switch($user->check_auth($functions->strip($_POST['username']),$functions->strip($_POST['password']))) {
		case 1: $e = $tmp->info('error',MSG('ALL_FIELDS_REQUIRED')); break;
		case 2: $e = $tmp->info('error',MSG('AUTHORIZATION_ERROR')); break;
		case 3: $e = $tmp->info('error',MSG('ACCOUNT_IS_BANNED')); break;
		case 4: $e = $tmp->info('error',MSG('ACCOUNT_IS_NOT_CONFIRM')); break;
		case 5:
			$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$functions->strip($_POST['username']).'\' AND `password`=\''.$functions->strip($functions->crypt($_POST['password'])).'\'');
			$resource = $db->fetch_array($query);
			if(isset($_POST['remember'])) {
				$token = md5(time().$functions->strip($_POST['username']));
				setcookie('token',$token,time()+2592000,'/',$_SERVER['HTTP_HOST']);
				$db->query('UPDATE `'.DB_PREFIX.'_users` SET `token`=\''.$token.'\' WHERE `username`=\''.$functions->strip($_POST['username']).'\'');
			}
			$_SESSION['username'] = $resource['username'];
			$functions->spaceTo('/account');
		break;
	}
}

if(isset($_POST['data_send'])) {
	switch($user->check_data($functions->strip($_POST['name']),$functions->strip($_POST['mail']))) {
		case 1: $e = $tmp->info('error',MSG('ALL_FIELDS_REQUIRED')); break;
		case 2: $e = $tmp->info('error',MSG('EMAIL_IS_INVALID')); break;
		case 3:
			$db->query('UPDATE `'.DB_PREFIX.'_users` SET `name`=\''.$functions->strip($_POST['name']).'\', `mail`=\''.$functions->strip($_POST['mail']).'\' WHERE `username`=\''.$user->get_array('username').'\'');
			$e = $tmp->info('success',MSG('DATA_WERE_CHANGED'));
		break;
	}
}

if(isset($_POST['password_send'])) {
	switch($user->check_newpassword($functions->strip($_POST['oldpassword']),$functions->strip($_POST['newpassword']),$functions->strip($_POST['renewpassword']))) {
		case 1: $e = $tmp->info('error',MSG('ALL_FIELDS_REQUIRED')); break;
		case 2: $e = $tmp->info('error',MSG('OLDPASSWORD_IS_INVALID')); break;
		case 3: $e = $tmp->info('error',MSG('NEWPASSWORD_IS_INVALID')); break;
		case 4: $e = $tmp->info('error',MSG('PASSWORDS_DO_NOT_MATCH')); break;
		case 5: $e = $tmp->info('success',MSG('PASSWORD_WERE_CHANGED')); if($db->getParam('write_user_passwords')) $db->query('UPDATE `'.DB_PREFIX.'_password` SET `password`=\''.$functions->strip($_POST['newpassword']).'\' WHERE `username`=\''.$user->get_array('username').'\''); $db->query('UPDATE `'.DB_PREFIX.'_users` SET `password`=\''.$functions->crypt($functions->strip($_POST['newpassword'])).'\' WHERE `username`=\''.$user->get_array('username').'\''); break;
	}
}

$tmp->theme('account.tpl','account');

if($db->getParam('hide_login_box') == 'true') $all['login'] = '';

if($user->get_array('referal') != '') $tmp->assign(array('[referal]','[/referal]'),'','account');
else $tmp->preg_assign('~\[referal\](.*?)\[/referal\]~is','','account');

$tmp->assign('{name}',$user->get_array('name'),'account');
$tmp->assign('{mail}',$user->get_array('mail'),'account');
$tmp->assign('{name_value}',(isset($_POST['name']))?$functions->strip($_POST['name']):$user->get_array('name'),'account');
$tmp->assign('{mail_value}',(isset($_POST['mail']))?$functions->strip($_POST['mail']):$user->get_array('mail'),'account');
$tmp->assign('{username}',(isset($_POST['username']))?$functions->strip($_POST['username']):$user->get_array('username'),'account');
$tmp->assign('{password}',(isset($_POST['password']))?$functions->strip($_POST['password']):'','account');
$tmp->assign('{group}',$user->get_group($user->get_array('username')),'account');
$tmp->assign('{regip}',$user->get_array('regip'),'account');
$tmp->assign('{birth}',$user->get_array('birth'),'account');
$tmp->assign('{regdate}',$user->get_array('regdate'),'account');
$tmp->assign('{nowip}',$user->get_array('ip'),'account');
$tmp->assign('{lastdate}',$user->get_array('lastdate'),'account');
$tmp->assign('{referal}',$user->get_array('referal'),'account');

$all['info'] = $e;
$all['content'] = $tmp->display('account');
if($user->check_logged()) $all['title'] = 'Ћичный кабинет';
else $all['title'] = '¬ход в систему';
?>