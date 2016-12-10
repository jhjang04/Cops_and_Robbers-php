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
	
	// ���ο� �� ��ȣ �ο�
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
	
	// �� ���翩�� �˻� return false or true
	public function isExistRoom($room_id , $pwd){
		$sql = "select * from room where room_id = ? and pwd = ?";
		$rs = $this->connector->excuteQuery($sql, "is", [$room_id , $pwd]);
		
		return count($rs) > 0;
	}
	
	// ���ο� user���� ƴ ��ȣ�� �ο� ��.(�� ���� ������ �ڵ� ��ġ)
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
	
	// �� ���� ���ο� user_no�� �ο� ��.
	public function getNewUserNo($room_id){
		$sql = "select ifnull(max(user_no) , 0) + 1 as user_no from user where room_id = ?";
		$rs = $this->connector->excuteQuery($sql , "i" , [$room_id]);
		return $rs[0]['user_no'];
	}
	
	// �濡 ������ ����(�߰�)
	public function insertUser($room_id , $user_no , $nickname , $team){
		$sql = "insert into user(room_id , user_no , nickname , team , state , team_select_time , ready_status , last_access)
				values( ? , ? , ? , ? , 1 , sysdate() , 2, sysdate());";
		
		// ready status �� �⺻������ 2(not ready) ����
		$rs = $this->connector->excuteQuery($sql , "iisi" , [$room_id , $user_no , $nickname , $team]);
		return $rs;
	}
	
	// ���� ���� üũ
	public function getRoomState($room_id){
		
		$sql = "select 1 from user where room_id= ? and ready_status = 2";
		$rs = $this->connector->excuteQuery($sql , "i" , [$room_id]);
		
		$value= count($rs);
		
		if($value > 0){
			$state = "WAIT";
		}
		else {
			$state = "START";
		}
		
		return $state;
	}
	
	// �ش� room_id�� ���� �������� ����Ʈ�� ��ȯ
	public function getUserList($room_id){
		$sql = "select user_no, nickname, team, state, latitude, longitude
				team_select_time, ready_status, last_access from user 
				where room_id = ?";
		$rs = $this->connector->excuteQuery($sql , "i" , [$room_id]);
		
		return $rs;
	}
	
	public function refreshUserLastAccess($room_id , $user_no) {
		$sql = "update user set last_access = sysdate() where room_id = ? and user_no = ?";
		$rs = $this->connector->excuteQuery($sql , "ii" , [$room_id, $user_no]);
	}
	
	// team ��ȣ�� �޾� team�� ������ ��.
	public function updateTeam($room_id, $user_no, $team){
		$sql = "update user set team = ? where room_id = ? and user_no = ?";
		$rs = $this->connector->excuteQuery($sql , "iii" , [$team, $room_id, $user_no]);
	}
	
	// ready ���¸� �޾� ������ ��.
	public function updateReadyState($room_id, $user_no, $ready_status){
		$sql = "update user set ready_status = ? where room_id = ? and user_no = ?";
		$rs = $this->connector->excuteQuery($sql , "iii" , [$ready_status, $room_id, $user_no]);
		
		return 1;
	}
	
	// ������ �ε��� ���� ��ü ä�� ����Ʈ
	public function getChatList($room_id, $lastChatIdx){
		$sql = "select room_id, team, chat_flag, idx, user_no, nickname, wr_time, text from chat
				where room_id = ? and chat_flag = 3 and idx > ?";
		$rs = $this->connector->excuteQuery($sql , "ii" , [$room_id, $lastChatIdx]);
		
		return $rs;
	}
	
	// ��ä���� �޾ƿ� ��
	public function getTeamChatList($room_id, $team, $lastTeamChatIdx){
		$sql = "select room_id, team, chat_flag, idx, user_no, nickname, wr_time, text from chat
				where room_id = ? and chat_flag = ? and idx > ?";
		$rs = $this->connector->excuteQuery($sql , "iii" , [$room_id, $team, $lastChatIdx]);
	
		return $rs;
	}
	
	// ������ ��ää�� �ε����� �޾ƿ��� �Լ�.
	public function getLastChatIdx($room_id, $lastChatIdx){
		$sql = "select max(idx) from chat where room_id= ? and chat_flag =3 and idx > ?";
		$rs = $this->connector->excuteQuery($sql, "ii", [$room_id, $lastChatIdx]);
		
		return $rs;
	}
	
	// ������ ��ä�� �ε����� �޾ƿ��� �Լ�.
	public function getLastTeamChatIdx($room_id,$team,$lastTeamChatIdx){
		$sql = "select max(idx) from chat where room_id= ? and chat_flag = ? and idx > ?";
		$rs = $this->connector->excuteQuery($sql, "iii", [$room_id, $team, $lastTeamChatIdx]);
		
		return $rs;
	}
	
	public function updateLocation($room_id, $user_no, $latitude, $longitude){
		$sql = "update user set latitude = ?, longitude = ? , last_access = sysdate()
				where room_id = ? and user_no = ?";
		$rs = $this->connector->excuteQuery($sql , "ddii" , [$latitude,$longitude,$room_id, $user_no]);
		
		return 1;
	}
	
	public function updateState($room_id, $user_no, $state){
		$sql = "update user set state = ? , last_access = sysdate(),
				where room_id = ? and user_no = ?";
		$rs = $this->connector->excuteQuery($sql , "iii" , [$state, $room_id, $user_no]);
	
		return 1;
	}
	
	// ä���� ������ idx�� ã�Ƽ� ����.
	public function insertChat($room_id, $team, $chat_flag, $user_no, $nickname, $text){
		$sql = "select max(idx) from chat where room_id = ? and chat_flag = ?";
		$maxIdx = $this->connector->excuteQuery($sql, "ii", [$room_id, $chat_flag]);
		
		$sql = "insert into chat(room_id, team, chat_flag, idx, user_no, nickname, wr_time, text)
				values( ?, ?, ?, ?, ?, ?, sysdate(), ?)";
		
		$rs = $this->connector->excuteQuery($sql, "iiiiiss",[$room_id, $team, $chat_flag, $maxIdx,
				$user_no, $nickname, $text]);
		
		return $rs;
	}
}
?>




















