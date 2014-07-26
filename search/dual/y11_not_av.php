<?php
include_once('connection.php');
$sql = "SELECT * 
FROM  `y11` 
WHERE  `blood_group` = 'not'";
$r = mysql_query($sql,$connection);
while($q = mysql_fetch_assoc($r)){
	//$url ='http://oa.cc.iitk.ac.in:8181/Oa/Jsp/OAServices/IITk_SrchRes.jsp?typ=stud&numtxt='.$q['roll_no'].'&sbm=Y';
	$raw = file_get_contents('http://oa.cc.iitk.ac.in:8181/Oa/Jsp/OAServices/IITk_SrchRes.jsp?typ=stud&numtxt='.$q['roll_no'].'&sbm=Y');
	echo $url;
	if(strpos($raw,'+')>0 || strpos($raw,'-') > 0){
		if(strpos($raw,'+')>0){
			$k = strpos($raw,'<!--<b>  Category:');
			$bg = substr($raw, $k-5, 3); 
		}else if(strpos($raw,'-')>0){
			$k = strpos($raw,'<!--<b> Category:');
			$bg = substr($raw, $k-5, 3); 
		}
		echo $k;
		echo "UPDATE y11 SET blood_group = '$bg' WHERE roll_no = '".$q['roll_no']."';<br/>";
	}
}
?>