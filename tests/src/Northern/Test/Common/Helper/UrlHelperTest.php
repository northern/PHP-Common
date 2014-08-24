<?php 

namespace Northern\Test\Common\Helper;

use Northern\Common\Helper\UrlHelper as Url;

class UrlHelperTest extends \PHPUnit_Framework_TestCase {

	public function testUsername()
	{
		$value = Url::getSlug('Alix Axel');
		$this->assertEquals( $value, 'alix-axel' );

		$value = Url::getSlug('Álix Ãxel');
		$this->assertEquals( $value, 'alix-axel' );

		$value = Url::getSlug('Álix----_Ãxel!?!?');
		$this->assertEquals( $value, 'alix-axel' );
	}

}
