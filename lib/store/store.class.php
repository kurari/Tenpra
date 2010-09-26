<?php
/**
 * 永続化キャッシュもでるを考え中
 * 単純なハッシュテーブル
 *
 */
require_once 'base/base.class.php';
class TStoreException extends TException { }


class TStore 
{
	/**
	 * values
	 */
	private $values;

	/**
	 * Set Value
	 *
	 * @param string key
	 * @param mixed value
	 */
	public function set( $key, $value = false )
	{
		if( is_array($key) ) {
			$ret = array();
			foreach($key as $k=>$v) $this->set($k, $v);
			return $ret;
		}
		if( !empty($this->section) ) $key = "$this->section.$key";
		if( false === strpos($key,'.') ){
			return $this->values[$key] = $value;
		}
		$arr = explode('.',$key);
		$fin = array_pop($arr);
		$ref =& $this->values;
		while( $k = array_shift($arr) ) $ref =& $ref[$k];
		$ref[$fin] = $value;
	}


	/**
	 * Set Value If Key Is Not Set Yet
	 *
	 * @param string
	 * @param mixed
	 */
	public function setIf( $key, $value = false) {
		if(is_array($key)) {
		 	foreach($key as $k=>$v) {
				$this->setIf($k, $v);
			}
			return true;
		}
		if( !$this->has($key) ) $this->set($key, $value);
	}

	/**
	 * Set Current Section For Store
	 *
	 * @param string Name
	 */
	public function setSection( $name ) 
	{
		$this->section = $name;
	}

	/**
	 * Has Value
	 *
	 * @param string Key
	 * @return bool
	 */
	public function has( $key ){
		if( !empty($this->section) ) $key = "$this->section.$key";
		if( false === strpos($key,'.') ){
			return isset($this->values[$key]);
		}else{
			$arr = explode('.',$key);
			$fin = array_pop($arr);
			$ref = $this->values;
			while( $k = array_shift($arr) ) $ref = $ref[$k];
			return isset($ref[$fin]);
		}
	}

	/**
	 * Get Values
	 *
	 * you can put more then one args to get multiplly
	 *
	 * @param mixed key one string or array
	 * @return mixed
	 */
	public function get( $key = false) 
	{
		// if key not defined output all
		if(!empty($this->values) && empty($key)) $key = array_keys($this->values);

		// if args are more then 1 multiple get
		elseif( func_num_args() > 1 ) $key = func_get_args();

		// for multiple get
		if( is_array($key) ) {
			$ret = array();
			foreach($key as $k) $ret[$k] = $this->get($k);
			return $ret;
		}

		if( !empty($this->section) ) $key = "$this->section.$key";

		if( false === strpos($key,'.') ){
			if(!isset($this->values[$key])) return false;
			$value = $this->values[$key];
		}else{
			$arr = explode('.',$key);
			$fin = array_pop($arr);
			$ref = $this->values;
			while( $k = array_shift($arr) ) $ref = $ref[$k];
			$value = $ref[$fin];
		}

		// if value is array recurse output
		if( is_array($value) ){
			$ret = array();
			foreach($value as $k=>$v){
				$ret[$k] = $this->get("$key.$k");
			}
			return $ret;
		}
		return $this->outputFilter($key, $value);
	}

	/**
	 * out put filter interface
	 *
	 * @param string
	 * @param mixed
	 */
	public function outputFilter( $key, $value ) {
		return $this->format($value);
	}

	/**
	 * for matting
	 *
	 * @param String
	 */
	public function format( $format )
	{
		if( is_bool($format) ) return $format;
		if( is_object($format) ) return $format;
		if( is_array($format) ) {
			foreach($format as $k=>$v) {
				$format[$k] = $this->format( $v );
			}
			return $format;
		}
		$text = preg_replace('/\$\{(.*?)\}/e', '$this->get("\1")', $format);
		$args = array_slice(func_get_args( ),1);
		if(!empty($args)) $text = vsprintf($text, $args);
		return $text;
	}

	public function dump( )
	{
		var_dump($this->get());
	}
	public function __get($key){
		$key = preg_replace('/([A-Z][^A-Z]+)/e', 'strtolower(".\1")', $key);
		return $this->get($key);
	}


}
?>
