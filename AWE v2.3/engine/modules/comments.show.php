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

$query = $db->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_comments`');
$count_records = $db->fetch_row($query);
$count_records = $count_records[0];
$num_pages = ceil($count_records / $db->getParam('comments_onpage'));
$curpage = (isset($_GET['page']))?$_GET['page']:1;
if($curpage < 1) $curpage = 1;
elseif($curpage > $num_pages) $curpage = $num_pages;
$start_from = ($curpage - 1) * $db->getParam('comments_onpage');
$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_comments` WHERE `newsid`=\''.$functions->strip($id).'\' ORDER BY `id` DESC LIMIT '.$functions->strip($start_from).', '.$db->getParam('comments_onpage'));
if(@$db->num_rows($query) != 0) {
	while($myrow = $db->fetch_array($query)) {
		$tmp->theme('comments.show.tpl','comments');
		$tmp->assign('{id}',$myrow['id'],'comments');
		$tmp->assign('{author}',$myrow['author'],'comments');
		$tmp->assign('{author-link}','/user/'.$myrow['author'],'comments');
		$tmp->assign('{comment}',$myrow['comment'],'comments');
		$tmp->assign('{date}',$myrow['date'],'comments');
		$tmp->assign('{delete-link}','/admin/comments/delete/'.$myrow['id'],'comments');
		echo $tmp->display('comments');
	}
} else echo $tmp->info('info',MSG('NO_COMMENTS'));
?>