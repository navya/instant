<?php
$url = "http://172.26.142.66:4040/Utils/CourseInfoPopup2.asp?Course=AE211";
$raw = file_get_contents($url);
$newlines = array("\t","\n","\r","\x20\x20","\0","\x0B");
$content = str_replace($newlines, "", html_entity_decode($raw));
$start = strpos($content,'<table border="0" height="269"');
$end = strpos($content,'</table>',$start) + 8;
$table = str_replace('</tr>','</td></tr>',substr($content,$start,$end-$start));
preg_match_all("|<tr(.*)</tr>|U",$table,$rows);
$info = array();
foreach ($rows[0] as $row){
	if ((strpos($row,'<th')===false)){
		//echo $row;
		preg_match_all("|<td(.*)</td>|U",$row,$cells);
 
		$td_left = str_replace(':','',strip_tags($cells[0][0]));
 
		$td_right = strip_tags($cells[0][1]);
		$info[$td_left] = $td_right;
		//$position = strip_tags($cells[0][2]);
 
		//echo " {$number} {$name} <br>\n";
 
	}
 
}

echo indent(json_encode($info));
?>
<?php
function indent($json) {

$result = '';
$pos = 0;
$strLen = strlen($json);
$indentStr = ' ';
$newLine = "<br/>";
$prevChar = '';
$outOfQuotes = true;

for($i = 0; $i <= $strLen; $i++) {

// Grab the next character in the string
$char = substr($json, $i, 1);

// Are we inside a quoted string?
if($char == '"' && $prevChar != '\\') {
$outOfQuotes = !$outOfQuotes;
}
// If this character is the end of an element, 
// output a new line and indent the next line
else if(($char == '}' || $char == ']') && $outOfQuotes) {
$result .= $newLine;
$pos --;
for ($j=0; $j<$pos; $j++) {
$result .= $indentStr;
}
}
// Add the character to the result string
$result .= $char;

// If the last character was the beginning of an element, 
// output a new line and indent the next line
if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
$result .= $newLine;
if ($char == '{' || $char == '[') {
$pos ++;
}
for ($j = 0; $j < $pos; $j++) {
$result .= $indentStr;
}
}
$prevChar = $char;
}

return $result;
}
?>