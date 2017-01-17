<?php
/**
    GD lib based Image Generator
    Version: 2.1
    Author: Andi Dittrich
    Author URI: http://andidittrich.de
    Plugin URI: http://andidittrich.de/go/cryptex
    License: MIT X11-License

    Copyright (c) 2010-2016, Andi Dittrich

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Cryptex;

class ImageGenerator{

    // plugin options
    private $_options;

    // selected fontsize in px
    private $_fontsize;

    // selected lineheight
    private $_lineheight = 0;

    // fontcolor - decimal value!
    private $_fontcolor;

    // selected ttf font (full path)
    private $_fontfile;

    // current used salt
    private $_salt;

    // use freetype rendering
    private $_useFreetype = false;

    // image offsets A,B,X,Y
    // X:width offset
    // Y:height offset
    // A:text position x offset
    // B:text position y offset
    private $_offsets = array(0, 0, 0, 0);

    // cache url + path
    private $_cachePath;
    private $_cacheURL;

    // image size storage
    private $_imageSizeCache;

    // antialiasing supported ?
    private $_antialiasingSupport = false;

    public function __construct($settingsUtil, $cacheManager){
        $this->_options = $settingsUtil->getOptions();

        // extract cache paths
        $this->_cachePath = $cacheManager->getCachePath();
        $this->_cacheURL = $cacheManager->getCacheUrl();

        // populate global options
        $this->_fontsize = $this->_options['font-size'];
        $this->_fontcolor = $this->_options['font-color'];
        $this->_salt = $this->_options['salt'];
        $this->_fontfile = $this->_options['font-file'];
        $this->_offsets[0] = intval($this->_options['offset-x']);
        $this->_offsets[1] = intval($this->_options['offset-y']);
        $this->_offsets[2] = intval($this->_options['offset-a']);
        $this->_offsets[3] = intval($this->_options['offset-b']);

        // antialiasing supported ?
        if ($this->_options['font-antialiasing'] && function_exists('imageantialias')){
            $this->_antialiasingSupport = true;
        }

        // manual line-height ?
        if (strlen(trim($this->_options['line-height'])) > 0){
            $this->_lineheight = intval($this->_options['line-height']);
        }else{
            $this->_lineheight = 0;
        }

        // try to load dimension cache
        if (($this->_imageSizeCache = get_transient('cryptex_imgsize')) === false){
            // cache has to be re-generated!
            $this->_imageSizeCache = array();
        }

        // GD lib installed ? prevent errors
        if (function_exists('gd_info')){
            $info = gd_info();

            // freetype enabled ?
            $this->_useFreetype = ($this->_options['font-renderer'] == 'freetype') && $info['FreeType Support'];
        }
    }


    public function isFreeTypeEnabled(){
        return $this->_useFreetype;
    }

    /**
     * Store Image Dimensions
     */
    public function updateCache(){
        // store data; 1day cache expire
        set_transient('cryptex_imgsize', $this->_imageSizeCache, DAY_IN_SECONDS);
    }

    public function getImage($txt, $font=null, $fontsize=null, $fontcolor=null, $offset=null, $scale=1){
        // check for gd lib
        if (!function_exists('gd_info')){
            return null;
        }

        // merge global options
        $font = ($font === null ? $this->_fontfile : $font);
        $fontsize = $this->parseFontSize($fontsize === null ? $this->_fontsize : $fontsize);
        $fontcolor = ($fontcolor === null ? $this->_fontcolor : $fontcolor);
        $offset = (($offset === null || count($offset) != 4) ? $this->_offsets : $offset);

        // parse font color
        $fontcolor = hexdec($fontcolor);

        // antialiasing
        $fontcolor = ($this->_options['font-antialiasing'] ? $fontcolor : -$fontcolor);

        // high-dpi scaling
        $fontsize = $fontsize*$scale;
        $offset = array_map(function($o) use ($scale){
            return $o*$scale;
        }, $offset);

        // generate filename
        $configHash = sha1($scale.$font.$fontsize.$fontcolor.implode('.', $offset));
        $imagehash = \Cryptex\skltn\Hash::filename($this->_salt.sha1($txt.$this->_salt.$configHash));
        $filename = $imagehash.'.png';

        // generate storage path
        $storagePath = $this->_cachePath.$filename;

        // try to load image dimensions
        $dim = (isset($this->_imageSizeCache[$imagehash]) ? $this->_imageSizeCache[$imagehash] : null);

        // cached version not available ? // generate new image
        if (!file_exists($storagePath) || $dim==null){

            // ttf font file available ?
            if (is_file($font) && is_readable($font)){
                // use ttf based image
                $dim = $this->generateTTFImage($txt, $storagePath, $font, $fontsize, $fontcolor, $offset, $scale);
            }else{
                // use gd fallback font
                $dim = $this->generateFallbackImage($txt, $storagePath, $fontcolor, $offset, $scale);
            }

            // store dimension
            $this->_imageSizeCache[$imagehash] = $dim;
        }

        // return cache file url and dimensions
        return array($this->_cacheURL.$filename, $dim[0]/$scale, $dim[1]/$scale, $imagehash);
    }

    // gd embedded image
    private function generateFallbackImage($txt, $filename, $fontcolor, $offset, $scale){
        // FALLBACK
        $width = (imagefontwidth(3)*strlen($txt)) + 2 + $offset[0];
        $height = imagefontheight(3) + 7 + $offset[1];

        // create new image
        $im = imagecreatetruecolor($width, $height);

        // transparent background
        $tcolor = imagecolorallocatealpha($im, 0, 0, 0, 127);
        imagefill($im, 0, 0, $tcolor);
        imagesavealpha($im, true);

        // enable AA
        if ($this->_antialiasingSupport) {
            imageantialias($im, true);
        }

        // create text
        imagestring($im, 3, $offset[2], $offset[3], $txt, $fontcolor);

        // store image
        imagepng($im, $filename);

        // destroy
        imagedestroy($im);

        // return image dimensions
        return array($width, $height);
    }

    // true type font based image
    private function generateTTFImage($txt, $filename, $font, $fontsize, $fontcolor, $offset, $scale){
        // calculate size
        $boundaries = array(0, 0, 0, 0, 0, 0, 0, 0);
        if ($this->_useFreetype){
            $boundaries = imageftbbox($fontsize, 0, $font, $txt);
        }else{
            $boundaries = imagettfbbox($fontsize, 0, $font, $txt);
        }

        // calculate boundaries
        $min_x = min( array($boundaries[0], $boundaries[2], $boundaries[4], $boundaries[6]) );
        $max_x = max( array($boundaries[0], $boundaries[2], $boundaries[4], $boundaries[6]) );
        $width  = ( $max_x - $min_x );
        // $min_y = min( array($boundaries[1], $boundaries[3], $boundaries[5], $boundaries[7]) );
        // $max_y = max( array($boundaries[1], $boundaries[3], $boundaries[5], $boundaries[7]) );
        // $height = ( $max_y - $min_y );

        // manual height or automatic height based on font size ! - solves problems with font base lines..
        $height = ($this->_lineheight == 0 ? $this->pt2px($fontsize) : $this->_lineheight* $scale);

        // dimension offsets
        $width = $width + $offset[0];
        $height = $height + $offset[1];

        // create new image
        $im = imagecreatetruecolor($width, $height);

        // transparent background
        $tcolor = imagecolorallocatealpha($im, 0, 0, 0, 127);
        imagefill($im, 0, 0, $tcolor);
        imagesavealpha($im, true);

        // enable AA
        if ($this->_antialiasingSupport) {
            imageantialias($im, true);
        }

        // create text - use calculated pt fontsize value
        // calculate font-baseline includung offset
        if ($this->_useFreetype){
            imagefttext($im, $fontsize, 0, $offset[2], $height - $offset[3], $fontcolor, $font, $txt);
        }else{
            imagettftext($im, $fontsize, 0, $offset[2], $height - $offset[3], $fontcolor, $font, $txt);
        }

        // store image
        imagepng($im, $filename);

        // destroy
        imagedestroy($im);

        // return image dimensions
        return array($width, $height);
    }

    /*
     * GD2 Requires font-size in pt
     * @param unknown $pxValue
     */
    private function parseFontSize($v){
        // strip whitespaces
        $v = trim($v);

        // pt or px given ?
        $isPtValue = (substr($v, -2) === 'pt');

        // pt value ?
        if ($isPtValue){
            return floatval($v);
        }else{
            // convert px value to pt
            return (floatval($v)/1.333);
        }
    }

    /*
     * Convert PT to PX value
     * @param String/Float $v
     * @return number
     */
    private function pt2px($v){
        return intval(floatval($v)*1.333);
    }
}