<?php
// Contextual Help Screens

namespace Cryptex;

class ContextualHelp{

    /**
     * Setup Help Screen
     */
    public static function settings(){
        // load screen
        $screen = get_current_screen();
    
        // shortcode help
        $screen->add_help_tab(array(
                'id' => 'cryptex_ch_shortcode',
                'title'    => __('Shortcodes'),
                'callback' => function(){
                    include(CRYPTEX_PLUGIN_PATH.'/views/help/'.'shortcodes.en_EN.phtml');
                }
        ));
        $screen->add_help_tab(array(
                'id' => 'cryptex_ch_shortcodeoptions',
                'title'    => __('Shortcode-Options'),
                'callback' => function(){
                    include(CRYPTEX_PLUGIN_PATH.'/views/help/'.'shortcode-options.en_EN.phtml');
                }
        ));
        
        // theme/php
        $screen->add_help_tab(array(
                'id' => 'cryptex_ch_themephp',
                'title'    => __('Theme/PHP'),
                'callback' => function(){
                    include(CRYPTEX_PLUGIN_PATH.'/views/help/'.'themephp.en_EN.phtml');
                }
        ));
        
        // sidebar
        $screen->set_help_sidebar(file_get_contents(CRYPTEX_PLUGIN_PATH.'/views/help/'.'sidebar.en_EN.html'));
    }
}