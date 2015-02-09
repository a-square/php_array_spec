<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec\Transformers;

/**
 * A transformer that ensures that whatever it's wrapping is not empty
 */
class NonEmpty implements \Lightsoft\ArraySpec\Transformer {
    public function __construct($spec) {
        $this->_spec = $spec;
    }
    
    public function getValidatable(\Lightsoft\ArraySpec\ValidatableFactory $validatableFactory) {
         $validatable = $validatableFactory->createValidatable($this->_spec);
         return $validatable->notEmpty();
    }
    
    private $_spec;
}