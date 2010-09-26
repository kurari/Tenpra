<?php
/**
 * TConf Library
 * ----
 * 
 * @author Hajime MATSUMOTO
 */

require_once 'base/base.class.php';
require_once 'store/store.class.php';

class TConfigException extends TException{ }

/**
 * TConfig Class
 */
class TConfig extends TStore
{

	public static function factory($type, $default = false, $options = false)
	{
		$file = dirname(__FILE__)."/config.$type.class.php";
		$class= "TConfig".ucfirst($type);

		if( !file_exists( $file ) ) throw new TConfigException('file %s not found', $file);
		require_once $file;

		if( !class_exists( $class ) ) throw new TConfigException('class %s not found', $class);
		$conf = new $class( );
		return $conf;
	}
}
?>
