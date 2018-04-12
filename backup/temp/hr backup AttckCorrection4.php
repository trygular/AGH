<?php
include('db.php');
$userrow=connectandlogin("");
//echo $userrow[4];

$regionq ="select * from userprivilege where username='".$userrow[4]."'";
//echo $regionq;
$resq = exequery($regionq);
$rowq = fetch($resq);

if($_POST['action']=='Nextempcode')
	{
		$empcode=checkemp($_POST['empcode'],$_POST['type']);
		
	      /*  $qry="select * from StaffMaster where empcode='".$empcode."' ";
		   $resqry=exequery($qry);
		   $rowqry=fetch($resqry); */
		   echo $empcode;
	die();
	}
	function checkemp($empcode,$type)	
	{
		   $qry="select * from StaffMaster where empcode='".$empcode."' and lefts=0";
		// echo $qry;
		   $resqry=exequery($qry);
		   $rowqry=fetch($resqry);
		   if($rowqry==null)
		   {
			   if($type==1)
			   {
			     checkemp($empcode+1,$type);
			   }
			   else
			   {
				 checkemp($empcode-1,$type);  
			   }
		   }
		   else
		   {
			   $userrow=connectandlogin("");
			  $qry="select * from StaffMaster S,userprivilege U where S.region=U.region and U.username='".$userrow[4]."' and S.empcode='".$empcode."' and S.lefts=0";
		 // echo $qry;
		   $resqry=exequery($qry);
		   $rowqry=fetch($resqry);
           if($rowqry==null)
		   {
               if($type==1)
			   {
			     checkemp($empcode+1,$type);
			   }
			   else
			   {
				 checkemp($empcode-1,$type);  
			   }			  
			    
		   }
		   else
		   {
			   echo  $empcode.":". $rowqry[1];
		   }
		   }
		
		
	}
		
		
		if($_POST['Action']=="search")
		{
			$d=cal_days_in_month(CAL_GREGORIAN,$_POST['month'],$_POST['year']);
			for($i=1;$i<=$d;$i++)
			{
				$date=$_POST['year']."-".$_POST['month']."-".$i;
				$qrytemp1 = "select * from Attendancechk where   empcode='".$_POST['staffId']."'  and attendancedate='".$date."'";
				$restemp1 = exequery($qrytemp1);
				$rowtemp1 = fetch($restemp1);
				if($rowtemp1==null)
				{
					exequery("delete from Attendancechk where empcode='".$_POST['staffId']."'  and attendancedate='".$date."'");
					exequery("insert into  Attendancechk values (0, '".$_POST['staffId']."', '".$date."', '', '00:00', '', '', '', '', '', '', '', '', '', '', '', 'A', 'A', '0', '".$date."', '', '')");
				}

			}	
		
			$i=1;
			$fromdate=	$_POST['year']."-".$_POST['month']."-01";	
			if($_POST['year']==date('Y') && $_POST['month']==date('m'))
			{
				$lastdate =$_POST['year']."-".$_POST['month']."-".date('d');
			}
			else
				$lastdate =$_POST['year']."-".$_POST['month']."-".$d;
			
			
			
			$StaffMasterSql = "select * from StaffMaster where lefts!=1 and  empcode='".$_POST['staffId']."' ";
			$StaffMasterRes = exequery($StaffMasterSql);
			$StaffMasterRow = fetch($StaffMasterRes);
			if($StaffMasterRow!=null)
			{
			
			$qryatt="select * from Attendancechk where empcode='".$_POST['staffId']."' and attendancedate>='".$fromdate."' and attendancedate<='".$lastdate."' order by attendancedate";	
			//echo $qryatt."<br>";		
 			$resqryatt=exequery($qryatt);			
			while($rowqryatt=fetch($resqryatt))			
			{
				$firstpunch=explode(" ",$rowqryatt[3]);
				$secondpunch=explode(" ",$rowqryatt[4]);
				$thirdpunch=explode(" ",$rowqryatt[5]);
				$fourthpunch=explode(" ",$rowqryatt[6]);
				$fifthpunch=explode(" ",$rowqryatt[7]);
				$sixpunch=explode(" ",$rowqryatt[8]);
				$seventhpunch=explode(" ",$rowqryatt[9]);
				
					$qryday2 = "SELECT  UPPER(DAYNAME('".$rowqryatt[2]."')) AS DAY";
					$resday2 = exequery($qryday2);
					$rowday2 = fetch($resday2);
					
					$weekdayQry = "select * from weekdays where id='".$StaffMasterRow[20]."'";
					$weekdayRes = exequery($weekdayQry);
					$weekdayRow = fetch($weekdayRes);
					
					
					$temp=$rowqryatt[6];
					//echo $rowqryatt[6]."<br>";
					if($temp!="")		
					{					
						$departure=explode(" ",$rowqryatt[6]);
						$departure1=$rowqryatt[6];
						}
					else
					{
						$departure=explode(" ",$rowqryatt[4]);
						$departure1=$rowqryatt[4];
					}
			   
					if($firstpunch[1]!="" && $firstpunch[1]!="00:00")
					{
					$count++;
					}
					if($secondpunch[1]!="" && $secondpunch[1]!="00:00")
					{
					$count++;
					}
					if($thirdpunch[1]!="" && $thirdpunch[1]!="00:00")
					{
					$count++;
					}
					if($fourthpunch[1]!="" && $fourthpunch[1]!="00:00")
					{
					$count++;
					}
					if($fifthpunch[1]!="" && $fifthpunch[1]!="00:00")
					{
					$count++;
					}
					if($sixpunch[1]!="" && $sixpunch[1]!="00:00")
					{
					$count++;
					}
					if($seventhpunch[1]!="" && $seventhpunch[1]!="00:00")
					{
					$count++;
					}
					
				  $arrival=explode(" ",$rowqryatt[3]);
				  $result="";
				
				  if(strtotime($rowqryatt[13])>=strtotime("03:30"))
				  {
					  $fresult="P";
					  $acolor = "green";
						$correct++;
					  
				  }
				  else
				  {
					$fresult="A";
					$acolor = "red";
						 
				  }			  
				  if(strtotime($rowqryatt[14])>=strtotime("03:30"))
				  {
					  $sresult="P";
					  $acolor = "green";
						 
					  
				  }
				  else
				  {
					$sresult="A";
					$acolor = "red";
						 
				  }	
				  if(strtotime($rowqryatt[15])>=strtotime("07:00"))
				  {
					  $fresult="P";
					  $sresult="P";
					  $acolor = "green";					 
					  
				  }
				  $result=$fresult.$sresult;
				  if($result=="AA")
					   $acolor = "red";		
				   
				  if(($rowday2[0]==strtoupper($weekdayRow[1])))
					{
					$result = "WO";	
					 
					$acolor = "#800000"	;
							
					} 
					
					
					$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$rowqryatt[2]."' and  ( E.edition='".$StaffMasterRow['region']."' or E.edition='0')  ";
					//echo $HolidayMasterSql ;
					$HolidayMasterRes = exequery($HolidayMasterSql);
					$HolidayMasterRow = fetch($HolidayMasterRes);
					if(($HolidayMasterRow!=NULL) )
						{
						$result = "HL";								 
						$acolor = "#800000";						 	

						}
				   
				  $Workinqtime=substr($rowqryatt[15],0,5);
				  
				  
						$LeaveSql = "select *  from LeaveTransaction where empcode='".$_POST['staffId']."' and '".$rowqryatt[2]."' >=frmdate and '".$rowqryatt[2]."'<=todate  ";		
						//CLHecho $LeaveSql;
						$LeaveRes = exequery($LeaveSql);
						$LeaveRow = fetch($LeaveRes);
						if(($LeaveRow!=NULL) ) 
						{
							$acolor = "#9933FF";
							$tempresult = $LeaveRow[4];	
							if($LeaveRow[4]=="CLH"  || $LeaveRow[4]=="PLH" )		
							{		

								
							
								$LateSql = "select TIMEDIFF ('".$arrival[1]."','".$shiftstarttime."')";
								$LateRes = exequery($LateSql);
								$LateRow = fetch($LateRes);

								
								if(strtotime("03:15") <= strtotime($LateRow[0]))
								{
									$result ="P".$LeaveRow[4];
									$fresult="P";
									$sresult=$LeaveRow[4];
								}
								else 
								{
									$result =$LeaveRow[4]."P";
									$sresult="P";
									$fresult=$LeaveRow[4];
								}
								 
								 
								
							
							}
							else
							{
							//$result = str_replace('A',$tempresult,$result);        
							$result = $LeaveRow[4];	
							}
							
						}
				  
				  
				  $shifttotaltime="07:00";
				  
				  if($rowqryatt[18]!=0)
				  $result=$rowqryatt[16].$rowqryatt[17];
			    if( $result=="PP")
				  {
					  $fresult="P";
					  $sresult="P";
					  $acolor = "green";					 
					  
				  }
				 
				 $data=$data."<tr>
					<td><a href='#' style='color:".$acolor.";'  onclick='SearchDetail(".$i.",".$rowqryatt[1].")'>$rowqryatt[0]</a></td>
					<td style='display:none; color:".$acolor.";'>$ShiftMasterRow[2]</td>
					<td style='color:".$acolor.";'><input type='hidden' name='adate".$i."' id='adate".$i."' value='".$rowqryatt[2]."'>".YMDtoDMY($rowqryatt[2])."</td>
					<td style='color:".$acolor.";'>".$rowday2[0]."</td>
					<td style='color:".$acolor.";'>$count</td>
					<td style='color:".$acolor.";'><input type='hidden' name='status".$i."' id='status".$i."' value='".$result."'>".$result."</td>
					<td style='color:".$acolor.";'><input type='hidden' name='arrival".$i."' id='arrival".$i."' value='".$arrival[1]."'>".$arrival[1]."</td>
					<td style='display:none; color:".$acolor.";'><input type='hidden' name='late".$i."' id='late".$i."' value='".$late."'>".$late."</td>
					<td style='color:".$acolor.";'><input type='hidden' name='departure".$i."' id='departure".$i."' value=".$departure[1]."> ".$departure[1]."</td>
					<td style='display:none; color:".$acolor.";'><input type='hidden' name='early".$i."' id='early".$i."' value=".$early.">".$early."</td>
					<td style='color:".$acolor.";'><input type='hidden' name='workhours".$i."' id='workhours".$i."' value=".$Workinqtime.">".$Workinqtime."</td>
					<td style='color:".$acolor.";'><input type='hidden' name='firsthalfend".$i."' id='firsthalfend".$i."' value=".$rowqryatt[13]."> 
					<input type='hidden' name='secondhalfstart".$i."' id='secondhalfstart".$i."' value=".$rowqryatt[14]."><input type='hidden' name='overtime".$i."' id='overtime".$i."' value=".$overtime.">".$overtime."</td>";
					
					$flagt=0;
					$count=0;
					if(($rowqryatt[12]==4 && strtotime($rowqryatt[15])<strtotime($shifttotaltime) && $rowqryatt[18]==0 ) )
					{
						$flagt=1;
					    $data=$data."<td><input type='checkbox' name='changestatus".$i."' id='changestatus".$i."' ></td>";	
					
					}
					
					if($rowqryatt[12]==2 && $rowqryatt[12]!="" && (strtotime($rowqryatt[15])>=strtotime($shifttotaltime) || strtotime($rowqryatt[15])<strtotime($shifttotaltime)) && $rowqryatt[18]==0 )
					{
				        $flagt=1;						
					    $data=$data."<td><input type='checkbox' name='changestatus".$i."' id='changestatus".$i."' ></td>";		
					
					}
					if( $flagt==0 && $rowqryatt[18]==0 && ($result=="PA" || $result=='AP'))						
						$data=$data."<td><input type='checkbox' name='changestatus".$i."' id='changestatus".$i."' ></td>";		
					
					$data=$data." </tr>";
					if($result=="PP")
					{	
						$fresult="P";
						$sresult="P";
					}
					if($result=="PA")
					{	
						$fresult="P";
						$sresult="A";
					}
					if($result=="AP")
					{	
						$fresult="A";
						$sresult="P";
					}
					if($result=="AA")
					{	
						$fresult="A";
						$sresult="A";
					}
					
					if($result=="CL")
					{	
						$fresult="CL";
						$sresult="CL";
					}
					if($result=="PL")
					{	
						$fresult="PL";
						$sresult="PL";
					}
					if($result=="CLHP")
					{	
						$fresult="CLH";
						$sresult="P";
					}
					if($result=="PCLH")
					{	
						$fresult="P";
						$sresult="CLH";
					}
					if($result=="PLHP")
					{	
						$fresult="PLH";
						$sresult="P";
					}
					if($result=="PPLH")
					{	
						$fresult="P";
						$sresult="PLH";
					}
					if($result=="OD")
					{	
						$fresult="OD";
						$sresult="OD";
					}
					
					if($result=="SL")
					{	
						$fresult="SL";
						$sresult="SL";
					}
					if($result=="CO")
					{	
						$fresult="CO";
						$sresult="CO";
					}
					$qryss =  "update Attendancechk set firsthalfstatus='".$fresult."',secondhalfstatus='".$sresult."' where empcode= '".$_POST['staffId']."' and attendancedate='".$rowqryatt[2]."' and attendancebit=0";
					//echo "<tr><td>".$qryss."</td></tr>";
					exequery($qryss);
					$fresult='';
					$sresult='';
					 $i++;
			
			}
				echo $data."*".$wo."*".$leave1."*".$hl."*".$lop."*".$i."*".$focepunch."*".$modify."*".$impproper."*".$correct."*".$co;
			}
			
			
				$datetyep = $_POST['year']."-".$_POST['month']."-";
		
			
				$qry = "SELECT * FROM Attendancechk where attendancedate like'%$datetyep%'  and empcode='".$_POST['staffId']."' group by empcode";
				//$qry = "SELECT * FROM Attendancechk where attendancedate like'%$datetyep%'";
				$res = exequery($qry);
				while($row=fetch($res))
				{	

					$StaffMasterSql = "select * from StaffMaster where lefts!=1 and  empcode='".$row[1]."' ";					 
					$StaffMasterRes = exequery($StaffMasterSql);
					$StaffMasterRow = fetch($StaffMasterRes);

					


					
					$qrytemp = "select * from attendance3 where empcode='".$row[1]."' and month='".$_POST['month']."' and year='".$_POST['year']."' ";
					$restemp = exequery($qrytemp);
					$rowtemp = fetch($restemp);
					if($rowtemp==null)
						exequery("insert attendance3  (companyid, empcode, month,year) values ('".$StaffMasterRow[8]."','".$row[1]."','".$_POST['month']."','".$_POST['year']."' )");
					for($i=1;$i<=31;$i++)
					{
						$result = "";
							$datetyepmain = $_POST['year']."-".$_POST['month']."-".$i;
							$qry1 = "SELECT * FROM Attendancechk where attendancedate ='$datetyepmain' and  empcode='".$row[1]."' ";							
							$res1 = exequery($qry1);
							while($row1=fetch($res1))
							{
								
									
								$qryshift="SELECT S.shiftid,timediff('".$arrival[1]."',S.shiftstart) as shifttime FROM Empshiftdetails E,ShiftMaster S where E.shiftid=S.shiftid  and E.empcode='".$row[1]."' and S.shiftstart<='".substr($row1[3],11,5)."' order by shifttime ";
								 
								$resqryshift=exequery($qryshift);
								$rowqryshift=fetch($resqryshift);			

								$ShiftMasterSql = "select * from ShiftMaster where shiftid= '".$rowqryshift[0]."' ";			
								$ShiftMasterRes = exequery($ShiftMasterSql);
								$ShiftMasterRow = fetch($ShiftMasterRes);
								$shiftstarttime = $ShiftMasterRow[4];	
								
								
								
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
								$result="SL";
								$fieldname="d".$day;
							 	
								
							
								exequery("update attendance3   set $fieldname='".$result."' where  empcode='".$row[1]."' and  month='".$_POST['month']."' and year='".$_POST['year']."' ");
								
								
							}
							
					}
			

			}
			
			$qrytemp = "SELECT count(*) as empcodes,empcode,attendancedate FROM `TarunBharat-Empire`.Attendancechk where  attendancedate>='2018-02-01' group by empcode, attendancedate having empcodes>1";
			$restemp = exequery($qrytemp);
			while($rowtemp = fetch($restemp))
			{
			if($rowtemp[0]>1)
			{
			$qrytemp1 = "delete from  Attendancechk where  empcode = '".$rowtemp[1]."' and attendancedate='".$rowtemp[2]."' limit 1";

			exequery($qrytemp1);

			}
			}
			
			
			
			
		die();	
		}
		
		
		if($_POST['Action']=="searchold")
		{
			$my=$_POST['year']."-".$_POST['month'];
			$startdate = $_POST['year']."-".$_POST['month']."-01";
			$lastdateqry = "SELECT LAST_DAY('".$startdate."')";
			$lastdateres = exequery($lastdateqry);
			$lastdaterow = fetch($lastdateres);
			$lastdate = $lastdaterow[0];
			 $lastday=explode('-',$lastdaterow[0]);
			//echo $lastday[2];
			$i=1;
			
			
			
			$StaffMasterSql = "select * from StaffMaster where lefts!=1 and  empcode='".$_POST['staffId']."' ";
			$StaffMasterRes = exequery($StaffMasterSql);
			$StaffMasterRow = fetch($StaffMasterRes);
			if($StaffMasterRow!=null)
			{
			$shiftid = $StaffMasterRow['shiftid'];
	   
			$d=cal_days_in_month(CAL_GREGORIAN,$_POST['month'],$_POST['year']);
			for($i=1;$i<=$d;$i++)
			{
				$date=$_POST['year']."-".$_POST['month']."-".$i;
				$qrytemp1 = "select * from Attendancechk where   empcode='".$_POST['staffId']."'  and attendancedate='".$date."'";
				$restemp1 = exequery($qrytemp1);
				$rowtemp1 = fetch($restemp1);
				if($rowtemp1==null)
				{
					exequery("delete from Attendancechk where empcode='".$_POST['staffId']."'  and attendancedate='".$date."'");
					exequery("insert into  Attendancechk values (0, '".$_POST['staffId']."', '".$date."', '', '00:00', '', '', '', '', '', '', '', '', '', '', '', 'A', 'A', '0', '".$date."', '', '')");
				}

			}	
		$fromdate=	$_POST['year']."-".$_POST['month']."-01";	
	    if($_POST['year']==date('Y') && $_POST['month']==date('m'))
		{
			$lastdate =$_POST['year']."-".$_POST['month']."-".date('d');
		}
	    else
			$lastdate =$_POST['year']."-".$_POST['month']."-".$d;
	   /*
			$qrytemp = "select dates from  Datetemp where dates>='".$startdate."' and  dates<='".$lastdate."' and dates not in ( SELECT attendancedate FROM Attendance A,StaffMaster S where A.empcode=S.empcode and S.empcode='".$_POST['staffId']."' and attendancedate>='".$startdate."')";
			echo $qrytemp;
			echo "<br>";
			$restemp = exequery($qrytemp);
			while($rowtemp=fetch($restemp))
			{
				$qrytemp1 = "select * from Attendancechk where   empcode='".$_POST['staffId']."'  and attendancedate='".$rowtemp[0]."'";
				$restemp1 = exequery($qrytemp1);
				$rowtemp1 = fetch($restemp1);
				if($rowtemp1==null)
				{
					exequery("delete from Attendancechk where empcode='".$_POST['staffId']."'  and attendancedate='".$rowtemp[0]."'");
					exequery("insert into  Attendancechk values (0, '".$_POST['staffId']."', '".$rowtemp[0]."', '', '00:00', '', '', '', '', '', '', '', '', '', '', '', 'A', 'A', '0', '".$rowtemp[0]."', '', '')");
				}
			}
			   
	   */
	   
	   
			$qryatt="select * from Attendancechk where empcode='".$_POST['staffId']."' and attendancedate>='".$fromdate."' and attendancedate<='".$lastdate."' order by attendancedate";	
			//echo $qryatt."<br>";		
 			$resqryatt=exequery($qryatt);
			
			while($rowqryatt=fetch($resqryatt))			
			{
				$count=0;
				$flagsts=0;
			
				
				  $FinalSecondHalfSql = "select TIMEDIFF ('".$rowqryatt[4]."','".$rowqryatt[3]."')";
			      $FinalSecondHalfRes = exequery($FinalSecondHalfSql);
			      $FinalSecondHalfRow = fetch($FinalSecondHalfRes);
					$firsthalf = $FinalSecondHalfRow[0];
					
					
				 $FinalSecondHalfSql = "select TIMEDIFF ('".$rowqryatt[6]."','".$rowqryatt[5]."')";
			      $FinalSecondHalfRes = exequery($FinalSecondHalfSql);
			      $FinalSecondHalfRow = fetch($FinalSecondHalfRes);
					$sechalf = $FinalSecondHalfRow[0];
						
			
				
				$firstpunch=explode(" ",$rowqryatt[3]);
				$secondpunch=explode(" ",$rowqryatt[4]);
				$thirdpunch=explode(" ",$rowqryatt[5]);
				$fourthpunch=explode(" ",$rowqryatt[6]);
				$fifthpunch=explode(" ",$rowqryatt[7]);
				$sixpunch=explode(" ",$rowqryatt[8]);
				$seventhpunch=explode(" ",$rowqryatt[9]);
			   
			   
				 if($firstpunch[1]!="" && $firstpunch[1]!="00:00")
				 {
					$count++;
				 }
				  if($secondpunch[1]!="" && $secondpunch[1]!="00:00")
				 {
					$count++;
				 }
				  if($thirdpunch[1]!="" && $thirdpunch[1]!="00:00")
				 {
					$count++;
				 }
				  if($fourthpunch[1]!="" && $fourthpunch[1]!="00:00")
				 {
					$count++;
				 }
				  if($fifthpunch[1]!="" && $fifthpunch[1]!="00:00")
				 {
					$count++;
				 }
				 if($sixpunch[1]!="" && $sixpunch[1]!="00:00")
				 {
					$count++;
				 }
				 if($seventhpunch[1]!="" && $seventhpunch[1]!="00:00")
				 {
					$count++;
				 }
					$weekdayQry = "select * from weekdays where id='".$StaffMasterRow[20]."'";
					$weekdayRes = exequery($weekdayQry);
					$weekdayRow = fetch($weekdayRes);
				  
					$qryday2 = "SELECT  UPPER(DAYNAME('".$rowqryatt[2]."')) AS DAY";
					$resday2 = exequery($qryday2);
					$rowday2 = fetch($resday2);
					
					$HolidayMasterSql = "select * from HolidayMaster H , HolidayEditionMaster E  where H.id = E.holidayid and H.hdate='".$rowqryatt[2]."' and  ( E.edition='".$StaffMasterRow['region']."' or E.edition='0')  ";
					//echo $HolidayMasterSql ;
					$HolidayMasterRes = exequery($HolidayMasterSql);
					$HolidayMasterRow = fetch($HolidayMasterRes);
			      
					$LeaveSql = "select * from LeaveTransaction where empcode='".$_POST['staffId']."' and '".$rowqryatt[2]."' >=frmdate and '".$rowqryatt[2]."'<=todate and leavetype!='CO'";					
					$LeaveRes = exequery($LeaveSql);
					$LeaveRow = fetch($LeaveRes);
				  
					$LeaveSql1 = "select * from LeaveTransaction where empcode='".$_POST['staffId']."' and '".$rowqryatt[2]."' >=frmdate and '".$rowqryatt[2]."'<=todate and leavetype='CO'";
					$LeaveRes1 = exequery($LeaveSql1);
					$LeaveRow1 = fetch($LeaveRes1);
				  
					if(($rowday2[0]==strtoupper($weekdayRow[1])))
					{
						$result = "WO";	
						$leave = '--' ;   
						$acolor = "#800000"	;
						$wo++;			         
					}
					else  if(($HolidayMasterRow!=NULL) )
					{
						$result = "HL";		
						$leave = '--' ;
						$acolor = "#800000";
						$hl++;				
		        
					}
					else if(($LeaveRow!=NULL) ) 
					{
						$acolor = "#9933FF";
						$result = $LeaveRow[4];		
						$leave = '--' ;  
						$leave1++;		        
					}
					else if(($LeaveRow1!=NULL) ) 
					{
						$acolor = "#9933FF";
						$result = $LeaveRow[4];					   
						$co++;		        
					}
					else if($rowqryatt==NULL)
					{
						$acolor = "#660099";
						$result = 'A';				    
						$lop++;		        
					}
					else if($rowqryatt[18]==2)
					{
						$acolor = "#FF9900";
						$result = 'PP';				       
						$focepunch++;
		        
					}
					else if($rowqryatt[18]==1)
					{
						$acolor = "blue";
						$result = 'PP';				     
						$modify++;
		         
					}
					else if($rowqryatt[12]!=4)
					{
						$acolor = "red";
						$result = $rowqryatt[16].$rowqryatt[17];				      
						$impproper++;
		         
					}
					else
					{
						$result=$rowqryatt[16].$rowqryatt[17];
						if($result=="PP")
						{
							$acolor = "green";
							$correct++;
						}		
				
			   
					}
					
				  $arrival=explode(" ",$rowqryatt[3]);
				 // echo "----".$arrival[1]."---";
				  $shiftarray=array();
           $shiftdiffarray=array();
		   $k=0;
		   $flag1=0;    
			$qry="SELECT * FROM Empshiftdetails E,ShiftMaster S where E.shiftid=S.shiftid and E.empcode='".$_POST['staffId']."'  and '".$arrival[1]."' between S.shiftstart and S.shiftend order by shiftstart";
			//echo $qry;
			$resqry=exequery($qry);
			while($rowqry=fetch($resqry))
			{
			  $flag=0;      
			    
			//echo $rowqry[7];
					$qry1="select TIMEDIFF ('".$arrival[1]."','".$rowqry[7]."') ";
					$resqry1=exequery($qry1);
					$rowqry1=fetch($resqry1);
					
					if(strtotime($rowqry1[0])<strtotime('04:30:00'))
					{
					      
							//echo $rowqry[7]."--------".$rowqry1[0];
						//	echo "<br>";							
							$shiftarray[$k]= $rowqry[2];
							$shiftdiffarray[$k]= $rowqry1[0];
							 //echo $shiftarray[$i];
							$k++;
							$flag=1;
					
					}
				
					if($flag==0)
					{
							$qry2="SELECT * FROM Empshiftdetails E,ShiftMaster S where E.shiftid=S.shiftid and E.empcode='".$_POST['staffId']."'  and S.shiftstart>='".$arrival[1]."' order by shiftstart  limit 1 ";
					//	echo $qry2;
							$resqry2=exequery($qry2);
							$rowqry2=fetch($resqry2);
							
							$qry11="select TIMEDIFF ('".$arrival[1]."','".$rowqry2[7]."') ";
							$resqry11=exequery($qry11);
							$rowqry11=fetch($resqry11);
							 $firsttime=$rowqry11[0];
							$shiftid=$rowqry2[2];
								
					}
			
			
			}
			if($k!=0)
			{
			for($j=0;$j<=$k;$j++)
			{
			
			//echo $shiftdiffarray[$j];
			     if($flag1==0)
					$firsttime=$shiftdiffarray[$j];
				if($shiftdiffarray[$j]>=$firsttime)
				{
				   $firsttime=$shiftdiffarray[$j];
				   $shiftid=$shiftarray[$j];
				   $flag1=1;
				}
			
			}
			}	  
				  
						
					//Shift Calculation
					$flag1=0;
					$flag2=0;
					$qryshift="SELECT S.shiftid,timediff('".$arrival[1]."',S.shiftstart) as shifttime FROM Empshiftdetails E,ShiftMaster S where E.shiftid=S.shiftid  and E.empcode='".$_POST['staffId']."' and S.shiftstart<='".$arrival[1]."' order by shifttime ";
					//echo $qryshift;
					$resqryshift=exequery($qryshift);
					$rowqryshift=fetch($resqryshift);
					if($rowqryshift==null)
					$flag1=1;
					else
					$shifttemp=$rowqryshift[0];

					//$qryshift="SELECT * FROM Empshiftdetails E,ShiftMaster S where E.shiftid=S.shiftid and (E.shiftid!=47 and E.shiftid!=26) and E.empcode='".$_POST['staffId']."' ";
					$qryshift1="SELECT S.shiftid,timediff('".$arrival[1]."',S.shiftstart) as shifttime FROM Empshiftdetails E,ShiftMaster S where E.shiftid=S.shiftid  and E.empcode='".$_POST['staffId']."' and S.shiftstart>='".$arrival[1]."' order by shifttime  ";
					//echo $qryshift1;
					$resqryshift1=exequery($qryshift1);
					$rowqryshift1=fetch($resqryshift1);
					if($rowqryshift1==null)
					$flag2=1;
					else
					$shifttemp=$rowqryshift1[0];

					if($flag1==0 && $flag2==0)
					{

					$tempdata=substr($rowqryshift[1],0,1);
					$tempdata1=substr($rowqryshift1[1],0,1);
					if($tempdata=="-")
					{
					$datainfo=explode("-",$rowqryshift[1]);
					$shiftdatainfo=$datainfo[1];
					}
					else
					$shiftdatainfo=$rowqryshift[1];


					if($tempdata1=="-")
					{
					$datainfo1=explode("-",$rowqryshift1[1]);
					$shiftdatainfo1=$datainfo1[1];
					}
					else
					$shiftdatainfo1=$rowqryshift1[1];

					//echo "shift--".$shiftdatainfo;
					//echo"<br>";
					//echo "shift1--".$shiftdatainfo1;
					if($rowqryshift[1]=="00:00:00")
					{
					$shiftid=$rowqryshift[0];
					}

					else if($rowqryshift1[1]=="00:00:00")
					{
					$shiftid=$rowqryshift1[0];
					}
					elseif($shiftdatainfo<$shiftdatainfo1)
					{

					$shiftid=$rowqryshift[0];
					}
					else
					{

					$shiftid=$rowqryshift1[0];
					}

					}
					else
					{
					$shiftid=$shifttemp;
					}

					//End

					$ShiftMasterSql = "select * from ShiftMaster where shiftid= '".$shiftid."' ";
			
					$ShiftMasterRes = exequery($ShiftMasterSql);
					$ShiftMasterRow = fetch($ShiftMasterRes);
					$shiftstarttime = $ShiftMasterRow[4];
					$shiftendtime   = $ShiftMasterRow[7];
					$shifttotaltime = $ShiftMasterRow[8];
					$breaktime = $ShiftMasterRow[11];
					
					
					
					$LateSql = "select TIMEDIFF ('".$arrival[1]."','".$shiftstarttime."')";
					 
					$LateRes = exequery($LateSql);
					$LateRow = fetch($LateRes);

					$template = substr($LateRow[0],0,1);
					if($template!='-') 
					{
						$late = substr($LateRow[0],0,5);
					}
					else 
					{
						$late = substr($LateRow[0],0,6);
					}
					$temp=$rowqryatt[6];
					//echo $rowqryatt[6]."<br>";
					if($temp!="")		
					{					
						$departure=explode(" ",$rowqryatt[6]);
						$departure1=$rowqryatt[6];
						}
					else
					{
						$departure=explode(" ",$rowqryatt[4]);
						$departure1=$rowqryatt[4];
					}
						
						if(strtotime($shiftendtime)>=strtotime("24:00:00"))						   
						 {
						// echo"Coming";
								$timeqry="select TIMEDIFF('".$shiftendtime."','24:00:00')";
								$restimeqry=exequery($timeqry);
								$rowtimeqry=fetch($restimeqry);
						      if(strtotime($departure[1])<strtotime("24:00") && strtotime($departure[1])<strtotime("08:00"))
							  {
							   
								$shifttime=$departure[0]." ".$rowtimeqry[0];
							  }
								else	
								{
									$qrydate="select DATE_ADD('".$departure[0]."',interval 1 day)";
									//echo $qrydate;
									$resqrydate=exequery($qrydate);
									$rowqrydate=fetch($resqrydate);
									$shifttime=$rowqrydate[0]." ".$rowtimeqry[0]; 
								}
						  }
						  else
						  {
								$rowtimeqry[0]=$shiftendtime;
								$shifttime=$arrival[0]." ".$rowtimeqry[0];
						  }
						  $temps =$departure[0]." ".$departure[1];
						 
					$EarlySql = "select TIMEDIFF ('".$shifttime."','".$departure[0]." ".$departure[1]."')";
	 
					$EarlyRes = exequery($EarlySql);
					$EarlyRow = fetch($EarlyRes);
					

					$tempearly = substr($EarlyRow[0],0,1);
					if($tempearly!='-') 
					{
						$early = substr($EarlyRow[0],0,5);
					}
					else 
					{
						$early = substr($EarlyRow[0],0,6);
					}
				 
						if(trim(substr($shifttime,0,6))==substr($EarlyRow[0],0,5))
						 {
							$early ="";
						 }					 
			          //$workinghours=explode(" ",$rowqryatt[6]);
					
					  
					  
					 $Workinqsql = "select TIMEDIFF ('".$departure1."','".$arrival[0]." ".$arrival[1]."')";
					 //echo $Workinqsql.'<br>';
					 $WorkinqsqlRes = exequery($Workinqsql);
					 $WorkinqsqlRow = fetch($WorkinqsqlRes);
                     
					 
					 $Workinqsql1 = "select TIMEDIFF ('".$WorkinqsqlRow[0]."','".$breaktime."')";
					// echo $Workinqsql1;
					 $WorkinqsqlRes1 = exequery($Workinqsql1);
					 $WorkinqsqlRow1 = fetch($WorkinqsqlRes1);
                     // echo $Workinqsql[0];

					 
					 $tempWorkinqsql = substr($WorkinqsqlRow[0],0,1);
					 if($tempWorkinqsql!='-') 
					  {
						 $Workinqtime = substr($WorkinqsqlRow[0],0,5);
					  }
						else 
					  {
						 $Workinqtime = substr($WorkinqsqlRow[0],0,6);
					  }
					  
					  $OvertimeSql = "select TIMEDIFF ('".$Workinqtime."','".$shifttotaltime."')";
					// echo $OvertimeSql.'<br>';
					 $OvertimeRes = exequery($OvertimeSql);
					 $OvertimeRow = fetch($OvertimeRes);	
					//echo $OvertimeRow[0].'<br>';	
				 
					 $tempovertime = substr($OvertimeRow[0],0,1);
					 if($tempovertime!='-') 
					  {
						 $overtime = substr($OvertimeRow[0],0,5);
					  }
						else 
					  {
						 $overtime = substr($OvertimeRow[0],0,6);
					  }
					  
					  if($arrival[1]=="00:00" && $departure[1]=="00:00" || $departure[1]=="00:00" || $arrival[1]=="00:00" )
					  {
							$Workinqtime="00:00";
							$overtime="00:00";
							$early="00:00";
							$late="00:00";
							$departure[1]="00:00";
							$count=0;
					  }
					  
					  
					  
					  
					  
					  if(strtotime($Workinqtime)>=strtotime("07:00"))
					  {
						  $result="PP";
						  $acolor = "green";
							$correct++;
						  
					  }
					  $finalresfirst ="";
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
							

					
							
						
								 
						//$result= $finalresfirst.$secfirst;
							 
									
						$finaltime = "select  addtime(TIMEDIFF(sixthpunch,fifthpunch),addtime(TIMEDIFF(secpunch,firstpunch),TIMEDIFF(fourthpunch,thirdpunch))),sixthpunch from Attendancechk where empcode='".$_POST['staffId']."' and attendancedate='".$rowqryatt[2]."' and sixthpunch!='' and fifthpunch!=''";
						//echo $finaltime."<br>";
						$finaltimeres = exequery($finaltime);
						$finalrow = fetch($finaltimeres);
						if($finalrow!=null)
						{
						$Workinqtime= substr($finalrow[0],0,5);
						$departure[1]=substr($finalrow[1],11,5);
						}
						
						$finaltime = "select  addtime(TIMEDIFF(secpunch,firstpunch),TIMEDIFF(fourthpunch,thirdpunch)),fourthpunch from Attendancechk where empcode='".$_POST['staffId']."' and attendancedate='".$rowqryatt[2]."' and sixthpunch='' and fifthpunch=''";
						//echo $finaltime."<br>";
						$finaltimeres = exequery($finaltime);
						$finalrow = fetch($finaltimeres);
						if($finalrow!=null)
						{
						$Workinqtime= substr($finalrow[0],0,5);
						$departure[1]=substr($finalrow[1],11,5);
						}
						
							
						$finaltime = "select  TIMEDIFF(secpunch,firstpunch),secpunch from Attendancechk where empcode='".$_POST['staffId']."' and attendancedate='".$rowqryatt[2]."' and sixthpunch='' and fifthpunch='' and fourthpunch=''  and thirdpunch=''";
						//echo $finaltime."<br>";
						$finaltimeres = exequery($finaltime);
						$finalrow = fetch($finaltimeres);
						if($finalrow!=null)
						{	
						$Workinqtime= substr($finalrow[0],0,5);
						$departure[1]=substr($finalrow[1],11,5);
						}
						
						$finaltime = "select  * from Attendancechk where empcode='".$_POST['staffId']."' and attendancedate='".$rowqryatt[2]."'   and firstpunch!=''   ";						
						$finaltimeres = exequery($finaltime);
						$finalrow = fetch($finaltimeres);
						if($finalrow!=null)
						{	
						
						
						
						if(strtotime($Workinqtime)>=strtotime("07:00"))
						  {
							  $result="PP";
							  $acolor = "green";
								$correct++;
							  
						  }
						  else
							  $result= $finalresfirst.$secfirst;
						} 
						$flagsts=0;
						if($rowqryatt[18]==2)
						{
							$flagsts=1;
							$result="PP";
						}
						if($rowqryatt[18]==1)
						{
							$flagsts=1;
							$result="PP";
						}	
						
						if(($rowday2[0]==strtoupper($weekdayRow[1])))
						{
						$result = "WO";	
						 
						$acolor = "#800000"	;
						        
						}
						else  if(($HolidayMasterRow!=NULL) )
						{
						$result = "HL";		
						 
						$acolor = "#800000";
						 	

						}
						  
						
						$LeaveSql = "select *  from LeaveTransaction where empcode='".$_POST['staffId']."' and '".$rowqryatt[2]."' >=frmdate and '".$rowqryatt[2]."'<=todate  ";		
						//CLHecho $LeaveSql;
						$LeaveRes = exequery($LeaveSql);
						$LeaveRow = fetch($LeaveRes);
						if(($LeaveRow!=NULL) ) 
						{
							$acolor = "#9933FF";
							$tempresult = $LeaveRow[4];	
							if($LeaveRow[4]=="CLH"  || $LeaveRow[4]=="PLH" )		
							{		

								
							
								$LateSql = "select TIMEDIFF ('".$arrival[1]."','".$shiftstarttime."')";
								$LateRes = exequery($LateSql);
								$LateRow = fetch($LateRes);

								
								if(strtotime("03:15") <= strtotime($LateRow[0]))
								{
									$result ="P".$LeaveRow[4];
									$fresult="P";
									$sresult=$LeaveRow[4];
								}
								else 
								{
									$result =$LeaveRow[4]."P";
									$sresult="P";
									$fresult=$LeaveRow[4];
								}
								 
								 
								
							
							}
							else
							{
							//$result = str_replace('A',$tempresult,$result);        
							$result = $LeaveRow[4];	
							}
							
						}
						if($result=="ODOD")
						{
							$result="OD";
						}
						if($result=="COCO")
						{
							$result="CO";
						}
					//$data="<tbody>";
					
				//	if($_POST['staffId']=="99206")
					//	$arrival[1]="10:15";
					$data=$data."<tr>
					<td><a href='#' style='color:".$acolor.";'  onclick='SearchDetail(".$i.",".$rowqryatt[1].")'>$rowqryatt[0]</a></td>
					<td style='color:".$acolor.";'>$ShiftMasterRow[2]</td>
					<td style='color:".$acolor.";'><input type='hidden' name='adate".$i."' id='adate".$i."' value='".$rowqryatt[2]."'>".YMDtoDMY($rowqryatt[2])."</td>
					<td style='color:".$acolor.";'>".$rowday2[0]."</td>
					<td style='color:".$acolor.";'>$count</td>
					<td style='color:".$acolor.";'><input type='hidden' name='status".$i."' id='status".$i."' value='".$result."'>".$result."</td>
					<td style='color:".$acolor.";'><input type='hidden' name='arrival".$i."' id='arrival".$i."' value='".$arrival[1]."'>".$arrival[1]."</td>
					<td style='color:".$acolor.";'><input type='hidden' name='late".$i."' id='late".$i."' value='".$late."'>".$late."</td>
					<td style='color:".$acolor.";'><input type='hidden' name='departure".$i."' id='departure".$i."' value=".$departure[1]."> ".$departure[1]."</td>
					<td style='color:".$acolor.";'><input type='hidden' name='early".$i."' id='early".$i."' value=".$early.">".$early."</td>
					<td style='color:".$acolor.";'><input type='hidden' name='workhours".$i."' id='workhours".$i."' value=".$Workinqtime.">".$Workinqtime."</td>
					<td style='color:".$acolor.";'><input type='hidden' name='firsthalfend".$i."' id='firsthalfend".$i."' value=".$rowqryatt[13].">
					<input type='hidden' name='secondhalfstart".$i."' id='secondhalfstart".$i."' value=".$rowqryatt[14]."><input type='hidden' name='overtime".$i."' id='overtime".$i."' value=".$overtime.">".$overtime."</td>";
					$flagt=0;
					if(($rowqryatt[12]==4 && $rowqryatt[15]<$shifttotaltime && $rowqryatt[18]==0 ) )
					{
						$flagt=1;
					    $data=$data."<td><input type='checkbox' name='changestatus".$i."' id='changestatus".$i."' ></td>";	
					
					}
					
					if($rowqryatt[12]==2 && $rowqryatt[12]!="" && ($rowqryatt[15]>=$shifttotaltime || $rowqryatt[15]<$shifttotaltime) && $rowqryatt[18]==0 )
					{
				        $flagt=1;						
					    $data=$data."<td><input type='checkbox' name='changestatus".$i."' id='changestatus".$i."' ></td>";		
					
					}
					if( $flagt==0 && $rowqryatt[18]==0 && ($result=="PA" || $result=='AP'))
						
						$data=$data."<td><input type='checkbox' name='changestatus".$i."' id='changestatus".$i."' ></td>";		
					
					$data=$data." </tr>";
					if($result=="PP")
					{	
						$fresult="P";
						$sresult="P";
					}
					if($result=="PA")
					{	
						$fresult="P";
						$sresult="A";
					}
					if($result=="AP")
					{	
						$fresult="A";
						$sresult="P";
					}
					if($result=="AA")
					{	
						$fresult="A";
						$sresult="A";
					}
					
					if($result=="CL")
					{	
						$fresult="CL";
						$sresult="CL";
					}
					if($result=="PL")
					{	
						$fresult="PL";
						$sresult="PL";
					}
					if($result=="CLHP")
					{	
						$fresult="CLH";
						$sresult="P";
					}
					if($result=="PCLH")
					{	
						$fresult="P";
						$sresult="CLH";
					}
					if($result=="PLHP")
					{	
						$fresult="PLH";
						$sresult="P";
					}
					if($result=="PPLH")
					{	
						$fresult="P";
						$sresult="PLH";
					}
					if($result=="OD")
					{	
						$fresult="OD";
						$sresult="OD";
					}
					
					if($result=="SL")
					{	
						$fresult="SL";
						$sresult="SL";
					}
					if($result=="CO")
					{	
						$fresult="CO";
						$sresult="CO";
					}
					$qryss =  "update Attendancechk set firsthalfstatus='".$fresult."',secondhalfstatus='".$sresult."' where empcode= '".$_POST['staffId']."' and attendancedate='".$rowqryatt[2]."'";
					//echo "<tr><td>".$qryss."</td></tr>";
					exequery($qryss);
					$fresult='';
					$sresult='';
					$i++;
			}
			//$data=$data."</tbody>";
			//echo $leave1;
			echo $data."*".$wo."*".$leave1."*".$hl."*".$lop."*".$i."*".$focepunch."*".$modify."*".$impproper."*".$correct."*".$co;
			}
			else
			{
						echo "<tr><td colspan=12 style='text-align:center'><font size='7' color='red'>No Records Found<font></td><tr>";
				
			}
			
			
			
			
			//////////////////////////////////////////////////
			
			
			
			
				$datetyep = $_POST['year']."-".$_POST['month']."-";
				$qry = "SELECT * FROM Attendancechk where attendancedate like'%$datetyep%'  and empcode='".$_POST['staffId']."' group by empcode";
				//$qry = "SELECT * FROM Attendancechk where attendancedate like'%$datetyep%'";
				$res = exequery($qry);
				while($row=fetch($res))
				{	

					$StaffMasterSql = "select * from StaffMaster where lefts!=1 and  empcode='".$row[1]."' ";					 
					$StaffMasterRes = exequery($StaffMasterSql);
					$StaffMasterRow = fetch($StaffMasterRes);

					


					
					$qrytemp = "select * from attendance3 where empcode='".$row[1]."' and month='".$_POST['month']."' and year='".$_POST['year']."' ";
					$restemp = exequery($qrytemp);
					$rowtemp = fetch($restemp);
					if($rowtemp==null)
						exequery("insert attendance3  (companyid, empcode, month,year) values ('".$StaffMasterRow[8]."','".$row[1]."','".$_POST['month']."','".$_POST['year']."' )");
					for($i=1;$i<=31;$i++)
					{
						$result = "";
							$datetyepmain = $_POST['year']."-".$_POST['month']."-".$i;
							$qry1 = "SELECT * FROM Attendancechk where attendancedate ='$datetyepmain' and  empcode='".$row[1]."' ";							
							$res1 = exequery($qry1);
							while($row1=fetch($res1))
							{
								
									
								$qryshift="SELECT S.shiftid,timediff('".$arrival[1]."',S.shiftstart) as shifttime FROM Empshiftdetails E,ShiftMaster S where E.shiftid=S.shiftid  and E.empcode='".$row[1]."' and S.shiftstart<='".substr($row1[3],11,5)."' order by shifttime ";
								 
								$resqryshift=exequery($qryshift);
								$rowqryshift=fetch($resqryshift);			

								$ShiftMasterSql = "select * from ShiftMaster where shiftid= '".$rowqryshift[0]."' ";			
								$ShiftMasterRes = exequery($ShiftMasterSql);
								$ShiftMasterRow = fetch($ShiftMasterRes);
								$shiftstarttime = $ShiftMasterRow[4];	
								
								
								
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
								$result="SL";
								$fieldname="d".$day;
							 	
								
							
								exequery("update attendance3   set $fieldname='".$result."' where  empcode='".$row[1]."' and  month='".$_POST['month']."' and year='".$_POST['year']."' ");
								
								
							}
							
					}
				}
			
				$qrytemp = "SELECT count(*) as empcodes,empcode,attendancedate FROM `TarunBharat-Empire`.Attendancechk where  attendancedate>='2018-02-01' group by empcode, attendancedate having empcodes>1";
				$restemp = exequery($qrytemp);
				while($rowtemp = fetch($restemp))
				{
					if($rowtemp[0]>1)
					{
						$qrytemp1 = "delete from  Attendancechk where  empcode = '".$rowtemp[1]."' and attendancedate='".$rowtemp[2]."' limit 1";
						exequery($qrytemp1);

					}
				}
			
			die();
	}
		
			
