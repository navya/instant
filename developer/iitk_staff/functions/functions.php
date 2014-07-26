<?php
function highlight_stuff($result,$query){
	$query = strtolower($query);
	$result = str_replace($query,"<font color=\"#090\">{$query}</font>",$result);
	return $result;	
}

function convert_full($sym){
	if($sym=='M')return "Male";
	else return "Female";
}

function return_query($q,$as){
		$qs = explode(" ",$q);
		$searchbits = array();
		foreach($qs as $searchterm){
			$searchterm = mysql_real_escape_string(trim($searchterm));
				if(!empty($searchterm) || $searchterm==0){
				$searchbits[] = "`$as` LIKE '%$searchterm%'";
				}
		}
		return $searchbits;
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
		if(($ip_segms[0]!='172') || ($ip_segms[1]<'24') || ($ip_segms[1]> '32' )){return false;}
		else {return true;}
	}
}

//information about the searcher
function visitor(){
	$timex =strftime("%b %d %Y %X");
	//echo $timex;
	$ip=$_SERVER["REMOTE_ADDR"];
	$write = $ip.":".$timex;
	$file = fopen("searcher.txt","a");
	if($ip!='127.0.0.1' && $ip!='172.24.0.186'){
	fwrite($file,$write."\n");
	}
	$lines = count(file("searcher.txt"));
	fclose($file);
	return $lines;
}
?>
