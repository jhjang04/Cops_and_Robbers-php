<?php
	$pwd = $_GET['pwd'];
	$nickname = $_GET['nickname'];
	
	
	if(!isset($pwd) || !isset($nickname)){
		throw new Exception("no password or nick");
	}
	
	$rs = serviceCall("makeRoom" , $pwd , $nickname);
	
	echo json_encode($rs);
?>


