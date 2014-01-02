<?php
/**
	Generic Plugin Updater - Backup/Restore Files. Wordpress will overwrite the COMPLETE plugin folder on update!
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
namespace Cryptex;

class Updater{
		// folders to restore
		private $_restoreFolders;
		private $_prefix = '';
		
		public function __construct($prefix, $restoreFolders=array()){
			// store informations
			$this->_restoreFolders = $restoreFolders;
			$this->_prefix = $prefix;
			
			// update/install events - well they are called on upgrading ANY plugin..but at this moment there is no better way..
			add_action('upgrader_pre_install', array($this, 'updateBackup'), 10, 0);
			add_action('upgrader_post_install', array($this, 'updateRestore'), 10, 0);
		}
		
		public function register($dir){
			$this->_restoreFolders[] = $dir;
		}
		
		public function updateBackup(){
			foreach ($this->_restoreFolders as $folder){
				// move files outside the plugin direcotry
				rename($folder, WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.$this->_prefix.'_backup_'.sha1($folder));
			}
		}
		
		public function updateRestore(){
			foreach ($this->_restoreFolders as $folder){
				// delete the NEW folder first -> problem on windows systems...
				if (is_dir($folder)){
					rmdir($folder);
				}
			
				// move folder back
				rename(WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.$this->_prefix.'_backup_'.sha1($folder), $folder);
			}
		}
}

?>