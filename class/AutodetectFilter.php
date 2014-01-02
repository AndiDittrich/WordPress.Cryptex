<?php
/**
	 EMail Address Autodetect Filter
	 Version: 1.0
	 Author: Andi Dittrich
	 Author URI: http://andidittrich.de
	 Plugin URI: http://www.a3non.org/go/cryptex
	 License: MIT X11-License
	
	 Copyright (c) 2014, Andi Dittrich
	
	 Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Cryptex;

class AutodetectFilter{
	
	// shortcode handler is used to process the filtered results
	private $_shortcodeHandler;
	
	public function __construct($shorcodeHandler){
		// store shortcode handler instance
		$this->_shortcodeHandler = $shorcodeHandler;
	}
	
	// wp the_content callback
	public function filter($content){
		// regex to detect emails
		return preg_replace_callback('/([a-z0-9_\.-]+@[\da-z\.-]+\.[a-z\.]{2,6}?)/Ui', array($this, 'filterMatchCallback'), $content);
	}
	
	// regex callback
	public function filterMatchCallback($matches){
		// render email by shortcode handler
		return $this->_shortcodeHandler->cryptex(null, $matches[0], '');
	}	
}