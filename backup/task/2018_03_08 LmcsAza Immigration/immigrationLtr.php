<?
include_once("db.php");
?>
<?

	/*
	if($_POST['Action'] == 'PDFGenerate')
	{
		$text = $_POST["text"];
		$text = str_replace('<_>', " ", $text);	// space
		$text = str_replace('<t>', "	", $text);
		$textArr = explode("<n>", $text); // array every New line to new index
		//$text = str_replace('_', " ", $text);
				
		
		include("fpdf/fpdf.php");

		$pdf = new FPDF();
		$pdf->AddPage();
		
		$pdf->Rect(5, 5, 200, 287, 'D');

		$yaxis = 60;		//top margin
		$xaxis = 10;		//left margin
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		
		$pdf->SetFont('Arial','',12);
		
		for($cntLine=0; $cntLine<sizeof($textArr); $cntLine++)
		{
			$yaxis=$yaxis+6;
			$xaxis=10;
			$pdf->setY($yaxis);
			$pdf->setX($xaxis);
			$pdf->Cell(50,6,$textArr[$cntLine],0,1,'L');
			
		} 
		$filenames = "immigrationLtr.pdf";
		$pdf->Output($filenames,'F');
	
		die();
	}
	*/
	if($_POST['Action'] == 'Generate')
	{
		
		$employeecode = $_POST['employeecode'];
		$outwardno = $_POST['outwardno'];
		//$date = $_POST['date'];
		$fromplace = $_POST['fromplace'];
		$toplace = $_POST['toplace'];
		$airno = $_POST['airno'];
		$flino = $_POST['flino'];
		$officer = $_POST["officer"];
	 	$agentaddress1 = $_POST["agentaddress1"];
		$agentaddress2 = $_POST["agentaddress2"];
		$agentaddress3 = $_POST["agentaddress3"];
		$agentaddress4 = $_POST["agentaddress4"];
		$agentmob = $_POST["agentmob"];
		$agentofficeno = $_POST["agentofficeno"];
		$agentfax = $_POST["agentfax"];
		$fromcode = $_POST['fromcode'];
		$tocode = $_POST['tocode'];
		$salutation = "He/She";
		
		include("fpdf/fpdf.php");
		
		$originalDate = $_POST['date'];
		$newDate = date("Y-m-d", strtotime($originalDate));
		
		$arrEmpIds = explode(",", $employeecode);
		
		for($empCount=0; $empCount<sizeof($arrEmpIds); $empCount++)
		{
			/* --- INSERT  */
			$qryin="insert into immigration (employeecode, date, fromplace, toplace, airno, flino, officer, outwardno, agentmob, agentofficeno, agentfax, agentaddress1, agentaddress2, agentaddress3, agentaddress4, fromcode, tocode) values('".mysql_real_escape_string($arrEmpIds[$empCount])."','".mysql_real_escape_string($newDate)."','".mysql_real_escape_string($fromplace)."','".mysql_real_escape_string($toplace)."','".mysql_real_escape_string($airno)."', '".$flino."', '".mysql_real_escape_string($officer)."','".mysql_real_escape_string($outwardno)."','".$agentmob."','".$agentofficeno."','".$agentfax."','".$agentaddress1."','".$agentaddress2."','".$agentaddress3."','".$agentaddress4."','".$fromcode."','".$tocode."')";
			exequery($qryin);
			
			if($empCount==1)
				$salutation = "They";
		}
		
		//echo $qryin;
		
		$pdf=new FPDF();
		$pdf->AddPage();
		
		$pdf->Rect(5, 5, 200, 287, 'D');

		$yaxis = 60;
		$xaxis = 10;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,'RPSL-MUM-387',0,1,'L');
		
		$yaxis=$yaxis+5;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'Ref No. '.$outwardno,0,1,'L');
		
		$xaxis=$xaxis+160;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,$originalDate,0,1,'L');
		
		$xaxis=$xaxis-110;
		$yaxis=$yaxis+15;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->SetFont('Arial','U',12);
		$pdf->Cell(0,0,'TO WHOMSOEVER IT MAY CONCERN',0,1,'L');
		
		$xaxis=$xaxis-50;
		$yaxis=$yaxis+10;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,'This is to certify that below mentioned personnels are bonafide Indian Nationals who will be joining',0,1,'L');
		
		$yaxis=$yaxis+5;
		$pdf->SetY($yaxis);
		$pdf->Cell(0,0,'the vessel '.$rowmvessel[0].' at Singapore.',0,1,'L');
		
		$yaxis=$yaxis+10;
		$pdf->setY($yaxis);
		$pdf->setX($xaxis);
		$pdf->Cell(50,6,'Name',1,1,'L');
		
		$xaxis=$xaxis+50;
		$pdf->setY($yaxis);
		$pdf->setX($xaxis);
		$pdf->Cell(50,6,'Rank',1,1,'L');
		
		$xaxis=$xaxis+50;
		$pdf->setY($yaxis);
		$pdf->setX($xaxis);
		$pdf->Cell(30,6,'Passport No.',1,1,'L');
		
		$xaxis=$xaxis+30;
		$pdf->setY($yaxis);
		$pdf->setX($xaxis);
		$pdf->Cell(30,6,'CDC No.',1,1,'L');
		
		$xaxis=$xaxis+30;
		$pdf->setY($yaxis);
		$pdf->setX($xaxis);
		$pdf->Cell(30,6,'Nationality',1,1,'L');
		
		
		for($empCount=0; $empCount<sizeof($arrEmpIds); $empCount++)
		{
			// in table start
			$sql ="SELECT * FROM EmployeeReg WHERE eid='".$arrEmpIds[$empCount]."'";
			$find = exequery($sql);
			$rowempreg = fetch($find);
			
			$sql1="SELECT vname FROM MVessel WHERE vid='".$rowempreg[12]."'";
			$find1 = exequery($sql1);
			$rowmvessel = fetch($find1);
			
			$sql2="SELECT name, surname, nationality FROM EmpMaster WHERE canid='".$rowempreg[1]."'";
			$find2 = exequery($sql2);
			$rowempmaster= fetch($find2);
			
			//name
			$yaxis=$yaxis+6;
			$xaxis=10;
			$pdf->setY($yaxis);
			$pdf->setX($xaxis);
			$pdf->Cell(50,6,$rowempmaster[0].' '.$rowempmaster[1],1,1,'L');
			
			
			$sql3="SELECT desig FROM Mdesignation WHERE did='".$rowempreg[5]."'";
			$find3 = exequery($sql3);
			$rowmdesignation = fetch($find3);
			
			$xaxis=$xaxis+50;
			$pdf->setY($yaxis);
			$pdf->setX($xaxis);
			$pdf->Cell(50,6,$rowmdesignation[0],1,1,'L');
			
			$sql4="SELECT pnumber FROM EmpPassport WHERE passport='3' AND canid='".$rowempreg[1]."'";
			$find4 = exequery($sql4);
			$rowemppassport = fetch($find4);
			
			$xaxis=$xaxis+50;
			$pdf->setY($yaxis);
			$pdf->setX($xaxis);
			$pdf->Cell(30,6,$rowemppassport[0],1,1,'L');
			
			$xaxis=$xaxis+30;
			$pdf->setY($yaxis);
			$pdf->setX($xaxis);
			$pdf->Cell(30,6,'',1,1,'L');
			
			$sql5="SELECT nationalname FROM MNationality WHERE nid='".$rowempmaster[2]."'";
			$find5 = exequery($sql5);
			$rownationality = fetch($find5);
			
			$xaxis=$xaxis+30;
			$pdf->setY($yaxis);
			$pdf->setX($xaxis);
			$pdf->Cell(30,6,$rownationality[0],1,1,'L');
			// in table end
		}
		
		$xaxis=10;
		$yaxis=$yaxis+10;
		$pdf->setY($yaxis);
		$pdf->setX($xaxis);
		$pdf->Cell(30,6,'They travel as follows: ',0,1,'L');
		
		$yaxis=$yaxis+10;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,$originalDate,0,1,'L');
		
		$xaxis=$xaxis+50;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,$fromplace.'/'.$toplace,0,1,'L');
		
		$xaxis=$xaxis+60;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,$fromcode.'/'.$tocode,0,1,'L');
		
		$xaxis=10;
		$yaxis=$yaxis+10;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'Airline Reference No: '.$airno,0,1,'L');
		
		$xaxis=10;
		$yaxis=$yaxis+5;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'Flight No: '.$flino,0,1,'L');
		
		$yaxis=$yaxis+10;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0, $salutation.' will be met by our below mentioned agents at the Singapore Airport who will clear him through',0,1,'L');
		
		$yaxis=$yaxis+5;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'Immigration and arrange to connect him to the vessel at '.$toplace.'.',0,1,'L');
		
		$yaxis=$yaxis+10;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,$agentaddress1,0,1);
		
		if($agentaddress2!=''){
			$yaxis=$yaxis+5;
			$pdf->SetY($yaxis);
			$pdf->SetX($xaxis);
			$pdf->Cell(0,0,$agentaddress2,0,1);
		}
		
		if($agentaddress3!=''){
			$yaxis=$yaxis+5;
			$pdf->SetY($yaxis);
			$pdf->SetX($xaxis);
			$pdf->Cell(0,0,$agentaddress3,0,1);
		}
		
		if($agentaddress4!=''){
			$yaxis=$yaxis+5;
			$pdf->SetY($yaxis);
			$pdf->SetX($xaxis);
			$pdf->Cell(0,0,$agentaddress4,0,1);
		}
		
		$yaxis=$yaxis+10;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'Mob: '.$agentmob,0,1,'L');
		
		$yaxis=$yaxis+5;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'Office tel: '.$agentofficeno,0,1,'L');
		
		$yaxis=$yaxis+5;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'Fax: '.$agentfax,0,1,'L');
		
		
		$yaxis=$yaxis+10;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'We confirm that if he fails to join the vessel, He will be sent to next port of call or repatriated to India',0,1,'L');
		
		$yaxis=$yaxis+5;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'at our  cost.',0,1,'L');
		
		$yaxis=$yaxis+5;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'Thanking you,',0,1,'L');
		
		$yaxis=$yaxis+10;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'Yours faithfully',0,1,'L');
		
		$yaxis=$yaxis+5;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(0,0,'For:AZA SHIPPING PRIVATE LIMITED',0,1,'L');
		
		$yaxis=$yaxis+15;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,$officer,0,1,'L');
		
		$yaxis=$yaxis+5;
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		$pdf->Cell(0,0,'OFFICER-Fleet Personnel',0,1,'L');
		
		$filenames = "immigrationLtr.pdf";
		$pdf->Output($filenames,'F');
	
		die();
	}
	
	if($_POST['Action'] == 'Find'){
		$agentaddress1 = $_POST["agentaddress1"];
		if($agentaddress1 != ''){
			$sql="SELECT * FROM immigration WHERE agentaddress1='".$agentaddress1."'";
			$find = exequery($sql);
			while($row = fetch($find)){
				$data= $row[0]."*".$row[1]."*".$row[2]."*".$row[3]."*".$row[4]."*".$row[5]."*".$row[6]."*".$row[7]."*".$row[8]."*".$row[9]."*".$row[10]."*".$row[11]."*".$row[12]."*".$row[13]."*".$row[14]."*".$row[15]."*".$row[16];
		
			}
			echo $data;
		}
		die();
	}
	
	if($_POST['Action'] == 'FindById'){
		$id = $_POST["id"];
		if($id != ''){
			$sql="SELECT * FROM MAgentDetails WHERE id='".$id."'";
			$find = exequery($sql);
			while($row = fetch($find)){
				$data= $row[0]."*".$row[1]."*".$row[2]."*".$row[3]."*".$row[4]."*".$row[5]."*".$row[6]."*".$row[7]."*";
		
			}
			echo $data;
		}
		die();
	}
