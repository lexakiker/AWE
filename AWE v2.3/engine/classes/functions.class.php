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

class functions {
	
	public function mistake($input,$diemode = true) {
		if($diemode) die($input);
		else return $input;
	}
	
	public function strip($input,$comment = false) {
		if($comment) {
			$input = str_replace("\r\n",'[br]',$input);
			$input = mysql_real_escape_string(htmlspecialchars($input,ENT_QUOTES));
			$input = str_replace('[br]','<br>',$input);
			return $input;
		} else return mysql_real_escape_string(htmlspecialchars(strip_tags($input),ENT_QUOTES));
	}
	
	public function generate_password($lenght) {
		$password = '';
		$full_array = array_merge(range('a','z'),range('A','Z'),range('1','9'));
		for($i = '0'; $i < $lenght; $i++){
			$entrie = array_rand($full_array);
			$password .= $full_array[$entrie];
		}
		return $password;
	}
	
	public function refresh_page() {
		echo '<script type="text/javascript">location.reload()</script>';
	}
	
	public function spaceTo($url) {
		Header('Location: '.$url);
	}
	
	public function crypt($input) {
		return md5(md5('~'.$input.'~'));
	}
	
}

$functions = new functions();
?>