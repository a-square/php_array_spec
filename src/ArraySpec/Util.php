<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec;

/**
 * A collection of helper methods
 */
class Util {
    /**
     * a simplified test of whether the array should be treated
     * as associative. Works for JSON-compatible arrays and
     * the distinction is arbitrary otherwise anyway
     *
     * Warning: changes the internal array iterator,
     * don't use procedural style iteration if it's a problem
     *
     * @return boolean
     */
    public function isArrayAssoc($array) {
        reset($array);
        return is_string(key($array));
    }
}