<?php
/**
	Simple KeyShifting based Encoder Class
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


class Cryptex_KeyShiftingEncoder{
	
	/**
	 * Generate dynamic cryptey key
	 * @return String key
	 */
	public static function generateKey(){
		$key = '';
		// [0-9a-z]
		for ($i=0;$i<32;$i++){
			$key .= chr(rand(48, 90));
		}
		return $key;
	}

	/**
	 * Generate encrypted data using simple keyshiting algorithm
	 * @param String $txt Message to encrypt
	 * @param String $ckey Key
	 * @return String encrypted message
	 */	
	public static function encode($txt, $ckey){
		// expand key on same length
		$key = str_repeat($ckey, strlen($txt)/strlen($ckey) + 1);
		
		// split strings
		$data = str_split($txt);
		$shifts = str_split($key);
		
		// output;
		$output = array();
		
		// shift
		for ($i=0;$i<count($data);$i++){
			$a = ord($data[$i]);
			$b = ord($shifts[$i]);
			$c = 49;
			
			// odd-even switch
			if ($i%2==0){
				$a += $b;
				
				// prevent overflow
				if ($a>255){
					$a -= 255;
					$c = 48;	
				}
			}else{
				$a -= $b;
				
				// prevent underflow
				if ($a<0){
					$a = -$a;
					$c = 48;	
				}
			}
			
			$output[] = $a;
			$output[] = $c;
		}
		
		// convert array to hex values
		$hex = '';	
		foreach ($output as $el){
			$hex .= str_pad (dechex($el), 2  ,'0', STR_PAD_LEFT);	
		}
		
		// return encrypted data
		return $hex;
	}
}






?>