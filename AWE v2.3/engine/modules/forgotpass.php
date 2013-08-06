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

if(isset($_POST['forgotpass_send'])) {
	switch($user->check_forgotpass($functions->strip($_POST['username']),$functions->strip($_POST['mail']),$functions->strip($_POST['keystring']))) {
		case 1: $e = $tmp->info('error',MSG('ALL_FIELDS_REQUIRED')); break;
		case 2: $e = $tmp->info('error',MSG('ACCOUNT_NOT_FOUND')); break;
		case 3:
			$db->query('UPDATE `'.DB_PREFIX.'_users` SET `password`=\''.$functions->crypt($user->random_password).'\' WHERE `username`=\''.$functions->strip($_POST['username']).'\'');
			$mail->setHeaders(
				'CP1251',
				'KOI8-R',
				$functions->strip($_POST['mail']),
				'Восстановление пароля',
				str_replace(
					array(
						'{password}',
						'{username}',
						'{sitename}'
					),
					array(
						$user->random_password,
						$functions->strip($_POST['username']),
						$db->getParam('title')
					),
					MAIL_MSG('FORGOT_PASSWORD')
				),
				$db->getParam('admin_mail')
			);
			$mail->send();
			$e = $tmp->info('success',MSG('LOSTPASSWORD_WERE_CHANGED'),array('{mail}' => $functions->strip($_POST['mail'])));
		break;
		case 4: $e = $tmp->info('error',MSG('CAPTCHA_IS_INVALID')); break;
	}
} else $e = '';

$tmp->theme('forgotpass.tpl','forgot');

$tmp->assign('{username}',(isset($_POST['username']))?$functions->strip($_POST['username']):'','forgot');
$tmp->assign('{mail}',(isset($_POST['mail']))?$functions->strip($_POST['mail']):'','forgot');
$tmp->assign('{captcha-link}','/engine/modules/captcha/captcha.php?'.session_name().'='.session_id(),'forgot');

$all['info'] = $e;
$all['content'] = $tmp->display('forgot');
$all['title'] = 'Восстановление пароля';
?>