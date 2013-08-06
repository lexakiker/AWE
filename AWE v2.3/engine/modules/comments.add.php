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

if($user->check_logged()) {
	$tmp->theme('comments.add.tpl','addcomments');
	if(isset($_POST['comment_send'])) {
		switch($user->check_comment($functions->strip($_POST['comment']))) {
			case 1: $e = $tmp->info('error',MSG('COMMENT_REQUIRED')); break;
			case 2:
				if($db->getParam('send_mail_oncomment') == 'true') {
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
								'{comment}',
								'{sitename}',
								'{newslink}'
							),
							array(
								$functions->strip($user->get_array('name')),
								$functions->strip($user->get_array('mail')),
								$functions->strip($user->get_array('username')),
								$functions->strip($_POST['comment'],true),
								$db->getParam('title'),
								'http://'.$_SERVER['HTTP_HOST'].'/news/'.$functions->strip($id)
							),
							MAIL_MSG('NEW_COMMENT')
						),
						'AWE_BOT@'.$_SERVER['HTTP_HOST']
					);
					$mail->send();
				}
				$db->query('INSERT INTO `'.DB_PREFIX.'_comments` (`author`, `comment`, `newsid`, `date`) VALUES (\''.$user->get_array('username').'\', \''.$functions->strip($_POST['comment'],true).'\', \''.$functions->strip($id).'\', \''.date('d.m.Y, в H:i').'\')');
				$e = $tmp->info('success',MSG('COMMENT_WERE_ADDED'));
			break;
		}
	} else $e = '';
	echo $e.$tmp->display('addcomments');
} else echo $tmp->info('info',MSG('LOGIN_REQUIRED'));
?>