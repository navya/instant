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
echo str_replace(' table ',' ',$table)."</table>";
?>
