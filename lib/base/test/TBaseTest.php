<?php
require_once 'base/base.class.php';

class TBaseTest extends TTestCase
{
	function testCallUndefinedMethod( ){
		$obj = new TBase( );

		try{
			$obj->noMethod( );
		}catch(Exception $e){
			$this->assertEquals( "TException", get_class($e));
		}
	}

}
?>
