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
		//call excuteQuery($sql , "sd" , [$pr1,$pre])
		return $rs;
	}
	
	
	public function getNewRoomId(){
		$sql = "select 1 from room where room_id = ?";
		do{
			$rand = mt_rand(1,9999);
			$rs = $this->connector->excuteQuery($sql , "d" , $rand);
		}
		while(count($rs) != 0 );
		return $rand;
	}
	
	public function makeRoom($pwd , $nick){
		$sql = "CALL P_MAKE_ROOM( ? , ? );";
		$rs = $this->connector->excuteQuery($sql , "ss" , [$pwd , $nick]);
		return $rs[0]['ROOM_ID'];
	}
	
}
?>