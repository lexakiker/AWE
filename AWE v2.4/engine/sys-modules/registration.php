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

$all['p-title'] = 'Регистрация пользователя';

if(isset($_POST['registration-send'])) {
	switch($user->checkRegister($functions->strip($_POST['name']), $functions->strip($_POST['username']), $functions->strip($_POST['password']), $functions->strip($_POST['re-password']), $functions->strip($_POST['mail']), $functions->strip($_POST['keystring']))) {
		case 1: $error = $templates->info('error', LANG('NAME_REQUIRED')); break;
		case 11: $error = $templates->info('error', LANG('NAME_BIG_LENGTH')); break;
		case 2: $error = $templates->info('error', LANG('LOGIN_IS_INVALID')); break;
		case 3: $error = $templates->info('error', LANG('EMAIL_IS_INVALID')); break;
		case 4: $error = $templates->info('error', LANG('PASSWORD_IS_INVALID')); break;
		case 5: $error = $templates->info('error', LANG('PASSWORDS_DO_NOT_MATCH')); break;
		case 6: $error = $templates->info('error', LANG('LOGIN_ALREADY_EXISTS')); break;
		case 7: $error = $templates->info('error', LANG('EMAIL_ALREADY_EXISTS')); break;
		case 8: $error = $templates->info('error', LANG('IP_ALREADY_EXISTS')); break;
		case 9:
			if($database->getParam('reg-mail-accept') == 'true') {
				$user->registration->SendMail();
				$checked = '0';
			} else { $checked = '1'; }
			$user->registration->Register($checked);
			if($database->getParam('write-user-passwords') == 'true') { $user->registration->WritePassword(); }
			if($checked == '1') {
				$error = $templates->info('success', LANG('REGISTRATION_FINISHED'));
			} elseif($checked == '0') { $error = $templates->info('success', LANG('REGISTRATION_MAIL_FINISHED'), array('{mail}' => $functions->strip($_POST['mail']))); }
		break;
		case 10: $error = $templates->info('error', LANG('CAPTCHA_IS_INVALID')); break;
	}
}

$templates->load('registration.tpl', 'registration');
$templates->assign('str', '{day-options}', $user->registration->getDayOptions(), 'registration');
$templates->assign('str', '{month-options}', $user->registration->getMonthOptions(), 'registration');
$templates->assign('str', '{year-options}', $user->registration->getYearOptions(), 'registration');
$templates->assign('str', '{name}', isset($_POST['name'])?$functions->strip($_POST['name']):'', 'registration');
$templates->assign('str', '{username}', isset($_POST['username'])?$functions->strip($_POST['username']):'', 'registration');
$templates->assign('str', '{mail}', isset($_POST['mail'])?$functions->strip($_POST['mail']):'', 'registration');
$templates->assign('str', '{captcha-link}', CAPTCHA_LINK, 'registration');
$all['p-info'] = $error;
$all['p-content'] = $templates->display('registration');
?>