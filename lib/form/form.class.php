<?php
require_once "form/input.class.php";

class TForm
{
	private $inputs     = array();
	private $hiddens     = array();
	private $allowProp  = array('method','name','enctype');
	protected $template = '<form method=":method" name=":name" enctype=":enctype">';
	private $method     = "post";
	private $name       = "";
	private $enctype    = "application/x-www-form-urlencoded"; // "multipart/form-data";

	function __set($key, $value)
	{
		if(in_array($key, $this->allowProp)){
			$this->$key = $value;
		}
	}

	function __get($key)
	{
		return $this->$key;
	}

	function inputFactory( $option )
	{
		return TFormInput::factory($option);
	}

	function append( $input )
	{
		$this->inputs[] = $input;
	}

	function appendHidden( $key, $value )
	{
		$this->hiddens[] = array($key,$value);
	}

	function toHead( )
	{
		$code = preg_replace('/:([a-z]+)/e', '$this->__get("\1")', $this->template);
		foreach($this->hiddens as $h){
			$code.= '<input type="hidden" name="'.$h[0].'" value="'.$h[1].'">';
		}
		return $code;
	}

	function toTail( )
	{
		return '</form>';
	}

	function getInput( $key )
	{
		$args = func_get_args( );
		if(count($args) > 1){
			$ret = array();
			foreach($args as $arg ){
				$ret[$arg] = $this->getInput($arg);
			}
			return $ret;
		}

		foreach($this->inputs as $input) {
			if($input->name == $key){
				return $input;
			}
		}

	}

}
?>
