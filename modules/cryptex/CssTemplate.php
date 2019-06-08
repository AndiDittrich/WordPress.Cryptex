<?php
// Dynamic CSS Generator
namespace Cryptex;

class CssTemplate{
    // list of assigned vars
    private $_cssVars;

    // raw css template
    private $_template;
    
    public function __construct($filename){
        // initialize var list
        $this->_cssVars = array();
        
        // read template
        $this->_template = file_get_contents($filename);
    }
    
    // assign key/value pair
    public function assign($key, $value){
        $this->_cssVars['$('.$key.')'] = $value;
    }
    
    // store rendered css file
    public function store($filename){
        // render tpl
        $renderedTPL = $this->render();
        
        // store
        file_put_contents($filename, $renderedTPL);
    }
    
    // return tpl
    public function render($cleanup = true){
        // replace key/value pairs
        $tplData = str_replace(array_keys($this->_cssVars), array_values($this->_cssVars), $this->_template);
        
        // filter non assigned template vars
        $tplData = preg_replace('/\$\([A-z_-]\)/i', '', $tplData);
        
        // remove comments and linebreaks
        if ($cleanup){
            $tplData = preg_replace('/^\s*/m', '', $tplData);
            $tplData = preg_replace('#[\r\n]#s', '', $tplData);
            $tplData = preg_replace('#/\*.*?\*/#s', '', $tplData);
            $tplData = trim($tplData);
        }
        
        return $tplData;
    }
        
}