?>
<?
include_once('header.php'); ?>
<script>
  $( function() {
    $( document ).tooltip();
  } );
  </script>
  <style>
label {
    display: inline-block;
    width: 5em;
  }
</style>
<body>
		
		<!--<link rel="stylesheet" type="text/css" href="assets-minified/widgets/datepicker/datepicker.css">
		<script type="text/javascript" src="assets-minified/widgets/datepicker/datepicker.js"></script>
		<script type="text/javascript">
			/* Datepicker bootstrap */
			$(function() {
				$('.bootstrap-datepicker').bsdatepicker({
					format: 'dd-mm-yyyy'
				});
			});
		</script>-->
		
		<link rel="stylesheet" type="text/css" href="assets-minified/widgets/datepicker-ui/datepicker.css">
		<script type="text/javascript" src="assets-minified/widgets/datepicker-ui/datepicker.js"></script>
		
		<script type="text/javascript">
			/* Datepicker bootstrap */
			$(function() {
					$('.bootstrap-datepicker').datepicker({
					format: 'dd-mm-yyyy'
				});
			});
		</script>
	
		<div id="page-content">
			<div class="page-box">
				<h3 class="page-title">Immigration letter</h3>
				
				<div class="example-box-wrapper" id='mainform'> 
					<form id="demo-form" class="form-horizontal" data-parsley-validate="" >
						
						<div class="row">
							<div class='col-md-12'>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Employee Code</label>
									<div class="col-sm-4">
										<select class="form-control" id="employeecode" name="employeecode" multiple data-toggle="tooltip" title="Select multiple employee's with Ctrl + Mouse Click" >
										<?
											$interqry = "SELECT eid,canid FROM EmployeeReg";
											$interres = exequery($interqry);
											while($interrow = fetch($interres)){
												$interqry1 = "SELECT name,middlename, surname FROM EmpMaster WHERE canid='".$interrow[1]."'";
												$interres1 = exequery($interqry1);
												while($interrow1 = fetch($interres1)){
													echo"<option value='".$interrow[0]."'>".$interrow[0]."-- ".$interrow1[0]." ".$interrow1[1]." ".$interrow1[2]."</option>";
												}
											}
										?>
										</select>
									</div>
									<div class="col-sm-5"></div>
									<div class="col-sm-1"><a class="btn btn-default" href="CustomPDF.php?fromurl=<?echo basename($_SERVER['PHP_SELF']);?>">Custom PDF</a></div>
									<!-- <div class="col-sm-4"></div> -->
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class='col-md-12'>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Date</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group demo-margin" style="margin-bottom: 0px;">
											<span class="add-on input-group-addon">
											<i class="glyph-icon icon-calendar"></i>
											</span>
											<input id='date' name='date' class="bootstrap-datepicker form-control" maxlength='10' style="width: 100px" onkeypress="return onlynum(event);" value="<? echo date('d-m-Y');?>" data-date-format="dd-mm-yyyy" type="text">
										</div>
									</div>
									<div class="col-sm-2"></div>
									<div class="col-sm-4"></div>
									
								</div>
							</div>
						</div>
						<div class="row">
							<div class='col-md-12'>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Reference No </label>
									<div class="col-sm-3">
										<select class="form-control" id="outwardno" name="outwardno" >
											<option value=''>Select</option>
											<?
												$interqry = "SELECT outwardno FROM OutwardRegister where departmentname='8' AND outwardstate='1'";
												$interres = exequery($interqry);
												while($interrow = fetch($interres)){
													echo"<option value='".$interrow[0]."'>".$interrow[0]."</option>";
												}
											?>
										</select>
									</div>
									<div class="col-sm-2"></div>
									<div class="col-sm-3"></div>
									
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class='col-md-12'>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Travel From
									</label>
									<div class="col-sm-3">
										<input class="form-control" id='fromplace' name='fromplace' value='' placeholder='Place' ></input>
									</div>
									<div class="col-sm-1">
										<input class="form-control" id='fromcode' name='fromcode' value='' placeholder='Code' ></input>
									</div>
									<label for="" class="col-sm-2 control-label">Travel To </label>
									<div class="col-sm-3">
										<input class="form-control" id='toplace' name='toplace' value='' placeholder='Place' ></input>
									</div>
									<div class="col-sm-1">
										<input class="form-control" id='tocode' name='tocode' value='' placeholder='Code' ></input>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class='col-md-12'>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Flight No. </label>
									<div class="col-sm-4">
										<input class="form-control" id='flino' name='flino' value='' placeholder='Flight No.' ></input>
									</div>
									<label for="" class="col-sm-2 control-label">Airline Ref No. </label>
									<div class="col-sm-4">
										<input class="form-control" id='airno' name='airno' value='' placeholder='Airlines Ref No.' ></input>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class='col-md-12'>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">
										Officer 
									</label>
									<div class="col-sm-4">
										<!--<input class="form-control" id='officer' name='officer' value='' ></input>-->
										<select class="form-control" id="officer" name="officer" >
											<option value=''>Select</option>
										<?
											$interqry = "SELECT name FROM MUser where usergroup='4'";
											$interres = exequery($interqry);
											while($interrow = fetch($interres)){
													echo"<option value='".$interrow[0]."'>".$interrow[0]."</option>";
											}
										?>
										</select>
									</div>
									<div class="col-sm-2"></div>
									<div class="col-sm-4"></div>
									
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class='col-md-12'>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">
										Agent Address
									</label>
									
									<div class="col-sm-4">
										<!-- <input class="form-control" id='agentaddress1' name='agentaddress1' value='' placeholder='address line 1'></input> -->
										<select class="form-control" id='agentaddress1' name='agentaddress1'><option value=''>Select</option>
										<?php
											$sql = "SELECT id, agentaddress1 FROM MAgentDetails ";
											$ressql = exequery($sql);
											while($row = fetch($ressql)){
													echo"<option value='".$row[0]."'>".$row[1]."</option>";
											}
										?>
										</select>
									</div>
									<div class="col-sm-1">
										<a href="MAgentDetails.php"><button class="btn btn-info" type="button">Add</button></a>
									</div>
									<label for="" class="col-sm-1 control-label">
										Line 2
									</label>
									<div class="col-sm-4"><input class="form-control" id='agentaddress2' name='agentaddress2' value='' placeholder='Address Line 2'></input></div>
									
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class='col-md-12'>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Address Line 3</label>
									<div class="col-sm-4">
										<input class="form-control" id='agentaddress3' name='agentaddress3' value='' placeholder='Address Line 3'>
									</div>
									<label for="" class="col-sm-2 control-label">Address Line 4
									</label>
									<div class="col-sm-4">
										<input class="form-control" id='agentaddress4' name='agentaddress4' value='' placeholder='Address Line 4'></input>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class='col-md-12'>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Agent Mobile No </label>
									<div class="col-sm-4">
										<input class="form-control" id='agentmob' name='agentmob' value='' placeholder="Mobile No." ></input>
									</div>
									<label for="" class="col-sm-2 control-label">Agent Office Tel No</label>
									<div class="col-sm-4"><input class="form-control" id='agentofficeno' name='agentofficeno' value='' placeholder="Office Tel No." ></input></div>
									
								</div>
							</div>
						</div>
						<div class="row">
							<div class='col-md-12'>
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Agent Fax </label>
									<div class="col-sm-4">
										<input class="form-control" id='agentfax' name='agentfax' value='' placeholder="Agent fax"></input>
									</div>
									<div class="col-sm-2"></div>
									<div class="col-sm-4"></div>
									
								</div>
							</div>
						</div>
						
						<div class='row'>
							</br></br>
							<label class="control-label col-sm-3"></label>
							<div class='col-md-6'>
								<button type="button" class="btn btn-primary" id='Generat' name='Generat' value='Generate' onclick='Generate();'>Generate</button>
								<button type="button" class="btn btn-primary" id='cancel' name='cancel' value='cancel' onclick="window.location = 'immigrationLtr.php'">Cancel</button>
							</div>
						</div>
						
					</form>
				</div>
				<div class="example-box-wrapper" id='loadgrid' style='display:none;'>
				
				</div>
				
			</div>
		</div>

		<!-- 
		<div onclick="PDFGenerate()">GENERATE CUSTOME PDF</div>
		<textarea id="areatext"></textarea>
		-->
	<? include_once('bottom.php'); ?>
