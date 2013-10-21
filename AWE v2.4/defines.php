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

define('ENGINE_PATH', ''); // папка с движком, в конце обязательно должен быть слеш | пример: "path/to/site/"

### Все что дальше - не трогать! ###

define('CAPTCHA_LINK', '/'.ENGINE_PATH.'engine/sys-modules/captcha.php?'.session_name().'='.session_id());
define('ROOT', str_replace('\\','/',dirname(__FILE__)).'/');
define('SYS_MODULES_ROOT',ROOT.'engine/sys-modules/');
define('VERSION', '2.4');
?>