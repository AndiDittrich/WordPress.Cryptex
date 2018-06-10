<?php
namespace Cryptex;

use \Cryptex\skltn\ResourceManager as ResourceManager;

class ResourceLoader{
        
    // stores the plugin config
    private $_settingsManager;
    
    private $_cacheManager;

    public function __construct($settingsManager, $cacheManager){
        // store local plugin config
        $this->_settingsManager = $settingsManager;
        $this->_cacheManager = $cacheManager;
    }
    
    // frontend resources
    public function frontend(){
        // load frontend css
        if ($this->_settingsManager->getOption('embed-css')){
            // get css
            $css = file_get_contents($this->_cacheManager->getCachePath().'Cryptex.css');

            // add content to enqeue cache
            ResourceManager::enqueueDynamicStyle($css);
        }
        
        // only include js if required
        if ($this->_settingsManager->getOption('enable-hyperlink') && $this->_settingsManager->getOption('embed-js')){
            // get js
            $js = file_get_contents(CRYPTEX_PLUGIN_PATH.'/resources/CryptexHyperlinkDecoder.min.js');

            // drop trailing function call "();"
            $js = substr($js, 0, -3);

            // append initialization - function call with arguments
            $js .= '(window, document, "' . KeyShiftingEncoder::getKey() . '");';
            
            // add content to enqeue cache
            ResourceManager::enqueueDynamicScript($js);
        }
    }

    public function backendSettings(){
        add_action('admin_enqueue_scripts', array($this, 'appendAdminResources'));
    }
    
    public function appendAdminResources(){
        // colorpicker css
        ResourceManager::enqueueStyle('cryptex-jquery-colorpicker', 'extern/colorpicker/css/colorpicker.css');
        
        // new UI !
        ResourceManager::enqueueStyle('cryptex-settings', 'admin/settings.css');

        // colorpicker js
        ResourceManager::enqueueScript('cryptex-jquery-colorpicker', 'extern/colorpicker/js/colorpicker.js', array('jquery'));

        // settings init script
        ResourceManager::enqueueScript('cryptex-settings', 'admin/settings.js', array('jquery', 'jquery-color'));
    }
}