</body>	 
<script type='text/javascript'>
$(document).ready(function(){
    $('#employeecode').tooltip(); 
});
function Generate()
{
	var employeecode = $('select[name=employeecode]').val();
	var date = $.trim($('#date').val());
	var outwardno = $.trim($('#outwardno').val());
	var fromplace = $.trim($('#fromplace').val());
	var toplace = $.trim($('#toplace').val());
	var fromcode = $('#fromcode').val();
	var tocode = $('#tocode').val();
	var airno = $.trim($('#airno').val());
	var flino = $.trim($('#flino').val());
	var officer = $.trim($('#officer').val());
	var agentaddress1 = $('#agentaddress1').val();
	var agentaddress1Text = $('#agentaddress1').find(":selected").text();
	var agentaddress2 = $('#agentaddress2').val();
	var agentaddress3 = $('#agentaddress3').val();
	var agentaddress4 = $('#agentaddress4').val();
	var agentmob = $('#agentmob').val();
	var agentofficeno = $('#agentofficeno').val();
	var agentfax = $('#agentfax').val();
	/* --- validate */
	if(employeecode == '' || employeecode == null){
		alert('Please Select Employee Code. \nSelect multiple employees with Ctrl + Left Mouse Click.!');
		$('select[name=employeecode]').focus();
		return false;
	}
	if(outwardno == ''){
		alert('Please Enter Reference Number');
		$('#outwardno').focus();
		return false;
	}
	if(fromplace == ''){
		alert('Please Enter From Place');
		$('#fromplace').focus();
		return false;
	}
	if(toplace == ''){
		alert('Please Enter To Place');
		$('#toplace').focus();
		return false;
	}
	if(fromcode == ''){
		alert('Please Enter From Code');
		$('#fromcode').focus();
		return false;
	}
	if(tocode == ''){
		alert('Please Enter To Code');
		$('#tocode').focus();
		return false;
	}
	if(airno == ''){
		alert('Please Enter Airline Ref No.');
		$('#airno').focus();
		return false;
	}
	if(flino == ''){
		alert('Please Enter Flight No.');
		$('#flino').focus();
		return false;
	}
	if(officer == ''){
		alert('Please Enter Officer');
		$('#officer').focus();
		return false;
	} 
	if(agentaddress1Text == 'Select'){
		alert('Please Select Or Add Agent Address Line 1');
		$('#agentaddress1').focus();
		return false;
	}
	if(agentmob == ''){
		alert('Please Enter Agent Mobile No');
		$('#agentmob').focus();
		return false;
	}
	if(agentofficeno == ''){
		alert('Please Enter Agent office No');
		$('#agentofficeno').focus();
		return false;
	}
	if(agentfax == ''){
		alert('Please Enter Agent Fax');
		$('#agentfax').focus();
		return false;
	}
	$.ajax({
		url:"immigrationLtr.php",
		data:"Action=Generate&employeecode="+employeecode+"&date="+date+"&outwardno="+outwardno+"&fromplace="+fromplace+"&toplace="+toplace+"&airno="+airno+"&flino="+flino+"&officer="+officer+"&agentmob="+agentmob+"&agentofficeno="+agentofficeno+"&agentfax="+agentfax+"&agentaddress1="+agentaddress1Text+"&agentaddress2="+agentaddress2+"&agentaddress3="+agentaddress3+"&agentaddress4="+agentaddress4+"&fromcode="+fromcode+"&tocode="+tocode,
		type:"post",
		success:function(output){
			var win = window.open('immigrationLtr.pdf', '_blank');
			if (win){
					win.focus();
			} else{
					alert('Please allow popups for this website');
			}
		}
	});
}
 
