<?php 
require_once 'templator/templator.class.php';

class TTemplatorTest extends TTestCase
{
	function init( ){
	}

	function testFactory( ){
		$o = new TTemplator( );
		$this->assertEquals('TTemplator', get_class($o));
	}

	function testGetTemplate( ){
		$o = new TTemplator( );
		$tpl = $o->getTemplate('file://'.realpath(dirname(__FILE__)).'/convert.html');
		$this->assertEquals(file_get_contents(dirname(__FILE__).'/convert.html'), $tpl);
	}

	function testConvert( ){
		$o = new TTemplator( );
		$c = $o->compile('{{$k}}');
		$this->assertEquals('<?php echo $store->get("k");?>', $c);
	}

	function testBlockInBlock( ){
		$o = new TTemplator( );
		$c = $o->compile('{{foreach from=$arr}}aaa{{foreach from=$v}}bbb{{/foreach}}ccc{{/foreach}}');
	}

	function testFetch( ){
		$o = new TTemplator( );
		$o->set('title', 'ABC');
		$r = $o->fetch('string://{{$title}}');
		$this->assertEquals('ABC', $r);
	}
}
?>
