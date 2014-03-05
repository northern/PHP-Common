<?php 

namespace Northern\Test\Common\Helper;

use Northern\Common\Helper\ArrayHelper as Arr;

class ArrayHelperTest extends \PHPUnit_Framework_TestCase {
	
	const VALUE_A = 666;
	const VALUE_B = 777;
	const VALUE_C = 888;
	
	public function testGetPath()
	{
		$data = $this->getTestData();
		
		$value = Arr::getPath( $data, 'foo.bar.baz' );
		
		$this->assertTrue( $value === self::VALUE_A );
		
		return $data;
	}

	/**
	 * @depends testGetPath
	 */
	public function testSetPath( $data )
	{
		Arr::setPath( $data, 'foo.bar.bat', self::VALUE_B );
		
		$value = Arr::getPath( $data, 'foo.bar.bat' );
		
		$this->assertTrue( $value === self::VALUE_B );
		
		return $data;
	}
	
	/**
	 * @depends testSetPath
	 */
	public function testDeletePath( $data )
	{
		Arr::deletePath( $data, 'foo.bar.bob' );
		
		$value = Arr::getPath( $data, 'foo.bar.bob' );
		
		$this->assertTrue( empty( $value ) === TRUE );	
		
		return $data;
	}
	
	/**
	 * @depends testDeletePath
	 */
	public function testDeletePaths( $data )
	{
		Arr::deletePaths( $data, array('foo.bar.baz', 'foo.bar.bat') );
		
		$value = Arr::getPath( $data, 'foo.bar.baz' );
		
		$this->assertTrue( empty( $value ) === TRUE );
			
		$value = Arr::getPath( $data, 'foo.bar.bat' );
		
		$this->assertTrue( empty( $value ) === TRUE );
	}
	
	public function testMap()
	{
		$data = $this->getTestData();
		
		$map = array(
			'foo.bar.baz' => 'baz.bar.foo',
			'foo.bar.bob' => 'bob.bar.foo',				
		);
		
		$data = Arr::remap( $data, $map );
		
		$value = Arr::getPath( $data, 'baz.bar.foo' );

		$this->assertTrue( $value === self::VALUE_A );
		
		$value = Arr::getPath( $data, 'bob.bar.foo' );
		
		$this->assertTrue( $value === self::VALUE_C );
	}
	
	public function getTestData()
	{
		$data = array(
			'foo' => array(
				'bar' => array(
					'baz' => self::VALUE_A,
					'bob' => self::VALUE_C,
				)
			)
		);
		
		return $data;
	}
		
}
