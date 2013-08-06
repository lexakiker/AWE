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

function MSG($input) {
	$messages = array(
		
		'INVALID_PAGE_ID'            => 'Статической страницы под номером <b>{id}</b> не существует.',
		'PAGE_ALREADY_EXISTS'        => 'Страница с таким URL (<b>{url}</b>) уже используется.',
		'INVALID_COMMENT_ID'         => 'Комментария под номером <b>{id}</b> не существует.',
		'INVALID_NEWS_ID'            => 'Новости под номером <b>{id}</b> не существует.',
		'EMPTY_COMMENT_ID'           => 'Не указан номер комментария.',
		'ACCESS_DENIED'              => 'Вы не имеете доступа к ПУ.',
		'EMPTY_PAGE_ID'              => 'Не указан номер страницы.',
		'EMPTY_NEWS_ID'              => 'Не указан номер новости.',
		
		'TRUE_DELETE_COMMENT'        => 'Вы действительно хотите удалить комментарий <b>#{id}</b>? - <a href="{accept-link}">Да</a> / <a href="javascript:history.go(-1)">Нет</a>',
		'TRUE_DELETE_PAGE'           => 'Вы действительно хотите удалить страницу <b>#{id}</b>? - <a href="{accept-link}">Да</a> / <a href="javascript:history.go(-1)">Нет</a>',
		'TRUE_DELETE_NEWS'           => 'Вы действительно хотите удалить новость <b>#{id}</b>? - <a href="{accept-link}">Да</a> / <a href="javascript:history.go(-1)">Нет</a>',
		'PASSWORD_IS_INVALID'        => 'Пароль должен содержать только латинские буквы и цифры, и состоять минимум из 6 символов до 20.',
		'LOGIN_REQUIRED'             => 'Вы не авторизированы. Для добавлениев комментариев необходимо авторизироваться.',
		'INVALID_PAGE_ID'            => 'Статической страницы под номером <b>{id}</b> не существует.',
		'ACCOUNT_ALREADY_ACTIVATED'  => '<font color="red">Данный аккаунт уже активирован.</font>',
		'PAGE_ALREADY_EXISTS'        => 'Страница с таким URL (<b>{url}</b>) уже используется.',
		'ACCOUNT_ACTIVATION_ERROR'   => '<font color="red">Ошибка активации аккаунта.</font>',
		'INVALID_COMMENT_ID'         => 'Комментария под номером <b>{id}</b> не существует.',
		'IP_ALREADY_EXISTS'          => 'Пользователь с таким IP-адресом уже существует.',
		'INVALID_NEWS_ID'            => 'Новости под номером <b>{id}</b> не существует.',
		'LOGIN_ALREADY_EXISTS'       => 'Пользователь с таким логином уже существует.',
		'EMAIL_ALREADY_EXISTS'       => 'Пользователь с таким E-Mail уже существует.',
		'ACCOUNT_NOT_FOUND'          => 'Несуществующий / непотдвержденный аккаунт.',
		'NEWPASSWORD_IS_INVALID'     => 'Новый пароль введен некорректно.',
		'ACCOUNT_IS_NOT_CONFIRM'     => 'Данный аккаунт не подтвержден.',
		'CAPTCHA_IS_INVALID'         => 'Неверно введен код с картинки.',
		'OLDPASSWORD_IS_INVALID'     => 'Старый пароль введен неверно.',
		'EMPTY_COMMENT_ID'           => 'Не указан номер комментария.',
		'COMMENT_REQUIRED'           => 'Заполните поле комментария.',
		'AUTHORIZATION_ERROR'        => 'Неверный логин или пароль.',
		'ACCESS_DENIED'              => 'Вы не имеете доступа к ПУ.',
		'EMPTY_PAGE_ID'              => 'Не указан номер страницы.',
		'EMPTY_NEWS_ID'              => 'Не указан номер новости.',
		'ACCOUNT_IS_BANNED'          => 'Данный аккаунт забанен.',
		'ALL_FIELDS_REQUIRED'        => 'Не все поля заполнены.',
		'EMAIL_IS_INVALID'           => 'Некорректный E-Mail.',
		'PASSWORDS_DO_NOT_MATCH'     => 'Пароли не совпадают.',
		'LOGIN_IS_INVALID'           => 'Некорректный логин.',
		'NAME_REQUIRED'              => 'Вы не ввели имя.',
		
		'USER_NOT_FOUND'             => 'Извините, такого пользователя не существует.',
		'PAGE_NOT_FOUND'             => 'Извините, такой страницы не существует.',
		'NEWS_NOT_FOUND'             => 'Извините, такой новости не существует.',
		'NOPE_RIGHT'                 => 'Извините, у вас нет прав для просмотра данной страницы.',
		'NO_COMMENTS'                => 'Ни одного комментария пока еще не добавлено.',
		'NO_PAGES'                   => 'Ни одной страницы пока еще нет.',
		'NO_NEWS'                    => 'Ни одной новости пока еще не добавлено.',
		
		'REGISTRATION_MAIL_FINISHED' => 'Поздравляем! Регистрация успешно завершена, однако необходимо подтверждение аккаунта. Для этого мы отослали вам на почту (<b>{mail}</b>) письмо, перейдите по ссылке, которая там находится.<br><i>Если письмо не пришло - проверьте папку "Спам".</i>',
		'NEWS_WERE_NULLED'           => 'Поздравляем! Просмотры у новости <b>#{id}</b> успешно обнулены - <a href="javascript:history.go(-1)">Назад</a>',
		'ACCOUNT_WERE_ACTIVATED'     => '<font color="green">Поздравляем! Вы успешно активировали свой аккаунт</font> - <a href="/">Главная</a>',
		'COMMENT_WERE_DELETED'       => 'Поздравляем! Комментарий <b>#{id}</b> успешно удален - <a href="javascript:history.go(-2)">Назад</a>',
		'PAGE_WERE_DELETED'          => 'Поздравляем! Страница <b>#{id}</b> успешно удалена - <a href="javascript:history.go(-2)">Назад</a>',
		'NEWS_WERE_DELETED'          => 'Поздравляем! Новость <b>#{id}</b> успешно удалена - <a href="javascript:history.go(-2)">Назад</a>',
		'COMMENT_WERE_ADDED'         => 'Поздравляем! Ваш комментарий успешно добавлен.<meta http-equiv="refresh" content="3">',
		'MESSAGE_WERE_SENDED'        => 'Поздравляем! Сообщение успешно отправлено и будет рассмотрено в ближайшие сроки.',
		'LOSTPASSWORD_WERE_CHANGED'  => 'Поздравляем! Новый пароль успешно отправлен вам на E-Mail (<b>{mail}</b>).',
		'NEWS_WERE_CHANGED'          => 'Поздравляем! Новость <b>#{id}</b> успешно изменена.',
		'PAGE_WERE_CHANGED'          => 'Поздравляем! Страница <b>#{id}</b> успешно изменена.',
		'PAGE_WERE_ADDED'            => 'Поздравляем! Статическая страница успешно добавлена.',
		'DATA_WERE_CHANGED'          => 'Поздравляем! Вы успешно изменили свои данные.',
		'REGISTRATION_FINISHED'      => 'Поздравляем! Регистрация успешно завершена.',
		'CONFIGURE_WERE_CHANGED'     => 'Поздравляем! Конфигурация успешно изменена.',
		'NEWS_WERE_ADDED'            => 'Поздравляем! Новость успешно добавлена.',
		'PASSWORD_WERE_CHANGED'      => 'Поздравляем! Пароль успешно изменен.',
		
		
		'GROUP_0'   => '<font color="red">Забаненный</font>',
		'GROUP_1'   => 'Пользователь',
		'GROUP_2'   => '<font color="orange">Класс 3</font>',
		'GROUP_3'   => '<font color="orange">Класс 2</font>',
		'GROUP_4'   => '<font color="purple">Класс 1</font>',
		'GROUP_5'   => '<font color="green">Модератор</font>',
		'GROUP_6'   => '<font color="brown">Администратор</font>'
		
	);
	return $messages[$input];
}
?>