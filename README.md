# PHP Helpers

To run tests use:

    vendor/bin/phpunit -c tests/

To generate code coverage report use:

    vendor/bin/phpunit -c tests/ --coverage-html ./reports


## How to use

To use `ArrayHelper` you need to import it.

    use Northern\Common\Helper\ArrayHelper as Arr;

To get a value from an array use `get`:

    $a = array(
       'foo' => 'bar'
    );


    $value = Arr::get( $a, 'foo' );

    // $value == 'bar'

You can specify a default value if the key you're trying to retrieve doesn't exists:

    $value = Arr::get( $a, 'baz', NULL );

    // $value == NULL

You can also specify a path with the `getPath` method:

    $a = array(
       'foo' => array(
          'bar' => array(
             'baz' => 123
          )
       )
    );

    $value = Arr::get( $a, 'foo.bar.baz' );

    // $value == 123