if($_POST['Action']=="SearchDetail")
{
             $AttendanceDeatilSql = "SELECT * FROM Attendancechk WHERE  empcode = '".$_POST['staffId1']."' and attendancedate='".($_POST['id'])."'";			
			//echo  $AttendanceDeatilSql;			 
			    $AttendanceDeatilRes = exequery($AttendanceDeatilSql);													
			    $AttendanceDeatilRow = fetch($AttendanceDeatilRes);
				 
				 $StaffSql = "select * from StaffMaster where empcode = '".$AttendanceDeatilRow[1]."' ";
           	 $StaffRes = exequery($StaffSql);
           	 $StaffRow = fetch($StaffRes);
           	   
           	 $ShiftSql = "select * from Empshiftdetails where empcode = '".$AttendanceDeatilRow[1]."'";
           	 $ShiftRes = exequery($ShiftSql);
           	 $ShiftRow = fetch($ShiftRes);
             
             $i = 0;
			    $j = 0;

			   $workhours = $_POST['workhours'];
			   $overtime  = $_POST['overtime'];
			   $late      = $_POST['late'];
			   $early     = $_POST['early'];
			   
			    $arrival=explode(" ",$AttendanceDeatilRow[3]);
			   $secondpunch=explode(" ",$AttendanceDeatilRow[4]);
			   $thirdpunch=explode(" ",$AttendanceDeatilRow[5]);
			   $fourthpunch=explode(" ",$AttendanceDeatilRow[6]);
			   $fifthpunch=explode(" ",$AttendanceDeatilRow[7]);
			   $sixpunch=explode(" ",$AttendanceDeatilRow[8]);
			   $seventhpunch=explode(" ",$AttendanceDeatilRow[9]);
			   
			     $arrival=explode(" ",$AttendanceDeatilRow[3]);
			   $secondpunch=explode(" ",$AttendanceDeatilRow[4]);
			   $thirdpunch=explode(" ",$AttendanceDeatilRow[5]);
			   $fourthpunch=explode(" ",$AttendanceDeatilRow[6]);
			   $fifthpunch=explode(" ",$AttendanceDeatilRow[7]);
			   $sixpunch=explode(" ",$AttendanceDeatilRow[8]);
			   $seventhpunch=explode(" ",$AttendanceDeatilRow[9]); 
			   
			   
			   
			   
			     
//echo $AttendanceDeatilRow[8];
			   $temp=explode(" ",$AttendanceDeatilRow[6]);
					//echo $rowqryatt[6]."<br>";
					if($temp[1]!="00:00")					
						$departure=explode(" ",$AttendanceDeatilRow[6]);
					else
						$departure=explode(" ",$AttendanceDeatilRow[4]);
			   //echo YMDtoDMY($AttendanceDeatilRow[2]).";".$ShiftRow[0].";".$ShiftRow[3].";".$StaffRow['minentry'].";".$AttendanceDeatilRow[3].";".$AttendanceDeatilRow[4].";".$AttendanceDeatilRow[5].";".$AttendanceDeatilRow[6].";".$AttendanceDeatilRow[7].";".$AttendanceDeatilRow[8].";".$AttendanceDeatilRow[9].";".$AttendanceDeatilRow[9].";".$AttendanceDeatilRow[1].";".$workhours.";".$overtime.";".$late.";".$early.";".$flag ;
			   
				echo YMDtoDMY($AttendanceDeatilRow[2]).";".$ShiftRow[0].";".$ShiftRow[3].";".$StaffRow['minentry'].";". $arrival[1].";".$secondpunch[1].";".$thirdpunch[1].";".$fourthpunch[1].";".$fifthpunch[1].";".$sixpunch[1].";".$seventhpunch[1].";".$departure[1].";".$AttendanceDeatilRow[1].";".$workhours.";".$overtime.";".$late.";".$early.";".$flag ;
          
           
          die();	
	  }
	  
