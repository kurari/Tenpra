<?php
/**
 * X Utility
 */
dirname(__FILE__).'/base.class.php';

class TUtil {

	/**
	 * Crete Path
	 */
	public static function mkPath( ){
		$args = func_get_args( );
		return implode(DIRECTORY_SEPARATOR, $args);
	}

	/**
	 * Append Include Path
	 *
	 */
	public static	function appendIncludePath( ){
		$args = func_get_args( );
		$args[] = get_include_path();
		set_include_path(implode(PATH_SEPARATOR, $args));
	}
}
?>
