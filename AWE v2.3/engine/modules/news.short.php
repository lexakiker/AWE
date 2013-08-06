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

$query = $db->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_news`');
$count_records = $db->fetch_row($query);
$count_records = $count_records[0];
$num_pages = ceil($count_records / $db->getParam('news_onpage'));
$curpage = (isset($_GET['page']))?$_GET['page']:1;
if($curpage < 1) $curpage = 1;
elseif($curpage > $num_pages) $curpage = $num_pages;
$start_from = ($curpage - 1) * $db->getParam('news_onpage');
$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_news` ORDER BY `id` DESC LIMIT '.$functions->strip($start_from).', '.$db->getParam('news_onpage'));
if(@$db->num_rows($query) != 0) {
	ob_start();
		while($myrow = $db->fetch_array($query)) {
			$comments = $db->fetch_array($db->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_comments` WHERE `newsid`=\''.$myrow['id'].'\''));
			$tmp->theme('shortstory.tpl','news');
			$tmp->assign('{id}',$myrow['id'],'news');
			$tmp->assign('{title}',$myrow['title'],'news');
			$tmp->assign('{date}',$myrow['date'],'news');
			$tmp->assign('{shortstory}',$myrow['shortstory'],'news');
			$tmp->assign('{fullstory}',$myrow['fullstory'],'news');
			$tmp->assign('{author}',$myrow['author'],'news');
			$tmp->assign('{author-link}','/user/'.$myrow['author'],'news');
			$tmp->assign('{views}',$myrow['views'],'news');
			$tmp->assign('{comments}',$comments[0],'news');
			$tmp->assign('{full-link}','/news/'.$myrow['id'],'news');
			$tmp->assign('{delete-link}','/admin/news/delete/'.$myrow['id'],'news');
			$tmp->assign('{nullify-link}','/admin/news/nullify/'.$myrow['id'],'news');
			$tmp->assign('{edit-link}','/admin/news/edit/'.$myrow['id'],'news');
			if($myrow['ximage'] != '') {
				$tmp->assign(array('[ximage]','[/ximage]'),'','news');
				$tmp->assign('{ximage}',$myrow['ximage'],'news');
				$tmp->assign('{ximg_width}',$db->getParam('ximg_width'),'news');
				$tmp->assign('{ximg_height}',$db->getParam('ximg_height'),'news');
			} else $tmp->preg_assign('~(\[ximage\].+\[/ximage\])~is','','news');
			echo $tmp->display('news');
		}
		$navigation = file_get_contents(ROOT.'themes/'.$db->getParam('theme').'/navigation.tpl');
		$navigation = preg_replace('~\[comments\](.*?)\[/comments\]~is','',$navigation);
		$navigation = str_replace(array('[news]','[/news]'),'',$navigation);
		ob_start();
			for($page = 1; $page <= $num_pages; $page++) {
				$navigation_items = file_get_contents(ROOT.'themes/'.$db->getParam('theme').'/navigation-items.tpl');
				$navigation_items = preg_replace('~\[comments\](.*?)\[/comments\]~is','',$navigation_items);
				$navigation_items = str_replace(array('[news]','[/news]'),'',$navigation_items);
				if($page == $curpage) {
					$navigation_items = str_replace(array('[active]','[/active]'),'',$navigation_items);
					$navigation_items = preg_replace('~(\[item\].+\[/item\])~is','',$navigation_items);
				} else {
					$navigation_items = str_replace(array('[item]','[/item]'),'',$navigation_items);
					$navigation_items = preg_replace('~(\[active\].+\[/active\])~is','',$navigation_items);
				}
				echo str_replace(array('{page-link}','{page}'),array('/page/'.$page,$page),$navigation_items);
			}
		echo str_replace('{navigation_items}',ob_get_clean(),$navigation);
	$all['content'] = ob_get_clean();
} else $all['content'] = $tmp->info('info',MSG('NO_NEWS'));
$all['title'] = 'Главная';
?>