<?php
try{
	require "header/header.php";
	
	$src = $_GET['src'];
	if($src != null)
		include "controller/".$src."Controller.php";
}
catch(Exception $e){
	echo $e;
}
finally {

}

?>