/**
	Plugin Name: CRYPTEX
	Plugin URI: https://github.com/AndiDittrich/WordPress.Cryptex
	Description: EMAIL OBFUSCATOR
	Version: 6.0
	Author: Andi Dittrich
	Author URI: https://andidittrich.de/
	License: MIT X11-License
	
	Copyright (c) 2010-2016, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
(function(_window, _document, ck){
    'use strict';

    var ctx = _window.Cryptex ={
        // get cryptex elements and add onclick event
        process: function(h){

            var e = _document.getElementsByTagName('span');
            for (var i=0;i<e.length;i++){
                // variable scope
                (function(r, c){

                    // attributes set ?
                    if (c.indexOf('cryptex') != -1 && r.length > 5){
                        e[i].onclick = function(){
                            h(r);
                            return false;
                        };
                    }
                })(e[i].getAttribute('rel') || '', e[i].getAttribute('class') || '');
            }
        },

        decode: function(hash){
            var origdata = '';
            var crypt = [];
            var protocols = ['', 'mailto:', 'tel:'];

            // convert hex
            for (var i=0;i<hash.length;i=i+2){
                var tmp = hash.charAt(i) + hash.charAt(i+1);
                crypt.push(parseInt(tmp.toUpperCase(), 16));
            }

            // expand key
            var key = (new Array(Math.ceil(crypt.length/ck.length)+1)).join(ck);

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

            // mail or tel ?
            var payload = origdata.substr(2);
            var protocolNumber = parseInt(origdata.substr(0, 1));

            // get protocol
            if (protocols[protocolNumber]){
                return protocols[protocolNumber] + payload;
            }else{
                return payload;
            }
        }
    };

    // delay init - async
    _window.setTimeout(function(){
        ctx.process(function(hash){
            location.href = ctx.decode(hash);
        });
    }, 100);
})();
