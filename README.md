# PHP Common

[![Build Status](https://travis-ci.org/northern/PHP-Common.png?branch=dev-master)](https://travis-ci.org/northern/PHP-Common)

PHP Common is a PHP library containing a set of common functionality.

To run tests use:

    composer install -v

    vendor/bin/phpunit

Find PHP Common on Packagist:

https://packagist.org/packages/northern/common

## ArrayHelper

To use `ArrayHelper` you need to import it.
```PHP
use Northern\Common\Helper\ArrayHelper as Arr;
```
### get

To get a value from an array use `get`:
```PHP
$a = array(
   'foo' => 'bar'
);

$value = Arr::get( $a, 'foo' );

// $value == 'bar'
```
You can specify a default value in case the key you're trying to retrieve doesn't exists:
```PHP
$value = Arr::get( $a, 'baz', NULL );

// $value == NULL
```
To get a nested value from an array you can specify a path:
```PHP
$a = array(
   'foo' => array(
      'bar' => array(
         'baz' => 123
      )
   )
);

$value = Arr::get( $a, 'foo.bar.baz' );

// $value == 123
```    
If required, you can use an alternate delimiter:
```PHP
$value = Arr::getPath( $a, 'foo/bar/baz', NULL, '/' );

// $value == 123
```
### set

To set a value or nested value use the `set` method:
```PHP
$a = array();

Arr::set( $a, 'foo.bar.baz', 123 );

// $a = array( 'foo' => array( 'bar' => array( 'baz' => 123 ) ) );
```    
### insert

With `insert` you can create a new value at a path or key, however, the path will only be created if it does not yet exists.
```PHP
$a = array();

Arr::set( $a, 'foo.bar.baz', 123 );

Arr::insert( $a, 'foo.bar.baz', 123 );

// The insert statement does nothing.
```    
### delete

It's also possible to delete a key or path:
```PHP
Arr::delete( $a, 'foo.bar.baz' );    
```    
Or to delete multiple paths or keys at once:
```PHP
Arr::delete( $a, array('fum', 'foo.bar.baz', foo.bar.bob') );
```
### exists

To test if a key or path exists use:
```PHP
$value = Arr::exists( $a, 'foo.bar.baz' ) );

// $value == TRUE
```    
### prefix

If you need to prefix all the values in an array, use the `prefix` method:
```PHP
$a = array('1', '2', '3');

$values = Arr::prefix( $a, '$' );

// $values = array('$1', '$2', '$3');
```
### flatten

Sometimes you need to "flatten" an array, i.e. glueing the keys and values together with a symbol or character:
```PHP
$a = array('param1' => '123', 'param2' => 'xyz');

$values = Arr::flatten( $a );

// $values = array('param1=123', 'param2=xyz');
```    
Or use a different 'glue' character from the default '=':
```PHP
$values = Arr::flatten( $a, '|' );

// $values = array( 'param1|123', 'param2|xyz' );
```
### keys

Returns the keys of an array in the same way the `array_keys` function works, however, `keys` allows you to specifiy a prefix:
```PHP
$a = array('param1' => '123', 'param2' => 'xyz');

$values = Arr::keys( $a, '&' );

// $values = array( '&param1', '&param2' )
```
### values

Returns the values of an array in the same way the `array_values` function works, however, `values` allows you to specify a prefix:
```PHP
$a = array('param1' => '123', 'param2' => 'xyz');

$values = Arr::values( $a, '&' );

// $values = array( '&123', '&xyz' )
```
### contains

Tests if the values of one array exist in another. E.g:
```PHP
$b = Arr::contains( array('A', 'B'), array('A', 'B', 'C') );

// $b = TRUE
```
The above tests if the values of the first array (needle) exist in the second array (haystack), which in the above example is true.
