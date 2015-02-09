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
            
            // arrays
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
            
            array(
                array(array('foo' => true)),
                array(array('foo' => true), array('foo' => true)),
            ),
            
            array(
                array(array('foo' => true)),
                array(),
            ),
            
        );
        
        $withTopLevelOptional = array();
        foreach ($raw as $pair) {
            $withTopLevelOptional[] = $pair;
            $withTopLevelOptional[] = array(s::optional($pair[0]), null);
        }
        
        return $withTopLevelOptional;
    }
}