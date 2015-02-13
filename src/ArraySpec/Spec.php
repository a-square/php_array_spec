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
final class Spec {
    public static function spec($spec) {
        return self::_getValidatableFactory()->createValidatable($spec);
    }
    
    public static function optional($spec) {
        return new Transformers\Optional($spec);
    }
    
    public static function nonEmpty($spec) {
        return new Transformers\NonEmpty($spec);
    }
    
    private static function _getValidatableFactory() {
        if (!self::$_validatableFactory) {
            $util = new Util();
            self::$_validatableFactory = new ValidatableFactory($util);
        }
        
        return self::$_validatableFactory;
    }
    
    private static $_validatableFactory = null;
}