<?php

namespace Northern\Test\Common\Helper;

use Northern\Common\Helper\Filter;
use Northern\Common\Helper\FilterHelper;

class FilterHelperTest extends \PHPUnit_Framework_TestCase {

	/*public function testBasicFilter()
	{
		$values = array(
			'price'           => 123.456,
			'mustBeTrueBool'  => "1",
			'mustBeFalseBool' => "0",
			'mustBeFloat'     => '123.456',
			'mustBeUcFirst'   => 'this must be ucfirst',
			'mustBeLowerCase' => 'THIS MUST ALL BE LOWER CASE',
			'mustBeUpperCase' => 'this must all be upper case',
			'mustBeCamelCase' => 'thIs must aLL bE caMEl cAsE',
			'mustBeRound'     => -123.456,
		);

		$filters = array(
			'price'           => [ ['MoneyFormat', ['$%i'] ] ],
			'mustBeTrueBool'  => [ 'Int', 'Bool' ],
			'mustBeFalseBool' => [ 'Int', 'Bool' ],
			'mustBeFloat'     => 'Float',
			'mustBeUcFirst'   => 'Ucfirst',
			'mustBeLowerCase' => 'Lowercase',
			'mustBeUpperCase' => 'Uppercase',
			'mustBeCamelCase' => [ 'Lowercase', 'Camelcase' ],
			'mustBeRound'     => [ 'Round', 'Abs' ],
		);

		$filteredValues = FilterHelper::filter( $values, $filters );

		print_r( $filteredValues );


		$values = [ 'hello', 'world', 123 ];

		$filters = [ ['Uppercase'], ['Ucfirst'], [ ['MoneyFormat', ['$%i'] ] ] ];

		$filteredValues = FilterHelper::filter( $values, $filters );

		print_r( $filteredValues );


		$values = array(
			'address' => array(
				'address1' => 'Some address',
				'location' => array(
					'lng' => 123.456,
					'lat' => 456.321,
				)
			)
		);

		$filters = array(
			'address.location.lng' => 'Round',
			'address.location.lat' => [ ['MoneyFormat', ['$%i'] ] ],
		);

		$filteredValues = FilterHelper::filter( $values, $filters );

		print_r( $filteredValues );
	}*/

	public function testBoolFilter()
	{
		$filter = new Filter\BoolFilter();

		$this->assertTrue( $filter->filter('1') );
		$this->assertFalse( $filter->filter('0') );
	}

	public function testIntFilter()
	{
		$filter = new Filter\IntFilter();

		$this->assertEquals( $filter->filter( 123.456 ), 123 );
		$this->assertEquals( $filter->filter( 123.678 ), 123 );
		$this->assertEquals( $filter->filter( '123' ), 123 );
	}

	public function testIntCastFilter()
	{
		$filter = new Filter\IntCastFilter();

		$this->assertEquals( $filter->filter( 123.456 ), 123 );
		$this->assertEquals( $filter->filter( 123.678 ), 123 );
		$this->assertEquals( $filter->filter( '123' ), 123 );
	}

	public function testFloatFilter()
	{
		$filter = new Filter\FloatFilter();

		$this->assertEquals( $filter->filter( 123.456 ), 123.456 );
		$this->assertEquals( $filter->filter( 123.678 ), 123.678 );
		$this->assertEquals( $filter->filter( '123.456' ), 123.456 );
	}

	public function testRoundFilter()
	{
		$filter = new Filter\RoundFilter();

		$this->assertEquals( $filter->filter( 123.456 ), 123 );
		$this->assertEquals( $filter->filter( 123.678 ), 124 );
	}

	public function testFloorFilter()
	{
		$filter = new Filter\FloorFilter();

		$this->assertEquals( $filter->filter( 123.456 ), 123 );
		$this->assertEquals( $filter->filter( 123.678 ), 123 );
	}

	public function testCeilFilter()
	{
		$filter = new Filter\CeilFilter();

		$this->assertEquals( $filter->filter( 123.456 ), 124 );
		$this->assertEquals( $filter->filter( 123.678 ), 124 );
	}

	public function testAbsFilter()
	{
		$filter = new Filter\AbsFilter();

		$this->assertEquals( $filter->filter( -123.456 ), 123.456 );
		$this->assertEquals( $filter->filter( -123.678 ), 123.678 );
	}

	public function testLowercaseFilter()
	{
		$filter = new Filter\LowercaseFilter();

		$this->assertEquals( $filter->filter( 'HELLO WORLD' ), 'hello world' );
	}

	public function testUppercaseFilter()
	{
		$filter = new Filter\UppercaseFilter();

		$this->assertEquals( $filter->filter( 'hello world' ), 'HELLO WORLD' );
	}

	public function testCamelcaseFilter()
	{
		$filter = new Filter\CamelcaseFilter();

		$this->assertEquals( $filter->filter( 'hello world' ), 'Hello World' );
	}

	public function testUcfirstFilter()
	{
		$filter = new Filter\UcfirstFilter();

		$this->assertEquals( $filter->filter( 'hello' ), 'Hello' );
	}

	public function testMoneyFormatFilter()
	{
		$filter = new Filter\MoneyFormatFilter('$%i');

		$this->assertEquals( $filter->filter( 123.456 ), '$123.46' );
	}

}
