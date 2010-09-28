<?php
class TFormInputText extends TFormInput
{
	protected $template  =  '<input type=":type" name=":name" id=":id" maxlength=":maxlength" size=":size" :etc/>';
	protected $type      =  "text";
	protected $name      =  "";


	function toHtml( )
	{
		return preg_replace('/:([a-z]+)/e', '$this->__get("\1")', $this->template);
	}
}
?>
