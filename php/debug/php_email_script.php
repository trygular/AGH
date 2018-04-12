<?php
include('db.php');
  //  include("fpdf/fpdf.php");
      // usedb("TarunBharat-Empire");
    require('testpavan.php');
 $filename = session_id(); 
 
 if($_POST['action']=="getsubdept")
 {
	 $qry = "select * from SubdepartmentMaster1 where departmentid='".$_POST['dept']."'";
	 $res = exequery($qry);
	 while($row=fetch($res))
	 {
		 echo $row[0].":".$row[1].";";
	 }		 
	 die();
 }
	//action=sendleavemailEmp&mailid="+emailtext+filename="+filename
	if($_POST['action'] == 'sendleavemailEmp')
	{
		
		$attchfilename = "attendencereport/".$_POST['filename'].".pdf";
		$frmdate = $_POST['frmdate'];
		$SMSmonth = date('m', strtotime($frmdate));
		$SMSyear = date('Y', strtotime($frmdate));
		
		require 'PHPMailer/class.phpmailer.php';

		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = false; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
		$mail->Host = "smtp.tarunbharat.com";
		$mail->Port = 587; // or 587
		$mail->IsHTML(true);
		
		/* $mail->Username = "hrd.mgr@tarunbharat.com";
		$mail->Password = "intel123";
		$mail->SetFrom("hrd.mgr@tarunbharat.com");
		 */
		 $mail->Username = "edp.deptt@tarunbharat.com";
		$mail->Password = "intel123";
		$mail->SetFrom("edp.deptt@tarunbharat.com");
		$empcodeq="select empcode from StaffMaster where Name like'%".$_POST['empname']."%'";
		$resempcode = exequery($empcodeq);
		$rowempcode = fetch($resempcode);
		
		//$mail->Subject = "Purchase Order No.: ".$pono.". Date: ".$newDate;
		$mail->Subject = "Name: ".strtoupper($_POST['empname']).". ID No.: ".$rowempcode[0];
		$mail->Body = "This mail is regarding your absent report.So we request you to please clear your report as soon as possible,<br><br>From<br>H.R.D<br>Belgaum";

		$mail->AddAttachment($attchfilename);
		
		//$email = "pavandeshpande23@gmail.com";
		//$cemail = $mailid;
		$email = trim($_POST['mailid']);
		
		if($email!="")
		$mail->AddAddress($email,'0');
		if($cemail!="")
		$mail->AddAddress($cemail,'0');
		 
		if(!$mail->Send()){
			$ret = "Mailer Error: " . $mail->ErrorInfo;
		} 		
		else{
			$ret = "Mail Sent Successfully....";
		}
		//echo $email."</br>";
		echo $ret;
		die();
	}
 
				  	
 //action=sendleavesmsEmp&smsdata="+smsdata+"&smsmob="+smsmob
	if($_POST['action'] =='sendleavesmsEmp')
	{
		 
		// echo "hi";
		 $mobileNumber=$_POST['smsmob'];
		 $message=trim($_POST['smsdata']);
		
		$senderId="DEMOOS";
		$routeId="1";
		$serverUrl="sms.belgaumit.com";
		$authKey="1eb155da72b53245fb32b05532b4de";
		
		$contype='1';
		 
		$postData = array(
        
      
        'mobileNumbers' => $mobileNumber,        
        'smsContent' => $message,
        'senderId' => $senderId,
        'routeId' => $routeId,		
        "smsContentType" =>$contype
    );


    $data_json = json_encode($postData);


    $url="http://".$serverUrl."/rest/services/sendSMS/sendGroupSms?AUTH_KEY=".$authKey;


    // init the resource
    $ch = curl_init();

    

    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => array('Content-Type: application/json','Content-Length: ' . strlen($data_json)),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data_json,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0
    ));

    //get response
    $output = curl_exec($ch);

    //Print error if any
    if(curl_errno($ch))
    {
        echo 'error:' . curl_error($ch);
    }
    curl_close($ch); 
		 
	echo "send sms...!!!"; 
		 
		 die();
	}
 
?>
<? include('header.php');?>
<title>Attendance Report</title>
<script type="text/javascript" src="/js1/jquery.js"></script>		
	<script type="text/javascript" src="/js1/jquery-ui.js"></script>
	<script type="text/javascript" src="/js1/jquery.timepicker.js"></script>
	<script type="text/javascript" src="/js1/rating.js"></script>
	<script type="text/javascript" src="/js1/apprise.js"></script>
	<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
	<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script>

		
 
  <script>
		$(function() {
				$("#tabs").tabs();
			});
			$(document).ready(function()
			{
				$("#fromempcode").autocomplete("empsearchfetch.php", 
				{
					selectFirst: true
					});
								
			});
	  
	  	$(document).ready(function()
		{
				$("#toempcode").autocomplete("empsearchfetch.php", {
					selectFirst: true
					});
								
			});
			function toempcode1() 
			{
				//alert('cmg');
				empcode = $('#fromempcode').val();
				
				$('#toempcode').val(empcode);
			}
			
			function todepartment1() 
			{
				
				fromdepartment = $('#fromdepartment').val();
				 
				 $.ajax({
						url: "/attchkreport.php" ,
						data: "action=getsubdept&dept="+fromdepartment,
						type : 'POST',
						success: function(output)
						{
						  catdata=output.split(';');
							$('#subdepartment').html('');		
					 
							$('#subdepartment').append("<option value=''>ALL</option>");
								for(i=0;i<catdata.length-1;i++)
								{
								  datainfo = catdata[i].split(':');
								  $('#subdepartment').append("<option value="+datainfo[0]+">"+datainfo[1]+"</option>");	
								}
						   
						}
				});	
			}
			
				function tolocation1() 
			{
				//alert('cmg');
				fromlocation = $('#fromlocation').val();
				
				$('#tolocation').val(fromlocation);
			}
			
				function toBranch() 
			{
				//alert('cmg');
				frombranch = $('#frombranch').val();
				
				$('#tobranch').val(frombranch);
			}
			
		function makeasleave(empcode)
		{
			var frmdate = $.trim($('#frmdate').val());
			var todate = $.trim($('#todate').val());
			
			window.open("Availeave1.php?Action=GetMainForm&empcode="+empcode+"&frmdate="+frmdate+"&todate="+todate, '_blank');
			//window.location.href="Availeave1.php?Action=GetMainForm&empcode="+empcode+"&frmdate="+frmdate+"&todate="+todate;
		}

		function correction(empcode)
		{
			var frmdate = $.trim($('#frmdate').val());
			var todate = $.trim($('#todate').val());
			
			 window.open("AttckCorrection1.php?Action=GetMainForm&empcode="+empcode+"&frmdate="+frmdate+"&todate="+todate, '_blank');
			//window.location.href="AttckCorrection1.php?Action=GetMainForm&empcode="+empcode+"&frmdate="+frmdate+"&todate="+todate;
		}	
		
	
		function sendleavesmsEmp(empid)
		{
			
			var smsdata = $('#smsdataemp'+empid).val();
			var smsmob = $('#mobno'+empid).val();
			var frmdate = $('#frmdate').val();
			
			//alert(smsdata);
			$.ajax({
				url:"attchkreport.php",
				data:"action=sendleavesmsEmp&smsdata="+smsdata+"&smsmob="+smsmob+"&frmdate="+frmdate,
				type:"POST",
				success:function(output)
				{
					alert(output);
				}
			});
			
		}
		
		function sendleavemailEmp(filename)
		{
			var empname = $.trim($('#empname').val());
			var emailtext = $.trim($('#emailtext').val());
			if ($.trim(emailtext).length == 0) {
				alert('Please enter valid email address');
				return false;
			}
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			var mail =regex.test(emailtext);
			//alert(mail);
			if(mail){
				$.ajax({
					url:"attchkreport.php",
					data:"action=sendleavemailEmp&mailid="+emailtext+"&filename="+filename,
					type:"POST",
					success:function(output)
					{
						alert(output);
					}
				});
			}
			else{
				alert('Invalid Email Address');
				return false;
			}
		}	
			
 </script>
 <body>
 
 <?
 
  
