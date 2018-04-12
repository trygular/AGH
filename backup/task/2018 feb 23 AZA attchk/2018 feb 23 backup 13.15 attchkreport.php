<?php
include('../db.php');

/*
	PHP Name : attchkreport.php
	Created Date : 24-02-2018
	Created By : Abhijeeet H
	Description : This is Attendance Report
*/	
 
include('../header.php');
?>
<title>Attendance Report</title>
	<link href="../css/jquery-ui.css" rel="stylesheet">
	<script src="../js/jquery-1.10.2.js"></script>
	<script src="../js/jquery-ui.js"></script>

	<script>
	$(function() {
		$("#tabs").tabs();
	});
		
	function toempcode1() {
		//alert('cmg');
		empcode = $('#fromempcode').val();
		$('#toempcode').val(empcode);
	}

	function todepartment1() {
		//alert('cmg');
		fromdepartment = $('#fromdepartment').val();
		$('#todepartment').val(fromdepartment);
	}
		
	function tolocation1() {
		//alert('cmg');
		fromlocation = $('#fromlocation').val();
		$('#tolocation').val(fromlocation);
	}
	</script>
	
	<style type="text/css">
table.table-bordered{
    border:0.7px solid black;
    margin-top:20px;
  }
table.table-bordered > thead > tr > th{
    border:0.7px solid black;
}
table.table-bordered > tbody > tr > td{
    border:0.7px solid black;
}
	</style>
	
<?
 
