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

var cryptex_action_handler=function(e){var h="";var o=new Array();for(var f=0;f<e.length;f=f+2){var g=e.charAt(f)+e.charAt(f+1);o.push(parseInt(g.toUpperCase(),16))}var n=CRYPTEX_KEY;for(var d=0;d<=(o.length/CRYPTEX_KEY.length);d++){n+=CRYPTEX_KEY}for(var f=0;f<o.length;f=f+2){var m=o[f];var l=o[f+1];var b=n.charCodeAt(f/2);if(f/2%2==0){if(l==49){h+=String.fromCharCode(m-b)}else{h+=String.fromCharCode(m-b-255)}}else{if(l==49){h+=String.fromCharCode(m+b)}else{h+=String.fromCharCode(-m+b)}}}location.href="mailto:"+h};if(typeof MooTools!="undefined"){window.addEvent("domready",function(){$$("span.cryptex").addEvent("click",function(){if(this.get("rel")){cryptex_action_handler(this.get("rel"))}})})}else{if(typeof jQuery!="undefined"){jQuery(document).ready(function(){jQuery("span.cryptex").click(function(){if(jQuery(this).attr("rel")){cryptex_action_handler(jQuery(this).attr("rel"))}})})}else{if(typeof MooTools=="undefined"&&typeof jQuery=="undefined"){window.setTimeout(function(){var b=document.getElementsByTagName("span");for(var a=0;a<b.length;a++){if(b[a].getAttribute("class")&&b[a].getAttribute("class").indexOf("cryptex")!=-1){if(b[a].getAttribute("rel")){b[a].onclick=(function(){cryptex_action_handler(this.getAttribute("rel"))})}}}},1000)}}};