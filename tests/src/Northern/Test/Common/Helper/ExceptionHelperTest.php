<?php 

namespace Northern\Test\Common\Helper;

use Northern\Common\Helper\ExceptionHelper;

class ExceptionHelperTest extends \PHPUnit_Framework_TestCase {
	
	public function testGetExceptionNameHierarchy()
	{
		try
		{
			$i = 1 / 0;
		}
		catch( \Exception $e )
		{
			$this->assertEquals("Division by zero", $e->getMessage() );

			$exceptionNameHierarchy = ExceptionHelper::getExceptionNameHierarchy( $e );

			$this->assertEquals("PHPUnit_Framework_Error_Warning", $exceptionNameHierarchy );
		}
	}

	public function testGetFormattedExceptionMessage()
	{
		try
		{
			$i = 1 / 0;
		}
		catch( \Exception $e )
		{
			$this->assertEquals("Division by zero", $e->getMessage() );

			$exceptionNameHierarchy = ExceptionHelper::getFormattedExceptionMessage( $e );
		}
	}

	public function testGetOriginalExceptionFileAndLineNumber()
	{
		try
		{
			$i = 1 / 0;
		}
		catch( \Exception $e )
		{
			$this->assertEquals("Division by zero", $e->getMessage() );

			$exceptionNameHierarchy = ExceptionHelper::getOriginalExceptionFileAndLineNumber( $e );
		}
	}

}
