<?php

namespace Lightsoft\ArraySpec\Iterators;

class StandardIteratorFactory implements IteratorFactory, SerializingIteratorFactory {
    public function __construct(TokenFactory $tokenFactory) {
        $this->_tokenFactory = $tokenFactory;
    }
    
    public function isIterable($value) {
        return is_array($value);
    }
    
    public function createIterator($value) {
        if (!$this->isIterable($value)) {
            throw new \LogicException('The argument must be an array');
        }
        
        return new \ArrayIterator($value);
    }
    
    public function createSerializingIterator($value) {
        return new SerializingIterator($this, $this->_tokenFactory, $value);
    }
    
    protected $_tokenFactory;
}