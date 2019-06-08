<?php
// GD lib based Image Generator
namespace Cryptex;

class ImageGenerator{

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

    // use font antialiasing ?
    private $_fontAntialiasing = false;

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

    // antialiasing supported by gd ?
    private $_antialiasingSupport = false;

    // fallback ttf font
    private $_ttfFallbackFont = 'liberation-fonts-ttf-2.00.1/LiberationSerif-Regular.ttf';

    public function __construct($settingsUtil, $cacheManager){

        // extract cache paths
        $this->_cachePath = $cacheManager->getCachePath();
        $this->_cacheURL = $cacheManager->getCacheUrl();

        // populate global options
        $this->_fontsize = $settingsUtil->getOption('font-size');
        $this->_fontcolor = $settingsUtil->getOption('font-color');
        $this->_salt = $settingsUtil->getOption('salt');
        $this->_fontfile = $settingsUtil->getOption('font-file');
        $this->_offsets[0] = $settingsUtil->getOption('offset-x');
        $this->_offsets[1] = $settingsUtil->getOption('offset-y');
        $this->_offsets[2] = $settingsUtil->getOption('offset-a');
        $this->_offsets[3] = $settingsUtil->getOption('offset-b');

        // antialiasing supported ?
        if ($settingsUtil->getOption('font-antialiasing') && function_exists('imageantialias')){
            $this->_antialiasingSupport = true;
        }

        // manual line-height ?
        $this->_lineheight = $settingsUtil->getOption('line-height');

        // font antialiasing ?
        $this->_fontAntialiasing = $settingsUtil->getOption('font-antialiasing');

        // try to load dimension cache
        if (($this->_imageSizeCache = get_transient('cryptex_imgsize')) === false){
            // cache has to be re-generated!
            $this->_imageSizeCache = array();
        }

        // GD lib installed ? prevent errors
        if (function_exists('gd_info')){
            $info = gd_info();

            // freetype enabled ?
            $this->_useFreetype = ($settingsUtil->getOption('font-renderer') == 'freetype') && isset($info['FreeType Support']);
        }
    }

    public function isFreeTypeEnabled(){
        return $this->_useFreetype;
    }

    // Store Image Dimensions
    public function updateCache(){
        // store data; 1day cache expire
        set_transient('cryptex_imgsize', $this->_imageSizeCache, DAY_IN_SECONDS);
    }

    public function getImage($txt, $font=null, $fontsize=null, $fontcolor=null, $offset=null, $scale=1, $cacheDimensions=true){
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

        // font antialiasing ? controlled by fontsize!
        $fontcolor = ($this->_fontAntialiasing ? $fontcolor : -$fontcolor);

        // high-dpi scaling
        $fontsize = $fontsize*$scale;
        $offset = array_map(function($o) use ($scale){
            return $o*$scale;
        }, $offset);

        // generate filename
        $configHash = sha1($scale.$font.$fontsize.$fontcolor.implode('.', $offset));
        $imagehash = \Cryptex\skltn\Hash::filename($this->_salt.sha1($txt.$this->_salt.$configHash));
        $filename = $imagehash.'.png';

        // generate image storage path
        $storagePath = $this->_cachePath.$filename;

        // try to load image dimensions
        $dim = (isset($this->_imageSizeCache[$imagehash]) ? $this->_imageSizeCache[$imagehash] : null);

        // cached version not available ? dimension not cached ? => generate new image
        if (!file_exists($storagePath) || ($dim === null && $cacheDimensions === true)){

            // ttf font file available ?
            if (is_file($font) && is_readable($font)){
                // use ttf based image
                $dim = $this->generateTTFImage($txt, $storagePath, $font, $fontsize, $fontcolor, $offset, $scale);
            }else{
                // use gd fallback font
                $dim = $this->generateFallbackImage($txt, $storagePath, $fontcolor, $offset, $scale);
            }

            // image generation error ?
            if ($dim === null){
                return null;
            }

            // store dimension
            if ($cacheDimensions === true){
                $this->_imageSizeCache[$imagehash] = $dim;
            }
        }

        // return cache file url and dimensions
        if ($cacheDimensions === true){
            return array($this->_cacheURL.$filename, $dim[0]/$scale, $dim[1]/$scale, $imagehash);

        // return cache file url without dimensions
        }else{
            return array($this->_cacheURL.$filename, 1, 1, $imagehash);
        }
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

        // pre-calculate box size
        $boundaries = ($this->_useFreetype) ? imageftbbox($fontsize, 0, $font, $txt) : imagettfbbox($fontsize, 0, $font, $txt);

        // valid box ?
        if ($boundaries === false){
            return null;
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

        // add dimension offsets
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