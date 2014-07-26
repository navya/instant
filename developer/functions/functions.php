<?php
function highlight_stuff($result,$query){
	$query = strtolower($query);
	$result = str_replace($query,"<font color=\"#090\">{$query}</font>",$result);
	return $result;	
}

function convert_full($sym){
	if($sym=='M'||$sym=='m')return "Male";
	else return "Female";
}

function return_query($q,$as){
		$qs = explode(" ",$q);
		$searchbits = array();
		foreach($qs as $searchterm){
			$searchterm = mysql_real_escape_string(trim($searchterm));
				if(!empty($searchterm)){
				$searchbits[] = "`$as` LIKE '%$searchterm%'";
				}
		}
		return $searchbits;
}

function get_course_list($course){
		//$url = "http://172.26.142.65:6060/Course/download/".$course.".txt";//odd sem
		$url = "http://172.26.142.66:6060/Course/download/".$course.".txt";//even sem
		$data = rtrim(@file_get_contents($url));
		$data = explode("\n",$data);
		//print_r($data);
		array_shift($data);
		array_shift($data);
		array_shift($data);
		//print_r($data);
		//echo count($data);
		$roll_no=array();
		foreach($data as $one){
				$st = explode("\t",$one);
				$roll_no[] = strtolower(trim($st[1]));
		}
		//print_r($roll_no);
		return "('".implode("','",$roll_no)."')";
}
function url_exist($file){
	$file_headers = @get_headers($file);
	if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
    return false;
	}
	else {
    return true;
	}
}

//security
function valid_ip(){
	$ip=$_SERVER["REMOTE_ADDR"];
	if($ip == '127.0.0.1')return true;
	else{
		$ip_segms = explode(".",$ip);
		if(($ip_segms[0]!='172') || ($ip_segms[1]<'21') || ($ip_segms[1]> '32' )){return false;}
		else {return true;}
	}
}

//information about the searcher
function visitor(){
	$timex =strftime("%b %d %Y %X");
	//echo $timex;
	$prevyr_count=7082;
	$ip=$_SERVER["REMOTE_ADDR"];
	$parse_url = (isset($_SERVER['HTTP_REFERER'])) ? parse_url($_SERVER['HTTP_REFERER']) : null;
	$write = $ip.":".$timex." ".$parse_url['host'];
	$file = fopen("searcher.txt","a");
	if($ip!='127.0.0.1' && $ip!='172.24.0.186'){
	fwrite($file,$write."\n");
	}
	$lines = 7082 + count(file("searcher.txt"));
	fclose($file);
	return $lines;
}

function generate_suggestions($search=null){
	include_once('names.php');
	$parts = explode(" ",$search);
	if(count($parts)==1){ $q=1; }
	for($i=0; $i<count($names);$i++)
	{
	   $reverse = reverse_letters($search);
	   $names_part = explode(" ",$names[$i]);
	   if(isset($q) && $q==1){
			$match_val = levenshtein($search,$names_part[0]); 
	   }else{
			$match_val = min(levenshtein($search,$names[$i]),levenshtein($reverse,$names[$i]));
	   }
	   $match_array[$i] = $match_val;
	}
	asort($match_array);
	foreach ($match_array as $key=>$val)
	{
		if($val>5){ $result[] = '';}
		else  $result[] = $names[$key];
	}
	$x ='';
	//print_r($result);
	for($j=0;$j<3;$j++){
		$x .= "<a href='#suggested' class='sug' onclick='$(\"#name\").val(\"".$result[$j]."\");$(\"#name\").focus().trigger(\"keyup\")'>".$result[$j]."</a> ";
	}
	return $x;
}

function reverse_letters($str){
	return implode(" ",array_reverse(explode(" ",$str)));
}

function order_tables($rollno_input=11){
	$min_yr = 6;
	$max_yr = 13;
	$tables=array();
	//echo $rollno_input;
	if(substr($rollno_input,0,2)>20){
		$j = substr($rollno_input,0,1);
	}else if(substr($rollno_input,0,2)>=10){
		$j = substr($rollno_input,0,2);
	}
	$j = ($j>$max_yr||$j<$min_yr)?$j=$max_yr:$j;
	//echo $j;
	if($rollno_input>1){		
		for($i=$min_yr;$i<=$max_yr;$i++){
			$tables[] = "y".$j;
			$tables[] = "y".$j."_2";
			if($j==$min_yr){$j=$max_yr;}
			else{
				$j--;
			}
		}
	}
	return $tables;
}

function refine_hometown($ht){
	$ht = strtolower($ht);
	$seps = array(',','(');
	foreach($seps as $sep){
		if(count(explode($sep,$ht))>1){
			$ht = substr($ht,0,strpos($ht,$sep));
		}
	}
	
	$escape_words = array('jn.','jn','junction','cantt.','cantt','city','junc','junct','junc.','junct.','.up');
	$hta=explode(" ",$ht);
	$s = $hta[0];
	foreach($escape_words as $ew){
		if($s==$ew){
			$hta[1]='';break;
		}
	}
	return trim(implode(" ",$hta));
}

function venue($subject){
	global $connection;
	$subject = mysql_real_escape_string($subject);
	$sql = 'SELECT venue FROM `venue` WHERE course LIKE "%'.$subject.'%" LIMIT 1';
	$query = @mysql_query($sql,$connection);
	$result = @mysql_fetch_assoc($query);
	if(!$result){
		return '';
	}
	return $result['venue'];
}

function user_profile($roll_no){
	global $connection;
	//echo "---";
	$roll_no = mysql_real_escape_string($roll_no);
	$sql = "SELECT * FROM `placement_stats` WHERE `roll_no` = '$roll_no' ORDER BY `id` DESC";
	$query = @mysql_query($sql,$connection);
	$num = @mysql_num_rows($query);
	$result = '';
	//if($num==0){ return $result;}
	$result .=" <a href='#profile' class='tooltip picon'>Pf<span class='classic'><ul id='profile'>";
	while($row=@mysql_fetch_assoc($query)){
		$result .="<li><b>". $row['profile'] ."</b> ". $row['company']." </li>";
	}
	if($_SERVER['REMOTE_ADDR']=='172.24.0.186'){
		$result .="<li>Add New <br/><input type='text' name='profile_$roll_no' title='profile' /><input type='text' name='company_$roll_no' title='company' /><button onclick='save(\"".$roll_no."\")'>Add</li>";
	}
	$result .="</ul></span></a>";
	return $result;
}
?>
