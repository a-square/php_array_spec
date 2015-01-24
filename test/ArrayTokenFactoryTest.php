<?php

require 'vendor/autoload.php';

class ArrayTokenFactoryTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->_tokenFactory = new \Lightsoft\ArraySpec\Iterators\ArrayTokenFactory();
    }
    
    public function testBegin() {
        $begin = $this->_tokenFactory->begin();
        $this->assertTrue($begin->isArrayBegin(), 'Beginning is the beginning');
        $this->assertFalse($begin->isArrayEnd(), 'Beginning is not the end');
    }
    
    public function testFalse() {
        $begin = $this->_tokenFactory->end();
        $this->assertFalse($begin->isArrayBegin(), 'End is not the beginning');
        $this->assertTrue($begin->isArrayEnd(), 'End is the end');
    }
    
    public function testIdentity() {
        $begin1 = $this->_tokenFactory->begin();
        $begin2 = $this->_tokenFactory->begin();
        $end1 = $this->_tokenFactory->end();
        $end2 = $this->_tokenFactory->end();
        
        $this->assertTrue($begin1 === $begin2, 'Beginnings are identical');
        $this->assertTrue($end1 === $end2, 'Ends are identical');
    }
    
    /** @var TokenFactory */
    private $_tokenFactory;
}