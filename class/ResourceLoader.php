<?php
/**
    Resource Utility Loader Class
    Version: 6.0
    Author: Andi Dittrich
    Author URI: http://andidittrich.de
    Plugin URI: http://andidittrich.de/go/cryptex
    License: MIT X11-License
    
    Copyright (c) 2013-2017, Andi Dittrich

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
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
    
    public function appendAdminCSS(){
        // colorpicker css
        ResourceManager::enqueueStyle('cryptex-jquery-colorpicker', 'extern/colorpicker/css/colorpicker.css');
        
        // new UI !
        ResourceManager::enqueueStyle('cryptex-settings', 'admin/settings.css');
    }
    
    public function appendAdminJS(){

        // colorpicker js
        ResourceManager::enqueueScript('cryptex-jquery-colorpicker', 'extern/colorpicker/js/colorpicker.js', array('jquery'));

        // settings init script
        ResourceManager::enqueueScript('cryptex-settings', 'admin/settings.js', array('jquery', 'jquery-color'));
    }
}