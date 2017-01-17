<?php
/**
    Plugin Name: Cryptex - E-Mail Address Protection
    Plugin URI: https://andidittrich.de/go/cryptex
    Description: Advanced Graphical E-Mail Obfuscator which provides image based email address protection using wordpress shortcode and integrated encryption/decryption of addresses
    Version: 6.0
    Author: Andi Dittrich
    Author URI: https://andidittrich.de
    License: MIT X11 License

    ----
    The MIT License (X11 License)
    Copyright (c) 2010-2017 Andi Dittrich <https://andidittrich.de>
    Permission is hereby granted, free of charge, to any personobtaining a copy of this software and associated documentationfiles (the "Software"), to deal in the Software withoutrestriction, including without limitation the rights to use,copy, modify, merge, publish, distribute, sublicense, and/or sellcopies of the Software, and to permit persons to whom theSoftware is furnished to do so, subject to the followingconditions:
    The above copyright notice and this permission notice shall beincluded in all copies or substantial portions of the Software.
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIESOF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE ANDNONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHTHOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISINGFROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OROTHER DEALINGS IN THE SOFTWARE.
*/


/*  AUTO GENERATED FILE - DO NOT EDIT !!
    WP-SKELEKTON | MIT X11 License | https://github.com/AndiDittrich/WP-Skeleton
    ------------------------------------
    Plugin Bootstrap Operation
*/
define('CRYPTEX_INIT', true);
define('CRYPTEX_VERSION', '6.0');
define('CRYPTEX_PLUGIN_PATH', dirname(__FILE__));
define('CRYPTEX_PLUGIN_URL', plugins_url('/cryptex/'));
define('CRYPTEX_DEFAULT_FONT_PATH', CRYPTEX_PLUGIN_PATH.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR);


// PHP Version Error Notice
function Cryptex_PhpEnvironmentError(){
    // error message
    $message = '<strong>Cryptex Plugin Error:</strong> Your PHP Version <strong style="color: #cc0a00">('. phpversion() .')</strong> is outdated! <strong>PHP 5.3 or greater</strong> is required to run this plugin!';

    // styling
    echo '<div class="notice notice-error is-dismissible"><p>', $message, '</p></div>';
}

// check php version
if (version_compare(phpversion(), '5.3', '>=')){
    // load classes
    require_once(CRYPTEX_PLUGIN_PATH.'/skltn/HtmlUtil.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/skltn/SettingsManager.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/skltn/SettingsViewHelper.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/skltn/CacheManager.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/skltn/PluginConfig.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/skltn/Hash.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/AutodetectFilter.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/ClassicRenderer.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/ContextualHelp.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/Cryptex.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/CssTemplate.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/FontManager.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/Hdpi5Renderer.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/HdpiCssRenderer.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/ImageGenerator.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/KeyShiftingEncoder.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/ResourceLoader.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/ShortcodeHandler.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/class/Updater.php');

    
    // startup - NEVER CALL IT OUTSIDE THIS FILE !!
    Cryptex::run(__FILE__);
}else{
    // add admin message handler
    add_action('admin_notices', 'Cryptex_PhpEnvironmentError');
    add_action('network_admin_notices', 'Cryptex_PhpEnvironmentError');
}

