<?php
	$pwd = $_GET['pwd'];
	$nickname = $_GET['nickname'];
	$room_id = $_GET['room_id'];
	
	if(!isset($pwd) || !isset($nickname) || !isset($room_id)){
		throw new Exception("no password or nick or room_id");
	}
	
	$rs = serviceCall("joinRoom" , $room_id , $pwd , $nickname);

	echo json_encode($rs);
?>


