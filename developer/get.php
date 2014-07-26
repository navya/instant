<?php include_once("functions/connection.php"); ?>
<?php include_once("functions/functions.php"); ?>
<?php
	$ip_validation = true;//search will be displayed when $ask will become true, default value is set to be false
	//$ip_validation = valid_ip();
?>
<?php
//setting up the queries
$sort='id';
$ask = false;
$requested_queries="";
$tile = ($_GET['tile']==true)?'tile':'';
if(!$ip_validation){echo "Either you are an outsider or your proxy is not set to noproxy";}

$fields = array('roll_no','name','program','dept','login','gender','city','course','hostel','bg');//input fields
foreach($fields as $field){//checking which fields are filled which are not
	if(isset($_REQUEST[$field])){//$_REQUEST['x']=value of x got/posted
	${$field} =mysql_real_escape_string(strtolower(trim($_REQUEST[$field])));
	//$requested_queries .="[".$field."=".${$field}."(".strlen(${$field}).")]";
	}
	else{
	${$field}="";
	}
}
if(strlen($roll_no)>1 || $login!="" || $dept!="all" || $name!="" || $program!="all" || $city!=''||$course!=''||$hostel!=''||$bg!=''){$ask=true;}
else{ echo "<div id=\"message\"><p>Query Please</p></div>";}
?>
<?php
$count = 0;//no of output results
$ccount=0;
$output="";
$name_suggestion="";
$extra_output="";//this is the output of those results which carry the search_word not in beginnig 
$result_display_count=0;$output_msg="";
$o = '';
if($ask==true){
	//search execution time starts here
	$time_start = microtime(true);
	//sql query	
	$dept_query = explode(" ",$dept);
	$dept = $dept_query[0];
	//echo return_query($rollNo_queries,"roll_no");
	//data is saved in a table name y8 in database
	//echo "<!--";
	//print_r(order_tables($roll_no));
	//echo "-->";
	if($roll_no!='' && $roll_no>9){
		$tables = order_tables($roll_no);
	}else{
		$tables = array("y11","y11_2","y10","y10_2","y9","y9_2","y8","y8_2","y7","y7_2","y6","y6_2");
	}
	foreach($tables as $table_name){
		if(strpos($roll_no,$table_name)===0){$tables = array($table_name,$table_name."_2");}
		//if(mtech,mba,){}, && strlen($table_name)==2
		//taking care of roll_no of batches 10,11,12....etc.
		if((substr($table_name,1,2)>=10)&&strpos($roll_no,$table_name)===0){$roll_no=str_replace("y","",$roll_no);}
	}
	foreach($tables as $table_name){
		if( ($table_name=='y6' || $table_name=='y6_2' || $table_name=='y7') && $hostel!=""){continue;}
		$sql = "SELECT * FROM `".$table_name."` WHERE ";
		//array_merge = merge the two arrays in one
		$total_query = array_merge((array)return_query($roll_no,"roll_no"),(array)return_query($name,"name"),(array)return_query($login,"email"),(array)return_query($city,"home_town"));
		if($dept!="all")$total_query =array_merge((array)$total_query,(array)" `department` LIKE '$dept%'");
		if($program!="all")$total_query =array_merge((array)$total_query,(array)" `program` = '$program'");
		if($gender!="both")$total_query = array_merge((array)$total_query,(array)" `gender` = '$gender'");
		if($hostel!="") $total_query = array_merge((array)$total_query,(array)" `hostel` LIKE '$hostel%'");
		if($bg!="") $total_query = array_merge((array)$total_query,(array)" `blood_group` = '$bg'");
		if($course!=""){$courses=get_course_list($course);$total_query = array_merge((array)$total_query,(array)" `roll_no` IN $courses");}
		$sql .= implode(" AND ",$total_query);//implode is reverse of explode
		$sql .= " ORDER BY `$sort`";
		//query for the results is set now
		//echo "<!--".$sql.$bg."-->";
		//query is executed when function mysql_query is run
		$results = @mysql_query($sql,$connection);
		//echo $sql;
		$count += @mysql_num_rows($results);//how much results we obtained
		$output_count = 0;
		//dispaly results corresponding to search
			while($row = @mysql_fetch_array($results)){
				if($table_name=='y8' & $row['id']<600){
					switch(strlen($row['id'])){
						case 1:$img_rollno='Y800'.$row['id'];break;
						case 2:$img_rollno='Y80'.$row['id'];break;
						case 3:$img_rollno='Y8'.$row['id'];break;
					}
					//$img_rollno='Y8'.$row['id'];
				}
				else{$img_rollno = ucwords($row['roll_no']);}
				$home_page = "http://home.iitk.ac.in/~".str_replace("@iitk.ac.in","",$row['email']);
					if(strtolower($name)==strtolower(substr($row['name'],0,strlen($name)))){
						//echo strtolower(substr($row['name'],0,strlen($q)));
						$output .="<div class='container $tile'>";
						$output .="<div class='item'><a href='".$home_page."' target=\"_blank\" class=\"img-link\"><img src='http://oa.cc.iitk.ac.in:8181/Oa/Jsp/Photo/".$img_rollno."_0.jpg'  onerror=\"this.src='images/blank.jpg'\" title='".$row['name']."'/></a><div class='closeDiv tooltip' onclick='$(this).parents(\".container\").slideUp()' title='Hide This Entry'>X</div></div>";
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
						$output_count++;
						if($output_count>100)break;
						if($ccount<5){
							$ccount++;
							$name_suggestion .='<li onclick="setNameFromDropBox(\''.$row['name'].'\');"><img src=\'http://oa.cc.iitk.ac.in:8181/Oa/Jsp/Photo/'.$img_rollno.'_0.jpg\' width=\'30\' height=\'36\' style=\'width:30px;height:36px;border:0;box-shadow:none;float:left;padding:1px 7px 1px 1px;margin:0;\'>'.$row['name'].'<br/><i style=\'font-size:12px;text-transform:lowercase;\'> ('.$row['email'].')</i><font style=\'float:right\'>'.substr($row['roll_no'],0,2).'</font></li>';
							$o .=$row['roll_no']."-";
						}
					}
					else{
						$extra_output .= "<div class='container $tile'>".
										 "<div class='item'><a href=\"{$home_page}\" target=\"_blank\"><img src='http://oa.cc.iitk.ac.in:8181/Oa/Jsp/Photo/".ucwords($row['roll_no'])."_0.jpg' onerror=\"this.src='images/blank.jpg'\" /></a><div class='closeDiv tooltip' onclick='$(this).parents(\".container\").slideUp()' title='Hide This Entry'>X</div></div>".
										 "<table class='info'>".
										 "<tr><td>roll no </td><td>".highlight_stuff($row['roll_no'],$roll_no)." <a href='#examscheduler' class='tooltip eicon' onmouseover='showExamSchedule(\"".$row['roll_no']."\",this)'><span class='classic'></span></a></td></tr>".
										 "<tr><td>name </td><td>".highlight_stuff($row['name'],$name).user_profile($row['roll_no']).". <a href='https://www.facebook.com/search/results.php?q=".$row['name']."&type=users' title='Search this name on FB' target='_blank' class='icon'></a>"."</td></tr>".
										 "<tr><td>department </td><td>".$row['department']."[{$row['program']}]"."</td></tr>".
										 "<tr><td>hostel </td><td>".$row['hostel']." [".convert_full($row['gender'])."]"."</td></tr>".
										 "<tr><td>email </td><td class='email'>".highlight_stuff($row['email'],$login)." <a href='https://www.facebook.com/search/results.php?q=".$row['email']."' title='Search this email on FB' target='_blank' class='icon'></a>"."</td></tr>".
										 "<tr><td>blood group </td><td>".strtoupper($row['blood_group'])."</td></tr>".
										 "<tr><td>Nearest Railway St. </td><td>".highlight_stuff($row['home_town'],$city)." <a href='http://maps.google.com/maps?q=".$row['home_town']."%20india' title='look this city in google map' target='_blank' class='gicon'></a> <a href='http://www.trainenquiry.com/TrainsBetw2St_Select.aspx?sourceStName=kanpur%20central&destinationStName=".refine_hometown($row['home_town'])."' class='ricon' title='Query for a train from kanpur to this station' target='_blank'></a>"."</td></tr>".
										 "</table>".
										 "</div>";		
					}
			}
	
	}
	$time_end = microtime(true);//query execution finish
	$time = $time_end - $time_start;//time taken in searching the result
	$time =round(($time*1000*100)/100+0.05,2);//time is converted to 2nd point of decimal
	if($count!=0){$message = "<p>".$count." search results in ". $time ." ms for your query(ies).";if($count>100)$message .="About 100 shown below.</p>";}
	else $message = "<p>No results found for your query(ies).</p>";//if no results is found,this message is displayed
	echo $message;
	echo $output;
	if($count<100)echo $extra_output;
	/*if($count==0){
		produce suggestions
	}*/
	//echo "<p>".$output_msg."</p>";
	//results ends
	//document finishes loading
	echo '<div style="display:none" id="NameDropBox"><ul>'.$name_suggestion.'</ul></div>';
	//echo "<div style='display:none'>".$sql."</div>";
	//$fil=fopen("data/".date("m-d-y").".txt","a");
	//fwrite($fil,$o);
	//fclose($fil);
	if($count==0 && trim($name)!=''){
		//$fq=fopen('data/0_results.txt','a');
		//fwrite($fq,$name."::");
		//fclose($fq);
		echo ". <p>Are you looking for one of these names: ".generate_suggestions($name)." . Press Ctrl + Z to return back for suggestions after selecting one name.</p>";
		//$new_name=substr($name,0,strlen($name)-1);
		//include("get.php?roll_no=$roll_no&name=$new_name&program=$program&dept=$dept&login=$login&gender=$gender&city=$city&course=$course");
	}
}
?>
<?php mysql_close($connection); ?>