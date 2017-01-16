<?php
/*  AUTO GENERATED FILE - DO NOT EDIT !!
    WP-SKELEKTON | MIT X11 License | https://github.com/AndiDittrich/WP-Skeleton
    ------------------------------------
    Plugin Config Defaults
*/
namespace Cryptex\skltn;

class PluginConfig{
    
    // config keys with default values
    private $_defaultConfig = array(
        'custom-font-path' => '',
        'enable-hyperlink' => true,
        'email-divider' => '@',
        'email-replacement-dot' => '.',
        'embed-css' => true,
        'embed-js' => true,
        'font-file' => 'LiberationSans-Regular.ttf',
        'font-family' => 'inherit',
        'font-size' => '12px',
        'line-height' => 0,
        'font-color' => '#000000',
        'font-antialiasing' => true,
        'font-renderer' => 'freetype',
        'security-level' => 2,
        'hdpi-enabled' => false,
        'hdpi-factor' => 3,
        'hdpi-renderer' => 'css',
        'placeholder-enabled' => true,
        'css-prefix' => '',
        'salt' => 'ABCDEF1234567890',
        'css-classes' => '',
        'font-source' => 'system+plugin',
        'shortcode-email' => true,
        'shortcode-telephone' => true,
        'show-full-paths' => false,
        'offset-a' => 2,
        'offset-b' => 2,
        'offset-x' => 2,
        'offset-y' => 0,
        'translation-enabled' => true,
        'email-autodetect' => false,
        'email-autodetect-content' => true,
        'email-autodetect-excerpt' => true,
        'email-autodetect-comments' => true,
        'email-autodetect-comments-excerpt' => true,
        'email-autodetect-widget-text' => true,
        'email-autodetect-excludeid' => '',
        'nestedShortcodes' => false,
        'cache-custom' => false,
        'cache-path' => '',
        'cache-url' => ''
    );

    // validation
    private $_validators = array(
        'custom-font-path' => 'string',
        'enable-hyperlink' => 'boolean',
        'email-divider' => 'string',
        'email-replacement-dot' => 'string',
        'embed-css' => 'boolean',
        'embed-js' => 'boolean',
        'font-file' => 'string',
        'font-family' => 'string',
        'font-size' => 'string',
        'line-height' => 'int',
        'font-color' => 'string',
        'font-antialiasing' => 'boolean',
        'font-renderer' => 'string',
        'security-level' => 'int',
        'hdpi-enabled' => 'boolean',
        'hdpi-factor' => 'int',
        'hdpi-renderer' => 'string',
        'placeholder-enabled' => 'boolean',
        'css-prefix' => 'string',
        'salt' => 'string',
        'css-classes' => 'string',
        'font-source' => 'string',
        'shortcode-email' => 'boolean',
        'shortcode-telephone' => 'boolean',
        'show-full-paths' => 'boolean',
        'offset-a' => 'int',
        'offset-b' => 'int',
        'offset-x' => 'int',
        'offset-y' => 'int',
        'translation-enabled' => 'boolean',
        'email-autodetect' => 'boolean',
        'email-autodetect-content' => 'boolean',
        'email-autodetect-excerpt' => 'boolean',
        'email-autodetect-comments' => 'boolean',
        'email-autodetect-comments-excerpt' => 'boolean',
        'email-autodetect-widget-text' => 'boolean',
        'email-autodetect-excludeid' => 'string',
        'nestedShortcodes' => 'boolean',
        'cache-custom' => 'boolean',
        'cache-path' => 'string',
        'cache-url' => 'string'
    );

    // get the default plugin config
    public function getDefaults(){
        return $this->_defaultConfig;
    }

    // get all validators
    public function getValidators(){
        return $this->_validators;
    }

    // get corresponding validator in case its available
    public function getValidator($key){
        if (isset($this->_validators[$key])){
            return $this->_validators[$key];
        }else{
            return null;
        }
    }

    // add dynamics key/value/validator pairs
    public function add($key, $value, $validator = null){
        // add key/value pair
        $this->_defaultConfig[$key] = $value;

        // validator given ?
        if ($validator){
            $this->_validators[$key] = $validator;
        }
    }
}