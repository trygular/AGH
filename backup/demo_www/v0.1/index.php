<?php

//$con = mysql_connect($mysql_temperp_host,$mysql_temperp_user,$mysql_temperp_password);
//$db = mysql_select_db("DummyData", $con);

$con = mysql_connect("192.168.1.24", "root", "#47@BOM23##@");
mysql_select_db("DummyData", $con) or die(" No Database Selected" . mysql_error());

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Students</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<style>
	body { background-color: #b1adad; }
	.frm_control { width: 30%; }
</style>

<body>



<div id="main_container" class="container"> 



	<div class="container">
	    <hr /><h2>Insert Student Details</h2>
		<hr />
	   <!-- <form class="form-inline" method="post"> -->
		  <div class="form-group">
			<label for="sid" id="lsid">Total students:</label>
				<?php
					$sql1 = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'DummyData' AND TABLE_NAME = 'students'";
					$sql2 = "SELECT * FROM DummyData.students";
					$result = mysql_query($sql2, $con);
					$num_rows = mysql_num_rows($result);
				?>
			<input type="input" class="form-control frm_control" id="sid" name="sid" readonly value="<?php echo $num_rows; ?>" />
		  </div>
		  <div class="form-group">
			<label for="sname" id="lsname">Student Name:</label>
			<input type="input" class="form-control frm_control" id="sname" name="sname" />
		  </div>
		  
		  <div class="form-group">
			<label for="ssub1">Subject 1 Marks :</label>
			<input type="input" class="form-control frm_control" id="ssub1" name="ssub1" />
		  </div>
		   
		  <div class="form-group">
			<label for="ssub2">Subject 2 Marks :</label>
			<input type="input" class="form-control frm_control" id="ssub2" name="ssub2" />
		  </div>
		   
		  <div class="form-group">
			<label for="ssub3">Subject 3 Marks :</label>
			<input type="input" class="form-control frm_control" id="ssub3" name="ssub3" />
		  </div>
		   
		   <br />
		  
		  <button type="submit" name="form_insert" class="btn btn-info">Insert student</button>
		<!-- </form> -->
	</div>
 
	<div class="container">
	
<hr />
<h2>Search Student Details</h2>
<hr />
	<form name="frm_search" id="frm_search" method="POST" action="<?php $_PHP_SELF ?>">
		<div class="form-group">
			<label for="ssub2">Enter student id :</label>
			<input type="text" name="search" id="search" class="form-control" />
			<button type="submit" name="form_search" class="btn btn-success">SEARCH</button>
		  </div>
		
		
	</form>
	
	<table class="table">
    <thead>
      <tr><th>ID</th><th>Name</th><th>Marks subject 1</th><th>Marks subject 2</th><th>Marks subject 3</th></tr>
    </thead>
    <tbody>
      <tr>
	  <?php
		if(isset($_POST['form_search']))
		{
			$result = mysql_query("select * from DummyData.students WHERE sid=".$_POST['search'].";", $con);
			while ($row = mysql_fetch_array($result)) {
				echo "<td id='s1'>" . $row[0] . "</td><td id='s2'>" . $row[1] . "</td><td id='s3'>" . $row[2] . "</td><td id='s4'>" . $row[3] . "</td><td id='s5'>" . $row[4] . "</td>"; 
			}
		}
		?>
        
      </tr>
	</tbody>
	</table>
		
	</div>

<hr />
<hr />
 
	<div class="container">
			<h2>List Student Details</h2>
			<p></p>            
	  <table class="table table-striped">
		<thead>
		  <tr>
			<th>ID</th>
			<th>Name</th>
			<th>Subject 1</th>
			<th>Subject 2</th>
			<th>Subject 3</th>
			<th>Average</th>
			<th>DELETE</th>
		  </tr>
		</thead>
		
		<tbody>
<?php 
		$result = mysql_query("select * from DummyData.students;", $con);
		while ($row = mysql_fetch_array($result)) {
?>
		<tr>
			<td><?php echo $row[0]; ?></td>
			<td><?php echo $row[1]; ?></td>
			<td><?php echo $row[2]; ?></td>
			<td><?php echo $row[3]; ?></td>
			<td><?php echo $row[4]; ?></td>
			<td><?php echo (($row[2] + $row[3] + $row[4]) * 100 / (100 * 3)); ?></td>
			<td>
				<form name="frm_delete" id="frm_delete" method="POST" action="<?php $_PHP_SELF ?>">
					<button type="submit" id="<?php echo $row[0]; ?>" name="btn_delete" class="btn btn-danger">DELETE</button>
				</form>
			</td>
<?php
		}
?>
		</tr>
		</tbody>
	  </table>
	</div>

<hr />
<hr />

</div>



</body>

<script>

$(document).ready(function(){
	
	$("#sname").focus();
	
	//insert
	$('.btn-info').click(function()
	{
		$.ajax({
		  method: "POST",
		  url: "./controller.php",
		  data: { token: "insert", sname: $("#sname").val(), sid: $("#sid").val(), ssub1:$("#ssub1").val(), ssub2:$("#ssub2").val(), ssub3:$("#ssub3").val() }
		})
		.done(function(msg) {
			$("#sname").val("");
			$("#sid").val("");
			$("#ssub1").val("");
			$("#ssub2").val("");
			$("#ssub3").val("");
			alert( msg );
		});
	});
		
	//delete
    $(".btn-danger").click(function()
	{
		$.ajax({
		  method: "POST",
		  url: "./controller.php",
		  data: { token: "delete", sid: $(this).attr("id") }
		})
		.done(function(msg) {
			alert( msg );
		});
	});

	//search
	/*
	$('.btn-success').click(function()
	{
		$.ajax({
		  method: "POST",
		  url: "./controller.php",
		  data: { token: "success", sname: $("#sname").val(), sid: $("#sid").val(), ssub1:$("#ssub1").val(), ssub2:$("#ssub2").val(), ssub3:$("#ssub3").val() }
		})
		.done(function(msg) {
			alert( msg );
		});
	});
	*/
	
	
	$("#ssub3").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	   $(this).text("");
	   $(this).focus();
	   alert("Enter only digits!");
    }
	});
	
	$("#ssub2").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	   $(this).text("");
	   $(this).focus();
	   alert("Enter only digits!");
    }
	});
	
	$("#ssub1").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	   $(this).text("");
	   $(this).focus();
	   alert("Enter only digits!");	
    }
	});
	
});

</script>

</html>
