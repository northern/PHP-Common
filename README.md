# PHP Common

[![Build Status](https://travis-ci.org/northern/PHP-Common.png?branch=dev-master)](https://travis-ci.org/northern/PHP-Common)

PHP Common is a PHP library containing a set of common functionality.

To run tests use:

    vendor/bin/phpunit -c tests/

To generate code coverage report use:

    vendor/bin/phpunit -c tests/ --coverage-html ./reports

Find PHP Common on Packagist:

https://packagist.org/packages/northern/common

## Table of Contents

  1. [ArrayHelper](#arrayhelper)
    1. [get](#get)
    1. [set](#set)
    1. [insert](#insert)
    1. [delete](#delete)
    1. [exists](#exists)
    1. [prefix](#prefix)
    1. [flatten](#flatten)
    1. [keys](#keys)
    1. [values](#values)
    1. [contains](#contains)
  1. [FilterHelper](#filterhelper)
    1. [Basic usage](#basicusage)

## ArrayHelper

To use `ArrayHelper` you need to import it.

    use Northern\Common\Helper\ArrayHelper as Arr;

### get

To get a value from an array use `get`:

    $a = array(
       'foo' => 'bar'
    );

    $value = Arr::get( $a, 'foo' );

    // $value == 'bar'

You can specify a default value in case the key you're trying to retrieve doesn't exists:

    $value = Arr::get( $a, 'baz', NULL );

    // $value == NULL

To get a nested value from an array you can specify a path:

    $a = array(
       'foo' => array(
          'bar' => array(
             'baz' => 123
          )
       )
    );

    $value = Arr::get( $a, 'foo.bar.baz' );

    // $value == 123
    
If required, you can use an alternate delimiter:

    $value = Arr::getPath( $a, 'foo/bar/baz', NULL, '/' );

    // $value == 123

### set

To set a value or nested value use the `set` method:

    $a = array();
    
    Arr::set( $a, 'foo.bar.baz', 123 );
    
    // $a = array( 'foo' => array( 'bar' => array( 'baz' => 123 ) ) );
    
### insert

With `insert` you can create a new value at a path or key, however, the path will only be created if it does not yet exists.

    $a = array();
    
    Arr::set( $a, 'foo.bar.baz', 123 );
    
    Arr::insert( $a, 'foo.bar.baz', 123 );
    
    // The insert statement does nothing.
    
### delete

It's also possible to delete a key or path:

    Arr::delete( $a, 'foo.bar.baz' );    
    
Or to delete multiple paths or keys at once:

    Arr::delete( $a, array('fum', 'foo.bar.baz', foo.bar.bob') );

### exists

To test if a key or path exists use:

    $value = Arr::exists( $a, 'foo.bar.baz' ) );
    
    // $value == TRUE
    
### prefix

If you need to prefix all the values in an array, use the `prefix` method:

    $a = array('1', '2', '3');
   
    $values = Arr::prefix( $a, '$' );
   
    // $values = array('$1', '$2', '$3');

### flatten

Sometimes you need to "flatten" an array, i.e. glueing the keys and values together with a symbol or character:

    $a = array('param1' => '123', 'param2' => 'xyz');
    
    $values = Arr::flatten( $a );
    
    // $values = array('param1=123', 'param2=xyz');
    
Or use a different 'glue' character from the default '=':

    $values = Arr::flatten( $a, '|' );
    
    // $values = array( 'param1|123', 'param2|xyz' );

### keys

Returns the keys of an array in the same way the `array_keys` function works, however, `keys` allows you to specifiy a prefix:

    $a = array('param1' => '123', 'param2' => 'xyz');
    
    $values = Arr::keys( $a, '&' );
    
    // $values = array( '&param1', '&param2' )

### values

Returns the values of an array in the same way the `array_values` function works, however, `values` allows you to specify a prefix:

    $a = array('param1' => '123', 'param2' => 'xyz');
    
    $values = Arr::values( $a, '&' );
    
    // $values = array( '&123', '&xyz' )

### contains

Tests if the values of one array exist in another. E.g:

    $b = Arr::contains( array('A', 'B'), array('A', 'B', 'C') );

    // $b = TRUE

The above tests if the values of the first array (needle) exist in the second array (haystack), which in the above example is true.

## FilterHelper

To use `FilterHelper` you need to import it.

    use Northern\Common\Helper\FilterHelper;

### Basic usage

The FilterHelper applies filters to values in an array. A basic example:

    $values = array( 'price' => -123.456 );

    $filters = array( 'price' => 'Round' )

    $values = FilterHelper::filter( $values, $filters );

    // $values = array( 'price' => -123 );

If you want to apply multiple filters to a value simply use an array of filters:

    $filters = array( 'price' => array('Round', 'Abs') )

    $values = FilterHelper::filter( $values, $filters );

    // $values = array( 'price' => 123 );

Notice how the price is a round positive number.

Some filters take parameters, such as the MoneyFormat filter. I this case the filter definition needs to be defined as an array itself. E.g:

    $values = array( 'price' => 123.456 );

    $filters = array( 'price' => array( array('MoneyFormat', array('$%i') ) ) );

    $values = FilterHelper::filter( $values, $filters );

    // $values = array( 'price' => '$123.46' );

Note the double `array` around the filter. The first `array` indicates an `array` of filters. We have to do this because the filter we're calling takes arguments on the constructor, i.e. the money format string. As you can see, the second `array` has two indexes. The first index specifies the filter name and the second index represents an `array` of parameters that will be passed into the constructor of the array. Please note that if the filter didn't require any arguments, the double array notation would not be necassery.

With PHP 5.4+ the array notation can be shortened by using the new bracket style definition syntax:

    $filters = [ 'price' => [ ['MoneyFormat', ['$%i'] ] ] ];

It's also possible to apply filters to nested arrays. To specify the filters simply use the `ArrayFilter` nested notation to specify the path. E.g:

    $values = array(
      'receipt' => array(
         'total' => 5.4556,
         'tax'   => .45
      )
    );

    $filters = array(
      'receipt.total' => array( array('MoneyFormat', array('$%i') ) ),
      'receipt.tax'   => array( array('MoneyFormat', array('$%i') ) )
    );

## Filters

### BoolFilter

Casts a value to a boolean:

    $values = array( 'myFalse' = "0", 'myTrue' => "1" );

    $filters = array( 'myFalse' => 'Bool', 'myTrue' => 'Bool' );

    // $values = array( 'myFalse' => FALSE, 'myTrue' => TRUE )

