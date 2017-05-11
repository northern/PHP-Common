<?php

namespace Northern\Test\Common\Util;

use Northern\Common\Util\ObjectUtil as Obj;

class ObjectUtilTest extends \PHPUnit_Framework_TestCase
{
    const VALUE_A = 666;
    const VALUE_B = 777;
    const VALUE_C = 888;
    const VALUE_D = 999;

    public function testGetObjectEmpty()
    {
        $obj = (object)array();

        $value = Obj::get($obj, 'fum');
        $this->assertEquals(null, $value);
    }

    public function testGetObjectProperty()
    {
        $obj = $this->getTestObject();

        $value = Obj::get($obj, 'fum');
        $this->assertEquals(self::VALUE_D, $value);

        $value = Obj::get($obj, 'baz');
        $this->assertEquals(null, $value);

        $value = Obj::get($obj, 'baz', 'error');
        $this->assertEquals('error', $value);
    }

    public function testGetObjectPath()
    {
        $obj = $this->getTestObject();

        $value = Obj::get($obj, 'foo.bar.baz');
        $this->assertEquals(self::VALUE_A, $value);

        $value = Obj::get($obj, 'foo.bar.fum');
        $this->assertEquals(null, $value);
    }

    public function testGetObjectPathDelimiter()
    {
        $obj = $this->getTestObject();

        $value = Obj::get($obj, 'foo/bar/baz', null, '/');
        $this->assertEquals(self::VALUE_A, $value);

        $value = Obj::get($obj, 'foo/bar/fum', null, '/');
        $this->assertEquals(null, $value);
    }

    public function testSetObjectProperty()
    {
        $obj = (object)array();

        Obj::set($obj, 'foo', 123);
        $this->assertEquals(123, $obj->foo);
    }

    public function testSetObjectPath()
    {
        $obj = (object)array();

        Obj::set($obj, 'foo.bar.baz', 123);

        $value = Obj::get($obj, 'foo.bar.baz');
        $this->assertEquals(123, $value);
    }

    public function testSetObjectExisting()
    {
        $obj = (object)array(
            'foo' => self::VALUE_A,
        );

        Obj::set($obj, 'foo', self::VALUE_B);
        $this->assertEquals(self::VALUE_B, $obj->foo);
    }

    public function testSetObjectPathExisting()
    {
        $obj = (object)array(
            'foo' => (object)array(
                'bar' => (object)array(
                    'baz' => self::VALUE_A,
                ),
            ),
        );

        Obj::set($obj, 'foo.bar.baz', self::VALUE_B);

        $value = Obj::get($obj, 'foo.bar.baz');
        $this->assertEquals(self::VALUE_B, $value);
    }

    public function getTestObject()
    {
        $obj = (object)array(
            'fum' => self::VALUE_D,
            'foo' => (object)array(
                'bar' => (object)array(
                    'baz' => self::VALUE_A,
                    'bob' => self::VALUE_C,
                ),
            ),
        );

        return $obj;
    }
}
