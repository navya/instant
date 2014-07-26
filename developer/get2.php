<?php include_once("functions/connection.php"); ?>
<?php include_once("functions/functions.php"); ?>
<?php
$tile = ($_GET['tile']==true)?'tile':'';
$sort = isset($_GET['sort']) ? $_GET['sort']:'name';
$offset = isset($_GET['offset'])? $_GET['offset']:0;

$ccount = 0;
$o ='';

$fields = array('roll_no','name','program','dept','login','gender','city','course','hostel','bg');//input fields

foreach($fields as $field){
	if(isset($_REQUEST[$field])){
		${$field} =mysql_real_escape_string(strtolower(trim($_REQUEST[$field])));
	}
	else{
		${$field}="";
	}
}

if(strlen($roll_no)>1 || $login!="" || $dept!="all" || $name!="" || $program!="all" || $city!=''||$course!=''||$hostel!=''||$bg!=''){}
else{ echo "<div id=\"message\"><p>Query Please</p></div>";}

$time_start = microtime(true);
$dept_query = explode(" ",$dept);
$dept = $dept_query[0];

if($roll_no!='' && $roll_no>9){
	$tables = order_tables($roll_no);
}else{
	$tables = array("y11","y11_2","y10","y10_2","y9","y9_2","y8","y8_2","y7","y7_2","y6","y6_2","y12","y13","y13_2");
}

foreach($tables as $table_name){
	if(strpos($roll_no,$table_name)===0){$tables = array($table_name,$table_name."_2");}
	if((substr($table_name,1,2)>=10)&&strpos($roll_no,$table_name)===0){$roll_no=str_replace("y","",$roll_no);}
}

$sql = array();

foreach($tables as $table_name){
	if( ($table_name=='y6' || $table_name=='y6_2' || $table_name=='y7') && $hostel!=""){continue;}
	$sql[$table_name] = "SELECT * FROM `".$table_name."` WHERE ";
	$total_query = array_merge((array)return_query($roll_no,"roll_no"),(array)return_query($name,"name"),(array)return_query($login,"email"),(array)return_query($city,"home_town"));
	if($dept!="all")$total_query =array_merge((array)$total_query,(array)" `department` LIKE '$dept%'");
	if($program!="all")$total_query =array_merge((array)$total_query,(array)" `program` = '$program'");
	if($gender!="both")$total_query = array_merge((array)$total_query,(array)" `gender` = '$gender'");
	if($hostel!="") $total_query = array_merge((array)$total_query,(array)" `hostel` LIKE '$hostel%'");
	if($bg!="") $total_query = array_merge((array)$total_query,(array)" `blood_group` = '$bg'");
	if($course!=""){$courses=get_course_list($course);$total_query = array_merge((array)$total_query,(array)" `roll_no` IN $courses");}
	$sql[$table_name] .= implode(" AND ",$total_query);
}

$tSQL = implode(" UNION ",$sql);
//$count = mysql_num_rows(mysql_query($tSQL)); 
$count = @mysql_num_rows(mysql_query($tSQL));

$tSQL .= " ORDER BY INSTR($sort,'$name') ASC, $sort ASC LIMIT $offset, 30";
//echo $tSQL;
$query = mysql_query($tSQL);

$time_end = microtime(true);
$time = $time_end - $time_start;
$time =round(($time*1000*100)/100+0.05,2);

if($offset==0){
	if($count!=0){$message = "<p>".$count." search results in ". $time ." ms for your query(ies).";}
	else $message = "<p>No results found for your query(ies).</p>";
	echo $message;
}
	if(!isset($name_suggestion))
		$name_suggestion="";

while($row = @mysql_fetch_assoc($query)){
	
	$img_rollno = str_replace('Y8127','Y8',ucwords($row['roll_no']));
	
	$home_page = "http://home.iitk.ac.in/~".str_replace("@iitk.ac.in","",$row['email']);
	$output  ="<div class='container $tile'>";
	$output .="<div class='item'><a href='".$home_page."' target=\"_blank\" class=\"img-link\"><img src='http://oa.cc.iitk.ac.in:8181/Oa/Jsp/Photo/".$img_rollno."_0.jpg'  onserror=\"this.src='images/blank.jpg'\" title='".$row['name']."'/></a><div class='closeDiv tooltip' onclick='$(this).parents(\".container\").slideUp()' title='Hide This Entry'>X</div></div>";
	$output .="<table class='info'>";
	$output .="<tr><td>roll no </td><td>".highlight_stuff($row['roll_no'],$roll_no)." <a href='#examscheduler' class='tooltip eicon' onmouseover='showExamSchedule(\"".$row['roll_no']."\",this)'><span class='classic'></span></a></td></tr>";
	$output .="<tr><td>name </td><td>".highlight_stuff($row['name'],$name).user_profile($row['roll_no'])." <a href='https://www.facebook.com/search/results.php?q=".$row['name']."&type=users' title='Search this name on FB' target='_blank' class='icon'></a>"."</td></tr>";
	$output .="<tr><td>department </td><td>".$row['department']."[{$row['program']}]"."</td></tr>";
	$output .="<tr><td>hostel </td><td>".$row['hostel']." [".convert_full($row['gender'])."]"."</td></tr>";
	$output .="<tr><td>email </td><td class='email'>".highlight_stuff($row['email'],$login)." <a href='https://www.facebook.com/search/results.php?q=".$row['email']."' title='Search this email on FB' target='_blank' class='icon'></a>"."</td></tr>";
	$output .="<tr><td>blood group </td><td>".strtoupper($row['blood_group'])."</td></tr>";
	$output .="<tr><td>Nearest Railway St. </td><td>".highlight_stuff($row['home_town'],$city)." <a href='http://maps.google.com/maps?q=".$row['home_town']."%20india' title='look this city in google map' target='_blank' class='gicon'></a> <a href='http://www.trainenquiry.com/TrainsBetw2St_Select.aspx?sourceStName=kanpur%20central&destinationStName=".refine_hometown($row['home_town'])."' class='ricon' title='Query for a train from kanpur to this station' target='_blank'></a>"."</td></tr>";
	$output .="</table>";
	$output .="</div>";
	echo $output;
	
	if($ccount<5){
		$ccount++;
		$name_suggestion .='<li onclick="setNameFromDropBox(\''.$row['name'].'\');"><img src=\'http://oa.cc.iitk.ac.in:8181/Oa/Jsp/Photo/'.$img_rollno.'_0.jpg\' width=\'30\' height=\'36\' style=\'width:30px;height:36px;border:0;box-shadow:none;float:left;padding:1px 7px 1px 1px;margin:0;\'>'.$row['name'].'<br/><i style=\'font-size:12px;text-transform:lowercase;\'> ('.$row['email'].')</i><font style=\'float:right\'>'.substr($row['roll_no'],0,2).'</font></li>';
		$o .=$row['roll_no']."-";
	}
}

if($count>$offset+30){
	$newOffset = $offset+30;
	$x = $count - $newOffset; 
	echo "<button onclick='loadMore($newOffset);$(this).remove();' class='load' >Show ". "$x" ." more results</button>";
}
echo '<div style="display:none" id="NameDropBox"><ul>'.$name_suggestion.'</ul></div>';

if($count==0 && trim($name)!=''){
	echo "<p>Are you looking for one of these names: ".generate_suggestions($name)." . Press Ctrl + Z to return back for suggestions after selecting one name.</p>";
}
?>
<?php mysql_close($connection); ?>
