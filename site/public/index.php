<?php
require_once '../../lib/util/util.class.php';

TUtil::appendIncludePath( 
	TUtil::mkPath("..","..","lib"),
	TUtil::mkPath("..","..","class") 
);

require_once "controller.class.php";
$Ctrl = new TFrameWorkController('../root.ini');

// 画面の出力
$Ctrl->show("system.default");
?>
