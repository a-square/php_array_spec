<?php

namespace Lightsoft\ArraySpec\Iterators;

// creates tokens of appropriate types to denote beginnings
// and ends of arrays
class ArrayTokenFactory implements TokenFactory {
    public function begin() {
        if ($this->_beginInstance === null) {
            $this->_beginInstance = new ArrayToken(ArrayToken::ARRAY_BEGIN);
        }
        
        return self::_beginInstance;
    }
    
    public function end() {
        if ($this->_endInstance === null) {
            $this->_endInstance = new ArrayToken(ArrayToken::ARRAY_END);
        }
        
        return $this->_endInstance;
    }
    
    private $_beginInstance = null;
    private $_endInstance = null;
}