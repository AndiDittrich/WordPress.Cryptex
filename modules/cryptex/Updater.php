<?php
// Generic Plugin Updater - Backup/Restore Files. Wordpress will overwrite the COMPLETE plugin folder on update!

namespace Cryptex;

class Updater{
        // folders to restore
        private $_restoreFolders;
        private $_prefix = '';
        
        public function __construct($prefix, $restoreFolders=array()){
            // store informations
            $this->_restoreFolders = $restoreFolders;
            $this->_prefix = $prefix;
            
            // update/install events - well they are called on upgrading ANY plugin..but at this moment there is no better way..
            add_action('upgrader_pre_install', array($this, 'updateBackup'), 10, 0);
            add_action('upgrader_post_install', array($this, 'updateRestore'), 10, 0);
        }
        
        public function register($dir){
            $this->_restoreFolders[] = $dir;
        }
        
        public function updateBackup(){
            foreach ($this->_restoreFolders as $folder){
                // move files outside the plugin direcotry
                rename($folder, WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.$this->_prefix.'_backup_'.sha1($folder));
            }
        }
        
        public function updateRestore(){
            foreach ($this->_restoreFolders as $folder){
                // delete the NEW folder first -> problem on windows systems...
                if (is_dir($folder)){
                    rmdir($folder);
                }
            
                // move folder back
                rename(WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.$this->_prefix.'_backup_'.sha1($folder), $folder);
            }
        }
}