#!/usr/bin/php
<?php
/**
 * arguments are multiple
 */
set_include_path(realpath(dirname(__FILE__)."/../lib").':'.get_include_path());
require_once "test/case.class.php";

$files = array_slice($argv, 1);

foreach($files as $file) {
	run_test_case( $file );
}

function run_test_case($file) {
	$className = substr(basename($file), 0, strlen(basename($file))-strlen(".php"));
	require_once $file;
	$TestCase = new $className( );
	$TestCase->init();
	$results = $TestCase->run();

	$count  = 0;
	foreach($results as $k=>$v){
		echo "Name: $k \n";
		foreach($v as $kk=>$vv){
			if(!$vv[0]) $count++;

			echo $vv[1];
			echo " ";
			echo ($vv[0]) ? "OK": "NG";
			echo " ";
			echo $vv[2];
			echo "\n";
		}
		echo "Total Error: $count \n";
		
	}
}

?>
