<?php require_once('../functions/connection.php'); ?>
<?php require_once('functions/functions.php'); ?>
<?php
//print_r($_POST);
$fields = array('pf_no','name','login','dept','desig');
//echo "Search results for " ;
foreach($fields as $k=>$field){
	${$field}=$_POST['params'][$k];
	//echo "$field=><b>${$field}</b>"." ";
	switch($field){
		case 'desig':if($desig=='all')$desig='';else $q['desig']="`desig` LIKE '".$desig."%'";break;
		case 'dept':if($dept=='all')$dept='';else $q['dept']="`dept` = '".$dept."'";break;
		default:
			if(${$field}!=''){
				$q[$field]=implode(' AND ',return_query(${$field},$field));
			}//echo $q[$field];
			break;
	}
}
	$v = array('pf_no'=>'PF No.',
				'name'=>'Name',
				'desig'=>'Designation',
				'dept'=>'Department',
				'login'=>'E-mail',
				//'oloc'=>'Office-Location',
				//'otel'=>'Office Tel.',
				//'rloc'=>'Residence',
				//'rtel'=>'Residence Tel.'
				);
//echo "<br/>";
//print_r($q);
$qs = implode(' AND ',$q);
if($qs==''){return;}
$query = 'SELECT * FROM `staff` WHERE ' . $qs;
//echo $query;
$indexing_ar = array('pf_no','name','login');
/*$pf_no_len = strlen($pf_no);
$name_len=strlen($name);
$login_len=strlen($login);*/
$t=microtime();
$r = mysql_query($query,$connection);
$result=array();
$count = mysql_num_rows($r);//." ///";

while($pf=mysql_fetch_array($r)){
	$res='';
	$index='';
	foreach($indexing_ar as $ele){
		if(${$ele}!=''){
			if(strpos($pf[$ele],${$ele})<=5){
				$index .=strpos($pf[$ele],${$ele});
			}else $index .='6';
		}
		//echo " ind= ".$index;
	}
	$home_page = "http://home.iitk.ac.in/~".str_replace("@iitk.ac.in","",$pf['login']);
	$res .="<div class='container'>";
	//echo "<div style='display:none'>".$index."</div>";
	$res .="<div class='item'><a href='".$home_page."' target=\"_blank\" class=\"img-link\"><img src='http://oa.cc.iitk.ac.in:8181/Oa/Jsp/Photo/".ucwords($pf['pf_no'])."_0.jpg'  onerror=\"this.src='images/blank.jpg'\" /></a></div>";
	$res .="<table>";
	foreach($v as $k=>$s){
			$res .="<tr><td>$s</td><td>$pf[$k]</td></tr>";
	}
	$res .="<tr><td>Office Location (Tel.)</td><td>".$pf['oloc']."(".$pf['otel'].")</td></tr>"."<tr><td>Residence (Tel.)</td><td>".$pf['rloc']."(".$pf['rtel'].")</td></tr>";
	$res .="</table></div>";
	//$res .= '';//div of output
	if(array_key_exists($index,$result)){
				$result[$index] .=$res;
	}else{
				$result[$index] = $res;
	}
	//get_index();
	//echo $index."--";
}
/*$a = array('000','011','010','100');
sort($a);
print_r($a);*/
ksort($result);
foreach($result as $v){
	echo $v;
}
$t =round(1000*(microtime()-$t),2);
echo "<div id='hide' style='display:none'>".$count." results found in ".$t." ms.</div>";
?>