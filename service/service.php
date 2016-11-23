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
		
		$this->logger = logger::getLogger();
	}

	public function __destruct(){
	}
	
	
	public function makeRoom($pwd , $nick){
		return $this->m_dao->makeRoom($pwd , $nick );
	}
	
	public function joinRoom($pwd , $nick){
		
	}
	
	public function exitRoom($room_id , $user_id){
		
	}
	
	//public function atTeamSelect();
	
	//public function atPlaying();
}


function serviceCall(){
	$args = func_get_args();
	global $G_SERVICE;
	if(count($args) < 1){
		throw new Exception("no service Names");
	}
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
		return $rtn;
	}
	catch (Exception $e){
		throw $e;
	}
}

?>