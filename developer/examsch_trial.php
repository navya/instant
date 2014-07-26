<?php include_once("functions/connection.php"); ?>
<?php include_once("functions/functions.php"); ?>
<?php
$roll_no = $_GET['roll_no'];
$exam_url = 'http://172.26.142.68/examscheduler2/personal_schedule.php?rollno='.$roll_no;
$raw = file_get_contents($exam_url);
$newlines = array("\t","\n","\r","\x20\x20","\0","\x0B");
$content = str_replace($newlines, "", html_entity_decode($raw));
$start = strpos($content,'<table table class="contenttable_lmenu"');
$end = strpos($content,'</body>',$start) - 7;
$table = substr($content,$start,$end-$start);
echo '<p><strong>Exam Schedule for '.$roll_no.' </strong></p>';
$table =  str_replace(' table ',' ',$table)."d></tr></table>";
//echo $table;
preg_match_all("|<tr(.*)</tr>|U",$table,$rows);
$newtable = array();
foreach ($rows[0] as $row){
 
	if ((strpos($row,'<th')===false)){
 
		preg_match_all("|<td(.*)</td>|U",$row,$cells);
 
		$course = strip_tags($cells[0][0]);
 
		$day = strip_tags($cells[0][1]);
 
		$slot = strip_tags($cells[0][2]);
		$key = $day.$slot;
		$j=99;
		if($day==0){$key=$j;$j++;}
		if($day=='DAY'){$key=0;}
		//echo "{$position} - {$name} - Number {$number} <br>\n";
		$newtable[$key] = array($day,$slot,$course);
	}
}
ksort($newtable);
echo "<table border=1 width=600 style='table-layout:fixed'><colgroup><col width=100></col><col width=100></col><col width=100></col></colgroup>";
foreach($newtable as $row){
	echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."&nbsp;&nbsp;&nbsp;<b>".venue($row[2])."</b></td></tr>";
}
echo "</table>";
//echo "<!--".venue('IME671')."-->";
?>
