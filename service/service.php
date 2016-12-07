<?php
class service{
	private $logger;
	private $m_dao;
	
	public function __construct(&$connector){
		
		if(!isset($connector)) {
			throw new Exception("connector is undefined on service constructor");
		}
		else if($connector instanceof redisConnector) {
				
		}
		else if($connector instanceof mysqlConnector) {
			$this->m_dao = new mysqlDao($connector);
		}
		
		$this->logger = SimpleLogger::getLogger();
	}

	public function __destruct(){
	}
	
	///////////////////service functioin /////////////////////
	
	
	public function example($pr1 , $pr2){
		return $this->m_dao->getExampleResult($pr1 , $pr2 );
	}
	
	
	
	public function makeRoom($pwd , $nickname){
		$room_id = $this->m_dao->getNewRoomId();
		$team = $this->m_dao->getNewTeam($room_id);
		$this->m_dao->insertRoom($room_id , $pwd);
		$this->m_dao->insertUser($room_id, 1, $nickname, $team);
		
		$rs = array();
		$rs['result'] = 'PASS';
		$rs['room_id'] = $room_id;
		$rs['user_no'] = 1;
		$rs['team'] = $team;
		
		return $rs;
	}
	
	// 방에 참가
	public function joinRoom($room_id , $pwd , $nickname){
		$rs = array();
		// 방의 존재 여부 검사.
		$room_exist = $this->m_dao->isExistRoom($room_id);
		
		//방이 존재하지 않으면
		if(!$room_exist){
			$rs['result'] = 'FAIL';
			return $rs;
		}
		
		// 새로운 팀을 받아 옴.
		$team = $this->m_dao->getNewTeam($room_id);
		$this->m_dao->insertUser($room_id, 1, $nickname, $team);
		$user_no = $this->m_dao->getNewUserNo($room_id);		
		
		$rs['result'] = 'PASS';
		$rs['room_id'] = $room_id;
		$rs['user_no'] = $user_no;
		$rs['team'] = $team;
		
		return $rs;
	}
	
	public function selectTeam($room_id , $user_no , $team, $ready_status){
		$rs = array();
		
		// 선택한 팀과 레디 상태를 업데이트 함.
		$updaters = $this->m_dao->updateTeam($room_id, $user_no, $team);
		$updaters2 = $this->m_dao->updateReadyState($room_id, $user_no,$ready_status);

		// 방의 진행 상태와 방에있는 유저의 리스트를 반환 함.
		$isStart = $this->m_dao->getRoomState($room_id);
		$user_list = $this->m_dao->getUserList($room_id);

		$rs['result'] = $isStart;
		$rs['userList']= $user_list;
		
		return $rs;
	}
	
	public function playing($room_id, $user_no, $team, $latitude, $longitude, $state, $lastChatIdx, $lastTeamChatIdx){
		$rs = array();
		
		// update 할 것
		$update_location = $this->m_dao->updateLocation($room_id, $user_no, $latitude, $longitude);
		$update_state = $this->m_dao->updateState($room_id, $user_no, $state);
		
		// response
		$user_list = $this->m_dao->getUserList($room_id);
		$chat_list = $this->m_dao->getChatList($room_id, $lastChatIdx);
		$teamchat_list = $this->m_dao->getTeamChatList($room_id, $team, $lastTeamChatIdx);
		$lastIdx = $this->m_dao->getLastChatIdx($room_id, $lastChatIdx);
		$lastTeamIdx = $this->m_dao->getLastTeamChatList($room_id,$team,$lastTeamChatIdx);
		

		$rs['result'] = "ING";
		$rs['userList'] = $user_list;
		$rs['chatList'] = $chat_list;
		$rs['teamChatList'] = $teamchat_list;
		$rs['lastChatIdx'] = $lastIdx;
		$rs['lastTeamChatIdx'] = $lastTeamIdx;
		$rs['remain_time'] = "";
		return $rs;
	}
	
	public function exitRoom($room_id , $user_id){
		
	}
	
	//public function atTeamSelect();
	
	//public function atPlaying();
}







function serviceCall(){
	$args = func_get_args();
	global $G_SERVICE;
	global $G_DBCONNECTOR;
	if(count($args) < 1){
		throw new Exception("no service name");
	}
	$logger = SimpleLogger::getLogger();
	
	$rtn;
	$strCallService = "\$rtn = \$G_SERVICE->".$args[0]."(";
	for($i=1 ; $i<count($args) ; $i++ ){
		if($i > 1)
			$strCallService.= " , ";
		$strCallService.= "\$args[".$i."]";
	}
	$strCallService.= ");";
	try{
		eval ($strCallService);
		$G_DBCONNECTOR->release("COMMIT");
		return $rtn;
	}
	catch (Exception $e){
		$G_DBCONNECTOR->release("ROLLBACK");
		throw $e;
	}
}

?>