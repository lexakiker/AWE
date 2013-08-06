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

$section = (isset($_GET['section']))?$_GET['section']:'';
$all = array('title' => '', 'info' => '', 'content' => '');
$e = NULL;

if($user->check_logged() && $user->get_array('group') == $db->getParam('admin_group') || $user->get_permission('allow_admin') == 1) {
	
	switch($section) {
		
		default:
			$tmp->theme('index.tpl','admin_page',true);
			$tmp->assign('{configure-link}','/admin/configure','admin_page');
			$tmp->assign('{news-link}','/admin/news','admin_page');
			$tmp->assign('{addnews-link}','/admin/news/add','admin_page');
			$tmp->assign('{static-link}','/admin/static','admin_page');
			$tmp->assign('{addstatic-link}','/admin/static/add','admin_page');
			$all['content'] = $tmp->display('admin_page');
			$all['title'] = 'Главная';
		break;
		
		case 'news':
			$tmp->theme('news.tpl','admin_page',true);
			ob_start();
				$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_news` ORDER BY `id`');
				if(@$db->num_rows($query) != 0) {
					while($myrow = $db->fetch_array($query)) {
						$tmp->theme('news-item.tpl','news_items',true);
						$tmp->assign('{title}',$myrow['title'],'news_items');
						$tmp->assign('{date}',$myrow['title'],'news_items');
						$tmp->assign('{author}',$myrow['author'],'news_items');
						$tmp->assign('{author-link}','/user/'.$myrow['author'],'news_items');
						$tmp->assign('{edit-link}','/admin/news/edit/'.$myrow['id'],'news_items');
						$tmp->assign('{delete-link}','/admin/news/delete/'.$myrow['id'],'news_items');
						$tmp->assign('{nullify-link}','/admin/news/nullify/'.$myrow['id'],'news_items');
						$tmp->assign('{item-link}','/news/'.$myrow['id'],'news_items');
						echo $tmp->display('news_items');
					}
				} else echo MSG('NO_NEWS');
			$tmp->assign('{news-items}',ob_get_clean(),'admin_page');
			$all['content'] = $tmp->display('admin_page');
			$all['title'] = 'Управление новостями';
		break;
		
		case 'configure':
			$array = array('Да','Нет');
			function getSelectBox($input) {
				global $db, $array;
				ob_start();
					echo '<select name="'.$input.'">';
					for($i = 0; $i < sizeof($array); $i++) {
						if($array[$i] == 'Нет') {
							if($db->getParam($input) == 'false') echo '<option selected value="'.$array[$i].'">'.$array[$i].'</option>';
							else echo '<option value="'.$array[$i].'">'.$array[$i].'</option>';
						} else {
							if($db->getParam($input) == 'true') echo '<option selected value="'.$array[$i].'">'.$array[$i].'</option>';
							else echo '<option value="'.$array[$i].'">'.$array[$i].'</option>';
						}
					}
					echo '</select>';
				return ob_get_clean();
			}
			$tmp->theme('editconfigure.tpl','admin_page',true);
			if(isset($_POST['editconfigure'])) {
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['title'].'\' WHERE `setting`=\'title\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['description'].'\' WHERE `setting`=\'description\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['tags'].'\' WHERE `setting`=\'tags\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['die_reason'].'\' WHERE `setting`=\'die_reason\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['admin_mail'].'\' WHERE `setting`=\'admin_mail\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['theme'].'\' WHERE `setting`=\'theme\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['timezone'].'\', WHERE `setting`=\'timezone\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['ximg_width'].'\' WHERE `setting`=\'ximg_width\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['ximg_height'].'\' WHERE `setting`=\'ximg_height\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['news_onpage'].'\' WHERE `setting`=\'news_onpage\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['comments_onpage'].'\' WHERE `setting`=\'comments_onpage\'');
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$_POST['admin_group'].'\' WHERE `setting`=\'admin_group\'');
				if($_POST['die_site'] == 'Да') $bool = 'true'; else $bool = 'false';
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$bool.'\' WHERE `setting`=\'die_site\'');
				if($_POST['reg_one_ip'] == 'Да') $bool = 'true'; else $bool = 'false';
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$bool.'\' WHERE `setting`=\'reg_one_ip\'');
				if($_POST['write_user_passwords'] == 'Да') $bool = 'true'; else $bool = 'false';
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$bool.'\' WHERE `setting`=\'write_user_passwords\'');
				if($_POST['reg_mail_accept'] == 'Да') $bool = 'true'; else $bool = 'false';
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$bool.'\' WHERE `setting`=\'reg_mail_accept\'');
				if($_POST['hide_login_box'] == 'Да') $bool = 'true'; else $bool = 'false';
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$bool.'\' WHERE `setting`=\'hide_login_box\'');
				if($_POST['send_mail_oncomment'] == 'Да') $bool = 'true'; else $bool = 'false';
				$db->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$bool.'\' WHERE `setting`=\'send_mail_oncomment\'');
				$bool = '';
				$e = MSG('CONFIGURE_WERE_CHANGED');
			}
			if(isset($e)) $tmp->assign(array('[message]','[/message]'),'','admin_page');
			else $tmp->preg_assign('~\[message\](.*?)\[/message\]~is','','admin_page');
			$tmp->assign('{title}',(isset($_POST['title']))?$_POST['title']:$db->getParam('title'),'admin_page');
			$tmp->assign('{description}',(isset($_POST['description']))?$_POST['description']:$db->getParam('description'),'admin_page');
			$tmp->assign('{tags}',(isset($_POST['tags']))?$_POST['tags']:$db->getParam('tags'),'admin_page');
			$tmp->assign('{die_reason}',(isset($_POST['die_reason']))?$_POST['die_reason']:$db->getParam('die_reason'),'admin_page');
			$tmp->assign('{timezone}',(isset($_POST['timezone']))?$_POST['timezone']:$db->getParam('timezone'),'admin_page');
			$tmp->assign('{admin_mail}',(isset($_POST['admin_mail']))?$_POST['admin_mail']:$db->getParam('admin_mail'),'admin_page');
			$tmp->assign('{admin_group}',(isset($_POST['admin_group']))?$_POST['admin_group']:$db->getParam('admin_group'),'admin_page');
			$tmp->assign('{news_onpage}',(isset($_POST['news_onpage']))?$_POST['news_onpage']:$db->getParam('news_onpage'),'admin_page');
			$tmp->assign('{comments_onpage}',(isset($_POST['comments_onpage']))?$_POST['comments_onpage']:$db->getParam('comments_onpage'),'admin_page');
			$tmp->assign('{ximg_height}',(isset($_POST['ximg_height']))?$_POST['ximg_height']:$db->getParam('ximg_height'),'admin_page');
			$tmp->assign('{ximg_width}',(isset($_POST['ximg_width']))?$_POST['ximg_width']:$db->getParam('ximg_width'),'admin_page');
			ob_start();
				echo '<select name="theme">';
				if(is_dir(ROOT.'/themes/')) {
					$files = scandir(ROOT.'/themes/');
					array_shift($files);
					array_shift($files);
					for($i = 0; $i < sizeof($files); $i++) {
						if($files[$i] != '.htaccess') {
							if($db->getParam('theme') == $files[$i]) $selected[$i] = 'selected ';
							echo '<option '.$selected[$i].'value="'.$files[$i].'">'.$files[$i].'</option>';
						}
					}
				}
				echo '</select>';
			$tmp->assign('{theme_selector}',ob_get_clean(),'admin_page');
			$tmp->assign('{die_site}',getSelectBox('die_site'),'admin_page');
			$tmp->assign('{send_mail_oncomment}',getSelectBox('send_mail_oncomment'),'admin_page');
			$tmp->assign('{hide_login_box}',getSelectBox('hide_login_box'),'admin_page');
			$tmp->assign('{reg_mail_accept}',getSelectBox('reg_mail_accept'),'admin_page');
			$tmp->assign('{reg_one_ip}',getSelectBox('reg_one_ip'),'admin_page');
			$tmp->assign('{write_user_passwords}',getSelectBox('write_user_passwords'),'admin_page');
			$tmp->assign('{message}',$e,'admin_page');
			$all['content'] = $tmp->display('admin_page');
			$all['title'] = 'Изменение конфигурции';
		break;
		
		case 'addnews':
			$tmp->theme('addnews.tpl','admin_page',true);
			if(isset($_POST['addnews'])) {
				if(empty($_POST['title']) || empty($_POST['shortstory'])) $e = MSG('ALL_FIELDS_REQUIRED');
				else {
					$fullstory = ($_POST['fullstory'] != '')?$_POST['fullstory']:$_POST['shortstory'];
					$db->query('INSERT INTO `'.DB_PREFIX.'_news` (`title`, `shortstory`, `fullstory`, `ximage`, `date`, `author`) VALUES (\''.$_POST['title'].'\', \''.addslashes($_POST['shortstory']).'\', \''.addslashes($fullstory).'\', \''.$_POST['ximage'].'\', \''.date('d.m.Y, в H:i').'\', \''.$user->get_array('username').'\')') or die(mysql_error());
					$e = MSG('NEWS_WERE_ADDED');
				}
			}
			if(isset($e)) $tmp->assign(array('[message]','[/message]'),'','admin_page');
			else $tmp->preg_assign('~\[message\](.*?)\[/message\]~is','','admin_page');
			$tmp->assign('{title}',(isset($_POST['title']))?$_POST['title']:'','admin_page');
			$tmp->assign('{shortstory}',(isset($_POST['shortstory']))?$_POST['shortstory']:'','admin_page');
			$tmp->assign('{fullstory}',(isset($_POST['fullstory']))?$_POST['fullstory']:'','admin_page');
			$tmp->assign('{ximage}',(isset($_POST['ximage']))?$_POST['ximage']:'','admin_page');
			$tmp->assign('{message}',$e,'admin_page');
			$all['content'] = $tmp->display('admin_page');
			$all['title'] = 'Добавление новостей';
		break;
		
		case 'deletenews':
			$tmp->theme('admin_page.tpl','admin_index',true);
			$id = (isset($_GET['id']))?$_GET['id']:'';
			$delete = (isset($_GET['delete']))?$_GET['delete']:'';
			if($id != '') {
				if($db->num_rows($db->query('SELECT * FROM `'.DB_PREFIX.'_news` WHERE `id`=\''.$id.'\'')) > 0) {
					if($delete == 'yes') {
						$db->query('DELETE FROM `'.DB_PREFIX.'_news` WHERE `id`=\''.$id.'\'');
						$db->query('DELETE FROM `'.DB_PREFIX.'_comments` WHERE `newsid`=\''.$id.'\'');
						$tmp->assign('{message}',str_replace('{id}',$id,MSG('NEWS_WERE_DELETED')),'admin_index');
					} else $tmp->assign('{message}',str_replace(array('{id}','{accept-link}'),array($id,'/admin/news/delete/'.$id.'/yes'),MSG('TRUE_DELETE_NEWS')),'admin_index');
				} else $tmp->assign('{message}',str_replace('{id}',$id,MSG('INVALID_NEWS_ID')),'admin_index');
			} else $tmp->assign('{message}',MSG('EMPTY_NEWS_ID'),'admin_index');
			$all['content'] = $tmp->display('admin_index');
			$tmp->clear('admin_index');
			$all['title'] = 'Удаление новости';
		break;
		
		case 'nullifynews':
			$tmp->theme('admin_page.tpl','admin_index',true);
			$id = (isset($_GET['id']))?$_GET['id']:'';
			if($id != '') {
				if($db->num_rows($db->query('SELECT * FROM `'.DB_PREFIX.'_news` WHERE `id`=\''.$id.'\'')) > 0) {
					$db->query('UPDATE `'.DB_PREFIX.'_news` SET `views`=\'0\' WHERE `id`=\''.$id.'\'');
					$tmp->assign('{message}',str_replace('{id}',$id,MSG('NEWS_WERE_NULLED')),'admin_index');
				} else $tmp->assign('{message}',str_replace('{id}',$id,MSG('INVALID_NEWS_ID')),'admin_index');
			} else $tmp->assign('{message}',MSG('EMPTY_NEWS_ID'),'admin_index');
			$all['content'] = $tmp->display('admin_index');
			$tmp->clear('admin_index');
			$all['title'] = 'Обнуление просмотров';
		break;
		
		case 'editnews':
			$tmp->theme('editnews.tpl','admin_page',true);
			$id = (isset($_GET['id']))?$_GET['id']:'';
			if($id != '') {
				$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_news` WHERE `id`=\''.$id.'\'');
				$resource = $db->fetch_array($query);
				if($db->num_rows($query) > 0) {
					if(isset($_POST['editnews'])) {
						if(empty($_POST['title']) or empty($_POST['shortstory']) or empty($_POST['fullstory'])) $e = MSG('ALL_FIELDS_REQUIRED');
						else {
							$db->query('UPDATE `'.DB_PREFIX.'_news` SET `title`=\''.$_POST['title'].'\', `shortstory`=\''.addslashes($_POST['shortstory']).'\', `fullstory`=\''.addslashes($_POST['fullstory']).'\', `ximage`=\''.$_POST['ximage'].'\' WHERE `id`=\''.$id.'\'');
							$e = str_replace('{id}',$id,MSG('NEWS_WERE_CHANGED'));
						}
					}
 					$tmp->assign(array('[form]','[/form]'),'','admin_page');
				} else {
					$e = str_replace('{id}',$id,MSG('INVALID_NEWS_ID'));
					$tmp->preg_assign('~\[form\](.*?)\[/form\]~is','','admin_page');
				}
				$tmp->assign('{title}',(isset($_POST['title']))?$_POST['title']:$resource['title'],'admin_page');
				$tmp->assign('{shortstory}',(isset($_POST['shortstory']))?$_POST['shortstory']:$resource['shortstory'],'admin_page');
				$tmp->assign('{fullstory}',(isset($_POST['fullstory']))?$_POST['fullstory']:$resource['fullstory'],'admin_page');
				$tmp->assign('{ximage}',(isset($_POST['ximage']))?$_POST['ximage']:$resource['ximage'],'admin_page');
			} else {
				$e = MSG('EMPTY_NEWS_ID');
				$tmp->preg_assign('~\[form\](.*?)\[/form\]~is','','admin_page');
			}
			if(isset($e)) $tmp->assign(array('[message]','[/message]'),'','admin_page');
			else $tmp->preg_assign('~\[message\](.*?)\[/message\]~is','','admin_page');
			$tmp->assign('{message}',$e,'admin_page');
			$all['content'] = $tmp->display('admin_page');
			$all['title'] = 'Изменение новости';
		break;
		
		case 'editstatic':
			$tmp->theme('editstatic.tpl','admin_page',true);
			$id = (isset($_GET['id']))?$_GET['id']:'';
			if($id != '') {
				$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `id`=\''.$id.'\'');
				$resource = $db->fetch_array($query);
				if($db->num_rows($query) > 0) {
					if(isset($_POST['editstatic'])) {
						if(empty($_POST['url']) or empty($_POST['title']) or empty($_POST['content'])) $e = MSG('ALL_FIELDS_REQUIRED');
						else {
							$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `url`=\''.$_POST['url'].'\'');
							$resource = $db->fetch_array($db->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `id`=\''.$id.'\''));
							if($resource['url'] == $_POST['url']) {
								$db->query('UPDATE `'.DB_PREFIX.'_static` SET `url`=\''.$_POST['url'].'\', `content`=\''.addslashes($_POST['content']).'\', `title`=\''.$_POST['title'].'\' WHERE `id`=\''.$id.'\'');
								$e = str_replace('{id}',$id,MSG('PAGE_WERE_CHANGED'));
							} else {
								if($db->num_rows($query) == 0) {
									$db->query('UPDATE `'.DB_PREFIX.'_static` SET `url`=\''.$_POST['url'].'\', `content`=\''.addslashes($_POST['content']).'\', `title`=\''.$_POST['title'].'\' WHERE `id`=\''.$id.'\'');
									$e = str_replace('{id}',$id,MSG('PAGE_WERE_CHANGED'));
								} else $e = str_replace('{url}',$_POST['url'],MSG('PAGE_ALREADY_EXISTS'));
							}
						}
					}
 					$tmp->assign(array('[form]','[/form]'),'','admin_page');
				} else {
					$e = str_replace('{id}',$id,MSG('INVALID_PAGE_ID'));
					$tmp->preg_assign('~\[form\](.*?)\[/form\]~is','','admin_page');
				}
				$tmp->assign('{title}',(isset($_POST['title']))?$_POST['title']:$resource['title'],'admin_page');
				$tmp->assign('{content}',(isset($_POST['content']))?$_POST['content']:$resource['content'],'admin_page');
				$tmp->assign('{url}',(isset($_POST['url']))?$_POST['url']:$resource['url'],'admin_page');
			} else {
				$e = MSG('EMPTY_PAGE_ID');
				$tmp->preg_assign('~\[form\](.*?)\[/form\]~is','','admin_page');
			}
			if(isset($e)) $tmp->assign(array('[message]','[/message]'),'','admin_page');
			else $tmp->preg_assign('~\[message\](.*?)\[/message\]~is','','admin_page');
			$tmp->assign('{message}',$e,'admin_page');
			$all['content'] = $tmp->display('admin_page');
			$all['title'] = 'Изменение статической страницы';
		break;
		
		case 'deletestatic': 
			$tmp->theme('admin_page.tpl','admin_index',true);
			$id = (isset($_GET['id']))?$_GET['id']:'';
			$delete = (isset($_GET['delete']))?$_GET['delete']:'';
			if($id != '') {
				if($db->num_rows($db->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `id`=\''.$id.'\'')) > 0) {
					if($delete == 'yes') {
						$db->query('DELETE FROM `'.DB_PREFIX.'_static` WHERE `id`=\''.$id.'\'');
						$tmp->assign('{message}',str_replace('{id}',$id,MSG('PAGE_WERE_DELETED')),'admin_index');
					} else $tmp->assign('{message}',str_replace(array('{id}','{accept-link}'),array($id,'/admin/static/delete/'.$id.'/yes'),MSG('TRUE_DELETE_PAGE')),'admin_index');
				} else $tmp->assign('{message}',str_replace('{id}',$id,MSG('INVALID_PAGE_ID')),'admin_index');
			} else $tmp->assign('{message}',MSG('dp_4'),'admin_index');
			$all['content'] = $tmp->display('admin_index');
			$tmp->clear('admin_index');
			$all['title'] = 'Удаление статической страницы';
		break;
		
		case 'static':
			$tmp->theme('static.tpl','admin_page',true);
			ob_start();
				$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_static` ORDER BY `id`');
				if(@$db->num_rows($query) != 0) {
					while($myrow = $db->fetch_array($query)) {
						$tmp->theme('static-item.tpl','static_items',true);
						$tmp->assign('{title}',$myrow['title'],'static_items');
						$tmp->assign('{url}',$myrow['url'],'static_items');
						$tmp->assign('{edit-link}','/admin/static/edit/'.$myrow['id'],'static_items');
						$tmp->assign('{delete-link}','/admin/static/delete/'.$myrow['id'],'static_items');
						$tmp->assign('{item-link}','/do/'.$myrow['url'],'static_items');
						echo $tmp->display('static_items');
					}
				} else echo MSG('NO_PAGES');
			$tmp->assign('{static-items}',ob_get_clean(),'admin_page');
			$all['content'] = $tmp->display('admin_page');
			$all['title'] = 'Управление статическими страницами';
		break;
		
		case 'addstatic':
			$tmp->theme('addstatic.tpl','admin_page',true);
			if(isset($_POST['addstatic'])) {
				if(empty($_POST['title']) || empty($_POST['content']) || empty($_POST['url'])) $e = MSG('ALL_FIELDS_REQUIRED');
				elseif($db->num_rows($db->query('SELECT * FROM `'.DB_PREFIX.'_static` WHERE `url`=\''.$_POST['url'].'\'')) == 0) {
					$db->query('INSERT INTO `'.DB_PREFIX.'_static` (`title`, `content`, `url`) VALUES (\''.$_POST['title'].'\', \''.addslashes($_POST['content']).'\', \''.$_POST['url'].'\')');
					$e = MSG('PAGE_WERE_ADDED');
				} else $e = str_replace('{url}',$_POST['url'],MSG('PAGE_ALREADY_EXISTS'));
			}
			if(isset($e)) $tmp->assign(array('[message]','[/message]'),'','admin_page');
			else $tmp->preg_assign('~\[message\](.*?)\[/message\]~is','','admin_page');
			$tmp->assign('{title}',(isset($_POST['title']))?$_POST['title']:'','admin_page');
			$tmp->assign('{content}',(isset($_POST['content']))?$_POST['content']:'','admin_page');
			$tmp->assign('{url}',(isset($_POST['url']))?$_POST['url']:'','admin_page');
			$tmp->assign('{message}',$e,'admin_page');
			$all['content'] = $tmp->display('admin_page');
			$all['title'] = 'Добавление статических страниц';
		break;
		
		case 'deletecomments':
			$tmp->theme('admin_page.tpl','admin_index',true);
			$id = (isset($_GET['id']))?$_GET['id']:'';
			$delete = (isset($_GET['delete']))?$_GET['delete']:'';
			if($id != '') {
				if($db->num_rows($db->query('SELECT * FROM `'.DB_PREFIX.'_comments` WHERE `id`=\''.$id.'\'')) > 0) {
					if($delete == 'yes') {
						$db->query('DELETE FROM `'.DB_PREFIX.'_comments` WHERE `id`=\''.$id.'\'');
						$tmp->assign('{message}',str_replace('{id}',$id,MSG('dn_1')),'admin_index');
					} else $tmp->assign('{message}',str_replace(array('{id}','{accept-link}'),array($id,'/admin/comments/delete/'.$id.'/yes'),MSG('TRUE_DELETE_COMMENT')),'admin_index');
				} else $tmp->assign('{message}',str_replace('{id}',$id,MSG('INVALID_COMMENT_ID')),'admin_index');
			} else $tmp->assign('{message}',MSG('EMPTY_COMMENT_ID'),'admin_index');
			$all['content'] = $tmp->display('admin_index');
			$tmp->clear('admin_index');
			$all['title'] = 'Удаление комментария';
		break;
		
	}
	
	$tmp->theme('admin.tpl','admin_index',true);
	$tmp->assign('{headers}','<meta http-equiv="content-type" content="text/html; charset=windows-1251">'.PHP_EOL.'		<meta name="robots" content="noindex,nofollow">'.PHP_EOL.'		<script type="text/javascript" src="/engine/include/jQuery-1.9.1.min.js"></script>'.PHP_EOL.'		<title>'.$all['title'].' » Ameden Web Engine</title>','admin_index');
	$tmp->assign('{info}',$all['info'],'admin_index');
	$tmp->assign('{title}',$all['title'],'admin_index');
	$tmp->assign('{content}',$all['content'],'admin_index');
	$tmp->assign('{engine}','/engine/include','admin_index');
	$tmp->assign('{username}',$user->get_array('username'),'admin_index');
	$tmp->assign('{admin-link}','/admin','admin_index');
	$tmp->assign('{logout-link}','/logout/admin','admin_index');
	echo $tmp->display('admin_index',true);
	
} else {
	
	$tmp->theme('login.tpl','admin_login',true);
	if(isset($_POST['admin_send'])) {
		switch($user->check_auth($functions->strip($_POST['username']),$functions->strip($_POST['password']),true)) {
			case 1: $e = MSG('ALL_FIELDS_REQUIRED'); break;
			case 2: $e = MSG('AUTHORIZATION_ERROR'); break;
			case 3: $e = MSG('ACCOUNT_IS_BANNED'); break;
			case 4: $e = MSG('ACCOUNT_IS_NOT_CONFIRM'); break;
			case 41: $e = MSG('ACCESS_DENIED'); break;
			case 5:
				$query = $db->fetch_array($db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$functions->strip($_POST['username']).'\' AND `password`=\''.$functions->strip($functions->crypt($_POST['password'])).'\''));
				if(isset($_POST['remember'])) {
					$token = md5(time().$functions->strip($_POST['username']));
					setcookie('token',$token,time()+2592000,'/',$_SERVER['HTTP_HOST']);
					$db->query('UPDATE `'.DB_PREFIX.'_users` SET `token`=\''.$token.'\' WHERE `username`=\''.$functions->strip($_POST['username']).'\'');
				}
				$_SESSION['username'] = $query['username'];
				$functions->spaceTo('/admin');
			break;
		}
	}
	if(isset($e)) $tmp->assign(array('[message]','[/message]'),'','admin_login');
	else $tmp->preg_assign('~\[message\](.*?)\[/message\]~is','','admin_login');
	$tmp->assign('{message}',$e,'admin_login');
	$tmp->assign('{title}','Авторизация','admin_login');
	$tmp->assign('{engine}','/engine/include','admin_login');
	echo $tmp->display('admin_login',true);
	
}

$db->close();
?>