if($_POST['Action']=="TimeCal")
	  {
					$arrival    = $_POST['arrival'];
					$secondhalf = $_POST['secondhalf'];
					$thirdhalf  = $_POST['thirdhalf'];
					$departure  = $_POST['departure'];
					$attendancedate = $_POST['attendancedate'];
					
					$AttendanceDeatilSql = "SELECT * FROM Attendancechk WHERE  empcode = '".$_POST['empcode']."' and attendancedate='".DMYtoYMD($attendancedate)."'";					 
					$AttendanceDeatilRes = exequery($AttendanceDeatilSql);													
					$AttendanceDeatilRow = fetch($AttendanceDeatilRes);
					
				
					
					$SatffMasterSql = "select * from StaffMaster where empcode = '".$_POST['empcode']."'";
					$SatffMasterRes = exequery($SatffMasterSql);
					$SatffMasterRow = fetch($SatffMasterRes);
			      
					$ShiftMasterSql = "select * from Empshiftdetails where empcode = '".$_POST['empcode']."'";
					$ShiftMasterRes = exequery($ShiftMasterSql);
					$ShiftMasterRow = fetch($ShiftMasterRes);
			    	
				
			    	$date = date('Y-m-d'); 
					
					$FirstHalfTimeSql = "select TIMEDIFF ('".$secondhalf."','".$arrival."')";
					$FirstHalfTimeRes = exequery($FirstHalfTimeSql);
					$FirstHalfTimeRow = fetch($FirstHalfTimeRes);
					$FirstHalfTime = $FirstHalfTimeRow[0];
					
					$SecondhalfTimeSql = "select TIMEDIFF ('".$departure."','".$thirdhalf."')";
					$SecondhalfTimeRes = exequery($SecondhalfTimeSql);
					$SecondhalfTimeRow = fetch($SecondhalfTimeRes);
			     	$SecondhalfTime = $SecondhalfTimeRow[0];
		          
					$temp=explode(" ",$AttendanceDeatilRow[6]);
					//echo $rowqryatt[6]."<br>";
					/* if($temp[1]!="00:00")					
						$departure1=explode(" ",$AttendanceDeatilRow[6]);
					else */
						$departure1=explode(" ",$AttendanceDeatilRow[4]);
						
						$arrival1=explode(" ",$AttendanceDeatilRow[3]);
						
						if(strtotime($departure+3600)<strtotime("24:00"))
							$depdate=$arrival1[0];
						else
							$depdate=$departure1[0];
						
			      $TotalWorkHoursSql = "select TIMEDIFF ('".$depdate." ".$departure."','".$arrival1[0]." ".$arrival."')";
				//echo $TotalWorkHoursSql;
			      $TotalWorkHoursRes = exequery($TotalWorkHoursSql);
			      $TotalWorkHoursRow = fetch($TotalWorkHoursRes);
			     	$WorkHours = $TotalWorkHoursRow[0];
			      
			      
			      $shiftstarttime = $ShiftMasterRow[4];
			      $shiftendtime   = $ShiftMasterRow[7];
			      $shifttotaltime = $ShiftMasterRow[8];
			       
			      $late = $arrival;
			      $early  = $departure;
			       
			      $LateSql = "select TIMEDIFF ('".$late."','".$shiftstarttime."')";
			      $LateRes = exequery($LateSql);
			      $LateRow = fetch($LateRes);
			      $FinalLate = $LateRow[0];
			      
			      $EarlySql = "select TIMEDIFF ('".$shiftendtime."','".$early."')";
			      $EarlyRes = exequery($EarlySql);
			      $EarlyRow = fetch($EarlyRes);
			      $FinalEarly = $EarlyRow[0];
			     
			      $OvertimeSql = "select TIMEDIFF ('".$WorkHours."','".$shifttotaltime."')";
			      $OvertimeRes = exequery($OvertimeSql);
			      $OvertimeRow = fetch($OvertimeRes);
			      
			      $Overtime = $OvertimeRow[0];
			      
			      echo $WorkHours."*".$Overtime."*".$FinalLate."*".$FinalEarly;
	  	
	  	    die();
	  }
	  	
