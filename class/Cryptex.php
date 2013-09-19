<?php
/**
	Cryptex Class
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


class Cryptex{
	// singleton instance
	private static $__instance;	
	
	// cryptex key
	private $_key;
	
	// cryptex config (array)
	private $_config;
	
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
	
	public function __construct(){
		// initialize updater
		$updater = new Cryptex_Updater('cryptex', array(CRYPTEX_PLUGIN_PATH.DIRECTORY_SEPARATOR.'fonts'));
		
		// generate key
		$this->_key = Cryptex_KeyShiftingEncoder::generateKey();
		
		// load cryptex config
		$this->loadConfig();
		
		// update cache on install
		add_action('activate_plugin', array($this, 'pluginActivate'), 10, 1);
		
		// frontend or admin area ?
		if (is_admin()){
			// load language files
			load_plugin_textdomain('cryptex', null, basename(dirname(__FILE__)).'/lang');
			
			// add admin menu handler
			add_action('admin_menu', array($this, 'setupBackend'));
		}else{
			// add shotcode handler
			add_shortcode('cryptex', array($this, 'shortcodeHandler'));
			
			// add js & css files
			add_action('wp_print_scripts', array($this, 'appendJS'), 100, 0);
			add_action('wp_print_styles', array($this, 'appendCSS'), 100, 0);
		}
		
	}
	
	// handle wp shortcode
	public function shortcodeHandler($atts=NULL, $content='', $code=''){
		// get 2 parts
		$parts = explode('@', $content);
		
		// return value
		$html = '';
		
		// get rel attribute
		$rel = (get_option('cryptex-enable-hyperlink', true) ? Cryptex_KeyShiftingEncoder::encode($content, $this->_key) : '');
		
		// email ?
		if (count($parts)==2 && $this->_config['securitylevel'] > 1){
			// generate html & images
			$html = Cryptex_PHPCapture::capture(CRYPTEX_PLUGIN_PATH.'/views/hybrid_mail.phtml', array(
				'imageURL0' => Cryptex_ImageGenerator::generate($parts[0]),
				'imageURL1' => Cryptex_ImageGenerator::generate($parts[1]),
				'rel' => $rel,
				'divider' => get_option('cryptex-email-divider', '(at)')
			));
		}else{
			// replace divider
			$content = str_replace('@', get_option('cryptex-email-divider', '@'), $content);
			
			// generate html
			$html = Cryptex_PHPCapture::capture(CRYPTEX_PLUGIN_PATH.'/views/static_mail.phtml', array(
				'imageURL' => Cryptex_ImageGenerator::generate($content),
				'rel' => (count($parts)==2 ? $rel : '')
			));
		}
		
		// return rendered shortcode
		return $html;
	}
	
	// direct static crypt (shortcode passthrough)
	public static function crypt($content){
		echo self::getInstance()->shortcodeHander(NULL, $content, '');
	}
	
	// update cache
	private function updateCache(){
		// cache dir
		$dir = CRYPTEX_PLUGIN_PATH.'/cache/';
		// remove cache files
		if (is_dir($dir)){
			$files = scandir($dir);
			foreach ($files as $file){
				if ($file!='.' && $file!='..'){
					unlink($dir.$file);	
				}
			}
		}
		
		// create dynamic css style
		
		// load css template
		$cssTPL = new Cryptex_SimpleTemplate(CRYPTEX_PLUGIN_PATH.'/css/cryptex.css');
		
		// assign vars
		$cssTPL->assign('CSSPREFIX', $this->_config['cssprefix']);
		$cssTPL->assign('FONT.FAMILY', $this->_config['fontfamily']);
		$cssTPL->assign('FONT.SIZE', $this->_config['fontsize']);
		$cssTPL->assign('FONT.COLOR', $this->_config['fontcolor']);
		$cssTPL->assign('HREF.CURSOR', ($this->_config['usehyperlinks'] ? 'pointer' : 'auto'));
		
		// store file
		$cssTPL->store(CRYPTEX_PLUGIN_PATH.'/cache/cryptex.css');
	}
	
	// append css
	public function appendCSS(){
		// only include css if enabled
		if ($this->_config['embedcss']){ 
			// include dynamic css file
			wp_register_style('cryptex-dynamic', plugins_url('/cryptex/cache/cryptex.css'));
			wp_enqueue_style('cryptex-dynamic');
		}		
	}
	
	// append js
	public function appendJS(){
		// only include js if hyperlinks are enabled and frontend is active
		if ($this->_config['usehyperlinks']){ 
			// js	
			echo '<script type="text/javascript">var CRYPTEX_KEY = \''.$this->_key.'\';</script>';
			echo '<script type="text/javascript" src="'.plugins_url('/cryptex/js/cryptex_compressed.js').'"></script>';
		}		
	}
	
	// plugin activation action
	public function pluginActivate(){
		// update cache (generate css file) on plugin activation
		if (strripos($plugin, 'cryptex')){
			$this->updateCache();
		}
	}
	
	// load options
	private function loadConfig(){
		$this->_config = array(
			'fontfamily' => substr(get_option('cryptex-font', 'Arial.ttf'), 0, -4),
			'fontcolor' => str_replace('0x', '', get_option('cryptex-font-color', '0x000000')),
			'fontsize' => intval(get_option('cryptex-font-size', '12')).Cryptex_ImageGenerator::getFontSizeFormat(),
			'cssprefix' => get_option('cryptex-css-prefix', ''),
			'usehyperlinks' => get_option('cryptex-enable-hyperlink', true),
			'embedcss' => get_option('cryptex-embed-css', true),
			'fontpath' => get_option('cryptex-font-path', ''),
			'securitylevel' => intval(get_option('cryptex-security-level', 2))
		);	
	}
	
	
	public function setupBackend(){
		// add options page
		$optionsPage = add_options_page(__('Cryptex - Advanced EMail Obfuscator+Protector'), __('Cryptex Obfuscator'), 'administrator', __FILE__, array($this, 'settingsPage'));
		
		// add css/js to page
		add_action('admin_print_styles-'.$optionsPage, array($this, 'appendAdminCSS'));
		add_action('admin_print_scripts-'.$optionsPage, array($this, 'appendAdminJS'));
		
		// call register settings function
		add_action('admin_init', array($this, 'registerSettings')); 	
	}
	
	// options page
	public function settingsPage(){
		// well...is there no action hook for updating settings in wp ?
		if (isset($_GET['settings-updated'])){
			$this->updateCache();
		}
	
		// font list
		$fonts = array();
		
		// get font list by path
		if (is_dir($this->_config['fontpath'])){
			$files = scandir($this->_config['fontpath']);	
			foreach ($files as $file){
				if (strtolower(end(explode(".", $file))) == 'ttf'){
					$fonts[] = $file;
				}	
			}
		}
	
		// include admin page
		include(CRYPTEX_PLUGIN_PATH.'/views/admin/settings.phtml');
	}
	
	// register settings
	public function registerSettings(){
		// register settings
		register_setting('cryptex-settings-group', 'cryptex-font-path');
		register_setting('cryptex-settings-group', 'cryptex-enable-hyperlink');
		register_setting('cryptex-settings-group', 'cryptex-email-divider');
		register_setting('cryptex-settings-group', 'cryptex-embed-css');
		register_setting('cryptex-settings-group', 'cryptex-font');
		register_setting('cryptex-settings-group', 'cryptex-font-size');
		register_setting('cryptex-settings-group', 'cryptex-font-color');
		register_setting('cryptex-settings-group', 'cryptex-security-level');
		register_setting('cryptex-settings-group', 'cryptex-css-prefix');
	}
	public function appendAdminCSS(){
		// colorpicker css
		wp_register_style('jquery-colorpicker', plugins_url('/cryptex/extern/colorpicker/css/colorpicker.css'));
		wp_enqueue_style('jquery-colorpicker');
	}
	public function appendAdminJS(){
		// colorpicker js
		wp_register_script('jquery-colorpicker', plugins_url('/cryptex/extern/colorpicker/js/colorpicker.js'));
		wp_enqueue_script('jquery-colorpicker');
	}
	
	
	
}