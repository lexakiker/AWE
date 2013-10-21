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

class api extends functions {
	
	protected $allow_api = true; // использовать API
	protected $modules_dir = 'engine/res-modules/'; // директория сторонних модулей
	
	protected $modules_array = array(
		'names' => array(),
		'urls' => array(),
		'errors' => array()
	);
	
	public function load($fileName, $moduleName) {
		if($this->allow_api) {
			$path = $this->modules_dir.$fileName;
			if(file_exists(ROOT.$path)) {
				$this->modules_array['names'][$moduleName] = 'LOADED';
				$this->modules_array['urls'][$moduleName] = ROOT.$this->modules_dir.$fileName;
			} else {
				echo $this->mistake('Ошибка! Файл модуля "<b>/'.$path.'</b> не найден!',false);
				$this->modules_array['errors'][$moduleName] = 'NO';
			}
		}
	}
	
	public function assign($action, $search, $replace, $i) {
		if($this->allow_api) {
			switch($action) {
				case 'str'; return str_replace($search, $replace, $i); break;
				case 'preg'; return preg_replace($search, $replace, $i); break;
				case 'callback'; return preg_replace_callback($search, $replace, $i); break;
			}
		}
	}
	
	public function checkModule($moduleData) {
		if($this->allow_api) {
			if(empty($this->modules_array['names'][$moduleData[1]])) {
				if(empty($this->modules_array['errors'][$moduleData[1]])) {
					return $this->mistake('Ошибка! Модуль "<b>'.$moduleData[1].'</b> не найден!', false);
				}
			} else {
				global $database, $templates, $mail, $user;
				$data = $moduleData[2];
				$moduleName = $moduleData[1];
				require($this->modules_array['urls'][$moduleData[1]]);
				return $data;
			}
		}
	}
	
}

$api = new api();
?>