?>
<?
if($_POST['action']=="Add")
		{
			
				$sessionid = session_id();
				$userinfoSql   = "SELECT * FROM ".$security.".userinfo WHERE erpsessionid='".$sessionid."';";
				$userinfoQuery = exequery($userinfoSql);				
				$userinfoRow   = fetch($userinfoQuery);



				$arrival = $_POST['arrival'] ;
				$thirdhalf = $_POST['thirdhalf'];
				$fifthhalf = $_POST['fifthhalf'];
				$seventhhalf = $_POST['seventhhalf'];

				$secondhalf = $_POST['secondhalf'];
				$fourthhalf = $_POST['fourthhalf'];
				$sixthhalf = $_POST['sixthhalf'];
				$empcode = $_POST['empcode'];
				$attendancedate = $_POST['attendancedate'];
				$workhours = $_POST['workhours'];
				$makep = $_POST['makep'];
				if($makep==1)
				{  
				$fstatus="P";
				$sstatus="P";
				}	

				$AttendanceDeatilSql = "SELECT * FROM Attendancechk WHERE  empcode = '".$empcode."' and attendancedate='".DMYtoYMD($attendancedate)."'";			
				//echo  $AttendanceDeatilSql;			 
				$AttendanceDeatilRes = exequery($AttendanceDeatilSql);													
				$AttendanceDeatilRow = fetch($AttendanceDeatilRes);

				$arrival1=explode(" ",$AttendanceDeatilRow[3]);
				$secondpunch1=explode(" ",$AttendanceDeatilRow[4]);
				$thirdpunch1=explode(" ",$AttendanceDeatilRow[5]);
				$fourthpunch1=explode(" ",$AttendanceDeatilRow[6]);
				$fifthpunch1=explode(" ",$AttendanceDeatilRow[7]);
				$sixpunch1=explode(" ",$AttendanceDeatilRow[8]);
				$seventhpunch1=explode(" ",$AttendanceDeatilRow[9]);


				$date = date('Y-m-d'); 
				$curdate = date('Y-m-d'); 

				$FirstHalfTimeSql = "select TIMEDIFF ('". $secondpunch1[0]." ".$secondhalf."','".$arrival1[0]." ".$arrival."')";
				//echo $FirstHalfTimeSql;
				$FirstHalfTimeRes = exequery($FirstHalfTimeSql);
				$FirstHalfTimeRow = fetch($FirstHalfTimeRes);
				$currentTimeex1 = explode(':',$FirstHalfTimeRow[0]);
				$FirstHalfhr = $currentTimeex1[0].':'.$currentTimeex1[1];

				$FirstHalfTime = $FirstHalfTimeRow[0];
				$dateA = $date.' '.$FirstHalfhr; 
				$dateB = $date.' 03:30'; 

				if(strtotime($dateA) >= strtotime($dateB))
				{
				$status = 'P';
				}
				else 
				{
				$status ="A";
				}

				if($fourthhalf!="" && $thirdhalf!="")
				{	  
				$SecondHalfTimeSql = "select TIMEDIFF ('".$fourthpunch1[0]." ".$fourthhalf."','".$thirdpunch1[0]." ".$thirdhalf."')";
				$SecondHalfTimeRes = exequery($SecondHalfTimeSql);
				$SecondHalfTimeRow = fetch($SecondHalfTimeRes);

				$currentTime1ex2 = explode(':',$SecondHalfTimeRow[0]);
				$secondhalfhr = $currentTime1ex2[0].':'.$currentTime1ex2[1];
				}
				else
				{
				$SecondHalfTimeRow[0]="00:00";
				}			  
				$dateC = $date.' '.$secondhalfhr; 
				$dateD = $date.' 03:30'; 

				if(strtotime($dateC) >= strtotime($dateD))
				{
				$status1 = 'P';

				}
				else 
				{

				$status1 ="A";
				}
				$finaltime = "select ADDTIME ('".$FirstHalfTime."','".$SecondHalfTimeRow[0]."')";
				$finaltimeres = exequery($finaltime);
				$finalrow = fetch($finaltimeres);
				$totalworkhrs = $finalrow[0];

				// $status ="P";
				//$status1 ="P";

				//  $AttendanceCorrectionSql = "UPDATE Attendancechk SET firstpunch = '".$arrival1[0]." ".substr($arrival,0,5)."' , secpunch = '".$secondpunch1[0]." ".substr($secondhalf,0,5)."' , thirdpunch = '".$thirdpunch1[0]." ".substr($thirdhalf,0,5)."' , fourthpunch = '".$fourthpunch1[0]." ".substr($fourthhalf,0,5)."' , fifthpunch = '' , sixthpunch = '' , seventhpunch = '', eightpunch = '' , ninepunch = '' , tenpunch = '' , firsthalftime='".$FirstHalfhr."' , secondhalftime = '".$secondhalfhr."' , totalworkhrs= '".$workhours."' , firsthalfstatus='".$status."' , secondhalfstatus='".$status1."' , attendancebit = '1' ,  dateofentry = '".$curdate."' , username='".$userinfoRow[1]."' where empcode='".$empcode."' and attendancedate='".DMYtoYMD($attendancedate)."'" ;

				$qrytemps = "select DATE_ADD('".DMYtoYMD($attendancedate)."', INTERVAL   1 DAY)";
				$restemps = exequery($qrytemps);
				$rowtemps = fetch($restemps);

				if(strtotime(substr($arrival,0,5)) >= strtotime("07:00"))			
				$fpunch = DMYtoYMD($attendancedate)." ".substr($arrival,0,5);
				else
				$fpunch = $rowtemps[0]." ".substr($arrival,0,5);

				if(strtotime(substr($secondhalf,0,5)) >= strtotime("07:00"))	
				$spunch = DMYtoYMD($attendancedate)." ".substr($secondhalf,0,5);
				else
				$spunch = $rowtemps[0]." ".substr($secondhalf,0,5);

				if(strtotime(substr($thirdhalf,0,5)) >= strtotime("07:00"))	
				$tpunch = DMYtoYMD($attendancedate)." ".substr($thirdhalf,0,5);
				else
				$tpunch = $rowtemps[0]." ".substr($thirdhalf,0,5);

				if(strtotime(substr($fourthhalf,0,5)) >= strtotime("07:00"))	
				$frpunch = DMYtoYMD($attendancedate)." ".substr($fourthhalf,0,5);
				else
				$frpunch = $rowtemps[0]." ".substr($fourthhalf,0,5);

				if(substr($thirdhalf,0,5)=="")
				$tpunch="";


				if(substr($fourthhalf,0,5)=="")
				$frpunch="";

				if(strtotime($totalworkhrs) >= strtotime("07:00"))
				{
				$fstatus = 'P';
				$sstatus = 'P';
				}
				$AttendanceCorrectionSql = "UPDATE Attendancechk SET firstpunch = '".$fpunch."' , secpunch = '".$spunch."' , thirdpunch = '".$tpunch."' , fourthpunch = '".$frpunch."' , fifthpunch = '' , sixthpunch = '' , seventhpunch = '', eightpunch = '' , ninepunch = '' , tenpunch = '' , firsthalftime='".$FirstHalfhr."' , secondhalftime = '".$secondhalfhr."' , totalworkhrs= '".$totalworkhrs."' , firsthalfstatus='".$status."' , secondhalfstatus='".$status1."' , attendancebit = '1' ,  dateofentry = '".$curdate."' , username='".$userinfoRow[1]."'  where empcode='".$empcode."' and attendancedate='".DMYtoYMD($attendancedate)."'" ;
				//echo $AttendanceCorrectionSql ;
				exequery($AttendanceCorrectionSql);

				if($makep==1)
				{
				$AttendanceCorrectionSql = "UPDATE Attendancechk SET firsthalfstatus='".$fstatus."' , secondhalfstatus='".$sstatus."' where empcode='".$empcode."' and attendancedate='".DMYtoYMD($attendancedate)."'" ;
				//echo $AttendanceCorrectionSql ;
				exequery($AttendanceCorrectionSql);
				}				 
				if($makep==0)
				{
				$AttendanceCorrectionSql = "UPDATE Attendancechk SET firsthalfstatus='".$fstatus."' , secondhalfstatus='".$status."',attendancebit=0 where empcode='".$empcode."' and attendancedate='".DMYtoYMD($attendancedate)."'" ;
				//	echo $AttendanceCorrectionSql ;
				exequery($AttendanceCorrectionSql);
				}	

				echo $AttendanceCorrectionSql ;
				
				$sessionid = session_id();
				$userinfoSql   = "SELECT * FROM ".$security.".userinfo WHERE erpsessionid='".$sessionid."';";
				$userinfoQuery = exequery($userinfoSql);				
				$userinfoRow   = fetch($userinfoQuery);

				$SkillMasterSqlreplace=str_replace("'", " ", $AttendanceCorrectionSql);
				$UserLogsql ="INSERT INTO UserLog1 VALUES ('','".$userinfoRow[1]."','".$userinfoRow[0]."','".date('Y-m-d')."','".date('H-i-s')."','".$userinfoRow[5]."','".$SkillMasterSqlreplace."');";
				exequery($UserLogsql);

				echo ' Record Updated successfully... ';
				die();
		}
