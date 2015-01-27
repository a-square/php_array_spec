<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec\Iterators;

/**
 * Facilitates creation of SerializingIterator for nested arrays
 */
class StandardIteratorFactory implements IteratorFactory, SerializingIteratorFactory {
    /**
     * Accepts the TokenFactory dependency
     */
    public function __construct(TokenFactory $tokenFactory) {
        $this->_tokenFactory = $tokenFactory;
    }
    
    /**
     * @return true iff $value is an array
     */
    public function isIterable($value) {
        return is_array($value);
    }
    
    /**
     * @return \ArrayIterator
     */
    public function createIterator($value) {
        if (!$this->isIterable($value)) {
            throw new \LogicException('The argument must be an array');
        }
        
        return new \ArrayIterator($value);
    }
    
    /**
     * Creates a serializing iterator for nested arrays
     */
    public function createSerializingIterator($value) {
        return new SerializingIterator($this, $this->_tokenFactory, $value);
    }
    
    /** @var TokenFactory $_tokenFactory */
    protected $_tokenFactory;
}