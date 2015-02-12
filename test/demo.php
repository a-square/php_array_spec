<?php

require_once 'vendor/autoload.php';

use Lightsoft\ArraySpec\Spec as s;
use Respect\Validation\Validator as v;

$validatable = s::spec(array(
    'foo' => s::optional('int'),
    'bar' => array('string'),
    'qux' => s::nonEmpty('array'),
));

$validArray = array(
    'bar' => array(),
    'qux' => array('foo' => 'bar'),
);

$invalidArray = array(
    'foo' => 'awol',
    'bar' => array('foo', 'bar', 1),
    'qux' => array(),
);

$validatable->assert($validArray);
echo "Valid array is valid!\n\n";

try {
    $validatable->assert($invalidArray);
} catch (\Exception $e) {
    echo $e->getFullMessage();
}