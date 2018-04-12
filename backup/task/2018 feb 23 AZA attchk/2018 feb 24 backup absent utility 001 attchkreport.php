
<?php
	include('../db.php');

	
function insertIntoAbsent($date,$absenttype,$empcode)
{
	$StaffMasterSql = "INSERT INTO attendanceAbsent(date,absenttype,empcode) VALUES ('".$date."','".$absenttype."','".$empcode."')";
	$StaffMasterRes = exequery($StaffMasterSql);
}
	
if($_POST['action'] == "Generate") 
{
	if(isset($_POST["month"]) && isset($_POST["year"]))
	{
	
		$sqlTruncate = "TRUNCATE Table attendanceAbsent";
		exequery($sqlTruncate);
	
		$Month = $_POST["month"];
		$Year = $_POST["year"];
		
		//echo date('t',strtotime('2018-02-01')); 28
		//echo date('t',strtotime('2018-01-01')); 31
		//die();
		
		if($Month<10)
			$Month = '0'.$Month;
			
		$from = $Year."-".$Month."-01";
		$lastDay = date('t',strtotime($from));
		$to = $Year."-".$Month."-".$lastDayArr[2];
		
		//echo $lastDayArr.$Month.$Year;
		
		$dateFromArr = explode("-", $from);
		$dateToArr = explode("-", $to);
		
		$StaffMasterSql = "select * from StaffMaster where lefts!=1 ";
		$StaffMasterRes = exequery($StaffMasterSql);
		
		while($StaffMasterRow  = fetch($StaffMasterRes))
		{
			$empcode = $StaffMasterRow[0];
			$absenttype = "";
			
			//loop for all days
			for($day=1; $day<=$lastDay; $day++)
			{
				$dateNow = $day."-".$Month."-".$Year;
				$dayNow = date('N', strtotime($dateNow));
				
				
				
				// chk if day is wo
				if($dayNow >= 6)
				{
					insertIntoAbsent(DMYtoYMD($dateNow), "WO", $empcode);
				} else if($dayNow < 6)
				{
					$flagIn = 0;
					if($flagIn == 0)
					{
						$sqlHL =  "select hdate from HolidayMaster WHERE hdate = '".DMYtoYMD($dateNow)."'";
						$resHL = exequery($sqlHL);
						while($rowsHL = fetch($resHL))
						{
							if($rowsHL[0] != NULL)
							{
								//echo $rowsHL[0]." =HL = ".$empcode."<br/>";
								insertIntoAbsent($rowsHL[0], "HL", $empcode);
								$flagIn = 1;
							}
						}
					}

					if($flagIn == 0)
					{
						$sqlLeave = "SELECT * FROM LeaveTransaction WHERE empcode='".$empcode."' AND '".DMYtoYMD($dateNow)."' BETWEEN frmdate AND todate ";
						//echo $sqlLeave;
						$resLeave = exequery($sqlLeave);
						while($rowsLeave = fetch($resLeave))
						{
							insertIntoAbsent(DMYtoYMD($dateNow), $rowsLeave[4], $empcode);
							$flagIn = 1;
						}
					}
					
					if($flagIn == 0)
					{
						//echo "'". DMYtoYMD($dateNow)."' OR ";
						$sqlAbsent = "SELECT * FROM Attendance WHERE empcode='".$empcode."' AND date='".DMYtoYMD($dateNow)."'";
						$resAbsent = exequery($sqlAbsent);
						$rowsAbsent = fetch($resAbsent);
						
						//echo "<br>". DMYtoYMD($dateNow);
						if($rowsAbsent != NULL)
						{ 
							//echo $empcode."-".DMYtoYMD($dateNow)." PP";
							insertIntoAbsent(DMYtoYMD($dateNow), "P", $empcode);
						} else 
						{
							//absent
							//echo $empcode."-".DMYtoYMD($dateNow)." AA";
							insertIntoAbsent(DMYtoYMD($dateNow), "A", $empcode);
						}
						
					echo "<br>";
						//echo "<br> NA - ". $empcode ."|". DMYtoYMD($dateNow)."|".$sqlAbsent;
					}
					
				}
			}
		}
		
	}
	else
	{
		echo "<script>alert(Yaer Or Month Not Selected...!);</script>";
	}
	die();
}
?>
<? include('../header.php');?>
	<title>Daily Attendance Correction</title>

	<link href="../css/jquery-ui.css" rel="stylesheet">
	<script src="../js/jquery-1.10.2.js"></script>
	<script src="../js/jquery-ui.js"></script>
	
	<script src="../jquery.maskedinput.js" type="text/javascript"></script>

		<style type="text/css">

			.tooltip1 {
				display:none;
				position:absolute;
				border:1px solid #333;
				background-color:#161616;
				border-radius:5px;
				padding:10px;
				color:#fff;
				font-size:12px Arial;
			}

		</style>

	<body>
<div id="page-content">
	<div id="wrap">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							  <h4>Advance Monthly Report</h4>
							  
						</div>
						<div class="panel-body" >
                            <div class="">
								<form action="AttchkReportUtility.php" method="post" class="form-horizontal ">
									<table class="table">
										<tr>
											<!--
											<td>From Date : </td>
											<td>
												  <input type="text" name="fromdate" id='fromdate' value='' placeholder='From Date' />
											</td>
											
											<td>To Date : </td>
											<td>
												  <input type="text" name="todate" id='todate' value='' placeholder='To Date' />
											</td>
											-->
											<td>Year</td>
											<td>
												  <select name='year' id='year' class='form-control' style='width:80px'>
													<?
													
														echo "<option value='".date('Y')."'>".date('Y')."</option>";
														$Year = date('Y') - 3;
														for($y=$Year;$y<=date('Y'); $y++){
															echo "<option value=".$y.">".$y."</option>";
														}
													?>
											
												  </select>
											</td>
											
											
											<td>Month</td>
											<td>
												  <select name='month' id='month' class='form-control' style='width:100px'>
													<?
														for ($m=1; $m<=12; $m++) {
															$month = date('F', mktime(0,0,0,$m, 1, date('Y')));
															echo "<option value=".$m.">".$month."</option>";
														}
													?>
											
												  </select>
											</td>
											
											<td style='width:150px;'><input class="btn btn-info" type='submit' name="action" id="action" value='Generate' /></td>
										</tr>
									</table>
							  
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
<?php include "../footer.php" ?>

<script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
<script src="../js/bootstrap.datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="../jqauto/jquery.autocomplete.css" />
<script type="text/javascript" src="../jqauto/jquery.autocomplete.js"></script>
<script>
	
$(function() {
	$('#fromdate').datepicker({dateFormat: 'dd-mm-yy'});
	$('#todate').datepicker({dateFormat: 'dd-mm-yy'});
});
</script>