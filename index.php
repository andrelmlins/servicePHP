<?php
	require_once('servphp/servphp.php');

	$servphp = new ServPHP("WebService");

	$servphp->connection("localhost","clubedotreino","3307","root","usbw");
	$servphp->token(array("/login"),50,30);
	//echo $servphp->getToken();
	$servphp->run();