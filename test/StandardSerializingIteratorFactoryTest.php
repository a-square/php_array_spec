<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

require_once 'vendor/autoload.php';

/**
 * Helper iterator treating values 0, 2, 4 etc as keys and
 * values 1, 3, 5 etc as values
 *
 * It's useful because the serializing iterator may use
 * keys normal array would never use, and it may use
 * them multiple times
 */
class KeyValueIterator implements \Iterator {
    public function __construct($array) {
        $this->_arrayIterator = new \ArrayIterator($array);
        $this->_advance();
    }
    
    public function current() {
        return $this->_value;
    }
    
    public function key() {
        return $this->_key;
    }
    
    public function next() {
        $this->_advance();
    }
    
    public function rewind() {
        $this->_arrayIterator->rewind();
        $this->_advance();
    }
    
    public function valid() {
        return $this->_valid;
    }
    
    private function _advance() {
        $this->_valid = $this->_arrayIterator->valid();
        if (!$this->_valid) {
            return;
        }
        
        $this->_key = $this->_arrayIterator->current();
        $this->_arrayIterator->next();
        $this->_value = $this->_arrayIterator->current();
        $this->_arrayIterator->next();
    }
    
    private $_arrayIterator, $_key, $_value, $_valid;
}

/**
 * Tests \Lightsoft\ArraySpec\Iterators\StandardIteratorFactory as an
 * implementation of \Lightsoft\ArraySpec\Iterators\IteratorFactory
 */
class StandardSerializingIteratorFactoryTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->_init();
    }

    /**
     * @dataProvider successProvider
     */
    public function testSuccess($nested, $serialized, $message) {
        $nestedIterator = $this->_iteratorFactory->createSerializingIterator($nested);
        $serializedIterator = new KeyValueIterator($serialized);
        
        /*
        // uncomment to get detailed output
        echo "\n************ $message ************\n";
        echo "****** Test run *******";
        foreach ($nestedIterator as $key => $value) {
            echo "*** $key ***\n";
            var_dump($value);
        }
        $nestedIterator->rewind();
        echo "****** Reference *******";
        foreach ($serializedIterator as $key => $value) {
            echo "*** $key ***\n";
            var_dump($value);
        }
        $serializedIterator->rewind();
        */
        $this->_assertIteratorsEqual($nestedIterator, $serializedIterator, $message);
    }
    
    public function successProvider() {
        $this->_init();
        
        $dataSets = array();
        
        //
        // edge cases
        //
        
        $dataSets[] = array(
            array(),
            array(
                0,    $this->_begin,
                null, $this->_end
            ),
            'Empty array'
        );
        
        $dataSets[] = array(
            'string',
            array(
                0, 'string'
            ),
            'Naked string'
        );
        
        $dataSets[] = array(
            123,
            array(
                0, 123
            ),
            'Naked number'
        );
        
        $dataSets[] = array(
            null,
            array(
                0, null,
            ),
            'Naked null'
        );
        
        $dataSets[] = array(
            (object)array('foo' => 'bar'),
            array(
                0, (object)array('foo' => 'bar'),
            ),
            'Naked object'
        );
        
        //
        // normal array
        //
        
        $dataSets[] = array(
            array(1, 2, 3, 'foo' => 'bar'),
            array(
                0,     $this->_begin,
                0,     1,
                1,     2,
                2,     3,
                'foo', 'bar',
                null,  $this->_end,
            ),
            'Normal array'
        );
        
        $dataSets[] = array(
            array(
                1,
                array(
                    2,
                    array(
                        'foo' => 3,
                        'bar' => 4,
                    )
                ),
                5,
            ),
            array(
                0,     $this->_begin,
                0,     1,
                1,     $this->_begin,
                0,     2,
                1,     $this->_begin,
                'foo', 3,
                'bar', 4,
                null,  $this->_end,
                null,  $this->_end,
                2,     5,
                null,  $this->_end,
            ),
            'Nested array'
        );
        
        return $dataSets;
    }
    
    private function _assertIteratorsEqual(\Iterator $iter1, \Iterator $iter2, $message) {
        $i = 0;
        while ($iter1->valid() && $iter2->valid()) {
            $this->assertEquals($iter1->key(), $iter2->key(), "$message #$i: equality of keys");
            $this->assertEquals($iter1->current(), $iter2->current(), "$message #$i: equality of values");
            $iter1->next();
            $iter2->next();
            $i++;
        }
        
        $this->assertEquals($iter1->valid(), $iter2->valid(), "$message #$i: equal validity");
    }

    private function _init() {
        if ($this->_iteratorFactory !== null) {
            return;
        }
        
        $tokenFactory = new \Lightsoft\ArraySpec\Iterators\ArrayTokenFactory();
        $this->_iteratorFactory = new \Lightsoft\ArraySpec\Iterators\StandardIteratorFactory($tokenFactory);
        
        $this->_begin = $tokenFactory->begin();
        $this->_end = $tokenFactory->end();
    }

    /** @var SerializingIteratorFactory */
    private $_iteratorFactory;

    /** @var ArrayToken */
    private $_begin;
    
    /** @var ArrayToken */
    private $_end;
}