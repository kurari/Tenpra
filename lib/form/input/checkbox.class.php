<?php
require_once 'form/input/text.class.php';

class TFormInputCheckbox extends TFormInputText
{
	protected $template = '<input type=":type" name=":name" id=":id" maxlength=":maxlength" size=":size" :etc/>';
	protected $type = "checkbox";
	protected $name = "";


	function toHtml( )
	{
		return preg_replace('/:([a-z]+)/e', '$this->__get("\1")', $this->template);
	}
}
?>
