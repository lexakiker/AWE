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

function register($checked) {
	global $functions, $db;
	$referal = (isset($_COOKIE['referal']))?$_COOKIE['referal']:'';
	$db->query('INSERT INTO `'.DB_PREFIX.'_users` (`name`, `username`, `password`, `mail`, `regdate`, `regip`, `birth`, `referal`, `checked`) VALUES (\''.$functions->strip($_POST['name']).'\', \''.$functions->strip($_POST['username']).'\', \''.$functions->strip($functions->crypt($_POST['password'])).'\', \''.$functions->strip($_POST['mail']).'\', \''.date('d.m.Y, в H:i').'\', \''.USER_IP.'\', \''.$functions->strip($_POST['day']).'.'.$functions->strip($_POST['month']).'.'.$functions->strip($_POST['year']).'\', \''.$functions->strip($referal).'\', \''.$checked.'\')');
}

function write_password() {
	global $functions, $db;
	$db->query('INSERT INTO `'.DB_PREFIX.'_passwords` (`username`, `password`) VALUES (\''.$functions->strip($_POST['username']).'\', \''.$functions->strip($_POST['password']).'\')');
}

function send_mail() {
	global $functions, $mail, $db;
	$mail->setHeaders(
		'CP1251',
		'KOI8-R',
		$functions->strip($_POST['mail']),
		'Подтверждение аккаунта',
		str_replace(
			array(
				'{url}',
				'{username}',
				'{sitename}'
			),
			array(
				'http://'.$_SERVER['HTTP_HOST'].'/activation/'.$functions->crypt('--'.$functions->crypt($functions->strip($_POST['password'])).'--').'/'.$functions->strip($_POST['username']),
				$functions->strip($_POST['username']),
				$db->getParam('title')
			),
			MAIL_MSG('REGISTRATION')
		),
		$db->getParam('admin_mail')
	);
	$mail->send();
}

if(isset($_POST['registration_send'])) {
	ob_start();
		switch($user->check_register($functions->strip($_POST['name']),$functions->strip($_POST['username']),$functions->strip($_POST['password']),$functions->strip($_POST['repassword']),$functions->strip($_POST['mail']),$functions->strip($_POST['keystring']))) {
			case 1: $e = $tmp->info('error',MSG('NAME_REQUIRED')); break;
			case 2: $e = $tmp->info('error',MSG('LOGIN_IS_INVALID')); break;
			case 3: $e = $tmp->info('error',MSG('EMAIL_IS_INVALID')); break;
			case 4: $e = $tmp->info('error',MSG('PASSWORD_IS_INVALID')); break;
			case 5: $e = $tmp->info('error',MSG('PASSWORDS_DO_NOT_MATCH')); break;
			case 6: $e = $tmp->info('error',MSG('LOGIN_ALREADY_EXISTS')); break;
			case 7: $e = $tmp->info('error',MSG('EMAIL_ALREADY_EXISTS')); break;
			case 8: $e = $tmp->info('error',MSG('IP_ALREADY_EXISTS')); break;
			case 910: register('1'); $e = $tmp->info('success',MSG('REGISTRATION_FINISHED')); break;
			case 911: write_password(); register('1'); $e = $tmp->info('success',MSG('REGISTRATION_FINISHED')); break;
			case 920: register('0'); send_mail(); $e = $tmp->info('success',MSG('REGISTRATION_MAIL_FINISHED'),array('{mail}' => $functions->strip($_POST['mail']))); break;
			case 921: write_password(); register('0'); send_mail(); $e = $tmp->info('success',MSG('REGISTRATION_MAIL_FINISHED'),array('{mail}' => $functions->strip($_POST['mail']))); break;
			case 10: $e = $tmp->info('error',MSG('CAPTCHA_IS_INVALID')); break;
		}
	$all['info'] = ob_get_clean();
} else $e = '';

$tmp->theme('registration.tpl','registration');
ob_start(); $day = 32; while($day-- > 1) { if($day < 10) $null = '0'; else $null = ''; echo '<option value="'.$null.$day.'">'.$day.'</option>'; }
$tmp->assign('{day-options}',ob_get_clean(),'registration');
ob_start(); $month = array('Январь' => '01','Февраль' => '02','Март' => '03','Апрель' => '04','Май' => '05','Июнь' => '06','Июль' => '07','Август' => '08','Сентябрь' => '09','Октябрь' => '10','Ноябрь' => '11','Декабрь' => '12'); foreach($month as $key => $value) echo '<option value="'.$value.'">'.$key.'</option>';
$tmp->assign('{month-options}',ob_get_clean(),'registration');
ob_start(); $year = 2005; while($year-- > 1940) echo '<option value="'.$year.'">'.$year.'</option>';
$tmp->assign('{year-options}',ob_get_clean(),'registration');
$tmp->assign('{name}',(isset($_POST['name']))?$functions->strip($_POST['name']):'','registration');
$tmp->assign('{username}',(isset($_POST['username']))?$functions->strip($_POST['username']):'','registration');
$tmp->assign('{mail}',(isset($_POST['mail']))?$functions->strip($_POST['mail']):'','registration');
$tmp->assign('{captcha-link}','/engine/modules/captcha/captcha.php?'.session_name().'='.session_id(),'registration');

$all['info'] = $e;
$all['content'] = $tmp->display('registration');
$all['title'] = 'Регистрация пользователя';
?>