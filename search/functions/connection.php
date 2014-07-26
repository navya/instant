<?php
//connection
require("constants.php");
$connection = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
if(!$connection){
	die("Database connection failed. ".mysql_error());
	}
//database selection
$select_db = mysql_select_db(DATABASE,$connection);
if(!$select_db){
	die("Database not connected ".mysql_error());
	}
?>