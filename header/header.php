<?php
//php.ini allow_url_include
header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate");

require "config/db.php";
require "config/log.php";
require "logger/SimpleLogger.php";
require "dbConnector/".$db_info['type']."Connector.php";
require "dao/".$db_info['type']."Dao.php";
require "service/service.php";

date_default_timezone_set('Asia/Seoul');

$G_LOGGER = SimpleLogger::getLogger();
$G_DBCONNECTOR;
eval("\$G_DBCONNECTOR = new ".$db_info['type']."Connector(\$db_info);");
$G_SERVICE = new service($G_DBCONNECTOR);
?>
