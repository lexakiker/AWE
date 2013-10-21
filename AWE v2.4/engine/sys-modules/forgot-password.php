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

$all['p-title'] = 'Восстановление пароля';

if(isset($_POST['forgotPassword-send'])) {
	switch($user->checkForgot_password($functions->strip($_POST['username']), $functions->strip($_POST['mail']), $functions->strip($_POST['keystring']))) {
		case 1: $error = $templates->info('error', LANG('ALL_FIELDS_REQUIRED')); break;
		case 2: $error = $templates->info('error', LANG('ACCOUNT_NOT_FOUND')); break;
		case 3:
			$database->query('UPDATE `'.DB_PREFIX.'_users` SET `password`=\''.$functions->crypt($user->rand_pass).'\' WHERE `username`=\''.$functions->strip($_POST['username']).'\'');
			$mail->setTo($functions->strip($_POST['mail']));
			$mail->setSubject('Восстановление пароля');
			$mail->setMessage(str_replace(
				array(
					'{password}',
					'{username}',
					'{site-name}'
				),
				array(
					$user->rand_pass,
					$functions->strip($_POST['username']),
					$database->getParam('title')
				),
				MAIL_MSG('FORGOT-PASSWORD')
			));
			$mail->send();
			$error = $templates->info('success', LANG('LOSTPASSWORD_WERE_CHANGED'), array('{mail}' => $functions->strip($_POST['mail'])));
		break;
		case 4: $error = $templates->info('error', LANG('CAPTCHA_IS_INVALID')); break;
	}
}

$templates->load('forgot-password.tpl', 'forgot-password');
$templates->assign('str', '{username}', isset($_POST['username'])?$functions->strip($_POST['username']):'', 'forgot-password');
$templates->assign('str', '{mail}', isset($_POST['mail'])?$functions->strip($_POST['mail']):'', 'forgot-password');
$templates->assign('str', '{captcha-link}', CAPTCHA_LINK, 'forgot-password');
$all['p-content'] = $templates->display('forgot-password');
$all['p-info'] = $error;
?>