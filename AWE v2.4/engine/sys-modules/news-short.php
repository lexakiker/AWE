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

$all['p-title'] = 'Главная';
$numb = 0;
$nop = $database->getParam('news-on-page');
$query = $database->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_news`');
$count_records = $database->fetch_row($query);
$count_records = $count_records[0];
$num_pages = ceil($count_records / $nop);
$curpage = isset($_GET['page'])?$_GET['page']:1;
if($curpage < 1) {
	$curpage = 1;
} elseif($curpage > $num_pages) {
	$curpage = $num_pages;
}
$start_from = ($curpage - 1) * $nop;
$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_news` ORDER BY `id` DESC LIMIT '.$functions->strip($start_from).', '.$nop);

if($count_records != 0) {
	ob_start();
		while($myrow = $database->fetch_array($query)) {
			$numb += 1;
			$comments_query = $database->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_comments` WHERE `news-id`=\''.$myrow['id'].'\'');
			$comments = $database->fetch_array($comments_query);
			$templates->load('short-story.tpl', 'short-news');
			$templates->assign('str', '{id}', $myrow['id'], 'short-news');
			$templates->assign('str', '{numb}', $numb, 'short-news');
			$templates->assign('str', '{title}', $myrow['title'], 'short-news');
			$templates->assign('str', '{date}', $myrow['date'], 'short-news');
			$templates->assign('str', '{short-story}', $myrow['short-story'], 'short-news');
			$templates->assign('str', '{full-story}', $myrow['full-story'], 'short-news');
			$templates->assign('str', '{author}', $myrow['author'], 'short-news');
			$templates->assign('str', '{views}', $myrow['views'], 'short-news');
			$templates->assign('str', '{comments}', $comments[0], 'short-news');
			$templates->assign('str', '{author-link}', '/'.ENGINE_PATH.'user/'.$myrow['author'].'/', 'short-news');
			$templates->assign('str', '{full-link}', '/'.ENGINE_PATH.'news/'.$myrow['id'].'/', 'short-news');
			$templates->assign('str', '{delete-link}', '/'.ENGINE_PATH.'admin/news/delete/'.$myrow['id'].'/', 'short-news');
			$templates->assign('str', '{nullify-link}', '/'.ENGINE_PATH.'admin/news/nullify/'.$myrow['id'].'/', 'short-news');
			$templates->assign('str', '{edit-link}', '/'.ENGINE_PATH.'admin/news/edit/'.$myrow['id'].'/', 'short-news');
			if($myrow['x-image'] != '') {
				$templates->assign('preg', '~(\[without-x-image\].+\[/without-x-image\])~is', '', 'short-news');
				$templates->assign('str', array('[with-x-image]', '[/with-x-image]'), '', 'short-news');
				$templates->assign('str', '{x-image}', $myrow['x-image'], 'short-news');
				$templates->assign('str', '{x-img-width}', $database->getParam('x-img-width'), 'short-news');
				$templates->assign('str', '{x-img-height}', $database->getParam('x-img-height'), 'short-news');
			} else {
				$templates->assign('str', array('[without-x-image]', '[/without-x-image]'), '', 'short-news');
				$templates->assign('preg', '~(\[with-x-image\].+\[/with-x-image\])~is', '', 'short-news');
			}
			echo $templates->display('short-news');
		}
		if($count_records > $nop) {
			$templates->load('navigation.tpl', 'news-navigation');
			$templates->assign('preg', '~\[comments\](.*?)\[/comments\]~is', '', 'news-navigation');
			$templates->assign('str', array('[news]', '[/news]'), '', 'news-navigation');
			ob_start();
				for($page = 1; $page <= $num_pages; $page++) {
					$templates->load('navigation-items.tpl','news-navigation-items');
					$templates->assign('preg', '~\[comments\](.*?)\[/comments\]~is', '', 'news-navigation-items');
					$templates->assign('str', array('[news]', '[/news]'), '', 'news-navigation-items');
					if($page == $curpage) {
						$templates->assign('str', array('[active]', '[/active]'), '', 'news-navigation-items');
						$templates->assign('preg', '~(\[item\].+\[/item\])~is', '', 'news-navigation-items');
					} else {
						$templates->assign('str', array('[item]', '[/item]'), '', 'news-navigation-items');
						$templates->assign('preg', '~(\[active\].+\[/active\])~is', '', 'news-navigation-items');
					}
					$templates->assign('str',array('{page-link}', '{page}'), array('/'.ENGINE_PATH.'page/'.$page.'/', $page), 'news-navigation-items');
					echo $templates->display('news-navigation-items');
				}
			$templates->assign('str', '{navigation-items}', ob_get_clean(), 'news-navigation');
			echo $templates->display('news-navigation');
		}
	$all['p-content'] = ob_get_clean();
} else {
	$templates->load('empty-news.tpl', 'empty-news');
	$all['p-content'] = $templates->display('empty-news');
}
?>