<?php
/**
 * Unit Test Tool
 * ----
 * @author Hajime
 */

/**
 * Test Case
 */
class TTestCase {

	public $results     = array(); /* results */
	public $tests = array( );

	function __construct( )
	{
		foreach( get_class_methods( $this ) as $method ) {
			if( substr($method, 0, 4) == "test" ) {
				$this->tests[] = $method;
			}
		}
	}

	function init( ) { }

	function assertEquals( $ok, $value, $message = 'assert equals') {
		if( $ok != $value ) $this->results[] = array( false, $message, var_export($ok,true)." ".var_export($value, true));
		else $this->results[] = array( true, $message, "");
	}
	function assertTrue( $value, $message = 'assert true') {
		if( true !== $value ) $this->results[] = array( false, $message, var_export($value, true));
		else $this->results[] = array( true, $message, "");
	}

	function run( )
	{
		$results = array();

		foreach($this->tests as $test){
			$this->results = array();
			call_user_func(array($this,$test));
			$results[$test] = $this->results;
		}
		return $results;
	}
}
?>
