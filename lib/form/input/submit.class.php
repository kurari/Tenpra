<?php
require_once 'form/input/text.class.php';

class TFormInputSubmit extends TFormInputText
{
	protected $template = '<input type=":type" name=":name" id=":id" :etc value=":label"/>';
	protected $type = "submit";
	protected $name = "";

	function __construct( )
	{
		parent::__construct( );
		$this->addClass('submit');
	}


	function toHtml( )
	{
		return preg_replace('/:([a-z]+)/e', '$this->__get("\1")', $this->template);
	}
}
?>
