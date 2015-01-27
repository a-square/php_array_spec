<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

require_once 'vendor/autoload.php';

/**
 * Tests \Lightsoft\ArraySpec\Iterators\StandardIteratorFactory
 * as an implementation of \Lightsoft\ArraySpec\Iterators\IteratorFactory
 */
class StandardIteratorFactoryTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $tokenFactory = new \Lightsoft\ArraySpec\Iterators\ArrayTokenFactory();
        $this->_iteratorFactory = new \Lightsoft\ArraySpec\Iterators\StandardIteratorFactory($tokenFactory);
    }
    
    /**
     * @dataProvider iterableProvider
     */
    public function testIterable($value, $result, $message) {
        $this->assertEquals($this->_iteratorFactory->isIterable($value), $result, $message);
    }
    
    public function iterableProvider() {
        return array(
            array(array(1, 2, 3), true, 'Positional arrays are iterable'),
            array(array(), true, 'Empty arrays are iterable'),
            array(array(5 => 'asdf', 'foo' => 'bar'), true, 'Other arrays are also iterable'),
            array(1, false, 'Numbers are not iterable'),
            array(false, false, 'Booleans are not iterable'),
            array((object)array('foo' => 'bar'), false, 'Standard objects are not iterable'),
            array(new ArrayIterator(array()), false, 'Custom objects are not iterable'),
            array(null, false, 'Null is not iterable'),
        );
    }
    
    public function testIterator() {
        $array = array(
            0 => 'asdf',
            'foo' => 'bar',
        );
        $iterator = $this->_iteratorFactory->createIterator($array);
        $this->assertTrue($iterator->valid());
        $this->assertEquals($iterator->key(), 0);
        $this->assertEquals($iterator->current(), 'asdf');
        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertEquals($iterator->key(), 'foo');
        $this->assertEquals($iterator->current(), 'bar');
        $iterator->next();
        $this->assertFalse($iterator->valid());
        $iterator->rewind();
        $this->assertTrue($iterator->valid());
        $this->assertEquals($iterator->key(), 0);
    }
    
    /** @var \Lightsoft\ArraySpec\Iterators\IteratorFactory */
    private $_iteratorFactory;
}