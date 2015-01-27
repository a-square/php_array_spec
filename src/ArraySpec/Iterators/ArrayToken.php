<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec\Iterators;

class ArrayToken {
    const ARRAY_BEGIN = 0;
    const ARRAY_END = 1;
    
    /*
     * construction
     */
    
    public function __construct($tokenValue) {
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