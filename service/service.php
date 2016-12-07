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
		// 새로운 팀을 받아 옴.
		$team = $this->m_dao->getNewTeam($room_id);
		$this->m_dao->insertUser($room_id, 1, $nickname, $team);
		
		$rs = array();
		$rs['result'] = 'PASS';
		$rs['room_id'] = $room_id;
		$rs['team'] = $team;
		
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