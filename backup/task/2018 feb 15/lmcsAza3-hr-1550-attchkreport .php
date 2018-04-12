<?php
include('db.php');
    
      /*
		PHP Name : attchkreport.php
		Created Date : 24-03-2017
		Created By : Deepa Dharennavar
		Description : This is Attendance Report
	*/	
	
?>
<? include('header.php');?>
<title>MIS Report</title>
<link href="../css/jquery-ui.css" rel="stylesheet">
	<script src="../js/jquery-1.10.2.js"></script>
	<script src="../js/jquery-ui.js"></script>
	
  <script>
		$(function() {
				$("#tabs").tabs();
			});
			
			function toempcode1() 
			{
				//alert('cmg');
				empcode = $('#fromempcode').val();
				
				$('#toempcode').val(empcode);
			}
			
			function todepartment1() 
			{
				//alert('cmg');
				fromdepartment = $('#fromdepartment').val();
				
				$('#todepartment').val(fromdepartment);
			}
			
				function tolocation1() 
			{
				//alert('cmg');
				fromlocation = $('#fromlocation').val();
				
				$('#tolocation').val(fromlocation);
			}
			
 </script>
 
		
 
 <?
 
if($_POST['action']=="Generate") 
 {
 	 echo "<br><a href='attchkreport.php'><input class='btn btn-primary' type='button'  value='Back' style='font-color:white;font-weight:bold;font-size:16px;float:left'/></a>";	
	echo '<form action="attchkreportexcel.php" method="POST"><br><span style="float:right;"><input class="btn btn-primary" style="font-weight:bold" type="submit" name="Action" id="Action" value="Generate as Excel"></span> ';
	if($_POST['misreport']=='performance') 
	{
		  $time = mktime(0, 0, 0, $_POST['month']);
		  $name = strftime("%b", $time);
		  echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'>Performance List  For the Month ".$name." And Year ".$_POST['year']."</p></center><br>";
		  $start = microtime(true);		
		$firstday = 0;
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
		   //echo $StaffMasterSql ;
	   
		   if(($_POST['fromdepartment']!='') && ($_POST['todepartment']!=''))
				  $StaffMasterSql.="and ((deptid BETWEEN ('".$_POST['fromdepartment']."') AND ('".$_POST['todepartment']."')) )";
		   
		//   if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
		 //        $StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
		   
		   
		   if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
				 $StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
		   
		   if(($_POST['frombranch']!='') && ($_POST['tobranch']!=''))
			$StaffMasterSql.="and ((branch BETWEEN ('".$_POST['frombranch']."') AND ('".$_POST['tobranch']."')) )";	
		
		   
		   if($_POST['company']!='all')
				 $StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";
		   
		   
				 //$StaffMasterSql.=" group by empcode   order by empcode desc ";
		  //sql has been displayed echo $StaffMasterSql;
		   $StaffMasterRes = exequery($StaffMasterSql);
		   while($row1 = fetch($StaffMasterRes))
		   {
			
			$DepartmentMatserSql = "select * from DepartmentMaster1 where departmentid ='".$row1['deptid']."'";
			$DepartmentMatserRes = exequery($DepartmentMatserSql);
			$DepartmentMatserRow = fetch($DepartmentMatserRes);
			
				$weekdayQry = "select * from weekdays where id='".$row1[20]."'";
			   $weekdayRes = exequery($weekdayQry);
			   $weekdayRow = fetch($weekdayRes);
				 
				 echo   '<tr><td colspan="32"><center><span class="btn btn-warning" style="font-weight:bold;fontsize:15px;"> Empcode : '.$row1[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name : '.$row1[1].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Department : '.$DepartmentMatserRow[1].'</center></span></td></tr><br>';


			echo "<table  class='table table-bordered'  style='width:100%'>";    	    
			echo "<tr bgcolor='#1FB5AD'>";
	   
			echo "<td style='color:white;font-size:16px;font-weight:bold;'>DATE</td>";
		  
			 $month = $_POST['year']."-".$_POST['month']."-01";
			 $lastday = date('t',strtotime($month));
			   //echo $lastday ;
			   
			   for($i=1;$i<=$lastday;$i++) 
			   {
		 
					echo  "<td style='color:white;font-size:16px;font-weight:bold;'>".$i."</td>";
			   }
		  
		  
		  
			
		  echo "<td style='color:white;font-size:16px;font-weight:bold;'>Total Work</td>";
		  echo '</tr><tr><td>Arrival</td>';
			   //echo $lastday ;
			   for($i=1;$i<=$lastday;$i++) 
			   {
							if($i<10)
									$i="0".$i;
								 $daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									
									$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
									$resday = exequery($qryday);
									$rowday = fetch($resday);
									
									$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
							  $ShiftMasterRes = exequery($ShiftMasterSql);
							  $rowshifttime = fetch($ShiftMasterRes);
				  
							  $shiftstarttime = $rowshifttime[4];
							  $shiftendtime   = $ShiftMasterRow[7];
							  $shifttotaltime = $ShiftMasterRow[8];
								
									$LeaveSql = "select * from LeaveTransaction where empcode='".$row1[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
									$LeaveRes = exequery($LeaveSql);
									$LeaveRow = fetch($LeaveRes);
									

									
									
							  $HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$row1['region']."' ";
							   $HolidayMasterRes = exequery($HolidayMasterSql);
							   $HolidayMasterRow = fetch($HolidayMasterRes);
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									if($rowattendance!=NULL)
									{ 
									   if($rowattendance[3]!=null )
									   {
										   echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[3]</td>";
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
							echo '<td></td></tr>';
							
							
					echo '<tr><td>Rest Out</td>';
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									if($rowattendance!=NULL)
									{
										if($rowattendance[4]!=NULL)
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
							
					echo '<tr><td>Rest In</td>';
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									if($rowattendance!=NULL)
									{
										 if($rowattendance[5]!=NULL)
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
							echo '<td></td></tr>';
							echo '<tr><td>Departure</td>';
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									if($rowattendance!=NULL)
									{
										if($rowattendance[6]!=NULL)
										{
										echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[6]</td>";
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
									
									
							  $HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$row1['region']."' ";
							   $HolidayMasterRes = exequery($HolidayMasterSql);
							   $HolidayMasterRow = fetch($HolidayMasterRes);
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
							  $ShiftMasterRes = exequery($ShiftMasterSql);
							  $rowshifttime = fetch($ShiftMasterRes);
				  
							  $shiftstarttime = $rowshifttime[4];
							  $shiftendtime   = $ShiftMasterRow[7];
							  $shifttotaltime = $ShiftMasterRow[8];
									
									if(strtotime($rowattendance[3])>strtotime($rowshifttime[4]))
									{
										$qrytimediff = "SELECT TIMEDIFF('$daytemp $rowattendance[3]','$daytemp $rowshifttime[4]')";
										$restimediff = exequery($qrytimediff);
										$rowtimediff = fetch($restimediff);
										
											
										$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','$daytemp 00:11:00')";
										$restimediff1 = exequery($qrytimediff1);
										$rowtimediff1 = fetch($restimediff1);
										$tempdata = substr($rowtimediff1[0],0,1);
									if($tempdata!='-')
										echo "<td style='color:red;font-size:14px;font-face:verdana;'>".substr($rowtimediff[0],0,5)."</td>";
									else if(strtotime($rowattendance[3])<strtotime($rowshifttime[4]))
										echo"<td style='color:black;font-size:12px;font-face:verdana;'></td>";
									else
										echo"<td style='color:black;font-size:12px;font-face:verdana;'></td>";
									}
									else if(strtotime($rowattendance[3])<strtotime($rowshifttime[4]) && strtotime($rowattendance[3])!=strtotime($rowshifttime[4]) && $rowattendance!=null)
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
									   echo"<td style='color:red;font-size:12px;font-face:verdana;'></td>";
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
							  $ShiftMasterRes = exequery($ShiftMasterSql);
							  $rowshifttime = fetch($ShiftMasterRes);
				  
							  $shiftstarttime = $rowshifttime[4];
							  $shiftendtime   = $ShiftMasterRow[7];
							  $shifttotaltime = $ShiftMasterRow[8];
									
									if(strtotime($rowattendance[6])<strtotime($rowshifttime[7]))
									{
										$qrytimediff = "SELECT TIMEDIFF('$daytemp $rowshifttime[7]','$daytemp $rowattendance[6]')";
										$restimediff = exequery($qrytimediff);
										$rowtimediff = fetch($restimediff);
										
										
										$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','$daytemp 00:11:00')";
										$restimediff1 = exequery($qrytimediff1);
										$rowtimediff1 = fetch($restimediff1);
										$tempdata = substr($rowtimediff1[0],0,1);
									if($tempdata!='-')
										echo "<td style='color:red;font-size:14px;font-face:verdana;'>".substr($rowtimediff[0],0,5)."</td>";
									else if(strtotime($rowattendance[6])>strtotime($rowshifttime[7]))
										echo"<td style='color:black;font-size:12px;font-face:verdana;'></td>";
									else
										echo"<td style='color:black;font-size:12px;font-face:verdana;'></td>";
									}
									else if($rowattendance!=null)
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
						
						   echo '<tr><td>Lunch Break</td>';
						$totallunchwork = 0;
						$toatllunchmin = 0; 
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									$date = date('Y-m-d');
									if($rowattendance[3]!=null || $rowattendance[3]!='') 
							   {
								  $firsthalfstart1 = $rowattendance[3];
							   }
							  else 
							   {
									 $firsthalfstart1 = '00:00:00';
							   }
							  if($rowattendance[4]!=null || $rowattendance[4]!='') 
							   {
								  $firsthalfend1 = $rowattendance[4];
							   }
							  else 
							   {
								 $firsthalfend1 = '00:00:00';
							   } 
							 if($rowattendance[5]!=null || $rowattendance[5]!='') 
							  {
								 $secondhalfstart1 = $rowattendance[5];
							  }
							else 
							  {
								 $secondhalfstart1 = '00:00:00';
							  }
							 if($rowattendance[6]!=null || $rowattendance[6]!='') 
							 {
								$secondhalfend1 = $rowattendance[6];
							 }
						   else 
							{
							   $secondhalfend1 = '00:00:00';
							}


									$LunchHrSql = "select TIMEDIFF ('".$secondhalfstart1."','".$firsthalfend1."')";
								$LunchHrRes = exequery($LunchHrSql);
								$LunchHrRow = fetch($LunchHrRes);


								
								$temworkhrs = substr($LunchHrRow[0],0,1);
								if($temworkhrs!='-')
								{
									$totallunchworkhrs = substr($LunchHrRow[0],0,5);
								}
									else 
									{
										$totallunchworkhrs = substr($LunchHrRow[0],0,6);
										
									}
										$totallunchhrs1  =  explode(':',$LunchHrRow[0]);
										$totallunchwork = $totallunchwork+$totallunchhrs1[0];
										$toatllunchmin = $toatllunchmin+$totallunchhrs1[1];	
									
									if($rowattendance!=NULL)
									{
								
										echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$totallunchworkhrs."</td>";
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
									$hours1  = floor($toatllunchmin/60); //round down to nearest minute. 
									$totallunch = $totallunchwork + $hours1;
									$minuteslunch = $toatllunchmin % 60;
									$lunchtotalwork = $totallunch.":".$minuteslunch;
					echo '<td>'.$lunchtotalwork.'</td></tr>';
					
					   echo '<tr><td>Work Hrs</td>';
						$totalwork = 0;
						$toatlmin = 0;
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									$date = date('Y-m-d');
									if($rowattendance[3]!=null || $rowattendance[3]!='') 
							   {
								  $firsthalfstart1 = $rowattendance[3];
							   }
							  else 
							   {
									 $firsthalfstart1 = '00:00:00';
							   }
							  if($rowattendance[4]!=null || $rowattendance[4]!='') 
							   {
								  $firsthalfend1 = $rowattendance[4];
							   }
							  else 
							   {
								 $firsthalfend1 = '00:00:00';
							   } 
							 if($rowattendance[5]!=null || $rowattendance[5]!='') 
							  {
								 $secondhalfstart1 = $rowattendance[5];
							  }
							else 
							  {
								 $secondhalfstart1 = '00:00:00';
							  }
							 if($rowattendance[6]!=null || $rowattendance[6]!='') 
							 {
								$secondhalfend1 = $rowattendance[6];
							 }
						   else 
							{
							   $secondhalfend1 = '00:00:00';
							}
							 $FirstHalfSql = "select TIMEDIFF ('".$firsthalfend1."','".$firsthalfstart1."')";
							  $FirstHalfRes = exequery($FirstHalfSql);
							  $FirstHalfRow = fetch($FirstHalfRes);

									$SecondHalfSql = "select TIMEDIFF ('".$secondhalfend1."','".$secondhalfstart1."')";
								$SecondHalfRes = exequery($SecondHalfSql);
								$SecondHalfRow = fetch($SecondHalfRes);

							$TotalWorkHoursSql = "select ADDTIME ('".$FirstHalfRow[0]."','".$SecondHalfRow[0]."')";
								$TotalWorkHoursRes = exequery($TotalWorkHoursSql);
								$TotalWorkHoursRow = fetch($TotalWorkHoursRes);
								
								$temworkhrs = substr($TotalWorkHoursRow[0],0,1);
								if($temworkhrs!='-')
								{
									$totalworkhrs1 = substr($TotalWorkHoursRow[0],0,5);
								}
									else 
									{
										$totalworkhrs1 = substr($TotalWorkHoursRow[0],0,6);
										
									}
										$total  =  explode(':',$TotalWorkHoursRow[0]);
										$totalwork = $totalwork+$total[0];
										$toatlmin = $toatlmin+$total[1];	
									
									if($rowattendance!=NULL)
									{
								
										echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$totalworkhrs1."</td>";
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
									$hours  = floor($toatlmin/60); //round down to nearest minute. 
									$total = $totalwork + $hours;
									$minutes = $toatlmin % 60;
									$totalwork = $total.":".$minutes;
									$actualworkhrs= 26*7;
					echo '<td>'.$totalwork.' <br>-----------<br> <font color="green">'.$actualworkhrs.'</font></td></tr>';
					
					echo '<tr><td>Over Time</td>';
						$totalothrs = 0;
						$totalotmin = 0;
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
							  $ShiftMasterRes = exequery($ShiftMasterSql);
							  $rowshifttime = fetch($ShiftMasterRes);
							  
									$date = date('Y-m-d');
									if($rowattendance[3]!=null || $rowattendance[3]!='') 
							   {
								  $firsthalfstart1 = $rowattendance[3];
							   }
							  else 
							   {
									 $firsthalfstart1 = '00:00:00';
							   }
							  if($rowattendance[4]!=null || $rowattendance[4]!='') 
							   {
								  $firsthalfend1 = $rowattendance[4];
							   }
							  else 
							   {
								 $firsthalfend1 = '00:00:00';
							   } 
							 if($rowattendance[5]!=null || $rowattendance[5]!='') 
							  {
								 $secondhalfstart1 = $rowattendance[5];
							  }
							else 
							  {
								 $secondhalfstart1 = '00:00:00';
							  }
							 if($rowattendance[6]!=null || $rowattendance[6]!='') 
							 {
								$secondhalfend1 = $rowattendance[6];
							 }
						   else 
							{
							   $secondhalfend1 = '00:00:00';
							}
							 $FirstHalfSql = "select TIMEDIFF ('".$firsthalfend1."','".$firsthalfstart1."')";
							  $FirstHalfRes = exequery($FirstHalfSql);
							  $FirstHalfRow = fetch($FirstHalfRes);

									$SecondHalfSql = "select TIMEDIFF ('".$secondhalfend1."','".$secondhalfstart1."')";
								$SecondHalfRes = exequery($SecondHalfSql);
								$SecondHalfRow = fetch($SecondHalfRes);

							$TotalWorkHoursSql = "select ADDTIME ('".$FirstHalfRow[0]."','".$SecondHalfRow[0]."')";
								$TotalWorkHoursRes = exequery($TotalWorkHoursSql);
								$TotalWorkHoursRow = fetch($TotalWorkHoursRes);
								
								$temworkhrs = substr($TotalWorkHoursRow[0],0,1);
								if($temworkhrs!='-')
								{
									$totalworkhrs1 = substr($TotalWorkHoursRow[0],0,5);
								}
									else 
									{
										$totalworkhrs1 = substr($TotalWorkHoursRow[0],0,6);
										
									}
										
									$qryovertime = "select TIMEDIFF('".$totalworkhrs1."','".$rowshifttime[8]."')";
									$resovertime = exequery($qryovertime);
									$rowovertime = fetch($resovertime);


										
									if($rowattendance!=NULL)
									{
								
										echo"<td style='color:black;font-size:12px;font-face:verdana;'>".substr($rowovertime[0],0,5)."</td>";
									   
									   $totalot  =  explode(':',$rowovertime[0]);
										$totalothrs = $totalothrs+$totalot[0];
										$totalotmin = $totalotmin+$totalot[1];	
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
									$hoursot  = floor($totalotmin/60); //round down to nearest minute. 
									$totalothrs1 = $totalothrs + $hoursot;
									$totalotmin1 = $totalotmin % 60;
									$totalotwork = $totalothrs1.":".$totalotmin1;
					echo '<td>'.$totalotwork.'</td></tr>';
					
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									if($rowattendance!=NULL)
									{
								
										echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$rowattendance[16]."".$rowattendance[17]."</td>";
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
			echo '</table>';
	 }
	}

	if($_POST['misreport']=='absent') 
	{
	  $time = mktime(0, 0, 0, $_POST['month']);
		  $name = strftime("%b", $time);
		  echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'>Absent List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";

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
	   }//attendance while
		   
	echo  "<center> <p class='btn btn-info' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";
	echo  '<center><table class="table table-bordered" border="1" style="width:75%">';
	echo  "<tr bgcolor='#A48AD4'>";
	echo	'<th style="color:white;font-size:16px;"> Emp Code</th> '; 
	echo	'<th style="color:white;font-size:16px;"> Employee Details </th>';
	echo  '<th style="color:white;font-size:16px;"> Date </th>';
	echo  '<th style="color:white;font-size:16px;">No. of Days</th>';
	echo  "</tr>";

	$frmdate = DMYtoYMD($_POST['frmdate']);
	$todate  = DMYTOYMD($_POST['todate']);
	$date=$_POST['year']."-".$_POST['month'];
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

	
	//echo "<tr><td>".$StaffMasterSql."</td></tr>";
			
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
				
				$qryabsent = "select *   from Attendancechk where attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'   and empcode = '".$StaffMasterRow[0]."' and firsthalfstatus='A' and secondhalfstatus='A' order by attendancedate desc";
				//echo $qryabsent;
				$resabsent = exequery($qryabsent);
				$inccnt=0;
				while($rowabsent = fetch($resabsent))
				 {
						$data = YMDtoDMY($rowabsent[2])." , ".$data;
						$days++;
				 }
				 if($days>0)
				 {
							echo"<tr>
									<td>".$StaffMasterRow[0]."</td>
									<td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td>";
									echo"<td>".$data."</td>";
									echo "<td>".$days."</td>";
							echo"</tr>";	
				 }
		   }
			echo "</table></center><br>";	
		}
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
	   }//attendance while
		   
	echo  "<center> <p class='btn btn-info' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";
	echo  '<center><table class="table table-bordered" border="1" style="width:75%">';
	echo  "<tr bgcolor='#A48AD4'>";
	echo	'<th style="color:white;font-size:16px;"> Emp Code</th> '; 
	echo	'<th style="color:white;font-size:16px;"> Employee Details </th>';
	echo  '<th style="color:white;font-size:16px;"> Date </th>';
	echo  '<th style="color:white;font-size:16px;">No. of Days</th>';
	echo  "</tr>";

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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$StaffMasterRow[0]."' ";
									//echo $qryattendance;
									//echo '<br>';
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									if(($rowattendance==null) && ($rowday[0]!=$weekdayRow[1]) && ($LeaveRow==NULL) && ($HolidayMasterRo==NULL))
									{
										$flag = 0;
										$data = YMDtoDMY($daytemp)." , ".$data;
										$days++;	
											
									   // echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$rowattendance[16]."".$rowattendance[17]."</td>";
									}
																	

								
								}
			if($days>0)
				 {
							echo"<tr>
									<td>".$StaffMasterRow[0]."</td>
									<td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td>";
									echo"<td>".$data."</td>";
									echo "<td>".$days."</td>";
							echo"</tr>";	
				 }	
		   }
			echo "</table></center><br>";	
		}
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
	   }//attendance while
		   
	echo  "<center> <p class='btn btn-warning' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";
	echo  '<center><table class="table table-bordered" border="1" style="width:75%">';
								echo "<tr bgcolor='sky blue'><th style='color:white;'>Emp Code</th>
								<th style='color:white;'>Employee Details</th>
								<th style='color:white;'>Date</th>
								<th style='color:white;'>No. of Days</th></tr>";

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
									$qrylatearrival = "select * from Attendancechk where attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'   and empcode = '".$StaffMasterRow[0]."'  ";
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
									
									
									
									while($rowlatearrival = fetch($reslatearrival))
									{
										$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$StaffMasterRow['shiftid']."'";
									$ShiftMasterRes = exequery($ShiftMasterSql);
								$rowshifttime = fetch($ShiftMasterRes);
										
										$qrydifference = "select TIMEDIFF('".$rowlatearrival[3]."','".$rowshifttime[4]."')";
										//echo $qryshifttime."<br>";
										$resdifference = exequery($qrydifference);
										$rowdifference = fetch($resdifference);
										
									if(strtotime($rowlatearrival[3])>strtotime($rowshifttime[4]))
									{
										$qrytimediff = "SELECT TIMEDIFF('$daytemp $rowlatearrival[3]','$daytemp $rowshifttime[4]')";
										$restimediff = exequery($qrytimediff);
										$rowtimediff = fetch($restimediff);
										
											
										$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','$daytemp 00:11:00')";
										$restimediff1 = exequery($qrytimediff1);
										$rowtimediff1 = fetch($restimediff1);
										$tempdata = substr($rowtimediff1[0],0,1);
										
									 
										//echo "".substr($rowdifference[0],0,1)."";
										if(($tempdata)!='-')
										{
											$dates = YMDtoDMY($rowlatearrival[2])." (".$rowdifference[0].") ,".$dates;
											$days++;
										}
										
									}	
										
										
									}
										if($days>0)
										echo"<tr><td>".$StaffMasterRow[0]."</td><td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td><td>".$dates."</td><td>".$days."</td></tr>";	
								
			
		   }
			echo "</tr></table></center><br>";	
		}  
		
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
	   }//attendance while
		   
	echo  "<center> <p class='btn btn-warning' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";
	echo  '<center><table class="table table-bordered" border="1" style="width:75%">';
								echo "<tr bgcolor='sky blue'><th style='color:white;'>Emp Code</th>
								<th style='color:white;'>Employee Details</th>
								<th style='color:white;'>Date</th>
								<th style='color:white;'>No. of Days</th></tr>";

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
									$qrylatearrival = "select * from Attendancechk where attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'   and empcode = '".$StaffMasterRow[0]."'  ";
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
									
									
									
									while($rowlatearrival = fetch($reslatearrival))
									{
										$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$StaffMasterRow['shiftid']."'";
									$ShiftMasterRes = exequery($ShiftMasterSql);
								$rowshifttime = fetch($ShiftMasterRes);
								
								if($rowlatearrival[4]!="")
											$latetime=$rowlatearrival[4];
										if($rowlatearrival[6]!="")
											$latetime=$rowlatearrival[6];
										if($rowlatearrival[8]!="")
											$latetime=$rowlatearrival[8];
										if($rowlatearrival[10]!="")
											$latetime=$rowlatearrival[10];
										

								if($rowlatearrival[6]!=null) 
								{		
									if(strtotime($rowlatearrival[6])< strtotime($rowshifttime[7]))
									{
										$qrytimediff = "SELECT TIMEDIFF('$daytemp $rowshifttime[7]','$daytemp $rowlatearrival[6]')";
										$restimediff = exequery($qrytimediff);
										$rowtimediff = fetch($restimediff);
										
											
										$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','$daytemp 00:11:00')";
										$restimediff1 = exequery($qrytimediff1);
										$rowtimediff1 = fetch($restimediff1);
										$tempdata = substr($rowtimediff1[0],0,1);
										
									 
										//echo "".substr($rowdifference[0],0,1)."";
										if(($tempdata)!='-')
										{
											$dates = YMDtoDMY($rowlatearrival[2])." (".$rowtimediff[0].") ,".$dates;
											$days++;
										}
										
									}	
										
										
									}
								}
										if($days>0)
										echo"<tr><td>".$StaffMasterRow[0]."</td><td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td><td>".$dates."</td><td>".$days."</td></tr>";	
								
			
		   }
		 }
	if($_POST['misreport']=='overtime') 
	{
	  $time = mktime(0, 0, 0, $_POST['month']);
		  $name = strftime("%b", $time);
		  echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'> Overtime List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";
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
	   }//attendance while
		   
	echo  "<center> <p class='btn btn-warning' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";
	echo  '<center><table class="table table-bordered" border="1" style="width:75%">';
								echo "<tr bgcolor='sky blue'><th style='color:white;'>Emp Code</th>
								<th style='color:white;'>Employee Details</th>
								<th style='color:white;'>Date</th>
								<th style='color:white;'>No. of Days</th></tr>";

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
									$qrylatearrival = "select * from Attendancechk where attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'   and empcode = '".$StaffMasterRow[0]."'  ";
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
									
									
									
									while($rowlatearrival = fetch($reslatearrival))
									{
										$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$StaffMasterRow['shiftid']."'";
									$ShiftMasterRes = exequery($ShiftMasterSql);
								$rowshifttime = fetch($ShiftMasterRes);
								
								if($rowlatearrival[4]!="")
											$latetime=$rowlatearrival[4];
										if($rowlatearrival[6]!="")
											$latetime=$rowlatearrival[6];
										if($rowlatearrival[8]!="")
											$latetime=$rowlatearrival[8];
										if($rowlatearrival[10]!="")
											$latetime=$rowlatearrival[10];
										

										$qrydifference = "select TIMEDIFF('".$rowlatearrival[15]."','".$rowshifttime[8]."')";
										$resdifference = exequery($qrydifference);
										$rowdifference = fetch($resdifference);
									
										//echo "".substr($rowdifference[0],1,1)."";
										if(substr($rowdifference[0],1,1)>='1')
										{
											$dates = YMDtoDMY($rowlatearrival[2])." (".$rowdifference[0].") ,".$dates;
											$days++;
										}
								}
										if($days>0)
										echo"<tr><td>".$StaffMasterRow[0]."</td><td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td><td>".$dates."</td><td>".$days."</td></tr>";	
								
			
		   }
			echo "</tr></table></center><br>";	
		}
				   
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
	   }//attendance while
		   
			echo "<center> <p class='btn btn-info' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";

	?>
	<center>
	<table class='table table-bordered' border="1" style="width:75%">
	   <tr bgcolor='#A48AD4'>
			<th style="color:white;font-size:16px;"> Emp Code</th>  
			  
			<th style="color:white;font-size:16px;"> Employee Details </th>
		   <th style="color:white;font-size:16px;"> Date </th>
			<th style="color:white;font-size:16px;">No. of Days</th>
			<th style="color:white;font-size:16px;">Reason</th>
		   
	  </tr>   
	<? 
	$frmdate = DMYtoYMD($_POST['frmdate']);
	$todate  = DMYTOYMD($_POST['todate']);

	$date=$_POST['year']."-".$_POST['month'];
	$fromempcode1 = explode(':',$_POST['fromempcode']);
	$fromempcode  = $fromempcode1[0];
		   
		   $toempcode1 = explode(':',$_POST['toempcode']);
		   $toempcode  = $toempcode1[0];
	   
		   $StaffMasterSql = "select * from StaffMaster where lefts!=1 ";
		  
		   if(($fromempcode!='') && ($toempcode!=''))
				 $StaffMasterSql.=" and  ((empcode BETWEEN ('".$fromempcode."') AND ('".$toempcode."')) )";
		   //echo $StaffMasterSql ;
	   
		   if(($_POST['fromdepartment']!='') && ($_POST['todepartment']!=''))
				  $StaffMasterSql.="and ((deptid BETWEEN ('".$_POST['fromdepartment']."') AND ('".$_POST['todepartment']."')) )";
		   
		   if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
				 $StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
		   
		   if($_POST['company']!='all')
				 $StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";
		   
				 //$StaffMasterSql.=" group by empcode   order by empcode desc ";
		  // echo $StaffMasterSql;
		   $StaffMasterRes = exequery($StaffMasterSql);
		   while($StaffMasterRow = fetch($StaffMasterRes))
		   {

									   $qrysickleave = "SELECT sum(days)as days,empcode FROM LeaveTransaction where empcode='".$StaffMasterRow[0]."' and leavetype='OD' and  frmdate>='".$frmdate."' and frmdate<='".$todate."' ";
										//echo $qrysickleave;
										$ressickleave = exequery($qrysickleave);
										while($rowsickleave = fetch($ressickleave))
									   {		
										//echo $rowsickleave[1];
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
										$resleave = exequery($qryleave);
										while($rowleave = fetch($resleave))
										{
											$qrytotday = "SELECT datediff('".$rowleave[3]."','".$rowleave[2]."')";
											//echo $qrytotday;
											$restotday = exequery($qrytotday);
											$rowtotday = fetch($restotday);
											$rowtotday1 = $rowtotday[0];
											//echo 	"diff".$rowtotday1;
											//echo "<br>";
													  
											for($i=0;$i<=$rowtotday1;$i++)
											{
												$qrydate2 = "select ADDDATE('".$rowleave[2]."',interval $i day)";
												$resdate2 = exequery($qrydate2);
												$rowdate2 = fetch($resdate2);
												
												$qryholiday = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and hdate>='".$rowdate2[0]."' and hdate<='".$rowdate2[0]."' and  E.edition='".$rowqry['region']."'";
												$resholiday = exequery($qryholiday);
												$rowholiday = fetch($resholiday);
												
												$qryday = "SELECT  UPPER(DAYNAME('".$rowdate2[0]."')) AS DAY";
												$resday = exequery($qryday);
												$rowday = fetch($resday);
												//echo $rowday[0];
												if($rowday[0]==$rowweekoff[1]||$rowholiday[0]!="")
												{
												//$data = "";
												}
												else
												$data = YMDtoDMY($rowdate2[0]).",".$data;
												//echo $data;
												//echo "<br>";
											}
												$reason = $reason.",".$rowleave[5];
										}	
												$flag=0;
												if($flag==0)
											{
												if($rowsickleave[0]>0)
												{
													// echo $total;
													$flag=1;
													$total=0;
													$filename = "upload/".$rowqry[0].".jpg";
													//echo $filename;
													if (file_exists($filename)) 
													{
														$image="upload/".$rowqry[0].".jpg";
													} 
													else 
													{
														$image='upload/staff.png';	
													}

									?>
															
											<tr>
												<td><? echo $rowqry[0]; ?></td>	
												<td><? echo $rowqry[1]; ?>,<br><? echo $rowb[1]; ?>,<br><? echo $rowregion[1]; ?> ,<? echo $rowdept[1]; ?></td>
												<td><? echo $data; ?></td>
												<td><? echo $rowsickleave[0];?></td>
												<td><? echo $reason; ?></td>															
											</tr>		
												<?
												//$count++;
												?>
																
												<?	
												}			
										 }												 
									}
								}	     //  echo $count; 
									?> 
													
														



	</table>
	</center>  
	<?

		   
	} // end of od 

