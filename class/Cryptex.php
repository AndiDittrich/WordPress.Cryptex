<?php
/**
    Cryptex Class
    Version: 6.0
    Author: Andi Dittrich
    Author URI: https://andidittrich.de
    Plugin URI: https://andidittrich.de/go/cryptex
    License: MIT X11-License
    
    Copyright (c) 2010-2017, Andi Dittrich

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class Cryptex{

    // shortcode handler instance
    private $_shortcodeHandler;
    
    // resource loader instamce
    private $_resourceLoader;
    
    // settings manager instance
    private $_settingsManager;

    // settings view helper
    private $_settingsUtility;
    
    // font manager instance
    private $_fontManager;
    
    // email address regex autodetect
    private $_autodetectFilter;
    
    // cache management
    private $_cacheManager;
    
    // image generator instance
    private $_imageGenerator;

    public function __construct(){
        // generate session based key
        Cryptex\KeyShiftingEncoder::generateKey();

        // create new updater instance - will restore fonts on plugin updates
        $updater = new Cryptex\Updater('cryptex', array(CRYPTEX_DEFAULT_FONT_PATH));

        // fetch default config & validators
        $pluginConfig = new Cryptex\skltn\PluginConfig();

        // create new settings utility class
        $this->_settingsManager = new Cryptex\skltn\SettingsManager($pluginConfig);

        // initialize cache-managemet
        $this->_cacheManager = new Cryptex\skltn\CacheManager();

        // use custom cache path/url ?
        if ($this->_settingsManager->getOption('cache-custom')){
            $this->_cacheManager->setCacheLocation($this->_settingsManager->getOption('cache-path'), $this->_settingsManager->getOption('cache-url'));
        }
    }

    public function _wp_init(){
        // load language files
        if ($this->_settingsManager->getOption('translation-enabled')){
            load_plugin_textdomain('cryptex', null, 'cryptex/lang/');
        }

        // create new image generator instance
        $this->_imageGenerator = new Cryptex\ImageGenerator($this->_settingsManager, $this->_cacheManager);
        
        // create new resource loader
        $this->_resourceLoader = new Cryptex\ResourceLoader($this->_settingsManager, $this->_cacheManager);
        
        // create new font manager
        $this->_fontManager = new Cryptex\FontManager($this->_settingsManager);

        // update cache on upgrade
        add_action('upgrader_post_install', array($this, 'generateCSS'), 10, 0);

        // store cached data on shutdown
        add_action('shutdown', array($this->_imageGenerator, 'updateCache'));
    
        // frontend or admin area ?
        if (is_admin()){
            // add admin menu handler
            add_action('admin_menu', array($this, 'setupBackend'));

            // add plugin upgrade notification
            add_action('in_plugin_update_message-cryptex/Cryptex.php', array($this, 'showUpgradeAvailabilityNotification'), 10, 2);

            // plugin upgraded ?
            if (get_option('cryptex-upgrade', false) === true){
                // add admin message handler
                add_action('admin_notices', array($this, 'showUpgradeMessage'));
                add_action('network_admin_notices', array($this, 'showUpgradeMessage'));

                // clear flag - avoid issues with caching plugin - override AND delete the flag
                update_option('cryptex-upgrade', false);
                delete_option('cryptex-upgrade');
            }

            // initialize settings view helper
            $this->_settingsUtility = new Cryptex\skltn\SettingsViewHelper($this->_settingsManager);
        }else{
            // create new shortcode handler, register all used shortcodes
            $this->_shortcodeHandler = new Cryptex\ShortcodeHandler($this->_settingsManager, $this->_imageGenerator);

            // autodetect emails ?
            if ($this->_settingsManager->getOption('email-autodetect')){
                $this->_autodetectFilter = new Cryptex\AutodetectFilter($this->_settingsManager, $this->_shortcodeHandler);
            }
            
            // apply frontend resource loading hooks
            $this->_resourceLoader->frontend();
        }
    }
    
    public function setupBackend(){
        if (current_user_can('manage_options')){
            // add options page
            $optionsPage = add_options_page(__('Cryptex | E-Mail-Address Protection', 'cryptex'), 'Cryptex', 'administrator', 'Cryptex', array($this, 'settingsPage'));

            // add links
            add_filter('plugin_row_meta', array($this, 'addPluginPageLinks'), 10, 2);

            // load jquery stuff
            add_action('admin_print_scripts-'.$optionsPage, array($this->_resourceLoader, 'appendAdminJS'));
            add_action('admin_print_styles-'.$optionsPage, array($this->_resourceLoader, 'appendAdminCSS'));
        
            // call register settings function
            add_action('admin_init', array($this->_settingsManager, 'registerSettings'));
            
            // contextual help
            $ch = new Cryptex\ContextualHelp($this->_settingsManager);
            add_filter('load-'.$optionsPage, array($ch, 'contextualHelp'));
        }
    }
    

    // options page
    public function settingsPage(){
        // well...is there no action hook for updating settings in wp ?
        if (isset($_GET['settings-updated'])){
            // generate new salt - don't have to be a cryptographically secure value
            $this->_settingsManager->setOption('salt', sha1(mt_rand().mt_rand().time()));
            
            // clear cache
            $this->_cacheManager->clearCache();
            
            // recreate css file
            $this->generateCSS();
        }
        
        // fetch system fontlist
        $fontlist = $this->_fontManager->getFontlist();
        
        // render settings view
        include(CRYPTEX_PLUGIN_PATH.'/views/admin/SettingsPage.phtml');
    }

    // links on the plugin page
    public function addPluginPageLinks($links, $file){
        // current plugin ?
        if ($file == 'cryptex/Cryptex.php'){
            $links[] = '<a href="'.admin_url('options-general.php?page=Cryptex'). '">'.__('Settings', 'cryptex').'</a>';
            $links[] = '<a href="https://twitter.com/andidittrich" target="_blank">'.__('News & Updates', 'cryptex').'</a>';
        }

        return $links;
    }
    
    // direct static crypt (shortcode passthrough)
    public static function crypt($content, $options=NULL){
        echo self::getInstance()->_shortcodeHandler->cryptex($options, $content, '');
    }
    
    // direct static crypt (shortcode passthrough)
    public static function getEncryptedAddress($content, $options=NULL){
        return self::getInstance()->_shortcodeHandler->cryptex($options, $content, '');
    }
    
    // generate dynamic css file
    public function generateCSS(){
        // load css template
        $cssTPL = new Cryptex\CssTemplate(CRYPTEX_PLUGIN_PATH.'/resources/Cryptex.css');
        
        // get config
        $config = $this->_settingsManager->getOptions();
        
        // assign vars
        $cssTPL->assign('CSSPREFIX', trim($config['css-prefix']));
        $cssTPL->assign('FONT.SIZE', $config['font-size']);
        $cssTPL->assign('FONT.COLOR', $config['font-color']);
        $cssTPL->assign('HREF.CURSOR', ($config['enable-hyperlink'] ? 'pointer' : 'auto'));
        $cssTPL->assign('FONT.FAMILY', (strlen($config['font-family']) > 1 ? $config['font-family'] : 'inherit'));
        
        // render template and store it (caching)
        $cssTPL->store($this->_cacheManager->getCachePath().'Cryptex.css');
    }
    
    // plugin activation action
    public function _wp_plugin_activate(){
        $this->_cacheManager->clearCache();
        $this->generateCSS();
    }

    public function _wp_plugin_deactivate(){
    }

    public function _wp_plugin_upgrade($currentVersion){
        // upgrade from < 6.0 ? use v5.99 condition to ensure that beta versions are not altered!
        if (version_compare($currentVersion, '5.99', '<')){
            // load upgrader
            require_once(CRYPTEX_PLUGIN_PATH.'/upgrade/Upgrade_to_6_0_0.php');

            // create upgrader instance
            $upgrader = new Cryptex\Upgrade\Upgrade_to_6_0_0();

            // run
            $upgrader->run($currentVersion, CRYPTEX_VERSION);
        }

        // regenerate cache on upgrade
        $this->_cacheManager->clearCache();
        $this->generateCSS();

        // upgrade successfull
        return true;
    }

    // Show Upgrade Notification in Plugin List for an available new Version
    public function showUpgradeAvailabilityNotification($currentPluginMetadata, $newPluginMetadata){
        // check "upgrade_notice"
        if (isset($newPluginMetadata->upgrade_notice) && strlen(trim($newPluginMetadata->upgrade_notice)) > 0){
            echo '<p style="background-color: #d54e21; padding: 10px; color: #f9f9f9; margin-top: 10px"><strong>Important Upgrade Notice:</strong> ';
            echo esc_html($newPluginMetadata->upgrade_notice), '</p>';
        }
    }

    // Show Admin Notice for Successfull Plugin Upgrade
    public function showUpgradeMessage(){
        // styling
        echo '<div class="notice notice-success is-dismissible"><p>';
        echo '<strong>Cryptex Plugin Upgrade:</strong> The Plugin has been upgraded to <strong>', CRYPTEX_VERSION, '</strong>';
        echo '</p></div>';
    }


//!WP::SKELETON

    // static entry/initialize singleton instance
    public static function run($pluginName){
        // check if singleton instance is available
        if (self::$__instance==null){
            // create new instance if not
            $i = self::$__instance = new self();

            // register plugin related hooks
            register_activation_hook($pluginName, array($i, '_wp_plugin_activate'));
            register_deactivation_hook($pluginName, array($i, '_wp_plugin_deactivate'));
            add_action('init', array($i, '_wp_init'));

            // fetch plugin version
            $version = get_option('cryptex-version', '0.0.0');

            // plugin upgraded ?
            if (version_compare('6.0', $version, '>')){
                // run upgrade hook
                if ($i->_wp_plugin_upgrade($version)){
                    // store new version
                    update_option('cryptex-version', '6.0');

                    // set flag
                    update_option('cryptex-upgrade', true);
                }
            }
        }
    }

    // singleton instance
    private static $__instance;
    public static function getInstance(){
        return self::$__instance;
    }
//!!WP::SKELETON
}