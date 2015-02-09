<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec;

/**
 * A decorator that wraps a spec and transforms whatever validatable
 * that it creates
 */
interface Transformer {
    /**
     * @return \Respect\Validation\Validatable
     */
    public function getValidatable(ValidatableFactory $validatableFactory);
}