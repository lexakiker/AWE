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

$templates->load('statistics.tpl', 'statistics');

$templates->assign('str', '{all-news}', $database->countFrom('news'), 'statistics');
$templates->assign('str', '{all-comments}', $database->countFrom('comments'), 'statistics');
$templates->assign('str', '{all-users}', $database->countFrom('users'), 'statistics');
// $templates->assign('str', '{all-bans}', $database->countFrom('bans'), 'statistics');

$templates->assign('str', '{first-news}', $database->forStatistics('first', 'news'), 'statistics');
$templates->assign('str', '{last-news}', $database->forStatistics('last', 'news'), 'statistics');
$templates->assign('str', '{first-user}', $database->forStatistics('first', 'users'), 'statistics');
$templates->assign('str', '{last-user}', $database->forStatistics('last', 'users'), 'statistics');

$templates->assign('str', '{db-size}', $functions->formatFileSize($database->getDatabaseSize()), 'statistics');

$all['p-content'] = $templates->display('statistics');
$all['p-title'] = 'Статистика сайта';
?>