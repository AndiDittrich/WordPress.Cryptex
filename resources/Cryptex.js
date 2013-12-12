/**
	Plugin Name: CRYPTEX
	Plugin URI: http://www.a3non.org/go/cryptex
	Description: EMAIL OBFUSCATOR
	Version: 3.0
	Author: Andi Dittrich
	Author URI: http://www.a3non.org
	License: MIT X11-License
	
	Copyright (c) 2010-2013, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
// action - onclick event handler
var cryptex_action_handler = function(hash){
	var origdata = '';
	var crypt = new Array();

	// convert hex
	for (var i=0;i<hash.length;i=i+2){
		var tmp = hash.charAt(i) + hash.charAt(i+1);
		crypt.push(parseInt(tmp.toUpperCase(), 16));		
	}

	// expand key		
	var key = CRYPTEX_KEY;
	for (var j=0;j<=(crypt.length/CRYPTEX_KEY.length);j++){
		key += CRYPTEX_KEY;			
	}
	
	// decode cryptex
	for (var i=0;i<crypt.length;i=i+2){
		var a = crypt[i];
		var c = crypt[i+1];
		var k = key.charCodeAt(i/2);
		
		if (i/2%2==0){
			if (c==49){					
				origdata += String.fromCharCode(a-k);
			}else{
				origdata += String.fromCharCode(a-k-255);
			}				
		}else{
			if (c==49){
				origdata += String.fromCharCode(a+k);
			}else{
				origdata += String.fromCharCode(-a+k);
			}
		}
	}

	location.href = 'mailto:' + origdata;
};

// framework dependent event handling
// mootools
if (typeof MooTools!="undefined"){
	window.addEvent('domready', function(){
		$$('span.cryptex').addEvent('click', function(){
			if (this.get('rel')){
				cryptex_action_handler(this.get('rel'));
			}
		});
	});
	
// jquery	
}else if (typeof jQuery!="undefined"){
	jQuery(document).ready(function(){
		jQuery('span.cryptex').click(function(){
			if (jQuery(this).attr('rel')){
				cryptex_action_handler(jQuery(this).attr('rel'));
			}
		});
	});
	


// generic fallback
}else if (typeof MooTools=="undefined" && typeof jQuery=="undefined"){
	window.setTimeout(function(){
		var els = document.getElementsByTagName('span');
		for (var i=0;i<els.length;i++){
			if (els[i].getAttribute('class') && els[i].getAttribute('class').indexOf('cryptex') != -1){
				if (els[i].getAttribute('rel')){
					els[i].onclick = (function(){
						cryptex_action_handler(this.getAttribute('rel'));
					});
				}
			}
		}
	}, 1000);
};
