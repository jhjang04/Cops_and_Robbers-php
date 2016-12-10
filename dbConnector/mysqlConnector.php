<?php

class mysqlConnector {
	private $m_host;
	private $m_port;
	private $m_user;
	private $m_pwd;
	private $m_db_name;

	private $m_conn = null;
	
	private $mLogger = null;
	
	public function __construct($db_info) {
		$this->set($db_info['host'] 
			, $db_info['port'] 
			, $db_info['user_nm'] 
			, $db_info['pwd'] 
			, $db_info['db_name']
		);
		$this->mLogger = SimpleLogger::getLogger();
	}
	
// 	public function __construct($_host , $_port , $_user , $_pwd , $_db_name) {
// 		set($_host , $_port , $_user , $_pwd , $_db_name);
// 	}
	
	
	public function set($_host , $_port , $_user , $_pwd , $_db_name) {
		$this->m_host = $_host;
		$this->m_port = $_port;
		$this->m_user = $_user;
		$this->m_pwd = $_pwd;
		$this->m_db_name = $_db_name;
	}

	
	public function getConnection() {
		if($this->m_conn != null){
			return $this->m_conn;
		}
		$this->m_conn = mysqli_connect($this->m_host . ":" . $this->m_port , $this->m_user , $this->m_pwd , $this->m_db_name);
		
		if(mysqli_connect_error($this->m_conn)){
			//error
			throw new Exception("db connection error");
		}
		mysqli_query($this->m_conn, "SET AUTOCOMMIT=0");
		mysqli_query($this->m_conn, "START TRANSACTION");
		
		return $this->m_conn;
	}
	
	
	
	public function excuteQuery($sql , $types , $params)
	{
		if(!isset($types)){ $types = "";}
		if(!isset($params)){ $params = array();}
		if(!is_array($params)){ $params = [$params]; }
		
		
		$this->mLogger->info("call dao : ".debug_backtrace()[1]['function']);
		
		$this->mLogger->debug("excuqte Query :: $sql");
		$this->mLogger->debug("params :: ".json_encode($params));
		$this->mLogger->debug("types :: ".$types);
		
		$conn = $this->getConnection();
		$stmt = mysqli_prepare($conn, $sql);
		$strBindCode = "mysqli_stmt_bind_param(\$stmt , \"".$types."\"";
		for($i = 0 ; $i<count($params) ; $i++) {
			$strBindCode = $strBindCode." , \$params[".$i."]";
		}
		$strBindCode = $strBindCode." );";
		eval($strBindCode);
		
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		if(is_bool($result)){
			return $result;
		}
		$rs = array();
		$idx = 0;
		while ($row = mysqli_fetch_assoc($result)){
			$rs[$idx++] = $row;
		}
		mysqli_stmt_close($stmt);

		return $rs;
	}
	
	
// 	public function excuteQuerySqlFile($path , $types , $params)
// 	{
// 		require $path;
// 		return $this->excuteQuery($sql, $types, $params);
// 	}
	
	
	public function commit(){
		return mysqli_query($this->m_conn, "COMMIT");
	}
	
	public function rollback(){
		return mysqli_query($this->m_conn, "ROLLBACK");
	}
	
	public function release($command){
		if($this->m_conn == null ){
			return;
		}
		if(!isset($command)){
			$command = "COMMIT";
		}
		
		if(strtoupper($command) == "ROLLBACK"){
			$this->rollback();
		}
		else if(strtoupper($command) == "COMMIT"){
			$this->commit();
		}
		
		mysqli_close($this->m_conn);
		$this->m_conn = null;
	}
	
}
?>


