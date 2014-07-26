<html>
<head>
</head>
<body>
<?php include('connection.php'); ?>
<?php
function getByEmail($email){
global $connection;
//if($email=="")return;
$url = "http://oa.cc.iitk.ac.in:8181/Oa/Jsp/OAServices/IITK_SrchStudMail.jsp?selstudmail=".$email;
$raw = file_get_contents($url);
//if()
//echo $email;
$extract_tr="/<tr>(.*)<\/tr>/isU"; 
$extract_td="/<td.*>(.*)<\/td>/isU"; 
preg_match_all($extract_tr, $raw, $match_tr, PREG_SET_ORDER); 
//print_r($match_tr[1][0]);
preg_match($extract_td, $match_tr[1][0], $match_th); 
echo $email;
//print_r($match_th[0]); 
$rollno = trim(strip_tags($match_th[0]));
//strlen($rollno)>5 ? $program="dual":$program="";
//echo $rollno.$program.$email;
$co = strlen($rollno);
if($co==8){
	$nq = 'UPDATE y8 SET roll_no = "'.$rollno.'", program = "mt(dual)" WHERE email ="'.$email.'@iitk.ac.in" ';
	if(mysql_query($nq,$connection)){
		echo $rollno." updated <br/>";
	}
}
}
//echo substr($raw,strpos($raw,'<tr>'),strpos($raw,'<tr>')+100);
//echo "<div id='hidden'></";
?>
<?php
	$v =isset($_GET['offset'])?$_GET['offset']:'0';
	$q = "SELECT REPLACE(email,'@iitk.ac.in','') as em FROM y8 WHERE id<589 LIMIT ".$v." ,100 " ;
	$r = mysql_query($q,$connection);
	while($z=mysql_fetch_array($r)){
		getByEmail($z['em']); 
		//print_r($z);
	}
	//getByEmail('anbansal')
?>
</body>
</html>