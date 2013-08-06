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

class mail extends functions {
	
	private $to;
	private $subject;
	private $message;
	private $headers;
	
	public function setHeaders($data_charset,$send_charset,$to,$subject,$message,$from) {
		$this->subject = $this->mime_header_encode($subject,$data_charset,$send_charset);
		if($data_charset != $send_charset) $this->message = iconv($data_charset,$send_charset,$message);
		$this->to = $to;
		$this->headers = 'From: '.$from."\r\n";
		$this->headers .= 'Content-type: text/html; charset='.$send_charset."\r\n";
		$this->headers .= 'X-Mailer: PHP/'.phpversion();
	}
	
	private function mime_header_encode($str,$data_charset,$send_charset) {
		if($data_charset != $send_charset) $str = iconv($data_charset,$send_charset,$str);
		return '=?'.$send_charset.'?B?'.base64_encode($str).'?=';
	}
	
	public function send() {
		mail($this->to,$this->subject,$this->message,$this->headers);
	}
	
}

$mail = new mail();
?>