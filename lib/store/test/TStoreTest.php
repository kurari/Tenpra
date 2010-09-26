<?php
require_once 'store/store.class.php';

class TStoreTest extends TTestCase
{

	function init( ){
		$this->store = new TStore( );
	}


	function testSetAndGet( ){
		$this->store->set("test", "aaaa");
		$this->assertEquals( "aaaa", $this->store->get("test"));
	}

	function testHas(){
		$this->store->set("test", "aaaa");
		$this->assertTrue( $this->store->has("test") );
	}

	function testSetIf( ){
		$this->store->set("test", "aaaa");
		$this->store->setIf("test", "aaaa");
		$this->assertEquals( "aaaa", $this->store->get("test"));

		$this->store->setIf("test2", "bbbb");
		$this->assertEquals( "bbbb", $this->store->get("test2"));
	}

	function testSetSection( ){
		$this->store->setSection("sec");
		$this->assertEquals( "sec", $this->store->section );
	}

	function testFormat( ){
		$this->store->set("A", "A");
		$this->store->set("B", "B");
		$this->store->set("C", "C");
		$this->assertEquals("A B C", $this->store->format('${A} ${B} ${C}'));
	}



}
?>
