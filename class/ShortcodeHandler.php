<?php
/**
	Shortcode Handler Class
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://www.a3non.org/go/enlighterjs
	License: MIT X11-License
	
	Copyright (c) 2013, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Cryptex;

class ShortcodeHandler{
	
	// stores the plugin config
	private $_config;
	
	// store registered shortcodes
	private $_registeredShortcodes;
	
	// image generator instance
	private $_imageGenerator;
	
	
	public function __construct($settingsUtil, $registeredShortcodes){
		// store local plugin config
		$this->_config = $settingsUtil->getOptions();
		
		// store registered shortcodes
		$this->_registeredShortcodes = $registeredShortcodes;
		
		// create new generator instance
		$this->_imageGenerator = new ImageGenerator($this->_config);
		
		// add texturize filter
		add_filter('no_texturize_shortcodes', array($this, 'texturizeHandler'));
	}
	
	// handle cryptex shortcode
	public function cryptex($atts=NULL, $content='', $code=''){
		// email address ?
		$isEmail = (filter_var($content, FILTER_VALIDATE_EMAIL) !== false);
	
		// return value
		$html = '';
		
		// remove whitespaces!
		$content = trim($content);
	
		// get rel attribute - used to store encrypted email address
		$rel = ($this->_config['enable-hyperlink'] ? KeyShiftingEncoder::encode($content) : '');

		// rel attribute available ? - add it
		// add additional css classes
		if (strlen($rel)>0 && $isEmail){
			$html  = sprintf('<span class="cryptex %s" rel="%s">', esc_attr($this->_config['css-classes']), esc_attr($rel));
		}else{
			$html  = sprintf('<span class="cryptex %s">', esc_attr($this->_config['css-classes']));
		}
		
		// email address ?
		if ($isEmail){
			// which security level should be used ?		
			switch ($this->_config['security-level']){
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
					$html .= sprintf(
							'<img src="%s" alt="hidden" />',
							esc_attr($this->_imageGenerator->getImage($content))
					);
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
							$html .= sprintf(
									'<img src="%s" alt="hidden" /><span class="divider">%s</span>',
									esc_attr($this->_imageGenerator->getImage($p0[$i])),
									$this->_config['email-replacement-dot']);
						// last element ?	
						}else{
							$html .= sprintf('<img src="%s" alt="hidden" />', esc_attr($this->_imageGenerator->getImage($p0[$i])));
						}
					}
					
					// add @ sign
					$html .= sprintf('<span class="divider">%s</span>',	$this->_config['email-divider']);
					
					// generate images after @
					for ($i=0;$i<count($p1);$i++){
						// more than 1 image available ?
						if ($i+1<count($p1)){
							$html .= sprintf(
									'<img src="%s" alt="hidden" /><span class="divider">%s</span>',
									esc_attr($this->_imageGenerator->getImage($p1[$i])),
									$this->_config['email-replacement-dot']);
							// last element ?
						}else{
							$html .= sprintf('<img src="%s" alt="hidden" />', esc_attr($this->_imageGenerator->getImage($p1[$i])));
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
					$html .= sprintf(
							'<img src="%s" alt="hidden" /><span class="divider">%s</span><img src="%s" alt="hidden" />',
							esc_attr($this->_imageGenerator->getImage($parts[0])),
							$this->_config['email-divider'],
							esc_attr($this->_imageGenerator->getImage($parts[1]))
					);
					break;					
			}
		}else{
			switch ($this->_config['security-level']){
				// direct output text - not recommended
				case '0':
					$html .= esc_html($content);
					break;

				// generate single image	
				default:
					// single image cryptex
					$html .= sprintf(
							'<img src="%s" alt="hidden" />',
							esc_attr($this->_imageGenerator->getImage($content))
					);
					break;
			}
		}
		
		// add closing span tag
		$html .= '</span>';
	
		// return rendered shortcode
		return $html;
	}
	
	/**
	 * Removes wordpress auto-texturize handler from used shortcodes
	 * @param Array $shortcodes
	 */
	public function texturizeHandler($shortcodes) {
		return array_merge($shortcodes, $this->_registeredShortcodes);
	}	
}

?>