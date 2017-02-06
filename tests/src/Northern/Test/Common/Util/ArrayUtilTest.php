<?php

namespace Northern\Test\Common\Util;

use Northern\Common\Util\ArrayUtil as Arr;

class ArrayUtilTest extends \PHPUnit_Framework_TestCase
{
    const VALUE_A = 666;
    const VALUE_B = 777;
    const VALUE_C = 888;
    const VALUE_D = 999;

    public function testGetArrayEmpty()
    {
        $data = array();

        $value = Arr::get($data, 'fum');
        $this->assertEquals(null, $value);
    }

    public function testGetArrayKey()
    {
        $data = $this->getTestData();

        $value = Arr::get($data, 'fum');
        $this->assertEquals(self::VALUE_D, $value);

        $value = Arr::get($data, 'baz');
        $this->assertEquals(null, $value);

        $value = Arr::get($data, 'baz', 'error');
        $this->assertEquals('error', $value);
    }

    public function testGetArrayPath()
    {
        $data = $this->getTestData();

        $value = Arr::get($data, 'foo.bar.baz');
        $this->assertEquals(self::VALUE_A, $value);

        $value = Arr::get($data, 'foo.bar.fum');
        $this->assertEquals(null, $value);
    }

    public function testGetArrayPathDelimiter()
    {
        $data = $this->getTestData();

        $value = Arr::get($data, 'foo/bar/baz', null, '/');
        $this->assertEquals(self::VALUE_A, $value);

        $value = Arr::get($data, 'foo/bar/fum', null, '/');
        $this->assertEquals(null, $value);
    }

    public function testSetArrayKey()
    {
        $data = array();

        Arr::set($data, 'foo', 123);
        $this->assertEquals(123, $data['foo']);
    }

    public function testSetArrayPath()
    {
        $data = array();

        Arr::set($data, 'foo.bar.baz', 123);

        $value = Arr::get($data, 'foo.bar.baz');
        $this->assertEquals(123, $value);
    }

    public function testSetArrayExisting()
    {
        $data = array(
            'foo' => 321,
        );

        Arr::set($data, 'foo', 123);
        $this->assertEquals(123, $data['foo']);
    }

    public function testSetArrayPathExisting()
    {
        $data = array(
            'foo' => array(
                'bar' => array(
                    'baz' => 321,
                ),
            ),
        );

        Arr::set($data, 'foo.bar.baz', 123);

        $value = Arr::get($data, 'foo.bar.baz');
        $this->assertEquals(123, $value);
    }

    public function testInsertArrayPath()
    {
        $data = $this->getTestData();

        Arr::insert($data, 'foo.bar.baz', self::VALUE_D);

        $value = Arr::get($data, 'foo.bar.baz');
        $this->assertEquals(self::VALUE_A, $value);

        Arr::insert($data, 'foo.bar.new', 'xyz');

        $value = Arr::get($data, 'foo.bar.new');
        $this->assertEquals('xyz', $value);
    }

    public function testDeleteArrayKey()
    {
        $data = $this->getTestData();

        Arr::delete($data, 'fum');

        $value = Arr::get($data, 'fum');
        $this->assertEquals(null, $value);

        Arr::delete($data, 'foo.fum');
    }

    public function testDeleteArrayPath()
    {
        $data = $this->getTestData();

        Arr::delete($data, 'foo.bar.baz');

        $value = Arr::get($data, 'foo.bar.baz');
        $this->assertEquals(null, $value);

        Arr::delete($data, 'foo.bar.bob');

        $value = Arr::get($data, 'foo.bar.bob');
        $this->assertEquals(null, $value);
    }

    public function testDeleteArrayPathMultiple()
    {
        $data = $this->getTestData();

        Arr::delete($data, array('foo.bar.baz', 'foo.bar.bob'));

        $value = Arr::get($data, 'foo.bar.baz');
        $this->assertEquals(null, $value);

        $value = Arr::get($data, 'foo.bar.bob');
        $this->assertEquals(null, $value);
    }

    public function testDeleteArrayPaths()
    {
        $data = $this->getTestData();

        $paths = array(
            'foo.bar.baz',
            'foo.bar.bob',
        );

        Arr::delete($data, $paths);

        $value = Arr::get($data, 'foo.bar.baz', null);
        $this->assertEquals(null, $value);

        $value = Arr::get($data, 'foo.bar.bob', null);
        $this->assertEquals(null, $value);
    }

    public function testExistsArrayKey()
    {
        $data = $this->getTestData();

        $value = Arr::exists($data, 'foo');
        $this->assertEquals(true, $value);

        $value = Arr::exists($data, 'bar');
        $this->assertEquals(false, $value);
    }

    public function testExistsArrayPath()
    {
        $data = $this->getTestData();

        $value = Arr::exists($data, 'foo.bar.baz');
        $this->assertEquals(true, $value);

        $value = Arr::exists($data, 'foo.bar.fum');
        $this->assertEquals(false, $value);
    }

    public function testPrefixArray()
    {
        $data = array('1', '2', '3');

        $data = Arr::prefix($data, '$');

        $this->assertEquals('$1', $data[0]);
        $this->assertEquals('$2', $data[1]);
        $this->assertEquals('$3', $data[2]);
    }

    public function testPrefix()
    {
        $data = array('param1', 'param2', 'param3');

        $values = Arr::prefix($data, '&');

        $this->assertEquals('&param1', $values[0]);
        $this->assertEquals('&param2', $values[1]);
        $this->assertEquals('&param3', $values[2]);
    }

    public function testPostfix()
    {
        $data = array('param1', 'param2', 'param3');

        $values = Arr::postfix($data, '&');

        $this->assertEquals('param1&', $values[0]);
        $this->assertEquals('param2&', $values[1]);
        $this->assertEquals('param3&', $values[2]);
    }

