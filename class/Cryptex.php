<?php
/**
	Cryptex Class
	Version: 3.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://www.a3non.org/go/cryptex
	License: MIT X11-License
	
	Copyright (c) 2010-2013, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class Cryptex{
	// singleton instance
	private static $__instance;
	
	// static entry
	public static function run(){
		Cryptex::getInstance();
	}
	
	// get singelton instance
	public static function getInstance(){
		// check if singelton instance is avaible
		if (self::$__instance==null){
			// create new instance if not
			self::$__instance = new self();
		}
		return self::$__instance;
	}
	
	
	// cryptex config keys with default values
	private $_defaultConfig = array(
			'custom-font-path' => '',
			'enable-hyperlink' => true,
			'email-divider' => '@',
			'embed-css' => true,
			'embed-js' => true,
			'font-file' => 'Arial.ttf',
			'font-size' => 12,
			'font-color' => '#000000',
			'security-level' => '2',
			'css-prefix' => '',
			'salt' => 'ABCDEF1234567890',
			'css-classes' => '',
			'font-source' => 'system',
			'shortcode-email' => true,
			'show-full-paths' => false,
			'offset-a' => '2',
			'offset-b' => '2',
			'offset-x' => '5',
			'offset-y' => '2',
	);
	
	// shortcode handler instance
	private $_shortcodeHandler;
	
	// resource loader instamce
	private $_resourceLoader;
	
	// settings utility instance
	private $_settingsUtility;
	
	// font manager instance
	private $_fontManager;
	
	public function __construct(){
		// generate session based key
		Cryptex\KeyShiftingEncoder::generateKey();
		
		// create new updater instance - will restore fonts on plugin updates
		$updater = new Cryptex\Updater('cryptex', array(CRYPTEX_DEFAULT_FONT_PATH));
		
		// create new settings utility class
		$this->_settingsUtility = new Cryptex\SettingsUtil('cryptex-', $this->_defaultConfig);
	
		// create new resource loader
		$this->_resourceLoader = new Cryptex\ResourceLoader($this->_settingsUtility);
		
		// create new font manager
		$this->_fontManager = new Cryptex\FontManager($this->_settingsUtility);
		
		// update cache on install
		add_action('activate_plugin', array($this, 'pluginActivate'), 10, 1);
	
		// frontend or admin area ?
		if (is_admin()){
			// i18n domain handler
			add_action('plugins_loaded', array($this, 'initializeI18n'));
				
			// add admin menu handler
			add_action('admin_menu', array($this, 'setupBackend'));
		}else{
			// create new shortcode handler, register all used shortcodes
			$this->_shortcodeHandler = new Cryptex\ShortcodeHandler($this->_settingsUtility, array('cryptex', 'email'));
				
			// add shotcode handlers
			add_shortcode('cryptex', array($this->_shortcodeHandler, 'cryptex'));
			
			// use email shortcode ?
			if ($this->_settingsUtility->getOption('shortcode-email')){
				add_shortcode('email', array($this->_shortcodeHandler, 'cryptex'));
			}
			
			// load frontend css+js
			add_action('wp_enqueue_scripts', array($this->_resourceLoader, 'appendCSS'), 50);
			add_action('wp_enqueue_scripts', array($this->_resourceLoader, 'appendJS'), 50);
				
			// display frontend config (as javascript or metadata)
			add_action('wp_head', array($this->_resourceLoader, 'appendJavascriptConfig'));
		}
	}
	
	public function initializeI18n(){
		// load language files
		load_plugin_textdomain('cryptex', null, CRYPTEX_PLUGIN_PATH.'/lang');
	}
	
	public function setupBackend(){
		// add options page
		$optionsPage = add_options_page(__('Cryptex | E-Mail-Address Protection'), __('Cryptex'), 'administrator', __FILE__, array($this, 'settingsPage'));
	
		// load jquery stuff
		add_action('admin_print_scripts-'.$optionsPage, array($this->_resourceLoader, 'appendAdminJS'));
		add_action('admin_print_styles-'.$optionsPage, array($this->_resourceLoader, 'appendAdminCSS'));
	
		// call register settings function
		add_action('admin_init', array($this->_settingsUtility, 'registerSettings'));
	}
	
	// options page
	public function settingsPage(){
		// well...is there no action hook for updating settings in wp ?
		if (isset($_GET['settings-updated'])){
			// generate new salt - don't have to be a cryptographically secure value
			$this->_settingsUtility->setOption('salt', sha1(mt_rand().mt_rand().time()));
			
			// clear cache
			$this->clearCache();
			
			// recreate css file
			$this->generateCSS();
		}
		
		$fontlist = $this->_fontManager->getFontlist();
					
		// render settings view
		include(CRYPTEX_PLUGIN_PATH.'/views/admin/Settings.phtml');
	}
	
	// direct static crypt (shortcode passthrough)
	public static function crypt($content){
		echo self::getInstance()->_shortcodeHandler->cryptex(NULL, $content, '');
	}
	
	// drop cache items
	private function clearCache(){
		// cache dir
		$dir = CRYPTEX_PLUGIN_PATH.'/cache/';
		
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
	
	// generate dynamic css file
	private function generateCSS(){
		// load css template
		$cssTPL = new Cryptex\SimpleTemplate(CRYPTEX_PLUGIN_PATH.'/resources/Cryptex.css');
		
		// get config
		$config = $this->_settingsUtility->getOptions();
		
		// assign vars
		$cssTPL->assign('CSSPREFIX', trim($config['css-prefix']));
		$cssTPL->assign('FONT.SIZE', $config['font-size']);
		$cssTPL->assign('FONT.COLOR', $config['font-color']);
		$cssTPL->assign('HREF.CURSOR', ($config['enable-hyperlink'] ? 'pointer' : 'auto'));
		
		// render template and store it (caching)
		$cssTPL->store(CRYPTEX_PLUGIN_PATH.'/cache/Cryptex.css');
	}
	
	// plugin activation action
	public function pluginActivate($plugin){
		// update cache (generate css file) on plugin activation
		if (strripos($plugin, 'cryptex')){
			$this->clearCache();
			$this->generateCSS();
		}
	}
	
}