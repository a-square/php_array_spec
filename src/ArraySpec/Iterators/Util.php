<?php

namespace Lightsoft\ArraySpec\Iterators;

interface Util {
    // must return true when the array should
    // be considered associative
    public function isArrayAssoc($array);
}