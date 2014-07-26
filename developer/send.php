<?php
$fd = fopen("data/feedbacks.txt","a");
$content = $_REQUEST['f']."\n \n"."------FROM ".$_SERVER['REMOTE_ADDR']."-----@---".strftime("%b %d %Y %X")."\n";
fwrite($fd,$content);
fclose($fd);
?>