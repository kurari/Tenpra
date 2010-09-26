<?php
require_once 'util/util.class.php';

class TUtilTest extends TTestCase
{
	function testMkPath( ){
		$path = TUtil::mkPath("a","b", "c");
		$this->assertEquals( "a/b/c", $path);
	}

	function testAppendIncludePath( ){
		set_include_path('.');
		TUtil::appendIncludePath(TUtil::mkPath("a","b"));

		$path = get_include_path( );
		$this->assertEquals( TUtil::mkPath("a","b").PATH_SEPARATOR.".", $path );

		TUtil::appendIncludePath(TUtil::mkPath("c","d"));

		$path = get_include_path( );
		$this->assertEquals( TUtil::mkPath("c","d").PATH_SEPARATOR.TUtil::mkPath("a","b").PATH_SEPARATOR.".", $path );
	}
}
?>