if($_POST['Action']=="UpdateStatus")
 {
			      $sessionid = session_id();
					$userinfoSql   = "SELECT * FROM ".$security.".userinfo WHERE erpsessionid='".$sessionid."';";
					$userinfoQuery = exequery($userinfoSql);				
					$userinfoRow   = fetch($userinfoQuery);
					$curdate = date('Y-m-d');

					$status1arrslpit = explode(',',$_POST['status1arr']);
					$adatearrsplit = explode(',',$_POST['adatearr']);
					$arrivalarrslpit = explode(',',$_POST['arrivalarr']);
					$firsthalfendarrsplit = explode(',',$_POST['firsthalfendarr']);
					$secondhalfstartarrsplit = explode(',',$_POST['secondhalfstartarr']);
					$departurearrsplit = explode(',',$_POST['departurearr']);
					$date1 = date('Y-m-d'); 
					
					
					

	for($i=0;$i < sizeof($status1arrslpit); $i++)
	{
	
	    $AttendanceDeatilSql = "SELECT * FROM Attendancechk WHERE  empcode = '".$_POST['empcode']."' and attendancedate='".$adatearrsplit[$i]."'";			
				//echo  $AttendanceDeatilSql;			 
			    $AttendanceDeatilRes = exequery($AttendanceDeatilSql);													
			    $AttendanceDeatilRow = fetch($AttendanceDeatilRes);
				
				 $arrival1=explode(" ",$AttendanceDeatilRow[3]);
			   $secondpunch1=explode(" ",$AttendanceDeatilRow[4]);
			   $thirdpunch1=explode(" ",$AttendanceDeatilRow[5]);
			   $fourthpunch1=explode(" ",$AttendanceDeatilRow[6]);
			   $fifthpunch1=explode(" ",$AttendanceDeatilRow[7]);
			   $sixpunch1=explode(" ",$AttendanceDeatilRow[8]);
			   $seventhpunch1=explode(" ",$AttendanceDeatilRow[9]);
			   
			   
        $qry5 = "UPDATE Attendancechk SET attendancebit = '2' ,  dateofentry = '".$curdate."' ,firsthalfstatus='P', secondhalfstatus='P', username='".$userinfoRow[1]."' where empcode='".$_POST['empcode']."' and attendancedate='".($adatearrsplit[$i])."'" ;
		
		
		//$qry5 = "UPDATE Attendancechk SET firstpunch = '".$arrival1[0]." ".substr($arrivalarrslpit[$i],0,5)."' , secpunch = '' , thirdpunch = '' , fourthpunch = '".$fourthpunch1[0]." ".substr($departurearrsplit[$i],0,5)."' , fifthpunch = '' , sixthpunch = '' , seventhpunch = '', eightpunch = '' , ninepunch = '' , tenpunch = '4' ,   firsthalfstatus='".$status1arrslpit[$i]."' , secondhalfstatus='".$status1arrslpit[$i]."' , attendancebit = '2' ,  dateofentry = '".$curdate."' , username='".$userinfoRow[1]."' where empcode='".$_POST['empcode']."' and attendancedate='".($adatearrsplit[$i])."'" ;
		//echo $qry5;
			exequery($qry5);
	}
	    $SkillMasterSqlreplace=str_replace("'", " ", $qry5);
		 $UserLogsql ="INSERT INTO UserLog1 VALUES ('','".$userinfoRow[1]."','".$userinfoRow[0]."','".date('Y-m-d')."','".date('H-i-s')."','".$userinfoRow[5]."','".$SkillMasterSqlreplace."');";
		 exequery($UserLogsql);
		 echo ' Record Updated successfully... ';
		 die();
}