if($_POST['action']=="Generate") 
 {
	?>  
	 <form action="attchkreportexcel.php" method="POST" >
     <a class='btn btn-primary' style="float:right;font-weight:bold" href="attendencereport/<? echo $filename?>.pdf" target="_blank">Download as PDF</a><br><br><br>
     <span style="float:right;"><input class="btn btn-success" style="font-weight:bold" type="submit" name="Action" value="Generate as Excel"></span>
 	<?
	echo "<br><a href='attchkreport.php'><input class='btn btn-primary' type='button'  value='Back' style='font-color:white;font-weight:bold;font-size:16px;float:left'/></a>";	
	//echo '<form   action="attchkreportexcel.php" method="POST"><br><span style="float:right;"><input class="btn btn-primary" style="font-weight:bold" type="submit" name="Action" id="Action" value="Generate as Excel"></span> ';
	
	
	    
		
		
		
	
 if($_POST['misreport']=='performance') 
{
		
	$pdf=new PDF('L', 'mm', 'A3');
    $pdf->SetFont('Arial','',10);
    $pdf->AddPage();
 
		$pa=0;	
		 $page=1;
		 $frmdate = $_POST['frmdate'];
         $todate  = $_POST['todate'];
		 
		 $_POST['year'] = substr($frmdate,6,4);
		 $_POST['month'] = substr($frmdate,4,2);
		 
 		  $time = mktime(0, 0, 0, $_POST['month']);
		  $name = strftime("%b", $time);
		  echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'>Attendance List  For the Month ".$name." And Year ".$_POST['year']."</p></center><br>";
			$pdf->WriteHTML( "Page No ". $page." <br>");
			$pdf->SetFont('Arial','B',12);
			$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");
		
			$pdf->WriteHTML( " Attendance List  Form   ".$frmdate." to ".$todate."</center><br><br>");
			$pdf->WriteHTML( " <hr>");
			
			
		  	
		  $start = microtime(true);		
   		
 	     $my = $_POST['year'].'-'.$_POST['month'];
 	     $month = $_POST['year']."-".$_POST['month']."-01";
		 
		 
		$query="SELECT DATEDIFF('".DMYtoYMD($todate)."', '".DMYtoYMD($frmdate)."')";
         $resq = exequery($query);		 
         $rowq = fetch($resq);
		 
		 $lastday = $rowq[0];
		 $firstday = 0;
		 
         // $lastday = date('t',strtotime($month));
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
	   
    	   if( $_POST['fromdepartment']!='')  
    	    $StaffMasterSql.="and deptid ='".$_POST['fromdepartment']."' ";
			if( $_POST['subdepartment']!='')  
    	    $StaffMasterSql.="and subdeptid ='".$_POST['subdepartment']."' ";
    	   if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
    	         $StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
    	   
    	   if(($_POST['frombranch']!='') && ($_POST['tobranch']!=''))
    	    $StaffMasterSql.="and ((branch BETWEEN ('".$_POST['frombranch']."') AND ('".$_POST['tobranch']."')) )";	
		
           if($_POST['company']!='all')
    	    $StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";
		
		 $StaffMasterSql.=" Order BY region,deptid,Empcompanyid";
    	   
    	         //$StaffMasterSql.=" group by empcode   order by empcode desc ";
    	  
		 // echo $StaffMasterSql;
		  
    	   $StaffMasterRes = exequery($StaffMasterSql);
    	   while($row1 = fetch($StaffMasterRes))
    	   {
    	   	
    	   	$DepartmentMatserSql = "select * from DepartmentMaster1 where departmentid ='".$row1['deptid']."'";
            $DepartmentMatserRes = exequery($DepartmentMatserSql);
            $DepartmentMatserRow = fetch($DepartmentMatserRes);
            
				$weekdayQry = "select * from weekdays where id='".$row1[20]."'";
				//echo $weekdayQry;
				$weekdayRes = exequery($weekdayQry);
				$weekdayRow = fetch($weekdayRes);
    	         
    	    echo' <center><span class="btn btn-warning" style="font-weight:bold;fontsize:15px;"> Empcode : '.$row1[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name : '.$row1[1].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Department : '.$DepartmentMatserRow[1].'<br> FROM '.$frmdate.' TO '.$todate.'</center></span> ';
			
			
			$pdf->SetFont('Arial','B',10);
			$pdf->WriteHTML("Empcode : ".$row1[0]." Name : ".$row1[1]."<br>Department : ".$DepartmentMatserRow[1]."<br>");
			
			$pdf->SetFont('Arial','',8); 
            echo "<center><table  class='table table-bordered'  style='width:75%'>";    	    
            echo "<tr bgcolor='#1FB5AD'>";
			
			$pdf->WriteHTML("<table  border=1><tr bgcolor='#1FB5AD' height='30'><td  width='70px'>DATE</td>");
            echo "<td style='color:white;font-size:16px;font-weight:bold;'>DATE</td>";
          
            // $month = $_POST['year']."-".$_POST['month']."-01";
            // $lastday = date('t',strtotime($month));
            //  $lastday = $days;
			   
			  
			   $k=0;
               for($i=$firstday;$i<=$lastday;$i++) 
               {
         
		             $q = "Select substr(date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY),9,2);";
					//echo $q;
					   $resday = exequery($q);
					   $rowday = fetch($resday);
						echo  "<td  >".$rowday[0]."</td>";
          			 $pdf->WriteHTML( "<td width='40px'>".$rowday[0]."</td>");
					$k++;
          	   }
          
           $pdf->WriteHTML( "<td width='70px'>   Total Work</td></tr>");
              
          echo "<td style='color:white;font-size:16px;font-weight:bold;'>Total Work</td>";
          echo '</tr><tr><td>Arrival</td>';
               //echo $lastday ;
			   $pdf->WriteHTML( "<tr><td width='70px'>Arrival</td>");
			   $k=0;
               for($i=$firstday;$i<=$lastday;$i++) 
               {
	                        if($i<10)
									$i="0".$i;
								
									$q = "Select (date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY));";
									$resday = exequery($q);
									$rowday = fetch($resday);

									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									$daytemp = $rowday[0];
									$k++;
									
									$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
									//echo $qryday."<br>";
									$resday = exequery($qryday);
									$rowday = fetch($resday);
									//echo $rowday[0]."<br>";
									
									$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$row1['shiftid']."'";
									$ShiftMasterRes = exequery($ShiftMasterSql);
									$rowshifttime = fetch($ShiftMasterRes);

									$shiftstarttime = $rowshifttime[4];
									$shiftendtime   = $ShiftMasterRow[7];
									$shifttotaltime = $ShiftMasterRow[8];

									$LeaveSql = "select * from LeaveTransaction where empcode='".$row1[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
									//echo $LeaveSql;
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
									   if($rowattendance[3]!=null)
									   {
										  $arrivaltime=explode(' ',$rowattendance[3]);
										  if($arrivaltime[1]=="")
										  {
											echo"<td style='color:black;font-size:12px;font-face:verdana;'>--</td>";
										    $pdf->WriteHTML( "<td width='40px'>--</td>");
										  }
											else										  
											{		
											  echo"<td style='color:black;font-size:12px;font-face:verdana;'>$arrivaltime[1]</td>";
											  $pdf->WriteHTML( "<td width='40px'>$arrivaltime[1]</td>");
											}
									   }
									   else if($LeaveRow!=NULL)
									   {
									      echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
										   $pdf->WriteHTML( "<td width='40px'>".$LeaveRow[4]."</td>");
									   }
									   else if($rowday[0]==$weekdayRow[1])
									   {	
										  echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
										   $pdf->WriteHTML( "<td width='40px'>WO</td>");
									   }
									   else 
									   {
										  echo"<td style='color:red;font-size:12px;font-face:verdana;'>--</td>";
										     $pdf->WriteHTML( "<td width='40px'>--</td>");
									   }
									}
									
									/*else if($rowday[0]==$weekdayRow[1])
									{	
									   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
									}*/
									else if($LeaveRow!=NULL)
									{
									    echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
										$pdf->WriteHTML( "<td width='40px'>".$LeaveRow[4]."</td>");
									}
									else if($HolidayMasterRow!=NULL)
									{
										echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
											$pdf->WriteHTML( "<td width='40px'>HL</td>");
									}									
									else
									{
									   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
									   $pdf->WriteHTML( "<td width='40px'>A</td>");
									   
									}
								}
							echo '<td width="70px">  </td></tr>';
							$pdf->WriteHTML('<td width="70px"> -- </td></tr>' );
							
					echo '<tr><td>Rest Out</td>';
					$pdf->WriteHTML('<tr><td width="70px">Rest Out</td>');
				
               //echo $lastday ;
			   $k=0;
               for($i=$firstday;$i<=$lastday;$i++) 
               {
	                        if($i<10)
									$i="0".$i;
								
								    $q = "Select (date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY));";
									$resday = exequery($q);
									$rowday = fetch($resday);

									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									$daytemp = $rowday[0];
									$k++;
								
								
								
									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									
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
											$arrivaltime=explode(' ',$rowattendance[4]);
											if($rowday[0]==$weekdayRow[1])
											{	
											echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
											$pdf->WriteHTML("<td width='40px'>WO</td>");
											}
											else
											{	
												if($arrivaltime[1]=="")
												{	
												echo"<td style='color:black;font-size:12px;font-face:verdana;'>--</td>";
												$pdf->WriteHTML("<td width='40px'>--</td>");
												}
												else
												{
												echo"<td style='color:black;font-size:12px;font-face:verdana;'>$arrivaltime[1]</td>";
												$pdf->WriteHTML("<td width='40px'>$arrivaltime[1]</td>");
												}											
											}
											
										}
										else if($LeaveRow!=NULL)
										{
											echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
											$pdf->WriteHTML("<td width='40px'>".$LeaveRow[4]."</td>");
										}
										else if($rowday[0]==$weekdayRow[1])
										{	
											echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
											$pdf->WriteHTML("<td width='40px'>WO</td>");
										}
										else 
										{
											echo"<td style='color:red;font-size:12px;font-face:verdana;'>--</td>";
											$pdf->WriteHTML("<td width='40px'>--</td>");
										}
									}									
									else if($LeaveRow!=NULL)
									{
									    echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
									    $pdf->WriteHTML("<td width='40px'>".$LeaveRow[4]."</td>");
									}
									else if($HolidayMasterRow!=NULL)
									{
										echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
										$pdf->WriteHTML("<td width='40px'>HL</td>");
									}									 								
									else
									{
									   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
									   $pdf->WriteHTML("<td width='40px'>A</td>");
									}
								}
							echo '<td>--</td></tr>';
							$pdf->WriteHTML( '<td width="70px">--</td></tr>');
							
					echo '<tr><td width="70px">Rest In</td>';
					$pdf->WriteHTML('<tr><td width="70px">Rest In</td>');
               //echo $lastday ;
			   $k=0;
               for($i=$firstday;$i<=$lastday;$i++) 
               {
	                        if($i<10)
									$i="0".$i;
								
								    $q = "Select (date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY));";
									$resday = exequery($q);
									$rowday = fetch($resday);

									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									$daytemp = $rowday[0];
									$k++;
								
						           // $daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									
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
											$arrivaltime=explode(' ',$rowattendance[5]);
											if($arrivaltime[1]=="")
												$arrivaltime[1]="--";
											echo"<td style='color:black;font-size:12px;font-face:verdana;'>$arrivaltime[1]</td>";
											$pdf->WriteHTML("<td width='40px'>$arrivaltime[1]</td>");
										}
										else if($LeaveRow!=NULL)
										{
											echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
											$pdf->WriteHTML("<td width='40px'>".$LeaveRow[4]."</td>");
										}
										else if($rowday[0]==$weekdayRow[1]  && $rowattendance[3]=='' )
										{	
											echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
											$pdf->WriteHTML("<td width='40px'>WO</td>");
										}
										else 
										{
										echo"<td style='color:red;font-size:12px;font-face:verdana;'>--</td>";
										$pdf->WriteHTML("<td width='40px'>--</td>");
										}
									}
										
										else if($LeaveRow!=NULL)
										{
											echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
											$pdf->WriteHTML("<td width='40px'>".$LeaveRow[4]."</td>");
										}
										else if($HolidayMasterRow!=NULL)
										{
											
											echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
											$pdf->WriteHTML("<td width='40px'>HL</td>");
										}									
										else
										{
										   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
										   $pdf->WriteHTML("<td width='40px'>A</td>");
										}
								}
								echo"<td width='70px'>--</td></tr>";
							$pdf->WriteHTML("<td width='70px'>--</td></tr>");
							echo '<tr><td>Departure</td>';
							$pdf->WriteHTML( '<tr><td width="70px">Departure</td>');
               //echo $lastday ;
			   $k=0;
               for($i=$firstday;$i<=$lastday;$i++) 
               {
	                        if($i<10)
									$i="0".$i;
								
								    $q = "Select (date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY));";
									$resday = exequery($q);
									$rowday = fetch($resday);

									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									$daytemp = $rowday[0];
									$k++;
								
								
						            //$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									
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
											 $arrivaltime=explode(' ',$rowattendance[6]);
									    echo"<td style='color:black;font-size:12px;font-face:verdana;'>$arrivaltime[1]</td>";
									    $pdf->WriteHTML("<td width='40px'>$arrivaltime[1]</td>");
									   }
									   else if($LeaveRow!=NULL)
									   {
									      echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
									      $pdf->WriteHTML("<td width='40px'>".$LeaveRow[4]."</td>");
									   }
										else if($rowday[0]==$weekdayRow[1] && $rowattendance[3]=='' )
										{	
										echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
										$pdf->WriteHTML("<td width='40px'>WO</td>");
										}
									   else 
									   {
									   	 echo"<td style='color:red;font-size:12px;font-face:verdana;'>--</td>";
									   	 $pdf->WriteHTML("<td width='40px'>--</td>");
									   }
									}
									
									else if($LeaveRow!=NULL)
									{
									    echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
									    $pdf->WriteHTML("<td width='40px'>".$LeaveRow[4]."</td>");
									}
									else if($HolidayMasterRow!=NULL)
									{
										
										echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
										$pdf->WriteHTML("<td width='40px'>HL</td>");
									}									
									else
									{
									   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
									   $pdf->WriteHTML("<td width='40px'>A</td>");
									}
								}
					echo '<td>--</td></tr>';
					$pdf->WriteHTML( '<td width="70px">--</td></tr>');
					
					echo '<tr><td>Late Arvl</td>';
					$pdf->WriteHTML( '<tr><td width="70px">Late Arvl</td>');
				   //echo $lastday ;
				   $k=0;
					$latetotalot=0;
					$latetotalothrs =0;
					$latetotalotmin =0;
					$latehours1  =0;
					$totallunch = 0;
					$lateminuteslunch = 0;
					$latetotalwork = 0;

				   for($i=$firstday;$i<=$lastday;$i++) 
				   {
	                        if($i<10)
								
								$i="0".$i;
								
								$q = "Select (date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY));";
								$resday = exequery($q);
								$rowday = fetch($resday);

								//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
								$daytemp = $rowday[0];
								$k++;
								
								
								
								//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;

								$qryday = "SELECT  UPPER(DAYNAME('".$daytemp."')) AS DAY";
								//echo $qryday."<br>";
								$resday = exequery($qryday);
								$rowday = fetch($resday);

								$LeaveSql = "select * from LeaveTransaction where empcode='".$row1[0]."' and '".$daytemp."' >=frmdate and '".$daytemp."'<=todate";
								//echo $LeaveSql."<br>";
								$LeaveRes = exequery($LeaveSql);
								$LeaveRow = fetch($LeaveRes);

								$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$daytemp."' and  E.edition='".$row1['region']."' ";
								//echo $HolidayMasterSql."<br>";
								$HolidayMasterRes = exequery($HolidayMasterSql);
								$HolidayMasterRow = fetch($HolidayMasterRes);

								$qryattendance = "select * from Attendancechk where attendancedate = '".$daytemp."' and empcode = '".$row1[0]."' ";
								//echo $qryattendance."<br>";
								$resattendance = exequery($qryattendance);
								$rowattendance = fetch($resattendance);
								
								$arrtime=explode(' ',$rowattendance[3]);
								$arttime1 = $arrtime[1].':00';

								$shiftdiff="SELECT * FROM ShiftMaster S, Empshiftdetails E where S.shiftid=E.shiftid and E.empcode='".$row1[0]."' and '".$arttime1."'>=shiftstart and '".$arttime1."'<=firsthalfend";
								//echo $shiftdiff."<br>";
								$shiftdiffres = exequery($shiftdiff);
								$shiftdiffrow = fetch($shiftdiffres);
								
								if($shiftdiffrow==null)
									{
										$shiftdiff="SELECT * FROM ShiftMaster S, Empshiftdetails E where S.shiftid=E.shiftid and E.empcode='".$row1[0]."' and '".$arttime1."'<=shiftstart";
										//echo $shiftdiff."<br>";
										$shiftdiffres = exequery($shiftdiff);
										$shiftdiffrow = fetch($shiftdiffres);
									}

								$shiftstarttime = $shiftdiffrow[4];
								$shiftendtime   = $ShiftMasterRow[7];
								$shifttotaltime = $ShiftMasterRow[8];	
	
									/*$EmpShiftMasterSql = "select * from Empshiftdetails where empcode = '".$row1[0]."' ";
									//echo $EmpShiftMasterSql."<br>";
									$EmpShiftMasterRes = exequery($EmpShiftMasterSql);
									$EmpShiftMasterRow = fetch($EmpShiftMasterRes);


									$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$EmpShiftMasterRow[2]."'";
									//echo $ShiftMasterSql."<br>";
									$ShiftMasterRes = exequery($ShiftMasterSql);
									$rowshifttime = fetch($ShiftMasterRes);

									$shiftstarttime = $rowshifttime[4];
									$shiftendtime   = $ShiftMasterRow[7];
									$shifttotaltime = $ShiftMasterRow[8];*/

									/*	if(strtotime($rowattendance[3])>strtotime($rowshifttime[4]))
										{*/
											$qrytimediff = "SELECT TIMEDIFF('$rowattendance[3]','$daytemp $shiftdiffrow[4]')";
											//$qrytimediff = "SELECT TIMEDIFF('$daytemp $rowattendance[3]','$daytemp $rowshifttime[4]')";
											//echo $qrytimediff."<br>";
											$restimediff = exequery($qrytimediff);
											$rowtimediff = fetch($restimediff);
											//echo $rowtimediff[0]."<br>";

											$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','$daytemp 00:11:00')";
											//echo $qrytimediff1;
											$restimediff1 = exequery($qrytimediff1);
											$rowtimediff1 = fetch($restimediff1);
											$tempdata = substr($rowtimediff1[0],0,1);
											
											$latearrival = substr($rowtimediff[0],0,1);
											if($latearrival!='-')
											{
											$latearrival = substr($rowtimediff[0],0,5);
											}
											else
											{
											$latearrival = substr($rowtimediff[0],0,6);
											}
										

											if($tempdata!='-')
											{
												if($latearrival=="")
												{
														if($rowday[0]==$weekdayRow[1] )
														{	
															echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
															$pdf->WriteHTML("<td width='40px'>WO</td>");
														}
														else
														{
															echo"<td style='color:blue;font-size:12px;font-face:verdana;'>--</td>";
															$pdf->WriteHTML("<td width='40px'>--</td>");
														}													
												}
												else												
												{	
												echo "<td style='color:red;font-size:14px;font-face:verdana;'>".$latearrival."</td>";
												$pdf->WriteHTML( "<td width='40px'>".$latearrival."</td>");
												if(substr($latearrival,0,1)!="-")
												{	
													$latetotalot  =  explode(':',$latearrival);
													$latetotalothrs = $latetotalothrs+$latetotalot[0];
													$latetotalotmin = $latetotalotmin+$latetotalot[1];	
												}
												
												
												
												}
											}
											 else if($rowday[0]==$weekdayRow[1] )
											{	
											    echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
											    $pdf->WriteHTML("<td width='40px'>WO</td>");
											}
										   /* else if(strtotime($rowattendance[3])<strtotime($rowshifttime[4]))
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
											}*/									
											else
											{
											  echo "<td style='color:red;font-size:14px;font-face:verdana;'>".substr($rowtimediff[0],0,6)."</td>";
											  $pdf->WriteHTML( "<td width='40px'>".substr($rowtimediff[0],0,6)."</td>");
											}
									//}
							
						}
									$latehours1  = floor($latetotalotmin/60); //round down to nearest minute. 
									$totallunch = $latetotalothrs + $latehours1;
									$lateminuteslunch = $latetotalotmin % 60;
									$latetotalwork = $totallunch.":".$lateminuteslunch;
							echo '<td>'.$latetotalwork.'</td></tr>';
							$pdf->WriteHTML( '<td width="70px">'.$latetotalwork.'</td></tr>');
					echo '<tr><td>Early Dept</td>';
					$pdf->WriteHTML('<tr><td width="70px">Early Dept</td>');
               //echo $lastday ;
			   $earlytotalwork =0;
							$earlyminuteslunch = 0;
							$earlytotallunch =0;
							$earlyhours1  =0;
							$$earlytotalotmin  =0;
							$earlytotalwork =0;
								$earlytotalot  = 0;
													$earlytotalothrs = 0;
													$earlytotalotmin = 0;	
			   $k=0;
               for($i=$firstday;$i<=$lastday;$i++) 
               {
									if($i<10)
									$i="0".$i;
								
								    $q = "Select (date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY));";
									$resday = exequery($q);
									$rowday = fetch($resday);

									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									$daytemp = $rowday[0];
									$k++;
								
								
								
									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									
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
									//echo $qryattendance."<br>";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);

									$arrtime=explode(' ',$rowattendance[3]);
									$arttime1 = $arrtime[1].':00';

									//echo $arttime1."<br>";
							
									if($rowattendance[8]!=null)
									{
									$shiftend=explode(' ',$rowattendance[8]);
									$shiftendnew=$shiftend[1].":00";
									}
									else if($rowattendance[6]!=null)
									{
									$shiftend=explode(' ',$rowattendance[6]);
									$shiftendnew=$shiftend[1].":00";
									}
									else
									{
									$shiftend=explode(' ',$rowattendance[4]);
									$shiftendnew=$shiftend[1].":00";
									}
										
										
								$shiftdiff="SELECT * FROM ShiftMaster S, Empshiftdetails E where S.shiftid=E.shiftid and E.empcode='".$row1[0]."' and '".$arttime1."'>=shiftstart and '".$arttime1."'<=firsthalfend";
								//echo $shiftdiff."<br>";
								$shiftdiffres = exequery($shiftdiff);
								$shiftdiffrow = fetch($shiftdiffres);

								if($shiftdiffrow==null)
									{
										$shiftdiff="SELECT * FROM ShiftMaster S, Empshiftdetails E where S.shiftid=E.shiftid and E.empcode='".$row1[0]."' and '".$arttime1."'<=shiftstart";
										//echo $shiftdiff."<br>";
										$shiftdiffres = exequery($shiftdiff);
										$shiftdiffrow = fetch($shiftdiffres);
									}
									
								$shiftstarttime = $shiftdiffrow[4];
								$shiftendtime   = $shiftdiffrow[7];
								$shifttotaltime = $shiftdiffrow[8];
								
								
								/*	$shiftdiff="SELECT * FROM ShiftMaster S, Empshiftdetails E where S.shiftid=E.shiftid and E.empcode='".$row1[0]."' and '".$shiftendnew."'>=secondhalfstart and '".$shiftendnew."'<=shiftend";
									echo $shiftdiff."<br>";
									$shiftdiffres = exequery($shiftdiff);
									$shiftdiffrow = fetch($shiftdiffres);

									$shiftstarttime = $shiftdiffrow[4];
									$shiftendtime   = $shiftdiffrow[7];
									$shifttotaltime = $shiftdiffrow[8];*/
									
									/*$EmpShiftMasterSql = "select * from Empshiftdetails where empcode = '".$row1[0]."' ";
									//echo $EmpShiftMasterSql."<br>";
									$EmpShiftMasterRes = exequery($EmpShiftMasterSql);
									$EmpShiftMasterRow = fetch($EmpShiftMasterRes);
								
									$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$EmpShiftMasterRow[2]."'";
									//echo $ShiftMasterSql."<br>";
									$ShiftMasterRes = exequery($ShiftMasterSql);
									$rowshifttime = fetch($ShiftMasterRes);

									$shiftstarttime = $rowshifttime[4];
									$shiftendtime   = $ShiftMasterRow[7];
									$shifttotaltime = $ShiftMasterRow[8];*/
									
									/*if(strtotime($rowattendance[6])<strtotime($rowshifttime[7]))
									{*/
										
										$empshiftend=$rowattendance[6];
										
										if($shiftendtime>'24:00:00')
										{
											$curday=Date('Y-m-d', strtotime("+1 day"));
											$newdaytemp=Date($daytemp, strtotime("+1 day"));
											//echo $curday."<br>";
											//echo $newdaytemp."<br>";

										}
										else
										{
											$newdaytemp=$daytemp;
										}
										
										$qrytimediff = "SELECT TIMEDIFF('$newdaytemp $shiftendtime','$empshiftend')";
										//echo $qrytimediff."<br>";
										$restimediff = exequery($qrytimediff);
										$rowtimediff = fetch($restimediff);
										//echo $rowtimediff[0]."<br>";
										
										
										$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','$daytemp 00:11:00')";
										//echo $qrytimediff1."<br>";
										$restimediff1 = exequery($qrytimediff1);
										$rowtimediff1 = fetch($restimediff1);
										$tempdata = substr($rowtimediff1[0],0,1);
										
										$earlydept = substr($rowtimediff[0],0,1);
										if($earlydept!='-')
										{
										$earlydept = substr($rowtimediff[0],0,5);
										}
										else
										{
										$earlydept = substr($rowtimediff[0],0,6);
										}
									
										/*if($rowattendance[3]!=null)
										{*/
											if($tempdata!='-')
											{
												if($earlydept=="")	
												{
													if($rowday[0]==$weekdayRow[1])
													{	
													   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
													   $pdf->WriteHTML("<td width='40px'>WO</td>");
													}
													else
													{
														echo"<td style='color:blue;font-size:12px;font-face:verdana;'>--</td>";
														$pdf->WriteHTML("<td width='40px'>--</td>");
													}												
												}	
												else
												{	
													echo "<td style='color:red;font-size:14px;font-face:verdana;'>".$earlydept."</td>";
													$pdf->WriteHTML("<td width='40px'>".$earlydept."</td>");
													
													if(substr($earlydept,0,1)!="-")
													{	
													$earlytotalot  =  explode(':',$earlydept);
													$earlytotalothrs = $earlytotalothrs+$earlytotalot[0];
													$earlytotalotmin = $earlytotalotmin+$earlytotalot[1];	
													}
													
												}
											}
											else if($rowday[0]==$weekdayRow[1] && $rowattendance[3]=='' )
											{	
											   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
											   $pdf->WriteHTML("<td width='40px'>WO</td>");
											}
											else
											{
												echo "<td style='color:red;font-size:14px;font-face:verdana;'>".substr($rowtimediff[0],0,6)."</td>";
												$pdf->WriteHTML( "<td width='40px'>".substr($rowtimediff[0],0,6)."</td>");
											}
										//}
									
										/*else if(strtotime($rowattendance[6])>strtotime($rowshifttime[7]))
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
										}*/
								}
								
							$earlyhours1  = floor($earlytotalotmin/60); //round down to nearest minute. 
							$earlytotallunch = $earlytotalothrs + $earlyhours1;
							$earlyminuteslunch = $earlytotalotmin % 60;
							$earlytotalwork = $earlytotallunch.":".$earlyminuteslunch;		
							
						echo '<td>'.$earlytotalwork.'</td></tr>';
						$pdf->WriteHTML( '<td width="70px">'.$earlytotalwork.'</td></tr>');
						
				echo '<tr><td>Lunch Break</td>';
				$pdf->WriteHTML('<tr><td width="70px">Lunch Break</td>');
				$totallunchwork = 0;
				$toatllunchmin = 0; 
				//echo $lastday ;
				$k=0;
               for($i=$firstday;$i<=$lastday;$i++) 
               {
	                        if($i<10)
								
									$i="0".$i;
									
									$q = "Select (date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY));";
									$resday = exequery($q);
									$rowday = fetch($resday);

									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									$daytemp = $rowday[0];
									$k++;
									
									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									
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
								//	echo $LunchHrSql."<br>";
									$LunchHrRes = exequery($LunchHrSql);
									$LunchHrRow = fetch($LunchHrRes);
									//echo $LunchHrRow[0].'<br>';


			      				
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
									
									//if($rowattendance!=NULL)
									if($totallunchworkhrs!='00:00')
									{
										if($totallunchworkhrs=="")
										{	
									    echo"<td style='color:black;font-size:12px;font-face:verdana;'>--</td>";
									    $pdf->WriteHTML("<td width='40px'>--</td>");
										}
										else
										{
											  echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$totallunchworkhrs."</td>";
											$pdf->WriteHTML("<td width='40px'>".$totallunchworkhrs."</td>");
										}
									}
									else if($rowday[0]==$weekdayRow[1] && $rowattendance[3]=='' )
									{	
									   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
									   $pdf->WriteHTML("<td width='40px'>WO</td>");
									}
									else if($LeaveRow!=NULL)
									{
									    echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
									    $pdf->WriteHTML("<td width='40px'>".$LeaveRow[4]."</td>");
									}
									else if($HolidayMasterRow!=NULL)
									{
										
										echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
										$pdf->WriteHTML("<td width='40px'>HL</td>");
									}									
									else
									{
									   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
									   $pdf->WriteHTML("<td width='40px'>A</td>");
									}
								}
									$hours1  = floor($toatllunchmin/60); //round down to nearest minute. 
									$totallunch = $totallunchwork + $hours1;
									$minuteslunch = $toatllunchmin % 60;
									$lunchtotalwork = $totallunch.":".$minuteslunch;
									 
									echo '<td width="70px">--</td></tr>';
									$pdf->WriteHTML('<td width="70px">--</td></tr>');
									 
					
					   echo '<tr><td>Work Hrs</td>';
					   $pdf->WriteHTML( '<tr><td width="70px">Work Hrs</td>');
						$totalwork = 0;
						$toatlmin = 0;
               //echo $lastday ;
			   $k=0;
               for($i=$firstday;$i<=$lastday;$i++) 
               {
	                        if($i<10)
									$i="0".$i;
								
								    $q = "Select (date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY));";
									$resday = exequery($q);
									$rowday = fetch($resday);

									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									$daytemp = $rowday[0];
									$k++;
									
						            //$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									
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
										if($totalworkhrs1!='00:00')
									{
										if($totalworkhrs1=="")
										{
									    echo"<td style='color:black;font-size:12px;font-face:verdana;'>--</td>";
									    $pdf->WriteHTML("<td width='40px'>--</td>");
										}
										else
										{
										echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$totalworkhrs1."</td>";
									    $pdf->WriteHTML("<td width='40px'>".$totalworkhrs1."</td>");
										}									
									}
									else if($rowday[0]==$weekdayRow[1] && $rowattendance[3]=='')
									{	
									   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
									   $pdf->WriteHTML("<td width='40px'>WO</td>");
									}
									else if($LeaveRow!=NULL)
									{
									    echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
									    $pdf->WriteHTML("<td width='40px' >".$LeaveRow[4]."</td>");
									}
									else if($HolidayMasterRow!=NULL)
									{
										echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
										$pdf->WriteHTML("<td width='40px'>HL</td>");
									}									
									else
									{
									   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
									   $pdf->WriteHTML("<td width='40px'>A</td>");
									}
				}
									$hours  = floor($toatlmin/60); //round down to nearest minute. 
									$total = $totalwork + $hours;
									$minutes = $toatlmin % 60;
									$totalwork = $total.":".$minutes;
									$actualworkhrs= 26*7;
					echo '<td>'.$totalwork.' <br>-----------<br> <font color="green">'.$actualworkhrs.'</font></td></tr>';
					$pdf->WriteHTML( '<td width="70px">'.$totalwork.'/'.$actualworkhrs.'</td></tr>');
					
					echo '<tr><td>Over Time</td>';
					$pdf->WriteHTML('<tr><td width="70px">Over Time</td>');
						$totalothrs = 0;
						$totalotmin = 0;
						
						$k=0;
					for($i=$firstday;$i<=$lastday;$i++) 
						{
	                        if($i<10)
								
									$i="0".$i;
									
									$q = "Select (date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY));";
									$resday = exequery($q);
									$rowday = fetch($resday);

									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									$daytemp = $rowday[0];
									$k++;
									
									
									
						            //$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									
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
									
									/*$EmpShiftMasterSql = "select * from Empshiftdetails where empcode = '".$row1[0]."' ";
									//echo $EmpShiftMasterSql."<br>";
									$EmpShiftMasterRes = exequery($EmpShiftMasterSql);
									$EmpShiftMasterRow = fetch($EmpShiftMasterRes);
									
									  $ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$EmpShiftMasterRow[2]."'";
									  $ShiftMasterRes = exequery($ShiftMasterSql);
									  $rowshifttime = fetch($ShiftMasterRes);*/
									  
									  
									$arrtime=explode(' ',$rowattendance[3]);
									$arttime1 = $arrtime[1].':00';

									$shiftdiff="SELECT * FROM ShiftMaster S, Empshiftdetails E where S.shiftid=E.shiftid and E.empcode='".$row1[0]."' and '".$arttime1."'>=shiftstart and '".$arttime1."'<=firsthalfend";
									//echo $shiftdiff."<br>";
									$shiftdiffres = exequery($shiftdiff);
									$shiftdiffrow = fetch($shiftdiffres);
									
									if($shiftdiffrow==null)
									{
										$shiftdiff="SELECT * FROM ShiftMaster S, Empshiftdetails E where S.shiftid=E.shiftid and E.empcode='".$row1[0]."' and '".$arttime1."'<=shiftstart";
										//echo $shiftdiff."<br>";
										$shiftdiffres = exequery($shiftdiff);
										$shiftdiffrow = fetch($shiftdiffres);
									}

									$shiftstarttime = $shiftdiffrow[4];
									$shiftendtime   = $shiftdiffrow[7];
									$shifttotaltime = $shiftdiffrow[8];
			                  
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
									
								/*$FirstHalfSql = "select TIMEDIFF ('".$firsthalfend1."','".$firsthalfstart1."')";
								echo $FirstHalfSql."A<br>";
								$FirstHalfRes = exequery($FirstHalfSql);
								$FirstHalfRow = fetch($FirstHalfRes);

								$SecondHalfSql = "select TIMEDIFF ('".$secondhalfend1."','".$secondhalfstart1."')";
								echo $SecondHalfSql."B<br>";
								$SecondHalfRes = exequery($SecondHalfSql);
								$SecondHalfRow = fetch($SecondHalfRes);

								$TotalWorkHoursSql = "select ADDTIME ('".$FirstHalfRow[0]."','".$SecondHalfRow[0]."')";
								echo $TotalWorkHoursSql."C<br>";
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
									echo $qryovertime."<br>";
									$resovertime = exequery($qryovertime);
									$rowovertime = fetch($resovertime);
									echo $rowovertime[0].'<br>';
									*/
									
									if($rowattendance[6]!='')
									{
										$shiftendemp=$rowattendance[6];
									}
									else
									{
										$shiftendemp=$rowattendance[4];
									}
									
									$Workinqsql = "select TIMEDIFF ('".$shiftendemp."','".$rowattendance[3]."')";
									//echo $Workinqsql."<br>";
									$WorkinqsqlRes = exequery($Workinqsql);
									$WorkinqsqlRow = fetch($WorkinqsqlRes);

									 $Workinqtime = $WorkinqsqlRow[0];
									 
									$OvertimeSql = "select TIMEDIFF ('".$Workinqtime."','".$shifttotaltime."')";
									//echo $OvertimeSql.'<br>';
									$OvertimeRes = exequery($OvertimeSql);
									$OvertimeRow = fetch($OvertimeRes);					 
									//$rowovertime = substr($OvertimeRow[0],0,6);
									
									$rowovertime = substr($OvertimeRow[0],0,1);
									if($rowovertime!='-')
									{
										$rowovertime = substr($OvertimeRow[0],0,5);
									}
									else
									{
										$rowovertime = substr($OvertimeRow[0],0,6);
									}
								
									//echo $rowovertime.'<br>';
									
									if($rowovertime!=NULL)
									{
									    echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$rowovertime."</td>";
									    $pdf->WriteHTML("<td width='40px'>".$rowovertime."</td>");
										$totalot  =  explode(':',$rowovertime);
										$totalothrs = $totalothrs+$totalot[0];
										$totalotmin = $totalotmin+$totalot[1];	
									}
									else if($rowday[0]==$weekdayRow[1] && $rowattendance[3]=='')
									{	
									   echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
									   $pdf->WriteHTML("<td width='40px'>WO</td>");
									}
									else if($LeaveRow!=NULL)
									{
									    echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
									    $pdf->WriteHTML("<td width='40px'>".$LeaveRow[4]."</td>");
									}
									else if($HolidayMasterRow!=NULL)
									{
										echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
										$pdf->WriteHTML("<td width='40px'>HL</td>");
									}									
									else
									{
									   echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
									   $pdf->WriteHTML("<td width='40px'>A</td>");
									}
						}
									$hoursot  = floor($totalotmin/60); //round down to nearest minute. 
									$totalothrs1 = $totalothrs + $hoursot;
									$totalotmin1 = $totalotmin % 60;
									$totalotwork = $totalothrs1.":".$totalotmin1;
									echo '<td>'.$totalotwork.'</td></tr>';
									$pdf->WriteHTML( '<td width="70px">'.$totalotwork.'</td></tr>');
					
						echo '<tr><td>Status</td>';
						$pdf->WriteHTML('<tr><td width="70px">Status</td>');
						//echo $lastday ;
						$k=0;
							for($i=$firstday;$i<=$lastday;$i++) 
							{
									if($i<10)
									$i="0".$i;
								
								    $q = "Select (date_add('".DMYtoYMD($frmdate)."',INTERVAL $k DAY));";
									$resday = exequery($q);
									$rowday = fetch($resday);

									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;
									$daytemp = $rowday[0];
									$k++;
								
								
								
									//$daytemp = $_POST['year'].'-'.$_POST['month']."-".$i;

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
									//echo $qryattendance."<br>";
									$resattendance = exequery($qryattendance);
									$rowattendance = fetch($resattendance);
									
									$arrtime=explode(' ',$rowattendance[3]);
									$arttime1 = $arrtime[1].':00';
									
									$shiftdiff="SELECT * FROM ShiftMaster S, Empshiftdetails E where S.shiftid=E.shiftid and E.empcode='".$row1[0]."' and '".$arttime1."'>=shiftstart and '".$arttime1."'<=firsthalfend";
									//echo $shiftdiff."<br>";
									$shiftdiffres = exequery($shiftdiff);
									$shiftdiffrow = fetch($shiftdiffres);
									
									if($shiftdiffrow==null)
									{
										$shiftdiff="SELECT * FROM ShiftMaster S, Empshiftdetails E where S.shiftid=E.shiftid and E.empcode='".$row1[0]."' and '".$arttime1."'<=shiftstart";
										//echo $shiftdiff."<br>";
										$shiftdiffres = exequery($shiftdiff);
										$shiftdiffrow = fetch($shiftdiffres);
									}
									
									$emptotalshift=$shiftdiffrow[8];
									//echo $emptotalshift.'<br>';
									
									$FirstHalfSql = "select TIMEDIFF ('".$rowattendance[4]."','".$rowattendance[3]."')";
									//echo $FirstHalfSql."A<br>";
									$FirstHalfRes = exequery($FirstHalfSql);
									$FinalSecondHalfRow = fetch($FirstHalfRes);
									$firsthalf = $FinalSecondHalfRow[0];

									$SecondHalfSql = "select TIMEDIFF ('".$rowattendance[6]."','".$rowattendance[5]."')";
									//echo $SecondHalfSql."B<br>";
									$SecondHalfRes = exequery($SecondHalfSql);
									$FinalSecondHalfRow = fetch($SecondHalfRes);
									$sechalf = $FinalSecondHalfRow[0];
									
									$TotalWorkHoursSql = "select ADDTIME ('".$firsthalf."','".$sechalf."')";
									//echo $TotalWorkHoursSql.'<br>';
									$TotalWorkHoursRes = exequery($TotalWorkHoursSql);
									$TotalWorkHoursRow = fetch($TotalWorkHoursRes);
									$totalempwork=$TotalWorkHoursRow[0];
									
									//echo $totalempwork.'<br>';
									//echo $emptotalshift.'<br>';
								
					
										if(strtotime($firsthalf)>=strtotime("03:15:00"))
										{
											$finalresfirst ="P";
										}
										else
										{
											$finalresfirst ="A";
										}					
										if(strtotime($sechalf)>=strtotime("03:15:00"))
										{
											$secfirst ="P";
										}
										else
										{
											$secfirst ="A";
										}

										if(strtotime($totalempwork)>=strtotime("07:00:00"))
										{
											$result="PP";
											$txtcolor = "green";
										}
										else if($rowattendance[18]==1)
										{
											$result="PP";
											$txtcolor = "green";
										}
										else if($rowattendance[18]==2)
										{
											$result="PP";
											$txtcolor = "green";
										}
										else
										{
											$result= $finalresfirst.$secfirst;
										} 

									if($rowattendance[3]!=NULL)
									{
										$result=$rowattendance[16].$rowattendance[17];
										echo"<td style='color:$txtcolor;font-size:12px;font-face:verdana;'>".$result."</td>";
										$pdf->WriteHTML("<td width='40px'>".$result."</td>");
									}
									else if($rowday[0]==$weekdayRow[1])
									{	
										echo"<td style='color:blue;font-size:12px;font-face:verdana;'>WO</td>";
										$pdf->WriteHTML("<td width='40px'>WO</td>");
									}
									else if($LeaveRow!=NULL)
									{
										echo"<td style='color:orange;font-size:12px;font-face:verdana;'>".$LeaveRow[4]."</td>";
										$pdf->WriteHTML("<td width='40px'>".$LeaveRow[4]."</td>");
									}
									else if($HolidayMasterRow!=NULL)
									{
										echo"<td style='color:green;font-size:12px;font-face:verdana;'>HL</td>";
										$pdf->WriteHTML("<td width='40px'>HL</td>");
									}									
									else
									{
										echo"<td style='color:red;font-size:12px;font-face:verdana;'>A</td>";
										$pdf->WriteHTML("<td width='40px'>A</td>");
									}
							}
					echo '<td></td></tr>';
					$pdf->WriteHTML( '<td width="70px">--</td></tr>');
				echo '</table>';
			  $pdf->WriteHTML( "<br><hr>");
			  $pa++;
			  if($pa==3)
			  { 
				$page++;
				$pdf->WriteHTML( "Page No ". $page." <br>");
				$pdf->SetFont('Arial','B',12);
				$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");			
				$pdf->WriteHTML( " Attendance List  Form   ".$frmdate." to ".$todate."</center><br><br>");
				$pdf->WriteHTML( " <hr>");
			
				$pa=0;
			  }

 	 }
	

	$pdf->WriteHTML( "</table>");
	     $pdf->Output("attendencereport/$filename.pdf","F");
 	}
  
		 
		
	
