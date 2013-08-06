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

if(isset($_POST['feedback_send'])) {
	switch($user->check_feedback($functions->strip($_POST['name']),$functions->strip($_POST['mail']),$functions->strip($_POST['subject']),$functions->strip($_POST['message']),$functions->strip($_POST['keystring']))) {
		case 1: $e = $tmp->info('error',MSG('ALL_FIELDS_REQUIRED')); break;
		case 2:
			$mail->setHeaders(
				'CP1251',
				'KOI8-R',
				$db->getParam('admin_mail'),
				'Оповещение',
				str_replace(
					array(
						'{name}',
						'{mail}',
						'{username}',
						'{subject}',
						'{message}',
						'{sitename}'
					),
					array(
						$functions->strip($_POST['name']),
						$functions->strip($_POST['mail']),
						($user->check_logged())?$user->get_array('username'):'---',
						$functions->strip($_POST['subject']),
						$functions->strip($_POST['message']),
						$db->getParam('title')
					),
					MAIL_MSG('FEEDBACK')
				),
				'AWE_BOT@'.$_SERVER['HTTP_HOST']
			);
			$mail->send();
			$e = $tmp->info('success',MSG('MESSAGE_WERE_SENDED'));
		break;
		case 3: $e = $tmp->info('error',MSG('CAPTCHA_IS_INVALID')); break;
	}
} else $e = '';

$tmp->theme('feedback.tpl','feedback');

$tmp->assign('{name}',(isset($_POST['name']))?$functions->strip($_POST['name']):$user->get_array('name'),'feedback');
$tmp->assign('{mail}',(isset($_POST['mail']))?$functions->strip($_POST['mail']):$user->get_array('mail'),'feedback');
$tmp->assign('{subject}',(isset($_POST['subject']))?$functions->strip($_POST['subject']):'','feedback');
$tmp->assign('{message}',(isset($_POST['message']))?$functions->strip($_POST['message']):'','feedback');
$tmp->assign('{captcha-link}','/engine/modules/captcha/captcha.php?'.session_name().'='.session_id(),'feedback');

$all['info'] = $e;
$all['content'] = $tmp->display('feedback');
$all['title'] = 'Обратная связь';
?>