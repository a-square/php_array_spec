<?php

namespace Lightsoft\ArraySpec;

class StandardUtil implements Util {
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