?>

<? include('header.php');?>
<title>Attendance Correction</title>
<script type="text/javascript" src="/js1/jquery.js"></script>		
<script type="text/javascript" src="/js1/jquery-ui.js"></script>
<script type="text/javascript" src="/js1/jquery.timepicker.js"></script>
<script type="text/javascript" src="/js1/rating.js"></script>
<link rel="stylesheet" href="/apprise/apprise.css" type="text/css" />	
<script type="text/javascript" src="/apprise/apprise.js"></script>
<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
	<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script>
<script>
$('#changestatus'+i).change(function()
   {
       var a1 = this.checked ?  'PP' : ho[15];
       alert(a1);

   });

	function update2() 
			{
				//alert('cmg');
				staffId1 = $('#staffId').val();
	         staffId = staffId1.split(':');
	         empcode = staffId[0];
				rowcouncountcheckt1 = $('#rowcount1').val();
				countcheck = $(":checkbox:checked").length
				//alert(rowcouncountcheckt1);
				var status1arr=[];
				var adatearr=[];
				var arrivalarr=[];
				var firsthalfendarr=[];
				var secondhalfstartarr=[];
				var departurearr=[];
 for(var i=0;i< rowcouncountcheckt1; i++)
	{
			//  alert(i); 
        if($('#changestatus'+i).prop("checked") == true)
         {
			 
              adate = $('#adate'+i).val();
			 // alert(adate);
              adatearr.push(adate);
              
				  arrival = $('#arrival'+i).val();
				  arrivalarr.push(arrival);
				  
				  firsthalfend = $('#firsthalfend'+i).val();
				  firsthalfendarr.push(firsthalfend);
				  
				  secondhalfstart = $('#secondhalfstart'+i).val();
				  secondhalfstartarr.push(secondhalfstart);
				  
				  departure = $('#departure'+i).val();
				  departurearr.push(departure);
				  
              statusval = 'P';
              changestatus1 = 1;
           
              status1arr.push(statusval);
        }

  }

$('#countcheck').val(countcheck);

			$.ajax({
               	url: "/AttckCorrection4.php" ,
               data: "Action=UpdateStatus&empcode="+empcode+"&adatearr="+adatearr+"&status1arr="+status1arr+"&arrivalarr="+arrivalarr+"&firsthalfendarr="+firsthalfendarr+"&secondhalfstartarr="+secondhalfstartarr+"&departurearr="+departurearr+"&changestatus1="+changestatus1+"&countcheck="+countcheck,
                  type : 'POST',
                  success: function(output)
                  {
						//alert(output);
						$("#tab2").removeClass("active");
						// $(".tab").addClass("active"); // instead of this do the below 
						$("#tab1").addClass("active");  
						$("#tabs-2").hide();  
						// $("#tab1").hide();  
						$("#tabs-1").show(); 
                       for(var i=0;i< rowcouncountcheckt1; i++)
							{
									   
								if($('#changestatus'+i).prop("checked") == true)
								 {
										  $('#changestatus'+i).hide();
							   }
							}
search();							
                  }
                  });
                   
}
	function search()
	{
		$('#loadstatus').show();
		$('#makep').prop( "checked",false);
			empcode=$('#staffId').val();
			empcode=empcode.split(':');
			//alert(empcode[0]);
			month = $('#month').val();
			year = $('#year').val();
			$('#display').html('');
				 
			$.ajax({
             url: "/AttckCorrection4.php" ,
             data: "Action=search&staffId="+empcode[0]+"&month="+month+"&year="+year,
             type : 'POST',
             success: function(output)
              {
			  
				  //alert(output);
				outputdata=output.split('*');
			  
				$('#loadstatus').hide();
				$('#display').html('');
				$('#display').append("<tr bgcolor='#1FB5AD' ><th style='color:white;'>ID</th><th style='display:none; color:white;'> Shift</th><th style='color:white;'>Attendance Date</th><th style='color:white;'>Day</th><th style='color:white;'>Punches</th><th style='color:white;'>Status</th><th style='color:white;'>Arrival</th><th style='display:none; color:white;'>Late Arrival</th><th style='color:white;'>Departure</th><th style='display:none; color:white;'>Early Departure</th><th style='color:white;'>Work Hours</th><th style='display:none; color:white;'>Overtime</th></tr>");
				$('#display ').addClass('active');
				$('#display').append(outputdata[0]);
				
             	$('#wobalcount').html(outputdata[1]);				
				$('#leavecount').html(outputdata[2]);
				$('#holidaycount').html(outputdata[3]);
				 $('#abcount').html(outputdata[4]);
				 $('#rowcount1').val(outputdata[5]);
				 $('#modified').html(outputdata[7]);
             	    		$('#improper').html(outputdata[8]);
             	    		$('#forcemodify').html(outputdata[6]);
							$('#correctpunch').html(outputdata[9]);
							 $('#monthwo').html(outputdata[10]);
			  
			  }	  
			  
			  });
			
			
	
	}

		$(function() {
				$("#tabs").tabs();
			});
			$(document).ready(function(){
				
				
				$("#staffId").autocomplete("empsearchfetch.php", {
					selectFirst: true
					});
								
			});

