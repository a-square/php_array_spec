<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec\Iterators;

/**
 * Abstract SerializingIterator factory
 *
 * It's not technically an abstract factory, because SerializingIterator is
 * a concrete type. But in spirit it is, because it incapsulates that iterator's
 * dependent factories IteratorFactory and TokenFactory.
 */
interface SerializingIteratorFactory {
    /**
     * Creates a SerializingIterator for the value
     */
    public function createSerializingIterator($value);
}