<?php
	$pwd = $_GET['pwd'];
	$nick = $_GET['nick'];
	
	if(!isset($pwd) || !isset($nick)){
		throw new Exception("no password or nick");
	}
	
	$room_id = serviceCall("makeRoom" , $pwd , $nick);
	
	$res = array();
	$res['room_id'] = $room_id;
	$res['user_no'] = 1;
	echo json_encode($res);
?>


