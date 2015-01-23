<?php

namespace Lightsoft\ArraySpec;

interface Util {
    // must return true when the array should
    // be considered associative
    public function isArrayAssoc($array);
}