if($_POST['action']=="Generate") 
{
	echo "<br><a href='attchkreport.php'><input class='btn btn-primary' type='button'  value='Back' style='font-color:white;font-weight:bold;font-size:16px;float:left'/></a>";	
	echo '<form action="attchkreportexcel.php" method="POST"><br><span style="float:right;">';
	echo '<input class="btn btn-primary" style="font-weight:bold" type="submit" name="Action" id="Action" value="Generate as Excel">&nbsp;&nbsp;';
	echo '<input class="btn btn-primary" style="font-weight:bold" type="submit" name="Action" id="Action" value="Generate as PDF"></span>';

//------------------------- PERFORMANCE
if($_POST['misreport']=='performance') 
{

	$time = mktime(0, 0, 0, $_POST['month']);
	$name = strftime("%b", $time);
	
	echo "<center><p style='font-color:white;font-weight:bold;font-size:16px;'>Performance List For the Month ".$name." And Year ".$_POST['year']."</p></center><br>";
	$start = microtime(true);		

	$my = $_POST['year'].'-'.$_POST['month'];
	$month = $_POST['year']."-".$_POST['month']."-01";
	$lastday = date('t',strtotime($month));
	$fromempcode1 = explode(':',$_POST['fromempcode']);
	$fromempcode  = $fromempcode1[0];
	$fromempcode1 = explode(':',$_POST['fromempcode']);
	$fromempcode  = $fromempcode1[0];
	$toempcode1 = explode(':',$_POST['toempcode']);
	$toempcode  = $toempcode1[0];

	$StaffMasterSql = "select * from StaffMaster where lefts!=1 ";
	if(($fromempcode!='') && ($toempcode!=''))
		$StaffMasterSql.=" and  ((empcode BETWEEN ('".$fromempcode."') AND ('".$toempcode."')) )";
	if(($_POST['fromdepartment']!='') && ($_POST['todepartment']!=''))
		$StaffMasterSql.="and ((deptid BETWEEN ('".$_POST['fromdepartment']."') AND ('".$_POST['todepartment']."')) )";  
	if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
		$StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
	if(($_POST['frombranch']!='') && ($_POST['tobranch']!=''))
		$StaffMasterSql.="and ((branch BETWEEN ('".$_POST['frombranch']."') AND ('".$_POST['tobranch']."')) )";	
	if($_POST['company']!='all')
		$StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";
	   
	$StaffMasterSql.=" group by empcode   order by empcode;";
	//-- 
	//echo "perfor=".$StaffMasterSql;
	$StaffMasterRes = exequery($StaffMasterSql);
	while($row1 = fetch($StaffMasterRes))
	{
		$DepartmentMatserSql = "select * from DepartmentMaster1 where departmentid ='".$row1['deptid']."'";
		$DepartmentMatserRes = exequery($DepartmentMatserSql);
		$DepartmentMatserRow = fetch($DepartmentMatserRes);

		$weekdayQry = "select * from weekdays where id='".$row1[20]."'";
		$weekdayRes = exequery($weekdayQry);
		$weekdayRow = fetch($weekdayRes);
		 
		echo '<tr><td colspan="32"><center><span style="font-weight:bold;fontsize:15px;"> Empcode : '.$row1[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name : '.$row1[1].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Department : '.$DepartmentMatserRow[1].'&nbsp;&nbsp;&nbsp;&nbsp;</center></span></td></tr>';
		
		echo "<center><table  class='table table-bordered'  style='width:80%'>";    	    
		echo "<tr bgcolor='#4f8edc'>";
		echo "<td style='color:white;font-size:16px;font-weight:bold;'>DATE</td>";

		$month = $_POST['year']."-".$_POST['month']."-01";
		$lastday = date('t', strtotime($month));
		//echo $lastday ;
		for($i=1;$i<=$lastday;$i++) 
		{
			echo "<td style='color:white;font-size:16px;font-weight:bold;'>".$i."</td>";
		}
		echo "<td style='color:white;font-size:16px;font-weight:bold;'>Total Work</td>";
		
		echo '</tr><tr><td>Arrival</td>';
			// echo $lastday;
			for($i=1;$i<=$lastday;$i++) 
			{
				if($i<10)
					$i="0".$i;
					
				$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;

				$qryday = "SELECT UPPER(DAYNAME('".$daytemp."')) AS DAY";
				$resday = exequery($qryday);
				$rowday = fetch($resday);
				//echo $qryday."<br/>";
				
				$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
				$ShiftMasterRes = exequery($ShiftMasterSql);
				$rowshifttime = fetch($ShiftMasterRes);
				//echo $ShiftMasterSql."<br/>";
				
				$shiftstarttime = $rowshifttime[4];
				$shiftendtime   = $ShiftMasterRow[7];
				$shifttotaltime = $ShiftMasterRow[8];

				$LeaveSql = "select * from LeaveTransaction where empcode='".$row1[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
				$LeaveRes = exequery($LeaveSql);
				$LeaveRow = fetch($LeaveRes);
				//echo $LeaveSql."<br/>";
				
				$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$row1['region']."' ";
				$HolidayMasterRes = exequery($HolidayMasterSql);
				$HolidayMasterRow = fetch($HolidayMasterRes);
				//echo $HolidayMasterSql."<br/>";
				
				$qryattendance = "select * from Attendance where date = '".$daytemp."' and empcode = '".$row1[0]."' ";
				$resattendance = exequery($qryattendance);
				$rowattendance = fetch($resattendance);
				//echo $qryattendance."<br/>";
				
				if($rowattendance!=NULL)
				{
					if($rowattendance[4]!=null )
					{
					   echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[4]</td>";
					}
					else if($LeaveRow!=NULL)
					{
					  echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
					}
					else 
					{
						echo"<td style='color:red;font-size:12px;font-face:verdana;'></td>";
					}
				}
				else if($rowday[0] == $weekdayRow[1] || $rowday[0] == $weekdayRow[6])
				{	
				   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
				}
				else if($LeaveRow!=NULL)
				{
					echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
				}
				else if($HolidayMasterRow!=NULL)
				{
					echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
				}
				else
				{
				   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
				}
			}
		echo '<td></td></tr>';

		echo '<tr><td>Departure</td>';
			for($i=1;$i<=$lastday;$i++) 
			{
				if($i<10)
					$i="0".$i;

				$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;

				$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
				$resday = exequery($qryday);
				$rowday = fetch($resday);

				$LeaveSql = "select * from LeaveTransaction where empcode='".$row1[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
				$LeaveRes = exequery($LeaveSql);
				$LeaveRow = fetch($LeaveRes);

								
				$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$row1['region']."' ";
				$HolidayMasterRes = exequery($HolidayMasterSql);
				$HolidayMasterRow = fetch($HolidayMasterRes);

				$qryattendance = "select * from Attendance where date = '".$daytemp."' and empcode = '".$row1[0]."' ";
				$resattendance = exequery($qryattendance);
				$rowattendance = fetch($resattendance);

				if($rowattendance!=NULL)
				{
					if($rowattendance[5]!=NULL)
					{
						echo "<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[5]</td>";
					}
					else if($LeaveRow!=NULL)
					{
						echo "<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
					}
					else 
					{
						echo "<td style='color:red;font-size:12px;font-face:verdana;'></td>";
					}
				}
				else if($rowday[0]==$weekdayRow[1])
				{	
				   echo "<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
				}
				else if($LeaveRow!=NULL)
				{
					echo "<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
				}
				else if($HolidayMasterRow!=NULL)
				{
					
					echo "<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
				}									
				else
				{
				   echo "<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
				}
			}
		echo '<td></td></tr>';
		
		echo '<tr><td>Late Arvl</td>';
		   //echo $lastday ;
				for($i=1;$i<=$lastday;$i++) 
				{
					if($i<10)
						$i="0".$i;
				$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;

				$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
				$resday = exequery($qryday);
				$rowday = fetch($resday);

				$LeaveSql = "select * from LeaveTransaction where empcode='".$row1[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
				$LeaveRes = exequery($LeaveSql);
				$LeaveRow = fetch($LeaveRes);
				//echo $LeaveSql;

				$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$row1['region']."' ";
				$HolidayMasterRes = exequery($HolidayMasterSql);
				$HolidayMasterRow = fetch($HolidayMasterRes);

				$qryattendance = "select * from Attendance where date = '".$daytemp."' and empcode = '".$row1[0]."' ";
				$resattendance = exequery($qryattendance);
				$rowattendance = fetch($resattendance);

				/*
				$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
				$ShiftMasterRes = exequery($ShiftMasterSql);
				$rowshifttime = fetch($ShiftMasterRes);

				$shiftstarttime = $rowshifttime[4];
				$shiftendtime   = $ShiftMasterRow[7];
				$shifttotaltime = $ShiftMasterRow[8];
				*/
				
					if(strtotime($rowattendance[4]) > strtotime('09:30:00') && $rowattendance[4] != "")
					{
						/*
						$qrytimediff = "SELECT TIMEDIFF('$daytemp $rowattendance[4]','$daytemp $rowshifttime[4]')";
						$restimediff = exequery($qrytimediff);
						$rowtimediff = fetch($restimediff);
						
							
						$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','$daytemp 00:11:00')";
						$restimediff1 = exequery($qrytimediff1);
						$rowtimediff1 = fetch($restimediff1);
						$tempdata = substr($rowtimediff1[0],0,1);
						if($tempdata!='-')
							echo "<td style='color:red;font-size:14px;font-face:verdana;'>".substr($rowtimediff[0],0,5)."</td>";
						else if(strtotime($rowattendance[4])<strtotime($rowshifttime[4]))
							echo"<td style='color:black;font-size:12px;font-face:verdana;'></td>";
						else
							echo"<td style='color:black;font-size:12px;font-face:verdana;'></td>";
						*/
					
						$latesec = strtotime($rowattendance[4]) - strtotime('09:30:00');
						$latemin = $latesec / 60; 
						$latehrs = floor($latemin / 60);
						
						$submin = $latehrs * 60;
						$submi = $latemin - $submin;
						
						if($latemin <= 59)
							echo"<td style='color:red;font-size:12px;font-face:verdana;'>".$latemin." min</td>";
						else if($latemin > 59)
							echo"<td style='color:red;font-size:12px;font-face:verdana;'>".$latehrs.'h '.$submi."min</td>";
						else
							echo"<td style='color:red;font-size:12px;font-face:verdana;'>NA</td>";
					} else if(strtotime($rowattendance[4]) <= strtotime('09:30:00') && $rowattendance[4] != "")
					{
						echo"<td style='color:black;font-size:12px;font-face:verdana;'>--</td>";
					} else if(strtotime($rowattendance[4])<strtotime($rowshifttime[4]) && strtotime($rowattendance[4])!=strtotime($rowshifttime[4]) && $rowattendance!=null)
					{
						echo"<td style='color:black;font-size:12px;font-face:verdana;'></td>";
					}
					else if($rowday[0] == $weekdayRow[1])
					{	
					   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
					}
					else if($LeaveRow!=NULL)
					{
						echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
					}
					else if($HolidayMasterRow!=NULL)
					{
						echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
					}									
					else
					{
					   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
					}
				}
		echo '<td></td></tr>';
		
		echo '<tr><td>Early Dept</td>';
		   //echo $lastday ;
		   for($i=1;$i<=$lastday;$i++) 
		   {
				if($i<10)
						$i="0".$i;

				$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;

				$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
				$resday = exequery($qryday);
				$rowday = fetch($resday);

				$LeaveSql = "select * from LeaveTransaction where empcode='".$row1[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
				$LeaveRes = exequery($LeaveSql);
				$LeaveRow = fetch($LeaveRes);


				$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$row1['region']."' ";
				$HolidayMasterRes = exequery($HolidayMasterSql);
				$HolidayMasterRow = fetch($HolidayMasterRes);

				$qryattendance = "select * from Attendance where date = '".$daytemp."' and empcode = '".$row1[0]."' ";
				$resattendance = exequery($qryattendance);
				$rowattendance = fetch($resattendance);

				$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
				$ShiftMasterRes = exequery($ShiftMasterSql);
				$rowshifttime = fetch($ShiftMasterRes);

				$shiftstarttime = $rowshifttime[4];
				$shiftendtime   = $ShiftMasterRow[7];
				$shifttotaltime = $ShiftMasterRow[8];
								
					if( strtotime($rowattendance[5]) <= strtotime('17:30:00') && $rowattendance[5] != "" )
					{
						/*$qrytimediff = "SELECT TIMEDIFF('$daytemp $rowshifttime[7]','$daytemp $rowattendance[7]')";
						$restimediff = exequery($qrytimediff);
						$rowtimediff = fetch($restimediff);
						
						$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','$daytemp 00:11:00')";
						$restimediff1 = exequery($qrytimediff1);
						$rowtimediff1 = fetch($restimediff1);
						$tempdata = substr($rowtimediff1[0],0,1);
						
						if($tempdata!='-')
							echo "<td style='color:red;font-size:14px;font-face:verdana;'>".substr($rowtimediff[0],0,5)."</td>";
						else if(strtotime($rowattendance[7])>strtotime($rowshifttime[7]))
							echo"<td style='color:black;font-size:12px;font-face:verdana;'></td>";
						else
							echo"<td style='color:black;font-size:12px;font-face:verdana;'></td>";*/
							
						
						$latesec = strtotime('17:30:00') - strtotime($rowattendance[5]);
						$latemin = $latesec / 60; 
						$latehrs = floor($latemin / 60);
						
						$submin = $latehrs * 60;
						$submi = $latemin - $submin;
						
						if($latemin <= 59)
							echo"<td style='color:red;font-size:12px;font-face:verdana;'>".$latemin." min</td>";
						else if($latemin > 59)
							echo"<td style='color:red;font-size:12px;font-face:verdana;'>".$latehrs."h ".$submi."min</td>";
						else 
							echo"<td style='color:black;font-size:12px;font-face:verdana;'>NA</td>";
					} else if( strtotime($rowattendance[5]) > strtotime('17:30:00') && $rowattendance[5] != "" )
					{
						echo "<td style='color:black;font-size:12px;font-face:verdana;'>--</td>";
					} else if($rowattendance!=null)
					{
						echo"<td style='color:black;font-size:12px;font-face:verdana;'></td>";
					}
					else if($rowday[0]==$weekdayRow[1])
					{	
					   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
					}
					else if($LeaveRow!=NULL)
					{
						echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
					}
					else if($HolidayMasterRow!=NULL)
					{
						echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
					}
					else
					{
					   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
					}
			}
		echo '<td></td></tr>';
		
		echo '<tr><td>Work Hrs</td>';
			$totalwork = 0;
			$toatlmin = 0;
			$toatlhr = 0;
			$toatlmn = 0;
			
		   //echo $lastday ;
			for($i=1;$i<=$lastday;$i++) 
			{
				if($i<10)
					$i="0".$i;
					
				$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
								
				$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
				$resday = exequery($qryday);
				$rowday = fetch($resday);
			
				$LeaveSql = "select * from LeaveTransaction where empcode='".$row1[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
				$LeaveRes = exequery($LeaveSql);
				$LeaveRow = fetch($LeaveRes);

				$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$row1['region']."' ";
				$HolidayMasterRes = exequery($HolidayMasterSql);
				$HolidayMasterRow = fetch($HolidayMasterRes);

				$qryattendance = "select * from Attendance where date = '".$daytemp."' and empcode = '".$row1[0]."' ";
				$resattendance = exequery($qryattendance);
				$rowattendance = fetch($resattendance);

				$qryTIMEDIFF = "select TIMEDIFF ('".$rowattendance[5]."','".$rowattendance[4]."');";
				$resTIMEDIFF = exequery($qryTIMEDIFF);
				$rowTIMEDIFF = fetch($resTIMEDIFF);

				$total  =  explode(':',$rowTIMEDIFF[0]);
				$totalwork = $total[0].':'.$total[1];

				$toatlhr = $toatlhr + $total[0];
				$toatlmn = $toatlmn + $total[1];

				if($rowattendance!=NULL)
				{
					echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$totalwork."</td>";
				}
				else if($rowday[0]==$weekdayRow[1])
				{	
				   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
				}
				else if($LeaveRow!=NULL)
				{
					echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
				}
				else if($HolidayMasterRow!=NULL)
				{
					
					echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
				}									
				else
				{
				   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
				}
			}
		$hours  = floor($toatlmn/60); //round down to nearest minute. 
		$total = $toatlhr + $hours;
		$minutes = $toatlmn % 60;
		
		$totalwork = $total.":".$minutes;
		
		$actualworkhrs = 26*7;
		echo '<td>'.$totalwork.' <br>-----------<br> <font color="green">'.$actualworkhrs.'</font></td></tr>';

		echo '<tr><td>Status</td>';
		   //echo $lastday ;
		for($i=1;$i<=$lastday;$i++) 
		{
			if($i<10)
				$i="0".$i;
			$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;

			$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
			$resday = exequery($qryday);
			$rowday = fetch($resday);

			$LeaveSql = "select * from LeaveTransaction where empcode='".$row1[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
			$LeaveRes = exequery($LeaveSql);
			$LeaveRow = fetch($LeaveRes);


			$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$row1['region']."' ";
			$HolidayMasterRes = exequery($HolidayMasterSql);
			$HolidayMasterRow = fetch($HolidayMasterRes);

			$qryattendance = "select * from Attendance where date = '".$daytemp."' and empcode = '".$row1[0]."' ";
			$resattendance = exequery($qryattendance);
			$rowattendance = fetch($resattendance);

			if($rowattendance!=NULL)
			{
				echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$rowattendance[15]."</td>";
			}
			else if($rowday[0] == $weekdayRow[1])
			{	
			   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
			}
			else if($LeaveRow != NULL)
			{
				echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
			}
			else if($HolidayMasterRow != NULL)
			{
				echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
			}									
			else
			{
			   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
			}
		}
		echo '<td></td></tr>';
		echo '</table></center><br/><br/>';
	}
}

//------------------------- absent
if($_POST['misreport']=='absent') 
{
	$time = mktime(0, 0, 0, $_POST['month']);
	$name = strftime("%b", $time);
	echo "<center> <p style='font-color:white;font-weight:bold;font-size:16px;'>Absent List between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";

	if($_POST['fromlocation']!="")
	{
		$branchqry = "select * from RegionMaster1 where regionid='".$_POST['fromlocation']."'";
		$branchres = exequery($branchqry);
		$branchrow = fetch($branchres);
		$branch = $branchrow[1];
	} else {
		$branch ='ALL';
	}
 
	if($_POST['fromdepartment']!="")
	{
		$departmentqry = "select * from DepartmentMaster1 where departmentid='".$_POST['fromdepartment']."'";
		$departmentres = exequery($departmentqry);
		$departmentrow = fetch($departmentres);
		$department = $departmentrow[1];
	} else {
		$department ='ALL';
	}

	if($_POST['company']!="all")
	{
		$companyqry = "select * from EmpCompanyMaster where empcompanyid='".$_POST['company']."'";
		$companyres = exequery($companyqry);
		$companyrow = fetch($companyres);
		$company = $companyrow[1];
	} else {
		$company ='ALL';
	} //attendance while


	//echo "</table></center>";
	
	/*
echo "<center><p class='btn btn-info' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department : ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";

echo '<center><table class="table table-bordered" border="1" style="width:75%">';
echo "<tr bgcolor='#A48AD4'>";
echo '<th style="color:white;font-size:16px;"> Emp Code</th> '; 
echo '<th style="color:white;font-size:16px;"> Employee Details </th>';
echo '<th style="color:white;font-size:16px;"> Date </th>';
echo '<th style="color:white;font-size:16px;"> No. of Days</th>';
echo "</tr>";*/


$frmdate = DMYtoYMD($_POST['frmdate']);
$todate  = DMYTOYMD($_POST['todate']);
$date = $_POST['year']."-".$_POST['month'];
$fromempcode1 = explode(':',$_POST['fromempcode']);
$fromempcode  = $fromempcode1[0];
$toempcode1 = explode(':',$_POST['toempcode']);
$toempcode  = $toempcode1[0];

$StaffMasterSql = "select * from StaffMaster where lefts!=1 ";

if(($fromempcode!='') && ($toempcode!=''))
	$StaffMasterSql.=" and  ((empcode BETWEEN ('".$fromempcode."') AND ('".$toempcode."')) )";

if(($_POST['fromdepartment']!='') && ($_POST['todepartment']!=''))
	$StaffMasterSql.="and ((deptid BETWEEN ('".$_POST['fromdepartment']."') AND ('".$_POST['todepartment']."')) )";

if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
		$StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
if(($_POST['frombranch']!='') && ($_POST['tobranch']!=''))
		$StaffMasterSql.="and ((branch BETWEEN ('".$_POST['frombranch']."') AND ('".$_POST['tobranch']."')) )";	

if($_POST['company']!='all')
	$StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";

	//-- echo "Absent=".$StaffMasterSql;
	
	$StaffMasterRes = exequery($StaffMasterSql);
	while($StaffMasterRow = fetch($StaffMasterRes))
	{
	
	$region="select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
	$resregion=exequery($region);
	$rowregion=fetch($resregion);
									
	$branch="select * from BranchMaster1 where branchid='".$StaffMasterRow['branch']."'";
	$resbranch=exequery($branch);
	$rowbranch=fetch($resbranch);

	$qrydept1 = "select * from DepartmentMaster1 where departmentid = '".$StaffMasterRow['deptid']."'";
	$resdept1 = exequery($qrydept1);
	$rowdept1 = fetch($resdept1);

	$days = 0;
	$data ="";
	$dataremk ="";

	$qryabsent = "select * from Attendance where date>='".DMYtoYMD($_POST['frmdate'])."' and date<='".DMYtoYMD($_POST['todate'])."' and empcode = '".$StaffMasterRow[0]."' and modstatus='A' order by date desc";

	//echo $qryabsent;

	$resabsent = exequery($qryabsent);
	$inccnt=0;
		while($rowabsent = fetch($resabsent))
		{
			$dateabsent = explode("-", $rowabsent[1]);
			$data .= "<td><b>".$dateabsent[2]."</b></td>";
			$dataremk .= "<td><b>".$rowabsent[15]."</b></td>";
			$days++;
		}
		if($days>0)
		{
			/*
			echo"<tr><td>".$StaffMasterRow[0]."</td><td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td>";
			echo"<td>".$data."</td>";
			echo "<td>".$days."</td>";
			echo"</tr>";
			*/
			
			echo "<div class='border'><center><table class='table table-striped table-bordered datatables' id='dynamic-table' style='width:80%'>";
			echo "<tr><td colspan='3'>Absent Memo between <b>".$_POST['frmdate']." to ".$_POST['todate']."</b></td></tr>";
			
			echo "<tr><td colspan='3'> Emp Code / Name :- <b>".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."</b></td></tr>";
			
			echo "<tr><td colspan='3'>You have been marked absent on the following dates. Please fill up OD/LEAVES at earliest.</td></tr>";
			echo "<tr><td width='7%'>Date :- </td><td colspan='2'>";
				echo "<table class='table table-striped table-bordered datatables' id='tableinner' style='width:100%'>";
				echo "<tr><td width='7%'>Date :- </td>".$data."</tr>"; 
				echo "<tr><td width='7%'>Remark :- </td>".$dataremk."</tr>";
				echo "<tr><td>Total :- ".$days."</td></tr></table>";
			echo "<tr><td colspan='3' style='text-align:right;'><input class='btn-primary' type='button' value='Mark as leave' />&nbsp;<input class='btn-default' type='button' value='Correction' /></td></tr>";
			echo "<tr><td colspan='3' style='text-align:left;'><b>Signature :- </b></td></tr>";
			echo "<tr><td colspan='3'></td></tr>";
			echo "</table></center></div><br>";	
		}
	}
}

//------------------------- no punch
if($_POST['misreport']=='ab') 
{
	$time = mktime(0, 0, 0, $_POST['month']);
	$name = strftime("%b", $time);
	echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'>No Punch List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";
	$my = $_POST['year'].'-'.$_POST['month'];
	$month = $_POST['year']."-".$_POST['month']."-01";
	$lastday = date('t',strtotime($month));
	
	if($_POST['fromlocation']!="")
	{
		$branchqry = "select * from RegionMaster1 where regionid='".$_POST['fromlocation']."'";
		$branchres = exequery($branchqry);
		$branchrow = fetch($branchres);
		$branch = $branchrow[1];
	} else {
		$branch ='ALL';
	}
	 
	if($_POST['fromdepartment']!="")
	{
		$departmentqry = "select * from DepartmentMaster1 where departmentid='".$_POST['fromdepartment']."'";
		$departmentres = exequery($departmentqry);
		$departmentrow = fetch($departmentres);
		$department = $departmentrow[1];
	} else {
		$department ='ALL';
	}
		   
	if($_POST['company']!="all")
	{
		$companyqry = "select * from EmpCompanyMaster where empcompanyid='".$_POST['company']."'";
		$companyres = exequery($companyqry);
		$companyrow = fetch($companyres);
		$company = $companyrow[1];
	} else {
		$company ='ALL';
	} //attendance while
			

	/*
	echo "<center> <p class='btn btn-info' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";
	echo '<center><table class="table table-bordered" border="1" style="width:75%">';
	echo "<tr bgcolor='#A48AD4'>";
	echo '<th style="color:white;font-size:16px;"> Emp Code</th> '; 
	echo '<th style="color:white;font-size:16px;"> Employee Details </th>';
	echo '<th style="color:white;font-size:16px;"> Date </th>';
	echo '<th style="color:white;font-size:16px;">No. of Days</th>';
	echo "</tr>";
	*/
	
	$frmdate = DMYtoYMD($_POST['frmdate']);
	$todate  = DMYTOYMD($_POST['todate']);
	$date = $_POST['year']."-".$_POST['month'];
	$fromempcode1 = explode(':',$_POST['fromempcode']);
	$fromempcode  = $fromempcode1[0];
	$toempcode1 = explode(':',$_POST['toempcode']);
	$toempcode = $toempcode1[0];

	$diffday = "SELECT TIMESTAMPDIFF(DAY, '".$frmdate."', '".$todate."')";
	$diffdayres = exequery($diffday);
	$diffdayrow = fetch($diffdayres);
	$days1 = $diffdayrow[0]+1;
			   
	$StaffMasterSql = "select * from StaffMaster where lefts!=1 ";
	if(($fromempcode!='') && ($toempcode!=''))
		$StaffMasterSql.=" and  ((empcode BETWEEN ('".$fromempcode."') AND ('".$toempcode."')) )";
	if(($_POST['fromdepartment']!='') && ($_POST['todepartment']!=''))
		$StaffMasterSql.="and ((deptid BETWEEN ('".$_POST['fromdepartment']."') AND ('".$_POST['todepartment']."')) )";
	if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
		$StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
	if($_POST['company']!='all')
		$StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";

	//-- echo "nopunch=".$StaffMasterSql;
	   $StaffMasterRes = exequery($StaffMasterSql);
	while($StaffMasterRow = fetch($StaffMasterRes))
	{ 
		$region="select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
		$resregion=exequery($region);
		$rowregion=fetch($resregion);

		$branch="select * from BranchMaster1 where branchid='".$StaffMasterRow['branch']."'";
		$resbranch=exequery($branch);
		$rowbranch=fetch($resbranch);

		$qrydept1 = "select * from DepartmentMaster1 where departmentid = '".$StaffMasterRow['deptid']."'";
		$resdept1 = exequery($qrydept1);
		$rowdept1 = fetch($resdept1);

		$weekdayQry = "select * from weekdays where id='".$StaffMasterRow[20]."'";
		$weekdayRes = exequery($weekdayQry);
		$weekdayRow = fetch($weekdayRes);
		//echo $weekdayRow[1].$StaffMasterRow[0];
		//echo '<br>';
					
		$days = 0;
		$data ="";
		$tmpmonth = 0;
		
	   for($i=0;$i<$days1;$i++) 
	   {
			if($i<10)
				$i="0".$i;
								
			//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
			$dateqry = "select DATE_ADD('".$frmdate."', interval '".$i."' day)";
			$dateres = exequery($dateqry);
			$daterow = fetch($dateres);
			$daytemp = $daterow[0];

			$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
			$resday = exequery($qryday);
			$rowday = fetch($resday);

			$LeaveSql = "select * from LeaveTransaction where empcode='".$StaffMasterRow[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
			$LeaveRes = exequery($LeaveSql);
			$LeaveRow = fetch($LeaveRes);

			$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$StaffMasterRow['region']."' ";
			$HolidayMasterRes = exequery($HolidayMasterSql);
			$HolidayMasterRow = fetch($HolidayMasterRes);

			$qryattendance = "select * from Attendance where date = '".$daytemp."' and empcode = '".$StaffMasterRow[0]."';";
			//echo $qryattendance;
			//echo '<br>';
			$resattendance = exequery($qryattendance);
			$rowattendance = fetch($resattendance);
			//echo $rowattendance[4];
			if($rowattendance!=null)
			{
				if($rowattendance[4] == '')
				{	
					$flag = 0;
					
					$datearr = explode('-', $daytemp);
					if($tmpmonth==0 || $tmpmonth != $datearr[1])
						$data .= "<tr><td>".$rowattendance[4]."-".YMDtoDMY($daytemp)."</td>";
					else
						$data .= "<td>".$datearr[2]."</td>";
					$tmpmonth = $datearr[1];
					$days++;
				}
			   // echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$rowattendance[16]."".$rowattendance[17]."</td>";
			}
		}
		if($days>0)
		{
		
		/*
		echo"<tr><td>".$StaffMasterRow[0]."</td><td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td>";
		echo"<td>".$data."</td>";
		echo "<td>".$days."</td>";
		echo"</tr>";
		*/
		
			echo "<div class='border'><center><table class='table table-striped table-bordered datatables' id='dynamic-table' style='width:80%'>";
			echo "<tr><td colspan='3'>No Punch list between <b>".$_POST['frmdate']." to ".$_POST['todate']."</b></td></tr>";
			
			echo "<tr><td colspan='3'> Emp Code / Name :- <b>".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."</b></td></tr>";
			
			echo "<tr><td colspan='3'>You have been marked absent on the following dates. Please fill up OD/LEAVES at earliest.</td></tr>";
			echo "<tr><td width='7%'>Details :- </td><td colspan='2'>";
				echo "<table class='table table-striped table-bordered datatables' id='tableinner' style='width:100%'>";
				echo "<tr><td width='7%'>Date :- </td></tr>".$data."";
				echo "<tr><td>Total :- ".$days."</td></tr></table>";
			echo "<tr><td colspan='3' style='text-align:right;'></td></tr>";
			echo "<tr><td colspan='3' style='text-align:left;'><b>Signature :- </b></td></tr>";
			echo "<tr><td colspan='3'></td></tr>";
			echo "</table></center></div><br>";	
		
		}	
	}
	echo "</table></center><br>";	
}

//------------------------- latearrival
if($_POST['misreport']=='latearrival') 
{
	$time = mktime(0, 0, 0, $_POST['month']);
	$name = strftime("%b", $time);
	echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'>Late Arrival List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";
	$my = $_POST['year'].'-'.$_POST['month'];
	$month = $_POST['year']."-".$_POST['month']."-01";
	$lastday = date('t',strtotime($month));
	
	if($_POST['fromlocation']!="")
	{
		$branchqry = "select * from RegionMaster1 where regionid='".$_POST['fromlocation']."'";
		$branchres = exequery($branchqry);
		$branchrow = fetch($branchres);
		$branch = $branchrow[1];
	}
	else 
	{
		$branch ='ALL';
	}

	if($_POST['fromdepartment']!="")
	{
		$departmentqry = "select * from DepartmentMaster1 where departmentid='".$_POST['fromdepartment']."'";
		$departmentres = exequery($departmentqry);
		$departmentrow = fetch($departmentres);
		$department = $departmentrow[1];
	}
	else 
	{
		$department ='ALL';
	}

	if($_POST['company']!="all")
	{
		$companyqry = "select * from EmpCompanyMaster where empcompanyid='".$_POST['company']."'";
		$companyres = exequery($companyqry);
		$companyrow = fetch($companyres);
		$company = $companyrow[1];
	}
	else 
	{
		$company ='ALL';
	} //attendance while
	 
	/*
	echo "<center> <p class='btn btn-warning' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";
	
	echo '<center><table class="table table-bordered" border="1" style="width:75%">';
	echo "<tr bgcolor='sky blue'><th style='color:white;'>Emp Code</th>
		<th style='color:white;'>Employee Details</th>
		<th style='color:white;'>Date</th>
		<th style='color:white;'>No. of Days</th></tr>";
	*/
	
	$frmdate = DMYtoYMD($_POST['frmdate']);
	$todate  = DMYTOYMD($_POST['todate']);
	$date=$_POST['year']."-".$_POST['month'];
	$fromempcode1 = explode(':',$_POST['fromempcode']);
	$fromempcode  = $fromempcode1[0];
	$toempcode1 = explode(':',$_POST['toempcode']);
	$toempcode  = $toempcode1[0];

	$diffday  = "SELECT TIMESTAMPDIFF(DAY, '".$frmdate."', '".$todate."')";
	$diffdayres = exequery($diffday);
	$diffdayrow = fetch($diffdayres);
	$days1 = $diffdayrow[0]+1;
			   
	$StaffMasterSql = "select * from StaffMaster where lefts!=1 ";
	if(($fromempcode!='') && ($toempcode!=''))
		$StaffMasterSql.=" and  ((empcode BETWEEN ('".$fromempcode."') AND ('".$toempcode."')) )";
	if(($_POST['fromdepartment']!='') && ($_POST['todepartment']!=''))
		$StaffMasterSql.="and ((deptid BETWEEN ('".$_POST['fromdepartment']."') AND ('".$_POST['todepartment']."')) )";
	if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
		$StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
	if(($_POST['frombranch']!='') && ($_POST['tobranch']!=''))
		$StaffMasterSql.="and ((branch BETWEEN ('".$_POST['frombranch']."') AND ('".$_POST['tobranch']."')) )";	
	if($_POST['company']!='all')
		$StaffMasterSql.="and Empcompanyid='".$_POST['company']."'  ORDER BY empcode";

		$StaffMasterSql.="ORDER BY empcode";
	//-- echo "smast= ".$StaffMasterSql;
	   $StaffMasterRes = exequery($StaffMasterSql);
	   while($StaffMasterRow = fetch($StaffMasterRes))
	   {
			$region="select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
			$resregion=exequery($region);
			$rowregion=fetch($resregion);
												
			$branch="select * from BranchMaster1 where branchid='".$StaffMasterRow['branch']."'";
			$resbranch=exequery($branch);
			$rowbranch=fetch($resbranch);
			
			$qrydept1 = "select * from DepartmentMaster1 where departmentid = '".$StaffMasterRow['deptid']."'";
			$resdept1 = exequery($qrydept1);
			$rowdept1 = fetch($resdept1);
			
			$weekdayQry = "select * from weekdays where id='".$StaffMasterRow[20]."'";
			$weekdayRes = exequery($weekdayQry);
			$weekdayRow = fetch($weekdayRes);
			//echo $weekdayRow[1].$StaffMasterRow[0];
			//echo '<br>';
					
			$dates = "";
			$days=0;
			$tmpmonth = 0;
			
			//$qrylatearrival = "select * from Attendancechk where attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'   and empcode = '".$StaffMasterRow[0]."'  ";
			$qrylatearrival = "select * from Attendance where date>='".DMYtoYMD($_POST['frmdate'])."' and date<='".DMYtoYMD($_POST['todate'])."' and empcode = '".$StaffMasterRow[0]."'  ORDER BY empcode;";
			$reslatearrival = exequery($qrylatearrival);

			$region="select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
			$resregion=exequery($region);
			$rowregion=fetch($resregion);

			$branch="select * from BranchMaster1 where branchid='".$StaffMasterRow['branch']."'";
			$resbranch=exequery($branch);
			$rowbranch=fetch($resbranch);

			$qrydept1 = "select * from DepartmentMaster1 where departmentid = '".$StaffMasterRow['deptid']."'";
			$resdept1 = exequery($qrydept1);
			$rowdept1 = fetch($resdept1);

			$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
			$ShiftMasterRes = exequery($ShiftMasterSql);
			$rowshifttime = fetch($ShiftMasterRes);

			$shiftstarttime = $rowshifttime[4];
			$shiftendtime   = $ShiftMasterRow[7];
			$shifttotaltime = $ShiftMasterRow[8];


			//echo "latearrival=".$qrylatearrival;
							
			while($rowlatearrival = fetch($reslatearrival))
			{
				/*
				$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$StaffMasterRow['shiftid']."'";
				$ShiftMasterRes = exequery($ShiftMasterSql);
				$rowshifttime = fetch($ShiftMasterRes);
				*/
				//echo $ShiftMasterSql;
				$qrydifference = "select TIMEDIFF('".$rowlatearrival[4]."','09:30:00')";
				// echo $qrydifference."<br>";
				$resdifference = exequery($qrydifference);
				$rowdifference = fetch($resdifference);
				
				// echo "<br>".$qrydifference;
				
				
				if(strtotime($rowlatearrival[4]) > strtotime('09:30:00'))
				{
					$qrytimediff = "SELECT TIMEDIFF('$daytemp $rowlatearrival[3]','$daytemp $rowshifttime[4]')";
					// echo "<br/>qrytimediff=".$qrytimediff;
					$restimediff = exequery($qrytimediff);
					$rowtimediff = fetch($restimediff);

					$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','$daytemp 00:11:00')";
					// echo "<br/>qrytimediff1=".$qrytimediff1;
					$restimediff1 = exequery($qrytimediff1);
					$rowtimediff1 = fetch($restimediff1);
					$tempdata = substr($rowtimediff1[0],0,1);

					//echo "".substr($rowdifference[0],0,1)."";
					if(($tempdata)!='-')
					{
						//$dates .= "<td>".YMDtoDMY($rowlatearrival[1])." (".$rowdifference[0].")</td>";
						
						$datearr = explode('-', $rowlatearrival[1]);
						if($tmpmonth==0 || $tmpmonth != $datearr[1])
							$dates .= "<tr><td></td><td>".YMDtoDMY($rowlatearrival[1])." (".$rowdifference[0].")</td>";
						else
							$dates .= "<td>".$datearr[2]." (".$rowdifference[0].")</td>";
						$tmpmonth = $datearr[1]; 
						$days++;
					}
				}	
			}
			// echo "hi=".$days;
			
			if($days>0)
			{
				//echo"<tr><td>".$StaffMasterRow[0]."</td><td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td><td>".$dates."</td><td>".$days."</td></tr>";	
				
				echo "<div class='border'><center><table class='table table-striped table-bordered datatables' id='dynamic-table' style='width:80%'>";
				echo "<tr><td colspan='3'>Late arrival list between <b>".$_POST['frmdate']." to ".$_POST['todate']."</b></td></tr>";
				
				echo "<tr><td colspan='3'> Emp Code / Name :- <b>".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."</b></td></tr>";
				
				echo "<tr><td colspan='3'>You have been marked late on the following dates. Kindly forward OD/REQUISITE PERMISION within 3 days.</td></tr>";
				echo "<tr><td width='7%'>Details :- </td><td colspan='2'>";
					echo "<table class='table table-striped table-bordered datatables' id='tableinner' style='width:100%'>";
					echo "<tr><td width='7%'>Date :-</td><td> DD-MM-YYYY (hh:mm:ss)</td></tr>".$dates."";
					echo "<tr><td>Total :- ".$days."</td></tr></table>";
				echo "<tr><td colspan='3' style='text-align:right;'></td></tr>";
				echo "<tr><td colspan='3' style='text-align:left;'><b>Signature :- </b></td></tr>";
				echo "<tr><td colspan='3'></td></tr>";
				echo "</table></center></div><br>";	
			
			}				
		
	   }
		echo "</tr></table></center><br>";	
	}  

//------------------------- early departure 
if($_POST['misreport']=='earlydep') 
{
	$time = mktime(0, 0, 0, $_POST['month']);
	$name = strftime("%b", $time);
	
	echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'> Early Departure List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";
	$my = $_POST['year'].'-'.$_POST['month'];
	$month = $_POST['year']."-".$_POST['month']."-01";
	$lastday = date('t',strtotime($month));

	if($_POST['fromlocation']!="")
	{
		 $branchqry = "select * from RegionMaster1 where regionid='".$_POST['fromlocation']."'";
		 $branchres = exequery($branchqry);
		 $branchrow = fetch($branchres);
		 $branch = $branchrow[1];
	}
	else 
	{
		$branch ='ALL';
	}

	if($_POST['fromdepartment']!="")
	{
		 $departmentqry = "select * from DepartmentMaster1 where departmentid='".$_POST['fromdepartment']."'";
		 $departmentres = exequery($departmentqry);
		 $departmentrow = fetch($departmentres);
		 $department = $departmentrow[1];
	}
	else 
	{
		$department ='ALL';
	}
	   
	if($_POST['company']!="all")
	{
		 $companyqry = "select * from EmpCompanyMaster where empcompanyid='".$_POST['company']."'";
		 $companyres = exequery($companyqry);
		 $companyrow = fetch($companyres);
		 $company = $companyrow[1];
	}
	else 
	{
		$company ='ALL';
	}
	
	$frmdate = DMYtoYMD($_POST['frmdate']);
	$todate  = DMYTOYMD($_POST['todate']);
	$date = $_POST['year']."-".$_POST['month'];
	$fromempcode1 = explode(':',$_POST['fromempcode']);
	$fromempcode  = $fromempcode1[0];
	$toempcode1 = explode(':',$_POST['toempcode']);
	$toempcode  = $toempcode1[0];

	$diffday  = "SELECT TIMESTAMPDIFF(DAY, '".$frmdate."', '".$todate."')";
	$diffdayres = exequery($diffday);
	$diffdayrow = fetch($diffdayres);
	$days1 = $diffdayrow[0]+1;

	$StaffMasterSql = "select * from StaffMaster where lefts!=1 ";
	if(($fromempcode!='') && ($toempcode!=''))
		$StaffMasterSql.=" and  ((empcode BETWEEN ('".$fromempcode."') AND ('".$toempcode."')) )";
	if(($_POST['fromdepartment']!='') && ($_POST['todepartment']!=''))
		$StaffMasterSql.="and ((deptid BETWEEN ('".$_POST['fromdepartment']."') AND ('".$_POST['todepartment']."')) )";
	if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
		$StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
	if(($_POST['frombranch']!='') && ($_POST['tobranch']!=''))
		$StaffMasterSql.="and ((branch BETWEEN ('".$_POST['frombranch']."') AND ('".$_POST['tobranch']."')) )";	
	if($_POST['company']!='all')
		$StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";

		$StaffMasterSql.="ORDER BY empcode";

	//--
	//echo "EarlyDept smas= ".$StaffMasterSql;
	$StaffMasterRes = exequery($StaffMasterSql);
	while($StaffMasterRow = fetch($StaffMasterRes))
	{
		$region="select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
		$resregion=exequery($region);
		$rowregion=fetch($resregion);

		$branch="select * from BranchMaster1 where branchid='".$StaffMasterRow['branch']."'";
		$resbranch=exequery($branch);
		$rowbranch=fetch($resbranch);

		$qrydept1 = "select * from DepartmentMaster1 where departmentid = '".$StaffMasterRow['deptid']."'";
		$resdept1 = exequery($qrydept1);
		$rowdept1 = fetch($resdept1);

		$weekdayQry = "select * from weekdays where id='".$StaffMasterRow[20]."'";
		$weekdayRes = exequery($weekdayQry);
		$weekdayRow = fetch($weekdayRes);
		//echo $weekdayRow[1].$StaffMasterRow[0];
		//echo '<br>';

		$data = "";
		$days=0;
		$tmpmonth = 0;
		
		$qrylatearrival = "select * from Attendance where date >= '".DMYtoYMD($_POST['frmdate'])."' and date<='".DMYtoYMD($_POST['todate'])."' and empcode = '".$StaffMasterRow[0]."' ";
		$reslatearrival = exequery($qrylatearrival);
		//echo $qrylatearrival;
		$region="select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
		$resregion=exequery($region);
		$rowregion=fetch($resregion);

		$branch="select * from BranchMaster1 where branchid='".$StaffMasterRow['branch']."'";
		$resbranch=exequery($branch);
		$rowbranch=fetch($resbranch);

		$qrydept1 = "select * from DepartmentMaster1 where departmentid = '".$StaffMasterRow['deptid']."'";
		$resdept1 = exequery($qrydept1);
		$rowdept1 = fetch($resdept1);

		$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
		$ShiftMasterRes = exequery($ShiftMasterSql);
		$rowshifttime = fetch($ShiftMasterRes);

		$shiftstarttime = $rowshifttime[4];
		$shiftendtime   = $ShiftMasterRow[7];
		$shifttotaltime = $ShiftMasterRow[8];

		$larr = "";
		//echo "<br > 0 - ".$qrylatearrival;
		while($rowlatearrival = fetch($reslatearrival))
		{ 
			if($rowlatearrival[13]!=NULL) {
				$larr = $rowlatearrival[13];
			}
			else if( $rowlatearrival[12]!=NULL )
			{
				$larr = $rowlatearrival[12];
			}else if( $rowlatearrival[11]!=NULL )
			{
				$larr = $rowlatearrival[11];
			}else if( $rowlatearrival[10]!=NULL )
			{
				$larr = $rowlatearrival[10];
			}else if( $rowlatearrival[9]!=NULL )
			{
				$larr = $rowlatearrival[9];
			}else if( $rowlatearrival[8]!=NULL )
			{
				$larr = $rowlatearrival[8];
			}else if( $rowlatearrival[7]!=NULL )
			{
				$larr = $rowlatearrival[7];
			} else if($rowlatearrival[6]!=NULL )
			{
				$larr = $rowlatearrival[6];
			} else if($rowlatearrival[5]!=NULL )
			{
				$larr = $rowlatearrival[5];
			}
			else 
			{
				$larr = "";
			}
		
			if($larr != "" && strtotime($larr) < strtotime('17:30:00'))
			{
				$minusedtime = (strtotime('17:30:00') - strtotime($larr.":00"));
				$mins = floor($minusedtime / 60);
				$hours = floor($mins / 60);
				$minutes = ($mins % 60);
				
				$datearr = explode('-', $rowlatearrival[1]);
				if($tmpmonth==0 || $tmpmonth != $datearr[1])
					$data .= "<tr><td></td><td>".YMDtoDMY($rowlatearrival[1])." (".$hours."h ".$minutes."mins)</td>";
				else
					$data .= "<td>".$datearr[2]." (".$hours."h ".$minutes."mins)</td>";
				$tmpmonth = $datearr[1];
				$days++;
			}
			
		}
		
		// early departure
		if($days>0)
		{
			echo "<div class='border'><center><table class='table table-striped table-bordered datatables' id='dynamic-table' style='width:80%'>";
			echo "<tr><td colspan='3'>Early departure list between <b>".$_POST['frmdate']." to ".$_POST['todate']."</b></td></tr>";
			
			echo "<tr><td colspan='3'> Emp Code / Name :- <b>".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."</b></td></tr>";
			
			echo "<tr><td colspan='3'>You have been marked early departure on the following dates. Kindly forward OD/REQUISITE PERMISION within 3 days.</td></tr>";
			echo "<tr><td width='7%'>Details :- </td><td colspan='2'>";
				echo "<table class='table table-striped table-bordered datatables' id='tableinner' style='width:100%'>";
				echo "<tr><td width='7%'>Date :- </td></tr>".$data."";
				echo "<tr><td>Total :- ".$days."</td></tr></table>";
			echo "<tr><td colspan='3' style='text-align:right;'></td></tr>";
			echo "<tr><td colspan='3' style='text-align:left;'><b>Signature :- </b></td></tr>";
			echo "<tr><td colspan='3'></td></tr>";
			echo "</table></center></div><br>";
		}
	}
}

//------------------------- outdoor
if($_POST['misreport']=='od') 
{
	$time = mktime(0, 0, 0, $_POST['month']);
	$name = strftime("%b", $time);
	echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'>OutDoor Duty List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";

	if($_POST['fromlocation']!="")
	{
		$branchqry = "select * from RegionMaster1 where regionid='".$_POST['fromlocation']."'";
		$branchres = exequery($branchqry);
		$branchrow = fetch($branchres);
		$branch = $branchrow[1];
	}
	else 
	{
		$branch ='ALL';
	}
 
	if($_POST['fromdepartment']!="")
	{
		$departmentqry = "select * from DepartmentMaster1 where departmentid='".$_POST['fromdepartment']."'";
		$departmentres = exequery($departmentqry);
		$departmentrow = fetch($departmentres);
		$department = $departmentrow[1];
	}
	else 
	{
		$department ='ALL';
	}
	   
	if($_POST['company']!="all")
	{
		$companyqry = "select * from EmpCompanyMaster where empcompanyid='".$_POST['company']."'";
		$companyres = exequery($companyqry);
		$companyrow = fetch($companyres);
		$company = $companyrow[1];
	}
	else 
	{
		$company ='ALL';
	} 
	   
	$frmdate = DMYtoYMD($_POST['frmdate']);
	$todate = DMYTOYMD($_POST['todate']);

	$date = $_POST['year']."-".$_POST['month'];
	$fromempcode1 = explode(':',$_POST['fromempcode']);
	$fromempcode  = $fromempcode1[0];
	$toempcode1 = explode(':',$_POST['toempcode']);
	$toempcode  = $toempcode1[0];

	$StaffMasterSql = "select * from StaffMaster where lefts!=1 ";

	if(($fromempcode!='') && ($toempcode!=''))
		$StaffMasterSql.=" and  ((empcode BETWEEN ('".$fromempcode."') AND ('".$toempcode."')) )";
	if(($_POST['fromdepartment']!='') && ($_POST['todepartment']!=''))
		$StaffMasterSql.="and ((deptid BETWEEN ('".$_POST['fromdepartment']."') AND ('".$_POST['todepartment']."')) )";
	if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
	$StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
	if(($_POST['frombranch']!='') && ($_POST['tobranch']!=''))
		$StaffMasterSql.="and ((branch BETWEEN ('".$_POST['frombranch']."') AND ('".$_POST['tobranch']."')) )";	
	if($_POST['company']!='all')
		$StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";
		
	//$StaffMasterSql.=" group by empcode   order by empcode desc ";
	$StaffMasterSql.="ORDER BY empcode";

	//-- 
	//echo "od smaster -".$StaffMasterSql;
	   
	$StaffMasterRes = exequery($StaffMasterSql);
	while($StaffMasterRow = fetch($StaffMasterRes))
	{
		$qrysickleave = "SELECT sum(days)as days,empcode FROM LeaveTransaction where empcode='".$StaffMasterRow[0]."' and leavetype='OD' and  frmdate>='".$frmdate."' and frmdate<='".$todate."' ";
		//echo $qrysickleave;
		
		$ressickleave = exequery($qrysickleave);
		
		//echo "<br>qrysickleave-".$qrysickleave;
		while($rowsickleave = fetch($ressickleave))
		{		
			//echo "<br>emp-" .	$rowsickleave[1];
			$qry="select * from StaffMaster where empcode='".$rowsickleave[1]."'";
			//echo $qry;
			$resqry=exequery($qry);
			// $count=0;
			$rowqry=fetch($resqry);
			 
			$qryweekoff="select * from weekdays where id='".$rowqry['weeklyoff']."'";
			$resweekoff=exequery($qryweekoff);
			$rowweekoff=fetch($resweekoff);
			
			$branchm="select * from BranchMaster1 where branchid='".$rowqry['branch']."'";
			$resbranch=exequery($branchm);
			$rowb=fetch($resbranch);

			$region="select * from RegionMaster1 where regionid='".$rowb['region']."'";
			$reg=exequery($region);
			$rowregion=fetch($reg);

			$qrydept="select * from DepartmentMaster1 where departmentid='".$rowqry['deptid']."'";
			$resdept=exequery($qrydept);
			$rowdept=fetch($resdept);

			$data = "";
			$reason = "";
			$qryleave = "SELECT * FROM LeaveTransaction where  empcode='".$rowsickleave[1]."' and leavetype='OD' and  frmdate>='".$frmdate."' and frmdate<='".$todate."' order by frmdate desc";
			//echo $qryleave;
			//echo "<br>";
			
			$data = "";
			$days = 0;
			$totdays = 0;
			$daytot = 0;
			$resleave = exequery($qryleave);
			while($rowleave = fetch($resleave))
			{
				if($rowleave[4]=="OD")
				{
					//$datefrom = explode("-",'2018-02-11');
					//$dateto = explode("-",'2018-02-12');
					//$datefrom[2];
					
					$date1=date_create($rowleave[2]); //from
					$date2=date_create($rowleave[3]); //to
					$diff=date_diff($date1,$date2);
					//echo $diff->format("%R%a days"); 
					$daytot = (int)($diff->format("%a"));
					
					$datearr = explode('-', $rowleave[2]);
						
					
					if(++$daytot > 1)
						$data .= "<tr><td></td><td><b>".YMDtoDMY($rowleave[2])." (".$daytot." days)</b></td></tr>";
					else
						$data .= "<tr><td></td><td><b>".YMDtoDMY($rowleave[2])."</b></td></tr>";
					
					/*
					$dateabsent = explode("-", $rowleave[2]);
					
					$dataremk .= "<td><b>".$rowabsent[15]."</b></td>";*/
				}
				$days++;
				
				if($days==1)
					$totdays = $days - 1;
				else
					$totdays += $days;
			}
			if($days > 0)
			{
				echo "<div class='border'><center><table class='table table-striped table-bordered datatables' id='dynamic-table' style='width:80%'>";
				echo "<tr><td colspan='3'>Outdoor Duty list between <b>".$_POST['frmdate']." to ".$_POST['todate']."</b></td></tr>";
				
				echo "<tr><td colspan='3'> Emp Code / Name :- <b>".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."</b></td></tr>";
				
				echo "<tr><td colspan='3'>You have been marked outdoor on the following dates. Kindly forward OD/REQUISITE PERMISION within 3 days.</td></tr>";
				echo "<tr><td width='7%'>Details :- </td><td colspan='2'>";
					echo "<table class='table table-striped table-bordered datatables' id='tableinner' style='width:100%'>";
					echo "<tr><td width='7%'>Date :- </td></tr>".$data."";
					echo "<tr><td>Total :- ".$days."</td><td></td></tr></table>";
				echo "<tr><td colspan='3' style='text-align:right;'></td></tr>";
				echo "<tr><td colspan='3' style='text-align:left;'><b>Signature :- </b></td></tr>";
				echo "<tr><td colspan='3'></td></tr>";
				echo "</table></center></div><br>";	
			}
		}
	}
} //------- END OF OD

//------------------------- STATUS
if($_POST['misreport']=='status') 
{
	$time = mktime(0, 0, 0, $_POST['month']);
	$name = strftime("%b", $time);
	//echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'> </p></center><br>";
	$my = $_POST['year'].'-'.$_POST['month'];
	$month = $_POST['year']."-".$_POST['month']."-01";
	$lastday = date('t',strtotime($month));

	if($_POST['fromlocation']!="")
	{
		$branchqry = "select * from RegionMaster1 where regionid='".$_POST['fromlocation']."'";
		$branchres = exequery($branchqry);
		$branchrow = fetch($branchres);
		$branch = $branchrow[1];
	} else {
		$branch ='ALL';
	}
 
	if($_POST['fromdepartment']!="")
	{
		$departmentqry = "select * from DepartmentMaster1 where departmentid='".$_POST['fromdepartment']."'";
		$departmentres = exequery($departmentqry);
		$departmentrow = fetch($departmentres);
		$department = $departmentrow[1];
	} else {
		$department ='ALL';
	}
	   
	if($_POST['company']!="all")
	{
		$companyqry = "select * from EmpCompanyMaster where empcompanyid='".$_POST['company']."'";
		$companyres = exequery($companyqry);
		$companyrow = fetch($companyres);
		$company = $companyrow[1];
	} else {
		$company ='ALL';
	} //attendance while
    	   
	echo "<center> <p class='btn btn-warning' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";
	
	$frmdate = DMYtoYMD($_POST['frmdate']);
	$todate  = DMYTOYMD($_POST['todate']);
	$date=$_POST['year']."-".$_POST['month'];
	$fromempcode1 = explode(':',$_POST['fromempcode']);
	$fromempcode  = $fromempcode1[0];
	$toempcode1 = explode(':',$_POST['toempcode']);
	$toempcode  = $toempcode1[0];

	$diffday  = "SELECT TIMESTAMPDIFF(DAY, '".$frmdate."', '".$todate."')";
	$diffdayres = exequery($diffday);
	$diffdayrow = fetch($diffdayres);
	$days1 = $diffdayrow[0]+1;

	$StaffMasterSql = "select * from StaffMaster where lefts!=1 ";
	if(($fromempcode!='') && ($toempcode!=''))
		$StaffMasterSql.=" and  ((empcode BETWEEN ('".$fromempcode."') AND ('".$toempcode."')) )";
	if(($_POST['fromdepartment']!='') && ($_POST['todepartment']!=''))
		$StaffMasterSql.="and ((deptid BETWEEN ('".$_POST['fromdepartment']."') AND ('".$_POST['todepartment']."')) )";
	if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
		$StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
	if($_POST['company']!='all')
		$StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";

	//echo "Status=".$StaffMasterSql;
	$StaffMasterRes = exequery($StaffMasterSql);
	while($StaffMasterRow = fetch($StaffMasterRes))
	{
		$region="select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
		$resregion=exequery($region);
		$rowregion=fetch($resregion);

		$branch="select * from BranchMaster1 where branchid='".$StaffMasterRow['branch']."'";
		$resbranch=exequery($branch);
		$rowbranch=fetch($resbranch);

		$qrydept1 = "select * from DepartmentMaster1 where departmentid = '".$StaffMasterRow['deptid']."'";
		$resdept1 = exequery($qrydept1);
		$rowdept1 = fetch($resdept1);

		$weekdayQry = "select * from weekdays where id='".$StaffMasterRow[20]."'";
		$weekdayRes = exequery($weekdayQry);
		$weekdayRow = fetch($weekdayRes);

		$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
		$ShiftMasterRes = exequery($ShiftMasterSql);
		$rowshifttime = fetch($ShiftMasterRes);

		$shiftstarttime = $rowshifttime[4];
		$shiftendtime   = $ShiftMasterRow[7];
		$shifttotaltime = $ShiftMasterRow[8];


		echo "<center><div style='text-align:left;'></div></center>";
		echo '<center><table class="table table-bordered" border="1" style="width:75%">';
		
		
		echo '<tr>';
		echo "<td colspan='30'>Status List  between ".$_POST['frmdate']." to ".$_POST['todate']." Employee Code :- <b>".$StaffMasterRow[0]."</b> &nbsp;&nbsp;&nbsp;&nbsp; Employee Name :- <b>".$StaffMasterRow[1]."</b></td>";
		echo '<tr>';
		
		echo '<tr>';
		echo "<td style='color:black;font-size:16px;font-weight:bold;'>Date </td>";

		for($a=0;$a<$days1;$a++) 
		{
			$dateqry = "select DATE_ADD('".$frmdate."', interval '".$a."' day)";
			$dateres = exequery($dateqry);
			$daterow = fetch($dateres);
			$dates = explode('-',$daterow[0]);
			$day1 = $dates[2];
			echo "<td style='color:black;font-size:16px;font-weight:bold;'>D $day1</td>";
		}
		echo "<td style='color:black;font-size:16px;font-weight:bold;'>Total Work </td>";
		echo "</tr>";

		//first in is   -- ARRIVAL
		echo "<tr>";

		if($_POST['firstin']=='firstin') 
	 	{
	 		       	   
	 		     echo "<tr>";
	 		     echo "<td style='font-size:14px;font-weight:bold;'>Arrival</td>";
				for($i=0;$i<$days1;$i++) 
				{		         	
					if($i<10)
						$i="0".$i;
						
						$dateqry = "select DATE_ADD('".$frmdate."', interval '".$i."' day)";
						$dateres = exequery($dateqry);
						$daterow = fetch($dateres);
						$daytemp = $daterow[0];

						$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
						$resday = exequery($qryday);
						$rowday = fetch($resday);

						$LeaveSql = "select * from LeaveTransaction where empcode='".$StaffMasterRow[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
						$LeaveRes = exequery($LeaveSql);
						$LeaveRow = fetch($LeaveRes);


						$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$StaffMasterRow['region']."' ";
						$HolidayMasterRes = exequery($HolidayMasterSql);
						$HolidayMasterRow = fetch($HolidayMasterRes);
						//echo $HolidayMasterSql;
						$qryattendance = "select * from Attendance where date = '".$daytemp."' and empcode = '".$StaffMasterRow[0]."' ";
						$resattendance = exequery($qryattendance);
						$rowattendance = fetch($resattendance);

						if($rowattendance!=NULL)
						{
							if($rowattendance[4]!=NULL)
							{
								echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[4]</td>";
							} else if($LeaveRow!=NULL) {
								echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
							} else {
								echo"<td style='color:red;font-size:12px;font-face:verdana;'></td>";
							}
						}
						else if($rowday[0]==$weekdayRow[1])
						{	
						   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
						}
						else if($LeaveRow!=NULL)
						{
							echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
						}
						else if($HolidayMasterRow!=NULL)
						{
							echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
						} 
						else 
						{
						   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
						}
					}  
				}
		echo '<td></td>';
		echo "</tr>";

		//first out is   -- DEPARTURE
		echo "<tr>";

		if($_POST['firstout']=='firstout') 
	 	{
			echo "<tr>";
			echo "<td style='font-size:14px;font-weight:bold;'>Departure</td>";
		    for($i=0;$i<$days1;$i++) 
			{
				if($i<10)
					$i="0".$i;

				$dateqry = "select DATE_ADD('".$frmdate."', interval '".$i."' day)";
				$dateres = exequery($dateqry);
				$daterow = fetch($dateres);
				$daytemp = $daterow[0];

				$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
				$resday = exequery($qryday);
				$rowday = fetch($resday);

				$LeaveSql = "select * from LeaveTransaction where empcode='".$StaffMasterRow[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
				$LeaveRes = exequery($LeaveSql);
				$LeaveRow = fetch($LeaveRes);


				$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$StaffMasterRow['region']."' ";
				$HolidayMasterRes = exequery($HolidayMasterSql);
				$HolidayMasterRow = fetch($HolidayMasterRes);

				$qryattendance = "select * from Attendance where date = '".$daytemp."' and empcode = '".$StaffMasterRow[0]."' ";
				$resattendance = exequery($qryattendance);
				$rowattendance = fetch($resattendance);

				if($rowattendance!=NULL)
				{
					if($rowattendance[13]!=NULL) {
						echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[13]</td>";
					}
					else if( $rowattendance[12]!=NULL )
					{
						echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[12]</td>";
					}else if( $rowattendance[11]!=NULL )
					{
						echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[11]</td>";
					}else if( $rowattendance[10]!=NULL )
					{
						echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[10]</td>";
					}else if( $rowattendance[9]!=NULL )
					{
						echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[9]</td>";
					}else if( $rowattendance[8]!=NULL )
					{
						echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[8]</td>";
					}else if( $rowattendance[7]!=NULL )
					{
					echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[7]</td>";
					} else if($rowattendance[6]!=NULL )
					{
					echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[6]</td>";
					} else if($rowattendance[5]!=NULL )
					{
						echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[5]</td>";
					}
					else if($LeaveRow!=NULL)
					{
						echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
					}
					else 
					{
						echo"<td style='color:red;font-size:12px;font-face:verdana;'></td>";
					}
				}
				else if($rowday[0]==$weekdayRow[1])
				{	
				   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
				}
				else if($LeaveRow!=NULL)
				{
					echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
				}
				else if($HolidayMasterRow!=NULL)
				{
					
					echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
				}									
				else
				{
				   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
				}
			}
		}
		echo '<td></td>';
		echo "</tr>";

		echo "<tr>";
		
		$Arrival = $attencerow['p1'];
		
		$LateSql = "select TIMEDIFF ('".$Arrival."','09:30')";
		$LateRes = exequery($LateSql);
		$LateRow = fetch($LateRes);
	 
		$template = substr($LateRow[0],0,1); // substr(string, startindex, endindex)
		if($template!='-'){
			$late = substr($LateRow[0],0,5);
		}
		else {
			$late = substr($LateRow[0],0,6);
		}
		
		
		$EarlySql = "select TIMEDIFF ('17:30','".$Departure."')";
		$EarlyRes = exequery($EarlySql);
		$EarlyRow = fetch($EarlyRes);
				 
		 $tempearly = substr($EarlyRow[0],0,1); // substr(string, startindex, endindex)
		if($tempearly!='-'){
			$early = substr($EarlyRow[0],0,5);
		}
		else {
			$early = substr($EarlyRow[0],0,6);
		}
				
		$OvertimeSql = "select TIMEDIFF ('".$Departure."','".$Arrival."')";
		$OvertimeRes = exequery($OvertimeSql);
		$OvertimeRow = fetch($OvertimeRes);
				 
			$tempovertime = substr($OvertimeRow[0],0,1);
			if($tempovertime!='-'){
				 $worktime = substr($OvertimeRow[0],0,5);
			}
			else{
				$worktime = substr($OvertimeRow[0],0,6);
			}
		
		
		
		
		$totalwork = 0;
		$toatlmin = 0;

		$hours  = floor($toatlmin/60); //round down to nearest minute. 
		$total = $totalwork + $hours;
		$minutes = $toatlmin % 60;
		if($minutes<9) {
			$minutes1 = "0".$minutes;
		} else {
			$minutes1 = $minutes;
		}
		$totalwork = $total.":".$minutes1;
		echo '<td>'.$totalwork.'</td></tr>';

	echo '</table></center>';	
    }
}

?>  	
<input class="span4" id="frmdate" name="frmdate" type="hidden" placeholder="Select date" value="<? echo $_POST['frmdate'] ?>">
<input class="span4" id="todate" name="todate" type="hidden" placeholder="Select date" value="<? echo $_POST['todate'] ?>" >
<input type="hidden" name="misreport"  value="<? echo $_POST['misreport'] ?>">
<input type="hidden" name="month" id="month" value="<? echo $_POST['month'] ?>">
<input type="hidden" name="year" id="year" value="<? echo $_POST['year'] ?>">
<input type="hidden" name="fromempcode" id="fromempcode" value="<? echo $_POST['fromempcode'] ?>">
<input type="hidden" name="toempcode" id="toempcode" value="<? echo $_POST['toempcode'] ?>">
<input type="hidden" name="fromlocation" id="fromlocation" value="<? echo $_POST['fromlocation'] ?>">
<input type="hidden" name="tolocation" id="tolocation" value="<? echo $_POST['tolocation'] ?>">
<input type="hidden" name="fromdepartment" id="fromdepartment" value="<? echo $_POST['fromdepartment'] ?>">
<input type="hidden" name="todepartment" id="todepartment" value="<? echo $_POST['todepartment'] ?>">
<input type="hidden" name="company" id="company" value="<? echo $_POST['company'] ?>">
<input  type="hidden"  id="firstin" name="firstin" value="<? echo $_POST['firstin'] ?>">
<input  type="hidden"  id="firstout" name="firstout"  value="<? echo $_POST['firstout'] ?>" >
<input  type="hidden"  id="secondin" name="secondin"   value="<? echo $_POST['secondin'] ?>">
<input  type="hidden"  id="secondout" name="secondout"   value="<? echo $_POST['secondout'] ?>">
</form>	
<?

// die the script here
die();
}
?>
<body>

<div id="page-content">
	<div id="wrap">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-primary">
				    <div class="panel-heading"><h4>Attendance Report</h4></div>

						<div class="panel-body" >
                            <div class="">

<script type="text/javascript">
$(document).ready(function () {
	$('#display').show();
	$('#display1').hide();
	$('#display2').hide();

	//if(this.value!= "empall")
	{
	$('#performance').click(function () {
		$('#display').show('fast');
		$('#display1').hide('fast');
		$('#display2').hide('fast');
	});
	$('#absent').click(function () {
		$('#display').hide('fast');
		$('#display1').show('fast');
		$('#display2').hide('fast');
	});
	$('#latearrival').click(function () {
		$('#display').hide('fast');
		$('#display1').show('fast');
		$('#display2').hide('fast');
	});
	$('#earlydep').click(function () {
		$('#display').hide('fast');
		$('#display1').show('fast');
		$('#display2').hide('fast');
	});
	$('#overtime').click(function () {
		$('#display').hide('fast');
		$('#display1').show('fast');
		$('#display2').hide('fast');
	});
	$('#od').click(function () {
		$('#display').hide('fast');
		$('#display1').show('fast');
		$('#display2').hide('fast');
	});
	$('#monthpresent').click(function () {
		$('#display').hide('fast');
		$('#display1').show('fast');
		$('#display2').hide('fast');
	});
	$('#ab').click(function () {
		$('#display').hide('fast');
		$('#display1').show('fast');
		$('#display2').hide('fast');
	});
	$('#status').click(function () {
		$('#display').hide('fast');
		$('#display1').show('fast');
		$('#display2').show('fast');
	});
	$('#improper').click(function () {
		$('#display').hide('fast');
		$('#display1').show('fast');
		$('#display2').hide('fast');
	});
	} //else 
	{
		$('#empall').click(function () {
			$('#display').hide('fast');
			$('#display1').hide('fast');
			$('#display2').hide('fast');
		});
	}
});

$(function() {
	$('#frmdate').datepicker({dateFormat: 'dd-mm-yy'});
	$('#todate').datepicker({dateFormat: 'dd-mm-yy'});
});
</script>

	<form action="attchkreport.php" method="post" enctype="multipart/form-data" class="form-horizontal ">
<table class="table">
	<tr>
	<td><input  type="radio"  id="performance" name="misreport" value="performance" checked> <label for="performance"> Performance</label></td>
	<td><input  type="radio"  id="absent" name="misreport" value="absent" > <label for="absent"> Absent</label></td>
	<td><input  type="radio"  id="ab" name="misreport" value="ab" > <label for="ab"> No Punch</label></td>
	<td><input  type="radio"  id="latearrival" name="misreport" value="latearrival" > <label for="latearrival"> Late Arrival</label></td>
	<td><input  type="radio"  id="earlydep" name="misreport" value="earlydep" > <label for="earlydep"> Early Departure</label></td>
	<!-- <td><input  type="radio"  id="overtime" name="misreport" value="overtime" >Over Time</td> -->
	<td><input  type="radio"  id="od" name="misreport" value="od"> <label for="od"> OD</label></td>
	<td><input  type="radio"  id="status" name="misreport" value="status" > <label for="status"> Status</label></td>
	<!-- <td>Improper<input  type="radio"  id="improper" name="misreport" value="improper" ></td>-->
	<!-- <tr><td>Monthly Present <input  type="radio"  id="monthpresent" name="misreport" value="monthpresent" ></td></tr> -->
	</tr>
</table>

<table class="table"> 
	<tr id='display'>                                    
		<td>Month</td>
		<td>
			<select class="form-control"  style="width:100px;" id="month" name="month" >
			<option value="<? echo date('m'); ?>"><? echo date('M'); ?></option>
			<option value="01">Jan</option>
			<option value="02">Feb</option>
			<option value="03">Mar</option>
			<option value="04">Apr</option>
			<option value="05">May</option>
			<option value="06">Jun</option>
			<option value="07">Jul</option>
			<option value="08">Aug</option>
			<option value="09">Sep</option>
			<option value="10">Oct</option>
			<option value="11">Nov</option>
			<option value="12">Dec</option>
			</select>
		</td>
		<td>Year</td>
		<td><input class="form-control"  style="width:70px;" id="year" name="year" type="text"  value="<? echo date('Y'); ?>"></td>
	</tr>

<tr id='display1' style="visible:hidden;">
<td> From Date</td>
	<td><input class="form-control" style="width:110px;" id="frmdate" name="frmdate" type="text" placeholder="Select date" value="<? echo date('d-m-Y');?>" ></td>	
<td> To Date</td>
	<td><input class="form-control" style="width:110px;" id="todate" name="todate" type="text" placeholder="Select date" value="<? echo date('d-m-Y');?>" ></td>	
</tr> 

<tr id='display2' style="visible:hidden;">
	<td>In <input  type="checkbox"  id="firstin" name="firstin" value="firstin" checked></td>
	<td>Out <input  type="checkbox"  id="firstout" name="firstout" value="firstout" ></td><td></td><td></td>
</tr>

<tr>
	<td>From Employee</td>
	<td> <input  type="text" class='form-control' style='width:300px' id="fromempcode" name="fromempcode"  onclick="toempcode1();" onchange="toempcode1();" > </td>
	<td>To Employee</td>
	<td> <input  type="text" class='form-control' style='width:300px'  id="toempcode" name="toempcode"> </td>
</tr>


<tr>
	<td>From Department</td>
	<td>
	<select name='fromdepartment' id='fromdepartment' class='form-control' style='width:300px' onclick="todepartment1();" onchange="todepartment1();" >
	<option value=''>select</option>
	<?
		$DepartmentSql = "select * from DepartmentMaster1 order by departmentid asc" ;
		$DepartmentRes = exequery($DepartmentSql);
		while($DepartmentRow = fetch($DepartmentRes))
		{
		   echo "<option value=".$DepartmentRow[0].">".$DepartmentRow[0].":".$DepartmentRow[1]."</option>";
		}
	?>
	</select>
	</td>

	<td>To Department</td>
	<td>
	<select name='todepartment' id='todepartment' class='form-control' style='width:300px'>
	<option value=''>select</option>
	<?
		$DepartmentSql = "select * from DepartmentMaster1 order by departmentid desc";
		$DepartmentRes = exequery($DepartmentSql);
		while($DepartmentRow = fetch($DepartmentRes))
		{
		   echo "<option value=".$DepartmentRow[0].">".$DepartmentRow[0].":".$DepartmentRow[1]."</option>";
		}
	?>
	</select>
	</td>
</tr>
<tr>
<td>Company</td>
	<td>
	  <select name='company' id='company' class='form-control' style='width:300px'>
		  <option value='all'>All</option>
		<?
		   $CompanySql = "select * from EmpCompanyMaster";
			$CompanyRes = exequery($CompanySql);
			while($CompanyRow = fetch($CompanyRes))
			{
			   echo "<option value=".$CompanyRow[0].">".$CompanyRow[1]."</option>";
			
			}
		
		?>
	  </select>
	</td>
	<TD></TD>
	<TD></TD>
</tr>
</table>
<center><input class="btn btn-info" type="submit" name="action" value="Generate" /></center>
</form>

							</div>
					</div>
				</div>
			</div>
			</div> <!-- row -->
		</div> <!-- container -->
	</div> <!-- wrap -->
</div> <!-- page-content -->

<?php include "../footer.php" ?>

<script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
<script src="../js/bootstrap.datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="../jqauto/jquery.autocomplete.css" />
<script type="text/javascript" src="../jqauto/jquery.autocomplete.js"></script>
<script type="text/javascript">
	var jQuery_1_8_2 = $.noConflict(true);
	jQuery_1_8_2(document).ready(function(){
		jQuery_1_8_2("#empcode").autocomplete("empsearchfetch.php", {
			selectFirst: true
		});
	});

	jQuery_1_8_2(document).ready(function(){
		jQuery_1_8_2("#fromempcode").autocomplete("empsearchfetch.php",{
			selectFirst: true
		});
	});
	  
	jQuery_1_8_2(document).ready(function(){
		jQuery_1_8_2("#toempcode").autocomplete("empsearchfetch.php", {
			selectFirst: true
		});
							
	});
</script>