<?php
/**
	Font Utility Class
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://www.a3non.org/go/cryptex
	License: MIT X11-License
	
	Copyright (c) 2013, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Cryptex;

class FontManager{
	// default font pathes, platform dependend
	public static $WindowsFontPaths = array(
		'C:\Windows\Fonts'
	);
	
	// default debian, ubuntu, centos font pathes
	public static $LinuxFontPaths = array(
		'/usr/share/fonts/truetype',
		'/usr/local/share/fonts/truetype',
		'/usr/share/fonts/X11/truetype',
		'/usr/lib/X11/fonts/truetype'				
	);
		
	// local plugin config
	private $_config;
	
	public function __construct($settingsUtil){
		// store local plugin config
		$this->_config = $settingsUtil->getOptions();		
	}
	
	// get formated fontlist
	public function getFontlist(){
		// get raw list
		$list = $this->getSelectedFonts();

		// output list
		$fontlist = array();
		
		foreach ($list as $item){
			// use full names (including paths ?)
			if ($this->_config['show-full-paths']){
				$fontlist[$item] = $item;
			}else{
				$fontlist[basename($item)] = $item;
			}
		}
		
		return $fontlist;
	}
	
	// get list of fonts within selected source
	public function getSelectedFonts(){
		// scaned fontlist
		$list = array();
		
		// which fontsource is selected ?
		switch ($this->_config['font-source']){
			case 'custom':
				$list = $this->getCustomFonts();
				break;
				
			case 'plugin':	
				$list = $this->getPluginFonts();
				break;

			case 'system':
			default:
				$list = $this->getSystemFonts();
				break;
		}
		
		// filtered fontlist
		$fontlist = array();
		
		// check fonts for accessibility and ttf extension
		foreach ($list as $font){
			if (is_readable($font) && self::getExtension($font) == 'ttf'){
				$fontlist[] = $font;
			}
		}
		
		return $fontlist;		
	}
	
	// get fonts of custom dir
	public function getCustomFonts(){
		return self::scandir($this->_config['custom-font-path']);
	}
	
	// get fonts of plugin dir
	public function getPluginFonts(){
		return self::scandir(CRYPTEX_DEFAULT_FONT_PATH);
	}
	
	// get system font list
	public function getSystemFonts(){
		// get available system font pathes
		$systemFontDirs = self::getDefaultFontPaths();
		
		// temp storage
		$rawFontlist = array();
		
		foreach ($systemFontDirs as $dirname){
			$list = self::scandir($dirname);
			
			// push list to output
			$rawFontlist = array_merge($rawFontlist, $list);
		}
		
		return $rawFontlist;
	}
	
	// default system font path
	public static function getDefaultFontPaths(){
		// windows or unix based os ?
		$scandirs = ((strncasecmp(PHP_OS, 'WIN', 3) == 0)? self::$WindowsFontPaths : self::$LinuxFontPaths);
		
		// valid list of dirs
		$dirstack = array();
			
		// check dirs for accessibility
		foreach ($scandirs as $dir){
			try{
				if (is_dir($dir) && is_readable($dir)){
					$dirstack[] = $dir;
				}
			}catch (Exception $exc){}
		}
			
		return $dirstack;
	}
	
	/**
	 *	Get file extension
	 */
	public static function getExtension($filename){
		return strtolower(substr(strrchr($filename, '.'), 1));
	}
	
	/**
	 * Recursive-Directory-Scan - Iterative Version
	 * @author Andi Dittrich
	 * @license MIT X11-License
	 * @param String $dirname
	 * @return array
	 */
	public static function scandir($dirname){
		// remove trailing slash
		$dirname = untrailingslashit($dirname);
		
		// accessibility check
		if (strlen($dirname) == 0 || !\is_dir($dirname) || !\is_readable($dirname)){
			return array();
		}
		
		// stack of scanning directories
		$dirStack = array($dirname);
	
		// output file list
		$fileList = array();
	
		// iterative walk through
		while (count($dirStack)>0){
			// get current dir (first element of stack)
			$currentDir = \array_shift($dirStack);
	
			// get file list of current dir
			$currentFiles = \scandir($currentDir);
	
			// iterate over current files
			foreach ($currentFiles as $file){
				if ($file != '.' && $file != '..'){
					// check if current file is a directory
					if (\is_dir($currentDir.DIRECTORY_SEPARATOR.$file)){
						// push it on stack
						$dirStack[] = $currentDir.DIRECTORY_SEPARATOR.$file;
					}else{
						// store it into output file list
						$fileList[] = $currentDir.DIRECTORY_SEPARATOR.$file;
					}
				}
			}
		}
	
		// return filelist
		return $fileList;
	}
}

?>