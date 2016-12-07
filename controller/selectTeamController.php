<?php

	$user_no = $_GET['user_no'];
	$room_id = $_GET['room_id'];
	$team = $_GET['team'];
	$ready_status = $_GET['ready_status'];
	
	if(!isset($room_id) || !isset($user_no) || !isset($team) || !isset($ready_status)){
		throw new Exception("no value");
	}
	
	$rs = serviceCall("selectTeam" , $room_id , $user_no , $team, $ready_status);

	echo json_encode($rs);
?>


