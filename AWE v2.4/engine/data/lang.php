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

function LANG($string) {
	$language = array(
		
		'TOMORROW_BIRTHDAY'          => '<font color="#E17C00">Завтра :)</font>',
		'NOW_BIRTHDAY'               => '<font color="#61B329">Сегодня!</font>',
		
		'USER_NOT_FOUND'             => 'Извините, такого пользователя не существует.',
		'USER_BANNED'                => 'Извините, данный пользователь забанен.',
		'PAGE_NOT_FOUND'             => 'Извините, такой страницы не существует.',
		'NEWS_NOT_FOUND'             => 'Извините, такой новости не существует.',
		'NOPE_RIGHT'                 => 'Извините, у вас нет прав для просмотра данной страницы.',
		'NO_COMMENTS'                => 'Ни одного комментария пока еще не добавлено.',
		'NO_PAGES'                   => 'Ни одной страницы пока еще добавлено.',
		'NO_NEWS'                    => 'Ни одной новости пока еще не добавлено.',
		'INVALID_PAGE_ID'            => 'Статической страницы, имеющей ID <b>{id}</b> не существует.',
		'PAGE_ALREADY_EXISTS'        => 'Страница с URL <b>{url}</b> уже используется.',
		'INVALID_COMMENT_ID'         => 'Комментария, имеющего ID <b>{id}</b> не существует.',
		'INVALID_NEWS_ID'            => 'Новости, имеющей ID <b>{id}</b> не существует.',
		'EMPTY_COMMENT_ID'           => 'Не указан ID комментария.',
		'ACCESS_DENIED'              => 'Вы не имеете доступа к ПУ.',
		'EMPTY_PAGE_ID'              => 'Не указан ID страницы.',
		'EMPTY_NEWS_ID'              => 'Не указан ID новости.',
		
		'TRUE_DELETE_COMMENT'        => 'Вы действительно хотите удалить комментарий <b>№{id}</b>? - <a href="{accept-link}">Да</a> / <a href="javascript:history.go(-1)">Нет</a>',
		'TRUE_DELETE_PAGE'           => 'Вы действительно хотите удалить страницу <b>№{id}</b>? - <a href="{accept-link}">Да</a> / <a href="javascript:history.go(-1)">Нет</a>',
		'TRUE_DELETE_NEWS'           => 'Вы действительно хотите удалить новость <b>№{id}</b>? - <a href="{accept-link}">Да</a> / <a href="javascript:history.go(-1)">Нет</a>',
		'PASSWORD_IS_INVALID'        => 'Пароль должен содержать <u>только</u> латинские<br>буквы и цифры, и состоять от 6 до 20 символов.',
		'LOGIN_IS_INVALID'           => 'Логин должен содержать <u>только</u> латинские<br>буквы и цифры, и состоять от 3 до 15 символов.',
		'LOGIN_REQUIRED'             => 'Вы не авторизированы. <br>Для добавлениев комментариев необходимо авторизироваться.',
		'EMAIL_IS_INVALID'           => 'Некорректный E-Mail. Возможно, он содержит больше 24 символов.',
		'URL_IS_INVALID'             => 'URL страницы должен содержать <u>только</u> латинские буквы, цифры, символы - или _.',
		'ACCOUNT_ALREADY_ACTIVATED'  => '<font color="#E70024">Данный аккаунт уже активирован.</font>',
		'COMMENT_BIG_LENGTH'         => 'Комментарий должен содержать до <b>{max-sym}</b> символов.',
		'ACCOUNT_ACTIVATION_ERROR'   => '<font color="#E70024">Ошибка активации аккаунта.</font>',
		'IP_ALREADY_EXISTS'          => 'Пользователь с таким IP-адресом уже существует.',
		'LOGIN_ALREADY_EXISTS'       => 'Пользователь с таким логином уже существует.',
		'EMAIL_ALREADY_EXISTS'       => 'Пользователь с таким E-Mail уже существует.',
		'ACCOUNT_NOT_FOUND'          => 'Несуществующий / непотдвержденный аккаунт.',
		'NAME_BIG_LENGTH'            => 'Имя должно содержать до 15 символов.',
		'NEWPASSWORD_IS_INVALID'     => 'Новый пароль введен некорректно.',
		'COMMENT_ALREADY_ADDED'      => 'Такой комментарий вы уже писали.',
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
		'PASSWORDS_DO_NOT_MATCH'     => 'Пароли не совпадают.',
		'NAME_REQUIRED'              => 'Вы не ввели имя.',
		
		'NOTHING_SELECTED'           => 'Вы ничего не выбрали.',
		'OPTIMIZATION_COMPLETED'     => 'Оптимизация успешно проведена.',
		'DELETE_NON_ACTIVATED'       => ' Удалено <b>{count}</b> неактивированных пользователей.',
		'NON_ACTIVATED_EMPTY'        => ' Неактивированных пользователей нет.',
		
		'REGISTRATION_MAIL_FINISHED' => 'Регистрация успешно завершена, однако необходимо подтверждение аккаунта.<br>Для этого мы выслали вам на почту (<b>{mail}</b>) письмо, перейдите по ссылке, которая там находится.<br><br><i>Если письмо не пришло - проверьте папку "<i>Спам</i>".</i>',
		'NEWS_WERE_NULLED'           => 'Просмотры новости <b>№{id}</b> успешно обнулены! - <a href="javascript:history.go(-1)">Назад</a>',
		'ACCOUNT_WERE_ACTIVATED'     => '<font color="#61B329">Поздравляем! Вы успешно активировали свой аккаунт!</font> - <a href="/">Главная</a>',
		'PAGE_WERE_ADDED'            => 'Статическая страница успешно добавлена! <a target="_blank" href="{page-url}">Просмотреть</a>!',
		'COMMENT_WERE_DELETED'       => 'Комментарий <b>№{id}</b> успешно удален! - <a href="javascript:history.go(-2)">Назад</a>',
		'PAGE_WERE_DELETED'          => 'Страница <b>№{id}</b> успешно удалена! - <a href="javascript:history.go(-2)">Назад</a>',
		'NEWS_WERE_DELETED'          => 'Новость <b>№{id}</b> успешно удалена! - <a href="javascript:history.go(-2)">Назад</a>',
		'MESSAGE_WERE_SENDED'        => 'Ваше сообщение успешно отправлено и будет рассмотрено в ближайшие сроки!',
		'LOSTPASSWORD_WERE_CHANGED'  => 'Новый пароль успешно отправлен вам на E-Mail (<b>{mail}</b>)!',
		'PAGE_WERE_CHANGED'          => 'Страница <b>№{id}</b> успешно изменена!',
		'NEWS_WERE_CHANGED'          => 'Новость <b>№{id}</b> успешно изменена!',
		'DATA_WERE_CHANGED'          => 'Вы успешно изменили свои данные!',
		'REGISTRATION_FINISHED'      => 'Регистрация успешно завершена!',
		'CONFIGURATION_WERE_CHANGED' => 'Конфигурация успешно изменена!',
		'PASSWORD_WERE_CHANGED'      => 'Ваш пароль успешно изменен!',
		'NEWS_WERE_ADDED'            => 'Новость успешно добавлена!',
		
		'GROUP_NOT_ACTIVATED'        => '<font color="#E70024">Неактивированный</font>',
		'GROUP_0'                    => '<font color="#E70024">Забаненный</font>',
		'GROUP_1'                    => '<font color="#333333">Пользователь</font>',
		'GROUP_2'                    => '<font color="#E17C00">VIP 3</font>',
		'GROUP_3'                    => '<font color="#E17C00">VIP 2</font>',
		'GROUP_4'                    => '<font color="#8B008B">VIP 1</font>',
		'GROUP_5'                    => '<font color="#61B329">Модератор</font>',
		'GROUP_6'                    => '<font color="#940016">Администратор</font>'
		
	);
	return $language[$string];
}
?>