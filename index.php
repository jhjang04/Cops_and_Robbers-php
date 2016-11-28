<?php

//example..
//http://YOUR_DOMAIN/index.php?src=example
try{
	require "header/header.php";
	
	$src = $_GET['src'];
	if($src != null)
		include "controller/".$src."Controller.php";
}
catch(Exception $e){
	$err = array();
	$err['msg'] = $e;
	echo json_encode($err);
}
finally {

}

?>