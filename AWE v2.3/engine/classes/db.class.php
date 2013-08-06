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

class db extends functions {
	
	public function connect($host,$user,$pass,$name) {
		global $link;
		$link = mysql_connect($host,$user,$pass) or $this->mistake('Ошибка соединения с Базой Данных: '.mysql_error());
		mysql_select_db($name,$link) or $this->mistake('Ошибка соединения с Базой Данных: '.mysql_error());
		$this->query('SET `character_set_client`=\'cp1251\'',$link);
		$this->query('SET `character_set_results`=\'cp1251\'',$link);
		$this->query('SET `collation_connection`=\'cp1251_general_ci\'',$link);
		$this->query('SET `time_zone`=\''.date('P').'\'',$link);
	}
	
	public function getParam($input) {
		$query = $this->query('SELECT * FROM `'.DB_PREFIX.'_config` WHERE `setting`=\''.$input.'\'');
		$resource = $this->fetch_array($query);
		if(isset($resource['value'])) return $resource['value'];
		else return '';
	}
	
	public function query($input) { return mysql_query($input); }
	
	public function result($input) { return mysql_result($input,0); }
	
	public function fetch_array($input) { return mysql_fetch_array($input); }
	
	public function num_rows($input) { return mysql_num_rows($input); }
	
	public function fetch_row($input) { return mysql_fetch_row($input); }
	
	public function close() {
		global $link;
		return mysql_close($link);
	}
	
}

$db = new db();
?>