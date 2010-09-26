<?php
/**
 *
 * This is Controller 
 *
 *
 * @author Hajime MATSUMOTO
 * @date 2010.09.25
 */
require_once "config/config.class.php";

class TController 
{
	private $Conf;

	function __construct( $file  ){
		$o = TConfig::factory("ini");
		$o->set('env.root', realpath(dirname(__FILE__).'/../'));
		$o->load($file);
		$this->Conf = $o;
	}

	function show( $key ){
		$file = $this->Conf->format('${view.dir}/%s.html', $key);
		echo $file;
	}
}
?>
