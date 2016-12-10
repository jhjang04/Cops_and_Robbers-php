<?php

//example..
//http://YOUR_DOMAIN/index.php?src=example
try{
	require "header/header.php";
	$G_LOGGER->info("********************************************************************");
	$G_LOGGER->info("HTTP_HOST : ".$_SERVER['HTTP_HOST']);
	$G_LOGGER->info("REQUEST_URI : ".$_SERVER['REQUEST_URI']);
	$G_LOGGER->info("REMOTE_ADDR : ".$_SERVER['REMOTE_ADDR']);
	
	$G_LOGGER->info("********************************************************************");
	
	$src = $_GET['src'];
	
	
	if($src != null) {
		$G_LOGGER->info("call ".$src."Controller.php");
		include "controller/".$src."Controller.php";
	}
}
catch(Exception $e){
	$err = array();
	$err['errmsg'] = $e->__toString();
	
	$G_LOGGER->debug($e->__toString());
	echo json_encode($err);
}
finally {
	//$G_LOGGER->info("--------------------------------------------------------------------");
}

?>