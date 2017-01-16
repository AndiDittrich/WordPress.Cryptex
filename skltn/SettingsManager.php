<?php
/*  AUTO GENERATED FILE - DO NOT EDIT !!
    WP-SKELEKTON | MIT X11 License | https://github.com/AndiDittrich/WP-Skeleton
    ------------------------------------
    Manages set plugin settings/options
*/
namespace Cryptex\skltn;

class SettingsManager{
    
    // local config storage
    private $_config = array();

    // default values
    private $_defaultConfig = array();

    // validators
    private $_configValidators = array();
    
    // initialize global plugin config
    public function __construct($pluginConfig){
        // store default config
        $this->_defaultConfig = $pluginConfig->getDefaults();

        // store validators
        $this->_configValidators = $pluginConfig->getValidators();

        // retrieve config - add default key/values
        $this->_config = array_merge($this->_defaultConfig, get_option('cryptex-options', array()));
    }
    
    // register settings
    public function registerSettings(){
        register_setting('cryptex-settings-group', 'cryptex-options', array($this, 'validateSettings'));
    }

    // sanitize callback
    public function validateSettings($settings){

        // is array ? if not invalid data is passed to the function, use secure default values!
        if (!is_array($settings)){
            return $this->_defaultConfig;
        }

        // new values
        $filteredValues = array();

        // filter values
        foreach ($this->_defaultConfig as $key => $value){

            // key exists ?
            if (isset($settings[$key])){

                // extract value
                $v = $settings[$key];

                // invalid value ?
                if (!is_scalar($v)){
                    // use defaults
                    $filteredValues[$key] = $value;
                    continue;
                }

                // strip whitespaces
                if (is_string($v)){
                    $v = trim($v);
                }

                // validator available ?
                if (isset($this->_configValidators[$key])){
                    // get validator type
                    $validator = $this->_configValidators[$key];

                    // boolean value ?
                    if ($validator == 'boolean'){
                        $filteredValues[$key] = ($v === '1' || $v === true);

                    // numeric int value
                    }else if ($validator == 'int'){
                        if (is_numeric($v)){
                            $filteredValues[$key] = intval($v);

                            // default value
                        }else{
                            $filteredValues[$key] = $value;
                        }

                    // numeric float value
                    }else if ($validator == 'float'){
                        if (is_numeric($v)){
                            $filteredValues[$key] = floatval($v);

                        // default value
                        }else{
                            $filteredValues[$key] = $value;
                        }

                    // default: string
                    }else{
                        $filteredValues[$key] = trim($v . '');
                    }

                // just assign string
                }else{
                    $filteredValues[$key] = trim($v . '');
                }

            // use default value
            }else{
                $filteredValues[$key] = $value;
            }
        }

        return $filteredValues;
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

    // update a set of options
    public function setOptions($values){
        foreach ($values as $key => $value){
            // config key exists (is in default config ?)
            if (isset($this->_config[$key])){
                // assign new value
                $this->_config[$key] = $value;
            }
        }

        // update config, apply prior validation
        update_option('cryptex-options', $this->_config);
    }
    
    // fetch option by key
    public function getOption($key){
        return $this->_config[$key];
    }
    
    // fetch all plugin options as array
    public function getOptions(){
        return $this->_config;
    }
}
