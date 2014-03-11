# PHP Helpers

To run tests use:

    vendor/bin/phpunit -c tests/

To generate code coverage report use:

    vendor/bin/phpunit -c tests/ --coverage-html ./reports

## How to use

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
    
If required, you use an alternate the delimiter:

    $value = Arr::getPath( $a, 'foo/bar/baz', NULL, '/' );

    // $value == 123

### set

To set a value or nested value use the `set` method:

    $a = array();
    
    Arr::set( $a, 'foo.bar.baz', 123 );
    
    // $a = array( 'foo' => array( 'bar' => array( 'baz' => 123 ) ) );
    
### delete

It's also possible to delete a key or path:

    Arr::delete( $a, 'foo.bar.baz' );    
    
### exists

To test if a key or path exists use:

    $value = Arr::isPath( $a, 'foo.bar.baz' ) );
    
    // $value == TRUE
    
### prefix

If you need to prefix all the values in an array, use `prefix`:

    $a = array('1', '2', '3');
   
    Arr::prefix( $a, '$' );
   
    // $a = array('$1', '$2', '$3');

### flatten

Sometimes you need to "flatten" array, ie. glueing it's keys and values together with a symbol:

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


 