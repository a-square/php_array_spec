<?php

namespace Lightsoft\ArraySpec\Iterators;

class ArrayToken {
    const ARRAY_BEGIN = 0;
    const ARRAY_END = 1;
    
    /*
     * construction
     */
    
    protected function __construct($tokenValue) {
        $this->_tokenValue = $tokenValue;
    }

    /*
     * type tests
     */
    
    public function isArrayBegin() {
        return $this->_tokenValue === self::ARRAY_BEGIN;
    }
    
    public function isArrayEnd() {
        return $this->_tokenValue === self::ARRAY_END;
    }
    
    protected $_tokenValue;
    
}