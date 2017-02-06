<?php

namespace Northern\Test\Common\Util;

use Northern\Common\Util\ExceptionUtil;

class ExceptionUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testGetExceptionNameHierarchy()
    {
        try {
            $i = 1 / 0;
        } catch (\Exception $e) {
            $this->assertEquals("Division by zero", $e->getMessage());

            $exceptionNameHierarchy = ExceptionUtil::getExceptionNameHierarchy($e);

            $this->assertEquals("PHPUnit_Framework_Error_Warning", $exceptionNameHierarchy);
        }
    }

    public function testGetFormattedExceptionMessage()
    {
        try {
            $i = 1 / 0;
        } catch (\Exception $e) {
            $this->assertEquals("Division by zero", $e->getMessage());

            $exceptionNameHierarchy = ExceptionUtil::getFormattedExceptionMessage($e);
        }
    }

    public function testGetOriginalExceptionFileAndLineNumber()
    {
        try {
            $i = 1 / 0;
        } catch (\Exception $e) {
            $this->assertEquals("Division by zero", $e->getMessage());

            $exceptionNameHierarchy = ExceptionUtil::getOriginalExceptionFileAndLineNumber($e);
        }
    }
}
