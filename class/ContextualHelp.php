<?php
// Contextual Help Screens

namespace Cryptex;

class ContextualHelp{
    
    public function __construct($settingsUtil){
    }

    /**
     * Setup Help Screen
     */
    public function contextualHelp(){
        // load screen
        $screen = get_current_screen();
    
        // shortcode help
        $screen->add_help_tab(array(
                'id' => 'cryptex_ch_shortcode',
                'title'    => __('Shortcodes'),
                'callback' => array($this, 'shortcode')
        ));
        $screen->add_help_tab(array(
                'id' => 'cryptex_ch_shortcodeoptions',
                'title'    => __('Shortcode-Options'),
                'callback' => array($this, 'shortcodeoptions')
        ));
        
        // theme/php
        $screen->add_help_tab(array(
                'id' => 'cryptex_ch_themephp',
                'title'    => __('Theme/PHP'),
                'callback' => array($this, 'themephp')
        ));
        
        // sidebar
        $screen->set_help_sidebar(file_get_contents(CRYPTEX_PLUGIN_PATH.'/views/help/'.'sidebar.en_EN.html'));
    }
    
    public function shortcode(){
        include(CRYPTEX_PLUGIN_PATH.'/views/help/'.'shortcodes.en_EN.phtml');
    }
    
    public function shortcodeoptions(){
        include(CRYPTEX_PLUGIN_PATH.'/views/help/'.'shortcode-options.en_EN.phtml');
    }
    
    public function themephp(){
        include(CRYPTEX_PLUGIN_PATH.'/views/help/'.'themephp.en_EN.phtml');
    }
    
    
}