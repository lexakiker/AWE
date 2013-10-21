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

include(ROOT.'engine/classes/registration.class.php');

class user extends functions {
	
	public $registration = '';
	public $rand_pass = '';
	
	public function __construct() {
		$this->registration = new registration();
	}
	
	public function invitedBy($by_id) {
		if(!$this->checkLogged()) {
			if(empty($_COOKIE['referal'])) {
				global $database;
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `id`=\''.$by_id.'\'');
				if($database->num_rows($query) == 1) {
					$resource = $database->fetch_array($query);
					setcookie('referal', $resource['username'], time()+2592000, '/', $_SERVER['HTTP_HOST']);
				} else { return false; }
			}
		} else { return false; }
	}
	
	public function userAdmin($group = '') {
		if($this->checkLogged()) {
			global $database;
			if($group == '') { $group = $database->getParam('admin-group'); }
			if(strpos(', ', $group)) {
				$array = explode(', ', $group);
			} else { $array = array($group); }
			if(in_array($this->getArray('group'), $array)) {
				return true;
			} else { return false; }
		} else { return false; }
	}
	
	public function update() {
		global $database, $functions;
		if($this->checkLogged()) { $database->query('UPDATE `'.DB_PREFIX.'_users` SET `last-date`=\''.$functions->curDate().'\', `ip`=\''.$_SERVER['REMOTE_ADDR'].'\' WHERE `username`=\''.$this->getArray('username').'\''); }
		if($this->checkCookie(true)) {
			$token = $this->strip($_COOKIE['token']);
			$query = $database->query('SELECT `username` FROM `'.DB_PREFIX.'_users` WHERE `token`=\''.$token.'\'');
			if($database->num_rows($query) < 1) {
				setcookie('token', null, time()-2592000, '/', $_SERVER['HTTP_HOST']);
			} else { $_SESSION['username'] = $database->result($query);  }
		}
	}
	
	public function getArray($string, $username = null) {
		global $database, $functions;
		if(isset($username)) {
			$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$username.'\'');
			$resource = $database->fetch_array($query);
			return $resource[$string];
		} else {
			if($this->checkLogged()) {
				$username = $functions->strip($_SESSION['username']);
				$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$username.'\'');
				$resource = $database->fetch_array($query);
				return $resource[$string];
			} else { return false; }
		}
	}
	
	public function getPermission($section) {
		if($this->checkLogged()) {
			global $database;
			$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$this->getArray('username').'\'');
			$resource = $database->fetch_array($query);
			$permissions = explode(';', $resource['permissions']);
			if($section == 'allow-admin') {
				return $permissions[0];
			} elseif($section == 'allow-offline') {
				return $permissions[1];
			}
		} else { return false; }
	}
	
	public function getGroup($username) {
		$do = true;
		if($this->getArray('checked', $username) == '0') {
			return LANG('GROUP_NOT_ACTIVATED');
			$do = false;
		}
		if($do) {
			switch($this->getArray('group', $username)) {
				case '0': return LANG('GROUP_0'); break;
				case '1': return LANG('GROUP_1'); break;
				case '2': return LANG('GROUP_2'); break;
				case '3': return LANG('GROUP_3'); break;
				case '4': return LANG('GROUP_4'); break;
				case '5': return LANG('GROUP_5'); break;
				case '6': return LANG('GROUP_6'); break;
			}
		}
	}
	
	public function checkLogged() {
		$return = false;
		if(isset($_SESSION['username'])) { $return = true; }
		return $return;
	}
	
	public function checkCookie($withSession = false) {
		$return = false;
		if($withSession) {
			if(isset($_COOKIE['token']) && empty($_SESSION['username'])) { $return = true; }
		} else {
			if(isset($_COOKIE['token'])) { $return = true; }
		}
		return $return;
	}
	
	public function checkData($name, $mail) {
		if(empty($name) || empty($mail)) {
			return 1;
		} elseif(strlen($name) > 15) {
			return 11;
		} elseif(strlen($mail) > 24 || !preg_match("/^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$/", $mail)) {
			return 2;
		} else { return 3; }
	}
	
	public function checkComment($comment) {
		global $database;
		if(empty($comment)) {
			return 1;
		} elseif(strlen($comment) > $database->getParam('comment-max-sym')) {
			return 2;
		} else { return 3; }
	}
	
	public function checkForgot_password($username, $mail, $keystring) {
		global $database;
		$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$username.'\' AND `mail`=\''.$mail.'\'');
		$resource = $database->fetch_array($query);
		if(empty($username) || empty($mail) || empty($keystring)) {
			return 1;
		} elseif(empty($resource['id']) || $resource['checked'] == '0' || $resource['group'] == '0') {
			return 2;
		} elseif($keystring || $keystring == '') {
			if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $keystring) {
				$this->rand_pass = $this->generatePassword('8');
				return 3;
			} else { return 4; }
			unset($_SESSION['captcha_keystring']);
		}
	}
	
	public function checkAuth($username, $password, $cpMode = false) {
		global $database;
		$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$username.'\' AND `password`=\''.$this->crypt($password).'\'');
		$resource = $database->fetch_array($query);
		$permissions = explode(';',$resource['permissions']);
		if(empty($username) || empty($password)) {
			return 1;
		} elseif(empty($resource['id'])) {
			return 2;
		} elseif($resource['group'] == '0') {
			return 3;
		} elseif($resource['checked'] == '0') {
			return 4;
		} elseif($cpMode) {
			if($this->userAdmin($resource['group']) || $permissions[0] == '1') {
				return 5;
			} else { return 41; }
		} else { return 5; }
	}
	
	public function checkChange_password($old_password, $new_password, $re_new_password) {
		global $database;
		$query = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `password`=\''.$this->crypt($old_password).'\'');
		$resource = $database->fetch_array($query);
		if(empty($old_password) || empty($new_password) || empty($re_new_password)) {
			return 1;
		} elseif(empty($resource['id'])) {
			return 2;
		} elseif(!preg_match("/\A(\w){5,20}\Z/", $new_password)) {
			return 3;
		} elseif($new_password != $re_new_password) {
			return 4;
		} else { return 5; }
	}
	
	public function checkFeed_back($name, $mail, $subject, $message, $keystring) {
		if(empty($name) || empty($mail) || empty($subject) || empty($message) || empty($keystring)) {
			return 1;
		} elseif($keystring || $keystring == '') {
			if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $keystring) {
				return 2;
			} else { return 3; }
			unset($_SESSION['captcha_keystring']);
		}
	}
	
	public function checkRegister($name, $username, $password, $repassword, $mail, $keystring) {
		global $database;
		if(empty($name)) {
			return 1;
		} elseif(strlen($name) > 15) {
			return 11;
		} elseif(strlen($username) < 3 || strlen($username) > 15 || !preg_match("/^[a-zA-Z0-9]+$/", $username)) {
			return 2;
		} elseif(strlen($mail) > 24 || !preg_match("/^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$/", $mail)) {
			return 3;
		} elseif(strlen($password) < 6 || strlen($password) > 20 || !preg_match("/^[a-zA-Z0-9]+$/", $password)) {
			return 4;
		} elseif($password != $repassword) {
			return 5;
		} elseif($keystring || $keystring == '') {
			if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $keystring) {
				$sql = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$username.'\'');
				if($database->num_rows($sql) > 0) {
					return 6;
				} else {
					$sql = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `mail`=\''.$mail.'\'');
					if($database->num_rows($sql) > 0) {
						return 7;
					} else {
						$sql = $database->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `reg-ip`=\''.$_SERVER['REMOTE_ADDR'].'\'');
						if($database->getParam('reg-one-ip') == 'true') {
							if($database->num_rows($sql) > 0) {
								return 8;
							} else { return 9; }
						} else { return 9; }
					}
				}
			} else { return 10; }
			unset($_SESSION['captcha_keystring']);
		}
	}
	
	public function logout() {
		if($this->checkLogged()) { unset($_SESSION['username']); }
		if($this->checkCookie()) { setcookie('token', null, time()-2592000, '/', $_SERVER['HTTP_HOST']); }
	}
	
}

$user = new user();
?>