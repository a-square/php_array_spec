<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

require_once 'vendor/autoload.php';

use Lightsoft\ArraySpec\Spec as s;

class SpecTest extends PHPUnit_Framework_TestCase {
    /** @dataProvider successProvider */
    public function testSuccess($spec, $value) {
        $validatable = s::spec($spec);
        $this->assertTrue($validatable->validate($value));
    }
    
    public function successProvider() {
        $raw = array(
            // primitive values
            array(
                'int',
                123,
            ),
            array(
                'number',
                123.456,
            ),
            array(
                'bool',
                true,
            ),
            array(
                'string',
                '',
            ),
            array(
                null,
                null,
            ),
            array(
                'null',
                null,
            ),
            array(
                true,
                true,
            ),
            array(
                'true',
                true,
            ),
            array(
                false,
                false
            ),
            array(
                'false',
                false,
            ),
            
            //
            // arrays
            //
            
            array(
                array('int'),
                array(),
            ),
            
            array(
                array('int'),
                array(1, 2, 3),
            ),
            
            array(
                array(array('int')),
                array(array(1, 2, 3), array(4, 5, 6)),
            ),
            
            array(
                array('foo' => 'string', 'bar' => 'int'),
                array('foo' => 'bar', 'bar' => 123),
            ),
            
            array(
                array(array('foo' => true)),
                array(array('foo' => true), array('foo' => true)),
            ),
            
            array(
                array(array('foo' => true)),
                array(),
            ),
            
            // extraneous keys cannot be rejected in respect/validation :(
            array(
                array('foo' => 'int'),
                array('foo' => 1, 'bar' => 2),
            ),
            
            //
            // optional
            //
            // Note: naked optionals are checked for every test case,
            // see below
            //
            
            array(
                array('foo' => s::optional(array('int'))),
                array(),
            ),
            
            array(
                array('foo' => s::optional(array('int'))),
                array('foo' => null),
            ),
            
            array(
                array('foo' => s::optional(array('int'))),
                array('foo' => array(1, 2, 3)),
            ),
            
            // nested optional
            array(
                s::optional('int'),
                null,
            ),

            array(
                s::optional('int'),
                -123,
            ),
            
            array(
                array('foo' => s::optional(s::optional('int'))),
                array('foo' => null),
            ),
            
            array(
                array('foo' => s::optional(s::optional('int'))),
                array(),
            ),
            
            array(
                array('foo' => s::optional(s::optional('int'))),
                array('foo' => 123),
            ),
            
            //
            // non-empty
            //
            
            array(
                s::nonEmpty(array()),
                array(1),
            ),
            
            array(
                s::nonEmpty('array'),
                array('foo' => 'bar'),
            ),
            
            array(
                s::nonEmpty('string'),
                'foo',
            ),
        );
        
        $withTopLevelOptional = array();
        foreach ($raw as $pair) {
            $withTopLevelOptional[] = $pair;
            $withTopLevelOptional[] = array(s::optional($pair[0]), null);
        }
        
        return $withTopLevelOptional;
    }
    
    /** @dataProvider failureProvider */
    public function testFailure($spec, $value) {
        $validatable = s::spec($spec);
        $this->assertFalse($validatable->validate($value));
    }
    
    public function failureProvider() {
        return array(
            array(
                'int',
                123.456,
            ),
            
            array(
                array('string'),
                array('1', '2', 3),
            ),
            
            array(
                true,
                false,
            ),
            
            array(
                array('foo' => 'string'),
                array('bar' => 'baz'),
            ),
            
            array(
                s::nonEmpty('string'),
                '',
            ),
            
            array(
                s::nonEmpty(array('int')),
                array(),
            ),
            
            array(
                array('foo' => s::optional('int')),
                array('foo' => 'bar'),
            ),
        );
    }
    
    /** @dataProvider invalidSpecProvider */
    public function testInvalidSpec($spec) {
        try {
            s::spec($spec);
            $this->assertTrue(false);
        } catch (\LogicException $e) {
            $this->assertTrue(true);
        }
    }
    
    public function invalidSpecProvider() {
        return array(
            array(
                'foo',
            ),
            
            array(
                array('int', 'string'),
            ),
            
            array(
                new \Lightsoft\ArraySpec\Util(),
            ),
        );
    }
    
    public function docExamplesTest() {
        $validatable = array(
            'foo' => 'string',
            'bar' => s::nonEmpty('string'),
            'baz' => s::nonEmpty(array('int')),
            'qux' => s::optional(array(
                'foo' => 'number',
                'bar' => v::oneOf(v::arr(), v::int()),
            )),
        );
        
        $test1 = array(
            'foo' => '',
            'bar' => 'Lorem ipsum',
            'baz' => array(1, 2, 3),
            'qux' => array(
                'foo' => 1.23,
                'bar' => array(),
            ),
        );
        
        $test2 = array(
            'foo' => '',
            'bar' => 'Lorem ipsum',
            'baz' => array(1, 2, 3),
            'qux' => null,
        );
        
        $test3 = array(
            'foo' => '',
            'bar' => 'Lorem ipsum',
            'baz' => array(1, 2, 3),
        );
        
        $this->assertTrue($validatable->validate($test1));
        $this->assertTrue($validatable->validate($test2));
        $this->assertTrue($validatable->validate($test3));
    }
}