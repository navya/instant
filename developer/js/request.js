// JavaScript Document

//window.onkeyup = initAll;
//window.onchange = initAll;

var xhr = false;
var url = "get.php";
var roll_no = "";
var name="";
var program="all";
var department="all";
var email="";
var gender="both";
var city="";
var course="";
var order="id";
var hostel="";
var bg='';
var tile = 0;
var offset = 0;

function setRoll(x){
	roll_no = x;
}
function setName(x){
	name = x;
	//alert(document.activeElement.getAttribute("id"));
}

function removeDropBox(){
	nameIsFocused=false;
}
function setProgram(x){
	program = x;
}
function setDept(x){
	department = x;
}
function setLogin(x){
	email = x;
}
function setGender(x){
	gender = x;
}
function setCity(x){
	city = x;
}
function setCourse(x){
	course=x;
}

function setHostel(x){
	hostel=x;
}

function setBG(x){
	bg=x;
}

//function setOrder(x){
	//order = x;
//}

function initAll(){
	//setting httprequest
	//document.getElementById("results").onKeyup=function(){alert("s");}//disappearDropNames();
	//document.Option.onMouseover= function(){this.bgColor="#000";}
	if(window.XMLHttpRequest){
		xhr = new XMLHttpRequest();
	}
	else{
		if(window.ActiveXObject){//IE6 browser	
			try{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){}
		}
	}
	//when request is set
	if(xhr){
		url = "get2.php?roll_no="+roll_no+"&name="+name+"&program="+program+"&dept="+department+"&login="+email+"&gender="+gender+"&city="+city+"&course="+course+"&hostel="+hostel+"&bg="+bg+"&tile="+tile+"&offset="+offset;
		//document.getElementById("hidden").innerHTML = url;
		//alert(tile);
		if(roll_no!="" || name!="" || program!="" || department!="" || email!="" ||hostel!=""||bg!=''|| course!="" || gender!="both"){
			xhr.onreadystatechange = response;
			xhr.open("GET",url,true);
			xhr.send();
		}
	}
}

function response(){
	if(offset==0){
		document.getElementById("results").innerHTML = "<img src='/images/load.gif' width='31' height='31' style='float:left;width:31px;height:31px;box-shadow:none;border:none;' />";
	}
	if(xhr.readyState==4){
		if(xhr.status==200){
			try{
				if(offset==0){
					document.getElementById("results").innerHTML = xhr.responseText;
				}else{
					$("#results").append(xhr.responseText);
				}
				offset = 0;
				if(document.activeElement.getAttribute("id")=='name'){
					showDropNames();
				}else switch(document.activeElement.getAttribute("name")){
					//alert('results');
					case 'roll_no':disappearDropNames();break;
					case 'email':disappearDropNames();break;
					case 'program':disappearDropNames();break;
					case 'dept':disappearDropNames();break;
					case 'gender':disappearDropNames();break;
					case 'city':disappearDropNames();break;
					case 'results':disappearDropNames();break;
				}
			}
			catch(e){
				document.getElementById("results").innerHTML = "Unable to load results";
			}
		}
		else{
			document.getElementById("results").innerHTML = "problem in setting http request"+ xhr.status;	
		}
	}
	else{
		//document.getElementById("results").innerHTML = "Sorry, but request is not initialized.";
	}
}

function changeNameVal(str){
	//document.forms[0].reset();
	setName(str);
	//document.forms[0].reset();
	initAll();
	document.getElementById("name").value=str;
}

function showDropNames(){
	var temp = document.getElementById("NameDropBox").innerHTML;
	//$("#name_suggest").fadeIn();
	document.getElementById("name_suggest").innerHTML = temp;
	//initAll();
	//$("#name_suggest").slideDown(400);
	setTimeout(disappearDropNames,3000);
}

function disappearDropNames(){
	document.getElementById("name_suggest").innerHTML='';
}

function setNameFromDropBox(q){
	document.getElementById("name").value = q;
	setName(q);
	disappearDropNames();//alert("..");
	initAll();
}

function popupWin(URL, width, height){
	var popup_width = width;
	var popup_height = height;
	var left = (screen.width - width)/2;
	var top = (screen.height - height)/2;
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=no,scrollbars=1,location=no,statusbar=no,menubar=no,resizable=yes,status=no,titlebar=no,width='+popup_width+',height='+popup_height+',min-width='+popup_width+',min-height='+popup_height+',top='+top+',left='+left+'');");
}

function showExamSchedule(roll_no,ele){
	var examURL = 'examsch_trial.php?roll_no='+roll_no;
	$.get(examURL,function(data){
		$("#hidden").html(data);
		//$("colgroup").remove();
		$("#hidden").find("td").each(function(index){
			if(index%3==0 && index!=0){
				$(this).html(covert_day($(this).html()));
			}else if(index%3==1 & index!=1){
				$(this).html(convert_slot($(this).html()));
			}
		});
		$(ele).find('span').html($("#hidden").html()).append('<p class="copy">&copy;credits: http://172.26.142.68/examscheduler/</p>');
		$(ele).click(function(){$("#hidden").fadeIn()});
		$("#hidden p").append("<span onclick='$(this).parents(\"div\").fadeOut()'>close</span>");
	});
	//alert('welcome');
}

function covert_day(i){
	switch(i){
		case "1": return '27/02/12(MON)';
		case "2": return '28/02/12(TUE)';
		case "3": return '29/02/12(WED)';
		case "4": return '01/03/12(THU)';
		case "5": return '02/03/12(FRI)';
		case "6": return '03/03/12(SAT)';
		case "0": return '26/02/12(SUN)';
	}
}

function convert_slot(i){
	switch(i){
		case "1": return '7:30-9:30';
		case "2": return '10:00-12:00';
		case "3": return '12:30-14:30';
		case "4": return '15:00-17:00';
		case "5": return '17:30-19:30';
		case "0": return 'As per Instructor';
	}
}

function save(roll_no){
	var profile = $("[name=profile_"+roll_no+"]").val();
	var company = $("[name=company_"+roll_no+"]").val()
	//alert(profile+"-"+company);
	$.get("save.php?roll_no="+roll_no+"&profile="+profile+"&company="+company,function(data){
		if(data=='done'){
			alert('Saved');
		}
	});
}

function loadMore(index){
	offset = index;
	initAll();
}