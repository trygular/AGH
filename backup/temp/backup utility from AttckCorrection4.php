

<?php


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
			
			
			
			?>