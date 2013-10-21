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

include(ROOT.'engine/classes/admin.class.php');

$all['p-robots'] = 'noindex,nofollow';
$templates->setPath(ROOT.'engine/include/admin/');
$check_version = file_get_contents('http://files.ameden.net/scripts/checker.php?version='.VERSION);

if($user->checkLogged() && $user->userAdmin() || $user->getPermission('allow-admin') == '1') {
	
	switch($section) {
		
		default:
			$all['p-title'] = 'Главная';
			
			$templates->load('index.tpl', 'index');
			
			$templates->assign('str', '{version}', VERSION, 'index');
			if($check_version == 'true') {
				$templates->assign('preg', '~\[version-oldest\](.*?)\[/version-oldest\]~is', '', 'index');
				$templates->assign('preg', '~\[version-error\](.*?)\[/version-error\]~is', '', 'index');
				$templates->assign('str', array('[version-normal]', '[/version-normal]'), '', 'index');
			} elseif($check_version == 'Scat!') {
				$templates->assign('preg', '~\[version-oldest\](.*?)\[/version-oldest\]~is', '', 'index');
				$templates->assign('preg', '~\[version-normal\](.*?)\[/version-normal\]~is', '', 'index');
				$templates->assign('str', array('[version-error]', '[/version-error]'), '', 'index');
			} else {
				$check_version = explode('~', $check_version);
				if($check_version[0] == 'false') {
					$templates->assign('preg', '~\[version-normal\](.*?)\[/version-normal\]~is', '', 'index');
					$templates->assign('preg', '~\[version-error\](.*?)\[/version-error\]~is', '', 'index');
					$templates->assign('str', array('[version-oldest]', '[/version-oldest]'), '', 'index');
					$templates->assign('str', '{new-version}', $check_version[1], 'index');
				}
			}
			$templates->assign('str', '{add-static-link}', '/'.ENGINE_PATH.'admin/static/add/', 'index');
			
			$templates->assign('str', '{configuration-link}', '/'.ENGINE_PATH.'admin/configuration/', 'index');
			$templates->assign('str', '{optimizer-link}', '/'.ENGINE_PATH.'admin/optimizer/', 'index');
			$templates->assign('str', '{news-link}', '/'.ENGINE_PATH.'admin/news/', 'index');
			$templates->assign('str', '{add-news-link}', '/'.ENGINE_PATH.'admin/news/add/', 'index');
			$templates->assign('str', '{static-link}', '/'.ENGINE_PATH.'admin/static/', 'index');
			$templates->assign('str', '{add-static-link}', '/'.ENGINE_PATH.'admin/static/add/', 'index');
			
			$templates->assign('str', '{all-news}', $database->countFrom('news'), 'index');
			$templates->assign('str', '{all-comments}', $database->countFrom('comments'), 'index');
			$templates->assign('str', '{all-users}', $database->countFrom('users'), 'index');
			// $templates->assign('str', '{all-bans}', $database->countFrom('bans'), 'index');
			
			$templates->assign('str', '{first-user}', $database->forStatistics('first', 'users'), 'index');
			$templates->assign('str', '{last-user}', $database->forStatistics('last', 'users'), 'index');
			
			$templates->assign('str', '{db-size}', $functions->formatFileSize($database->getDatabaseSize()), 'index');
			
			$all['p-content'] = $templates->display('index');
		break;
		
		case 'optimizer':
			$all['p-title'] = 'Мастер оптимизации';
			
			$templates->load('optimizer.tpl', 'optimizer');
			
			if(isset($_POST['optimize'])) {
				if(empty($_POST['non-activated'])) {
					$error = LANG('NOTHING_SELECTED');
				} else {
					$error = LANG('OPTIMIZATION_COMPLETED');
					if(isset($_POST['non-activated'])) {
						$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `checked`=\'0\'');
						$count = $database->num_rows($query);
						if($count > 0) {
							$database->query('DELETE FROM `'.DB_PREFIX.'_users` WHERE `checked`=\'0\'');
							$error .= str_replace('{count}', $count, LANG('DELETE_NON_ACTIVATED'));
						} else { $error .= LANG('NON_ACTIVATED_EMPTY'); }
					}
				}
			}
			
			$templates->assign('str', '{non-activated-status}', isset($_POST['non-activated'])?' checked':'', 'optimizer');
			
			$all['p-content'] = $templates->display('optimizer');
		break;
		
		case 'configuration':
			$all['p-title'] = 'Редактирование конфигурции';
			
			$templates->load('edit-configuration.tpl', 'edit-configuration');
			
			if(isset($_POST['edit-configuration'])) {
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['title'].'\' WHERE `setting`=\'title\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['description'].'\' WHERE `setting`=\'description\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['keywords'].'\' WHERE `setting`=\'keywords\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['off-reason'].'\' WHERE `setting`=\'off-reason\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['admin-mail'].'\' WHERE `setting`=\'admin-mail\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['theme'].'\' WHERE `setting`=\'theme\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['time-zone'].'\' WHERE `setting`=\'time-zone\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['x-img-width'].'\' WHERE `setting`=\'x-img-width\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['x-img-height'].'\' WHERE `setting`=\'x-img-height\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['news-on-page'].'\' WHERE `setting`=\'news-on-page\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['comments-on-page'].'\' WHERE `setting`=\'comments-on-page\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['admin-group'].'\' WHERE `setting`=\'admin-group\'');
				$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['comment-max-sym'].'\' WHERE `setting`=\'comment-max-sym\'');
				$admin->updateBoolean('off-site');
				$admin->updateBoolean('reg-one-ip');
				$admin->updateBoolean('write-user-passwords');
				$admin->updateBoolean('reg-mail-accept');
				$admin->updateBoolean('send-mail-oncomment');
				$error = LANG('CONFIGURATION_WERE_CHANGED');
			}
			
			$templates->assign('str', '{title}', isset($_POST['title'])?$_POST['title']:$database->getParam('title'), 'edit-configuration');
			$templates->assign('str', '{description}', isset($_POST['description'])?$_POST['description']:$database->getParam('description'), 'edit-configuration');
			$templates->assign('str', '{keywords}', isset($_POST['keywords'])?$_POST['keywords']:$database->getParam('keywords'), 'edit-configuration');
			$templates->assign('str', '{off-reason}', isset($_POST['off-reason'])?$_POST['off-reason']:$database->getParam('off-reason'), 'edit-configuration');

			$templates->assign('str', '{admin-mail}', isset($_POST['admin-mail'])?$_POST['admin-mail']:$database->getParam('admin-mail'), 'edit-configuration');
			$templates->assign('str', '{admin-group}', isset($_POST['admin-group'])?$_POST['admin-group']:$database->getParam('admin-group'), 'edit-configuration');
			$templates->assign('str', '{news-on-page}', isset($_POST['news-on-page'])?$_POST['news-on-page']:$database->getParam('news-on-page'), 'edit-configuration');
			$templates->assign('str', '{comments-on-page}', isset($_POST['comments-on-page'])?$_POST['comments-on-page']:$database->getParam('comments-on-page'), 'edit-configuration');
			$templates->assign('str', '{x-img-height}', isset($_POST['x-img-height'])?$_POST['x-img-height']:$database->getParam('x-img-height'), 'edit-configuration');
			$templates->assign('str', '{x-img-width}', isset($_POST['x-img-width'])?$_POST['x-img-width']:$database->getParam('x-img-width'), 'edit-configuration');
			$templates->assign('str', '{comment-max-sym}', isset($_POST['comment-max-sym'])?$_POST['comment-max-sym']:$database->getParam('comment-max-sym'), 'edit-configuration');
			
			$templates->assign('str', '{time-zone-selector}', $time_zone->zones_list($database->getParam('time-zone')), 'edit-configuration');
			$templates->assign('str', '{theme-selector}', $admin->getSelectBox('theme'), 'edit-configuration');
			$templates->assign('str', '{off-site-selector}', $admin->getSelectBox('off-site'), 'edit-configuration');
			$templates->assign('str', '{send-mail-oncomment-selector}', $admin->getSelectBox('send-mail-oncomment'), 'edit-configuration');
			$templates->assign('str', '{reg-mail-accept-selector}', $admin->getSelectBox('reg-mail-accept'), 'edit-configuration');
			$templates->assign('str', '{reg-one-ip-selector}', $admin->getSelectBox('reg-one-ip'), 'edit-configuration');
			$templates->assign('str', '{write-user-passwords-selector}', $admin->getSelectBox('write-user-passwords'), 'edit-configuration');
			
			$all['p-content'] = $templates->display('edit-configuration');
		break;
		
		case 'news':
			$all['p-title'] = 'Управление новостями';
			
			$templates->load('news.tpl', 'news');
			
			ob_start();
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_news` ORDER BY `id` DESC');
				if($database->num_rows($query) > 0) {
					$numb = 0;
					while($myrow = $database->fetch_array($query)) {
						$numb += 1;
						
						$templates->load('news-item.tpl', 'news-items');
						
						$templates->assign('str', '{title}', $myrow['title'], 'news-items');
						$templates->assign('str', '{date}', $myrow['date'], 'news-items');
						$templates->assign('str', '{author}', $myrow['author'], 'news-items');
						$templates->assign('str', '{views}', $myrow['views'], 'news-items');
						$templates->assign('str', '{author-link}', '/'.ENGINE_PATH.'user/'.$myrow['author'].'/', 'news-items');
						$templates->assign('str', '{edit-link}', '/'.ENGINE_PATH.'admin/news/edit/'.$myrow['id'].'/', 'news-items');
						$templates->assign('str', '{delete-link}', '/'.ENGINE_PATH.'admin/news/delete/'.$myrow['id'].'/', 'news-items');
						$templates->assign('str', '{nullify-link}', '/'.ENGINE_PATH.'admin/news/nullify/'.$myrow['id'].'/', 'news-items');
						$templates->assign('str', '{item-link}', '/'.ENGINE_PATH.'news/'.$myrow['id'].'/', 'news-items');
						
						if($database->num_rows($query) != $numb) {
							$templates->assign('str', array('[no-last]', '[/no-last]'), '', 'news-items');
						} else { $templates->assign('preg', '~\[no-last\](.*?)\[/no-last\]~is', '', 'news-items'); }
						
						echo $templates->display('news-items');
					}
				} else { echo LANG('NO_NEWS'); }
			$templates->assign('str', '{news-items}', ob_get_clean(), 'news');
			
			$all['p-content'] = $templates->display('news');
		break;
		
		case 'static':
			$all['p-title'] = 'Управление статическими страницами';
			
			$templates->load('static.tpl', 'static');
			
			ob_start();
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_static` ORDER BY `id` DESC');
				if($database->num_rows($query) > 0) {
					$numb = 0;
					while($myrow = $database->fetch_array($query)) {
						$numb += 1;
						
						$templates->load('static-item.tpl', 'static-items');
						
						$templates->assign('str', '{title}', $myrow['title'], 'static-items');
						$templates->assign('str', '{url}', $myrow['url'], 'static-items');
						$templates->assign('str', '{edit-link}', '/'.ENGINE_PATH.'admin/static/edit/'.$myrow['id'].'/', 'static-items');
						$templates->assign('str', '{delete-link}', '/'.ENGINE_PATH.'admin/static/delete/'.$myrow['id'].'/', 'static-items');
						$templates->assign('str', '{item-link}', '/'.ENGINE_PATH.'do/'.$myrow['url'].'/', 'static-items');
						
						if($database->num_rows($query) != $numb) {
							$templates->assign('str', array('[no-last]', '[/no-last]'), '', 'static-items');
						} else { $templates->assign('preg', '~\[no-last\](.*?)\[/no-last\]~is', '', 'static-items'); }
						
						echo $templates->display('static-items');
					}
				} else { echo LANG('NO_PAGES'); }
			$templates->assign('str', '{static-items}', ob_get_clean(), 'static');
			
			$all['p-content'] = $templates->display('static');
		break;
		
		case 'edit-news':
			$all['p-title'] = 'Изменение новости';
			$id = isset($_GET['id'])?$_GET['id']:'';
			
			$templates->load('edit-news.tpl', 'edit-news');
			
			if($id != '') {
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_news` WHERE `id`=\''.$id.'\'');
				$resource = $database->fetch_array($query);
				if($database->num_rows($query) > 0) {
					if(isset($_POST['edit-news'])) {
						if(empty($_POST['title']) or empty($_POST['short-story']) or empty($_POST['full-story'])) {
							$error = LANG('ALL_FIELDS_REQUIRED');
						} else {
							$database->query('UPDATE `'.DB_PREFIX.'_news` SET `title`=\''.$_POST['title'].'\', `short-story`=\''.$admin->strip($_POST['short-story']).'\', `fullstory`=\''.$admin->strip($_POST['full-story']).'\', `x-image`=\''.$_POST['x-image'].'\' WHERE `id`=\''.$id.'\'');
							$error = str_replace('{id}', $id, LANG('NEWS_WERE_CHANGED'));
						}
					}
 					$templates->assign('str', array('[form]', '[/form]'), '', 'edit-news');
				} else { $templates->assign('preg', '~\[form\](.*?)\[/form\]~is', str_replace('{id}', $id, LANG('INVALID_NEWS_ID')), 'edit-news'); }
				$templates->assign('str', '{title}', isset($_POST['title'])?$_POST['title']:$resource['title'], 'edit-news');
				$templates->assign('str', '{short-story}', isset($_POST['short-story'])?$_POST['short-story']:$resource['short-story'], 'edit-news');
				$templates->assign('str', '{full-story}', isset($_POST['full-story'])?$_POST['full-story']:$resource['full-story'], 'edit-news');
				$templates->assign('str', '{x-image}', isset($_POST['x-image'])?$_POST['x-image']:$resource['x-image'], 'edit-news');
			} else { $templates->assign('preg', '~\[form\](.*?)\[/form\]~is', LANG('EMPTY_NEWS_ID'), 'edit-news'); }
			
			$all['p-content'] = $templates->display('edit-news');
		break;
		
		case 'edit-static':
			$all['p-title'] = 'Изменение статической страницы';
			$id = isset($_GET['id'])?$_GET['id']:'';
			
			$templates->load('edit-static.tpl', 'edit-static');
			
			if($id != '') {
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `id`=\''.$id.'\'');
				$resource = $database->fetch_array($query);
				if($database->num_rows($query) > 0) {
					if(isset($_POST['edit-static'])) {
						if(empty($_POST['url']) or empty($_POST['title']) or empty($_POST['content'])) {
							$error = LANG('ALL_FIELDS_REQUIRED');
						} else {
							$url_query = $database->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `url`=\''.$_POST['url'].'\'');
							$id_query = $database->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `id`=\''.$id.'\'');
							$resource = $database->fetch_array($id_query);
							if($resource['url'] == $_POST['url']) {
								$database->query('UPDATE `'.DB_PREFIX.'_static` SET `url`=\''.$_POST['url'].'\', `content`=\''.addslashes($_POST['content']).'\', `title`=\''.$_POST['title'].'\' WHERE `id`=\''.$id.'\'');
								$error = str_replace('{id}', $id, LANG('PAGE_WERE_CHANGED'));
							} else {
								if($database->num_rows($url_query) == 0) {
									$database->query('UPDATE `'.DB_PREFIX.'_static` SET `url`=\''.$_POST['url'].'\', `content`=\''.addslashes($_POST['content']).'\', `title`=\''.$_POST['title'].'\' WHERE `id`=\''.$id.'\'');
									$error = str_replace('{id}',$id,LANG('PAGE_WERE_CHANGED'));
								} else { $error = str_replace('{url}', $_POST['url'], LANG('PAGE_ALREADY_EXISTS')); }
							}
						}
					}
 					$templates->assign('str', array('[form]', '[/form]'), '', 'edit-static');
				} else { $templates->assign('preg', '~\[form\](.*?)\[/form\]~is', str_replace('{id}', $id, LANG('INVALID_PAGE_ID')), 'edit-static'); }
				$templates->assign('str', '{title}', isset($_POST['title'])?$_POST['title']:$resource['title'], 'edit-static');
				$templates->assign('str', '{content}', isset($_POST['content'])?$_POST['content']:$resource['content'], 'edit-static');
				$templates->assign('str', '{url}', isset($_POST['url'])?$_POST['url']:$resource['url'], 'edit-static');
			} else { $templates->assign('preg', '~\[form\](.*?)\[/form\]~is', LANG('EMPTY_PAGE_ID'), 'edit-static'); }
			
			$templates->assign('str', '{site-link}', $_SERVER['HTTP_HOST'], 'edit-static');
			
			$all['p-content'] = $templates->display('edit-static');
		break;
		
		case 'add-news':
			$all['p-title'] = 'Добавление новостей';
			
			$templates->load('add-news.tpl', 'add-news');
			if(isset($_POST['add-news'])) {
				if(empty($_POST['title']) || empty($_POST['short-story'])) {
					$error = LANG('ALL_FIELDS_REQUIRED');
				} else {
					$full_story = ($_POST['full-story'] != '')?$_POST['full-story']:$_POST['short-story'];
					$database->query('INSERT INTO `'.DB_PREFIX.'_news` (`title`, `short-story`, `full-story`, `x-image`, `date`, `author`) VALUES (\''.$_POST['title'].'\', \''.$admin->strip($_POST['short-story']).'\', \''.$admin->strip($full_story).'\', \''.$_POST['x-image'].'\', \''.$functions->curDate().'\', \''.$user->getArray('username').'\')') or $functions->mistake(mysql_error());
					$error = LANG('NEWS_WERE_ADDED');
				}
			}
			
			$templates->assign('str', '{title}', isset($_POST['title'])?$_POST['title']:'', 'add-news');
			$templates->assign('str', '{short-story}', isset($_POST['short-story'])?$_POST['short-story']:'', 'add-news');
			$templates->assign('str', '{full-story}', isset($_POST['full-story'])?$_POST['full-story']:'', 'add-news');
			$templates->assign('str', '{x-image}', isset($_POST['x-image'])?$_POST['x-image']:'', 'add-news');
			$templates->assign('str', '{x-width}', $database->getParam('x-img-width'), 'add-news');
			$templates->assign('str', '{x-height}', $database->getParam('x-img-height'), 'add-news');
			
			$all['p-content'] = $templates->display('add-news');
		break;
		
		case 'add-static':
			$all['p-title'] = 'Добавление статических страниц';
			
			$templates->load('add-static.tpl', 'add-static');
			
			if(isset($_POST['add-static'])) {
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `url`=\''.$_POST['url'].'\'');
				if(empty($_POST['title']) || empty($_POST['content']) || empty($_POST['url'])) {
					$error = LANG('ALL_FIELDS_REQUIRED');
				} elseif(!preg_match("/^[a-zA-Z0-9-_]+$/", $_POST['url'])) {
					$error = LANG('URL_IS_INVALID');
				} elseif($database->num_rows($query) == 0) {
					$database->query('INSERT INTO `'.DB_PREFIX.'_static` (`title`, `content`, `url`) VALUES (\''.$_POST['title'].'\', \''.$admin->strip($_POST['content']).'\', \''.$_POST['url'].'\')');
					$error = str_replace('{page-url}', '/'.ENGINE_PATH.'do/'.$_POST['url'].'/', LANG('PAGE_WERE_ADDED'));
				} else { $error = str_replace('{url}', $_POST['url'], LANG('PAGE_ALREADY_EXISTS')); }
			}
			
			$templates->assign('str', '{title}', isset($_POST['title'])?$_POST['title']:'', 'add-static');
			$templates->assign('str', '{content}', isset($_POST['content'])?$_POST['content']:'', 'add-static');
			$templates->assign('str', '{url}', isset($_POST['url'])?$_POST['url']:'', 'add-static');
			$templates->assign('str', '{site-link}', $_SERVER['HTTP_HOST'], 'add-static');
			
			$all['p-content'] = $templates->display('add-static');
		break;
		
		case 'delete-news':
			$all['p-title'] = 'Удаление новости';
			$id = isset($_GET['id'])?$_GET['id']:'';
			$delete = isset($_GET['delete'])?$_GET['delete']:'';
			
			$templates->load('admin-page.tpl', 'admin');
			
			if($id != '') {
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_news` WHERE `id`=\''.$id.'\'');
				if($database->num_rows($query) > 0) {
					if($delete == 'yes') {
						$database->query('DELETE FROM `'.DB_PREFIX.'_news` WHERE `id`=\''.$id.'\'');
						$database->query('DELETE FROM `'.DB_PREFIX.'_comments` WHERE `news-id`=\''.$id.'\'');
						$error = str_replace('{id}', $id, LANG('NEWS_WERE_DELETED'));
					} else { $error = str_replace(array('{id}', '{accept-link}'), array($id, '/'.ENGINE_PATH.'admin/news/delete/'.$id.'/yes/'), LANG('TRUE_DELETE_NEWS')); }
				} else { $error = str_replace('{id}', $id,LANG('INVALID_NEWS_ID')); }
			} else { $error = LANG('EMPTY_NEWS_ID'); }
			
			$all['p-content'] = $templates->display('admin');
			$templates->clear('admin');
		break;
		
		case 'delete-static': 
			$all['p-title'] = 'Удаление статической страницы';
			$id = isset($_GET['id'])?$_GET['id']:'';
			$delete = isset($_GET['delete'])?$_GET['delete']:'';
			
			$templates->load('admin-page.tpl', 'admin');
			
			if($id != '') {
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `id`=\''.$id.'\'');
				if($database->num_rows($query) > 0) {
					if($delete == 'yes') {
						$database->query('DELETE FROM `'.DB_PREFIX.'_static` WHERE `id`=\''.$id.'\'');
						$error = str_replace('{id}', $id, LANG('PAGE_WERE_DELETED'));
					} else { $error = str_replace(array('{id}', '{accept-link}'), array($id, '/'.ENGINE_PATH.'admin/static/delete/'.$id.'/yes/'), LANG('TRUE_DELETE_PAGE')); }
				} else { $error = str_replace('{id}', $id, LANG('INVALID_PAGE_ID')); }
			} else { $error = LANG('EMPTY_PAGE_ID'); }
			
			$all['p-content'] = $templates->display('admin');
			$templates->clear('admin');
		break;
		
		case 'delete-comments':
			$all['p-title'] = 'Удаление комментария';
			$id = isset($_GET['id'])?$_GET['id']:'';
			$delete = isset($_GET['delete'])?$_GET['delete']:'';
			
			$templates->load('admin-page.tpl', 'admin');
			
			if($id != '') {
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_comments` WHERE `id`=\''.$id.'\'');
				if($database->num_rows($query) > 0) {
					if($delete == 'yes') {
						$database->query('DELETE FROM `'.DB_PREFIX.'_comments` WHERE `id`=\''.$id.'\'');
						$error = str_replace('{id}', $id, LANG('COMMENT_WERE_DELETED'));
					} else { $error = str_replace(array('{id}', '{accept-link}'), array($id, '/'.ENGINE_PATH.'admin/comments/delete/'.$id.'/yes/'), LANG('TRUE_DELETE_COMMENT')); }
				} else { $error = str_replace('{id}', $id, LANG('INVALID_COMMENT_ID')); }
			} else { $error = LANG('EMPTY_COMMENT_ID'); }
			
			$all['p-content'] = $templates->display('admin');
			$templates->clear('admin');
		break;
		
		case 'nullify-news':
			$all['p-title'] = 'Обнуление просмотров';
			$id = isset($_GET['id'])?$_GET['id']:'';
			
			$templates->load('admin-page.tpl', 'admin');
			
			if($id != '') {
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_news` WHERE `id`=\''.$id.'\'');
				if($database->num_rows($query) > 0) {
					$database->query('UPDATE `'.DB_PREFIX.'_news` SET `views`=\'0\' WHERE `id`=\''.$id.'\'');
					$error = str_replace('{id}', $id, LANG('NEWS_WERE_NULLED'));
				} else { $error = str_replace('{id}', $id, LANG('INVALID_NEWS_ID')); }
			} else { $error = LANG('EMPTY_NEWS_ID'); }
			
			$all['p-content'] = $templates->display('admin');
			$templates->clear('admin');
		break;
		
	}
	
	$templates->load('admin.tpl', 'admin');
	
	$templates->assign('str', '{p-headers}', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="Author" content="RevenHell">
		<meta name="Generator" content="Ameden Web Engine ('.VERSION.')">
		<meta name="Robots" content="'.$all['p-robots'].'">
		<script type="text/javascript" src="/'.ENGINE_PATH.'engine/include/js/jQuery-1.9.1.min.js"></script>', 'admin');
	$templates->assign('str', '{p-title}', $all['p-title'], 'admin');
	$templates->assign('str', '{p-content}', $all['p-content'], 'admin');
	$templates->assign('str', '{THEME}', '/'.ENGINE_PATH.'engine/include', 'admin');
	$templates->assign('str', '{username}', $user->getArray('username'), 'admin');
	$templates->assign('str', '{engine-name}', 'Ameden Web Engine', 'admin');
	$templates->assign('str', '{admin-link}', '/'.ENGINE_PATH.'admin/', 'admin');
	$templates->assign('str', '{logout-link}', '/'.ENGINE_PATH.'logout/admin/', 'admin');
	$templates->assign('str', '{main-link}', '/'.ENGINE_PATH, 'admin');
	
	if($error != '') {
		$templates->assign('str', array('[message]', '[/message]'), '', 'admin');
		$templates->assign('str', '{message}', $error, 'admin');
	} else { $templates->assign('preg', '~\[message\](.*?)\[/message\]~is', '', 'admin'); }
	
	echo $templates->display('admin', true);
	
} else {
	
	$templates->load('login.tpl', 'admin');
	
	if(isset($_POST['admin-send'])) {
		switch($user->checkAuth($functions->strip($_POST['username']), $functions->strip($_POST['password']), true)) {
			case 1: $error = LANG('ALL_FIELDS_REQUIRED'); break;
			case 2: $error = LANG('AUTHORIZATION_ERROR'); break;
			case 3: $error = LANG('ACCOUNT_IS_BANNED'); break;
			case 4: $error = LANG('ACCOUNT_IS_NOT_CONFIRM'); break;
			case 41: $error = LANG('ACCESS_DENIED'); break;
			case 5:
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$functions->strip($_POST['username']).'\' AND `password`=\''.$functions->strip($functions->crypt($_POST['password'])).'\'');
				$resource = $database->fetch_array($query);
				if(isset($_POST['remember'])) {
					$token = $functions->generateToken($functions->strip($_POST['username']));
					setcookie('token', $token, time()+2592000, '/', $_SERVER['HTTP_HOST']);
					$database->query('UPDATE `'.DB_PREFIX.'_users` SET `token`=\''.$token.'\' WHERE `username`=\''.$functions->strip($_POST['username']).'\'');
				}
				$_SESSION['username'] = $resource['username'];
				$functions->spaceTo('/'.ENGINE_PATH.'admin/');
			break;
		}
	}
	
	if($error != '') {
		$templates->assign('str', array('[message]', '[/message]'), '', 'admin');
	} else { $templates->assign('preg', '~\[message\](.*?)\[/message\]~is', '', 'admin'); }
	$templates->assign('str', '{message}', $error, 'admin');
	$templates->assign('str', '{p-title}', 'Авторизация', 'admin');
	$templates->assign('str', '{engine-name}', 'Ameden Web Engine', 'admin');
	$templates->assign('str', '{p-headers}', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="Author" content="RevenHell">
		<meta name="Generator" content="Ameden Web Engine ('.VERSION.')">
		<meta name="Robots" content="'.$all['p-robots'].'">
		<script type="text/javascript" src="/'.ENGINE_PATH.'engine/include/js/jQuery-1.9.1.min.js"></script>', 'admin');
	$templates->assign('str', '{THEME}', '/'.ENGINE_PATH.'engine/include', 'admin');
	
	echo $templates->display('admin', true);
	
}

$database->close();
die();
?>