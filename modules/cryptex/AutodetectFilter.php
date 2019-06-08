<?php
// EMail Address Autodetect Filter

namespace Cryptex;

class AutodetectFilter{
    
    // shortcode handler is used to process the filtered results
    private $_shortcodeHandler;
    
    // post/page IDs to exclude from filtering
    private $_excludeIDs = array();
    
    // the email address detection pattern
    private $_detectionPattern = '/\b([a-z0-9_\.+-]+@[\da-z\.-]+\.[a-z]{2,23})\b/i';


    public function __construct($settingsManager, $shorcodeHandler){
        // get IDs to exclude
        $eID = $settingsManager->getOption('email-autodetect-excludeid');
        
        // filter non numeric chars
        $eID = preg_replace('/[^0-9,]/', '', $eID);
                
        // convert it into array (split by "," speraator)
        $this->_excludeIDs = explode(',', $eID); 
        
        // store shortcode handler instance
        $this->_shortcodeHandler = $shorcodeHandler;

          // filter content ?
        if ($settingsManager->getOption('email-autodetect-content')){
            add_filter('the_content', array($this, 'filter'), 50, 1);
        }
        
        // filter excerpt ?
        if ($settingsManager->getOption('email-autodetect-excerpt')){
            add_filter('get_the_excerpt', array($this, 'filter'), 50, 1);
        }
        
        // filter comment text ?
        if ($settingsManager->getOption('email-autodetect-comments')){
            add_filter('get_comment_text', array($this, 'filterNoExclusion'), 50, 1);
        }
        
        // filter comment excerpt ?
        if ($settingsManager->getOption('email-autodetect-comments-excerpt')){
            add_filter('get_comment_excerpt', array($this, 'filterNoExclusion'), 50, 1);
        }
        
        // filter widget text ? 
        if ($settingsManager->getOption('email-autodetect-widget-text')){
            add_filter('widget_text', array($this, 'filterNoExclusion'), 50, 1);
        }
    }
    
    // wp the_content/the_excerpt callback
    public function filter($content){
        // exclude post/page from filtering ?
        if (in_array(get_the_ID(), $this->_excludeIDs)){
            return $content;
        }else{
            // regex to detect emails
            return preg_replace_callback($this->_detectionPattern, array($this, 'filterMatchCallback'), $content);
        }
    }
    
    // wp comment callback (no id exclusion!)
    public function filterNoExclusion($content){
        // regex to detect emails
        return preg_replace_callback($this->_detectionPattern, array($this, 'filterMatchCallback'), $content);
    }
    
    // regex callback
    public function filterMatchCallback($matches){
        // render email by shortcode handler
        return $this->_shortcodeHandler->cryptex(null, $matches[0], '');
    }
}