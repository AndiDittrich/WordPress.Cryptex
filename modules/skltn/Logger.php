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

// Generic Logging Facility

namespace Cryptex\skltn;

class Logger{
    
    
    // initialize global plugin config
    public function __construct($pluginConfig){
        // store default config
        $this->_defaultConfig = $pluginConfig->getDefaults();

        // store validators
        $this->_configValidators = $pluginConfig->getValidators();

        // retrieve config - add default key/values
        $this->_config = array_merge($this->_defaultConfig, get_option('cryptex-options', array()));
    }
 
    // update a single option
    public function setOption($key, $value){
        // config key exists (is in default config ?)
        if (isset($this->_config[$key])){
            // assign new value
            $this->_config[$key] = $value;

            // update config, apply prior validation
            update_option('cryptex-options', $this->_config);
        }
    }

    public function log($msg){

    }

    public function notice($msg){
        
    }

}
