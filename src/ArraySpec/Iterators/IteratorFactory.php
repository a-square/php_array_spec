<?php

namespace Lightsoft\ArraySpec\Iterators;

interface IteratorFactory {
    public function isIterable($value);
    public function createIterator($value);
}