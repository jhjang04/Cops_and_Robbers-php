<?php
	$pwd = $_GET['pwd'];
	$nick = $_GET['nick'];
	
	if(!isset($pwd) || !isset($$nick)){
		//..exception
	}
	
	$room_id = serviceCall("makeRoom" , $pwd , $nick);
	
	$res = array();
	$res['room_id'] = $room_id;
	echo json_encode($res);
?>


