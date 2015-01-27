<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec\Iterators;

/**
 * Allows a SerializingIterator to determine if its current value
 * is iterable and to create an iterator for it
 *
 * Structurally, this is the most important companion interface to
 * SerializingIterator. It encapsulates the exact meaning of
 * "iterable" as applied to its value and is responsible for
 * creating iterators to recurse into such values
 */
interface IteratorFactory {
    /**
     * Determines if $value is considered iterable
     *
     * Determines if $value should be considered iterable.
     * Value being iterable may have nothing to do with the
     * \Iterable or \Traversible interfaces, it is entirely
     * abstract notion
     * If $value is iterable, then createIterator($value)
     * must return an Iterator instance and never throw
     *
     * @return boolean
     */
    public function isIterable($value);
    
    /**
     * Creates an iterator for iterable $value
     *
     * @throws \LogicException if $value isn't considered iterable
     *
     * @return \Iterator
     */
    public function createIterator($value);
}