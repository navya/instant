<?php include("functions/functions.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search Instantly</title>
<link href="style.css" type="text/css" rel="stylesheet" />
<style>
#container{border-top: 1px solid #CCC;}
.qA{
padding: 10px;
margin:10px 0;
min-height: 10px;
background: white;
border: 1px solid #CCC;
border-radius: 5px;
font-size: 13px;
font-family: "lucida grande",tahoma,verdana,arial,sans-serif;
color: #333;
line-height: 1.28;
}
.q:before{content:'Que: ';font-weight:bold;}
.A{padding-left:30px;}
.A:before{content:'Ans: ';font-weight:bold;}
</style>
<script src="iitk_staff/js/jquery-1.7.min.js" type="text/javascript"></script>
<script>
function fshow(){
	$("#feedback").fadeIn(400);
}
function fsend(){
	var feedback = $("#feedbackr").val();
	if(feedback!=''){
			$("#comment").fadeOut(400,function(){
			$.get("send.php",{f:feedback});
			//alert("<b>Thank you</b>");
			$("#thanks").fadeIn();
			$("#comment").fadeIn(400,function(){
				setTimeout(1500);
				$("#feedback").fadeOut(400);
				$("#feedbackr").val("");
				$("#thanks").fadeOut();
			});
		});
	}
}

function fclose(){
	$("#feedback").fadeOut("slow");
}
</script>
<link rel="shortcut icon" href='favicon.ico' /> 
</head>
<body>
<div id='hidden' style='display:none'></div>
<div align="center"><h2><a href='./' style='text-decoration:none;' class='active' >Student Search</a> <a href='./iitk_staff'>Faculty/Employee Search</a> <a href="./faq.php">FAQs</a> </h2></div>
<div id="container" style="padding-top:50px;">
<button style="float:right;margin-right:90px;margin-top:-35px;" id="fshow" onclick="fshow()">Any Question</button>
<!--
<div style="float:right">
<label for="order">Sort By: </label>
<select name="order" onchange="setOrder(this.value)">
<option value='rollno'>Roll No</option>
<option value='rollno'>Name</option>
<option value='rollno'>Gender</option>
</div>-->
<div id="results" name='results'>
	<div class='qA'>
		<div class='q'>Is this website available from outside-IITK ?</div>
		<div class='A'>No, navya server is not accessible from outside IITK.  This website runs on Navya Sever.</div>
	</div>
	<div class='qA'>
		<div class='q'>What algorithm/technique is used to make the seaching so fast?</div>
		<div class='A'>There is no algorithm, it is just a simple use of AJAX and MySQL. MySQL itself is a very fast technology for small databases. A small database, we can say may be upto 1 millon rows. </div>
	</div>
	<p style='text-align:center;'>--By Hrishabh Gupta--</p>
</div>
</div>
<div id="footer">
<p>&copy;search.junta.iitk.ac.in, 2010</p>
</div>
<div id="feedback" style="display:none;background:#FFF;position:fixed;left:50%;top:50%;width:500px;height:280px;margin-top:-140px;margin-left:-250px;border:7px solid rgba(82,82,82,0.7)">
	<div style="height:30px;background:#abcf87;padding:5px 10px;color: white;font-size: 22px;font-weight: bold;font-family: Arial;"><strong>Your Question</strong></div>
	<div id="comment" style="height:180px;padding:5px 10px"><textarea style='width: 470px;height: 170px;resize:none' id="feedbackr"></textarea></div>
	<div style="height:40px;padding:5px 10px;background: #ABCF87;"><p style='display:none;color:#FFF;width:140px;float:left;margin-top: 10px;margin-left: 10px;font-weight: bold;font-family: Arial;' id='thanks'>Thank you</p><button style="float:right" id="fclose" onclick="fclose()">Close</button><button style="float:right" id="fsend" onclick="fsend()">Send</button></div>
</div>
</body>
</html>
