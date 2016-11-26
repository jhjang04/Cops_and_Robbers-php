<?php
	//example source
	$param1 = $_GET['param1'];
	$param2 = $_GET['param2'];
	
	if(!isset($param1) ||!isset($param2)){
		//throws new Exception("your message")
		$param1 = "123";
		$param2 = "123";
	}
	
	$rs = serviceCall("example" , $param1 , $param2);
	echo json_encode($rs);
?>
