<?php

// High-DPI/Retina Rendering Engine - based on srcset
namespace Cryptex;

class Hdpi5Renderer
    extends ClassicRenderer{

    // high-dpi scaling factor
    private $_hdpiFactor = 3;
    
    public function __construct($settingsUtil, $imageGenerator){
        parent::__construct($settingsUtil, $imageGenerator);

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
        $imgdata_hdpi = $this->_imageGenerator->getImage($content, $options['font'], $options['size'], $options['color'], $options['offset'], $this->_hdpiFactor);
        
        // generate tag - only set the image height, auto-width is used!
        return sprintf(
            '<img height="%s" src="%s" srcset="%s 1x, %s 2x, %s 3x">',
            esc_attr($imgdata_ndpi[2]),
            esc_attr($imgdata_ndpi[0]),
            esc_attr($imgdata_ndpi[0]),
            esc_attr($imgdata_hdpi[0]),
            esc_attr($imgdata_hdpi[0])
        );
    }
}