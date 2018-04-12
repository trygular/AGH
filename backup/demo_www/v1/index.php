
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Students</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <script src="./js/jquery-3.3.1.min.js"></script>
  <script src="./js/bootstrap.min.js"></script> 
</head>

<style>
	body {  }
	.frm_control { width: 100%; }
	.ismall { width:50%; }
</style>

<body>

<div id="main_container" class="container"> 

	<div class="container">  
		<h2>Student Details</h2> 
		<button type="button" id="expdf"><span class="badge badge-pill badge-info">Export PDF</span></button>
		<button type="button" id="exexl"><span class="badge badge-pill badge-info">Export EXCEL</span></button>
		<p></p>
			
		<hr />
		<div class="row">
			<div class="col-sm-3"><select class="form-control frm_control" id="sel_id" name="sel_id"></select></div>
			<div class="col-sm-3"><button type="button" id="btnsearch" class="btn btn-info">SEARCH</button></div>
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
		</div>
		  
		<hr />
		<div class="row">
			<div class="col-sm-3">SID:<input type="text" class="form-control frm_control" id="sid" name="sid" readonly value="" /></div>
			<div class="col-sm-3">MID:<input type="text" class="form-control frm_control" id="mid" name="mid" readonly value="" /></div>
			<div class="col-sm-3">NAME:<input type="text" class="form-control frm_control" id="sname" name="sname" placeholder="Enter name" readonly /></div>
			<div class="col-sm-3"></div>
		</div>
		
		<div class="row">
			<div class="col-sm-3">Marks1:<input type="input" class="form-control frm_control" id="m1" name="m1" value="0" /></div>
			<div class="col-sm-3">Marks2:<input type="input" class="form-control frm_control" id="m2" name="m2" value="0" /></div>
			<div class="col-sm-3">Marks3:<input type="input" class="form-control frm_control" id="m3" name="m3" value="0" /></div>
			<div class="col-sm-3">Avg:<input type="input" class="form-control frm_control" id="avg" readonly value="" /></div>
		</div>
		
		<div class="row">
			<div class="col-sm-9"><button type="button" id="form_add" class="btn btn-info">ADD</button>
			
			<button type="button" id="form_reset" class="btn btn-info">RESET</button></td></div>
			<div class="col-sm-3"></div>
		</div>
		
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
		</div>
		
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
		</div>
		
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
		</div>
		
	</div>

<hr />

<div class="container"></div>
<table class="table table-striped table-dark" id="tab_add">
<tr><th>MID</th><th>SID</th><th>Name</th><th>Marks1</th><th>Marks2</th><th>Marks3</th><th>Average</th><th>Edit</th><th>Delete</th></tr>
<thead id="thead"></thead>
<tbody id="tbody"></tbody> 
</table>

<div class="form-group">
	<button type="button" id="form_insert" class="btn btn-success">SAVE</button><button type="button" id="form_update" class="btn btn-info">UPDATE</button> <button type="button" id="tabrow_clear" class="btn btn-success">CLEAR</button>
</div>
<div class="form-group"></div>
		  

<hr />

</div>

<div id="tet"></div>

