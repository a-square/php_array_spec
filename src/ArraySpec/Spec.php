<?php

namespace Lightsoft\ArraySpec;

class Spec {
    public static function spec($spec) {
        return static::_getValidatableFactory()->createValidatable($spec);
    }
    
    public static function optional($spec) {
        return new Optional($spec);
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