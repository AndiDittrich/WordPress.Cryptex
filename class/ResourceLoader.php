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
			add_action('wp_head', array($this, 'appendInlineCSS'), 50);
		}
		
		// only include js if required
		if ($this->_config['enable-hyperlink'] && $this->_config['embed-js']){
			add_action('wp_footer', array($this, 'appendInlineJavascript'));
		}
	}

	// inline css
	public function appendInlineJavascript(){
		// get js
		$js = file_get_contents(CRYPTEX_PLUGIN_PATH.'/resources/CryptexHyperlinkDecoder.min.js');

        // drop trailing ;
        $js = rtrim($js, ';');

		// output inline scripts - add function call
		echo '<script type="text/javascript">/* <![CDATA[ */', $js, '(window, document, "', KeyShiftingEncoder::getKey() , '"); /* ]]> */</script>';
	}

	// inline css
	public function appendInlineCSS(){
		echo '<style type="text/css">', file_get_contents($this->_cacheManager->getCachePath().'Cryptex.css'), '</style>';
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
        // jquery cookie js
        wp_register_script('cryptex-jquery-cookie', plugins_url('/cryptex/extern/jquery.cookie/jquery.cookie.js'), array('jquery'));
        wp_enqueue_script('cryptex-jquery-cookie');

		// colorpicker js
		wp_register_script('cryptex-jquery-colorpicker', plugins_url('/cryptex/extern/colorpicker/js/colorpicker.js'), array('jquery'));
		wp_enqueue_script('cryptex-jquery-colorpicker');
		
		// settings init script
		wp_register_script('cryptex-settings-init', plugins_url('/cryptex/resources/admin/CryptexSettings.min.js'), array('jquery'));
		wp_enqueue_script('cryptex-settings-init');
	}
}

?>