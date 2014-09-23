<?php
/**
	Resource Utility Loader Class
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://andidittrich.de/go/cryptex
	License: MIT X11-License
	
	Copyright (c) 2013, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Cryptex;

class ResourceLoader{
		
	// stores the plugin config
	private $_config;
	
	private $_cacheManager;

	public function __construct($settingsUtil, $cacheManager){
		// store local plugin config
		$this->_config = $settingsUtil->getOptions();
		$this->_cacheManager = $cacheManager;
	}
	
	/**
	 * Apply frontend hooks
	 */
	public function frontend(){
		// load frontend css
		if ($this->_config['embed-css']){
			if ($this->_config['css-type'] == 'inline'){
				add_action('wp_head', array($this, 'appendInlineCSS'), 50);
			}else{
				add_action('wp_enqueue_scripts', array($this, 'appendCSS'), 50);
			}
		}
		
		// only include js if required
		if ($this->_config['enable-hyperlink'] && $this->_config['embed-js']){
			// inline js on the bottom of the page ?		
			if ($this->_config['js-type'] == 'inline-footer'){
				add_action('wp_footer', array($this, 'appendInlineJavascript'));
			
			}else if ($this->_config['js-type'] == 'inline-head'){
				add_action('wp_head', array($this, 'appendInlineJavascript'));
				
			// external js fallback
			}else{	
				// load frontend js
				add_action('wp_enqueue_scripts', array($this, 'appendJS'), 50);
					
				// display frontend config
				add_action('wp_head', array($this, 'appendJavascriptConfig'));				
			}

		}
	}
	
	// append javascript based config
	public function appendJavascriptConfig(){
		// generate a config based js tag
		echo '<script type="text/javascript">var CRYPTEX_KEY = \''.KeyShiftingEncoder::getKey().'\';</script>';
	}
	
	// inline css
	public function appendInlineJavascript(){
		// get js
		$js = file_get_contents(CRYPTEX_PLUGIN_PATH.'/resources/Cryptex.min.js');
		
		// add inline key
		$js = str_replace('CRYPTEX_KEY', "'".KeyShiftingEncoder::getKey()."'", $js);
		
		// output inline scripts
		echo '<script type="text/javascript">/* <![CDATA[ */';
		echo $js;
		echo '/* ]]> */</script>';
	}
	
	// append css
	public function appendCSS(){
		// include local css file
		wp_register_style('cryptex-local', $this->_cacheManager->getCacheUrl().'Cryptex.css');
		wp_enqueue_style('cryptex-local');
	}
	
	// inline css
	public function appendInlineCSS(){
		echo '<style type="text/css">';
		echo file_get_contents($this->_cacheManager->getCachePath().'Cryptex.css');
		echo '</style>';
	}
	
	// append js
	public function appendJS(){
		// include local css file
		wp_register_script('cryptex-local', plugins_url('/cryptex/resources/Cryptex.min.js'));
		wp_enqueue_script('cryptex-local');
	}
	
	public function appendAdminCSS(){
		// colorpicker css
		wp_register_style('cryptex-jquery-colorpicker', plugins_url('/cryptex/extern/colorpicker/css/colorpicker.css'));
		wp_enqueue_style('cryptex-jquery-colorpicker');
		
		// new UI !
		wp_register_style('cryptex-settings', plugins_url('/cryptex/resources/admin/CryptexSettings.css'));
				
		// settings css		
		wp_enqueue_style('cryptex-settings');
	}
	
	public function appendAdminJS(){
		// colorpicker js
		wp_register_script('cryptex-jquery-colorpicker', plugins_url('/cryptex/extern/colorpicker/js/colorpicker.js'), array('jquery'));
		wp_enqueue_script('cryptex-jquery-colorpicker');
		
		// settings init script
		wp_register_script('cryptex-settings-init', plugins_url('/cryptex/resources/admin/CryptexSettings.js'), array('jquery'));
		wp_enqueue_script('cryptex-settings-init');
	}
}

?>