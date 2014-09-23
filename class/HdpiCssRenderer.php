<?php

/**
	 High-DPI/Retina Rendering Engine - based on css media queries
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
		$imgdata_hdpi = $this->_imageGenerator->getImage($content, $options['font'], $options['size'], $options['color'], $options['offset'], $this->_hdpiFactor);
		
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

?>