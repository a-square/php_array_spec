<?php

namespace Lightsoft\ArraySpec\Iterators;

interface ArrayIteratorFactory {
    public function createIterator($array);
}