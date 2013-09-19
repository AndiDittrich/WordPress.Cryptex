<?php
/**
	GD lib based Image Generator
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://www.a3non.org/go/cryptex
	License: MIT X11-License
	
	Copyright (c) 2010-2012, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
if (!defined('CRYPTEX_INIT')) die('DIRECT ACCESS PROHIBITED');


class Cryptex_ImageGenerator{
	
	public static function generate($txt){
		// check for gd lib
		if (!function_exists('gd_info')){
			return '';	
		}
		
		// url
		$file = sha1($txt).'.png';
		
		// if file not exists create it
		if (!file_exists(CRYPTEX_PLUGIN_PATH.'/cache/'.$file)){
			$fontsize = intval(get_option('cryptex-font-size', '12'));
			$fontfile = get_option('cryptex-font-path', '').get_option('cryptex-font', '');
			$fontcolor = hexdec(get_option('cryptex-font-color', '0x000000'));
			
			// check for valid font file
			if (is_file($fontfile)){
				// boundarys
				$boundaries = imagettfbbox($fontsize, 0, $fontfile, $txt);
				
				// calculate boundaries
				$min_x = min( array($boundaries[0], $boundaries[2], $boundaries[4], $boundaries[6]) );
				$max_x = max( array($boundaries[0], $boundaries[2], $boundaries[4], $boundaries[6]) );
				$min_y = min( array($boundaries[1], $boundaries[3], $boundaries[5], $boundaries[7]) );
				$max_y = max( array($boundaries[1], $boundaries[3], $boundaries[5], $boundaries[7]) );
				$width  = ( $max_x - $min_x );
				//$height = ( $max_y - $min_y ); 
				
				// pt to px lookup table
				// @source http://reeddesign.co.uk/test/points-pixels.html
				$PTtoPX = array(
					'6' => 8,
					'7' => 9,
					'8' => 11,
					'9' => 12,
					'10' => 13,
					'11' => 15,
					'12' => 16,
					'13' => 17,
					'14' => 19,
					'15' => 21,
					'16' => 22,
					'17' => 23,
					'18' => 24,
					'19' => 25,
					'20' => 26,
					'21' => 28,
					'22' => 29
				);
				
				// height based on font size ! - this can cause problems using big fonts -> pt<>px drift, but solves problems with font base lines..
				$height = $fontsize;
				
				// if using GD2 -> pt settings, convert pt height in px using lookup table
				if (Cryptex_ImageGenerator::getFontSizeFormat()=='pt'){
					if (array_key_exists($fontsize, $PTtoPX)){
						$height = $PTtoPX[$fontsize];	
					}
				}
				
				// create new image
				$im = imagecreatetruecolor($width+2, $height+2);
				
				// transparent background 
				$color = imagecolorallocatealpha($im, 0, 0, 0, 127);
				imagefill($im, 0, 0, $color);
				imagesavealpha($im, true); 
				
				// enable AA
				imageantialias($im, true);
				
				// create text		
				imagettftext($im, $fontsize, 0, 0, $fontsize+1, $fontcolor, $fontfile, $txt);
				
				// store image
				imagepng($im, CRYPTEX_PLUGIN_PATH.'/cache/'.$file);
				
				// destroy
				imagedestroy($im);
			}else{
				// FALLBACK
				$width = imagefontwidth(3)*strlen($txt);
				$height = imagefontheight(3);
				
				// create new image
				$im = imagecreatetruecolor($width+2, $height+7);
				
				// transparent background 
				$color = imagecolorallocatealpha($im, 0, 0, 0, 127);
				imagefill($im, 0, 0, $color);
				imagesavealpha($im, true); 
				
				// enable AA
				imageantialias($im, true);
				
				// create text
				imagestring($im, 3, 0, 0, $txt, $fontcolor);
				
				// store image
				imagepng($im, CRYPTEX_PLUGIN_PATH.'/cache/'.$file);
				
				// destroy
				imagedestroy($im);
			}
		}
		
		// return cache file url
		return plugins_url('/cryptex/cache/').$file;
	}
	
	

	// depending on GD version, px or pt are user for font size
	public static function getFontSizeFormat(){
		if (function_exists('gd_info')) {
			$gdinfo = gd_info();
			preg_match('/\d/', $gdinfo['GD Version'], $match);
	
			// Depending on your version of GD, this should be specified as the pixel size (GD1) or point size (GD2).
			if (version_compare($match[0], '2', '>=')){
				return 'pt';
			}else{
				return 'px';	
			}
		}else{
			return 'px';	
		}
	}

}


?>