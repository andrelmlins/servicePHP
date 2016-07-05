<?php
	require_once('servphp/servphp.php');

	$servphp = new ServPHP("Framework_PHP");

	$servphp->connection("localhost","clubedotreino","3307","root","usbw");
	//$servphp->token(array("login"),50,1);
	$servphp->run();