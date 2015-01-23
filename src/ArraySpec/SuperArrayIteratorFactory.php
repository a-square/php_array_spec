<?php

namespace Lightsoft\ArraySpec;

class SuperArrayIteratorFactory implements ArrayIteratorFactory, SerializingArrayIteratorFactory {
    public function __construct(Util $util) {
        $this->_util = $util;
    }
    
    public function createIterator($array) {
        if (!is_array($array)) {
            throw new \LogicException('The argument must be an array');
        }
        
        if ($this->_util->isArrayAssoc($array)) {
            return new AssocArrayIterator($array);
        } else {
            return new \ArrayIterator($array);
        }
    }
    
    public function createSerializingIterator($value) {
        return new SerializingArrayIterator($this, $value);
    }
    
    protected $_util;
}