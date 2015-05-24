/**
	Cryptex Settings/Admin Page
	Version: 5.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://www.a3non.org/go/cryptex
	License: MIT X11-License
	
	Copyright (c) 2010-2015, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

// initialize
jQuery(function(jq){
    // fetch console object
    var c = window.console || {};

    // show developer message
    if (c.info){
        c.info('You like to look under the hood? Cryptex is OpenSource, you are welcome to contribute! https://github.com/AndiDittrich/WordPress.Cryptex');
    }

    // hide update message
    // --------------------------------------------------------
    var msg = jq('#setting-error-settings_updated');
    if (msg){
        msg.delay(1500).fadeOut(500);
    }

    // colorpicker
    // --------------------------------------------------------
    jq('.CryptexColorChooser').ColorPicker({
        onSubmit : function(hsb, hex, rgb, el){
            jq(el).val('#' + hex);
            jq(el).css('background-color', '#' + hex);
            jq(el).ColorPickerHide();
        },
        onBeforeShow : function(){
            jq(this).ColorPickerSetColor(this.value);
        }
    });

    // custom font path
    // --------------------------------------------------------
    jq('#cryptex-font-source').change(function(){
        if (this.value=='custom'){
            jq('#CryptexCustomFontpath').show();
        }else{
            jq('#CryptexCustomFontpath').hide();
        }
    }).change();

    // custom cache path
    // --------------------------------------------------------
    jq('#cryptex-cache-custom').change(function(){
        if(jq(this).is(":checked")) {
            jq('#CryptexCustomCachePath').show();
        }else{
            jq('#CryptexCustomCachePath').hide();
        }
    }).change();

    // EMail Address Autodetection
    // --------------------------------------------------------
    jq('#cryptex-email-autodetect').change(function(){
        if(jq(this).is(":checked")) {
            jq('#CryptexAutodetectionOptions').show();
        }else{
            jq('#CryptexAutodetectionOptions').hide();
        }
    }).change();

    // HDPI Settings
    // --------------------------------------------------------
    jq('#cryptex-hdpi-enabled').change(function(){
        if(jq(this).is(":checked")) {
            jq('#CryptexHdpiSettings').show();
        }else{
            jq('#CryptexHdpiSettings').hide();
        }
    }).change();

    // Tabs/Sections
    // --------------------------------------------------------
    // try to restore last tab
    var lastActiveTab = jq.cookie('cryptex-tab');

    // container actions
    var currentTab = (lastActiveTab ? jq("#CryptexTabNav a[data-tab='" + lastActiveTab + "']") : jq('#CryptexTabNav a:first-child'));
    jq('#CryptexTabNav a').each(function(){
        // get current element
        var el = jq(this);

        // hide content container
        jq('#' + el.attr('data-tab')).hide();

        // click event
        el.click(function(){
            // remove highlight
            currentTab.removeClass('nav-tab-active');

            // hide container
            jq('#' + currentTab.attr('data-tab')).hide();

            // store current active tab
            currentTab = el;
            currentTab.addClass('nav-tab-active');

            // show container
            jQuery('#' + currentTab.attr('data-tab')).show();

            // store current tab as cookie - 1 day lifetime
            jq.cookie('cryptex-tab', currentTab.attr('data-tab'), { expires: 1 });
        });
    });

    // show first container
    currentTab.click();

    // show navbar
    jq('#CryptexTabNav').show();
});
