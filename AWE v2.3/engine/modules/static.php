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

$url = (isset($_GET['url']))?$_GET['url']:'';
$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `url`=\''.$functions->strip($url).'\'');
$resource = $db->fetch_array($query);
if(empty($url) || $url != $resource['url']) {
	$all['content'] = $tmp->info('info',MSG('PAGE_NOT_FOUND'));
	$all['title'] = 'Информация';
} else {
	$tmp->theme('static.tpl','static');
	
	$tmp->assign('{title}',$resource['title'],'static');
	$tmp->assign('{content}',$resource['content'],'static');
	
	$all['content'] = $tmp->display('static');
	$all['title'] = $resource['title']; 
}
?>