if($_POST['misreport']=='status') 
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
	   }//attendance while
    	   
   echo  "<center> <p class='btn btn-warning' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";
	echo  '<center><table class="table table-bordered" border="1" style="width:75%">';

 
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
			         
	 		       echo "<tr bgcolor='#A48AD4'>";
	 		       	   echo "<td style='color:white;font-size:16px;font-weight:bold;'>Date </td>";
	 		       	   
	 		       	   
	 		       for($a=0;$a<$days1;$a++) 
	 		        {
	 		        	    $dateqry = "select DATE_ADD('".$frmdate."', interval '".$a."' day)";
	 		       	     $dateres = exequery($dateqry);
	 		       	     $daterow = fetch($dateres);
	 		       	     $dates = explode('-',$daterow[0]);
	 		       	      $day1 = $dates[2];
                       echo "<td style='color:white;font-size:16px;font-weight:bold;'>$day1</td>";
                 }
                                  echo "<td style='color:white;font-size:16px;font-weight:bold;'>Total Work </td>";
                 echo "</tr>";
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$StaffMasterRow[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									if($rowattendance!=NULL)
									{
										if($rowattendance[3]!=NULL)
										{
									          echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[3]</td>";
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
          if($_POST['firstout']=='firstout') 
	 		 {
	 		       	   
	 		     echo "<tr>";
	 		     echo "<td style='font-size:14px;font-weight:bold;'>Rest Out </td>";
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$StaffMasterRow[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									if($rowattendance!=NULL)
									{
										if($rowattendance[4]!=NULL)
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
          if($_POST['secondin']=='secondin') 
	 		 {
	 		       	   
	 		     echo "<tr>";
	 		     echo "<td style='font-size:14px;font-weight:bold;'>Rest In </td>";
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$StaffMasterRow[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									if($rowattendance!=NULL)
									{
										if($rowattendance[5]!=NULL)
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
          if($_POST['secondout']=='secondout') 
	 		 {
	 		       	   
	 		     echo "<tr>";
	 		     echo "<td style='font-size:14px;font-weight:bold;'>Departure </td>";
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
									
									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$StaffMasterRow[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									if($rowattendance!=NULL)
									{
										if($rowattendance[6]!=NULL)
										{
									          echo"<td style='color:black;font-size:12px;font-face:verdana;'>$rowattendance[6]</td>";
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
     $totalwork = 0;
	  $toatlmin = 0;
          if(($_POST['secondout']=='secondout') && ($_POST['secondin']=='secondin') && ($_POST['firstout']=='firstout') && ($_POST['firstin']=='firstin')) 
	 		 {
	 		       	   
	 		     echo "<tr bgcolor='#EFF2F7' >";
	 		     echo "<td style='font-size:14px;font-weight:bold;'>Work Hrs </td>";
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
									
									      
      									$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$StaffMasterRow[0]."' ";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									$date = date('Y-m-d');
									if($rowattendance[3]!=null || $rowattendance[3]!='') 
			                   {
			      	              $firsthalfstart1 = $rowattendance[3];
			                   }
			                  else 
			                   {
			      	                 $firsthalfstart1 = '00:00:00';
			                   }
			      			  if($rowattendance[4]!=null || $rowattendance[4]!='') 
			                   {
			      	              $firsthalfend1 = $rowattendance[4];
			                   }
			                  else 
			                   {
			      	             $firsthalfend1 = '00:00:00';
			                   } 
			      			 if($rowattendance[5]!=null || $rowattendance[5]!='') 
			                  {
			      	             $secondhalfstart1 = $rowattendance[5];
			                  }
			                else 
			                  {
			      	             $secondhalfstart1 = '00:00:00';
			                  }
			      			 if($rowattendance[6]!=null || $rowattendance[6]!='') 
			                 {
			      	            $secondhalfend1 = $rowattendance[6];
			                 }
			               else 
			                {
			      	           $secondhalfend1 = '00:00:00';
			                }
		                     $FirstHalfSql = "select TIMEDIFF ('".$firsthalfend1."','".$firsthalfstart1."')";
			                  $FirstHalfRes = exequery($FirstHalfSql);
			                  $FirstHalfRow = fetch($FirstHalfRes);

									$SecondHalfSql = "select TIMEDIFF ('".$secondhalfend1."','".$secondhalfstart1."')";
			      				$SecondHalfRes = exequery($SecondHalfSql);
			      				$SecondHalfRow = fetch($SecondHalfRes);

               				$TotalWorkHoursSql = "select ADDTIME ('".$FirstHalfRow[0]."','".$SecondHalfRow[0]."')";
			      				$TotalWorkHoursRes = exequery($TotalWorkHoursSql);
			      				$TotalWorkHoursRow = fetch($TotalWorkHoursRes);
			      				
			      				$temworkhrs = substr($TotalWorkHoursRow[0],0,1);
			      				if($temworkhrs!='-')
			      				{
			      				    $totalworkhrs1 = substr($TotalWorkHoursRow[0],0,5);
			      				}
									else 
									{
										$totalworkhrs1 = substr($TotalWorkHoursRow[0],0,6);
										
									}
										$total  =  explode(':',$TotalWorkHoursRow[0]);
										$totalwork = $totalwork+$total[0];
										$toatlmin = $toatlmin+$total[1];	
									
									if($rowattendance!=NULL)
									{
								
									    echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$totalworkhrs1."</td>";
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
					$hours  = floor($toatlmin/60); //round down to nearest minute. 
					$total = $totalwork + $hours;
					$minutes = $toatlmin % 60;
					if($minutes<9) 
					{
						$minutes1 = "0".$minutes;
					}
					else 
					{
						$minutes1 = $minutes;
					}
						
					$totalwork = $total.":".$minutes1;
					echo '<td>'.$totalwork.'</td></tr>';						
 
      

      }
}
	  ?>  	<input class="span4" id="frmdate" name="frmdate" type="hidden" placeholder="Select date" value="<? echo $_POST['frmdate'] ?>">
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
 	 //echo "Completed in ", microtime(true) - $start, " Seconds\n";
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
				      <div class="panel-heading">
				          <h4>REPORT</h4>
				          
				      </div>
				      <div class="panel-body" >

                            <div class="">
    
                                	<form action="attchkreport.php" method="post" enctype="multipart/form-data" class="form-horizontal ">
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
                
                
           }
           //else 
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
                                	<table class="table">
                                		<tr>
                                	 
                                	      <td>
                                	      
                                	          <input  type="radio"  id="performance" name="misreport" value="performance" checked>Performance
                                	      </td>
                                	      
                                	       <td>
                                	      
                                	          <input  type="radio"  id="absent" name="misreport" value="absent" >Absent
                                	      </td>
                                	      
                                	        <td>
                                	      
                                	          <input  type="radio"  id="ab" name="misreport" value="ab" >No Punch
                                	      </td>
                                	      
                                	      <td>
                                	      
                                	          <input  type="radio"  id="latearrival" name="misreport" value="latearrival" >Late Arrival
                                	      </td>
                                	      
                                	      <td>
                                	      
                                	          <input  type="radio"  id="earlydep" name="misreport" value="earlydep" >Early Departure
                                	      </td>
                                	      
                                	       <td>
                                	      
                                	          <input  type="radio"  id="overtime" name="misreport" value="overtime" >Over Time
                                	      </td>
                                	  
                                	      <td>
                                	      
                                	          <input  type="radio"  id="od" name="misreport" value="od" >OD
                                	      </td>
                                	      <td>
                                	      
                                	          <input  type="radio"  id="status" name="misreport" value="status" >Status
                                	      </td>
                                	     <!-- <td>Improper
                                	      
                                	          <input  type="radio"  id="improper" name="misreport" value="improper" >
                                	      </td>-->

                                	  </tr>
                                	 <!-- <tr>
                                	       <td>Monthly Present 
                                	      
                                	          <input  type="radio"  id="monthpresent" name="misreport" value="monthpresent" >
                                	      </td>
                                	  
                                	  </tr> -->
                                	  </table>
<table class="table"> 
                                	<tr id='display' style="visible:hidden;">                                    
                                	       <td>
                                               Month
                                        </td>
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
							
<tr id='display1'>
	<td> From Date</td>
	<td>
		<input  class="form-control"  style="width:110px;"  id="frmdate" name="frmdate" type="text" placeholder="Select date" value="<? echo date('d-m-Y');?>" >  
	</td>	

	<td> To Date</td>
	<td>
		  <input  class="form-control"  style="width:110px;"  id="todate" name="todate" type="text" placeholder="Select date" value="<? echo date('d-m-Y');?>" >
	</td>	
</tr> 

<tr id='display2' style="visible:hidden;">
 <td>
 First In 
 <input  type="checkbox"  id="firstin" name="firstin" value="firstin" checked>
</td>
 <td>
 First Out 
 <input  type="checkbox"  id="firstout" name="firstout" value="firstout" >
</td>

 <td>
 Second In 
 <input  type="checkbox"  id="secondin" name="secondin" value="secondin" >
</td>

 <td>
 Second Out 
 <input  type="checkbox"  id="secondout" name="secondout" value="secondout" >
</td>
</tr>
 

								<tr>
								    <td>From Employee</td>
									<td>
									      <!--<select name='fromempcode' id='fromempcode' class='form-control' style='width:300px'>
										    <?
											    /*$StaffMasterSql = "select * from StaffMaster order by empcode asc";
												$StaffMasterRes = exequery($StaffMasterSql);
												while($StaffMasterRow = fetch($StaffMasterRes))
												{
												   echo "<option value=".$StaffMasterRow[0].">".$StaffMasterRow[0].":".$StaffMasterRow[1]."</option>";
												
												}*/
											
											?>
									
									      </select>-->
									       <input  type="text" class='form-control' style='width:300px' id="fromempcode" name="fromempcode"  onclick="toempcode1();" onchange="toempcode1();" > 
									</td>
									
									<td>To Employee</td>
									<td>
									      <!--<select name='toempcode' id='toempcode' class='form-control' style='width:300px'>
										    <?
											    //$StaffMasterSql = "select * from StaffMaster order by empcode desc" ;
												//$StaffMasterRes = exequery($StaffMasterSql);
												//while($StaffMasterRow = fetch($StaffMasterRes))
												//{
												  // echo "<option value=".$StaffMasterRow[0].">".$StaffMasterRow[0].":".$StaffMasterRow[1]."</option>";
												
												//}
											
											?>
									
									      </select> -->
									     <input  type="text" class='form-control' style='width:300px'  id="toempcode" name="toempcode"  > 
									</td>
								
								
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
								    <td>From Location</td>
									<td>
									      <select name='fromlocation' id='fromlocation' class='form-control' style='width:300px' onclick="tolocation1();" onchange="tolocation1();">
											  <option value=''>select</option>
										    <?
											    $RegionSql = "select * from RegionMaster1 order by regionid asc";
												$RegionRes = exequery($RegionSql);
												while($RegionRow = fetch($RegionRes))
												{
												   echo "<option value=".$RegionRow[0].">".$RegionRow[0].":".$RegionRow[1]."</option>";
												
												}
											
											?>
									
									      </select>
									</td>
									
									<td>To Location</td>
									<td>
									      <select name='tolocation' id='tolocation' class='form-control' style='width:300px'>
											  <option value=''>select</option>
										    <?
											    $RegionSql = "select * from RegionMaster1 order by regionid asc";
												$RegionRes = exequery($RegionSql);
												while($RegionRow = fetch($RegionRes))
												{
												   echo "<option value=".$RegionRow[0].">".$RegionRow[0].":".$RegionRow[1]."</option>";
												
												}
											
											?>
									
									      </select>
									</td>
								
								
								</tr><!-- s location  -->
								
								<tr>
								    <td>From Branch</td>
									<td>
									      <select name='frombranch' id='frombranch' class='form-control' style='width:300px' onclick="toBranch();" onchange="toBranch();">
											  <option value=''>select</option>
										    <?
											    $RegionSql = "select * from BranchMaster1 order by branchid asc";
												$RegionRes = exequery($RegionSql);
												while($RegionRow = fetch($RegionRes))
												{
												   echo "<option value=".$RegionRow[0].">".$RegionRow[0].":".$RegionRow[1]."</option>";
												
												}
											
											?>
									
									      </select>
									</td>
									
									<td>To Branch</td>
									<td>
									      <select name='tobranch' id='tobranch' class='form-control' style='width:300px'>
											  <option value=''>select</option>
										    <?
											    $RegionSql = "select * from BranchMaster1 order by branchid asc";
												$RegionRes = exequery($RegionSql);
												while($RegionRow = fetch($RegionRes))
												{
												   echo "<option value=".$RegionRow[0].">".$RegionRow[0].":".$RegionRow[1]."</option>";
												
												}
											
											?>
									
									      </select>
									</td>
								
								
								</tr><!-- s branch  -->
								
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
									<td></td><td></td>
								
								</tr>
								<tr>
								<td>Group By</td>
								<td><input type='checkbox' name='departmenttype' value='department'>&nbsp;Department &nbsp;&nbsp;&nbsp;<input type='checkbox' name='locationtype' value='location'>&nbsp;Location&nbsp;&nbsp;&nbsp;<input type='checkbox' name='companytype' value='company'>&nbsp;Company</td><td></td><td></td>
								</tr>
                                	
                                	
                                	
                                	</table>
                                    
                                <center>
                                    <input class="btn btn-info" type="submit" name="action" value="Generate" />
						                  
						              </center>
                            </form>
  </div>
				   
				  </div>
				</div>
			</div>

		 </div> <!-- row -->
	 </div> <!-- container -->
 </div> <!-- wrap -->
</div> <!-- page-content -->

<?php include "footer.php" ?>

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
