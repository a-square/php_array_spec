<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec\Transformers;

use Respect\Validation\Validator as v;

/**
 * A transformer that accepts null or what it wraps.
 *
 * This transformer has special treatment by ValidatableFactory
 * making its array key optional if it wraps the value
 */
class Optional implements \Lightsoft\ArraySpec\Transformer {
    public function __construct($spec) {
        $this->_spec = $spec;
    }
    
    public function getValidatable(\Lightsoft\ArraySpec\ValidatableFactory $validatableFactory) {
         $validatable = $validatableFactory->createValidatable($this->_spec);
         return v::oneOf($validatable, v::nullValue());
    }
    
    private $_spec;
}