<?php
/* *
 * Модуль "Desirable Google" для Ameden Web Engine.
 * Предназначение: окно с советом "Скачать браузер Google Chrome" для более правильного отображения HTML элементов.
 * Функционал: окно не появляется, если у пользователя уже стоит данный браузер.
 * Автор: RevenHell (www.ameden.net | engine.ameden.net)
*/

if(!defined('INC_CHECK')) { die('Scat!'); }

if(!stristr($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
	$data = $this->assign('str', array('[if_google_browser]','[/if]'), '', $data);
} else {
	$data = $this->assign('preg', '~\[if_google_browser\](.*?)\[/if\]~is', '', $data);
}
?>