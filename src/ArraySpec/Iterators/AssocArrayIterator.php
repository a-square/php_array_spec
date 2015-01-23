<?php

namespace Lightsoft\ArraySpec\Iterators;

// iterates an array as key0, value0, key1, value1...
class AssocArrayIterator implements \Iterator {
    public function __construct($array) {
        $this->_iterator = new \ArrayIterator($array);
        $this->_atKey = true;
    }
    
    public function current() {
        return $this->_atKey ? $this->_iterator->key() ? $this->_iterator->current();
    }
    
    public function key() {
        return $this->_iterator->key();
    }
    
    public function next() {
        if (!_atKey) {
            $this->_iterator->next();
        }
        
        $this->_atKey = !$this->_atKey;
    }
    
    public function rewind() {
        $this->_iterator->rewind();
        $this->_atKey = true;
    }
    
    public function valid() {
        return $this->_iterator->valid();
    }
}