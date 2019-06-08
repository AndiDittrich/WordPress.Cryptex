<?php

// High-DPI/Retina Rendering Engine - based on srcset
namespace Cryptex;

use Cryptex\skltn\HtmlUtil;

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
        
        // origin image
        $originImage = null;

        // srcset entries
        $srcset = [];

        // generate images
        for ($i=1; $i<=$this->_hdpiFactor; $i++){
            // generate image
            // just cache the dimensions for the origin image!
            $img = $this->_imageGenerator->getImage(
                $content, 
                $options['font'], 
                $options['size'], 
                $options['color'], 
                $options['offset'], 
                $i,
                ($i===1)
            );
            
            // origin ?
            if ($i === 1){
                $originImage = $img;
            }

            // generate srcset entry
            $srcset[] = $img[0].' '.$i.'x';
        }

        // html tag
        return HtmlUtil::generateTag('img', array(
            'width' => $originImage[1],
            'height' => $originImage[2],
            'src' => $originImage[0],
            'srcset' => implode(', ', $srcset),
            'alt' => 'img'
        ));
    }
}