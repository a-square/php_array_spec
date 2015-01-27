<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec;

// a collection of helper methods that mostly deal with
// icky and unpleasant parts of working with primitive types
//
// there's high variability in ways you could approach these problems,
// so it is expected that a utility class is created for a coherent
// approach and then exposed via each submodule's Util facade interface
class StandardUtil {
    // a simplified test of whether the array should be treated
    // as associative. Works for JSON-compatible arrays and
    // the distinction is arbitrary otherwise anyway
    //
    // Warning: changes the internal array iterator,
    // don't use procedural style iteration if it's a problem
    public function isArrayAssoc($array) {
        reset($array);
        return is_string(key($array));
    }
}