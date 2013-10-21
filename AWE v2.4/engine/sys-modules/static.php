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

$url = isset($_GET['url'])?$_GET['url']:null;
$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `url`=\''.$functions->strip($url).'\'');
$resource = $database->fetch_array($query);

if(empty($url) || $url != $resource['url']) {
	$all['p-content'] = $templates->info('info',LANG('PAGE_NOT_FOUND'));
	$all['p-title'] = 'Информация';
} else {
	$templates->load('static.tpl', 'static');
	$templates->assign('str', '{content}', $resource['content'], 'static');
	$templates->assign('str', '{title}', $resource['title'], 'static');
	$all['p-content'] = $templates->display('static');
	$all['p-title'] = $resource['title']; 
}
?>