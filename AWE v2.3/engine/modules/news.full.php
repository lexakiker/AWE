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

$id = (isset($_GET['id']))?$_GET['id']:'';
$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_news` WHERE `id`=\''.$functions->strip($id).'\'');
$resource = $db->fetch_array($query);
if(empty($id) || $id != $resource['id']) {
	$all['content'] = $tmp->info('info',MSG('NEWS_NOT_FOUND'));
	$all['title'] = 'Информация';
} else {
	$db->query('UPDATE `'.DB_PREFIX.'_news` SET `views`=\''.($resource['views']+1).'\' WHERE `id`=\''.$resource['id'].'\'');
	$comments = $db->fetch_array($db->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'_comments` WHERE `newsid`=\''.$resource['id'].'\''));
	$tmp->theme('fullstory.tpl','news');
	$tmp->assign('{id}',$resource['id'],'news');
	$tmp->assign('{title}',$resource['title'],'news');
	$tmp->assign('{date}',$resource['date'],'news');
	$tmp->assign('{shortstory}',$resource['shortstory'],'news');
	$tmp->assign('{fullstory}',$resource['fullstory'],'news');
	$tmp->assign('{author}',$resource['author'],'news');
	$tmp->assign('{author-link}','/user/'.$resource['author'],'news');
	$tmp->assign('{views}',$resource['views'],'news');
	$tmp->assign('{comments}',$comments[0],'news');
	$tmp->assign('{delete-link}','/admin/news/delete/'.$resource['id'],'news');
	$tmp->assign('{nullify-link}','/admin/news/nullify/'.$resource['id'],'news');
	$tmp->assign('{edit-link}','/admin/news/edit/'.$resource['id'],'news');
	if($resource['ximage'] != '') {
		$tmp->assign(array('[ximage]','[/ximage]'),'','news');
		$tmp->assign('{ximage}',$resource['ximage'],'news');
		$tmp->assign('{ximg_width}',$db->getParam('ximg_width'),'news');
		$tmp->assign('{ximg_height}',$db->getParam('ximg_height'),'news');
	} else $tmp->preg_assign('~(\[ximage\].+\[/ximage\])~is','','news');
	ob_start();
		include(ROOT.'engine/modules/comments.add.php');
	$tmp->assign('{comments.add}',ob_get_clean(),'news');
	ob_start();
		include(ROOT.'engine/modules/comments.show.php');
	$tmp->assign('{comments.show}',ob_get_clean(),'news');
	ob_start();
		if(@$db->num_rows($resource) > 0) {
			$navigation = file_get_contents(ROOT.'themes/'.$db->getParam('theme').'/navigation.tpl');
			$navigation = preg_replace('~\[news\](.*?)\[/news\]~is','',$navigation);
			$navigation = str_replace(array('[comments]','[/comments]'),'',$navigation);
			ob_start();
				for($page = 1; $page <= $num_pages; $page++) {
					$navigation_items = file_get_contents(ROOT.'themes/'.$db->getParam('theme').'/navigation-items.tpl');
					$navigation_items = preg_replace('~\[news\](.*?)\[/news\]~is','',$navigation_items);
					$navigation_items = str_replace(array('[comments]','[/comments]'),'',$navigation_items);
					if($page == $curpage) {
						$navigation_items = str_replace(array('[active]','[/active]'),'',$navigation_items);
						$navigation_items = preg_replace('~(\[item\].+\[/item\])~is','',$navigation_items);
					} else {
						$navigation_items = str_replace(array('[item]','[/item]'),'',$navigation_items);
						$navigation_items = preg_replace('~(\[active\].+\[/active\])~is','',$navigation_items);
					}
					echo str_replace(array('{page-link}','{page}'),array('/news/'.$id.'/page/'.$page,$page),$navigation_items);
				}
			echo str_replace('{navigation_items}',ob_get_clean(),$navigation);
		}
	$tmp->assign('{comments.navigation}',ob_get_clean(),'news');
	$all['content'] = $tmp->display('news');
	$all['title'] = $resource['title'];
}
?>