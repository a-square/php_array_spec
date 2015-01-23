<?php

namespace Lightsoft\ArraySpec\Iterators;

class Token {
    const ARRAY_BEGIN = 'ARRAY_BEGIN';
    const ARRAY_END = 'ARRAY_END';
    const ASSOC_BEGIN = 'ASSOC_BEGIN';
    const ASSOC_END = 'ASSOC_END';
    
    /*
     * construction
     */
    
    protected function __construct($tokenValue) {
        $this->_tokenValue = $tokenValue;
    }

    public function arrayBegin() {
        if (self::_arrayBeginInstance === null) {
            self::_arrayBeginInstance = new self(self::ARRAY_BEGIN);
        }
        
        return self::_arrayBeginInstance;
    }
    
    public function arrayEnd() {
        if (self::_arrayEndInstance === null) {
            self::_arrayEndInstance = new self(self::ARRAY_END);
        }
        
        return self::_arrayEndInstance;
    }
    
    public function assocBegin() {
        if (self::_assocBeginInstance === null) {
            self::_assocBeginInstance = new self(self::ASSOC_BEGIN);
        }
        
        return self::_assocBeginInstance;
    }
    
    public function assocEnd() {
        if (self::_assocEndInstance === null) {
            self::_assocEndInstance = new self(self::ASSOC_END);
        }
        
        return self::_assocEndInstance;
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
    
    public function isAssocBegin() {
        return $this->_tokenValue === self::ASSOC_BEGIN;
    }
    
    public function isAssocEnd() {
        return $this->_tokenValue === self::ASSOC_END;
    }
    
    protected $_tokenValue;
    
    private static $_arrayBeginInstance = null;
    private static $_arrayEndInstance = null;
    private static $_assocBeginInstance = null;
    private static $_assocEndInstance = null;
}