    public function testFlatten()
    {
        $data = array('param1' => 'foo', 'param2' => 'bar', 'param3' => 'baz');

        $values = Arr::flatten($data);

        $this->assertEquals('param1=foo', $values[0]);
        $this->assertEquals('param2=bar', $values[1]);
        $this->assertEquals('param3=baz', $values[2]);
    }

    public function testKeys()
    {
        $data = array('param1' => 'foo', 'param2' => 'bar', 'param3' => 'baz');

        $values = Arr::keys($data);

        $this->assertEquals('param1', $values[0]);
        $this->assertEquals('param2', $values[1]);
        $this->assertEquals('param3', $values[2]);
    }

    public function testKeysWithPrefix()
    {
        $data = array('param1' => 'foo', 'param2' => 'bar', 'param3' => 'baz');

        $values = Arr::keys($data, '&');

        $this->assertEquals('&param1', $values[0]);
        $this->assertEquals('&param2', $values[1]);
        $this->assertEquals('&param3', $values[2]);
    }

    public function testValues()
    {
        $data = array('param1' => 'foo', 'param2' => 'bar', 'param3' => 'baz');

        $values = Arr::values($data);

        $this->assertEquals('foo', $values[0]);
        $this->assertEquals('bar', $values[1]);
        $this->assertEquals('baz', $values[2]);
    }

    public function testValuesWithPrefix()
    {
        $data = array('param1' => 'foo', 'param2' => 'bar', 'param3' => 'baz');

        $values = Arr::values($data, '&');

        $this->assertEquals('&foo', $values[0]);
        $this->assertEquals('&bar', $values[1]);
        $this->assertEquals('&baz', $values[2]);
    }

    public function testMap()
    {
        $data = array('foo', 'bar' => array('fum', 'bob'), 'baz');

        $values = Arr::map('strtoupper', $data);

        $this->assertEquals('FOO', $values[0]);
        $this->assertEquals('BAZ', $values[1]);

        $value = Arr::get($values, 'bar.0');
        $this->assertEquals('FUM', $value);

        $value = Arr::get($values, 'bar.1');
        $this->assertEquals('BOB', $value);
    }

    public function testRemap()
    {
        $data = $this->getTestData();

        $map = array(
            'foo.bar.baz' => 'baz.bar.foo',
            'foo.bar.bob' => 'bob.bar.foo',
        );

        $data = Arr::remap($data, $map);

        $value = Arr::get($data, 'baz.bar.foo');
        $this->assertTrue($value === self::VALUE_A);

        $value = Arr::get($data, 'bob.bar.foo');
        $this->assertTrue($value === self::VALUE_C);
    }

    public function testRemapCollection()
    {
        $collection = array(
            $this->getTestData(),
            $this->getTestData(),
        );

        $map = array(
            'foo.bar.baz' => 'baz.bar.foo',
            'foo.bar.bob' => 'bob.bar.foo',
        );

        $collection = Arr::remapCollection($collection, $map);

        foreach ($collection as $data) {
            $value = Arr::get($data, 'baz.bar.foo');
            $this->assertTrue($value === self::VALUE_A);

            $value = Arr::get($data, 'bob.bar.foo');
            $this->assertTrue($value === self::VALUE_C);
        }
    }

    public function testMerge()
    {
        $dataA = $this->getTestData();

        $map = array(
            'foo.bar.baz' => 'baz.bar.foo',
            'foo.bar.bob' => 'bob.bar.foo',
        );

        $dataB = Arr::remap($dataA, $map);

        $data = Arr::merge($dataA, $dataB);

        $value = Arr::get($data, 'fum');
        $this->assertTrue($value === self::VALUE_D);

        $value = Arr::get($data, 'foo.bar.baz');
        $this->assertTrue($value === self::VALUE_A);

        $value = Arr::get($data, 'foo.bar.bob');
        $this->assertTrue($value === self::VALUE_C);

        $value = Arr::get($data, 'baz.bar.foo');
        $this->assertTrue($value === self::VALUE_A);

        $value = Arr::get($data, 'bob.bar.foo');
        $this->assertTrue($value === self::VALUE_C);
    }

    public function testContains()
    {
        $haystack = array('A', 'B', 'C');

        $value = Arr::contains('A', $haystack);
        $this->assertTrue($value);

        $value = Arr::contains(array('A'), $haystack);
        $this->assertTrue($value);

        $value = Arr::contains(array('A', 'B'), $haystack);
        $this->assertTrue($value);

        $value = Arr::contains(array('A', 'B', 'C'), $haystack);
        $this->assertTrue($value);

        $value = Arr::contains('D', $haystack);
        $this->assertFalse($value);

        $value = Arr::contains(array('D'), $haystack);
        $this->assertFalse($value);
    }

    public function testExtract()
    {
        $data = $this->getTestData();

        $value = Arr::exists($data, 'foo.bar.baz');
        $this->assertTrue($value);

        $value = Arr::extract($data, 'foo.bar.baz');
        $this->assertTrue($value === self::VALUE_A);

        $value = Arr::exists($data, 'foo.bar.baz');
        $this->assertFalse($value);
    }

    public function testCompare()
    {
        $data1 = $this->getTestData();
        $data2 = $this->getTestData();

        $value = Arr::compare($data1, $data2);
        $this->assertTrue($value);

        $data2 = array_reverse($data1, true);

        $value = Arr::compare($data1, $data2);
        $this->assertFalse($value);
    }

    public function getTestData()
    {
        $data = array(
            'fum' => self::VALUE_D,
            'foo' => array(
                'bar' => array(
                    'baz' => self::VALUE_A,
                    'bob' => self::VALUE_C,
                ),
            ),
        );

        return $data;
    }
}