//----------------------------------------------------------    absent     ----------------------------------------------------------------------//	
	
	
	//echo $htmloutput;
 if($_POST['misreport']=='absent') 
   {
		$updateq1 ="UPDATE Attendancechk SET firsthalfstatus='P' where attendancebit>0 ;";
		exequery($updateq1);

		$updateq2 ="UPDATE Attendancechk SET secondhalfstatus='P' where attendancebit>0 ;";
		exequery($updateq2);
		
		$pdf=new PDF('L', 'mm', 'A3');
		$pdf->SetFont('Arial','',10);
		$pdf->AddPage();
		   $pa=0;
		  $page=1; 
   	      $time = mktime(0, 0, 0, $_POST['month']);
		  $name = strftime("%b", $time);
		  echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'>Absent Memo between ".$_POST['frmdate']." to ".$_POST['todate']." </p> </center><br>";
		 
		$pdf->WriteHTML( "Page No ". $page." <br>");
		$pdf->SetFont('Arial','B',12);
		$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");
	
		$pdf->WriteHTML( " Absent Memo  between   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
		$pdf->WriteHTML( " <hr>");

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
    	   
 //  echo  "<center> <p class='btn btn-info' style='font-color:white;font-weight:bold;font-size:16px;'>Region : ".$branch." &nbsp;&nbsp;&nbsp; Department :  ".$department." &nbsp;&nbsp;&nbsp; Company : ".$company." </p></center><br>";
 
   $pdf->SetFont('Arial','',8); 
	echo  '<center><table class="table table-bordered" border="1" style="width:75%"><tr><td colspan=20>';
	echo"Absent Memo  between ".$_POST['frmdate']." to ".$_POST['todate']."</td><td colspan='3'><input type='text' class='form-control' name='empname' id='empname' value='' placeholder='Name' /></td><td colspan=7><input type='text' class='form-control' name='emailtext' id='emailtext' value='' placeholder='Email'/></td><td colspan=1>";
	?>
	<input type='button' class='btn btn-info' value='Send MAIL' onclick="sendleavemailEmp('<? echo $filename; ?>');"></td></tr>
	<?
	$pdf->WriteHTML("<table  border=1><tr bgcolor='#1FB5AD' ><td  width='70px'></td>");
 
   $frmdate = DMYtoYMD($_POST['frmdate']);
   $todate  = DMYTOYMD($_POST['todate']);
   $date=$_POST['year']."-".$_POST['month'];
   $fromempcode1 = explode(':',$_POST['fromempcode']);
   $fromempcode  = $fromempcode1[0];
   $toempcode1 = explode(':',$_POST['toempcode']);
   $toempcode  = $toempcode1[0];
   
   $depttest ='';
   $loctest ='';
   $comptest ='';
   
   $_POST['year'] = substr($frmdate,0,4);
   $_POST['month'] = substr($frmdate,8,2);
 
   $datetyep = $_POST['year']."-".$_POST['month']."-";
   
   //echo $datetyep;
		 
		 
	$StaffMasterSql = "select * from StaffMaster where lefts!=1 ";
	  if(($fromempcode!='') && ($toempcode!=''))
		    $StaffMasterSql.=" and  ((empcode BETWEEN ('".$fromempcode."') AND ('".$toempcode."')) )";
     if( $_POST['fromdepartment']!='')  
    	    $StaffMasterSql.="and deptid ='".$_POST['fromdepartment']."' ";
	  if($_POST['subdepartment']!='')  
    	    $StaffMasterSql.=" and subdeptid ='".$_POST['subdepartment']."' ";	
     if(($_POST['fromlocation']!='') && ($_POST['tolocation']!=''))
    	    $StaffMasterSql.="and ((region BETWEEN ('".$_POST['fromlocation']."') AND ('".$_POST['tolocation']."')) )";
	 if(($_POST['frombranch']!='') && ($_POST['tobranch']!=''))
    	    $StaffMasterSql.="and ((branch BETWEEN ('".$_POST['frombranch']."') AND ('".$_POST['tobranch']."')) )";	
		
     if($_POST['company']!='all')
    	    $StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";
		
		 $StaffMasterSql.=" Order BY region,deptid,Empcompanyid";
	 //echo $StaffMasterSql.'<br>';
		 
 
    	   $StaffMasterRes = exequery($StaffMasterSql);
    	   while($StaffMasterRow = fetch($StaffMasterRes))
    	   {
			   	if($_POST['departmenttype']=='department')
				 {
					 //echo'coming department';
					 if($depttest!=$StaffMasterRow['deptid'])
					 {
						 $qrydept2 = "select * from DepartmentMaster1 where departmentid='".$StaffMasterRow['deptid']."'";
						 $resdept2 = exequery($qrydept2);
						 $rowdept2 = fetch($resdept2);
						 
							echo"<tr><td style='color:#1fb5ad;font-weight:bold' colspan=2>DEPARTMENT :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Department : ".$rowdept2[1]."<br>");
			
			
						 
					 }
				 }
				 $depttest=$StaffMasterRow['deptid'];
				 
				 if($_POST['locationtype']=='location')
				 {
					 if($loctest!=$StaffMasterRow['region'])
					 {
						 $qrydept2 = "select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
						 $resdept2 = exequery($qrydept2);
						 $rowdept2 = fetch($resdept2);
						 
							echo"<tr><td style='color:#1fb5ad;font-weight:bold' colspan=2>LOCATION :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Location : ".$rowdept2[1]."<br>");
						 
					 }
				 }
				 $loctest=$StaffMasterRow['region'];
				 
				  if($_POST['companytype']=='company')
				 {
					 if($comptest!=$StaffMasterRow['Empcompanyid'])
					 {
						 $qrydept2 = "SELECT * FROM EmpCompanyMaster where empcompanyid='".$StaffMasterRow['Empcompanyid']."'";
						 $resdept2 = exequery($qrydept2);
						 $rowdept2 = fetch($resdept2);
						
							echo"<tr><td style='color:#1fb5ad;font-weight:bold' colspan=2>COMPANY :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Company : ".$rowdept2[1]."<br>");
						 
					 }
				 }
				 $comptest=$StaffMasterRow['Empcompanyid'];



				$region="select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
				$resregion=exequery($region);
				$rowregion=fetch($resregion);
								
				$branch="select * from BranchMaster1 where branchid='".$StaffMasterRow['branch']."'";
				$resbranch=exequery($branch);
				$rowbranch=fetch($resbranch);

				$qrydept1 = "select * from DepartmentMaster1 where departmentid = '".$StaffMasterRow['deptid']."'";
				$resdept1 = exequery($qrydept1);
				$rowdept1 = fetch($resdept1);

				$datetyep = $_POST['year']."-".$_POST['month']."-";
				$qry = "select *   from Attendancechk where ( attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'  and firsthalfstatus='A' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime!=0 and secondhalftime!=0) or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'  and  firsthalfstatus='P' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime!=0 and secondhalftime='') or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'  and  firsthalfstatus='P' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0)or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'  and  firsthalfstatus='A' and secondhalfstatus='P' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime='' and secondhalftime!=0) or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'  and  firsthalfstatus='A' and secondhalfstatus='P' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0)or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."' and firsthalfstatus='A' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime='' and secondhalftime='') or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."' and firsthalfstatus='A' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime!='' and secondhalftime='') or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."' and firsthalfstatus='A' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime='' and secondhalftime!='') or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."' and firsthalfstatus='A' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."'  )order by attendancedate desc";
				
				//echo $qry."<br>";
				
				//$qry = "SELECT * FROM Attendancechk where attendancedate like'%$datetyep%'";
				
				$res = exequery($qry);
				while($row=fetch($res))
				{	

					$_POST['month']=substr($_POST['frmdate'],3,2);
					$_POST['year']=substr($_POST['frmdate'],6,4);

					
					$qrytemp = "select * from attendance3 where empcode='".$row[1]."' and month='".$_POST['month']."' and year='".$_POST['year']."' ";
					 
					$restemp = exequery($qrytemp);
					$rowtemp = fetch($restemp);
					if($rowtemp==null)
						exequery("insert attendance3  (companyid, empcode, month,year) values ('".$StaffMasterRow[8]."','".$row[1]."','".$_POST['month']."','".$_POST['year']."' )");
					//for($i=1;$i<=31;$i++)
					{
							$result = "";
							$datetyepmain = $row[2];
							$qry1 = "SELECT * FROM Attendancechk where attendancedate ='$datetyepmain' and  empcode='".$row[1]."' ";							
							$res1 = exequery($qry1);
							while($row1=fetch($res1))
							{
								$day = substr($row1[2],8,2);
								$day =$day*2;
								$day =$day/2;
								
								$result1 = $row1[16];
								$result2 = $row1[17];
								
								$result = $result1.$result2;
								//echo "---->".$result;
								//echo "<br>";
							
								if($row1[18]==2)
								{

								$result="PP";
								}
								if($row1[18]==1)
								{  
								$result="PP";
								}	

								$Workinqtime=substr($row1[15],0,5);
								
								
								
								$weekdayQry = "select * from weekdays where id='".$StaffMasterRow[20]."'";
								$weekdayRes = exequery($weekdayQry);
								$weekdayRow = fetch($weekdayRes);

								$qryday2 = "SELECT  UPPER(DAYNAME('".$row1[2]."')) AS DAY";
								$resday2 = exequery($qryday2);
								$rowday2 = fetch($resday2);
									
								
								
								$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$row1[2]."' and  ( E.edition='".$StaffMasterRow['region']."' or E.edition='0')  ";
								//echo $HolidayMasterSql ;
								$HolidayMasterRes = exequery($HolidayMasterSql);
								$HolidayMasterRow = fetch($HolidayMasterRes);

								$LeaveSql = "select * from LeaveTransaction where empcode='".$row[1]."' and '".$row1[2]."' >=frmdate and '".$row1[2]."'<=todate and leavetype!='CO'";					
								$LeaveRes = exequery($LeaveSql);
								$LeaveRow = fetch($LeaveRes);

								$LeaveSql1 = "select * from LeaveTransaction where empcode='".$row[1]."' and '".$row1[2]."' >=frmdate and '".$row1[2]."'<=todate and leavetype='CO'";
								$LeaveRes1 = exequery($LeaveSql1);
								$LeaveRow1 = fetch($LeaveRes1);

								if(($rowday2[0]==strtoupper($weekdayRow[1])))
								{
								$result = "WO";	
									
								}
								else  if(($HolidayMasterRow!=NULL) )
								{
								$result = "HL";		
										

								}
								if(strtotime($Workinqtime)<=strtotime("07:00"))
								{
									$fresult="P";
									$sresult="A";								
									$qryss =  "update Attendancechk set firsthalfstatus='".$fresult."',secondhalfstatus='".$sresult."' where empcode= '".$row[1]."' and attendancedate='".$row1[2]."' and attendancebit=0";
									//echo "<tr><td>".$qryss."</td></tr>";
									exequery($qryss);			
								}
								
								if(strtotime($row1[13])<=strtotime("03:10"))
								{
									$fresult="A";							
									$qryss =  "update Attendancechk set firsthalfstatus='".$fresult."' where empcode= '".$row[1]."' and attendancedate='".$row1[2]."' and attendancebit=0";
									//echo "<tr><td>".$qryss."</td></tr>";
									exequery($qryss);								

								}
								else
									{
									$fresult="P";							
									$qryss =  "update Attendancechk set firsthalfstatus='".$fresult."' where empcode= '".$row[1]."' and attendancedate='".$row1[2]."' and attendancebit=0";
									//echo "<tr><td>".$qryss."</td></tr>";
									exequery($qryss);								

								}
								
								if(strtotime($row1[14])<=strtotime("03:10"))
								{
									$sresult="A";							
									$qryss =  "update Attendancechk set secondhalfstatus='".$sresult."' where empcode= '".$row[1]."' and attendancedate='".$row1[2]."' and attendancebit=0";
									//echo "<tr><td>".$qryss."</td></tr>";
									exequery($qryss);								
								}
								else
								{
									$sresult="P";							
									$qryss =  "update Attendancechk set secondhalfstatus='".$sresult."' where empcode= '".$row[1]."' and attendancedate='".$row1[2]."' and attendancebit=0";
									//echo "<tr><td>".$qryss."</td></tr>";
									exequery($qryss);		
								}
								
								
								if(strtotime($row1[15])>=strtotime("07:00"))
								{
									$fresult="P";
									$sresult="P";								
									$qryss =  "update Attendancechk set firsthalfstatus='".$fresult."',secondhalfstatus='".$sresult."' where empcode= '".$row[1]."' and attendancedate='".$row1[2]."' and attendancebit=0";
									//echo "<tr><td>".$qryss."</td></tr>";
									exequery($qryss);								
								}
								
								
								if($result=="PP")
								$result="P";
								
								if($result=="AA")
								$result="A";
							
								if($result=="CLCL")
								$result="CL";
							
								if($result=="PLPL")
								$result="PL";
							
								if($result=="ODOD")
								$result="OD";
							
							    if($result=="SLSL")
								$result="SL";
							
							
							  if($result=="COCO")
								$result="CO";
								$fieldname="d".$day;
							 	
								
							
								exequery("update attendance3   set $fieldname='".$result."' where  empcode='".$row[1]."' and  month='".$_POST['month']."' and year='".$_POST['year']."' ");
								
							
							}
							
					}
				}
			
				 
						
						
						
						
						
						
			 	
						
						
    	     	$days = 0;
				$data ="";
				$data1 ="";
				
				
				$qryabsent = "select *   from Attendancechk where ( attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'  and firsthalfstatus='A' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime!=0 and secondhalftime!=0) or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'  and  firsthalfstatus='P' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime!=0 and secondhalftime='') or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'  and  firsthalfstatus='P' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0)or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'  and  firsthalfstatus='A' and secondhalfstatus='P' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime='' and secondhalftime!=0) or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'  and  firsthalfstatus='A' and secondhalfstatus='P' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0)or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."' and firsthalfstatus='A' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime='' and secondhalftime='') or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."' and firsthalfstatus='A' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime!='' and secondhalftime='') or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."' and firsthalfstatus='A' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."' and attendancebit=0 and firsthalftime='' and secondhalftime!='') or (attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."' and firsthalfstatus='A' and secondhalfstatus='A' and empcode = '".$StaffMasterRow[0]."'  )order by attendancedate desc";
				//echo $qryabsent;
				//echo "<br>";
				$SMSmonth = date('m', strtotime($_POST['frmdate']));
				$SMSyear = date('Y', strtotime($_POST['frmdate']));
				$smsdata = "ECode:".$StaffMasterRow[0]."\n";
				$smsdata = $smsdata."Month:".$SMSmonth." Year:".$SMSyear."\n"; 
				$smsdata = $smsdata.'day--status'."\n";
				$resabsent = exequery($qryabsent);
				$inccnt=0;
				while($rowabsent = fetch($resabsent))					
				{
					
					
					$weekdayQry = "select * from weekdays where id='".$StaffMasterRow[20]."'";
			   		$weekdayRes = exequery($weekdayQry);
			   		$weekdayRow = fetch($weekdayRes);

					$qryday = "SELECT  UPPER(DAYNAME('".$rowabsent[2]."')) AS DAY";
					//echo $qryday;
					$resday = exequery($qryday);
					$rowday = fetch($resday);
					$flag=0;	
					
					 if($weekdayRow[1]==$rowday[0])
						 $flag=1;
					 
					$qrytemps = "SELECT * FROM LeaveTransaction where empcode='".$StaffMasterRow[0]."' and  '".$rowabsent[2]."'>=frmdate and  '".$rowabsent[2]."'<=todate";
					$restemps = exequery($qrytemps);
					$rowtemps = fetch($restemps);
					if($rowtemps!=null)
						$flag=1;
					 
					if($flag==0)	
					{						
				        // $HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate>='".DMYtoYMD($_POST['frmdate'])."' and H.hdate<='".DMYtoYMD($_POST['todate'])."'";
				         $HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$rowabsent[2]."' and  ( E.edition='".$StaffMasterRow['region']."' or E.edition='0')";
						 
						 //echo $HolidayMasterSql.'<br>';
			             $HolidayMasterRes = exequery($HolidayMasterSql);
			             $HolidayMasterRow = fetch($HolidayMasterRes);
				      
						if($rowabsent[2] != $HolidayMasterRow[3])
						{
						
							$data1 = "<td width='40px'>".substr($rowabsent[2],8,2)."</td>".$data1;
							$data  = "<td width='40px'>".$rowabsent[16]." ".$rowabsent[17]."</td>".$data;
							
							$smsdata = $smsdata.substr($rowabsent[2],8,2)."---".$rowabsent[16]." ".$rowabsent[17]."\n";
							$days++;
							
						}	
						
					}	
						
				 }
					$smsdata = $smsdata."Please Fill up leave at earlist."."\n";
					$smsdata = $smsdata."From HRD-TBD";




				if($days>=1)
				 {
					    echo"<input type='hidden' id='empcode' name='empcode' value='".$StaffMasterRow[0]."'>";
						echo"<input type='hidden' id='frmdate' name='frmdate' value='".DMYtoYMD($_POST['frmdate'])."'>";
						echo"<input type='hidden' id='todate' name='todate' value='".DMYtoYMD($_POST['todate'])."'>";
						echo"<input type='hidden' id='departmenttype' name='departmenttype' value='".$_POST['departmenttype']."'>";
						echo"<input type='hidden' id='locationtype' name='locationtype' value='".$_POST['locationtype']."'>";
						echo"<input type='hidden' id='companytype' name='companytype' value='".$_POST['companytype']."'>";
						
						$newjoin ="select Doj from StaffMaster where empcode='".$StaffMasterRow[0]."'";
						$resnewjoin = exequery($newjoin);
						$rownewjoin = fetch($resnewjoin);
						$newjoin = substr($rownewjoin[0],0,8);
						$current = substr(DMYtoYMD($_POST['frmdate']),0,8);
						
						echo"<tr>";
						if($current == $newjoin)
						{
							echo"<td colspan=20 >Emp Code / Name :-  ".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."  </td><td style='color:red'>NEW JOINEE</td>";
						}
						else
						{
							echo"<td colspan=21 >Emp Code / Name :-  ".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."  </td>";
						}
						
						
						echo "<td colspan=8><input type='text' id='mobno".$StaffMasterRow['empcode']."' name='mobno".$StaffMasterRow['empcode']."' class='form-control'  value='".$StaffMasterRow['Contactno']."' /></td><td colspan=4><textarea style='display:none;' id='smsdataemp".$StaffMasterRow['empcode']."' name='smsdataemp".$StaffMasterRow['empcode']."'>".$smsdata."</textarea> <input type='button' class='btn btn-info' value='Send SMS' onclick='sendleavesmsEmp(".$StaffMasterRow['empcode'].");'></td></tr>";
						echo" <tr>
						<td colspan=10> Dept :- ".$rowdept1[1]." </td><td colspan=10> Branch :-   ".$rowbranch[1]."  </td><td colspan=10> Region :- ".$rowregion[1]."</td><td colspan=2><input type='button' class='btn btn-primary' value='Mark as Leave' onclick='makeasleave(".$StaffMasterRow[0].");'></td><td colspan=2><input type='button' class='btn btn-info' value='Correction' onclick='correction(".$StaffMasterRow[0].");'></td>
						</tr> ";

						$pdf->SetFont('Arial','B',10);
						$pdf->WriteHTML("Emp Code / Name :-".$StaffMasterRow[0]."/".$StaffMasterRow[1]."     Dept :- ".$rowdept1[1]."     Branch :-   ".$rowbranch[1]."    Region :- ".$rowregion[1]."<br>");							
						$pdf->WriteHTML("You have been marked absent on the following dates. Please fill up OD/LEAVES at earliest.<br><br>");	
						$pdf->SetFont('Arial','',8);
						$pdf->WriteHTML("<tr><td width='100px'>Date </td>".$data1."</tr>");							
						$pdf->WriteHTML("<tr><td width='100px'>Remark </td>".$data."</tr>");	

						$pdf->WriteHTML("    <br>");	
						$pdf->WriteHTML(" Signature  <br>");	
						$pdf->WriteHTML("<tr><td colspan=31><hr></td></tr><br>");	
						
						
					

						//
						echo"<tr><td colspan=34 > You have been marked absent on the following dates. Please fill up OD/LEAVES at earliest.   </td></tr>";
						echo"<tr><td> Date </td>".$data1."</tr>";
						echo"<tr><td>Remark</td>".$data."</tr>";
						echo"<tr><td colspan=7 align='center'> <br> </td></tr>";
						echo"<tr><td colspan=7 align='center'>Signature </td></tr>";
						echo"<tr><td colspan=34><hr></td></tr>";
									//echo "<td>".$days."</td>";
						//	echo"</tr>";	
			$pa++; 
		   if($pa==5)
			  { 
		        $pdf->AddPage();
				$page++;
				$pdf->WriteHTML( "Page No ". $page." <br>");
				$pdf->SetFont('Arial','B',12);
				$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");			
				$pdf->WriteHTML( " Attendance List  Form   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
				$pdf->WriteHTML( " <hr>");
			
				$pa=0;
			  }
				 }
    	   //}//STAFF WHILE2
		   
    	 }//STAFF WHILE1
    		echo "</table></center><br>";	
			
			
			 
			
			$pdf->WriteHTML( "</table>");
	        $pdf->Output("attendencereport/$filename.pdf","F");
		?>	
			
			
		<?	
    	}
		
