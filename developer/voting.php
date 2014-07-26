<?php
$handle = @fopen("data/voting.txt", "r");
$filesize = filesize("data/voting.txt");
$ex = explode("::",fgets($handle,$filesize+32));
print_r($ex);
if(in_array($_SERVER['REMOTE_ADDR'],$ex)){
	echo "Yes it is";
}else{
	echo "<button>Thumb Up</button>";
}
?>