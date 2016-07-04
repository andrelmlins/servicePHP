<?php
	require_once('system/framework.php');
	require_once('system/define.php');
	require_once('system/system.php');
	require_once('system/connection.php');
	require_once('system/token.php');

	$framework = new Framework("Framework_PHP");

	$framework->connection("localhost","clubedotreino","3307","root","usbw");
	$framework->token(array("login"),50,1);
	$framework->run();