var i = 0;
function SearchDetail(id,id1)
{                 
staffId1   = $('#staffId').val();
adate      = $('#adate'+id).val();
workhours  = $('#workhours'+id).val();
overtime   = $('#overtime'+id).val();
late       = $('#late'+id).val();
early      = $('#early'+id).val();
//alert(adate);
var staffId = staffId1.split(':');
 var tabs = $("#tabs").tabs();
$(".tab-pane active").hide();  
$(".tab-pane ").show();
					   
 $.ajax({
				url: "/AttckCorrection4.php" ,
				data: "Action=SearchDetail&id="+adate+"&staffId1="+id1+"&workhours="+workhours+"&overtime="+overtime+"&late="+late+"&early="+early,
				type : 'POST',
				success: function(output)
						{
					//	alert(output);
                     $("#tab1").removeClass("active");
							$("#tab2").addClass("active");  
                     $("#tabs-1").hide();  
							$("#tabs-2").show();  
						   var staff = output.split(';');
							$('#attendancedate').val(staff[0]);
							$('#shiftname').html('');
						   $('#shiftname').append("<option value="+staff[1]+">"+staff[2]+"</option>");
                     $('#minentry').html('');
						   $('#minentry').append("<option value="+staff[3]+">"+staff[3]+"</option>");
							$('#arrival').val(staff[4]);
                     $('#secondhalf').val(staff[5]);
							$('#thirdhalf').val(staff[6]);
                     $('#fourthhalf').val(staff[7]);
                     $('#fifthhalf').val(staff[8]);
                     $('#sixthhalf').val(staff[9]);
                     $('#seventhhalf').val(staff[10]);
							$('#departure').val(staff[11]);
                     $('#empcode').val(staff[12]);
                     $('#workhours').val(staff[13]);
                     $('#overtime').val(staff[14]);
                     $('#late').val(staff[15]);
                     $('#early').val(staff[16]);
                     $tabs.tabs('select', 2);
					 TimeCalc();
                 }
	   });	
   i++;			
}
				
</script> 
		
<script type="text/javascript">
function TimeCalc() 
		{
			
			empcode        = $('#empcode').val();
			attendancedate = $('#attendancedate').val();
			shiftname      = $('#shiftname').val();
			arrival        = $('#arrival').val();
			departure      = $('#departure').val();
			secondhalf     = $('#secondhalf').val();
			thirdhalf      = $('#thirdhalf').val();
			fourthhalf     = $('#fourthhalf').val();
			fifthhalf      = $('#fifthhalf').val();
			sixthhalf      = $('#sixthhalf').val();
			seventhhalf    = $('#seventhhalf').val();
			
			$.ajax({
						url: "/AttckCorrection4.php" ,
						data: "Action=TimeCal&empcode="+empcode+"&attendancedate="+attendancedate+"&arrival="+arrival+"&departure="+departure+"&secondhalf="+secondhalf+"&thirdhalf="+thirdhalf,
						type : 'POST',
						success: function(output)
						{
					alert(output);
							 $('#departure').val(fourthhalf);
							 var worktime = output.split('*');
							 $('#workhours').val(worktime[0]);
							 $('#overtime').val(worktime[1]);
							 $('#late').val(worktime[2]);
							 $('#early').val(worktime[3]);
						}
				});	

		}
</script>
<script type="text/javascript">

		function insert() 
		{
		   
			empcode        = $('#empcode').val();
			attendancedate = $('#attendancedate').val();
			shiftname      = $('#shiftname').val();
			arrival        = $('#arrival').val();
			departure      = $('#departure').val();
			secondhalf     = $('#secondhalf').val();
			thirdhalf      = $('#thirdhalf').val();
			fourthhalf     = $('#fourthhalf').val();
			fifthhalf      = $('#fifthhalf').val();
			sixthhalf      = $('#sixthhalf').val();
			seventhhalf    = $('#seventhhalf').val();
			workhours    = $('#workhours').val();
			    
			if($('#makep').prop( "checked")==true)
				makep=1
			else
				makep=0
		//	alert(makep);
			$.ajax({
						url: "/AttckCorrection4.php" ,
						data: "action=Add&empcode="+empcode+"&attendancedate="+attendancedate+"&arrival="+arrival+"&departure="+departure+"&secondhalf="+secondhalf+"&thirdhalf="+thirdhalf+"&fourthhalf="+fourthhalf+"&workhours="+workhours+"&makep="+makep,
						type : 'POST',
						success: function(output)
						{
						   alert(output);
						   
						}
				});	
			//$('#makep').prop( "checked",false);
			}
			function getnextemp()
			{
			 empcode=$('#staffId').val();
			 empcode = empcode.split(':');
			// alert(empcode[0]);
			 nextempcode=parseInt(empcode[0])+1;
			// alert(nextempcode);
			$.ajax({
						url: "/AttckCorrection4.php" ,
						data: "action=Nextempcode&empcode="+nextempcode+"&type=1",
						type : 'POST',
						success: function(output)
						{
						  // alert(output);
						   $('#staffId').val(output);
						   search();
						}
				});	
			
			}
			
			function getprevemp()
			{
			 empcode=$('#staffId').val();
			 empcode = empcode.split(':');
			// alert(empcode[0]);
			 nextempcode=parseInt(empcode[0])-1;
			// alert(nextempcode);
			$.ajax({
						url: "/AttckCorrection4.php" ,
						data: "action=Nextempcode&empcode="+nextempcode+"&type=2",
						type : 'POST',
						success: function(output)
						{
						  // alert(output);
						   $('#staffId').val(output);
						   search();
						}
				});	
			
			}
			
</script>
			
<script type="text/javascript">
    function validateHhMm(inputField) 
    {
        var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(inputField.value);
			if (isValid) 
			{
			} 
         else 
         {
         	inputField.value = "00:00:00";
			}
	}

</script>
   <style>
	.rotateimg {
  -webkit-transform:rotate(180deg);
  -moz-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  -o-transform: rotate(180deg);
  transform: rotate(180deg);
}
</style
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
                          Wage Data Register
                       
						<div style="float:right">
<canvas id="myCanvas" width="20" height="15" style="border:1px solid #000000;">
</canvas>

<script>

var c = document.getElementById("myCanvas");
var ctx = c.getContext("2d");
ctx.fillStyle = "#6CA26C";
ctx.fillRect(0, 0, 20, 15);
ctx.stroke();

</script>
Correct    <span id='correctpunch' style="font-size:16px;font-weight:bold;color:black;"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<canvas id="myCanvas1" width="20" height="15" style="border:1px solid #000000;">
</canvas>

<script>

var c = document.getElementById("myCanvas1");
var ctx = c.getContext("2d");
ctx.fillStyle = "blue";
ctx.fillRect(0, 0, 20, 15);
ctx.stroke();

</script>

Modified    <span id='modified' style="font-size:16px;font-weight:bold;color:black;"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<canvas id="myCanvas2" width="20" height="15" style="border:1px solid #000000;">
</canvas>

<script>

var c = document.getElementById("myCanvas2");
var ctx = c.getContext("2d");
ctx.fillStyle = "red";
ctx.fillRect(0, 0, 20, 15);
ctx.stroke();

</script>
Improper    <span id='improper' style="font-size:16px;font-weight:bold;color:black;"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<canvas id="myCanvas3" width="20" height="15" style="border:1px solid #000000;">
</canvas>

<script>

var c = document.getElementById("myCanvas3");
var ctx = c.getContext("2d");
ctx.fillStyle = "#FF9900";
ctx.fillRect(0, 0, 20, 15);
ctx.stroke();

