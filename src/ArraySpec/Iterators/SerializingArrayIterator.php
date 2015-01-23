<?php

namespace Lightsoft\ArraySpec\Iterators;

// The array iterator class that serializes nested arrays in
// a reversible fashion in order.
//
// It works with arrays of primitive data (including nested arrays)
// in a way that is consistent with how this array would be
// serialized in a high level language syntax, such as JSON.
//
// For example:
// array(1, 2, 3) -> ARRAY_BEGIN, 1, 2, 3, ARRAY_END
// array('foo', 'bar') -> ASSOC_BEGIN, 'foo', 'bar', ASSOC_END
//
// An array is considered associative if its first index is a
// string.
class SerializingArrayIterator implements \Iterator {
    public function __construct(ArrayIteratorFactory $iteratorFactory, $value) {
        $this->_iteratorFactory = $iteratorFactory;
        $this->_value = $value;
    }
    
    public function current() {
        return $this->_value;
    }
    
    public function key() {
        return $this->_key;
    }
    
    // TODO: reduce the number of pushes and pops?
    public function next() {
        // remove the last iterator from the array
        $lastIterator = $this->_popIterator();
        $currentValue = $lastIterator->current();
        
        if (is_array($currentValue)) {
            // if the last iterator points to an array, push this
            // array's iterator to the 
            $this->_pushIterator($lastIterator);
            $this->_pushIterator($this->_iteratorFactory->createIterator($currentValue));
            
            $this->_value = Token::autoArrayBegin($lastIterator->current());
        } else if($lastIterator->valid()) {
            // if no deeper recursion is necessary and the iterator is
            // still valid, simply advance it
            $lastIterator->next();
            $this->_pushIterator($this->_iteratorStack, $lastIterator);
            
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

        $this->_key = $lastIterator->key();
        $this->_generateValue();
    }
    
    public function rewind() {
        $this->_resetIteratorStack();
    }
    
    public function valid() {
        assert(!empty($this->_iteratorStack), 'Empty iterator stack');
        return $this->_iteratorStack[0]->valid();
    }
    
    private function _rewindIteratorStack() {
        $this->_iteratorStack = array(
            // wrapping the whole value in an array
            // so that all the algorithms work properly
            // without special treatment of the root
            new \ArrayIterator(array($value))
        );
        
        $this->_generateValue();
    }

    private function _generateValue() {
        assert(!empty($this->_iteratorStack));

        // special case: at the root
        if (count($this->_iteratorStack) === 1) {
            $root = $this->_iteratorStack[0];
            $this->_value = self::_getIteratorValue($root);
            return;
        }

        $count = count($this->_iteratorStack);
        $last = $this->_iteratorStack[$count - 1];
        $prev = $this->_iteratorStack[$count - 2];
        
        // the last iterator is only invalid when it's at the end of an array,
        // in which case the previous iterator is pointing to that array
        if ($last->valid()) {
            $this->_value = self::_getIteratorValue($last);
        } else {
            $this->_value = Token::autoArrayEnd($prev->value());
        }
    }
    
    // wraps ArrayIterator::current() to return the proper
    // token rather than expose the nested array
    private static function _getIteratorValue($iterator) {
        if (!$iterator->valid()) {
            return null;
        }
        
        $value = $iterator->current();
        if (is_array($value)) {
            return Token::autoArrayBegin($value);
        } else {
            return $value;
        }
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
    
    // stored for the sole purpose of this iterator being rewindable
    private $_value;

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