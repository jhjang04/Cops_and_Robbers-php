<?php

	$room_id = $_GET['room_id'];
	$team = $_GET['team'];
	$chat_flag = $_GET['chat_flag'];
	$user_no = $_GET['user_no'];
	$nickname = $_GET['nickname'];
	$text = $_GET['text'];
	$lastChatIdx = $_GET['lastChatIdx'];
	$lastTeamChatIdx = $_GET['lastTeamChatIdx'];
	
	
	if(!isset($room_id) || !isset($team) || !isset($chat_flag) || !isset($user_no) || !isset($nickname)
			|| !isset($text) || !isset($lastChatIdx) || !isset($lastTeamChatIdx)){
		throw new Exception("no value");
	}
	
	$rs = serviceCall("sendChat" , $room_id , $team, $chat_flag, $user_no, $nickname, $text
			, $lastChatIdx, $lastTeamChatIdx);

	echo json_encode($rs);

?>