</script>
Forced to Modify   <span id='forcemodify' style="font-size:16px;font-weight:bold;color:black;"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<canvas id="myCanvas4" width="20" height="15" style="border:1px solid #000000;">
</canvas>

<script>

var c = document.getElementById("myCanvas4");
var ctx = c.getContext("2d");
ctx.fillStyle = "#800000";
ctx.fillRect(0, 0, 20, 15);
ctx.stroke();

</script>
HL  <span id='holidaycount' style="font-size:16px;font-weight:bold;color:black;"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	

<canvas id="myCanvas5" width="20" height="15" style="border:1px solid #000000;">
</canvas>

<script>

var c = document.getElementById("myCanvas5");
var ctx = c.getContext("2d");
ctx.fillStyle = "#800000";
ctx.fillRect(0, 0, 20, 15);
ctx.stroke();

</script>
CO   <span id='weeklyoffcount' style="font-size:16px;font-weight:bold;color:black;"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


				   
<canvas id="myCanvas6" width="20" height="15" style="border:1px solid #000000;">
</canvas>

<script>

var c = document.getElementById("myCanvas6");
var ctx = c.getContext("2d");
ctx.fillStyle = "#9933FF";
ctx.fillRect(0, 0, 20, 15);
ctx.stroke();

</script>
Leave  <span id='leavecount' style="font-size:16px;font-weight:bold;color:black;"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 	

<canvas id="myCanvas7" width="20" height="15" style="border:1px solid #000000;">
</canvas>

<script>

var c = document.getElementById("myCanvas7");
var ctx = c.getContext("2d");
ctx.fillStyle = "#FFFF60";
ctx.fillRect(0, 0, 20, 15);
ctx.stroke();

</script>
WO   <span id='wobalcount' style="font-size:16px;font-weight:bold;color:black;"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<canvas id="myCanvas9" width="20" height="15" style="border:1px solid #000000;">
</canvas>

<script>

var c = document.getElementById("myCanvas9");
var ctx = c.getContext("2d");
ctx.fillStyle = "#660099";
ctx.fillRect(0, 0, 20, 15);
ctx.stroke();

</script>
Ab   <span id='abcount' style="font-size:16px;font-weight:bold;color:black;"></span> &nbsp;&nbsp; 
  	
  	
<canvas id="myCanvas8" width="20" height="15" style="border:1px solid #000000;">
</canvas>

<script>

var c = document.getElementById("myCanvas8");
var ctx = c.getContext("2d");
ctx.fillStyle = "#7FDAFF";
ctx.fillRect(0, 0, 20, 15);
ctx.stroke();

</script>
Total  <span id='totaldays' style="font-size:16px;font-weight:bold;color:black;"></span> 

 	

</div> </header>
                        <div class="panel-body">
                            <div class="">
     
                              <form action="AttckCorrection4.php" method="post" enctype="multipart/form-data" class="form-horizontal ">
                                	<table class="table">
                                    
                                         <tr>
                                            
                                           <td>
                                              <select class="form-control"  style="width:100px;" id="month" name="month"  >
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
                                         
                                         <td><input class="form-control"  style="width:70px;" id="year" name="year" type="text"  value="<? echo date('Y'); ?>"></td>
                                         
                                         <td>
                                           		<!--<input class="btn btn-warning" type="button" name="action" value="Next" onclick="getnextemp();"/> -->
												<img src='/images/previmg1.png' width='20px' height='30px' onclick="getprevemp();" style='float:right' class='rotateimg'></img>
							                    </td>
                                         <td width="30%">
                                           		<input class="form-control" id="staffId" size="15" name="staffId" placeholder="Staff id" type="text"  >
							                    </td>
												 <td>
                                           		<!--<input class="btn btn-warning" type="button" name="action" value="Next" onclick="getnextemp();"/> -->
												<img src='/images/nextimg1.png' width='20px' height='30px' onclick="getnextemp();" style='float:left'></img>
							                    </td>
							                    <td>
							                    		<input class="btn btn-warning" type="button" name="action" value="Search" onclick="search();"/> 
							                     <input style='float:right' class="btn btn-info" type='button'   value='Clear' onclick="clearfun();"></td>
												<td><input style='float:right' class="btn btn-info" type='button' name="update" id="update" value='Update Status 'onclick="update2();"></td>
												<td>
											<input style='float:right'class="btn btn-info" type='button'   value='Re-Cal 'onclick="missingpunch();">
                            
                       </td>
							                    </tr>
                                    
                                  
                                  <input type='hidden' name="rowcount1" id="rowcount1">
                                  <input type='hidden' name="countcheck" id="countcheck">
    							</table>
		</div>        
          <script type="text/javascript">
		  function missingpunch()
		  {
			staffid = $('#staffId').val(); 
			month = $('#month').val(); 
			year = $('#year').val(); 
		//	alert(staffid);
			 window.open("attendancemergingcron1.php?empcode="+staffid+"&month="+month+"&year="+year);
		  }
		  
		   function clearfun()
		  {
			  alert("ccc");
			 $('#staffId').val(''); 
		  }
function showdiv1()
 {
     $("#tab2").removeClass("active");
    // $(".tab").addClass("active"); // instead of this do the below 
                           $("#tab1").addClass("active");  
    $("#tabs-2").hide();  
   // $("#tab1").hide();  
     $("#tabs-1").show();  
     
     search();
             
             }
function showdiv2()
   {
       $("#tab1").removeClass("active");
    // $(".tab").addClass("active"); // instead of this do the below 
       $("#tab2").addClass("active");  
       $("#tabs-1").hide();  
   // $("#tab1").hide();  
      $("#tabs-2").show();  
             
             }
</script>
             <style>
.errmsg
{
color: red;
}
</style>

      <section class="panel">
		  <header class="panel-heading tab-bg-dark-navy-blue ">
                    <ul class="nav nav-tabs">
                        <li class="active" id="tab1">
                            <a   data-toggle="tab" href="#tabs-1" onclick="showdiv1();" >Attendance Records</a>
                        </li>
                       
                        <li class=""  id='tab2'>
                            <a    data-toggle="tab" href="#tabs-2" onclick="showdiv2();">Attendance Details</a>
                        </li>
                      
                       
                    </ul>
             </header>
             <br>
      
					 <div class="tab-content">
                    <div id="tabs-1" class="tab-pane active" >
                   	<center>
                   <img src='load.gif' id='loadstatus' style='display:none'></img>
			               <table id="display" class='table table-bordered' style="width:100%">
						   
						      </table>
						    </center>
						  </div>
				    
				    <div id="tabs-2" class="tab-pane ">
				   
				      <div class="col-sm-12">  
                           <section class="panel">
                               <header class="panel-heading">
                                     DETAILS
                              </header>
                                <div class="panel-body">
                                   <table class="table" >
                                     <tr>
                                          <td>
							                        Employee Code 
							               	  </td>
							               	  <td>
							               	       <input type="text" class="form-control" name="empcode" id="empcode" style="width:110px;" readonly>
							                    </td>
							                    <td>
							                        Date 
							               	  </td>
							               	  <td>
							               	       <input type="text" class="form-control" name="attendancedate" id="attendancedate" style="width:110px;" readonly>
							                   </td>
							           			 <td>
							                        Shift
							                   </td>
							                   <td>        
							                       <select class="form-control" name="shiftname" id="shiftname" style="width:110px;">
							                        <option value="0"></option>
							                        </select>
	                                     </td>
							              		 <td>
							                        Punches
							                   </td>
							                   <td>         
							                       <select class="form-control" name="minentry" id="minentry" style="width:60px;">
							                         <option value="0"></option>
							                        </select>
	                                     </td>
							             </tr>
											</table>
                               </div>
                         </section>
                    </div>
                    
                    <div class="col-sm-6">  
                           <section class="panel">
                               <header class="panel-heading">
                                     TIME
                              </header>
                                <div class="panel-body">
                                   <table class="table" >
                                     <tr>
									

  


									
									    
							                    <td>Arrival&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="arrival"  class="mks" name="arrival" value="" onclick='TimeCalc();' onkeypress='TimeCalc();' />
							                         <span class='errmsg'  id="errmsgday" ></span>	  
							                    </td>
							                     <td>Work Hrs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="workhours" class="mks"  name="workhours" value="" onclick='TimeCalc();' onkeypress='TimeCalc();' />	  
							                    </td>
                       					</tr>
							               
							               <tr>
							                    
							                    <td>Departure &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="departure" class="mks"  name="departure" value="" onclick='TimeCalc();' onkeypress='TimeCalc();' />	  
							                    </td>
							                     <td>Overtime &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="overtime" class="mks"  name="overtime" value="" onclick='TimeCalc();' onkeypress='TimeCalc();'/>	  
							                    </td>
                       				  </tr>
							               
							               <tr>
							                    
							                    <td>Late Arrival &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="late" class="mks"  name="late" value="" onclick='TimeCalc();' onkeypress='TimeCalc();'/>	  
							                    </td>
							                     <td>Early Departure &nbsp;&nbsp;&nbsp;&nbsp;  
							                         <input  type="text" style="width:70px" id="early" class="mks"  name="early" value="" onclick='TimeCalc();' onkeypress='TimeCalc();'/>	  
							                    </td>
                       
							                  
							               </tr>
							            
                                  </table>
                               </div>
                         </section>
                    </div>
                   
                   <div class="col-sm-6">  
                           <section class="panel">
                               <header class="panel-heading">
                                    IRREGULAR  ENTRIES

                              </header>
                                <div class="panel-body">
                                   <table class="table" >
                                     <tr>
							                    
							                    <td>2nd&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="secondhalf" name="secondhalf" class="mks" value="" onclick='TimeCalc();' onkeypress='TimeCalc();'  />	  
							                    </td>
							                     <td>3rd&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="thirdhalf" name="thirdhalf" class="mks" value="" onclick='TimeCalc();' onkeypress='TimeCalc();' />	  
							                    </td>
                       					</tr>
							               
							               <tr>
							                    
							                    <td>4th&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="fourthhalf" name="fourthhalf" class="mks" value="" onclick='TimeCalc();' onkeypress='TimeCalc();' />	  
							                    </td>
							                     <td>5th&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="fifthhalf" name="fifthhalf" class="mks" value="" onclick='TimeCalc();' onkeypress='TimeCalc();' />	  
							                    </td>
                       
							                  
							               </tr>
							               
							                <tr>
							                    
							                    <td>6th&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="sixthhalf" name="sixthhalf" class="mks" value="" onclick='TimeCalc();' onkeypress='TimeCalc();' onchange="validateHhMm(this);"/>	  
							                    </td>
							                     <td>7th&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							                         <input  type="text" style="width:70px" id="seventhhalf" name="seventhhalf" class="mks" value="" onclick='TimeCalc();' onkeypress='TimeCalc();' onchange="validateHhMm(this);"/>	  
							                    </td>
												
												<td>
							                   <input  type="checkbox"   id="makep" name="makep" class="mks" value="1"  /> Make Present	  
							                    </td>
                       
							                  
							               </tr>
							            
                                  </table>
                               </div>
                         </section>
                    </div>


                       <center>
                                    <input class="btn btn-info" type="button" name="action" value="Update" onclick='insert();'/>
						                 
						                  <input class="btn btn-warning" type="submit" name="action" value="Cancel"/> 
						    </center>
                   
                    				</div>
                    			</div>
                  		</div>
            		 </section>
	  				  </div>
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
<script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

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


 
<script src="jquery.maskedinput.js" type="text/javascript"></script>
<script>
jQuery(function($){
 
   $(".mks").mask("99:99");
  
});
</script>
  
  
</body>
</html>
