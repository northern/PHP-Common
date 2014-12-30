<?php 

namespace Northern\Test\Common\Helper;

use Northern\Common\Helper\ObjectHelper as Obj;

class SimpleApplyTestObject {

	protected $someProperty;

	public function setSomeProperty( $value )
	{
		$this->someProperty = $value;
	}

	public function getSomeProperty()
	{
		return $this->someProperty;
	}

}

class ComplexApplyTestObject {

	protected $simpleProperty;

	protected $simpleObject;

	public function __construct()
	{
		$this->simpleObject = new SimpleApplyTestObject();
	}

	public function setSimpleProperty( $value )
	{
		$this->simpleProperty = $value;
	}

	public function getSimpleProperty()
	{
		return $this->simpleProperty;
	}

	public function getSimpleObject()
	{
		return $this->simpleObject;
	}

}

class CollectionApplyTestObject {

	protected $someCollection;

	public function __construct()
	{
		$this->someCollection = array();
	}

	public function addSomeCollection( $value )
	{
		$this->someCollection[] = $value;
	}

	public function getSomeCollection()
	{
		return $this->someCollection;
	}

}

class ObjectHelperApplyTest extends \PHPUnit_Framework_TestCase {

	public function testSimpleObject()
	{
		$simpleObject = new SimpleApplyTestObject();

		$values = array(
			'someProperty' => 123,
		);

		Obj::apply( $simpleObject, $values );

		$someProperty = $simpleObject->getSomeProperty();
		$this->assertEquals( $someProperty, 123 );
	}

	public function testComplexObject()
	{
		$complexObject = new ComplexApplyTestObject();

		$values = array(
			'simpleProperty' => 'abc',
			'simpleObject' => array(
				'someProperty' => 123,
			),
		);

		Obj::apply( $complexObject, $values );

		$simpleProperty = $complexObject->getSimpleProperty();
		$this->assertEquals( $simpleProperty, 'abc');

		$someProperty = $complexObject->getSimpleObject()->getSomeProperty();
		$this->assertEquals( $someProperty, 123);
	}

	public function testCollectionObject()
	{
		$collectionObject = new CollectionApplyTestObject();

		$values = array(
			'someCollection' => array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
		);

		Obj::apply( $collectionObject, $values );

		$collection = $collectionObject->getSomeCollection();

		for( $i = 0; $i < 10; $i++ )
		{
			$this->assertEquals( $collection[$i], $i );
		}
	}

}
