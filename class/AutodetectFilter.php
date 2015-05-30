<?php
/**
	 EMail Address Autodetect Filter
	 Version: 1.1
	 Author: Andi Dittrich
	 Author URI: http://andidittrich.de
	 Plugin URI: http://andidittrich.de/go/cryptex
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
	
	// post/page IDs to exclude from filtering
	private $_excludeIDs = array();
	
	// the email address detection pattern
    private $_detectionPattern = '/\b([a-z0-9_\.+-]+@[\da-z\.-]+\.[a-z]{2,23})\b/i';


	public function __construct($settingsUtil, $shorcodeHandler){
		// get IDs to exclude
		$eID = $settingsUtil->getOption('email-autodetect-excludeid');
		
		// filter non numeric chars
		$eID = preg_replace('/[^0-9,]/', '', $eID);
				
		// convert it into array (split by "," speraator)
		$this->_excludeIDs = explode(',', $eID); 
		
		// store shortcode handler instance
		$this->_shortcodeHandler = $shorcodeHandler;
	}
	
	// wp the_content/the_excerpt callback
	public function filter($content){
		// exclude post/page from filtering ?
		if (in_array(get_the_ID(), $this->_excludeIDs)){
			return $content;
		}else{
			// regex to detect emails
			return preg_replace_callback($this->_detectionPattern, array($this, 'filterMatchCallback'), $content);
		}
	}
	
	// wp comment callback (no id exclusion!)
	public function filterNoExclusion($content){
		// regex to detect emails
		return preg_replace_callback($this->_detectionPattern, array($this, 'filterMatchCallback'), $content);
	}
	
	// regex callback
	public function filterMatchCallback($matches){
		// render email by shortcode handler
		return $this->_shortcodeHandler->cryptex(null, $matches[0], '');
	}	
}