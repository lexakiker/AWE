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

$id = isset($_GET['id'])?$_GET['id']:null;
$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_news` WHERE `id`=\''.$functions->strip($id).'\'');
$resource = $database->fetch_array($query);
$query = $database->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_comments` WHERE `news-id`=\''.$resource['id'].'\'');
$comments = $database->fetch_array($query);

if(empty($id) || $id != $resource['id']) {
	$all['p-content'] = $templates->info('error', LANG('NEWS_NOT_FOUND'));
	$all['p-title'] = 'Ошибка';
} else {
	$database->query('UPDATE `'.DB_PREFIX.'_news` SET `views`=\''.($resource['views']+1).'\' WHERE `id`=\''.$resource['id'].'\'');
	$templates->load('full-story.tpl', 'full-news');
	$templates->assign('str', '{id}', $resource['id'], 'full-news');
	$templates->assign('str', '{title}', $resource['title'], 'full-news');
	$templates->assign('str', '{date}', $resource['date'], 'full-news');
	$templates->assign('str', '{short-story}', $resource['short-story'], 'full-news');
	$templates->assign('str', '{full-story}', $resource['full-story'], 'full-news');
	$templates->assign('str', '{author}', $resource['author'], 'full-news');
	$templates->assign('str', '{views}', $resource['views'], 'full-news');
	$templates->assign('str', '{comments}', $comments[0], 'full-news');
	$templates->assign('str', '{author-link}', '/'.ENGINE_PATH.'user/'.$resource['author'].'/', 'full-news');
	$templates->assign('str', '{delete-link}', '/'.ENGINE_PATH.'admin/news/delete/'.$resource['id'].'/', 'full-news');
	$templates->assign('str', '{nullify-link}', '/'.ENGINE_PATH.'admin/news/nullify/'.$resource['id'].'/', 'full-news');
	$templates->assign('str', '{edit-link}', '/'.ENGINE_PATH.'admin/news/edit/'.$resource['id'].'/', 'full-news');
	if($resource['x-image'] != '') {
		$templates->assign('preg', '~(\[without-x-image\].+\[/without-x-image\])~is', '', 'full-news');
		$templates->assign('str', array('[with-x-image]', '[/with-x-image]'), '', 'full-news');
		$templates->assign('str', '{x-image}', $resource['x-image'], 'full-news');
		$templates->assign('str', '{x-img-width}', $database->getParam('x-img-width'), 'full-news');
		$templates->assign('str', '{x-img-height}', $database->getParam('x-img-height'), 'full-news');
	} else {
		$templates->assign('str', array('[without-x-image]', '[/without-x-image]'), '', 'full-news');
		$templates->assign('preg', '~(\[with-x-image\].+\[/with-x-image\])~is', '', 'full-news');
	}
	ob_start();
		require(SYS_MODULES_ROOT.'comments-add.php');
	$templates->assign('str', '{comments-add}', ob_get_clean(), 'full-news');
	ob_start();
		require(SYS_MODULES_ROOT.'comments-show.php');
	$templates->assign('str', '{comments-show}', ob_get_clean(), 'full-news');
	ob_start();
		if($count_records > $com) {
			$templates->load('navigation.tpl', 'news-navigation');
			$templates->assign('preg', '~\[news\](.*?)\[/news\]~is', '', 'news-navigation');
			$templates->assign('str', array('[comments]', '[/comments]'), '', 'news-navigation');
			ob_start();
				for($page = 1; $page <= $num_pages; $page++) {
					$templates->load('navigation-items.tpl', 'news-navigation-items');
					$templates->assign('preg', '~\[news\](.*?)\[/news\]~is', '', 'news-navigation-items');
					$templates->assign('str', array('[comments]', '[/comments]'), '', 'news-navigation-items');
					if($page == $curpage) {
						$templates->assign('str', array('[active]', '[/active]'), '', 'news-navigation-items');
						$templates->assign('preg', '~(\[item\].+\[/item\])~is', '', 'news-navigation-items');
					} else {
						$templates->assign('str', array('[item]', '[/item]'), '', 'news-navigation-items');
						$templates->assign('preg', '~(\[active\].+\[/active\])~is', '', 'news-navigation-items');
					}
					$templates->assign('str', array('{page-link}', '{page}'), array('/'.ENGINE_PATH.'news/'.$id.'/page/'.$page.'/', $page), 'news-navigation-items');
					echo $templates->display('news-navigation-items');
				}
			$templates->assign('str', '{navigation-items}', ob_get_clean(), 'news-navigation');
			echo $templates->display('news-navigation');
		}
	$templates->assign('str', '{comments-navigation}', ob_get_clean(), 'full-news');
	$all['p-content'] = $templates->display('full-news');
	$all['p-title'] = $resource['title'];
}
?>