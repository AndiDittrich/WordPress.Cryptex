<?php
/**
	Plugin Name: Cryptex - E-Mail Address Protection
	Plugin URI: http://andidittrich.de/go/cryptex
	Description: Advanced Graphical EMail Obfuscator which provides image based email address protection using wordpress shortcode and integrated encryption/decryption of addresses
	Version: 5.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	License: MIT X11-License
	
	Copyright (c) 2010-2015, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/*
*	BOOTSTRAP OPERATION
*/
define('CRYPTEX_INIT', true);
define('CRYPTEX_VERSION', '5.0');
define('CRYPTEX_PLUGIN_PATH', dirname(__FILE__));
define('CRYPTEX_DEFAULT_FONT_PATH', CRYPTEX_PLUGIN_PATH.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR);

// check php version
if (version_compare(phpversion(), '5.3', '>=')){
	// load classes
	require_once(CRYPTEX_PLUGIN_PATH.'/class/Cryptex.php');	
	require_once(CRYPTEX_PLUGIN_PATH.'/class/HtmlUtil.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/ResourceLoader.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/SettingsUtil.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/KeyShiftingEncoder.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/ImageGenerator.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/CssTemplate.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/Updater.php');	
	require_once(CRYPTEX_PLUGIN_PATH.'/class/ShortcodeHandler.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/FontManager.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/AutodetectFilter.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/ObjectCache.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/ClassicRenderer.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/HdpiCssRenderer.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/Hdpi5Renderer.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/ContextualHelp.php');
	require_once(CRYPTEX_PLUGIN_PATH.'/class/CacheManager.php');
	
	// bootstrap startup
	Cryptex::run();
}else{
	// add admin menu handler
	add_action('admin_menu', 'Cryptex_SetupEnvironmentError');
}

// error options page setup
function Cryptex_SetupEnvironmentError(){
	// add options page
	add_options_page('Cryptex | E-Mail-Address Protection', 'Cryptex', 'administrator', __FILE__, 'Cryptex_EnvironmentError');
}
// options page
function Cryptex_EnvironmentError(){
	include(CRYPTEX_PLUGIN_PATH.'/views/admin/EnvironmentError.phtml');
}