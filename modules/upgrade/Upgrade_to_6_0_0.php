<?php

namespace Cryptex\Upgrade;

class Upgrade_to_6_0_0{

    // settings keys as of v1..v5
    private $_cryptexSettingsKeys = array(
        'custom-font-path',
        'enable-hyperlink',
        'email-divider',
        'email-replacement-dot',
        'embed-css',
        'embed-js',
        'font-file',
        'font-family',
        'font-size',
        'line-height',
        'font-color',
        'font-antialiasing',
        'font-renderer',
        'security-level',
        'hdpi-enabled',
        'hdpi-factor',
        'hdpi-renderer',
        'placeholder-enabled',
        'css-prefix',
        'salt',
        'css-classes',
        'font-source',
        'shortcode-email',
        'show-full-paths',
        'offset-a',
        'offset-b',
        'offset-x',
        'offset-y',
        'translation-enabled',
        'email-autodetect',
        'email-autodetect-content',
        'email-autodetect-excerpt',
        'email-autodetect-comments',
        'email-autodetect-comments-excerpt',
        'email-autodetect-widget-text',
        'email-autodetect-excludeid',
        'nestedShortcodes',
        'cache-custom',
        'cache-path',
        'cache-url'
    );

    public function __construct(){
    }

    public function run($currentVersion, $newVersion){
        // plugin config instance
        $pluginConfig = new \Cryptex\skltn\PluginConfig();

        // plugin settings manager
        $settingsManager = new \Cryptex\skltn\SettingsManager($pluginConfig);

        // existing plugin config
        $oldPluginConfig = array();

        // load existing plugin settings
        foreach ($this->_cryptexSettingsKeys as $key){
            // get option by key
            $value = get_option('cryptex-'.$key, null);

            // option set ? otherwise ignore it
            if ($value !== null){
                $oldPluginConfig[$key] = $value;
            }
        }

        // validate settings
        $newConfigValues = $settingsManager->validateSettings($oldPluginConfig);

        // store settings in new format
        $settingsManager->setOptions($newConfigValues);
    }
}