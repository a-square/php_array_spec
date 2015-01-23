<?php

namespace Lightsoft\ArraySpec;

class SpecInterpreter {
    public function __construct($spec) {
        $this->_spec = $spec;
    }
    
    public function validate(ArrayValidator $validator) {
        _expect($this->_spec, $validator);
    }
    
    private function _expect($spec, ArrayValidator $validator) {
        
    }
    
    private $_spec;
}