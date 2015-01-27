<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec\Iterators;

/**
 * Iterates over possibly recurrent structures in order
 * using special tokens to denote their beginnings and ends
 *
 * Allows clients to inspect complicated recurrent data structures
 * in linear fashion, simplifying visitor pattern implementations
 * that are themselves recursive
 *
 * Depends on abstract factories IteratorFactory and TokenFactory
 */
class SerializingIterator implements \Iterator {
    /**
     * @param IteratorFactory $iteratorFactory determines which
     * values should be recursed into and handles creation of
     * iterators for them
     *
     * @param TokenFactory $tokenFactory handles creation of tokens
     * denoting beginnings and ends of iterable structures
     *
     * @param $value The value to be iterated
     */
    public function __construct(IteratorFactory $iteratorFactory, TokenFactory $tokenFactory, $value) {
        $this->_iteratorFactory = $iteratorFactory;
        $this->_tokenFactory = $tokenFactory;
        $this->_initialValue = $value;
        
        $this->_resetIteratorStack();
    }
    
    public function current() {
        return $this->_value;
    }
    
    /**
     * Iterator key
     *
     * @return mixed Depends on the nature of the structure being iterated,
     * do not count on it being an integer or a string, or even primitive value
     */
    public function key() {
        return $this->_key;
    }
    
    /**
     * Iterator value
     *
     * @throws \LogicException when the iterator is invalid
     *
     * @return mixed
     */
    public function next() {
        if (!$this->valid()) {
            throw new \LogicException('Next called on an invalid iterator');
        }
        
        // remove the last iterator from the array
        $lastIterator = $this->_popIterator();
        $currentValue = $lastIterator->current();
        
        if ($this->_iteratorFactory->isIterable($currentValue)) {
            // if the last iterator post to an iterable, push
            // its iterator to the stack
            $this->_pushIterator($lastIterator);
            $this->_pushIterator($this->_iteratorFactory->createIterator($currentValue));
            
            $this->_value = $this->_tokenFactory->begin();
        } else if($lastIterator->valid()) {
            // if no deeper recursion is necessary and the iterator is
            // still valid, simply advance it
            $lastIterator->next();
            $this->_pushIterator($lastIterator);
            
            $this->_value = $lastIterator->current();
        } else {
            // otherwise, remove it from the stack permanently
            // and advance the now last iterator
            $lastIterator = $this->_popIterator();
            
            // it's impossible for this iterator to be invalid
            // under normal conditions
            assert($lastIterator->valid(), 'Two invalid iterators in sequence');
            $lastIterator->next();
            
            $this->_pushIterator($lastIterator);
        }

        $this->_generateKeyValuePair();
    }
    
    public function rewind() {
        $this->_resetIteratorStack();
    }
    
    public function valid() {
        assert(!empty($this->_iteratorStack), 'Empty iterator stack');
        return $this->_iteratorStack[0]->valid();
    }

    /**
     * Generates the trace of all keys leading up to the current value
     *
     * ???: what about the end token, what key should it have?
     */
    public function getKeyTrace() {
        $trace = array();
        foreach ($this->_iteratorStack as $iterator) {
            $trace[] = $iterator->key();
        }
        
        return $trace;
    }
    
    /**
     * Effectively rewinds the iterator by resetting the iterator
     * stack so that it points to the root value
     */
    private function _resetIteratorStack() {
        $this->_iteratorStack = array(
            // wrapping the whole value in an array
            // so that all the algorithms work properly
            // without special treatment of the root
            new \ArrayIterator(array($this->_initialValue))
        );
        
        $this->_generateKeyValuePair();
    }

    /**
     * Generates the key and value of the iterator from
     * the current iterator stack
     */
    private function _generateKeyValuePair() {
        assert(!empty($this->_iteratorStack));

        $count = count($this->_iteratorStack);
        $lastIterator = $this->_iteratorStack[$count - 1];
        
        if (!$lastIterator->valid()) {
            $this->_value = $this->_tokenFactory->end();
        } else {
            $value = $lastIterator->current();
            if ($this->_iteratorFactory->isIterable($value)) {
                $this->_value = $this->_tokenFactory->begin();
            } else {
                $this->_value = $value;
            }
        }
        
        $this->_key = $lastIterator->key();
    }
    
    /**
     * Pushes the iterator to the iterator stack
     */
    private function _pushIterator($iterator) {
        array_push($this->_iteratorStack, $iterator);
    }
    
    /**
     * Pops the iterator out of the iterator stack
     */
    private function _popIterator() {
        assert(!empty($this->_iteratorStack), 'Empty iterator stack');
        return array_pop($this->_iteratorStack);
    }
    
    /** @var IteratorFactory $_iteratorFactory */
    private $_iteratorFactory;
    
    /** @var TokenFactory $_tokenFactory */
    private $_tokenFactory;
    
    /** @var mixed $_initialValue stored for the sole purpose of this iterator being rewindable */
    private $_initialValue;

    /** @var array $_iteratorStack */
    private $_iteratorStack;

    /** @var mixed $_key iterator key */    
    private $_key = null;

    /** @var mixed $_value iterator value */    
    private $_value = null;
}