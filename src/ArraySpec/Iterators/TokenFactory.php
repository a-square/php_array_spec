<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec\Iterators;

/**
 * Handles creation of tokens for the use in SerializingIterator
 */
interface TokenFactory {
    /**
     * @return Token A token denoting the beginning of
     * an iterable structure
     */
    public function begin();

    /**
     * @return Token A token denoting the end of
     * an iterable structure
     */
    public function end();
}