<?php

/**
	 High-DPI/Retina Rendering Engine - based on srcset
	 Version: 1.0
	 Author: Andi Dittrich
	 Author URI: http://andidittrich.de
	 Plugin URI: http://andidittrich.de/go/cryptex
	 License: MIT X11-License
	
	 Copyright (c) 2013-2015, Andi Dittrich
	
	 Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
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

?>