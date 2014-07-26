// JavaScript Document
var $pf_no='';
var $name='';
var $login='';
var $desig='all';
var $dept='all';
//var gender='both';
var field='';
$(document).ready(function(e) {
    /*$("input").keyup(function(){
		field = $("input").attr("id");
		val = $("input").val();
		switch(field){
			case 'pf_no':pf_no=val;break;
			case 'name':name=val;break;
			case 'login':login=val;break;
		}
	});
	$("input").click(function(){
		field = $("input").attr("id");
		//val = $("input").val();
		switch(field){
			case 'male':gender='m';break;
			case 'female':gender='f';break;
			case 'both':gender='both';break;
		}
	});
	$("select").change(function(){
		field = $("select").attr("id");
		val = $("select").val();
		switch(field){
			case 'dept':dept=val;
			case 'desig':desig=val;
		}
	});*/
	$("#desig").chosen();
	$("#dept").chosen();
	$("form").keyup(function(e){
		//if(e.keycode>36&&e.keycode<41){}
		//else{
			get_value();
			produce_results();
		//}
	});
	$("form").change(function(){
		get_value();
		produce_results();
	});
	$(".back-top").hide();
	
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('.back-top').fadeIn();
			} else {
				$('.back-top').fadeOut();
			}
		});
	
		$('.back-top').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});
});

function get_value(){
		$pf_no = $("#pf_no").val();
		$name = $("#name").val();
		$login = $("#login").val();
		$dept = $("select.dept").val();
		$desig = $("select.desig").val();
		//alert(desig);
}

function produce_results(){
		if(($.trim($pf_no)==''||$.trim($pf_no)=='0') && $.trim($login)=='' && $.trim($name)=='' && $.trim($dept)=='all' && $.trim($desig)=='all'){
			$("#results").html("No query inputed.");
			$(".count").html("");
		}else{
			$(".count").html();
			$("#results").html("Loading results.....<img src='images/25.gif' width='31' height='31' style='width:31px;height:31px;box-shadow:none;' />");
			$("#results").load("get.php", { 'params[]': [$pf_no, $name, $login, $dept, $desig] },function(response,status,xhr){
				if(status=='error'){
					$("#results").fadeOut("fast",function(){
						$(this).html("Sorry, Unable to proceed your request.");
						$("#results").fadeIn("fast");
					});
				}
				$(".count").html($("#hide").html());
				$("tr:nth-child(5) td:nth-child(even)").css("text-transform","lowercase");
				//$("body").niceScroll({cursorcolor:"#6AA84F",cursorwidth:"6px",cursorborderradius:"3px",cursorborder:"solid 1px #538312"});
			});
		}
		//$("#results").html("Pf No.-"+pf_no+":Name-"+name+":Desig-"+desig+":Dept-"+dept+":Login-"+login);
}