<?php

namespace Lightsoft\ArraySpec\Iterators;

interface SerializingArrayIteratorFactory {
    public function createSerializingIterator($value);
}