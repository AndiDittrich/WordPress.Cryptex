<?php

// High-DPI/Retina Rendering Engine - based on css media queries
namespace Cryptex;

class HdpiCssRenderer
    extends ClassicRenderer{
    
    // cache of generated images
    private $_imageCache = array();
    
    // high-dpi scaling factor
    private $_hdpiFactor = 3;
    
    public function __construct($settingsUtil, $imageGenerator){
        parent::__construct($settingsUtil, $imageGenerator);
        
        // add wp_footer hook
        add_action('wp_footer', array($this, 'printInlineStyles'), 100);
        
        // extract hdpi scaling factor
        $this->_hdpiFactor = intval($settingsUtil->getOption('hdpi-factor'));
    }
    
    /**
     * @OVERRIDE
     * Generate Image Tag - including server side image generation
     * @param unknown $content
     * @param unknown $options
     * @return string
     */
    protected function getImage($content, $options){
        // generate images
        $imgdata_ndpi = $this->_imageGenerator->getImage($content, $options['font'], $options['size'], $options['color'], $options['offset'], 1);
        $imgdata_hdpi = $this->_imageGenerator->getImage($content, $options['font'], $options['size'], $options['color'], $options['offset'], $this->_hdpiFactor, false);
        
        // use image-hash of first image as key
        $this->_imageCache[$imgdata_ndpi[3]] = array(
                $imgdata_ndpi,
                $imgdata_hdpi
        );
        
        // generate tag
        return sprintf(
                '<span class="CryptexImg ctx%s"></span>',
                esc_attr($imgdata_ndpi[3])
        );
    }
    
    /**
     * Generate the required Inline-Styles
     */
    public function printInlineStyles(){
        // crytex images available
        if (count($this->_imageCache) == 0){
            return;
        }
        
        echo '<style type="text/css">';

        // normal-dpi images; dpr=1.0
        foreach ($this->_imageCache as $hash => $img){
            echo '.ctx', $hash, '{';
            echo 'width: ', $img[0][1], 'px;';
            echo 'height: ', $img[0][2], 'px;';
            echo 'background-image: url(', $img[0][0], ');';
            echo "}\n";
        }
        
        // high-dpi images; dpr>=1.5
        // @see https://developer.mozilla.org/en-US/docs/Web/Guide/CSS/Media_queries#-moz-device-pixel-ratio
        //echo '@media screen and (min--moz-device-pixel-ratio: 1.5), screen and (-o-min-device-pixel-ratio: 3/2), screen and (-webkit-min-device-pixel-ratio: 1.5), screen and (min-device-pixel-ratio: 1.5) {';
        echo '@media (-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (min-resolution: 1.5dppx), (min-resolution: 144dpi){';
        foreach ($this->_imageCache as $hash => $img){
            echo '.ctx', $hash, '{';
            echo 'background-image: url(', $img[1][0], ');';
            echo "}\n";
        }
        echo '}</style>';
    }    
}