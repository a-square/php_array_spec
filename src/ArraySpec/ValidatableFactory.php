<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec;

use Respect\Validation\Validator as v;

class ValidatableFactory {
    public function __construct($util) {
        $this->_util = $util;
        $this->_precacheSpecs();
    }
    
    public function createValidatable($spec) {
        if (is_string($spec)) {
            if (!key_exists($spec, $this->_cachedSpecs)) {
                $validShorthands = implode(", ", array_keys($this->_precachedSpecs));
                throw new \LogicException("Invalid spec shorthand \"$spec\", the valid ones are $validShorthands");
            }
            
            return $this->_cachedSpecs[$spec];
        }
        
        if (is_array($spec)) {
            if (empty($spec)) {
                return $this->_cachedSpecs['array'];
            }
            
            if ($this->_util->isArrayAssoc($spec)) {
                return $this->_createValidatableFromAssoc($spec);
            } else {
                return $this->_createValidatableFromArray($spec);
            }
        }

        if ($spec === null) {
            return $this->_cachedSpecs['null'];
        }
        
        if ($spec === true) {
            return $this->_cachedSpecs['true'];
        }
        
        if ($spec === false) {
            return $this->_cachedSpecs['false'];
        }
        
        if ($spec instanceof Transformer) {
            return $spec->getValidatable($this);
        }

        if ($spec instanceof Respect\Validation\Validatable) {
            return $spec;
        }
    }
    
    private function _createValidatableFromAssoc($array) {
        $v = v::arr();
        foreach ($array as $key => $value) {
            $isRequired = true;
            if ($value instanceof Transformers\Optional) {
                $isRequired = false;
            }
            
            $v->key($key, $this->createValidatable($value), $isRequired);
        }
        
        return $v;
    }
    
    private function _createValidatableFromArray($array) {
        if (count($array) != 1) {
            throw new \LogicException('Positional arrays in the spec must have exactly one element');
        }
        
        return v::arr()->each($this->createValidatable($array[0]));
    }
    
    private function _precacheSpecs() {
        $this->_cachedSpecs = array(
            'arr' => v::arr(),
            'array' => v::arr(),
            
            'bool' => v::bool(),
            'boolean' => v::bool(),
            'true' => v::equals(true, true),
            'false' => v::equals(false, true),
            
            'float' => v::float(),
            'int' => v::int(),
            'integer' => v::int(),
            'numeric' => v::numeric(),
            'number' => v::numeric(),

            'null' => v::nullValue(),

            'string' => v::string(),
            'date' => v::date(),
            'xdigit' => v::xdigit(),
            'hex' => v::xdigit(),
        );
    }
    
    private $_cachedSpecs, $_util;
}