//--------------------------------------------------------   NO PUNCH    ------------------------------------------------------------------//		
		
		
 if($_POST['misreport']=='ab') 
   {
		$pdf=new PDF('L', 'mm', 'A3');
		$pdf->SetFont('Arial','',10);
		$pdf->AddPage();
		$pa=0;
		$page=1; 

		$time = mktime(0, 0, 0, $_POST['month']);
		$name = strftime("%b", $time);
		echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'>No Punch List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";
		$my = $_POST['year'].'-'.$_POST['month'];
		$month = $_POST['year']."-".$_POST['month']."-01";
		$lastday = date('t',strtotime($month));

		$pdf->WriteHTML( "Page No ". $page." <br>");
		$pdf->SetFont('Arial','B',12);
		$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");

		$pdf->WriteHTML( " No Punch List  between   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
		$pdf->WriteHTML( " <hr>");
        
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
  /*  echo  "<tr bgcolor='#A48AD4'>";
	echo	'<th style="color:white;font-size:16px;"> Emp Code</th> '; 
	echo	'<th style="color:white;font-size:16px;"> Employee Details </th>';
	echo  '<th style="color:white;font-size:16px;"> Date </th>';
	echo  '<th style="color:white;font-size:16px;">No. of Days</th>';
	echo  "</tr>"; */
 
	$pdf->SetFont('Arial','',8); 
	echo  '<center><table class="table table-bordered" border="1" style="width:75%"><tr><td colspan=31>';
	
	$pdf->WriteHTML("<table  border=1><tr bgcolor='#1FB5AD' ><td  width='70px'></td>");
	
   $frmdate = DMYtoYMD($_POST['frmdate']);
   $todate  = DMYTOYMD($_POST['todate']);
   $date=$_POST['year']."-".$_POST['month'];
   $fromempcode1 = explode(':',$_POST['fromempcode']);
   $fromempcode  = $fromempcode1[0];
   $toempcode1 = explode(':',$_POST['toempcode']);
   $toempcode  = $toempcode1[0];
   
   $depttest ='';
   $loctest ='';
   $comptest ='';
   
	
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
		 $StaffMasterSql.=" Order BY region,deptid,Empcompanyid";
 
    	   $StaffMasterRes = exequery($StaffMasterSql);
    	   while($StaffMasterRow = fetch($StaffMasterRes))
    	   {
			   
			   if($_POST['departmenttype']=='department')
				 {
					 //echo'coming department';
					 if($depttest!=$StaffMasterRow['deptid'])
					 {
						 $qrydept2 = "select * from DepartmentMaster1 where departmentid='".$StaffMasterRow['deptid']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>DEPARTMENT :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Department : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $depttest=$StaffMasterRow['deptid'];
				 
				 if($_POST['locationtype']=='location')
				 {
					 if($loctest!=$StaffMasterRow['region'])
					 {
						 $qrydept2 = "select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>LOCATION :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Location : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $loctest=$StaffMasterRow['region'];
				 
				  if($_POST['companytype']=='company')
				 {
					 if($comptest!=$StaffMasterRow['Empcompanyid'])
					 {
						 $qrydept2 = "SELECT * FROM EmpCompanyMaster where empcompanyid='".$StaffMasterRow['Empcompanyid']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>COMPANY :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Company : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $comptest=$StaffMasterRow['Empcompanyid'];

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
				$data1 ="";
				
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
										//$data = YMDtoDMY($daytemp)." , ".$data;
										$days++;	
										
										$data1 = "<td width='40px'>".substr($daytemp,8,2)."</td> ".$data1;
											
									   // echo"<td style='color:black;font-size:12px;font-face:verdana;'>".$rowattendance[16]."".$rowattendance[17]."</td>";
									}
									 								

								
				}
			if($days>0)
				 {
				 		/* 	echo"<tr>
									<td>".$StaffMasterRow[0]."</td>
									<td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td>";
									echo"<td>".$data."</td>";
									echo "<td>".$days."</td>";
							echo"</tr>"; */	
							echo" <tr>
									<td colspan=6 >Emp Code / Name :-  ".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."  <td><td colspan=5> Dept :- ".$rowdept1[1]." </td><td colspan=5> Branch :-   ".$rowbranch[1]."  </td><td colspan=15> Region :- ".$rowregion[1]."</td></tr> ";
									
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Emp Code / Name :-".$StaffMasterRow[0]."/".$StaffMasterRow[1]."     Dept :- ".$rowdept1[1]."     Branch :-   ".$rowbranch[1]."    Region :- ".$rowregion[1]."<br>");							
							$pdf->WriteHTML("You have been marked absent on the following dates. Please fill up OD/LEAVES at earliest.<br><br>");	
							$pdf->SetFont('Arial','',8);
                            $pdf->WriteHTML("<tr><td width='100px'>Date </td>".$data1."</tr>");							
        
                             	
                            $pdf->WriteHTML("    <br>");	
                            $pdf->WriteHTML(" Signature  <br>");	
                            $pdf->WriteHTML("<tr><td colspan=31><hr></td></tr><br>");	
							
									echo"<tr><td colspan=32 > You have been marked absent on the following dates. Please fill up OD/LEAVES at earliest.   </td> </tr>";
									echo"<tr><td> Date </td>".$data1."</tr>";
									echo"<tr><td colspan=7 align='center'> <br> </td></tr>";
									echo"<tr><td colspan=7 align='center'>Signature </td></tr>";
									echo"<tr><td colspan=32><hr></td></tr>";
									
									$pa++; 
								   if($pa==5)
									  { 
										$pdf->AddPage();
										$page++;
										$pdf->WriteHTML( "Page No ". $page." <br>");
										$pdf->SetFont('Arial','B',12);
										$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");			
										$pdf->WriteHTML( " Attendance List  Form   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
										$pdf->WriteHTML( " <hr>");
									
										$pa=0;
									  }
				 }	
    	   }
    		echo "</table></center><br>";	
			
			$pdf->WriteHTML( "</table>");
	        $pdf->Output("attendencereport/$filename.pdf","F");
    	}	

//---------------------------------------------------------  LATE Arrival   ----------------------------------------------------------------//		
		
		
		
		
if($_POST['misreport']=='latearrival') 
   {
	   $pdf=new PDF('L', 'mm', 'A3');
		$pdf->SetFont('Arial','',10);
		$pdf->AddPage();
		   $pa=0;
		  $page=1; 
   	  $time = mktime(0, 0, 0, $_POST['month']);
		  $name = strftime("%b", $time);
		  echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'>Late Arrival List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";
 	     $my = $_POST['year'].'-'.$_POST['month'];
 	     $month = $_POST['year']."-".$_POST['month']."-01";
        $lastday = date('t',strtotime($month));
        
		$pdf->WriteHTML( "Page No ". $page." <br>");
		$pdf->SetFont('Arial','B',12);
		$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");
	
		$pdf->WriteHTML( " No Punch List  between   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
		$pdf->WriteHTML( " <hr>");
		
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
							/*	echo "<tr bgcolor='sky blue'><th style='color:white;'>Emp Code</th>
								<th style='color:white;'>Employee Details</th>
								<th style='color:white;'>Date</th>
								<th style='color:white;'>No. of Days</th></tr>";*/
 	$pdf->SetFont('Arial','',8); 
	//echo  '<center><table class="table table-bordered" border="1" style="width:75%"><tr><td colspan=31>';
	
	$pdf->WriteHTML("<table  border=1><tr bgcolor='#1FB5AD' ><td  width='70px'></td>");
	
   $frmdate = DMYtoYMD($_POST['frmdate']);
   $todate  = DMYTOYMD($_POST['todate']);
   $date=$_POST['year']."-".$_POST['month'];
   $fromempcode1 = explode(':',$_POST['fromempcode']);
   $fromempcode  = $fromempcode1[0];
   $toempcode1 = explode(':',$_POST['toempcode']);
   $toempcode  = $toempcode1[0];
   
   
   $depttest ='';
   $loctest ='';
   $comptest ='';
	
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
		 $StaffMasterSql.=" Order BY region,deptid,Empcompanyid";
 
    	   $StaffMasterRes = exequery($StaffMasterSql);
    	   while($StaffMasterRow = fetch($StaffMasterRes))
    	   {
			   if($_POST['departmenttype']=='department')
				 {
					 //echo'coming department';
					 if($depttest!=$StaffMasterRow['deptid'])
					 {
						 $qrydept2 = "select * from DepartmentMaster1 where departmentid='".$StaffMasterRow['deptid']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>DEPARTMENT :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Department : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $depttest=$StaffMasterRow['deptid'];
				 
				 if($_POST['locationtype']=='location')
				 {
					 if($loctest!=$StaffMasterRow['region'])
					 {
						 $qrydept2 = "select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>LOCATION :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Location : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $loctest=$StaffMasterRow['region'];
				 
				  if($_POST['companytype']=='company')
				 {
					 if($comptest!=$StaffMasterRow['Empcompanyid'])
					 {
						 $qrydept2 = "SELECT * FROM EmpCompanyMaster where empcompanyid='".$StaffMasterRow['Empcompanyid']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>COMPANY :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Company : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $comptest=$StaffMasterRow['Empcompanyid'];

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
					$data1 = "";
					$datas1 = "";
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
									
									$qrytemp = "SELECT * FROM Empshiftdetails  where empcode ='".$StaffMasterRow[0]."'";
									$restemp = exequery($qrytemp);
									$rowtemp = fetch($restemp);
									

									$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$rowtemp[2]."'";
									$ShiftMasterRes = exequery($ShiftMasterSql);
									$rowshifttime = fetch($ShiftMasterRes);

									$shiftstarttime = $rowshifttime[4];
									$shiftendtime   = $ShiftMasterRow[7];
									$shifttotaltime = $ShiftMasterRow[8];
	
									while($rowlatearrival = fetch($reslatearrival))
									{
										$ShiftMasterSql = "select * from ShiftMaster where shiftid ='".$rowtemp[2]."'";
										$ShiftMasterRes = exequery($ShiftMasterSql);
										$rowshifttime = fetch($ShiftMasterRes);
										
										$qrydifference = "select TIMEDIFF('".$rowshifttime[4]."','".substr($rowlatearrival[3],11,6)."')";
										$resdifference = exequery($qrydifference);
										$rowdifference = fetch($resdifference);
										 
										if(substr($rowdifference[0],0,1)=="-")
										{
											$qrytimediff = "SELECT TIMEDIFF('$daytemp $rowlatearrival[3]','$daytemp $rowshifttime[4]')";
											$restimediff = exequery($qrytimediff);
											$rowtimediff = fetch($restimediff);
											
											$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','$daytemp 00:11:00')";
											$restimediff1 = exequery($qrytimediff1);
											$rowtimediff1 = fetch($restimediff1);
											$tempdata = substr($rowtimediff1[0],0,1);
										 
										 $rowdifference[0]=str_replace('-','',$rowdifference[0]);
										 
											//echo "".substr($rowdifference[0],0,1)."";
											//if(($tempdata)!='-')
											{
												//$dates = YMDtoDMY($rowlatearrival[2])." (".$rowdifference[0].") ,".$dates;
												$data1 = $data1."<td width='40px'>".substr($rowlatearrival[2],8,2)."</td>";
												$datas1 = $datas1."<td width='40px'>".substr($rowdifference[0],0,5)."</td>";
												$days++;
											}	
										}	
									}
									
									if($days>0)
									{
								//	echo"<tr><td>".$StaffMasterRow[0]."</td><td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td><td>".$dates."</td><td>".$days."</td></tr>";	
								
								
									echo" <tr>
									<td colspan=6 >Emp Code / Name :-  ".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."  <td><td colspan=5> Dept :- ".$rowdept1[1]." </td><td colspan=5> Branch :-   ".$rowbranch[1]."  </td><td colspan=15> Region :- ".$rowregion[1]."</td></tr> ";
									
									$pdf->SetFont('Arial','B',10);
									$pdf->WriteHTML("Emp Code / Name :-".$StaffMasterRow[0]."/".$StaffMasterRow[1]."     Dept :- ".$rowdept1[1]."     Branch :-   ".$rowbranch[1]."    Region :- ".$rowregion[1]."<br>");							
									$pdf->WriteHTML("You have been marked absent on the following dates. Please fill up OD/LEAVES at earliest.<br><br>");	
									$pdf->SetFont('Arial','',8);
									$pdf->WriteHTML("<tr><td width='100px'>Date </td>".$data1."</tr>");							
									$pdf->WriteHTML("<tr><td width='100px'>Remark </td>".$datas1."</tr>");							
				
										
									$pdf->WriteHTML("    <br>");	
									$pdf->WriteHTML(" Signature  <br>");	
									$pdf->WriteHTML("<tr><td colspan=31><hr></td></tr><br>");	
							
									echo"<tr><td colspan=31 > You have been marked late on the following dates. Kindly forward  OD/REQUISITE PERMISION within 3 days.   </td> </tr>";
									echo"<tr><td> Date </td>".$data1."</tr>";
									echo"<tr><td>Remark</td>".$datas1."</tr>";
									echo"<tr><td colspan=7 align='center'> <br> </td></tr>";
									echo"<tr><td colspan=7 align='center'>Signature </td></tr>";
									echo"<tr><td colspan=31><hr></td></tr>";
									$data1 = "";
									
									$pa++; 
									   if($pa==5)
										  { 
											$pdf->AddPage();
											$page++;
											$pdf->WriteHTML( "Page No ". $page." <br>");
											$pdf->SetFont('Arial','B',12);
											$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");			
											$pdf->WriteHTML( " Attendance List  Form   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
											$pdf->WriteHTML( " <hr>");
										
											$pa=0;
										  }
									  
									}
								

    	   }
    		echo "</tr></table></center><br>";	
			
			$pdf->WriteHTML( "</table>");
	        $pdf->Output("attendencereport/$filename.pdf","F");
    	}  
    
//---------------------------------------------------------  early departure     ---------------------------------------------------------//		
		
if($_POST['misreport']=='earlydep') 
   {
	   
	   $pdf=new PDF('L', 'mm', 'A3');
		$pdf->SetFont('Arial','',10);
		$pdf->AddPage();
		   $pa=0;
		  $page=1; 
		  
   	  $time = mktime(0, 0, 0, $_POST['month']);
		  $name = strftime("%b", $time);
		  echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'> Early Departure List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";
 	     $my = $_POST['year'].'-'.$_POST['month'];
 	     $month = $_POST['year']."-".$_POST['month']."-01";
        $lastday = date('t',strtotime($month));
		
		
		$pdf->WriteHTML( "Page No ". $page." <br>");
		$pdf->SetFont('Arial','B',12);
		$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");
	
		$pdf->WriteHTML( " Early Departure List   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
		$pdf->WriteHTML( " <hr>");
        
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
    $pdf->SetFont('Arial','',8);
	echo  '<center><table class="table table-bordered" border="1" style="width:75%">';
	$pdf->WriteHTML("<table  border=1><tr bgcolor='#1FB5AD' ><td  width='70px'></td>");
								/* echo "<tr bgcolor='sky blue'><th style='color:white;'>Emp Code</th>
								<th style='color:white;'>Employee Details</th>
								<th style='color:white;'>Date</th>
								<th style='color:white;'>No. of Days</th></tr>"; */
 
   $frmdate = DMYtoYMD($_POST['frmdate']);
   $todate  = DMYTOYMD($_POST['todate']);
   $date=$_POST['year']."-".$_POST['month'];
   $fromempcode1 = explode(':',$_POST['fromempcode']);
   $fromempcode  = $fromempcode1[0];
   $toempcode1 = explode(':',$_POST['toempcode']);
   $toempcode  = $toempcode1[0];
   
   $depttest ='';
   $loctest ='';
   $comptest ='';
	
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
		 $StaffMasterSql.=" Order BY region,deptid,Empcompanyid";
 //echo $StaffMasterSql.'<br>';
    	   $StaffMasterRes = exequery($StaffMasterSql);
    	   while($StaffMasterRow = fetch($StaffMasterRes))
    	   {

	               if($_POST['departmenttype']=='department')
				 {
					 //echo'coming department';
					 if($depttest!=$StaffMasterRow['deptid'])
					 {
						 $qrydept2 = "select * from DepartmentMaster1 where departmentid='".$StaffMasterRow['deptid']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>DEPARTMENT :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Department : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $depttest=$StaffMasterRow['deptid'];
				 
				 if($_POST['locationtype']=='location')
				 {
					 if($loctest!=$StaffMasterRow['region'])
					 {
						 $qrydept2 = "select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>LOCATION :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Location : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $loctest=$StaffMasterRow['region'];
				 
				  if($_POST['companytype']=='company')
				 {
					 if($comptest!=$StaffMasterRow['Empcompanyid'])
					 {
						 $qrydept2 = "SELECT * FROM EmpCompanyMaster where empcompanyid='".$StaffMasterRow['Empcompanyid']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>COMPANY :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Company : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $comptest=$StaffMasterRow['Empcompanyid'];
	 		       
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
						      $datas1 = "";
    	                   	  $data1 = "";
									$days=0;
									$qrylatearrival = "select * from Attendancechk where attendancedate>='".DMYtoYMD($_POST['frmdate'])."' and attendancedate<='".DMYtoYMD($_POST['todate'])."'   and empcode = '".$StaffMasterRow[0]."'  ";
									//echo $qrylatearrival."<br>";
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
										//echo $ShiftMasterSql."<br>";
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
										
										$qrytimediff = "SELECT TIMEDIFF('".substr($rowshifttime[7],0,5)."','".substr($rowlatearrival[6],11,5)."')";
										//echo $qrytimediff; 
										$restimediff = exequery($qrytimediff);
										$rowtimediff = fetch($restimediff);
										
											
										$qrytimediff1 = "SELECT TIMEDIFF('$daytemp $rowtimediff[0]','00:11:00')";
										$restimediff1 = exequery($qrytimediff1);
										$rowtimediff1 = fetch($restimediff1);
										$tempdata = substr($rowtimediff1[0],0,1);
										
									 
										//echo $rowtimediff[0];
										if(($tempdata)!='-')
										{
											//$dates = YMDtoDMY($rowlatearrival[2])." (".$rowtimediff[0].") ,".$dates;
											$data1 = $data1."<td width='40px'>".substr($rowlatearrival[2],8,2)."</td>";
											$datas1 = $datas1."<td width='40px'>".substr($rowtimediff[0],0,5)."</td>";
											//$datas1 = $datas1."<td>".$tempdata."</td>";
											$days++;
										}
										
									}	
										
										
									}
								}
							if($days>0)
							{
										//echo"<tr><td>".$StaffMasterRow[0]."</td><td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td><td>".$dates."</td><td>".$days."</td></tr>";	
										
									echo" <tr>
									<td colspan=6 >Emp Code / Name :-  ".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."  <td><td colspan=5> Dept :- ".$rowdept1[1]." </td><td colspan=5> Branch :-   ".$rowbranch[1]."  </td><td colspan=15> Region :- ".$rowregion[1]."</td></tr> ";
									echo"<tr><td colspan=31 > You have been marked early departure on the following dates. Kindly forward  OD/REQUISITE PERMISION within 3 days.   </td> </tr>";
									echo"<tr><td> Date </td>".$data1."</tr>";
									echo"<tr><td>Remark</td>".$datas1."</tr>";
									echo"<tr><td colspan=7 align='center'> <br> </td></tr>";
									echo"<tr><td colspan=7 align='center'>Signature </td></tr>";
									echo"<tr><td colspan=31><hr></td></tr>";
									
							$pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Emp Code / Name :-".$StaffMasterRow[0]."/".$StaffMasterRow[1]."     Dept :- ".$rowdept1[1]."     Branch :-   ".$rowbranch[1]."    Region :- ".$rowregion[1]."<br>");							
							$pdf->WriteHTML("You have been marked early departure on the following dates. Kindly forward  OD/REQUISITE PERMISION within 3 days.<br><br>");	
							$pdf->SetFont('Arial','',8);
                            $pdf->WriteHTML("<tr><td width='100px'>Date </td>".$data1."</tr>");							
                            $pdf->WriteHTML("<tr><td width='100px'>Remark </td>".$datas1."</tr>");	
                             	
                            $pdf->WriteHTML("    <br>");	
                            $pdf->WriteHTML(" Signature  <br>");	
                            $pdf->WriteHTML("<tr><td colspan=31><hr></td></tr><br>");	
							
							$pa++; 
						   if($pa==5)
							  { 
								$pdf->AddPage();
								$page++;
								$pdf->WriteHTML( "Page No ". $page." <br>");
								$pdf->SetFont('Arial','B',12);
								$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");			
								$pdf->WriteHTML( " Attendance List  Form   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
								$pdf->WriteHTML( " <hr>");
							
								$pa=0;
							  }
							}
			
    	   }
		   
		   $pdf->WriteHTML( "</table>");
	        $pdf->Output("attendencereport/$filename.pdf","F");
    	 }
		 
//---------------------------------------------------------    overtime     -----------------------------------------------------------------//		 
		 
		 
 if($_POST['misreport']=='overtime') 
   {
	   
	   $pdf=new PDF('L', 'mm', 'A3');
	   $pdf->SetFont('Arial','',10);
	   $pdf->AddPage();
	   $pa=0;
	   $page=1;
	   
   	  $time = mktime(0, 0, 0, $_POST['month']);
		  $name = strftime("%b", $time);
		  echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'> Overtime List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";
 	     $my = $_POST['year'].'-'.$_POST['month'];
 	     $month = $_POST['year']."-".$_POST['month']."-01";
        $lastday = date('t',strtotime($month));
		
		$pdf->WriteHTML( "Page No ". $page." <br>");
		$pdf->SetFont('Arial','B',12);
		$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");
	
		$pdf->WriteHTML( " Overtime List  between   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
		$pdf->WriteHTML( " <hr>");
        
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
	
	$pdf->WriteHTML("<table  border=1><tr bgcolor='#1FB5AD' ><td  width='70px'></td>");
								/* echo "<tr bgcolor='sky blue'><th style='color:white;'>Emp Code</th>
								<th style='color:white;'>Employee Details</th>
								<th style='color:white;'>Date</th>
								<th style='color:white;'>No. of Days</th></tr>"; */
 
   $frmdate = DMYtoYMD($_POST['frmdate']);
   $todate  = DMYTOYMD($_POST['todate']);
   $date=$_POST['year']."-".$_POST['month'];
   $fromempcode1 = explode(':',$_POST['fromempcode']);
   $fromempcode  = $fromempcode1[0];
   $toempcode1 = explode(':',$_POST['toempcode']);
   $toempcode  = $toempcode1[0];
   
   $depttest ='';
   $loctest ='';
   $comptest ='';
	
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
		 $StaffMasterSql.=" Order BY region,deptid,Empcompanyid";
 
    	   $StaffMasterRes = exequery($StaffMasterSql);
    	   while($StaffMasterRow = fetch($StaffMasterRes))
    	   {
                      if($_POST['departmenttype']=='department')
				 {
					 //echo'coming department';
					 if($depttest!=$StaffMasterRow['deptid'])
					 {
						 $qrydept2 = "select * from DepartmentMaster1 where departmentid='".$StaffMasterRow['deptid']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>DEPARTMENT :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							 $pdf->WriteHTML("Department : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $depttest=$StaffMasterRow['deptid'];
				 
				 if($_POST['locationtype']=='location')
				 {
					 if($loctest!=$StaffMasterRow['region'])
					 {
						 $qrydept2 = "select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>LOCATION :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							 $pdf->WriteHTML("Location : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $loctest=$StaffMasterRow['region'];
				 
				  if($_POST['companytype']=='company')
				 {
					 if($comptest!=$StaffMasterRow['Empcompanyid'])
					 {
						 $qrydept2 = "SELECT * FROM EmpCompanyMaster where empcompanyid='".$StaffMasterRow['Empcompanyid']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>COMPANY :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							 $pdf->WriteHTML("Company : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $comptest=$StaffMasterRow['Empcompanyid'];
	 		       
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
						
    	   	$data1 = "";
    	   	$datas1 = "";
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
											//$dates = YMDtoDMY($rowlatearrival[2])." (".$rowdifference[0].") ,".$dates;
											$data1 = $data1."<td width='40px'>".substr($rowlatearrival[2],8,2)."</td>";
											$datas1 = $datas1."<td width='40px'>".substr($rowdifference[0],0,5)."</td>";
											$days++;
										}
								}
								if($days>0)
								{
										//echo"<tr><td>".$StaffMasterRow[0]."</td><td>".$StaffMasterRow[1]."<br>".$rowdept1[1]."<br>".$rowbranch[1]."<br>".$rowregion[1]."</td><td>".$dates."</td><td>".$days."</td></tr>";	
										
									echo" <tr>
									<td colspan=6 >Emp Code / Name :-  ".$StaffMasterRow[0]." / ".$StaffMasterRow[1]."  <td><td colspan=5> Dept :- ".$rowdept1[1]." </td><td colspan=5> Branch :-   ".$rowbranch[1]."  </td><td colspan=15> Region :- ".$rowregion[1]."</td></tr> ";
									echo"<tr><td colspan=31 > You have been marked overtime on the following dates. Kindly forward  OD/REQUISITE PERMISION within 3 days.   </td> </tr>";
									echo"<tr><td> Date </td>".$data1."</tr>";
									echo"<tr><td>Remark</td>".$datas1."</tr>";
									echo"<tr><td colspan=7 align='center'> <br> </td></tr>";
									echo"<tr><td colspan=7 align='center'>Signature </td></tr>";
									echo"<tr><td colspan=31><hr></td></tr>";
									
									$pdf->SetFont('Arial','B',10);
									$pdf->WriteHTML("Emp Code / Name :-".$StaffMasterRow[0]."/".$StaffMasterRow[1]."     Dept :- ".$rowdept1[1]."     Branch :-   ".$rowbranch[1]."    Region :- ".$rowregion[1]."<br>");							
									$pdf->WriteHTML("You have been marked overtime on the following dates. Kindly forward  OD/REQUISITE PERMISION within 3 days. <br><br>");	
									$pdf->SetFont('Arial','',8);
									$pdf->WriteHTML("<tr><td width='100px'>Date </td>".$data1."</tr>");							
									$pdf->WriteHTML("<tr><td width='100px'>Remark </td>".$datas1."</tr>");	
										
									$pdf->WriteHTML("    <br>");	
									$pdf->WriteHTML(" Signature  <br>");	
									$pdf->WriteHTML("<tr><td colspan=31><hr></td></tr><br>");	
									
									$pa++; 
									   if($pa==5)
										  { 
											$pdf->AddPage();
											$page++;
											$pdf->WriteHTML( "Page No ". $page." <br>");
											$pdf->SetFont('Arial','B',12);
											$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");			
											$pdf->WriteHTML( " Attendance List  Form   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
											$pdf->WriteHTML( " <hr>");
										
											$pa=0;
										  }
									
								}
								
			
    	   }
    		echo "</tr></table></center><br>";	
			
			$pdf->WriteHTML( "</table>");
	        $pdf->Output("attendencereport/$filename.pdf","F");
    	}
    	    	   

//----------------------------------------------------------   outdoor       ----------------------------------------------------//				   
				   
if($_POST['misreport']=='od') 
   {
	   $pdf=new PDF('L', 'mm', 'A3');
	   $pdf->SetFont('Arial','',10);
	   $pdf->AddPage();
	   $pa=0;
	   $page=1;
   		
   	      $time = mktime(0, 0, 0, $_POST['month']);
		  $name = strftime("%b", $time);
		  echo "<center> <p class='btn btn-success' style='font-color:white;font-weight:bold;font-size:16px;'>OutDoor Duty List  between ".$_POST['frmdate']." to ".$_POST['todate']." </p></center><br>";
		  
		$pdf->WriteHTML( "Page No ". $page." <br>");
		$pdf->SetFont('Arial','B',12);
		$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");
	
		$pdf->WriteHTML( " OutDoor Duty List  between   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
		$pdf->WriteHTML( " <hr>");  
		  

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
	<?
	$pdf->WriteHTML("<table  border=1><tr bgcolor='#1FB5AD' ><td  width='70px'></td>");
	?>
       <!--<tr bgcolor='#A48AD4'>
	   
			<th style="color:white;font-size:16px;"> Emp Code</th>  
			<th style="color:white;font-size:16px;"> Employee Details </th>
			<th style="color:white;font-size:16px;"> Date </th>
			<th style="color:white;font-size:16px;">No. of Days</th>
			<th style="color:white;font-size:16px;">Reason</th>
		   
	  </tr>-->
   <? 
   $frmdate = DMYtoYMD($_POST['frmdate']);
   $todate  = DMYTOYMD($_POST['todate']);
   
   $depttest ='';
   $loctest ='';
   $comptest ='';
   
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
    	   
    	    if(($_POST['frombranch']!='') && ($_POST['tobranch']!=''))
    	    $StaffMasterSql.="and ((branch BETWEEN ('".$_POST['frombranch']."') AND ('".$_POST['tobranch']."')) )";	
		
            if($_POST['company']!='all')
    	    $StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";
		    $StaffMasterSql.=" Order BY region,deptid,Empcompanyid";
    	   
    	         //$StaffMasterSql.=" group by empcode   order by empcode desc ";
    	  // echo $StaffMasterSql;
    	   $StaffMasterRes = exequery($StaffMasterSql);
    	   while($StaffMasterRow = fetch($StaffMasterRes))
    	   {
                 if($_POST['departmenttype']=='department')
				 {
					 //echo'coming department';
					 if($depttest!=$StaffMasterRow['deptid'])
					 {
						 $qrydept2 = "select * from DepartmentMaster1 where departmentid='".$StaffMasterRow['deptid']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>DEPARTMENT :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->WriteHTML("Department : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $depttest=$StaffMasterRow['deptid'];
				 
				 if($_POST['locationtype']=='location')
				 {
					 if($loctest!=$StaffMasterRow['region'])
					 {
						 $qrydept2 = "select * from RegionMaster1 where regionid='".$StaffMasterRow['region']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>LOCATION :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->WriteHTML("Location : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $loctest=$StaffMasterRow['region'];
				 
				  if($_POST['companytype']=='company')
				 {
					 if($comptest!=$StaffMasterRow['Empcompanyid'])
					 {
						 $qrydept2 = "SELECT * FROM EmpCompanyMaster where empcompanyid='".$StaffMasterRow['Empcompanyid']."'";
						 $resdept2 = exequery($qrydept2);
						 while($rowdept2 = fetch($resdept2))
						 {
							echo"<tr><td style='color:#1fb5ad;font-weight:bold'>COMPANY :</td><td colspan = 30 style='color:#1fb5ad;font-weight:bold'>".$rowdept2[1]."</td></tr>";
							$pdf->WriteHTML("Company : ".$rowdept2[1]."<br>");
						 }
					 }
				 }
				 $comptest=$StaffMasterRow['Empcompanyid'];   
	
	
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
							$data1 = "";
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
									
									/* $qryholiday = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and hdate>='".$rowdate2[0]."' and hdate<='".$rowdate2[0]."' and  E.edition='".$rowqry['region']."'";
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
									else */
									$data = YMDtoDMY($rowdate2[0]).",".$data;
								
								    $data1 = $data1."<td width='40px'>".substr($data,0,2)."</td>";
									//$datas1 = $datas1."<td>".substr($rowdifference[0],0,5)."</td>";
									//echo $data;
									//echo "<br>";
									$reason = $reason."<td width='40px'>OD</td>";
								}
								    
									//$reason = $reason.",".$rowleave[5];
									
									
								
									
							}	
									$flag=0;
									if($flag==0)
								{
									if($rowsickleave[0]>0)
									{
										/* // echo $total;
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
										} */

						?>			
							 <?	/* <!--<tr>
									<td><? echo $rowqry[0]; ?></td>	
									<td><? echo $rowqry[1]; ?>,<br><? echo $rowb[1]; ?>,<br><? echo $rowregion[1]; ?> ,<? echo $rowdept[1]; ?></td>
									<td><? echo $data; ?></td>
									<td><? echo $rowsickleave[0];?></td>
									<td><? echo $reason; ?></td>															
								</tr>-->	 */

                             
                                    echo" <tr>
									<td colspan=6 >Emp Code / Name :-  ".$rowqry[0]." / ".$rowqry[1]."  <td><td colspan=5> Dept :- ".$rowdept1[1]." </td><td colspan=5> Branch :-   ".$rowb[1]."  </td><td colspan=15> Region :- ".$rowregion[1]."</td></tr> ";
									echo"<tr><td colspan=31 > You have been marked outdoor on the following dates. Kindly forward  OD/REQUISITE PERMISION within 3 days.   </td> </tr>";
									echo"<tr><td> Date </td>".$data1."</tr>";
									echo"<tr><td>Remark</td>".$reason."</tr>";
									echo"<tr><td colspan=7 align='center'> <br> </td></tr>";
									echo"<tr><td colspan=7 align='center'>Signature </td></tr>";
									echo"<tr><td colspan=31><hr></td></tr>";	

                                   $pdf->SetFont('Arial','B',10);
			                $pdf->WriteHTML("Emp Code / Name :-".$StaffMasterRow[0]."/".$StaffMasterRow[1]."     Dept :- ".$rowdept1[1]."     Branch :-   ".$rowbranch[1]."    Region :- ".$rowregion[1]."<br>");							
							$pdf->WriteHTML("You have been marked outdoor on the following dates. Please fill up OD/LEAVES at earliest.<br><br>");	
							$pdf->SetFont('Arial','',8);
                            $pdf->WriteHTML("<tr><td width='100px'>Date </td>".$data1."</tr>");							
                            $pdf->WriteHTML("<tr><td width='100px'>Remark </td>".$reason."</tr>");	
                             	
                            $pdf->WriteHTML("    <br>");	
                            $pdf->WriteHTML(" Signature  <br>");	
                            $pdf->WriteHTML("<tr><td colspan=31><hr></td></tr><br>");									
									
									//$count++;
			$pa++; 
		   if($pa==5)
			  { 
		        $pdf->AddPage();
				$page++;
				$pdf->WriteHTML( "Page No ". $page." <br>");
				$pdf->SetFont('Arial','B',12);
				$pdf->WriteHTML( "TARUN BHARAT DAILY PVT. LTD., BELGAUM</center><br> ");			
				$pdf->WriteHTML( " Attendance List  Form   ".$_POST['frmdate']." to ".$_POST['todate']."</center><br><br>");
				$pdf->WriteHTML( " <hr>");
			
				$pa=0;
			  }
									}			
							 }												 
						}
			}	     //  echo $count; 
									
	        $pdf->WriteHTML( "</table>");
	        $pdf->Output("attendencereport/$filename.pdf","F");
			 
 ?>			   
  </table>
  </center>  


  <?  	   
    	   } // od 
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
      if(($_POST['frombranch']!='') && ($_POST['tobranch']!=''))
    	    $StaffMasterSql.="and ((branch BETWEEN ('".$_POST['frombranch']."') AND ('".$_POST['tobranch']."')) )";	
		
      if($_POST['company']!='all')
    	    $StaffMasterSql.="and Empcompanyid='".$_POST['company']."'";
		    $StaffMasterSql.=" Order BY region,deptid,Empcompanyid";
 
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
			       echo"<tr><td>Empcode/Name</td><td colspan=".($days1+1).">".$StaffMasterRow[0]."  /  ".$StaffMasterRow[1]."</td></tr>";  
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


	
	

				


	  ?>   	<input class="span4" id="frmdate" name="frmdate" type="hidden" placeholder="Select date" value="<? echo $_POST['frmdate'] ?>">
       <input class="span4" id="todate" name="todate" type="hidden" placeholder="Select date" value="<? echo $_POST['todate'] ?>" >
       <input type="hidden" name="misreport"  value="<? echo $_POST['misreport'] ?>">
     
       <input type="hidden" name="month" id="month" value="<? echo $_POST['month'] ?>">
       <input type="hidden" name="year" id="year" value="<? echo $_POST['year'] ?>">
       
       <input type="hidden" name="fromempcode" id="fromempcode" value="<? echo $_POST['fromempcode'] ?>">
       <input type="hidden" name="toempcode" id="toempcode" value="<? echo $_POST['toempcode'] ?>">
       
       <input type="hidden" name="fromlocation" id="fromlocation" value="<? echo $_POST['fromlocation'] ?>">
       <input type="hidden" name="tolocation" id="tolocation" value="<? echo $_POST['tolocation'] ?>">
       
       <input type="hidden" name="fromdepartment" id="fromdepartment" value="<? echo $_POST['fromdepartment'] ?>">
       <input type="hidden" name="subdepartment" id="subdepartment" value="<? echo $_POST['subdepartment'] ?>">
       
       
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

<section id="container" >
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<!--Top Menu start-->
<? include('top.php');?>
<!--logo End-->
<!--Top Menu End-->
<? include('left.php');?>
</header>
<!--header end-->
<? include('menu.php') ;
             

?>
<!--sidebar end-->
    <!--main content start-->
    <section id="main-content" class="">
        <section class="wrapper">
        <!-- page start-->
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                          REPORT
                        </header>
                        <div class="panel-body">

                            <div class="">
    
                                	<form action="attchkreport.php" method="post" enctype="multipart/form-data" class="form-horizontal ">
 <script type="text/javascript">

     
      $(document).ready(function () {
      	$('#display').hide();
      	$('#display1').show();
      	$('#display2').hide();
      	//if(this.value!= "empall")
      	{
                      /*  $('#performance').click(function () {
                       $('#display').show('fast');
                       $('#display1').hide('fast');
                       $('#display2').hide('fast');
                       
                }); */
				 $('#performance').click(function () {
                       $('#display').hide('fast');
                       $('#display1').show('fast');
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

</script>                               	
                                	<table class="table">
                                		<tr>
                                	 
                                	      <td>Performance
                                	      
                                	          <input  type="radio"  id="performance" name="misreport" value="performance" checked>
                                	      </td>
                                	      
                                	       <td>Absent
                                	      
                                	          <input  type="radio"  id="absent" name="misreport" value="absent" >
                                	      </td>
                                	      
                                	        <td>No Punch
                                	      
                                	          <input  type="radio"  id="ab" name="misreport" value="ab" >
                                	      </td>
                                	      
                                	      <td>Late Arrival
                                	      
                                	          <input  type="radio"  id="latearrival" name="misreport" value="latearrival" >
                                	      </td>
                                	      
                                	      <td>Early Departure
                                	      
                                	          <input  type="radio"  id="earlydep" name="misreport" value="earlydep" >
                                	      </td>
                                	      
                                	       <td>Over Time
                                	      
                                	          <input  type="radio"  id="overtime" name="misreport" value="overtime" >
                                	      </td>
                                	  
                                	      <td>OD
                                	      
                                	          <input  type="radio"  id="od" name="misreport" value="od" >
                                	      </td>
                                	      <td>Status
                                	      
                                	          <input  type="radio"  id="status" name="misreport" value="status" >
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
                                	<tr id='display'>                                    
                                	       <td>
                                               Month S
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
							
<tr id='display1' style="visible:hidden;">
<td> From Date</td>
<td>
<div data-date-viewmode="day" data-date-format="dd-mm-yyyy" style="width:110px" data-date="<? echo date('d-m-Y');?>"  class="input-append date dpYears">
      <input  class="form-control"  style="width:110px;"  id="frmdate" name="frmdate" type="text" placeholder="Select date" value="<? echo date('d-m-Y');?>" >          
          <span class="input-group-btn add-on">
              <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
          </span>
</div>

</td>	

<td> To Date</td>
<td>
<div data-date-viewmode="day" data-date-format="dd-mm-yyyy" style="width:110px" data-date="<? echo date('d-m-Y');?>"  class="input-append date dpYears">
      <input  class="form-control"  style="width:110px;"  id="todate" name="todate" type="text" placeholder="Select date" value="<? echo date('d-m-Y');?>" >         
      <span class="input-group-btn add-on">
         <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
       </span>
</div>
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
									      <select name='fromdepartment' id='fromdepartment' class='form-control' style='width:300px'  onchange="todepartment1();" >
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
									
									<td>Sub  Department</td>
									<td>
									      <select name='subdepartment' id='subdepartment' class='form-control' style='width:300px'>
											  <option value=''>All</option>
										   
									
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
								
								
								</tr>
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
								
								
								</tr>
								<tr>
								<td>Group By</td>
								<td><input type='checkbox' name='departmenttype' value='department'>&nbsp;Department &nbsp;&nbsp;&nbsp;<input type='checkbox' name='locationtype' value='location'>&nbsp;Location&nbsp;&nbsp;&nbsp;<input type='checkbox' name='companytype' value='company'>&nbsp;Company</td>
								</tr>
                                	
                                	
                                	</table>
                                    
                                <center>
                                    <input class="btn btn-info" type="submit" name="action" value="Generate" />
						                  
						              </center>
                            </form>
                            </div>

                        </div>
                    </section>

            </div>
    
        </div>








   

      


        <!-- page end-->
        </section>
    </section>
    <!--main content end-->
<!--right sidebar start-->
<? include ('rightsidebar.php'); ?>
<!--right sidebar end-->

</section>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="bs3/js/bootstrap.min.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/easypiechart/jquery.easypiechart.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>

<script src="js/bootstrap-switch.js"></script>

<script type="text/javascript" src="js/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>


 <script type="text/javascript" src="bootstrap-datepicker.js"></script>

<script type="text/javascript" src="js/bootstrap-daterangepicker/moment.min.js"></script>

<script type="text/javascript" src="js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="js/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="js/jquery-multi-select/js/jquery.quicksearch.js"></script>

<script type="text/javascript" src="js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>

<script src="js/jquery-tags-input/jquery.tagsinput.js"></script>

<script src="js/select2/select2.js"></script>
<script src="js/select-init.js"></script>


<!--common script init for all pages-->
<script src="js/scripts.js"></script>
<!--
<script src="js/toggle-init.js"></script>-->

<script src="js/advanced-form.js"></script>
<!--Easy Pie Chart-->
<script src="js/easypiechart/jquery.easypiechart.js"></script>
<!--Sparkline Chart-->
<script src="js/sparkline/jquery.sparkline.js"></script>
<!--jQuery Flot Chart-->
<script src="js/flot-chart/jquery.flot.js"></script>
<script src="js/flot-chart/jquery.flot.tooltip.min.js"></script>
<script src="js/flot-chart/jquery.flot.resize.js"></script>
<script src="js/flot-chart/jquery.flot.pie.resize.js"></script>
<script src="js/iCheck/jquery.icheck.js"></script>
<script src="js/icheck-init.js"></script>

<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>
<!--dynamic table initialization -->
<script src="js/dynamic_table_init.js"></script>

</body>
</html>

