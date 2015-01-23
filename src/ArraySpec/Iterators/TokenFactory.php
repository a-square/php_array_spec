<?php

namespace Lightsoft\ArraySpec\Iterators;

// creates tokens of appropriate types to denote beginnings
// and ends of arrays
class TokenFactory {
    public function __construct(Util $util) {
        $this->_util = $util;
    }
    
    public function autoArrayBegin($array) {
        return $this->_util->isArrayAssoc($array) ? Token::assocBegin() : Token::arrayBegin();
    }
    
    public function autoArrayEnd($array) {
        return $this->_util->isArrayAssoc($array) ? Token::assocEnd() : Token::arrayEnd();
    }

    protected $_util;
}