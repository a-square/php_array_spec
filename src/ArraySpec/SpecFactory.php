<?php

/**
 * @author Alexei Averchenko <lex.aver@gmail.com>
 * @copyright 2015 Lightsoft Research
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Lightsoft\ArraySpec;

use Respect\Validation\Validator as v;

class SpecFactory {
    public function __construct() {
        $this->_precacheSpecs();
        $this->_util = new Util();
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
            if ($this->_util->isArrayAssoc($spec)) {
                return $this->_createValidatableFromAssoc($spec);
            } else {
                return $this->_createValidatableFromArray($spec);
            }
        }

        if ($spec === null) {
            return v::nullValue();
        }
        
        if ($spec === true) {
            return v::true();
        }
        
        if ($spec === false) {
            return v::false();
        }
        
        if ($spec instanceof Respect\Validation\Validatable) {
            return $spec;
        }
    }
    
    private function _createValidatableFromAssoc($array) {
        $v = v::arr();
        foreach ($array as $key => $value) {
            $isRequired = true;
            if ($value instanceof Optional) {
                $isRequired = false;
                $value = $value->getSpec();
            }
            
            $v->key($key, $this->createValidatable($value), $isRequired);
        }
        
        return $v;
    }
    
    private function _createValidatableFromArray($array) {
        if (!count($array) != 1) {
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
            'true' => v::true(),
            'false' => v::false(),
            
            'float' => v::float(),
            'int' => v::int(),
            'integer' => v::integer(),
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