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

### getPath

To get a nested value from an array you can specify a path with the `getPath` method:

    $a = array(
       'foo' => array(
          'bar' => array(
             'baz' => 123
          )
       )
    );

    $value = Arr::getPath( $a, 'foo.bar.baz' );

    // $value == 123

And you can set a default value in case the path you're trying to retrieve doesn't exists:

    $values = Arr::getPath( $a, 'foo.bar.bob', NULL );
    
    // $value == NULL
    
If required, you use an alternate delimter:

    $value = Arr::getPath( $a, 'foo/bar/baz', NULL, '/' );

    // $value == 123

### setPath

To set a nested value use the `setPath` method.

    $a = array();
    
    Arr::setPath( $a, 'foo.bar.baz', 123 );
    
    // $a = array( 'foo' => array( 'bar' => array( 'baz' => 123 ) ) );
    
### deletePath

It's also possible to delete a path:

    Arr::deletePath( $a, 'foo.bar.baz' );
    
### isPath

To test is a path exists:

    if( Arr::isPath( $a, 'foo.bar.baz' ) ) {
       // OK
    }
    
### prefix

If you need to prefix all the values in an array, use `prefix`:

    $array('1', '2', '3');
   
    Arr::prefix( $a, '$' );
   
    // $a = array('$1', '$2', '$3');
    