function PDFGenerate()
{
	var pdfText = $('#areatext').val(); //document.getElementById('txta').innerHTML;
	
	pdfText = pdfText.replace(' ', '<_>');
	pdfText = pdfText.replace(/\t/g, '<t>');
	pdfText = pdfText.replace(/\r?\n/g, '<n>');

	$.ajax({
		url:"immigrationLtr.php",
		data:"Action=PDFGenerate&text="+pdfText,
		type:"post",
		success:function(output){
			/* alert(output); return;*/
			var win = window.open('immigrationLtr.pdf', '_blank');
			if (win){
					win.focus();
			} else{
					alert('Please allow popups for this website');
			}
		}
	});	
}

</script>

 
<script type="text/javascript" src="js1/jquery.js"></script>		
<script type="text/javascript" src="js1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script>

<script type='text/javascript'>		
$(document).ready(function(){
	/*
	 $("#agentaddress1").autocomplete("autocompletesearch.php", {
		selectFirst: true
	});	*/
}); 
</script>

<script>

$('#agentaddress1').on('change', function() {
	var aid = $('#agentaddress1').val();
	//alert(aid);
	$.ajax({
		url:"immigrationLtr.php",
		data:"Action=FindById&id="+aid,
		type:"post",
		success:function(output){
			//alert(output);
			var store = output.split('*');	
					
			$('#agentaddress2').val(store[2]);
			$('#agentaddress3').val(store[3]);
			$('#agentaddress4').val(store[4]);	
			$('#agentmob').val(store[5]);
			$('#agentofficeno').val(store[6]);
			$('#agentfax').val(store[7]);
		}
	});	
});

</script>