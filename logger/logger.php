<?php

class logger{
	private static $LOGGER = null;
	private $m_level;
	private $m_file;
	private $m_buffer = "";
	
	
	public static function getLogger() {
		if(logger::$LOGGER == null) {
			global $log_info;
			logger::$LOGGER = new logger($log_info);
		}
		return logger::$LOGGER;
	}
	
	
	public function __construct($log_info){
		$this->set($log_info);
	}
	
	
	public function set($log_info){
		
		$this->m_file = $log_info['path']."log.txt";
		$this->m_level = $log_info['level'];
	}
	
	
	public function error($txt){
		$this->$m_buffer .= date("Y-m-d H:i:s")." error :: ".$txt;
	}
	
	public function info($txt){
		$this->$m_buffer .= date("Y-m-d H:i:s")." info :: ".$txt;
	}
	
	public function debug($txt){
		$this->$m_buffer .= date("Y-m-d H:i:s")." debuf :: ".$txt;
	}
	
	public function flush(){
		
	}
}
?>