</body>
<script>
$(document).ready(function(){

$(document).on('click','.btn.btn-danger', function(){
    //alert( $(this).attr("myval") );
	//console.log("you should see this");
	delete_row($(this).attr("myval"));
});

$(document).on('click','.btn.btn-default', function(){ 
	edit_row($(this).attr("myval"));
});
 
function delete_row(no)
{
 document.getElementById("row"+no+"").outerHTML="";
}

function edit_row(no)
{  

 var mid_val=document.getElementById("mid"+no).innerHTML;
 var sid_val=document.getElementById("sid"+no).innerHTML;
 var sn_val=document.getElementById("sn"+no).innerHTML;
 var ma_val=document.getElementById("ma"+no).innerHTML;
 var mb_val=document.getElementById("mb"+no).innerHTML;
 var mc_val=document.getElementById("mc"+no).innerHTML;
 var av_val=document.getElementById("avg"+no).innerHTML;
 
 document.getElementById("mid").value=mid_val;
 document.getElementById("sid").value=sid_val;
 document.getElementById("sname").value=sn_val;
 document.getElementById("m1").value=ma_val;
 document.getElementById("m2").value=mb_val;
 document.getElementById("m3").value=mc_val;
 document.getElementById("avg").value=av_val;

 /*alert(mid_val);
 
 document.getElementById("edit_button"+no).style.display="block";
 document.getElementById("save_button"+no).style.display="none";*/
 
 document.getElementById("row"+no+"").outerHTML="";
}

    $("#m1").val(0);
	$("#m2").val(0);
	$("#m3").val(0);
	$("#avg").val(0);
	$("#sname").focus();
	
	$("#form_update").hide();
	
	//get_allid
	function get_allid()
	{
		$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "get_allid" }
		})
		.done(function(msg) { 
		
			//alert(msg);
			$("#sel_id").html(msg);
		});
	}
	get_allid();
	
	//get_allname
	function get_allname()
	{
		$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "get_allname" }
		})
		.done(function(msg) { 
		
			//alert(msg);
			$("#sel_name").html(msg);
		});
	}
	get_allname();
	
	//next id
	function next_mid()
	{
		$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "next_mid" }
		})
		.done(function(msg) { 
			$("#mid").val(msg);
		});
	}
	next_mid();

	//next id
	function next_sid()
	{
		$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "next_sid" }
		})
		.done(function(msg) { 
			$("#sid").val(msg);
		});
	}
	next_sid();
	
	//$("tr").each()
	
	$("#sel_name").change(function(){
		if( $(this).val() == "SELECT NAME" )
		{
			$("#sname").removeAttr("readonly").val('').focus();
		} else {
			$("#sname").val($(this).val()); $("#sname").attr("readonly","").focus();
		}
	});
	
	//reset
	$("#form_reset").click(function(){
	
		get_allid();
		next_mid();
		$("#sname").val("");
		$("#m1").val(0);
		$("#m2").val(0);
		$("#m3").val(0);
		$("#avg").val(0);
		$("#sname").focus();	
		
		$("#sel_id").val("SELECT ID");
		$("#sel_name").val("SELECT NAME"); 
		$("#tabrow_clear").click();
	});
	
	
	//insert
	$('#form_insert').click(function()
	{ 
	
		if($("#sname").val() !== "")
		{
		$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "insertsname", n: $("#sname").val() }
		}).done(function(msg) {
			var newsid = parseInt(msg);
			//alert("naM:" + newsid);
			if(newsid != 0)
			{
	$.ajax({
	  method: "POST",
	  url: "./studc.php",
	  data: { token: "insertjson", sid:newsid, sname: $("#sname").val(), tabdata: jtc() }
	}).done(function(msg) {
		alert( msg );
		setTimeout(location.reload.bind(location), 2000);
	});
			} else {
				alert("Student doesnot exists!");
			}
		}); 
		
	}
		/*alert(jtc()); */
	});
	
	//update
	$("#form_update").click(function(){
		/*$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "update", sname: $("#sname").val(), sid: $("#sid").val(), m1:$("#m1").val(), m2:$("#m2").val(), m3:$("#m3").val() }
		})
		.done(function(msg) {
			$("#form_reset").click();
			nextid();
			alert( msg + ' Reloading in 5 seconds..' );
			setTimeout(location.reload.bind(location), 3000);
		});*/
		
		$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "cleardata", si:$("#sid").val() }
		}).done(function(msg) {
			var newsid = parseInt($("#sid").val());
			//alert(newsid);
			if(newsid != 0)
			{
				$.ajax({
				  method: "POST",
				  url: "./studc.php",
				  data: { token: "insertjson", sid:newsid, sname: $("#sname").val(), tabdata: jtc() }
				}).done(function(msg) {
					alert( msg );
				});
			} else {
				alert("Previous data was not cleared!");
			}
		}); 
		
	});
	
	//--- - search
	$('#sel_id').change(function()
	{ 
		if( $(this).val() == "SELECT ID" ) {
			
		}
		else if( $(this).val() == "NEW STUDENT" ) {
			$("#form_update").hide();
			$("#form_insert").show();
			
			$("#sname").val("");
			$("#sid").val("");
			
			next_sid();
			
			$("#sname").removeAttr("readonly").focus();
			
			$("#tabrow_clear").click();
		}
		else if( $(this).val() != "SELECT ID" && $(this).val() != "NEW STUDENT" )
		{
			$("#form_update").show();
			$("#form_insert").hide();
			
			$.ajax({
			  method: "POST",
			  url: "./studc.php",
			  data: { token: "get_onename", sid: $(this).val() },
			  success: function(msg) {
			  	$("#sid").val(msg[0].ID);
				$("#sname").val(msg[0].SNAME);
				}, dataType: 'json'
			});
		
			$.ajax({
			  method: "POST",
			  url: "./studc.php",
			  data: { token: "get_alldata", sid: $(this).val() }
			})
			.done(function(msg) {
				$("#tbody").html(msg);
				 
				$("#sname").attr("readonly",true);
				$("#m1").focus();
			});
		} 
		else
		{
			$("#form_reset").click();
		}
	});

	//btn_edit
	$(".btn-info").click(function(){
		
		$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "search", sid: $(this).attr("vl") }
		})
		.done(function(msg) {
			
			var JSONObject = JSON.parse(msg);
			
			$("#sid").val(JSONObject[0]["id"]);
			$("#sname").val(JSONObject[0]["name"]);
			$("#m1").val(JSONObject[0]["s1"]);
			$("#m2").val(JSONObject[0]["s2"]);
			$("#m3").val(JSONObject[0]["s3"]);
			 
			$("#sname").focus();
		});
		
	});
	
	//delete
    $(".btn-danger").click(function()
	{
		$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "delete", sid: $(this).attr("vl") }
		})
		.done(function(msg) {
			alert( msg + ' Reloading in 5 seconds..' );
			setTimeout(location.reload.bind(location), 3000);
		});
	});
	
	//table row add
	$("#form_add").click(function(){
		$('#tab_add tr:last').after('<tr><td>'+$("#mid").val()+'</td><td>'+$("#sid").val()+'</td><td>'+$("#sname").val()+'</td><td>'+$("#m1").val()+'</td><td>'+$("#m2").val()+'</td><td>'+$("#m3").val()+'</td><td>'+$("#avg").val()+'</td><td><button class="btn btn-default">Edit</button></td><td><button class="btn btn-danger">Delete</button></td></tr>');
 
		$("#mid").val( parseInt($("#mid").val()) + 1 ); 
		//$("#sname").removeAttr("readonly").focus();
		$("#sname").attr("readonly", true);
		$("#m1").val("").focus();$("#m2").val("");$("#m3").val("");
	});
	
	//table row clear
	$("#tabrow_clear").click(function(){
		$("#tab_add").find("tr:gt(0)").remove();
	});
	
	/*
	$("#expdf").click(function(){
				
        var shelf_clone = $('#stab').clone();
        var shelf = shelf_clone.prop('outerHTML'); 

		$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "expdf", htm: shelf }
		})
		.done(function(msg) {
			alert( "done" );
		});
	});
	*/
	
	//calculate average
	function avg()
	{
		$.ajax({
		  method: "POST",
		  url: "./studc.php",
		  data: { token: "cal_avg", s1:$("#m1").val(), s2:$("#m2").val(), s3:$("#m3").val() }
		})
		.done(function(msg) {
			$("#avg").val(msg);
		});
	}
	
	/* validation */
	$("#m3").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	   alert("Enter only digits!");
	   $(this).val(0);
	   $(this).focus();
    } 
	}).focus(function(){ avg(); });
	
	$("#m2").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	   alert("Enter only digits!");
	   $(this).text(0);
	   $(this).focus();
    }
	}).focus(function(){ avg(); });
	
	$("#m1").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	   alert("Enter only digits!");	
	   $(this).text(0);
	   $(this).focus();
    }
	}).focus(function(){ avg(); });
	
	$("#avg").focus(function(){ avg(); });
	
	
	
	
	
function jtc()
{
	var myRows = [];
	var $headers = $("th");
	var $rows = $("tbody tr").each(function(index) {
	  $cells = $(this).find("td");
	  myRows[index] = {};
	  $cells.each(function(cellIndex) {
		myRows[index][$($headers[cellIndex]).text()] = $(this).text();
	  });    
	});
	var myObj = {};
	//myObj.sid = { id: $("#sid").val(), name: $("#sname").val() };
	myObj.myrows = myRows;
	return (JSON.stringify(myObj));
}

});

</script>

</html>
