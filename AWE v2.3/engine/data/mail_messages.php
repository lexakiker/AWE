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

function MAIL_MSG($input) {
	$messages = array(
		
		'REGISTRATION'       => '<h1>Подтверждение регистрации</h1><br>'.
								'Здравствуйте, {username}.<br>'.
								'Для подтверждения регистрации перейдите по следующей ссылке:<br>'.
								'{url}<br><br>'.
								'С уважением, администрация {sitename}.',
		
		'FORGOT_PASSWORD'    => '<h1>Восстановление пароля</h1><br>'.
								'Здравствуйте, {username}.<br>'.
								'Ваш новый пароль (желательно сменить его после входа):<br>'.
								'{password}<br><br>'.
								'С уважением, администрация {sitename}.',
		
		'FEEDBACK'           => 'Здравствуйте, уважаемый администратор сайта {sitename}.<br>'.
								'Вам было отправлено сообщение:<br><br>'.
								'Имя отправителя: {name}<br>'.
								'E-Mail отправителя: {mail}<br>'.
								'Логин отправителя: {username}<br>'.
								'Тема сообщения: {subject}<br>'.
								'Сообщение:<br>'.
								'{message}',
		
		'NEW_COMMENT'        => 'Здравствуйте, уважаемый администратор сайта {sitename}.<br>'.
								'На сайте только что был добавлен новый комментарий.<br><br>'.
								'Ссылка на новость: {newslink}<br>'.
								'Имя комментатора: {name}<br>'.
								'E-Mail комментатора: {mail}<br>'.
								'Логин комментатора: {username}<br>'.
								'Комментарий:<br>'.
								'{comment}'
		
	);
	return $messages[$input];
}
?>