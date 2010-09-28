<?php
/**
 * TSystem
 * ---
 * @file class/base.class.php
 * @author Hajime MATSUMOTO
 */
class TException extends Exception { 

	function __construct( $message ){
		$args = func_get_args( );
		$vars = array_slice($args, 1);
		if(!empty($vars)) $message = vsprintf($message, $vars);
		parent::__construct( $message );
	}
}

/**
 * TBase Object
 *
 */
class TBase {

	function __call( $name, $args )
	{
		throw new TException("%s is not defined %s", $name, print_r($args,true));
	}

}
?>
