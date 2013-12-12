<?php
/**
	Resource Utility Loader Class
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

class ResourceLoader{
		
	// stores the plugin config
	private $_config;

	public function __construct($settingsUtil){
		// store local plugin config
		$this->_config = $settingsUtil->getOptions();
	}
	
	// append javascript based config
	public function appendJavascriptConfig(){
		// only include js if required
		if ($this->_config['enable-hyperlink']){
			// generate a config based js tag
			echo '<script type="text/javascript">var CRYPTEX_KEY = \''.KeyShiftingEncoder::getKey().'\';</script>';
		}
	}
	
	// append css
	public function appendCSS(){
		// only include css if enabled
		if ($this->_config['embed-css']){
			// include local css file
			wp_register_style('cryptex-local', plugins_url('/cryptex/cache/Cryptex.css'));
			wp_enqueue_style('cryptex-local');
		}
	}
	
	// append js
	public function appendJS(){
		// only include js if required
		if ($this->_config['enable-hyperlink'] && $this->_config['embed-js']){
			// include local css file
			wp_register_script('cryptex-local', plugins_url('/cryptex/resources/Cryptex.yui.js'));
			wp_enqueue_script('cryptex-local');
		}
	}
	
	public function appendAdminCSS(){
		// colorpicker css
		wp_register_style('cryptex-jquery-colorpicker', plugins_url('/cryptex/extern/colorpicker/css/colorpicker.css'));
		wp_enqueue_style('cryptex-jquery-colorpicker');
		
		// new UI ?
		if (version_compare(get_bloginfo('version'), '3.8', '>=')) {
			wp_register_style('cryptex-settings', plugins_url('/cryptex/resources/admin/settings38.css'));
		}else{
			wp_register_style('cryptex-settings', plugins_url('/cryptex/resources/admin/settings37.css'));
		}
		
		// settings css		
		wp_enqueue_style('cryptex-settings');
	}
	
	public function appendAdminJS(){
		// colorpicker js
		wp_register_script('cryptex-jquery-colorpicker', plugins_url('/cryptex/extern/colorpicker/js/colorpicker.js'), array('jquery'));
		wp_enqueue_script('cryptex-jquery-colorpicker');
		
		// settings init script
		wp_register_script('cryptex-settings-init', plugins_url('/cryptex/resources/admin/settings.js'), array('jquery'));
		wp_enqueue_script('cryptex-settings-init');
	}
	
	
}