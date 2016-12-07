<?php

class mysqlDao {
	private $connector = null;
	private $m_path = "dao/sql/mysql/";

	public function __construct(&$connector) {
		if(!isset($connector)){
			throw new Exception("connector is undefined on mysqlDao");
		}
		$this->connector = $connector;
	}
	
	
	//example source
	public function getExampleResult($pr1 , $pr2){
		$sql = "select * from room where ? = ? ;";
		//? is parameter 
		// search prepared statement
		$rs = $this->connector->excuteQuery($sql , "ss" , [$pr1,$pr2]);
		//"ss" is paremeter type string string.
		//if parameters are integer and string then
		//call excuteQuery($sql , "is" , [$pr1,$pr2])
		// i : integer
		// d : double
		// s : string
		// b : blob
		return $rs;
	}
	
	
	public function getNewRoomId(){
		$sql = "select 1 from room where room_id = ?";
		do{
			$rand = mt_rand(1,9999);
			$rs = $this->connector->excuteQuery($sql , "i" , $rand);
		}
		while(count($rs) != 0 );
		return $rand;
	}
	
	public function makeRoom($pwd , $nick){
		$sql = "CALL P_MAKE_ROOM( ? , ? );";
		$rs = $this->connector->excuteQuery($sql , "ss" , [$pwd , $nick]);
		return $rs[0]['ROOM_ID'];
	}
	
	public function insertRoom($room_id , $pwd){
		$sql = "insert into room(room_id , pwd , last_access)
        	values( ?  , ? , sysdate() );";
		$rs = $this->connector->excuteQuery($sql , "is" , [$room_id , $pwd]);
		return $rs;
	}
	
	public function isExistRoom($room_id){
		$sql = "select * from room where room_id = ?";
		$rs = $this->connector->excuteQuery($sql, "i", [$room_id]);
		
		return count($rs);
	}
	
	public function getNewTeam($room_id){
		$sql = "select sum(case when team = 1 then 1 else 0 end)  cop_cnt
					, sum(case when team = 2 then 1 else 0 end)  robber_cnt
				from user
				where room_id = ?
				group by room_id"
		;
		$rs = $this->connector->excuteQuery($sql , "i" , [$room_id]);
		$team;
		if(count($rs) == 0){
			$team = 1;
		}
		else if($rs[0]['cop_cnt'] > $rs[0]['robber_cnt']){
			$team = 2;
		}
		else{
			$team = 1;
		}
		return $team;
	}
	
	public function getNewUserNo($room_id){
		$sql = "select ifnull(max(user_no) , 0) + 1 as user_no from user where room_id = ?";
		$rs = $this->connector->excuteQuery($sql , "i" , [$room_id]);
		return $rs[0]['user_no'];
	}
	
	
	public function insertUser($room_id , $user_no , $nickname , $team){
		$sql = "insert into user(room_id , user_no , nickname , team , state , team_select_time , last_access)
				values( ? , ? , ? , ? , 1 , sysdate() , sysdate());";
		
		$rs = $this->connector->excuteQuery($sql , "iisi" , [$room_id , $user_no , $nickname , $team]);
		return $rs;
	}
	
	
	
}
?>