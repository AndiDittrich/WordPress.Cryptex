<?php

/**
	 Classic/Legacy Cryptex Rendering Engine - used by Cryptex v1-v3
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

class ClassicRenderer{
	
	// plugin config
	protected $_config;

	// image generator instance
	protected $_imageGenerator;
	
	public function __construct($settingsUtil, $imageGenerator){
		// store local plugin config
		$this->_config = $settingsUtil->getOptions();
		
		// create new generator instance
		$this->_imageGenerator = $imageGenerator;
	}
	
	public function render($content, $options = array()){
		// process nested shortcodes ?
		if ($this->_config['nestedShortcodes']){
			$content = do_shortcode($content);
		}
		
		// remove leading+trailing whitespaces!
		$content = trim($content);
		
		// email address ?
		$isEmail = (filter_var($content, FILTER_VALIDATE_EMAIL) !== false);
		
		// return value
		$html = ($this->_config['placeholder-enabled']) ? '<!--CTX!-->' : '';
		
		// security level override
		$securityLevel = ($options['security'] != null ? $options['security'] : $this->_config['security-level']);
			
		// get rel attribute - used to store encrypted email address
		$rel = ($this->_config['enable-hyperlink'] ? KeyShiftingEncoder::encode($content) : '');
		
		// shortcode-options set ?
		$styles = '';
		if ($options['color'] != null){
			$styles .= 'color: '.$options['color'].';';
		}
		if ($options['size'] != null){
			$s = $options['size'];
			$styles .= 'font-size: '.$s.';';
		}
		
		// rel attribute available ? - add it
		// add additional css classes
		if (strlen($rel)>0 && $isEmail){
			$html  .= sprintf('<span rel="%s" class="cryptex %s" style="%s">',  esc_attr($rel), esc_attr($this->_config['css-classes']),esc_attr($styles));
		}else{
			$html  .= sprintf('<span class="cryptex %s" style="%s">', esc_attr($this->_config['css-classes']), esc_attr($styles));
		}
		
		// email address ?
		if ($isEmail){
			// which security level should be used ?
			switch ($securityLevel){
				// direct output text - not recommended
				case '0':
					// replace @sign & dot
					$content = str_replace('@', $this->_config['email-divider'], $content);
					$content = str_replace('.', $this->_config['email-replacement-dot'], $content);
						
					$html .= esc_html($content);
					break;
		
					// single image
				case '1':
					// replace @sign
					$content = str_replace('@', $this->_config['email-divider'], $content);
					$content = str_replace('.', $this->_config['email-replacement-dot'], $content);
						
					// single image cryptex
					$html .= $this->getImage($content, $options);
					break;
		
					// multiple images, seperated by DOT and AT
				case '3':
					// split email
					$parts = explode('@', $content);
						
					// split parts by dots
					$p0 = explode('.', $parts[0]);
					$p1 = explode('.', $parts[1]);
						
					// generate images before @
					for ($i=0;$i<count($p0);$i++){
						// more than 1 image available ?
						if ($i+1<count($p0)){
							$html .= $this->getImage($p0[$i], $options);
							$html .= $this->getDivider($this->_config['email-replacement-dot'], $styles);
								// last element ?
						}else{
							$html .= $this->getImage($p0[$i], $options);
						}
					}
						
					// add @ sign
					$html .= $this->getDivider($this->_config['email-divider'], $styles);
						
					// generate images after @
					for ($i=0;$i<count($p1);$i++){
						// more than 1 image available ?
						if ($i+1<count($p1)){
							$html .= $this->getImage($p1[$i], $options);
							$html .= $this->getDivider($this->_config['email-replacement-dot'], $styles);
							
							// last element ?
						}else{
							$html .= $this->getImage($p1[$i], $options);
						}
					}
		
					break;
		
					// default : multipart image (2)
				case '2':
				default:
					// split email
					$content = str_replace('.', $this->_config['email-replacement-dot'], $content);
					$parts = explode('@', $content);
						
					// hybrid image
					$html .= $this->getImage($parts[0], $options);
					$html .= $this->getDivider($this->_config['email-divider'], $styles);
					$html .= $this->getImage($parts[1], $options);
					break;
			}
		}else{
			switch ($securityLevel){
				// direct output text - not recommended
				case '0':
					$html .= esc_html($content);
					break;
		
					// generate single image
				default:
					// single image cryptex
					$html .= $this->getImage($content, $options);
					break;
			}
		}
		
		// add closing span tag
		$html .= '</span>' . (($this->_config['placeholder-enabled']) ? '<!--/CTX!-->' : '');
		
		// return rendered shortcode
		return $html;
	}
	
	/**
	 * Generate Image Tag - including server side image generation
	 * @param unknown $content
	 * @param unknown $options
	 * @return string
	 */
	protected function getImage($content, $options){
		// generate image
		$imgdata = $this->_imageGenerator->getImage($content, $options['font'], $options['size'], $options['color'], $options['offset'], 1);
		
		// generate tag
		return sprintf(
				'<img src="%s" width="%d" height="%d" alt=".img" />',
				esc_attr($imgdata[0]), esc_attr($imgdata[1]), esc_attr($imgdata[2])
		);
	}
	
	/**
	 * Generate the divider (dots, @) inlcuding css overrides
	 * @param unknown $content
	 * @param unknown $options
	 */
	protected function getDivider($content, $styles){
		return '<span class="divider" style="'.esc_attr($styles).'">'.esc_html($content).'</span>';
	}
	
}

?>