<?php
// ---------------------------------------------------------------------------------------------------------------
// -- WP-SKELETON AUTO GENERATED FILE - DO NOT EDIT !!!
// --
// -- Copyright (c) 2016-2019 Andi Dittrich
// -- https://github.com/AndiDittrich/WP-Skeleton
// --
// ---------------------------------------------------------------------------------------------------------------
// --
// -- This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
// -- If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
// --
// ---------------------------------------------------------------------------------------------------------------

// Plugin Config Defaults

namespace Cryptex\skltn;

class PluginConfig{
    
    // config keys with default values
    private $_defaultConfig = array(
        'embed-css' => true,
        'embed-js' => true,
        'salt' => 'ABCDEF1234567890',
        'translation-enabled' => true,
        'css-prefix' => '',
        'css-classes' => '',
        'hdpi-enabled' => false,
        'hdpi-factor' => 3,
        'hdpi-renderer' => 'css',
        'custom-font-path' => '',
        'font-source' => 'plugin',
        'show-full-paths' => false,
        'font-color' => '#000000',
        'font-antialiasing' => true,
        'font-renderer' => 'freetype',
        'enable-hyperlink' => true,
        'email-divider' => '@',
        'email-replacement-dot' => '.',
        'security-level' => 2,
        'font-file' => 'LiberationSans-Regular.ttf',
        'font-family' => 'inherit',
        'font-size' => '12px',
        'line-height' => 20,
        'offset-a' => 4,
        'offset-b' => 4,
        'offset-x' => 2,
        'offset-y' => 0,
        'shortcode-email' => true,
        'shortcode-telephone' => true,
        'shortcode-cryptex' => true,
        'nestedShortcodes' => false,
        'email-autodetect' => false,
        'email-autodetect-content' => true,
        'email-autodetect-excerpt' => true,
        'email-autodetect-comments' => true,
        'email-autodetect-comments-excerpt' => true,
        'email-autodetect-widget-text' => true,
        'email-autodetect-excludeid' => '',
        'cache-custom' => false,
        'cache-path' => '',
        'cache-url' => '',
        'placeholder-enabled' => false
    );

    // validation
    private $_validators = array(
        'embed-css' => 'boolean',
        'embed-js' => 'boolean',
        'salt' => 'string',
        'translation-enabled' => 'boolean',
        'css-prefix' => 'string',
        'css-classes' => 'string',
        'hdpi-enabled' => 'boolean',
        'hdpi-factor' => 'int',
        'hdpi-renderer' => 'string',
        'custom-font-path' => 'string',
        'font-source' => 'string',
        'show-full-paths' => 'boolean',
        'font-color' => 'string',
        'font-antialiasing' => 'boolean',
        'font-renderer' => 'string',
        'enable-hyperlink' => 'boolean',
        'email-divider' => 'string',
        'email-replacement-dot' => 'string',
        'security-level' => 'int',
        'font-file' => 'string',
        'font-family' => 'string',
        'font-size' => 'string',
        'line-height' => 'int',
        'offset-a' => 'int',
        'offset-b' => 'int',
        'offset-x' => 'int',
        'offset-y' => 'int',
        'shortcode-email' => 'boolean',
        'shortcode-telephone' => 'boolean',
        'shortcode-cryptex' => 'boolean',
        'nestedShortcodes' => 'boolean',
        'email-autodetect' => 'boolean',
        'email-autodetect-content' => 'boolean',
        'email-autodetect-excerpt' => 'boolean',
        'email-autodetect-comments' => 'boolean',
        'email-autodetect-comments-excerpt' => 'boolean',
        'email-autodetect-widget-text' => 'boolean',
        'email-autodetect-excludeid' => 'string',
        'cache-custom' => 'boolean',
        'cache-path' => 'string',
        'cache-url' => 'string',
        'placeholder-enabled' => 'boolean'
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