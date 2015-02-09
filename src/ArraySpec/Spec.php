<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec;

/**
 * Convenience class exposing all functionality through static methods
 * in a way that allows more embedded DSL type usage without boilerplate
 * unless the client needs to tweak something.
 */
class Spec {
    public static function spec($spec) {
        return static::_getValidatableFactory()->createValidatable($spec);
    }
    
    public static function optional($spec) {
        return new Transformers\Optional($spec);
    }
    
    public static function nonEmpty($spec) {
        return new Transformers\NonEmpty($spec);
    }
    
    public static function explain($exception) {
        $messages = array();
        $iterator = $exception->getIterator(false, \Respect\Validation\Exceptions\AbstractNestedException::ITERATE_TREE);
        foreach ($iterator as $m) {
            $messages[] = $m;
        }
        
        return implode(PHP_EOL, $messages);
    }
    
    protected static function _getValidatableFactory() {
        if (!static::$_validatableFactory) {
            $util = new Util();
            static::$_validatableFactory = new ValidatableFactory($util);
        }
        
        return static::$_validatableFactory;
    }
    
    private static $_validatableFactory = null;
}