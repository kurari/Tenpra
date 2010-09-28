<?php
/**
 * X Library
 */

require_once 'base/exception.class.php';
class XRequestException extends XBaseException {}


class XRequest 
{
	private $_headers = array();

	function addHeader( $key, $value )
	{
		$this->_headers[$key] = $value;
	}

	function bind( )
	{

	}

}
?>
