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

$tmp->theme('statistics.tpl','statistics');

$query = $db->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_news`');
$tmp->assign('{allnews}',$db->result($query),'statistics');

$query = $db->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_comments`');
$tmp->assign('{allcomments}',$db->result($query),'statistics');

$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_news`');
$resource = $db->fetch_array($query);
if($resource == '') $tmp->assign('{firstnews}','---','statistics');
else $tmp->assign('{firstnews}','<a target="_blank" href="/news/'.$resource['id'].'">'.$resource['title'].'</a>','statistics');

$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_news` ORDER BY `id` DESC');
$resource = $db->fetch_array($query);
if($resource == '') $tmp->assign('{lastnews}','---','statistics');
else $tmp->assign('{lastnews}','<a target="_blank" href="/news/'.$resource['id'].'">'.$resource['title'].'</a>','statistics');

$query = $db->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_users`');
$tmp->assign('{allusers}',$db->result($query),'statistics');

$query = $db->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_bans`');
$tmp->assign('{allbans}',$db->result($query),'statistics');

$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_users`');
$resource = $db->fetch_array($query);
if($resource == '') $tmp->assign('{firstuser}','---','statistics');
else $tmp->assign('{firstuser}','<a target="_blank" href="/user/'.$resource['username'].'">'.$resource['username'].'</a>','statistics');

$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` ORDER BY `id` DESC');
$resource = $db->fetch_array($query);
if($resource == '') $tmp->assign('{lastuser}','---','statistics');
else $tmp->assign('{lastuser}','<a target="_blank" href="/user/'.$resource['username'].'">'.$resource['username'].'</a>','statistics');

$all['content'] = $tmp->display('statistics');
$all['title'] = 'Статистика сайта';
?>