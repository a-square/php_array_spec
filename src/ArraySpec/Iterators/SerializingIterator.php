<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec\Iterators;

/**
 * 
 */
class SerializingIterator implements \Iterator {
    public function __construct(IteratorFactory $iteratorFactory, TokenFactory $tokenFactory, $value) {
        $this->_iteratorFactory = $iteratorFactory;
        $this->_tokenFactory = $tokenFactory;
        $this->_iteratedValue = $value;
        
        $this->_resetIteratorStack();
    }
    
    public function current() {
        return $this->_value;
    }
    
    public function key() {
        return $this->_key;
    }
    
    // TODO: reduce the number of pushes and pops?
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
    
    public function getKeyTrace() {
        $trace = array();
        $count = count($this->_iteratorStack);
        for ($i = 1; $i < $count; ++$i) {
            $trace[] = $this->_iteratorStack[$i]->key();
        }
        
        return $trace;
    }
    
    private function _resetIteratorStack() {
        $this->_iteratorStack = array(
            // wrapping the whole value in an array
            // so that all the algorithms work properly
            // without special treatment of the root
            new \ArrayIterator(array($this->_iteratedValue))
        );
        
        $this->_generateKeyValuePair();
    }

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
    
    // pushes the iterator to the iterator stack
    private function _pushIterator($iterator) {
        array_push($this->_iteratorStack, $iterator);
    }
    
    // pops the iterator out of the iterator stack
    private function _popIterator() {
        assert(!empty($this->_iteratorStack), 'Empty iterator stack');
        return array_pop($this->_iteratorStack);
    }
    
    // used to create (flat) array iterators that detect if
    // the array is associative and behave correctly
    //
    // our code makes no assumption about these iterators
    // besides the basics, so in theory the factory can do
    // any crazy thing it wants as long as it outputs iterators
    private $_iteratorFactory;
    
    private $_tokenFactory;
    
    // stored for the sole purpose of this iterator being rewindable
    private $_iteratedValue;

    // the iterator stack implemented as a simple array.
    // Not using a more specialized structure is useful
    // because it allows us to freely explore the stack's
    // contents
    //
    // normally the iterator stack is never empty
    private $_iteratorStack;
    
    // the $this->key() value
    private $_key = null;
    
    // the $this->current() value.
    // Using this cannot be avoided because we don't always have
    // actual value handy so sometimes we use specialized tokens
    // in place of a primitive type value
    private $_value = null;
}