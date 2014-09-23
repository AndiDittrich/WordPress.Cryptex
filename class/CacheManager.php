<?php 
/**
	Cache Path/Url Management
	Version: 1.0
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

class CacheManager{
	
	// local cache path
	private $_cachePath;
	
	// cache url (public accessable)
	private $_cacheUrl;
	
	// internal cache
	private $_internalCachePath;
	
	public function __construct($settingsUtil){
		// default cache
		$this->_cachePath = CRYPTEX_PLUGIN_PATH.'/cache/';
		$this->_internalCachePath = CRYPTEX_PLUGIN_PATH.'/cache/';
		$this->_cacheUrl = plugins_url('/cryptex/cache/');
		
		// use custom cache path/url ?
		if ($settingsUtil->getOption('cache-custom')){
			$cp = $settingsUtil->getOption('cache-path');
				
			// valid cache path ?
			if (is_dir($cp) && is_writable($cp)){
				$this->_cachePath = trailingslashit($cp);
			}
				
			// copy cusstom cache url (no check)
			$this->_cacheUrl = trailingslashit($settingsUtil->getOption('cache-url'));
		}		
	}

	/**
	 * drop cache items
	 */
	public function clearCache(){
		// cache dir
		$this->rmdir($this->_cachePath);
		$this->rmdir($this->_internalCachePath);
	}	

	public function getCachePath(){
		return $this->_cachePath;
	}
	
	public function getCacheUrl(){
		return $this->_cacheUrl;
	}
	
	public function getInternalCachePath(){
		return $this->_internalCachePath;
	}
	
	/**
	 * Remove all files within the given directoy (non recursive)
	 */ 
	private function rmdir($dir){
		// remove cached files
		if (is_dir($dir)){
			$files = scandir($dir);
			foreach ($files as $file){
				if ($file!='.' && $file!='..'){
					unlink($dir.$file);
				}
			}
		}
	}

}


?>