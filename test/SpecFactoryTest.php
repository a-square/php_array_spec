<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use Respect\Validation\Validator as v;

class SpecFactoryTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->_specFactory = new \Lightsoft\ArraySpec\SpecFactory();
    }
    
    /** @dataProvider successProvider */
    public function testSuccess($spec, $value) {
        $validatable = $this->_specFactory->createValidatable($spec);
        $this->assertTrue($validatable->validate($value));
    }
    
    public function successProvider() {
        return array(
            // primitive values
            array(
                'int',
                v::int(),
            ),
            array(
                'bool',
                v::bool(),
            ),
            array(
                'string',
                v::string(),
            ),
            array(
                true,
                v::true(),
            ),
            array(
                false,
                v::false(),
            ),
            array(
                null,
                v::null(),
            ),
        );
    }
    
    private $_specFactory;
}