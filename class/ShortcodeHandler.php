<?php
/**
    Shortcode Handler Class
    Version: 1.0
    Author: Andi Dittrich
    Author URI: http://andidittrich.de
    Plugin URI: http://andidittrich.de/go/cryptex
    License: MIT X11-License
    
    Copyright (c) 2013-2014, Andi Dittrich

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Cryptex;

class ShortcodeHandler{
    
    // store registered shortcodes
    private $_registeredShortcodes = array();
    
    // renderer
    private $_renderer;
    
    public function __construct($settingsManager, $imageGenerator){

        // use cryptex shortcode ?
        if ($settingsManager->getOption('shortcode-cryptex')){
            $this->_registeredShortcodes[] = 'cryptex';
            add_shortcode('cryptex', array($this, 'cryptex'));
        }        
        
        // use email shortcode ?
        if ($settingsManager->getOption('shortcode-email')){
            $this->_registeredShortcodes[] = 'email';
            add_shortcode('email', array($this, 'email'));
        }

        // use telephone shortcode ?
        if ($settingsManager->getOption('shortcode-telephone')){
            $this->_registeredShortcodes[] = 'telephone';
            add_shortcode('telephone', array($this, 'telephone'));
        }
        
        // add texturize filter
        add_filter('no_texturize_shortcodes', array($this, 'texturizeHandler'));
        
        // initialize renderer
        if ($settingsManager->getOption('hdpi-enabled')){
            // css or srcset renderer ?
            if ($settingsManager->getOption('hdpi-renderer')=='srcset'){
                $this->_renderer = new Hdpi5Renderer($settingsManager, $imageGenerator);
            }else{
                $this->_renderer = new HdpiCssRenderer($settingsManager, $imageGenerator);
            }
        }else{
            $this->_renderer = new ClassicRenderer($settingsManager, $imageGenerator);
        }
    }
    
    // handle cryptex shortcode
    public function cryptex($shortcodeAttributes, $content='', $code=''){
        // default attribute settings
        $options = shortcode_atts(
                array(
                    'font' => null,
                    'size' => null,
                    'color' => null,
                    'offset' => null,
                    'security' => null,
                    'type' => 'email',
                    'href' => null
                ), $shortcodeAttributes);
        
        // offset available ?
        if ($options['offset'] != null){
            $e = explode(',', $options['offset']);
            
            // 4 values provided ?
            if (count($e) == 4){
                $options['offset'] = $e;
            }else{
                $options['offset'] = null;
            }
        }
        
        return $this->_renderer->render($content, $options);
    }

    // handle email shortcode - just set the type and pass to cryptex generic handler
    public function email($shortcodeAttributes, $content='', $code=''){
        // attributes given ?
        $options = is_array($shortcodeAttributes) ? $shortcodeAttributes : array();

        // override type
        $options['type'] = 'email';

        // passthrough
        return $this->cryptex($options, $content, $code);
    }

    // handle telephone shortcode - just set the type and pass to cryptex generic handler
    public function telephone($shortcodeAttributes, $content='', $code=''){
        // attributes given ?
        $options = is_array($shortcodeAttributes) ? $shortcodeAttributes : array();

        // override type
        $options['type'] = 'telephone';

        // passthrough
        return $this->cryptex($options, $content, $code);
    }
    
    /**
     * Removes wordpress auto-texturize handler from used shortcodes
     * @param Array $shortcodes
     */
    public function texturizeHandler($shortcodes) {
        return array_merge($shortcodes, $this->_registeredShortcodes);
    }
}
