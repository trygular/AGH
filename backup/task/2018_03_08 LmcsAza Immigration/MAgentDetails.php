<?

	include_once("db.php");
	
	if($_POST['Action'] == 'Add' || $_POST['Action'] == 'Update')
	{
		$agentid = $_POST["agentid"];
	 	$agentaddress1 = $_POST["agentaddress1"];
		$agentaddress2 = $_POST["agentaddress2"];
		$agentaddress3 = $_POST["agentaddress3"];
		$agentaddress4 = $_POST["agentaddress4"];
		$agentmob = $_POST["agentmob"];
		$agentofficeno = $_POST["agentofficeno"];
		$agentfax = $_POST["agentfax"];
		
		
		if($_POST['Action'] == 'Update')
		{
			$sqlDELETE = "DELETE FROM MAgentDetails WHERE id='".$agentid."' ";
			$resDELETE = exequery($sqlDELETE);
		}

		$sqlInsert = "INSERT INTO MAgentDetails (agentaddress1,agentaddress2,agentaddress3,agentaddress4,agentmob,agentofficeno,agentfax) VALUES ('".$agentaddress1."','".$agentaddress2."','".$agentaddress3."','".$agentaddress4."','".$agentmob."','".$agentofficeno."','".$agentfax."');";
		$resInsert = exequery($sqlInsert);
		
		echo $_POST['Action'];
		
		die();
	}
	
	if($_POST['Action'] == 'Delete')
	{
		$agentid = $_POST["agentid"];
			
		$sqlDELETE = "DELETE FROM MAgentDetails WHERE id='".$agentid."' ";
		$resDELETE = exequery($sqlDELETE);
		
		if($resDELETE)
			echo "Deleted Successfully ..!";
		else
			echo "Agent Id ".$agentid." is not deleted...!";
		
		die();
	}
	
	if($_POST['Action'] == "loadgrid1")
	{
		?>	
		<link rel="stylesheet" type="text/css" href="assets-minified/widgets/datatable/datatable.css">
		<script type="text/javascript" src="assets-minified/widgets/datatable/datatable.js"></script>
		<script type="text/javascript" src="assets-minified/widgets/datatable/datatable-bootstrap.js"></script>
		
		<script type="text/javascript">
			/* Datatables init */
			$(document).ready(function() {
				$('#dynamic-table-example-1').dataTable();

				/* Add sorting icons */

				$("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
				$("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
				$("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');

			});
		</script>
		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="dynamic-table-example-1">
			<thead>
				<tr><th colspan="8" style='text-align:right'><input class='btn btn-primary' type='button' value='Back' name='Action' onclick='window.location.reload();' style='width:50px;'></th></tr>
				<tr>
					<th class='sorting_desc'>Id</th>
					<th>agentaddress1</th><th>agentaddress2</th>
					<th>agentaddress3</th><th>agentaddress4</th>
					<th>agentmob</th><th>agentofficeno</th><th>agentfax</th>
				</tr>
			</thead>
			<tbody>
				<?
					$qry = "SELECT * FROM MAgentDetails ";
					$res1 = exequery($qry);
					while($rows = fetch($res1))
					{
						//agentaddress1, agentaddress2, agentaddress3, agentaddress4, agentmob, agentofficeno, agentfax
						echo "<tr class=''><td>".$rows[0]."</td>";
						echo "<td>".$rows[1]."</td>";
						echo "<td>".$rows[2]."</td>";
						echo "<td>".$rows[3]."</td>";
						echo "<td>".$rows[4]."</td>";
						echo "<td>".$rows[5]."</td>";
						echo "<td>".$rows[6]."</td>";
						echo "<td>".$rows[7]."</td>";
						echo "</tr>";
					}	
				?>
			</tbody>
		</table> 

		<?
	die();
	}
	
	if($_POST['Action'] == 'Find'){
		$agentid = $_POST["agentid"];
		
		if($agentid != '')
		{
			$sql = "SELECT * FROM MAgentDetails WHERE id='".$agentid."'";
			$find = exequery($sql);
			while($row = fetch($find))
			{
//agentaddress1, agentaddress2, agentaddress3, agentaddress4, agentmob, agentofficeno, agentfax
				$data = $row[0]."*".$row[1]."*".$row[2]."*".$row[3]."*".$row[4]."*".$row[5]."*".$row[6]."*".$row[7]."*";
			}
			echo $data;
		}
		die();
	}
	
	if($_POST['Action'] == 'getMaxId'){
		$sql = "SELECT (max(id) + 1) FROM MAgentDetails ";
		$find = exequery($sql);
		$row = fetch($find);
		
		echo ($row[0] == '') ? 1 : $row[0];
		die();
	}

	include_once('header.php'); ?>

<body>
	<div id="page-content">
		<div class="page-box">
			<h3 class="page-title">Agent Master</h3>
			
			<div class="example-box-wrapper" id='mainform'> 
				<form id="demo-form" class="form-horizontal" data-parsley-validate="" >
					
					<div class="row">
						<div class='col-md-12'>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">
									Agent Id
								</label>
								<div class="col-sm-2">
									<input class="form-control" id='agentid' name='agentid' value='' placeholder='ID' />
								</div>
								
								<button class="btn btn-info" type="button" id='LookUpbutton' name='LookUpbutton' value='LookUp' onclick='LookUp();'><i class="glyph-icon demo-icon tooltip-button icon-search" style='margin: -4px; border:0px;'></i></button>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class='col-md-12'>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">
									Agent Address Line 1 
								</label>
								<div class="col-sm-4">
									<input class="form-control" id='agentaddress1' name='agentaddress1' value='' placeholder='address line 1'></input>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class='col-md-12'>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">
								</label> 
								<div class="col-sm-4">
									<input class="form-control" id='agentaddress2' name='agentaddress2' value='' placeholder='address line 2'></input>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class='col-md-12'>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">
								</label>
								<div class="col-sm-4">
									<input class="form-control" id='agentaddress3' name='agentaddress3' value='' placeholder='address line 3'></input>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class='col-md-12'>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">
								</label>
								<div class="col-sm-4">
									<input class="form-control" id='agentaddress4' name='agentaddress4' value='' placeholder='address line 4'></input>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class='col-md-12'>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">
									Agent Mobile No 
								</label>
								<div class="col-sm-4">
									<input class="form-control" id='agentmob' name='agentmob' value='' placeholder='mobile no.' maxlength='10'></input>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class='col-md-12'>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">
									Agent Office Tel No 
								</label>
								<div class="col-sm-4">
									<input class="form-control" id='agentofficeno' name='agentofficeno' value='' placeholder='office telephone no.' maxlength='10' ></input>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class='col-md-12'>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">
									Agent Fax 
								</label>
								<div class="col-sm-4">
									<input class="form-control" id='agentfax' name='agentfax' value='' placeholder='fax' maxlength='10' ></input>
								</div>
							</div>
						</div>
					</div>
					
					<div class='row'>
						</br></br>
						<label class="control-label col-sm-3"></label>
						<div class='col-md-6'>
							<input type="button" class="btn btn-primary" id='btnAddUpdate' name='btnAddUpdate' value='Add' onclick='AddUpdate();' />
							<input type="button" class="btn btn-primary" id='btnDelete' name='btnDelete' value='Delete' onclick='Delete();' style='display:none;' />
							<button type="button" class="btn btn-primary" id='cancel' name='cancel' value='cancel' onclick="window.location = 'MAgentDetails.php'">Cancel</button>
						</div>
					</div>
					
				</form>
			</div>
			
			<div class="example-box-wrapper" id='loadgrid' style='display:none;'></div>
		</div>
	</div>

<? include_once('bottom.php'); ?>
</body>	
 
<script type='text/javascript'>	

//grid
function LookUp(){
	$("#loadgrid").show();
	$.ajax({
		url:"MAgentDetails.php",
		data:"Action=loadgrid1",
		type:"POST",
		success:function(output)
		{
			/*	alert(output);*/
			$("#mainform").hide();
			$('#loadgrid').html($.trim(output));
			return false;
		}
	});
}

//update
$('#agentid').on('blur', function(){
	var idval = $(this).val().trim();
	if( idval != '')
	{
		$('#btnAddUpdate').val("Add");
		
		$.ajax({
			url:"MAgentDetails.php",
			data:"Action=Find&agentid="+idval,
			type:"post",
			success:function(output){
				var store = output.split('*');
				if(store.length > 1)
				{	
					$(this).val(store[0]);
					$('#agentaddress1').val(store[1]);
					$('#agentaddress2').val(store[2]);
					$('#agentaddress3').val(store[3]);
					$('#agentaddress4').val(store[4]);	
					$('#agentmob').val(store[5]);
					$('#agentofficeno').val(store[6]);
					$('#agentfax').val(store[7]);
					$('#btnAddUpdate').val("Update");
					$('#btnDelete').show();
					//getMaxId();
				} else {
				
					//clear all text inputs
					$('#agentaddress1').val('');
					$('#agentaddress2').val('');
					$('#agentaddress3').val('');
					$('#agentaddress4').val('');	
					$('#agentmob').val('');
					$('#agentofficeno').val('');
					$('#agentfax').val('');
					$('#btnAddUpdate').val("Add");
					$('#btnDelete').hide();
				}
				//$('#agentid').val(output);
			}
		});
	} else {
		alert('Agent ID cannot be blank..!');
		$('#btnAddUpdate').val("Add");
		return;
	}
});

function getMaxId()
{
	$.ajax({
		url:"MAgentDetails.php",
		data:"Action=getMaxId",
		type:"post",
		success:function(output){
			$('#agentid').val(output);
		}
	});
}

function Delete()
{
	var agentid = $('#agentid').val();
	$.ajax({
		url:"MAgentDetails.php",
		data: "Action=Delete&agentid="+agentid,
		type:"post",
		success:function(output) {
			alert(output);
			
			//clear all text inputs
			$('#agentaddress1').val("");
			$('#agentaddress2').val("");
			$('#agentaddress3').val("");
			$('#agentaddress4').val("");
			$('#agentmob').val("");
			$('#agentofficeno').val("");
			$('#agentfax').val("");
			$('#btnAddUpdate').val("Add");
			$('#btnDelete').hide();
			getMaxId();
		}
	});
}

function AddUpdate()
{
	var valAddUpdate = $('#btnAddUpdate').val();
	var agentid = $('#agentid').val();

	var agentaddress1 = $('#agentaddress1').val();
	var agentaddress2 = $('#agentaddress2').val();
	var agentaddress3 = $('#agentaddress3').val();
	var agentaddress4 = $('#agentaddress4').val();
	var agentmob = $('#agentmob').val();
	var agentofficeno = $('#agentofficeno').val();
	var agentfax = $('#agentfax').val();
		
	if(agentid == '')
		window.location = 'MAgentDetails.php';
	
	if(agentaddress4 == '' && agentaddress3 == '' && agentaddress2 == '')
	{
		if(agentaddress1 == '')
		{
			alert('Please fill up atleast address line 1..!');
			$('#agentaddress1').val("").focus();
			return;
		}
	}
	
	if(agentmob != '')
	{
		if(agentmob.length <= 9 || agentmob.length >= 11)
		{
			alert('Mobile no. must be 10 digit..!');
			$('#agentmob').val().focus();
			return;
		}
        var filter = /^\d*(?:\.\d{1,2})?$/;
		if(filter.test(agentmob) == false)
		{
			alert('Mobile no. invalid..!');
			$('#agentmob').val().focus();
			return;
		}
	}

	if(agentofficeno != '')
	{
		if(agentmob.length <= 9 || agentmob.length >= 11)
		{
			alert('Office no. must be 10 digit..!');
			$('#agentofficeno').val().focus();
			return;
		}
        var filter = /^\d*(?:\.\d{1,2})?$/;
		if(filter.test(agentofficeno) == false)
		{
			alert('Office no. invalid..!');
			$('#agentofficeno').val().focus();
			return;
		}
	}

	
	var data_request = "Action="+valAddUpdate+"&agentid="+agentid+"&agentaddress1="+agentaddress1+"&agentaddress2="+agentaddress2+"&agentaddress3="+agentaddress3+"&agentaddress4="+agentaddress4+"&agentmob="+agentmob+"&agentofficeno="+agentofficeno+"&agentfax="+agentfax;
	
	$.ajax({
		url:"MAgentDetails.php",
		data: data_request,
		type:"post",
		success:function(output) {
			alert('Agent details ' + valAddUpdate + ' successfull...! ');
			
			//clear all text inputs
			$('#agentaddress1').val("");
			$('#agentaddress2').val("");
			$('#agentaddress3').val("");
			$('#agentaddress4').val("");
			$('#agentmob').val("");
			$('#agentofficeno').val("");
			$('#agentfax').val("");
			getMaxId();
		}
	});
}
</script>

<script type="text/javascript" src="js1/jquery.js"></script>		
<script type="text/javascript" src="js1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script> 
<script type="text/javascript" src="js1/angular.min.js"></script>
<script type='text/javascript'>	

$(document).ready(function(){
	/* $("#agentaddress1").autocomplete("autocompletesearch.php", { selectFirst: true });	*/
	getMaxId();
	$('#agentid').focus();

	<?php
	// take id and punt into id text then trigger on blur
	/*
	if(isset($_GET['id']) && $_GET['id'] != "")
	{
		echo "$('#agentid').val(".$_GET['id'].").blur(); ";
	}
	*/
	?>
	
}); 	// end docu ready

/*
$('#agentaddress1').on('blur', function() {

	var agentaddress1 = $('#agentaddress1').val();
		
	$.ajax({
			url:"MAgentDetails.php",
			data:"Action=Find&agentaddress1="+agentaddress1,
			type:"post",
			success:function(output){
				//alert(output);
				var store = output.split('*');	
						
				$('#agentaddress1').val(store[11]);
				$('#agentaddress2').val(store[12]);
				$('#agentaddress3').val(store[13]);
				$('#agentaddress4').val(store[14]);	
				$('#agentmob').val(store[8]);
				$('#agentofficeno').val(store[9]);
				$('#agentfax').val(store[10]);
				 
				//alert($('#agentaddress1'));
			//	alert($('#agentmob'));
			}
	});	
});
*/
</script>