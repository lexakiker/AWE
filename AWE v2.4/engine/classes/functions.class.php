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

if(!defined('INC_CHECK')) die('Scat!');

class functions {
	
	public function initialize() {
		global $error, $action, $section, $all;
		
		$error = '';
		$all = array('p-login' => '', 'p-suffix' => '', 'p-title' => '', 'p-info' => '', 'p-content' => '', 'p-robots' => 'index,follow');
		$action = isset($_GET['action'])?$_GET['action']:'';
		$section = isset($_GET['section'])?$_GET['section']:'';
		
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', true);
		ini_set('html_errors', true);
	}
	
	public function mistake($string, $dieMode = true) {
		if($dieMode) {
			echo '<style type="text/css">body { padding: 20px; background: #F7F7F8; margin: 0; font-family: Verdana; font-size: 12px; font-weight: normal; font-style: normal; line-height: 20px; color: #333333; }</style>'.PHP_EOL;
			die($string);
		} else {
			return $string;
		}
	}
	
	public function strip($string,$textMode = false) {
		if($textMode) {
			return str_replace('[br]', '<br>', mysql_real_escape_string(htmlspecialchars(str_replace(PHP_EOL, '[br]', $string), ENT_QUOTES)));
		} else {
			return mysql_real_escape_string(htmlspecialchars(strip_tags(str_replace(' ', '', $string)), ENT_QUOTES));
		}
	}
	
	function formatFileSize($data) {
		if($data < 1024) {
			return $data.' Bytes';
		} elseif($data < 1024000) {
			return round(($data / 1024 ), 1).'KB';
		} else {
			return round(($data / 1024000), 1).'MB';
		}
	}
	
	public function generateToken($username) {
		return md5(time().$username);
	}
	
	public function generatePassword($lenght) {
		$password = '';
		$full_array = array_merge(range('a', 'z'), range('A', 'Z'), range('1', '9'));
		for($i = '0'; $i < $lenght; $i++){
			$entrie = array_rand($full_array);
			$password .= $full_array[$entrie];
		}
		return $password;
	}
	
	public function available($data) {
		global $action;
		switch($data[1]) {
			case 'full-news':       if($action == 'news')            { return $data[2]; }   break;
			case 'view-user':       if($action == 'view-user')       { return $data[2]; }   break;
			case 'statistics':      if($action == 'statistics')      { return $data[2]; }   break;
			case 'registration':    if($action == 'registration')    { return $data[2]; }   break;
			case 'account':         if($action == 'account')         { return $data[2]; }   break;
			case 'static':          if($action == 'static')          { return $data[2]; }   break;
			case 'forgot-password': if($action == 'forgot-password') { return $data[2]; }   break;
			case 'feed-back':       if($action == 'feed-back')       { return $data[2]; }   break;
			case 'activation':      if($action == 'activation')      { return $data[2]; }   break;
			case 'terms':           if($action == 'terms')           { return $data[2]; }   break;
			case 'main':            if($action == '')                { return $data[2]; }   break;
			
			default:                return 'Страницы "<b>'.$data[1].'</b>" не существует!'; break;
		}
	}
	
	public function refreshPage() {
		$this->spaceTo($_SERVER['PHP_SELF']);
	}
	
	public function spaceTo($url) {
		Header('Location: '.$url);
	}
	
	public function curDate() {
		return date('d.m.Y, в H:i');
	}
	
	public function curDay() {
		return date('d');
	}
	
	public function curYear() {
		return date('Y');
	}
	
	public function curMonth() {
		return date('m');
	}
	
	public function crypt($string) {
		return md5(md5('~'.$string.'~'));
	}
	
}

$functions = new functions();
?>