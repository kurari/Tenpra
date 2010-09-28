<?php
class TFormInput
{
	private $allowProp				= array('name','id','value','class','style','label','size','maxlength');
	protected $template_label = '<label for=":id">:label</label>';

	protected $classes = array();
	protected $styles  = array();
	protected $others  = array();

	public static function factory( $option )
	{
		$type = isset($option['type']) ? $option['type']: "text";

		$file = 'form/input/'.$type.'.class.php';
		$class = 'TFormInput'.ucfirst($type);

		require_once $file;
		$input = new $class( );
		$input->set($option);
		return $input;
	}

	public function __construct( ){
	}

	public function set( $array ){
		foreach($array as $k=>$v) {
			$this->__set($k, $v);
		}
	}

	protected function addAllowProp( $key ) {
		$this->allowProp[]  = $key;
	}

	public function __set($key, $value) 
	{
		if( in_array($key, $this->allowProp) ) {
			$this->$key = $value;
		}
	}

	public function __get($key)
	{
		$v = isset($this->$key) ? $this->$key: false;
		if($key == "id" && empty($v)) return $this->__get('name');

		if($key == "etc"){
			return $this->genTagPropEtc();
		}
		return $v;
	}

	public function __toString( )
	{
		return $this->toHtml( );
	}

	public function addClass( $class ){
		$this->classes[$class] = $class;
	}

	public function addStyle( $key, $value ){
		$this->styles[$key] = $valeu;
	}

	public function genTagPropEtc( )
	{
		$opt = array();
		$opt[] = 'class="'.implode(' ', $this->classes).'"';

		$ss ="";
		foreach($this->styles as $k=>$v){
			$ss .= "$k:$v;";
		}
		$opt[] = 'style="'.$ss.'"';

		foreach($this->others as $k=>$v){
			$opt[] = "$k=\"$v\"";
		}
		return implode(" ",$opt);
	}

	function toHtml( )
	{
		return preg_replace('/:([a-z]+)/e', '$this->__get("\1")', $this->template);
	}

	function toLabel( )
	{
		return preg_replace('/:([a-z]+)/e', '$this->__get("\1")', $this->template_label);
	}
}
?>
