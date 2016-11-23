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
	
	public function example(){
		$sql = "select * from room_mst where ? = ? ;";
		$rs = $this->connector->exquteQuery($sql , "ss" , ["1","1"]);
		return $rs;
	}
	
	
	public function getNewRoomId(){
		$sql = "select 1 from room_mst where room_id = ?";
		do{
			$rand = mt_rand(1,9999);
			$rs = $connector->excuteQuery($sql , "d" , $rand);
		}
		while(count($rs) != 0 );
		return $rand;
	}
	
	
	public function makeRoom($pwd , $nick){
		$sql = "CALL P_MAKE_ROOM( ? );";
		$rs = $this->connector->excuteQuery($sql , "s" , [$pwd]);
		return $rs[0]['ROOM_ID'];
	}
	
}
?>