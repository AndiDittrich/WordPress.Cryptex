<?php
/**
    Plugin Name: Cryptex - E-Mail Address Protection
    Plugin URI: https://github.com/AndiDittrich/WordPress.Cryptex
    Description: Advanced Graphical E-Mail Obfuscator which provides image based email address protection using wordpress shortcode and integrated encryption/decryption of addresses
    Version: 7.1
    Author: Andi Dittrich
    Author URI: https://andidittrich.de
    License: GPL-2.0
    Text Domain: cryptex
    Domain Path: /lang
*/


// Plugin Bootstrap Operation
// AUTO GENERATED CODE - DO NOT EDIT !!!
define('CRYPTEX_INIT', true);
define('CRYPTEX_VERSION', '7.1');
define('CRYPTEX_WPSKLTN_VERSION', '0.17.0');
define('CRYPTEX_PLUGIN_TITLE', 'Cryptex - E-Mail Address Protection');
define('CRYPTEX_PLUGIN_HEADLINE', 'Advanced Graphical E-Mail Obfuscator which provides image based email address protection using wordpress shortcode and integrated encryption/decryption of addresses');
define('CRYPTEX_PLUGIN_PATH', dirname(__FILE__));
define('CRYPTEX_PLUGIN_URL', plugins_url('/cryptex/'));
define('CRYPTEX_DEFAULT_FONT_PATH', CRYPTEX_PLUGIN_PATH.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR);


// PHP Version Error Notice
function Cryptex_PhpEnvironmentError(){
    // error message
    $message = '<strong>Cryptex Plugin Error:</strong> Your PHP Version <strong style="color: #cc0a00">('. phpversion() .')</strong> is outdated! <strong>PHP 5.4 or greater</strong> is required to run this plugin!';

    // styling
    echo '<div class="notice notice-error is-dismissible"><p>', $message, '</p></div>';
}

// check php version
if (version_compare(phpversion(), '5.4', '>=')){
    // load classes
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/AdminMenu.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/CacheManager.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/CssBuilder.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/EnvironmentCheck.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/Hash.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/HtmlUtil.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/Logger.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/Plugin.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/PluginConfig.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/ResourceManager.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/RewriteRuleHelper.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/SettingsManager.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/SettingsViewHelper.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/skltn/VirtualPageManager.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/AutodetectFilter.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/ClassicRenderer.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/ContextualHelp.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/Cryptex.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/CssTemplate.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/FontManager.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/Hdpi5Renderer.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/HdpiCssRenderer.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/ImageGenerator.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/KeyShiftingEncoder.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/ResourceLoader.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/ShortcodeHandler.php');
    require_once(CRYPTEX_PLUGIN_PATH.'/modules/cryptex/Updater.php');

    
    // startup - NEVER CALL IT OUTSIDE THIS FILE !!
    Cryptex::run(__FILE__);
}else{
    // add admin message handler
    add_action('admin_notices', 'Cryptex_PhpEnvironmentError');
    add_action('network_admin_notices', 'Cryptex_PhpEnvironmentError');
}