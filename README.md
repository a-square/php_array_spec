# php_array_spec

[![Build Status](https://img.shields.io/travis/Kallikanzarid/php_array_spec/master.svg?style=flat-square)](http://travis-ci.org/Kallikanzarid/php_array_spec)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/Kallikanzarid/php_array_spec/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/Kallikanzarid/php_array_spec/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Kallikanzarid/php_array_spec/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/Kallikanzarid/php_array_spec/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/lightsoft/php_array_spec.svg?style=flat-square)](https://packagist.org/packages/lightsoft/php_array_spec)
[![Total Downloads](https://img.shields.io/packagist/dt/lightsoft/php_array_spec.svg?style=flat-square)](https://packagist.org/packages/lightsoft/php_array_spec)
[![License](https://img.shields.io/packagist/l/lightsoft/php_array_spec.svg?style=flat-square)](https://packagist.org/packages/lightsoft/php_array_spec)


An array-based embedded DSL for creating complex nested Respect/Validation validatables. It makes specs for your parsed JSON look more like the arrays they will be parsing, so it's more readable. It also provides an utility method `s::explain($exception)` which formats nested exceptions resulting from failed validation to a human-readable tree.

## Usage

First, you may want to create a convenience shortcut:

```php
use Lightsoft\ArraySpec\Spec as s;
```

Respect/Validation is a dependency, and you can use its validatables inside specs freely, so it's also useful to create a shortcut to that:

```php
use Respect\Validation\Validator as v;
```

TODO: more...

## Requirements

- Development
  - PHP 5.3.3 or later (to run PHPUnit on)
- Production
  - PHP 5.3.0 or later

## Testing

Use a test runner from the project root, e.g. `phpunit --colors test`