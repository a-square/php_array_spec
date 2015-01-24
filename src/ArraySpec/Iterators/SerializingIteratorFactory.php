<?php

namespace Lightsoft\ArraySpec\Iterators;

interface SerializingIteratorFactory {
    public function createSerializingIterator($value);
}