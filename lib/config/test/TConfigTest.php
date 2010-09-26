<?php require_once 'config/config.class.php';

class TConfigTest extends TTestCase
{

	function init( ){
	}


	function testFactory( ){
		$o = TConfig::factory("ini");
		$this->assertEquals( "TConfigIni", get_class($o) );
	}

}
?>
