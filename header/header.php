<?php
//php.ini allow_url_include
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate");

require "config/db.php";
require "config/log.php";
require "logger/logger.php";
require "dbConnector/".$db_info['type']."Connector.php";
require "dao/".$db_info['type']."Dao.php";
require "service/service.php";

$G_DOMAIN = "http://localhost/car/";
$G_LOGGER = logger::getLogger();
$G_DBCONNECTOR;
eval("\$G_DBCONNECTOR = new ".$db_info['type']."Connector(\$db_info);");
$G_SERVICE = new service($G_DBCONNECTOR);
?>
