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

class admin extends functions {
	
	public function strip($string) {
		return addslashes($string);
	}
	
	public function updateBoolean($settingName) {
		global $database;
		if($_POST[$settingName] == 'Да') {
			$bool = 'true';
		} else { $bool = 'false'; }
		$database->query('UPDATE `'.DB_PREFIX.'_config` SET `value`=\''.$bool.'\' WHERE `setting`=\''.$settingName.'\'');
		$bool = null;
	}
	
	public function getSelectBox($paramName) {
		global $database;
		ob_start();
			if($paramName == 'theme') {
				echo '<select id="cs-theme-box" name="theme">'.PHP_EOL;
				if(is_dir(ROOT.'/themes/')) {
					$files = scandir(ROOT.'/themes/');
					array_shift($files);
					array_shift($files);
					for($i = 0; $i < sizeof($files); $i++) {
						if($files[$i] != '.htaccess') {
							if($database->getParam('theme') == $files[$i]) {
								$selected[$i] = 'selected ';
								$active[$i] = 'active-';
							}
							echo '<option id="cs-theme-'.$active[$i].'option" '.$selected[$i].'value="'.$files[$i].'">'.$files[$i].'</option>'.PHP_EOL;
						}
					}
				}
				echo '</select>'.PHP_EOL;
			} else {
				$selectBox = array('Да', 'Нет');
				echo '<select id="cs-'.$paramName.'-box" name="'.$paramName.'">'.PHP_EOL;
				for($i = 0; $i < sizeof($selectBox); $i++) {
					if($selectBox[$i] == 'Нет') {
						if($database->getParam($paramName) == 'false') {
							echo '<option id="cs-'.$paramName.'-active-option" selected value="'.$selectBox[$i].'">'.$selectBox[$i].'</option>'.PHP_EOL;
						} else { echo '<option id="cs-'.$paramName.'-option" value="'.$selectBox[$i].'">'.$selectBox[$i].'</option>'.PHP_EOL; }
					} else {
						if($database->getParam($paramName) == 'true') {
							echo '<option id="cs-'.$paramName.'-active-option" selected value="'.$selectBox[$i].'">'.$selectBox[$i].'</option>'.PHP_EOL;
						} else { echo '<option id="cs-'.$paramName.'-option" value="'.$selectBox[$i].'">'.$selectBox[$i].'</option>'.PHP_EOL; }
					}
				}
				echo '</select>'.PHP_EOL;
			}
		return ob_get_clean();
	}
	
}

$admin = new admin();
?>