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

if(!defined('CAPTCHA')) die('Scat!');

class captcha {
	
	public $keystring = '';
	
	public function create_image() {
		// CONFIG | START //
		$alphabet = '0123456789abcdefghijklmnopqrstuvwxyz';
		$allowed_symbols = '23456789abcdegikpqsvxyz';
		$fontsdir = 'engine/include/captcha';	
		$length = mt_rand(5,7);
		$width = 178;
		$height = 80;
		$fluctuation_amplitude = 8;
		$white_noise_density = 1/6;
		$black_noise_density = 1/30;
		$no_spaces = true;
		$show_credits = true;
		$credits = $_SERVER['HTTP_HOST'];
		$foreground_color = array(mt_rand(0,80), mt_rand(0,80), mt_rand(0,80));
		$background_color = array(mt_rand(220,255), mt_rand(220,255), mt_rand(220,255));
		$jpeg_quality = 100;
		// CONFIG | END //
		
		// IMAGE GENERATION | START //
		$fonts = array();
		$fontsdir_absolute = dirname(dirname(dirname(__FILE__))).'/'.$fontsdir;
		if($handle = opendir($fontsdir_absolute)) {
			while(false !== ($file = readdir($handle))) {
				if(preg_match('/\.png$/i',$file)) {
					$fonts[] = $fontsdir_absolute.'/'.$file;
				}
			}
		    closedir($handle);
		}	
		$alphabet_length = strlen($alphabet);
		do {
			while(true) {
				$this->keystring = '';
				for($i = 0; $i < $length; $i++){
					$this->keystring .= $allowed_symbols{mt_rand(0,strlen($allowed_symbols)-1)};
				}
				if(!preg_match('/cp|cb|ck|c6|c9|rn|rm|mm|co|do|cl|db|qp|qb|dp|ww/',$this->keystring)) { break; }
			}
			$font_file = $fonts[mt_rand(0, count($fonts)-1)];
			$font = imagecreatefrompng($font_file);
			imagealphablending($font, true);
			$fontfile_width = imagesx($font);
			$fontfile_height = imagesy($font)-1;
			$font_metrics = array();
			$symbol = 0;
			$reading_symbol = false;
			for($i = 0; $i < $fontfile_width && $symbol < $alphabet_length; $i++){
				$transparent = (imagecolorat($font, $i, 0) >> 24) == 127;
				if(!$reading_symbol && !$transparent) {
					$font_metrics[$alphabet{$symbol}] = array('start' => $i);
					$reading_symbol = true;
					continue;
				}
				if($reading_symbol && $transparent) {
					$font_metrics[$alphabet{$symbol}]['end'] = $i;
					$reading_symbol = false;
					$symbol++;
					continue;
				}
			}
			$img = imagecreatetruecolor($width,$height);
			imagealphablending($img,true);
			$white = imagecolorallocate($img,255,255,255);
			$black = imagecolorallocate($img,0,0,0);
			imagefilledrectangle($img,0,0,$width-1,$height-1,$white);
			$x = 1;
			$odd = mt_rand(0,1);
			if($odd == 0) { $odd = -1; }
			for($i = 0; $i < $length; $i++) {
				$m = $font_metrics[$this->keystring{$i}];
				$y = (($i%2)*$fluctuation_amplitude - $fluctuation_amplitude/2)*$odd+mt_rand(-round($fluctuation_amplitude/3),round($fluctuation_amplitude/3))+($height-$fontfile_height)/2;
				if($no_spaces) {
					$shift = 0;
					if($i > 0){
						$shift = 10000;
						for($sy = 3; $sy < $fontfile_height-10; $sy += 1){
							for($sx = $m['start']-1; $sx < $m['end']; $sx += 1) {
				        		$rgb = imagecolorat($font,$sx,$sy);
				        		$opacity = $rgb>>24;
								if($opacity < 127){
									$left = $sx-$m['start']+$x;
									$py = $sy+$y;
									if($py > $height) { break; }
									for($px = min($left,$width-1); $px > $left-200 && $px >= 0; $px -= 1){
						        		$color = imagecolorat($img, $px, $py) & 0xff;
										if($color+$opacity < 170) {
											if($shift > $left-$px) {
												$shift = $left-$px;
											}
											break;
										}
									}
									break;
								}
							}
						}
						if($shift == 10000) {
							$shift = mt_rand(4,6);
						}
					}
				} else {
					$shift = 1;
				}
				imagecopy($img,$font,$x-$shift,$y,$m['start'],1,$m['end']-$m['start'],$fontfile_height);
				$x += $m['end']-$m['start']-$shift;
			}
		} while($x >= $width-10);
		$white = imagecolorallocate($font,255,255,255);
		$black = imagecolorallocate($font,0,0,0);
		for($i = 0; $i < (($height-30)*$x)*$white_noise_density;$i++) {
			imagesetpixel($img,mt_rand(0,$x-1),mt_rand(10,$height-15),$white);
		}
		for($i = 0; $i < (($height-30)*$x)*$black_noise_density; $i++) {
			imagesetpixel($img,mt_rand(0,$x-1),mt_rand(10,$height-15),$black);
		}
		$center = $x/2;
		$img2 = imagecreatetruecolor($width,$height+($show_credits?12:0));
		$foreground = imagecolorallocate($img2,$foreground_color[0],$foreground_color[1],$foreground_color[2]);
		$background = imagecolorallocate($img2,$background_color[0],$background_color[1],$background_color[2]);
		imagefilledrectangle($img2,0,0,$width-1,$height-1,$background);		
		imagefilledrectangle($img2,0,$height,$width-1,$height+12,$foreground);
		$credits = empty($credits)?$_SERVER['HTTP_HOST']:$credits;
		imagestring($img2,2,$width/2-imagefontwidth(2)*strlen($credits)/2,$height-2,$credits,$background);
		$rand1 = mt_rand(750000,1200000)/10000000;
		$rand2 = mt_rand(750000,1200000)/10000000;
		$rand3 = mt_rand(750000,1200000)/10000000;
		$rand4 = mt_rand(750000,1200000)/10000000;
		$rand5 = mt_rand(0,31415926)/10000000;
		$rand6 = mt_rand(0,31415926)/10000000;
		$rand7 = mt_rand(0,31415926)/10000000;
		$rand8 = mt_rand(0,31415926)/10000000;
		$rand9 = mt_rand(330,420)/110;
		$rand10 = mt_rand(330,450)/100;
		for($x = 0; $x < $width; $x++) {
			for($y = 0; $y < $height; $y++) {
				$sx = $x+(sin($x*$rand1+$rand5)+sin($y*$rand3+$rand6))*$rand9-$width/2+$center+1;
				$sy = $y+(sin($x*$rand2+$rand7)+sin($y*$rand4+$rand8))*$rand10;
				if($sx < 0 || $sy<0 || $sx >= $width-1 || $sy >= $height-1) {
					continue;
				} else {
					$color = imagecolorat($img, $sx, $sy) & 0xFF;
					$color_x = imagecolorat($img, $sx+1, $sy) & 0xFF;
					$color_y = imagecolorat($img, $sx, $sy+1) & 0xFF;
					$color_xy = imagecolorat($img, $sx+1, $sy+1) & 0xFF;
				}
				if($color == 255 && $color_x == 255 && $color_y == 255 && $color_xy == 255) {
					continue;
				} elseif($color == 0 && $color_x == 0 && $color_y == 0 && $color_xy == 0) {
					$newred = $foreground_color[0];
					$newgreen = $foreground_color[1];
					$newblue = $foreground_color[2];
				} else {
					$frsx = $sx-floor($sx);
					$frsy = $sy-floor($sy);
					$frsx1 = 1-$frsx;
					$frsy1 = 1-$frsy;
					$newcolor = (
						$color*$frsx1*$frsy1+
						$color_x*$frsx*$frsy1+
						$color_y*$frsx1*$frsy+
						$color_xy*$frsx*$frsy
					);
					if($newcolor > 255) { $newcolor = 255; }
					$newcolor = $newcolor/255;
					$newcolor0 = 1-$newcolor;
					$newred = $newcolor0*$foreground_color[0]+$newcolor*$background_color[0];
					$newgreen = $newcolor0*$foreground_color[1]+$newcolor*$background_color[1];
					$newblue = $newcolor0*$foreground_color[2]+$newcolor*$background_color[2];
				}
				imagesetpixel($img2,$x,$y,imagecolorallocate($img2,$newred,$newgreen,$newblue));
			}
		}
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0',FALSE);
		header('Pragma: no-cache');
		if(function_exists('imagejpeg')) {
			header('Content-Type: image/jpeg');
			imagejpeg($img2,null,$jpeg_quality);
		} elseif(function_exists('imagegif')) {
			header('Content-Type: image/gif');
			imagegif($img2);
		} elseif(function_exists('imagepng')) {
			header('Content-Type: image/x-png');
			imagepng($img2);
		}
		// IMAGE GENERATION | END //
	}
	
	public function getKeyString() {
		return $this->keystring;
	}
	
}

$captcha = new captcha();
?>