/**
	Cryptex Settings/Admin Page
	Version: 4.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://www.a3non.org/go/cryptex
	License: MIT X11-License
	
	Copyright (c) 2010-2014, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
(function(){
	// initialize
	jQuery(document).ready(function(){
		// hide update message
		// --------------------------------------------------------
		var msg = jQuery('#setting-error-settings_updated');
		if (msg){
			msg.delay(1500).fadeOut(500);
		}
		
		// colorpicker
		// --------------------------------------------------------
		jQuery('.CryptexColorChooser').ColorPicker({
			onSubmit : function(hsb, hex, rgb, el){
				jQuery(el).val('#' + hex);
				jQuery(el).css('background-color', '#' + hex);
				jQuery(el).ColorPickerHide();
			},
			onBeforeShow : function(){
				jQuery(this).ColorPickerSetColor(this.value);
			}
		});
		
		// custom font path
		// --------------------------------------------------------
		jQuery('#cryptex-font-source').change(function(){
			if (this.value=='custom'){
				jQuery('#CryptexCustomFontpath').show();
			}else{
				jQuery('#CryptexCustomFontpath').hide();
			}
		}).change();
		
		// custom cache path
		// --------------------------------------------------------
		jQuery('#cryptex-cache-custom').change(function(){
			if(jQuery(this).is(":checked")) {
				jQuery('#CryptexCustomCachePath').show();
			}else{
				jQuery('#CryptexCustomCachePath').hide();
			}
		}).change();
		
		// EMail Address Autodetection
		// --------------------------------------------------------
		jQuery('#cryptex-email-autodetect').change(function(){
			if(jQuery(this).is(":checked")) {
				jQuery('#CryptexAutodetectionOptions').show();
			}else{
				jQuery('#CryptexAutodetectionOptions').hide();
			}
		}).change();
		
		// HDPI Settings
		// --------------------------------------------------------
		jQuery('#cryptex-hdpi-enabled').change(function(){
			if(jQuery(this).is(":checked")) {
				jQuery('#CryptexHdpiSettings').show();
			}else{
				jQuery('#CryptexHdpiSettings').hide();
			}
		}).change();		
		
		// Tabs/Sections
		// --------------------------------------------------------
		// container actions
		var currentTab = jQuery('#CryptexTabNav a:first-child');
		jQuery('#CryptexTabNav a').each(function(){
			// get current element
			var el = jQuery(this);
			
			// hide content container
			jQuery('#' + el.attr('data-tab')).hide();
			
			// click event
			el.click(function(){
				// remove highlight
				currentTab.removeClass('nav-tab-active');
				
				// hide container
				jQuery('#' + currentTab.attr('data-tab')).hide();
				
				// store current active tab
				currentTab = el;
				currentTab.addClass('nav-tab-active');
				
				// show container
				jQuery('#' + currentTab.attr('data-tab')).show();
			});
		});
		
		// show first container
		currentTab.click();
		
		// show navbar
		jQuery('#CryptexTabNav').show();
	});
})();
