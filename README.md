# php_array_spec

[![Build Status](https://img.shields.io/travis/Kallikanzarid/php_array_spec/master.svg?style=flat-square)](http://travis-ci.org/Kallikanzarid/php_array_spec)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/Kallikanzarid/php_array_spec/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/Kallikanzarid/php_array_spec/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Kallikanzarid/php_array_spec/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/Kallikanzarid/php_array_spec/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/lightsoft/php_array_spec.svg?style=flat-square)](https://packagist.org/packages/lightsoft/php_array_spec)
[![Total Downloads](https://img.shields.io/packagist/dt/lightsoft/php_array_spec.svg?style=flat-square)](https://packagist.org/packages/lightsoft/php_array_spec)
[![License](https://img.shields.io/packagist/l/lightsoft/php_array_spec.svg?style=flat-square)](https://packagist.org/packages/lightsoft/php_array_spec)


An array-based embedded DSL for creating complex nested Respect/Validation validatables. It makes specs for your parsed JSON look more like the arrays they will be parsing, so it's more readable.

## Getting Started

The easiest way to integrate this into your project is to use composer:

```json
"require": {
    "lightsoft/php_array_spec": "dev-master"
}
```

## Demo

There is a short demo in the `test` directory. Run from the root directory like this: `php test/demo.php`

## Usage

First, you may want to create a convenience shortcut:

```php
use Lightsoft\ArraySpec\Spec as s;
```

Respect/Validation is a dependency, and you can use its validatables inside specs freely, so it's also useful to create a shortcut to that:

```php
use Respect\Validation\Validator as v;
```

Now create a validatable from a spec like this:

```php
$validatable = s::spec(array(
    'foo' => 'string',
    'bar' => s::nonEmpty('string'),
    'baz' => s::nonEmpty(array('int')),
    'qux' => s::optional(s::nonEmpty(array(
        'foo' => 'number',
        'bar' => v::oneOf(v::arr(), v::int()),
    ))),
));
```

*Warning: `s::optional` can come anywhere, but to allow keys to be omitted from an array, it must be the first in the spec for that key's value!*

Then you validate normally:

```php
$validatable->validate($someTestData); // returns true or false
$validatable->assert($someTestData); // throws an exception
$validatable->check($someTestData); // throws an exception, only finds the first error
```

For example, all these test arrays would pass:

```php
$test1 = array(
    'foo' => '',
    'bar' => 'Lorem ipsum',
    'baz' => array(1, 2, 3),
    'qux' => array(
        'foo' => 1.23,
        'bar' => array(),
    ),
);

$test2 = array(
    'foo' => '',
    'bar' => 'Lorem ipsum',
    'baz' => array(1, 2, 3),
    'qux' => null,
);

$test3 = array(
    'foo' => '',
    'bar' => 'Lorem ipsum',
    'baz' => array(1, 2, 3),
);
```

When you get an exception from `assert` or `check`, it will be a descendant of `\Respect\Validation\Exceptions\AbstractNestedException`. In particular, you can use `$e->getFullMessage()` to get a nicely formatted tree of broken rules.

## Requirements

- Development
  - PHP 5.3.3 or later (to run PHPUnit on)
- Production
  - PHP 5.3.0 or later

## Testing

Use a test runner from the project root, e.g. `vendor/bin/phpunit --colors`