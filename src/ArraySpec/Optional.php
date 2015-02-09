<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec;

class Optional {
    public function __construct($spec) {
        $this->_spec = $spec;
    }
    
    public function getSpec() {
        return $this->_spec;
    }
    
    private $_spec;
}