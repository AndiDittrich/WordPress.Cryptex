<?php

class Cryptex extends \Cryptex\skltn\Plugin{

    // shortcode handler instance
    protected $_shortcodeHandler;
    
    // resource loader instamce
    protected $_resourceLoader;
    
    // font manager instance
    protected $_fontManager;
    
    // email address regex autodetect
    protected $_autodetectFilter;
    
    // cache management
    protected $_cacheManager;
    
    // image generator instance
    protected $_imageGenerator;

    public function __construct(){
        parent::__construct();

        // generate session based key
        Cryptex\KeyShiftingEncoder::generateKey();

        // create new updater instance - will restore fonts on plugin updates
        $updater = new Cryptex\Updater('cryptex', array(CRYPTEX_DEFAULT_FONT_PATH));

        // initialize cache-managemet
        $this->_cacheManager = new Cryptex\skltn\CacheManager();

        // use custom cache path/url ?
        if ($this->_settingsManager->getOption('cache-custom')){
            $this->_cacheManager->setCacheLocation($this->_settingsManager->getOption('cache-path'), $this->_settingsManager->getOption('cache-url'));
        }
    }

    public function _wp_init(){
        // execute extended functions
        parent::_wp_init();
        
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

    // backend menu structure
    protected function getBackendMenu(){
        // menu group + first entry
        return array(
            'pagetitle' => CRYPTEX_PLUGIN_TITLE,
            'title' => 'Cryptex',
            'title2' => 'Appearance',
            'slug' => 'cryptex-appearance',
            'icon' => 'dashicons-shield',
            'template' => 'appearance/AppearancePage',
            'resources' => array($this->_resourceLoader, 'backendSettings'),
            'render' => array($this, 'settingsPage'),
            'help' => array('Cryptex\ContextualHelp', 'settings'),
            'items' => array(
                // advanced options
                array(
                    'pagetitle' => CRYPTEX_PLUGIN_TITLE,
                    'title' => 'Options',
                    'slug' => 'cryptex-options',
                    'template' => 'options/OptionsPage',
                    'resources' => array($this->_resourceLoader, 'backendSettings'),
                    'render' => array($this, 'settingsPage'),
                    'help' => array('Cryptex\ContextualHelp', 'settings')
                ),
                // about
                array(
                    'pagetitle' => CRYPTEX_PLUGIN_TITLE,
                    'title' => 'About',
                    'slug' => 'cryptex-about',
                    'template' => 'about/AboutPage',
                    'resources' => array($this->_resourceLoader, 'backendSettings')
                )
            )
        );
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

        return array(
            'fontlist' => $this->_fontManager->getFontlist()
        );
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
        parent::_wp_plugin_activate();

        // regenerate files
        $this->_cacheManager->clearCache();
        $this->generateCSS();
    }

    public function _wp_plugin_upgrade($currentVersion){
        // upgrade from < 6.0 ? use v5.99 condition to ensure that beta versions are not altered!
        if (version_compare($currentVersion, '5.99', '<')){
            // load upgrader
            require_once(CRYPTEX_PLUGIN_PATH.'/modules/upgrade/Upgrade_to_6_0_0.php');

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
}