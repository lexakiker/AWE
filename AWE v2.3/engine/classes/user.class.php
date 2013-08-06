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

class user extends functions {
	
	public $random_password = '';
	
	public function get_array($input) {
		global $db;
		if($this->check_logged()) {
			$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$this->strip($_SESSION['username']).'\'');
			$resource = $db->fetch_array($query);
			return $resource[$input];
		} else return '';
	}
	
	public function get_permission($input) {
		global $db;
		if($this->check_logged()) {
			$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$_SESSION['username'].'\'');
			$resource = $db->fetch_array($query);
			$permissions = explode(';',$resource['permissions']);
			if($input == 'allow_admin') return $permissions[0];
			elseif($input == 'allow_offline') return $permissions[1];
		} else return '';
	}
	
	public function update() {
		global $db;
		if($this->check_logged()) $db->query('UPDATE `'.DB_PREFIX.'_users` SET `lastdate`=\''.date('d.m.Y, в H:i').'\', `ip`=\''.USER_IP.'\' WHERE `username`=\''.$_SESSION['username'].'\'');
		if($this->check_cookie() && empty($_SESSION['username'])) {
			$token = $this->strip($_COOKIE['token']);
			$query = $db->query('SELECT `username` FROM `'.DB_PREFIX.'_users` WHERE `token`=\''.$token.'\'');
			if($db->num_rows($query) < 1) setcookie('token','',time()-2592000,'/',$_SERVER['HTTP_HOST']);
			else $_SESSION['username'] = $db->result($query);
		}
	}
	
	public function get_group($username) {
		global $db;
		$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$username.'\'');
		$resource = $db->fetch_array($query);
		switch($resource['group']) {
			case 0: return MSG('GROUP_0'); break;
			case 1: return MSG('GROUP_1'); break;
			case 2: return MSG('GROUP_2'); break;
			case 3: return MSG('GROUP_3'); break;
			case 4: return MSG('GROUP_4'); break;
			case 5: return MSG('GROUP_5'); break;
			case 6: return MSG('GROUP_6'); break;
		}
	}
	
	public function check_logged() {
		if(isset($_SESSION['username'])) return true;
		else return false;
	}
	
	public function check_cookie() {
		if(isset($_COOKIE['token'])) return true;
		else return false;
	}
	
	public function check_data($name,$mail) {
		if(empty($name) || empty($mail)) return 1;
		elseif(!preg_match("/^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$/",$mail)) return 2;
		else return 3;
	}
	
	public function check_comment($comment) {
		if(empty($comment)) return 1;
		else return 2;
	}
	
	public function check_forgotpass($username,$mail,$keystring) {
		global $db;
		$randompass = $this->generate_password('8');
		$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$username.'\' AND `mail`=\''.$mail.'\'');
		$resource = $db->fetch_array($query);
		if(empty($username) || empty($mail) || empty($keystring)) return 1;
		elseif(empty($resource['id']) || $resource['checked'] == '0') return 2;
		elseif($keystring || $keystring == '') {
			if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $keystring) {
				$this->random_password = $randompass;
				return 3;
			} else return 4;
			unset($_SESSION['captcha_keystring']);
		}
	}
	
	public function check_auth($username,$password,$cp = false) {
		global $db;
		$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$username.'\' AND `password`=\''.$this->crypt($password).'\'');
		$resource = $db->fetch_array($query);
		$permissions = explode(';',$resource['permissions']);
		if(empty($username) || empty($password)) return 1;
		elseif(empty($resource['id'])) return 2;
		elseif($resource['group'] == '0') return 3;
		elseif($resource['checked'] == '0') return 4;
		elseif($cp) { if($resource['group'] == $db->getParam('admin_group') || $permissions[0] == 1) return 5; else return 41; }
		else return 5;
	}
	
	public function check_newpassword($oldpassword,$newpassword,$renewpassword) {
		global $db;
		$query = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `password`=\''.$this->crypt($oldpassword).'\'');
		$resource = $db->fetch_array($query);
		if(empty($oldpassword) || empty($newpassword) || empty($renewpassword)) return 1;
		elseif(empty($resource['id'])) return 2;
		elseif(!preg_match("/\A(\w){5,20}\Z/",$newpassword)) return 3;
		elseif($newpassword != $renewpassword) return 4;
		else return 5;
	}
	
	public function check_feedback($name,$mail,$subject,$message,$keystring) {
		if(empty($name) || empty($mail) || empty($subject) || empty($message) || empty($keystring)) return 1;
		elseif($keystring || $keystring == '') {
			if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $keystring) return 2;
			else return 3;
			unset($_SESSION['captcha_keystring']);
		}
	}
	
	public function check_register($name,$username,$password,$repassword,$mail,$keystring) {
		global $db;
		if(empty($name)) return 1;
		elseif(!preg_match("/^\w{3,}$/",$username)) return 2;
		elseif(!preg_match("/^[a-zA-Z0-9_\.\-]+@([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$/",$mail)) return 3;
		elseif(!preg_match("/\A(\w){5,20}\Z/",$password)) return 4;
		elseif($password != $repassword) return 5;
		elseif($keystring || $keystring == '') {
			if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $keystring) {
				$sql = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `username`=\''.$username.'\'');
				if($db->num_rows($sql) > 0) return 6; else {
					$sql = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `mail`=\''.$mail.'\'');
					if($db->num_rows($sql) > 0) return 7; else {
						$sql = $db->query('SELECT * FROM `'.DB_PREFIX.'_users` WHERE `regip`=\''.USER_IP.'\'');
						if($db->getParam('write_user_passwords') == 'true') {
							if($db->getParam('reg_mail_accept') == 'true') {
								if($db->getParam('reg_one_ip') == 'true') {
									if($db->num_rows($sql) > 0) return 8;
									else return 921;
								} else return 921;
							} else {
								if($db->getParam('reg_one_ip') == 'true') {
									if($db->num_rows($sql) > 0) return 8;
									else return 911;
								} else return 911;
							}
						} else {
							if($db->getParam('reg_mail_accept') == 'true') {
								if($db->getParam('reg_one_ip') == 'true') {
									if($db->num_rows($sql) > 0) return 8;
									else return 920;
								} else return 920;
							} else {
								if($db->getParam('reg_one_ip') == 'true') {
									if($db->num_rows($sql) > 0) return 8;
									else return 910;
								} else return 910;
							}
						}
					}
				}
			} else return 10;
			unset($_SESSION['captcha_keystring']);
		}
	}
	
	public function logout() {
		if($this->check_logged()) unset($_SESSION['username']);
		if($this->check_cookie()) setcookie('token','',time()-2592000,'/',$_SERVER['HTTP_HOST']);
	}
	
}

$user = new user();
?>