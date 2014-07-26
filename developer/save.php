<?php include_once("functions/connection.php"); ?>
<?php
$fields = array('roll_no','profile','company');
foreach($fields as $field){
	${$field} = $_GET[$field];
}
$developer = true;
if($developer==false){
	return ; 
}
$sql = "INSERT INTO `placement_stats` (`roll_no`,`profile`,`company`) VALUES ('$roll_no','$profile','$company')";
if(mysql_query($sql,$connection)){
	echo 'done';
}

?>