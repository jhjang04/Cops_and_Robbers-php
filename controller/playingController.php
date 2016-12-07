<?php
	$room_id = $_GET['room_id'];
	$user_no = $_GET['user_no'];
	$team = $_GET['team'];
	$latitude = $_GET['latitude'];
	$longitude = $_GET['longitude'];
	$state = $_GET['state'];
	$lastChatIdx = $_GET['lastchatIdx'];
	$lastTeamChatIdx = $_GET['lastTeamChatIdx'];

	if(!isset($room_id) || !isset($user_no) || !isset($latitude) || !isset($longitude) || !isset($state)
			|| !isset($lastChatIdx) || !isset($lastTeamChatIdx)){
		throw new Exception("no value");
	}

	$rs = serviceCall("playing" , $room_id, $user_no, $team, $latitude, $longitude, $state, $lastChatIdx, $lastTeamChatIdx);
	
	echo json_encode($rs);
?>


