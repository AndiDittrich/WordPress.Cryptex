<?php
/**
	GD lib based Image Generator
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://andidittrich.de/go/cryptex
	License: MIT X11-License
	
	Copyright (c) 2010-2014, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Cryptex;

class ImageGenerator{
	
	// plugin options
	private $_options;
	
	// selected fontsize in px
	private $_fontsize;
	
	// calculated fontsize (pt)
	private $_ptFontsize;
	
	// fontcolor - decimal value!
	private $_fontcolor;
	
	// selected ttf font (full path)
	private $_fontfile;
	
	// current used salt
	private $_salt;
	
	// image offsets
	// width offset
	private $_offsetX = 0;
	
	// height offset
	private $_offsetY = 0;

	// text position x offset
	private $_offsetA = 0;
	
	// text position y offset
	private $_offsetB = 0;
	
	public function __construct($options){
		$this->_options = $options;
		
		// populate options
		$this->_fontsize = $this->_options['font-size'];
		$this->_fontcolor = hexdec($this->_options['font-color']);
		$this->_salt = $this->_options['salt'];
		$this->_fontfile = $this->_options['font-file'];
		$this->_offsetA = intval($this->_options['offset-a']);
		$this->_offsetB = intval($this->_options['offset-b']);
		$this->_offsetX = intval($this->_options['offset-x']);
		$this->_offsetY = intval($this->_options['offset-y']);
		
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
		
		// if using GD2 -> pt settings, convert pt height in px using lookup table
		if (($ptValue = array_search($this->_fontsize, $PTtoPX)) !== false){
			$this->_ptFontsize = $ptValue;
		}else{
			// fallback
			$this->_ptFontsize = $this->_fontsize;
		}
	}
	
	public function getImage($txt){
		// check for gd lib
		if (!function_exists('gd_info')){
			return null;	
		}
				
		// generate filename
		$filename = sha1($this->_salt.sha1($txt.$this->_salt)).'.png';
		
		// generate storage path
		$storagePath = CRYPTEX_PLUGIN_PATH.'/cache/'.$filename;
		
		// cached version available ?
		if (!file_exists($storagePath)){
			// ttf font file available ?
			if (is_file($this->_fontfile) && is_readable($this->_fontfile)){
				// use ttf based image
				$this->generateTTFImage($txt, $storagePath);
			}else{
				// use gd fallback font
				$this->generateFallbackImage($txt, $storagePath);
			}
		}
				
		// return cache file url
		return plugins_url('/cryptex/cache/').$filename;
	}

	// gd embedded image
	private function generateFallbackImage($txt, $filename){			
		// FALLBACK
		$width = (imagefontwidth(3)*strlen($txt)) +2+$this->_offsetX;
		$height = imagefontheight(3) + 7 + $this->_offsetY;
		
		// create new image
		$im = imagecreatetruecolor($width, $height);
		
		// transparent background
		$color = imagecolorallocatealpha($im, 0, 0, 0, 127);
		imagefill($im, 0, 0, $color);
		imagesavealpha($im, true);
		
		// enable AA
		imageantialias($im, true);
		
		// create text
		imagestring($im, 3, $this->_offsetA, $this->_offsetB, $txt, $this->_fontcolor);
		
		// store image
		imagepng($im, $filename);
		
		// destroy
		imagedestroy($im);
	}
	
	// true type font based image
	private function generateTTFImage($txt, $filename){
		// calculate size
		$boundaries = imagettfbbox($this->_ptFontsize, 0, $this->_fontfile, $txt);
		
		// calculate boundaries
		$min_x = min( array($boundaries[0], $boundaries[2], $boundaries[4], $boundaries[6]) );
		$max_x = max( array($boundaries[0], $boundaries[2], $boundaries[4], $boundaries[6]) );
		$width  = ( $max_x - $min_x );
		// $min_y = min( array($boundaries[1], $boundaries[3], $boundaries[5], $boundaries[7]) );
		// $max_y = max( array($boundaries[1], $boundaries[3], $boundaries[5], $boundaries[7]) );
		// $height = ( $max_y - $min_y );

		// height based on font size ! - this can cause problems using big fonts -> pt<>px drift, but solves problems with font base lines..
		$height = $this->_fontsize;
		
		// dimension offsets
		$width = $width+$this->_offsetX;
		$height = $height+$this->_offsetY;
		
		// create new image
		$im = imagecreatetruecolor($width, $height);
		
		// transparent background
		$color = imagecolorallocatealpha($im, 0, 0, 0, 127);
		imagefill($im, 0, 0, $color);
		imagesavealpha($im, true);
		
		// enable AA
		imageantialias($im, true);
		
		// create text - use calculated pt fontsize value
		// calculate font-baseline includung offset
		imagettftext($im, $this->_ptFontsize, 0, $this->_offsetA, $height-$this->_offsetB, $this->_fontcolor, $this->_fontfile, $txt);
		
		// store image
		imagepng($im, $filename);
		
		// destroy
		imagedestroy($im);
	}
}

?>