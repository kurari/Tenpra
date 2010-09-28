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
require_once "templator/templator.class.php";

class TFrameWorkView extends TTemplator
{

}

class TFrameWorkController 
{
	private $Conf;

	function __construct( $file  ){
		$o = TConfig::factory("ini");
		$o->set('env.root', realpath(dirname(__FILE__).'/../'));
		$o->load($file);
		$this->Conf = $o;
	}

	function show( $path ){
		$file = $this->Conf->format('${view.dir}/%s.html', $path);

		$view = new TFrameWorkView( $this );
		$view->set('site', $this->Conf->site);
		$view->setTemplateDir( $this->Conf->viewDir );
		$view->display('file://'.$file);
	}
}
?>
