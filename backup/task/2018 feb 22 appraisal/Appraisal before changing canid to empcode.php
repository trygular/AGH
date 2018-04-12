<? 
//file:Appraisal.php
	include_once('db.php');
?>	

<?

if($_POST['action'] == "getInitAppraisal")
{
	//$q1="SELECT empcode,fdate,tdate, concat(name, ' ', middlename, ' ', surname) ename FROM ApprailsalTrans INNER JOIN EmpMaster ON AID = CANID WHERE empcode='".$_POST['ecode']."'";
	 /*
	$q1 = "SELECT em.joiningdate, concat(name, ' ', middlename, ' ', surname) ename, er.vessel, ep.dob, md.desig, apt.fdate, apt.tdate, mde.dept ";
	$q1 .= " FROM EmpMaster em, EmpPassport ep, EmployeeReg er, ApprailsalTrans apt, Mdesignation md, Mdepartment mde ";
	$q1 .= " WHERE em.canid = ep.canid AND em.canid = er.canid AND apt.empcode = em.canid AND md.did = er.desig AND mde.did = em.rank ";
	$q1 .= " AND em.canid = '".$_POST['ecode']."' limit 1 ";
	
	
	$q = "select eid from EmployeeReg where canid = '".$_POST["ecode"]."';";
	$rowsq = exequery($q);
	$rowq = fetch($rowsq);*/
	
	$data = '';
	$q1 = "select EM.joiningdate, concat(EM.name, EM.middlename, EM.surname) as namefull, ER.vessel,'passport', ER.desig, ER.selectionDate, 'todae', 'dept' from EmployeeReg ER, EmpMaster EM where ER.state = 'Sign On' AND ER.canid = EM.canid AND EM.canid = '".$_POST['ecode']."';";
	$r1=exequery($q1);
	
	while($r = fetch($r1))
	{
		//$data .= YMDtoDMY($r[0])."#".$r[1]."#".$r[2]."#".YMDtoDMY($r[3])."#".$r[4]."#".YMDtoDMY($r[5])."#".YMDtoDMY($r[6])."#".$r[7]; 
		
		//get vessel 2
		$sqlVessel = "select vname from MVessel where vid = '".$r[2]."';";
		$rowsVessel = exequery($sqlVessel);
		$rowVessel = fetch($rowsVessel);
		
		//get design 3
		$sqlDesig = "select desig from Mdesignation where did = '".$r[4]."';";
		$rowsDesig = exequery($sqlDesig);
		$rowDesig = fetch($rowsDesig);
		
		//get 
		$sqlDOB = "select dob from EmpPassport where canid = '".$_POST["ecode"]."';";
		$rowsDOB = exequery($sqlDOB);
		$rowDOB = fetch($rowsDOB);
		
		$data .= YMDtoDMY($r[0])."#".$r[1]."#".$rowVessel[0]."#".YMDtoDMY($rowDOB[0])."#".$rowDesig[0]."#".YMDtoDMY($r[5])."#".YMDtoDMY($r[6])."#".$r[7]; 
	}
	echo $data;
	die();
}

// mearged abhijeet 10-feb2018
if($_POST['action'] == "occasionSave")
{
	$empcode = $_POST['empcode']; // ApprailsalOcc
	
	$maxidno1="SELECT aid FROM `ApprailsalTrans` WHERE empcode='".$_POST['empcode']."'";
	$rs1=exequery($maxidno1);
	$nsi = mysql_num_rows($rs1);
	if($nsi != 1)
	{
		echo "Appraisal_id_notfound";
		die();
	}
	$out1=fetch($rs1);
	
	if($nsi >= 1){
		$deleterow="delete from ApprailsalOcc where aid='".$out1[0]."'";
		exequery($deleterow);
	}

	//get department id
	$maxidno1="SELECT aid FROM `ApprailsalTrans` WHERE empcode='".$_POST['empcode']."' AND CURDATE() BETWEEN fdate AND tdate";
	$rs1=exequery($maxidno1);

	//echo $idsaf[0] ."=". $idoperati[0] ."=". $idtechinc[0] ."=". $idpersonn[0];
	$id = '';
	$iremark = '';
	$idept = '';
	$idate = '';
	for($v=1; $v <= 4; $v++)
	{
		if($v == 1)
		{
			$idsaft=exequery("SELECT * FROM Mdepartment M where dept = 'SAFTY'");
			$idept = fetch($idsaft);
			$iremark = $_POST["sdr"];
			$idate = date("Y-m-d", strtotime($_POST["sdd"]));
		} else if($v == 2)
		{
			$idoperatio=exequery("SELECT * FROM Mdepartment M where dept = 'OPERATION'");
			$idept = fetch($idoperatio);
			$iremark = $_POST["odr"];
			$idate = date("Y-m-d", strtotime($_POST["odd"]));
		} else if($v == 3)
		{
			$idtechinca=exequery("SELECT * FROM Mdepartment M where dept = 'TECHINCAL'");
			$idept = fetch($idtechinca);
			$iremark = $_POST["tdr"];
			$idate = date("Y-m-d", strtotime($_POST["tdd"]));
		} else if($v == 4)
		{
			$idpersonne=exequery("SELECT * FROM Mdepartment M where dept = 'PERSONNEL'");
			$idept = fetch($idpersonne);
			$iremark = $_POST["pdr"];	
			$idate = date("Y-m-d", strtotime($_POST["pdd"]));
		}
			
		$insertdetails1 ="insert into ApprailsalOcc(aid, date, remark, deptid) values (".$out1[0].",'".$idate."','".$iremark."',".$idept[0].")";
		exequery($insertdetails1);
	}
	//echo "succcess " + sdd + "=" + sdr + "=" + odd + "=" + odr + "=" + tdd + "=" + tdr + "=" + pdd + "=" + pdr;
	echo "success";
	die(); 
}

// mearged abhijeet 12-feb-2018
//--cc OccasionReport
if($_POST['action']=="OccasionReport")
{
	$empcode = $_POST['empcode'];
	
	$maxidno1="SELECT aid FROM `ApprailsalTrans` WHERE empcode='".$empcode."' AND CURDATE() BETWEEN fdate AND tdate";
	//echo $maxidno1;
	$rs1=exequery($maxidno1);
	$nsi = mysql_num_rows($rs1);
	if($nsi != 1)
	{
		echo "Appraisal_id_notfound";
		die();
	}
	$out1=fetch($rs1);
	
	$q1="SELECT A.*, M.dept FROM ApprailsalOcc A INNER JOIN Mdepartment M ON A.deptid=M.did WHERE aid = '".$out1[0]."'"; 
	$r1=exequery($q1);
	while($r = fetch($r1))
	{
		$data .= $r[0]."#".$r[1]."#".$r[2]."#".$r[3]."#".$r[4]."$"; 
	}
	echo $data;
	die();
}

//mearged abhijeet 10-feb-2018
if($_POST['action'] == "detailSave")
{
	$empcode = $_POST['empcode'];
	
	$maxidno1="SELECT aid FROM `ApprailsalTrans` WHERE empcode='".$_POST['empcode']."' AND CURDATE() BETWEEN fdate AND tdate";
	$rs1=exequery($maxidno1);
	$nsi = mysql_num_rows($rs1);
	if($nsi != 1)
	{
		echo "Appraisal_id_notfound";
		die();
	}
	$out1=fetch($rs1);
	
	if($nsi >= 1){
		$deleterow="delete from ApprailsalDetails1 where aid='".$out1[0]."'";
		exequery($deleterow);
	}
		
	//$c = "";
	for($v=1; $v < 4; $v++)
	{
		($_POST['oqa'.$v] == 'yes') ? $oqa = 'true' : $oqa = 'false';
	
		$insertdetails1 ="insert into ApprailsalDetails1(aid, qid, tick, reason)values(".$out1[0].",".$v.",".$oqa.",'".$_POST['or'.$v]."')";
		exequery($insertdetails1);
	}
	echo "succcess";
	die(); 
}

//mearged abhijeet 10-feb-2018
if($_POST['action'] == "detailSave2")
{
	$empcode = $_POST['empcode'];
	
	$maxidno1="SELECT aid FROM `ApprailsalTrans` WHERE empcode='".$_POST['empcode']."' AND CURDATE() BETWEEN fdate AND tdate";
	$rs1=exequery($maxidno1);
	$nsi = mysql_num_rows($rs1);
	if($nsi != 1)
	{
		echo "Appraisal_id_notfound";
		die();
	}
	$out1=fetch($rs1);
	
	if($nsi >= 1){
		$deleterow="delete from ApprailsalDetails2 where aid='".$out1[0]."'";
		exequery($deleterow);
	}

	//$c = "";
	for($v=1; $v < 5; $v++)
	{
		($_POST['oa'.$v] == 'yes') ? $oa = 'true' : $oa = 'false';
	
		$insertdetails1 ="insert into ApprailsalDetails2(aid, qid, tick, remarks)values(".$out1[0].",".$v.",".$oa.",'".$_POST['oar'.$v]."')";
		exequery($insertdetails1);
	}
	echo "succcess";
	die(); 
}

//mearged abhijeet 10-feb-2018
// start occasion detail 
if($_POST['action']=="OccasionDetailsDisp")
{
	$empcode = $_POST['empcode'];
	
	$maxidno1="SELECT aid FROM `ApprailsalTrans` WHERE empcode='".$empcode."' AND CURDATE() BETWEEN fdate AND tdate";
	//echo $maxidno1;
	$rs1=exequery($maxidno1);
	$nsi = mysql_num_rows($rs1);
	if($nsi != 1)
	{
		echo "Appraisal_id_notfound";
		die();
	}
	$out1=fetch($rs1);
	
	$q1="SELECT * FROM ApprailsalDetails1 WHERE aid = '".$out1[0]."'"; 
	$r1=exequery($q1);
	while($r = fetch($r1))
	{
		$data .= $r[0]."#".$r[1]."#".$r[2]."#".$r[3]."$"; 
	}
	
	$data .= "$$";
	$q2="SELECT * FROM ApprailsalDetails2 WHERE aid = '".$out1[0]."'";
	$r2=exequery($q2);
	while($r = fetch($r2))
	{
		$data .= $r[0]."#".$r[1]."#".$r[2]."#".$r[3]."$";
	}
	
	echo $data;
	die(); 
	
	//echo oq1+"##"+or1+"##"+oq2+"##"+or2+"##"+oq3+"##"+or3+"###"+oa1+"##"+oar1+"##"+oa2+"##"+oar2+"##"+oa3+"##"+oar3+"##"+oa4+"##"+oar4   
	
	
}
// end occasion details


if($_POST['action'] == "CrewRatings")
{
	$empcode = $_POST['empcode'];
	
	$maxidno1="SELECT aid FROM `ApprailsalTrans` WHERE empcode='".$_POST['empcode']."' AND CURDATE() BETWEEN fdate AND tdate";
	$rs1=exequery($maxidno1);
	$nsi = mysql_num_rows($rs1);
	if($nsi != 1)
	{
		echo "Appraisal_id_notfound";
		die();
	}
	$out1=fetch($rs1);
	
	if($nsi >= 1){
		$deleterow="delete from ApprailsalTrans1 where aid='".$out1[0]."'";
		exequery($deleterow);
	}
		
	
	$up = split("#;;#",$_POST['crew_ratings']);
		for($v=0; $v < count($up); $v++)
		{
			if($up[$v] != "")
			{
				$upb = split(":##:",$up[$v]);//transaction,$_POST['empcode']
				
				$updaterowin="insert into ApprailsalTrans1(aid, qno, marks, remarks)values('".$out1[0]."','".mysql_real_escape_string($upb[2])."','".mysql_real_escape_string($upb[0])."','".mysql_real_escape_string($upb[1])."')";
				exequery($updaterowin);
				//$idmax1++;
			}
		}
	die();
}

if($_POST['action'] == "empinfosave")
{
	$chkqry = "select * from ApprailsalTrans where empcode ='".$_POST['empcode']."'";
	
	$chkres = exequery($chkqry);
	$chknumrow = mysql_num_rows($chkres);
	$chkrow = fetch($chkres);
	if($chknumrow >= 1)
	{
		$qryupd = "update ApprailsalTrans SET fdate='".DMYtoYMD($_POST['fromd'])."', tdate='".DMYtoYMD($_POST['tod'])."' where empcode ='".$_POST['empcode']."'";
		$qryures = exequery($qryupd);
	}
	else{
		$maxidno1="select max(aid) from ApprailsalTrans";
							
		$rs1=exequery($maxidno1);
		$out1=fetch($rs1);
		if($out1[0]!=null)
		$idmax1=$out1[0]+1;
		else{
			$idmax1 =1;
		}
		$curdate = date('Y-m-d');
		$curtime = date("H:i:s");
		$qryins = "insert into ApprailsalTrans(aid,empcode,fdate,tdate,entrydate,entrytime) values ('".mysql_real_escape_string($idmax)."','".mysql_real_escape_string($_POST['empcode'])."','".DMYtoYMD($_POST['fromd'])."','".DMYtoYMD($_POST['tod'])."','".$curdate."','".$curtime."')"; 
		$qryinsr = exequery($qryins);
	}
	
}

if($_POST['action']=="AppraisalAssment")
{
	$empcode = $_POST['empcode'];
	$dept = $_POST['dept'];
	
	?>
	<tr><td style='text-align:center;' width='50%'> CREW / RATINGS</td>     
		<td style='text-align:center;'width='15%'> ASSESSMENT 1 TO 10</td>
		<td style='text-align:center;'>REMARK</td>
	</tr>
	<?
	$chkque="SELECT aid FROM ApprailsalTrans WHERE empcode='".$_POST['empcode']."' AND CURDATE() BETWEEN fdate AND tdate";
	$chkq=exequery($chkque);
	$chkqrow = fetch($chkq);
	
		$apptra1 = "select aid,qno,marks,remarks from ApprailsalTrans1 where aid ='".$chkqrow[0]."' order by qno ";
		$apptra1res = exequery($apptra1);
		$apptra1num = mysql_num_rows($apptra1res);
		
		if($apptra1num > 1)
		{
			
			$i = 1;
			echo "<input type='hidden' id='noofquestion' name='noofquestion' value='".$apptra1num."'/>";
			while($apptra1row = fetch($apptra1res))
			{
				$queqry1 = "select cid,heading,cdecp from MAppraisalQue where cid = '".$apptra1row[1]."' ";
				$queres1 = exequery($queqry1);
				$querow1 = fetch($queres1);
				?>
				<tr>
					<td><strong><u><? echo $querow1['heading']; ?></u></strong><br><? echo $querow1['cdecp']; ?></td>
					<td><input type='hidden'  id='queid<? echo $i; ?>' name='queid<? echo $i; ?>' value='<? echo $querow1['cid']; ?>'/> 
						<select class='form-control' style='width:70px;' id='crewass<? echo $i; ?>' name='crewass<? echo $i; ?>'>
							<option value='<? echo $apptra1row['marks']; ?>'><? echo $apptra1row['marks']; ?></option>
							<option value=''></option>
							<option value='1'>1</option>
							<option value='2'>2</option>
							<option value='3'>3</option>
							<option value='4'>4</option>
							<option value='5'>5</option>
							<option value='6'>6</option>
							<option value='7'>7</option>
							<option value='8'>8</option>
							<option value='9'>9</option>
							<option value='10'>10</option>
						</select>
					</td>
					<td> <textarea class="form-control" id='crewremark<? echo $i; ?>' name='crewremark<? echo $i; ?>'  ><? echo $apptra1row['remarks']; ?></textarea> </td>			
				</tr>
				
			<?	
				$i++;
			}
			die();
			
		}
		else{
			
			$i = 1;
			$appques = "select * from MAppraisalQue where active='1' order by cid  ";
			$appqres = exequery($appques);
			$appnumrow = mysql_num_rows($appqres); 
			echo "<input type='hidden' id='noofquestion' name='noofquestion' value='".$appnumrow."'/>";
			while($querow = fetch($appqres))
			{	
		?>
			<tr>
				<td><strong><u><? echo $querow['heading']; ?></u></strong><br><? echo $querow['cdecp']; ?></td>
																		
				<td><input type='hidden' id='queid<? echo $i; ?>' name='queid<? echo $i; ?>' value='<? echo $querow['cid']; ?>'/> 
					<select class='form-control' style='width:70px;' id='crewass<? echo $i; ?>' name='crewass<? echo $i; ?>'>
						<option value=''></option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<!--<input type='text' id='crewass<? //echo $i; ?>' name='crewass<? //echo $i; ?>' value='' onkeypress='return isNumber(event);' /> -->
				</td>
				<td> <textarea class="form-control" id='crewremark<? echo $i; ?>' name='crewremark<? echo $i; ?>' ></textarea> </td>
			</tr>
		<?
				$i++;
			}
			
			
		die();	
			
		}

	die();
}

if($_POST['action'] == "AppTraingSave")
{
	$empcode = $_POST['empcode'];
	
	$maxidno1="SELECT aid FROM `ApprailsalTrans` WHERE empcode='".$_POST['empcode']."' AND CURDATE() BETWEEN fdate AND tdate";
	$rs1=exequery($maxidno1);
	$nsi = mysql_num_rows($rs1);
	if($nsi != 1)
	{
		echo "Appraisal_id_notfound";
		die();
	}
	$out1=fetch($rs1);
	
	if($nsi >= 1){
		$deleterow="delete from ApprailsalTrans2 where aid='".$out1[0]."'";
		exequery($deleterow);
	}
		
	
	$up = split("#;;#",$_POST['App_traning']);
		for($v=0; $v < count($up); $v++)
		{
			if($up[$v] != "")
			{
				$upb = split(":##:",$up[$v]);//transaction,$_POST['empcode']
				
				//echo $upb[0];
				
				$updaterowin="insert into ApprailsalTrans2(aid, qno, remark)values('".$out1[0]."','".mysql_real_escape_string($upb[0])."','".mysql_real_escape_string($upb[1])."')";
				exequery($updaterowin);
				$idmax1++;
			}
		}	
	die();
}

if($_POST['action'] == "Appraisaltraining")
{
	$empcode = $_POST['empcode'];
	$dept = $_POST['dept'];
	echo "<tr><td style='text-align:left;' width='50%'> <strong>TRAINING</strong> </td>  </tr>";
	
	$chkque="SELECT aid FROM ApprailsalTrans WHERE empcode='".$_POST['empcode']."' AND CURDATE() BETWEEN fdate AND tdate";
	$chkq=exequery($chkque);
	$chkqrow = fetch($chkq);
	
		$apptra1 = "select aid,qno,remark from ApprailsalTrans2 where aid ='".$chkqrow[0]."' order by qno";
		$apptra1res = exequery($apptra1);
		$apptra1num = mysql_num_rows($apptra1res);
		if($apptra1num > 1)
		{	
			$i= 1;
			echo "<input type='hidden' id='nooftraq' name='nooftraq' value='".$apptra1num."'/>";
			while($apptrain = fetch($apptra1res))
			{
				$apptra = "Select tid,tdecp from MAppraisalTrain where tid='".$apptrain[0]."'";
				$apptrares = exequery($apptra);
				$apptrarow = fetch($apptrares);
				
				echo"<tr><input type='hidden' id='trainingid".$i."' name='trainingid".$i."' value='".$apptrain['qno']."'/><td>".$apptrarow[1]."</td></tr>";
				echo"<tr><td> <textarea class='form-control' id='training".$i."'  name='training".$i."' >".$apptrain['remark']."</textarea> </td></tr>";
				$i++;
			}
			die();
		}
		else{
			
			$apptraining = "select tid,tdecp from MAppraisalTrain where active='1' order by tid  ";
			$apptres = exequery($apptraining);
			$apptnumrow = mysql_num_rows($apptres); 
			$i= 1;
			echo "<input type='hidden' id='nooftraq' name='nooftraq' value='".$apptnumrow."'/>";
			while($trainingrow = fetch($apptres))
			{	
				echo"<tr><input type='hidden' id='trainingid".$i."' name='trainingid".$i."' value='".$trainingrow['tid']."'/><td>".$trainingrow['tdecp']."</td></tr>";
				echo"<tr><td> <textarea class='form-control' id='training".$i."'  name='training".$i."' ></textarea> </td></tr>";
				$i++;
			}
			
			
			die();	
			
		}
		
	die();	
}

if($_POST['action'] == "AppScoreSave")
{
	$empcode = $_POST['empcode'];

	$maxidno1="SELECT aid FROM `ApprailsalTrans` WHERE empcode='".$_POST['empcode']."' AND CURDATE() BETWEEN fdate AND tdate";
	$rs1=exequery($maxidno1);
	$nsi = mysql_num_rows($rs1);
	
	echo $nsi;
	
	if($nsi != 1)
	{
		echo "Appraisal_id_notfound";
		die();
	}
	$out1=fetch($rs1);
	
	if($nsi >= 1){
		$deleterow="delete from ApprailsalTrans3 where aid='".$out1[0]."'";
		exequery($deleterow);
	}
	//echo "entered";
	$up = split("#;;#",$_POST['App_score']);
	echo $up[0];
		for($u=0; $u < count($up)-1; $u++)
		{ 
			$up1 = split(":##:",$up[$u]);
			
			if($up[$u] != "")
			{
				
				
				$updaterowin="insert into ApprailsalTrans3(aid, qno, feild1, feild2, feild3, feild4, feild5)values('".$out1[0]."','".mysql_real_escape_string($up1[0])."','".mysql_real_escape_string($up1[1])."','".mysql_real_escape_string($up1[2])."','".mysql_real_escape_string($up1[3])."','".mysql_real_escape_string($up1[4])."','".mysql_real_escape_string($up1[5])."')";
				
				exequery($updaterowin);
			
			}
		
		}	
		
	die();
}

if($_POST['action'] == "scorestafftable")
{
	$empcode = $_POST['empcode'];
	$dept = $_POST['dept'];
	echo "<tr><td style='text-align:left;' > <strong>SCORE</strong> </td> <td style='text-align:left;' > <strong>1-2</strong> </td> <td style='text-align:left;' > <strong>3-4</strong> </td> <td style='text-align:left;' > <strong>5-6</strong> </td> <td style='text-align:left;' > <strong>7-8</strong> </td> <td style='text-align:left;' > <strong>9-10</strong> </td>  </tr>";
	
	$chkque="SELECT aid FROM ApprailsalTrans WHERE empcode='".$_POST['empcode']."' AND CURDATE() BETWEEN fdate AND tdate";
	$chkq=exequery($chkque);
	$chkqrow = fetch($chkq);
	
		$apptra1 = "select aid,qno,feild1,feild2,feild3,feild4,feild5 from ApprailsalTrans3 where aid ='".$chkqrow[0]."' order by qno";
		$apptra1res = exequery($apptra1);
		$apptra1num = mysql_num_rows($apptra1res);
		if($apptra1num > 1)
		{	
			echo "<input type='hidden' id='noofscro' name='noofscro' value='".$apptra1num."'/>";
			$i= 1;
			
			while($apptrain = fetch($apptra1res))
			{
				$apptra = "Select * from MScore where sid='".$apptrain['qno']."' ";
				$apptrares = exequery($apptra);
				$apptrarow = fetch($apptrares);
				
				 echo "<tr><input type='hidden' id='scoreid".$i."' name='scoreid".$i."' value='".$apptrarow['sid']."'/><td>".$apptrarow['decp']."</td>";
				
				
				echo "<td>".$apptrarow['field1']." <select class='form-control' id='score".$i."1'  name='score".$i."1' ><option value='".$apptrain['feild1']."'>".$apptrain['feild1']."</option><option value=''></option><option value='1'>1</option><option value='2'>2</option></select> </td>";
				echo "<td>".$apptrarow['field2']." <select class='form-control' id='score".$i."2'  name='score".$i."2' ><option value='".$apptrain['feild2']."'>".$apptrain['feild2']."</option><option value=''></option><option value='1'>1</option><option value='2'>2</option></select> </td>";
				echo "<td>".$apptrarow['field3']." <select class='form-control' id='score".$i."3'  name='score".$i."3' ><option value='".$apptrain['feild3']."'>".$apptrain['feild3']."</option><option value=''></option><option value='1'>1</option><option value='2'>2</option></select> </td>";
				echo "<td>".$apptrarow['field4']." <select class='form-control' id='score".$i."4'  name='score".$i."4' ><option value='".$apptrain['feild4']."'>".$apptrain['feild4']."</option><option value=''></option><option value='1'>1</option><option value='2'>2</option></select> </td>";
				echo "<td>".$apptrarow['field5']." <select class='form-control' id='score".$i."5'  name='score".$i."5' ><option value='".$apptrain['feild5']."'>".$apptrain['feild5']."</option><option value=''></option><option value='1'>1</option><option value='2'>2</option></select> </td></tr>";
				$i++;
				
				
			}
			
			echo "<tr><td><strong>Column Total</strong></td>
						<td><input type='text' id='totalfieldfir' class='form-control' name='totalfieldfir' value='0'/></td>
						<td><input type='text' id='totalfieldsec' class='form-control' name='totalfieldsec' value='0'/></td>
						<td><input type='text' id='totalfieldtrd' class='form-control' name='totalfieldtrd' value='0'/></td>
						<td><input type='text' id='totalfieldfour' class='form-control' name='totalfieldfour' value='0'/></td>
						<td><input type='text' id='totalfieldfive' class='form-control' name='totalfieldfive' value='0'/></td>
					</tr>
					<tr><td><strong>Grand Total</strong></td>
						<td colspan='5'><input type='text' class='form-control' style='width:200px' id='grandtotal' name='grandtotal' value=''/></td>
					</tr>";
			die();
		}
		else{
			
			$apptraining = "select sid,decp, field1, field2, field3, field4, field5 from MScore where active='1' order by sid  ";
			$apptres = exequery($apptraining);
			$apptnumrow = mysql_num_rows($apptres); 
			$i= 1;
			//$j=1;
			echo "<input type='hidden' id='noofscro' name='noofscro' value='".$apptnumrow."'/>";
			while($scorerow = fetch($apptres))
			{	
				
				echo"<tr><input type='hidden' id='scoreid".$i."' name='scoreid".$i."' value='".$scorerow['sid']."'/><td>".$scorerow['decp']."</td>
				
				<td>".$scorerow['field1']."</br><select class='form-control' id='score".$i."1'  name='score".$i."1' ><option value=''></option><option value='1'>1</option><option value='2'>2</option></select> </td>
				
				<td>".$scorerow['field2']."</br><select class='form-control' id='score".$i."2'  name='score".$i."2' ><option value=''></option><option value='1'>1</option><option value='2'>2</option></select></td>
				
				<td>".$scorerow['field3']."</br><select class='form-control' id='score".$i."3'  name='score".$i."3' ><option value=''></option><option value='1'>1</option><option value='2'>2</option></select></td>
				
				<td>".$scorerow['field4']."</br><select class='form-control' id='score".$i."4'  name='score".$i."4' ><option value=''></option><option value='1'>1</option><option value='2'>2</option></select></td>
				
				<td>".$scorerow['field5']."</br><select class='form-control' id='score".$i."5'  name='score".$i."5' ><option value=''></option><option value='1'>1</option><option value='2'>2</option></select></td></tr>";
				//echo"<tr><td> <select class='form-control' id='score".$i."'  name='score".$i."' ></select> </td></tr>";
				//$j++; 
				$i++;
			}
			
			echo "<tr><td><strong>Column Total</strong></td>
					<td><input type='text' id='totalfieldfir' class='form-control' name='totalfieldfir' value='0'/></td>
					<td><input type='text' id='totalfieldsec' class='form-control' name='totalfieldsec' value='0'/></td>
					<td><input type='text' id='totalfieldtrd' class='form-control' name='totalfieldtrd' value='0'/></td>
					<td><input type='text' id='totalfieldfour' class='form-control' name='totalfieldfour' value='0'/></td>
					<td><input type='text' id='totalfieldfive' class='form-control' name='totalfieldfive' value='0'/></td>
				</tr>
				<tr><td><strong>Grand Total</strong></td>
					<td colspan='5'><input type='text' class='form-control' style='width:200px' id='grandtotal' name='grandtotal' value=''/></td>
				</tr>";
			
			die();	
			
		}
		
		
		
	die();	
}

?>

<? include_once('header.php'); ?>

<script type="text/javascript">


//mearged abhijeet -- 10-feb-2018
function occasionSave()
{
	
	var sdd = $("#safdeptdate").val();
	var odd = $("#opdeptdate").val();
	var tdd = $("#tefdeptdate").val();
	var pdd = $("#pedeptdate").val();
	
	var sdr = $("#sadeptremar").val();
	var odr = $("#opdeptremar").val();
	var tdr = $("#tedeptremar").val();
	var pdr = $("#pedeptremar").val();
	
	var empcode = $('#ecode').val();
	if($.trim(empcode)== ""){
		alert("Employment Id Is missing..!!");
		return false;
	}
	//alert(sdd +'='+ sdr +'='+ odd +'='+ odr +'='+ tdd +'='+ tdr +'='+ pdd +'='+ pdr);
	//alert(sdd +'='+ odd  +'='+ tdd  +'='+ pdd);
	
	$.ajax({
		url:"Appraisal.php",
		data:"action=occasionSave&empcode="+empcode+"&sdd="+sdd+"&sdr="+sdr+"&odd="+odd+"&odr="+odr+"&tdd="+tdd+"&tdr="+tdr+"&pdd="+pdd+"&pdr="+pdr,
		type:"post",				  
		success:function(output)
		{
			alert(output);		
		}
	});
}	

//mearged abhijeet 10-feb-2018
function OccasionReport()
{
	var empcode = $('#ecode').val();
	var dept = $('#dept').val(); 
	$.ajax({
		url:"Appraisal.php",
		data:"action=OccasionReport&empcode="+empcode,
		type:"post",				  
		success:function(output)
		{
			var output1;
			var output1 = output.split("$");
			//alert(output1.length);
	
			var dt = '';
			
			for(var la=0; la<(output1.length-1); la++)
			{
				data1 = output1[la].split("#");
				//alert( data1[0] +"="+ data1[1] +"="+ data1[2] +"="+ data1[3] +"="+ data1[4] )
				
				if(data1[4] == 'SAFTY') {
					$('#safdeptdate').val(data1[1]);
					$('#sadeptremar').val(data1[2]);
					dt = dt + "sa" + data1[1];
				} else if(data1[4] == 'OPERATION') {
					$('#opdeptdate').val(data1[1]);
					$('#opdeptremar').val(data1[2]);
					dt = dt + "op" + data1[1];
				} else if(data1[4] == 'TECHINCAL') {
					$('#tefdeptdate').val(data1[1]);
					$('#tedeptremar').val(data1[2]);
					dt = dt + "te" + data1[1];
				} else if(data1[4] == 'PERSONNEL') {
					$('#pedeptdate').val(data1[1]);
					$('#pedeptremar').val(data1[2]);
					dt = dt + "pe" + data1[1];
				}
				
				/*$("select[id=offiQA"+(la+1)+"]").find("option[value="+databool+"]").attr("selected", true); 
				$("#offireason"+(la+1)).val(data1[3]);*/
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			alert("Status: " + textStatus); alert("Error: " + errorThrown); 
		}
	});
}

//mearged abhijeet -- 10-feb-2018
function detailSave()
{	
	
	var oaq1 = $("#offiQA1").val();
	var oaq2 = $("#offiQA2").val();
	var oaq3 = $("#offiQA3").val();
	
	var or1 = $("#offireason1").val();
	var or2 = $("#offireason2").val();
	var or3 = $("#offireason3").val();
	
	var empcode = $('#ecode').val();
	if($.trim(empcode)== ""){
		alert("Employment Id Is missing..!!");
		return false;
	}
	
	$.ajax({
		url:"Appraisal.php",
		data:"action=detailSave&empcode="+empcode+"&oqa1="+oaq1+"&or1="+or1+"&oqa2="+oaq2+"&or2="+or2+"&oqa3="+oaq3+"&or3="+or3,
		type:"post",				  
		success:function(output)
		{
			alert(output);
		}
	});
}

//mearged abhijeet -- 10-feb-2018
function detailSave2()
{	
	
	var oa1 = $("#offAss1").val();
	var oa2 = $("#offAss2").val();
	var oa3 = $("#offAss3").val();
	var oa4 = $("#offAss4").val();
	
	var oar1 = $("#offiAPPreason1").val();
	var oar2 = $("#offiAPPreason2").val();
	var oar3 = $("#offiAPPreason3").val();
	var oar4 = $("#offiAPPreason4").val();
	
	var empcode = $('#ecode').val();
	if($.trim(empcode)== ""){
		alert("Employment Id Is missing..!!");
		return false;
	}
	//alert(empcode+"&oa1="+oa1+"&oar1="+oar1+"&oa2="+oa2+"&oar2="+oar2+"&oa3="+oa3+"&oar3="+oar3+"&oa4="+oa4+"&oar4="+oar4);
	
	$.ajax({
		url:"Appraisal.php",
		data:"action=detailSave2&empcode="+empcode+"&oa1="+oa1+"&oar1="+oar1+"&oa2="+oa2+"&oar2="+oar2+"&oa3="+oa3+"&oar3="+oar3+"&oa4="+oa4+"&oar4="+oar4,
		type:"post",				  
		success:function(output)
		{
			alert(output);			
		}
	});
}

//mearged abhijeet -- 10-feb-2018
function OccasionDetails()
{
	var empcode = $('#ecode').val();
	var dept = $('#dept').val();
	//alert(empcode);
	$.ajax({
			url:"Appraisal.php",
			data:"action=OccasionDetailsDisp&empcode="+empcode,
			type:"post",				  
			success:function(output)
			{	
				var output1 = output.split("$$$");
				
				var detail1 = output1[0].split("$");
				var detail2 = output1[1].split("$");
				
				var data1, data2;
				for(var la=0; la<detail1.length; la++)
				{
					data1 = detail1[la].split("#");
					//alert(data1[0] + "=" + data1[1] + "=" + data1[2] + "=" + data1[3]); 
					var databool = 'No';
					if(data1[2] == 0)
						databool = 'no';
					else
						databool = 'yes';

					$("select[id=offiQA"+(la+1)+"]").find("option[value="+databool+"]").attr("selected", true); 
					$("#offireason"+(la+1)).val(data1[3]);
				}
				
				for(var lb=0; lb<detail2.length; lb++)
				{
					data1 = detail2[lb].split("#");
					//alert(data1[0] + "=" + data1[1] + "=" + data1[2] + "=" + data1[3]); 
					var databool = 'No';
					if(data1[2] == 0)
						databool = 'no';
					else
						databool = 'yes';

					$("select[id=offAss"+(lb+1)+"]").find("option[value="+databool+"]").attr("selected", true); 
					$("#offiAPPreason"+(lb+1)).val(data1[3]);
				}
			}
	});
	
}

function crewAppSave()
{
	var empcode = $('#ecode').val();
		
	if($.trim(empcode)== ""){ 
		//alert("Employment Id Is missing..!!");
		return false;
	}
		
	var noofquestion = $('#noofquestion').val();
	var crew_ratings = "";
	
	for (var t=1; t<=noofquestion; t++){
		
		var queid = $('#queid'+t).val();
		var crewass = $('#crewass'+t).val();
		var crewremark = $('#crewremark'+t).val();
		
		if(jQuery.type(crewass) !== "undefined"){
			var ansrecord=crewass+':##:'+crewremark+':##:'+queid+'#;;#';	
			crew_ratings = crew_ratings + ansrecord;
		}
	}
	
	/* alert("Employment :" + crew_ratings); */
	$.ajax({
		url:"Appraisal.php",
		data:"action=CrewRatings&empcode="+empcode+"&crew_ratings="+crew_ratings,
		type:"post",				  
		success:function(output)
		{
			output1 = $.trim(output);
			
			if(output1 == "Appraisal_id_notfound")
			{
				alert("First fill Appraisal form....!!! ");
				//swal("First fill Appraisal form....!!! ");
				return false;
			}
			alert("Employment Code :"+empcode+ " Is Update successfully!!!!!!!");
			//swal(output1," ","success");			
		}
	});
}


function empinforSave()
{
	var empcode = $('#ecode').val();
	var fromd = $('#fromd').val();
	var tod = $('#tod').val();
	
	//alert(empcode);
	
	if($.trim(fromd) == ""){
			alert("Please Enter From Date..!!");
			$('#fromd').focus();
			return false;
		}
		
	if($.trim(tod) == ""){
			alert("Please Enter To Date..!!");
			$('#tod').focus();
			return false;
		}	
	
	$.ajax({
			url:"Appraisal.php",
			data:"action=empinfosave&empcode="+empcode+"&fromd="+fromd+"&tod="+tod,
			type:"post",				  
			success:function(output)
			{
				alert("Employment Code :"+empcode+ " Is Update successfully!!!!!!!");
				output1 = $.trim(output);
				//alert(output1);
				
			}
		});
	
}
//--------------------------------- fetch Appraisal Assessment Question --------------------------------------------
function AppraisalAssment()
{
	var empcode = $('#ecode').val();
	var dept = $('#dept').val();
	$.ajax({
			url:"Appraisal.php",
			data:"action=AppraisalAssment&empcode="+empcode+"&dept="+dept,
			type:"post",				  
			success:function(output)
			{
				//alert("Employment Code :"+empcode+ " Is Update successfully!!!!!!!");
				output1 = $.trim(output);
				//alert(output1);
				$('#Appraisalqueition').html(output1);
				
			}
	});
	
}

//--------------------------------- fetch Appraisal Training Question --------------------------------------------
function AppraisalTrain()
{
	var empcode = $('#ecode').val();
	var dept = $('#dept').val();
	$.ajax({
			url:"Appraisal.php",
			data:"action=Appraisaltraining&empcode="+empcode+"&dept="+dept,
			type:"post",				  
			success:function(output)
			{
				//alert("Employment Code :"+empcode+ " Is Update successfully!!!!!!!");
				output1 = $.trim(output);
				//alert(output1);
				$('#Appraisaltraining').html(output1);
				
			}
	});
	
}

//------------------------ save Appraisal Training -----------------------------------------------
function trainingSave()
{
	var empcode = $('#ecode').val();
	if($.trim(empcode)== ""){
		alert("Employment Id Is missing..!!");
		return false;
	}
	var dept = $('#dept').val();
	
	
	var nooftraq = $('#nooftraq').val();
	var App_traning = "";
	
	for (var t=1; t<=nooftraq; t++){
		
		var trainingid = $('#trainingid'+t).val(); // is displaying id
		var trainingrem = $('#training'+t).val(); //training1
		
		//alert("trainingid"+trainingid);
		//alert("trainingrem"+trainingrem);
		
		
		if(jQuery.type(trainingid) !== "undefined"){
			var ansrecord=trainingid+':##:'+trainingrem+'#;;#';	
			App_traning = App_traning + ansrecord;
		}
	}
	//alert(App_traning);
	
	$.ajax({
			url:"Appraisal.php",
			data:"action=AppTraingSave&empcode="+empcode+"&App_traning="+App_traning,
			type:"post",				  
			success:function(output)
			{
				output1 = $.trim(output);
				
				if(output1 == "Appraisal_id_notfound")
				{
					alert("First fill Appraisal form....!!! ");
					return false;
				}
				alert("success");			
			}
		});
	
	
}

//--------------------------------- fetch score Question --------------------------------------------
function AppraisalScore()
{
	var empcode = $('#ecode').val();
	var dept = $('#dept').val();
	$.ajax({
			url:"Appraisal.php",
			data:"action=scorestafftable&empcode="+empcode+"&dept="+dept,
			type:"post",				  
			success:function(output)
			{
				output1 = $.trim(output);
				//alert(output1);
				$('#scorestafftable').html(output1);
				
			}
	});
	
}

//-------------------------------------save score ----------------------------------
function scoreSave()
{
	//alert("entered");
	var empcode = $('#ecode').val();
	if($.trim(empcode)== ""){
		alert("Employment Id Is missing..!!");
		return false;
	}
	var dept = $('#dept').val();
	
	
	var noofscore = $('#noofscro').val();
	var App_score = "";
	var rowscore ='';
	var fullrow ='';
	var column1=0;
	var column2=0;
	var column3=0;
	var column4=0;
	var column5=0;
	//alert(noofscore);
	for (var t=1; t<=noofscore; t++){
		var scoreid = $('#scoreid'+t).val();
		if(jQuery.type(scoreid) !== "undefined"){
			rowscore = scoreid;
			for(var i=1; i<=5; i++){
			 // is displaying id
			var scorerem = $('#score'+t+i).val(); //training1
			
			//alert("scoreid"+scoreid);
			//alert("scorerem"+scorerem);
			if(i==1){
				if(scorerem==''){
					scorerem=0;
				}
				column1 = parseInt(column1) + parseInt(scorerem);
				//alert(column1);
				if(scorerem==0){
					scorerem='';
				}
			}
			if(i==2){
				if(scorerem==''){
					scorerem=0;
				}
				column2 = parseInt(column2) + parseInt(scorerem);
				//alert(column1);
				if(scorerem==0){
					scorerem='';
				}
			}
			if(i==3){
				if(scorerem==''){
					scorerem=0;
				}
				column3 = parseInt(column3) + parseInt(scorerem);
				//alert(column1);
				if(scorerem==0){
					scorerem='';
				}
			}
			if(i==4){
				if(scorerem==''){
					scorerem=0;
				}
				column4 = parseInt(column4) + parseInt(scorerem);
				//alert(column1);
				if(scorerem==0){
					scorerem='';
				}
			}
			if(i==5){
				if(scorerem==''){
					scorerem=0;
				}
				column5 = parseInt(column5) + parseInt(scorerem);
				//alert(column1);
				if(scorerem==0){
					scorerem='';
				}
			}
			rowscore = rowscore+":##:"+scorerem;
			//alert(rowscore);
			}
			fullrow = fullrow + rowscore+'#;;#';
		}
		//App_score = App_score + ansrecord; 
		//var ansrecord=scoreid+':##:'+scorerem+'#;;#';
		
	}
	
	var col1 = parseInt(column1);
	var col2 = parseInt(column2);
	var col3 = parseInt(column3);
	var col4 = parseInt(column4);
	var col5 = parseInt(column5);
	
	var grandtotal = col1+col2+col3+col4+col5;
	
	$("#totalfieldfir").val(col1);
	$("#totalfieldsec").val(col2);
	$("#totalfieldtrd").val(col3);
	$("#totalfieldfour").val(col4);
	$("#totalfieldfive").val(col5);
	
	$("#grandtotal").val(grandtotal);
	
	$.ajax({
			url:"Appraisal.php",
			data:"action=AppScoreSave&empcode="+empcode+"&App_score="+fullrow,
			type:"post",				  
			success:function(output)
			{
				output1 = $.trim(output);
				
				//alert(output1);
				
				if(output1 == "Appraisal_id_notfound")
				{
					alert("First fill Appraisal form....!!! ");
					return false;
				}
				alert("success");			
			}
		});
}

</script>

<body>

<?php

function getCanId($myecode, $myname)
{
	//SELECT concat(canid, ' : ', name, ' ', middlename, ' ', surname) accol FROM EmpMaster WHERE canid 
	//$arname = explode(" ", $myname);
	//echo count($arname);
	
		$qCan = "SELECT canid FROM `EmployeeReg` WHERE eid='".$myecode."'";
		$rsCan = exequery($qCan);
		$rowCan = fetch($rsCan);
		if($rowCan[0] > 0)
			return $rowCan[0];
		return false;
}

if(isset($_POST["myempcode"]) && $_POST["myempcode"] !== "") {

	$ecodearr = explode(" : ", $_POST["myempcode"]);
	$ecodearr[2] = getCanId($ecodearr[0], $ecodearr[1]);
	
	if($ecodearr[2] != false)
	{
		
?>
		
	<link rel="stylesheet" type="text/css" href="assets-minified/widgets/datepicker/datepicker.css" />
	<script type="text/javascript" src="assets-minified/widgets/datepicker/datepicker.js"></script>
	<script type="text/javascript">
		/* Datepicker bootstrap */
		$(function() {
			$('.bootstrap-datepicker').bsdatepicker({
				format: 'dd-mm-yyyy'
			});
		});
	</script>
	
	
	<link rel="stylesheet" type="text/css" href="assets-minified/widgets/tabs-ui/tabs.css" />
	<script type="text/javascript" src="assets-minified/widgets/tabs-ui/tabs.js"></script>
	<script type="text/javascript">
		/* jQuery UI Tabs */

		$(function() {
			$(".tabs").tabs();
		});

		$(function() {
			$(".tabs-hover").tabs({
				event: "mouseover"
			});
		});
		
		
	</script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			 // alert('hi');
		  $('#ui-accordion-1-header-0').trigger('click');
		
		});
		
		function assessmentPotentialfun(){
			//alert("hi");
			$('#ui-accordion-4-header-1').trigger('click');
		}
		
		//$( "#occasion" ).accordion();
		
		function officeusefun(){
			//alert("hi");
			$('#ui-accordion-5-header-1').trigger('click');
			
		}
		function details()
		{
			//alert("tab4");
			//$('#ui-accordion-4-header-0').trigger('click');
		}
		
		
		function ocasionForreptfun()
		{
			//$('#ui-accordion-5-header-1').trigger('click');
			
		}
			
			//$(":input").each(function (i) { $(this).attr('tabindex', i + 1); });
	</script>
		
		<div id="page-content">
			<!--<input type='hidden' id='ecode' name='ecode' value='4'/> -->
			<div class="page-box">
				<!--<h3 class="page-title">Vessel Reporting</h3>-->
				<div class="example-box-wrapper" id="Employmentform" style="display:block;">
					<form id="demo-form" class="form-horizontal" data-parsley-validate="">
						<div class='row'>
							<div class='form-group'>
								<div class="content-box tabs" style="padding:20px;">
									<div id="dialog" style="display: none" align="center" style="height:400px; width:400px;">
												<div id='showpdf' style='height:100%; width:100%;'>
												
												</div>
													<img id='imgcershow' class='img-thumbnail' src='' alt='your image' height='100%' width='100%' />
									</div>
									<div id="dialog_pdf" style="display: none" align="center" style="height:400px; width:400px;">
										<iframe id='pdfcershow' src=''></iframe>
									</div>
								
									<h3 class="content-box-header bg-blue-alt">
										<span>Appraisal</span>
										<ul>
											<li>
												<a href="#crewappraisal" title="Tab 1" >
													Crew Appraisal / Ratings
												</a>
											</li>
											<li>
												<a href="#Training" title="Tab 2" onclick='AppraisalTrain();'>
													Training 
												</a>
											</li>
											<li>
												<a href="#Score" title="Tab 3" onclick='AppraisalScore();' >
													Score
												</a>
											</li>
											<li>
												<a href="#detailstab" title="Tab 4" onclick='OccasionDetails(); detailfun();'>
													Details
												</a>
											</li>
											<li>
												<a href="#occasion" title="Tab 5" onclick='OccasionReport(); occasionfun();'>
													Occasion For Report
												</a>
											</li>
										</ul>
									</h3>	

							<script src="js/bootstrap.minexpand.js"></script> 
									<div id="crewappraisal"> <!-- tab 1 -->
										<div class="accordion accordion-transparent"> 
											
											<div class="block">
												<div class="header">
													<h3 style='background: #65a6ff;'class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-state-hover ui-accordion-header-active ui-state-active ui-corner-top content-box-header bg-blue-alt" role="tab" id="ui-accordion-1-header-0" aria-controls="ui-accordion-1-panel-1" aria-selected="true" tabindex="0" data-toggle='collapse' data-target='#Appraisalmain'><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span> Appraisal </h3>
													
													<div id="Appraisalmain" class="collapse">
														
														<div class='row'>
														</br>
															<div class="col-md-4">
												
																<input type="hidden" name="updateid" id="updateid" >
																
																<div class="form-group">
																	<label for="" class="col-sm-6 control-label"> Employment Code <span class="required">*</span></label>
																	<div class="col-sm-6">
																		<input type="text" placeholder='' style="width:90%" class="form-control" id='ecode' name='ecode' value="<? echo $ecodearr[2]; ?>" readonly />
																	</div>
																</div>

															</div>
														</div>
														<div class='row'>
															<div class="form-row">		
																<div class="col-md-4">
																	<div class="form-group">
																		<label for="" class="col-sm-6 control-label">MT</label>
																		<div class="col-sm-6">
																			<input type="text" placeholder='' style="width:50%" class="form-control" id='mt' name='mt' value=""/>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class='row'>
																	
															<div class="col-md-4">
																<div class="form-group">
																	<label for="" class="col-sm-6 control-label">Name</label>
																	<div class="col-sm-6">
																		<input type="text" placeholder='' class="form-control" id='name' name='name' readonly='' value=''/>
																	</div>
																</div>
															</div>
															
															<div class="col-md-4">
																<div class="form-group">
																	<label for="" class="col-sm-6 control-label">Department</label>
																	<div class="col-sm-6">
																		<input type="text" placeholder='' class="form-control" id='dept' name='dept' value=''/>
																	</div>
																</div>
															</div>
															
															<div class="col-md-4">
																<div class="form-group">
																	<label for="" class="col-sm-6 control-label">Job Title</label>
																	<div class="col-sm-6">
																		<input type="text" placeholder='' class="form-control" id='jobtitle' name='jobtitle' value=''/>
																	</div>
																</div>
															</div>
															
														</div>
														<div class='row'>
															<div class="col-md-4">
																<div class="form-group">
																	<label for="" class="col-sm-6 control-label">Date Of Birth</label>
																	<div class="col-sm-6">
																		<input type="text" placeholder='' maxlength="10" class="datepicker form-control" id='dob' name='dob' readonly='' value=''/>
																	</div>
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group">
																	<label for="" class="col-sm-6 control-label">From</label>
																	<div class="col-sm-6">
																		<input type="text" maxlength="10" placeholder='' class="form-control bootstrap-datepicker" id='fromd' name='fromd' value=''/>
																	</div>
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group">
																	<label for="" class="col-sm-6 control-label">To</label>
																	<div class="col-sm-6">
																		<input type="text" maxlength="10" placeholder='' class="form-control bootstrap-datepicker" id='tod' name='tod' value=''/>
																	</div>
																</div>
															</div>
														</div>
														
														<div class='row'>
															<div class="col-md-4">
																<div class="form-group">
																	<label for="" class="col-sm-6 control-label">Date Employed In Company</label>
																	<div class="col-sm-6">
																		<input type="text" placeholder='' maxlength="10" class=" datepicker form-control" id='comdate' name='comdate' readonly='' value=''/>
																	</div>
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group">
																	<label for="" class="col-sm-6 control-label">CDC No</label>
																	<div class="col-sm-6">
																		<input type="text" placeholder='' class="form-control" id='cdcno' name='cdcno' value=''/>
																	</div>
																</div>
															</div>
														</div>
					
														
														<div class="form-row" id='empinfosave'>
															<div style="float:right;display:inline-block;">
																<!-- <button class="btn btn-primary" type="button" id='empsave' name='empsave' value='Save' onclick='empinforSave();'>Save</button> -->
																	&nbsp;&nbsp;&nbsp;
															</div>	
														</div>
													</div>
													
													</br>
													
													<h3 style='background: #65a6ff;' class='content-box-header bg-blue-alt ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-state-hover ui-accordion-header-active ui-state-active ui-corner-top' role='tab' id='ui-accordion-1-header-1' aria-controls='ui-accordion-1-panel-0' aria-selected='true' tabindex='0' data-toggle='collapse' data-target='#Appraisalmain1' onclick='AppraisalAssment();'><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s" ></span> APPRAISAL ASSESSMENT </h3>
												
													<div id="Appraisalmain1" class="collapse">
														<div class="block">
															<div class="content controls">
																<div class="row">
																<p class="title-lead">
																<strong>
																Note : Please fill out this Appraisal Report form with care and fairness and in the interest of both Rating and Company. Make an honest judgment of the qualities of the Rating,based on the entire period and not upon isolated incidents alone.
																</strong></p>
																</br>
																</DIV>
															</div>
														</div>
														<div class="block">
															<div class="content controls">
																<div class="row">
																<p class="title-lead">
																<strong>
																ON A SCALE OF 1 TO 10 INDICATE HOW YOU RATE THE SEAMAN COMPARED TO OTHERS YOU HAVE SAILED WITH 10 IS THE VERY BEST AND 1 THE WORST,IF THE RATING IS 3, ORLESS THAN 3 YOU SHOULDSUGGEST SUITABLE CORRECTIVE ACTION e.g. COUNSELING, TRAINING, WARNING ETC.
																<strong>
																</p>
																</div>
															</div>	
														</div>
														
														<div class="block">
														<div class="content controls">
															<div class="form-row">
																<div class="col-md-12">
																
																
																	<table id='Appraisalqueition' class="table table-bordered table-striped " width="100%" border='1'>
																		
																	</table>
																</div>
															</div>
															
															</br>
															
															<div class="form-row" id='ratingsave'>
																<div style="float:right;display:inline-block;">
																	<button class="btn btn-primary" type="button" id='ansave' name='ansave' value='Save' onclick='crewAppSave();'>Save</button>
																					&nbsp;&nbsp;&nbsp;
																</div>	
															</div>
														</div>
														</div>
														
														
													</div>
													
													
												
												</div>
											</div>
										</div>
									</div>	<!-- tab1 End -->
									
									<div id="Training"> <!-- tab 2 -->
										<div class="accordion accordion-transparent"> 
											
											<div class="block">
												<div class="header">
													<div class="block">
														<div class="content controls">
															<div class="form-row">
																<div class="col-md-12">
																	<table id='Appraisaltraining' class="table table-bordered table-striped " width="100%" border='1'>
																		<thead>
																		<?
																			$traqry = "select * from MAppraisalTrain where active='1'";
																				$trares = exequery($traqry);
																				$tranumrow = mysql_num_rows($trares);
																				echo "<input type='hidden' id='nooftraq' name='nooftraq' value='".$tranumrow."'/>";
																				while($trarow = fetch($trares))
																				{
																		?>			
																			<tr><td style='text-align:left;' > <? echo $trarow['tdecp'] ?></td></tr>     
																			<tr><td style='text-align:center;'> <textarea class="form-control" id='training<? echo $trarow['tid']; ?>' name='training<? echo $trarow['tid']; ?>' ></textarea> </td></tr>
																			<? } ?>
																		</thead>
																	</table>
																</div>
															</div>
															
															</br>
															
															<div class="form-row" id='trainingsave'>
																<div style="float:right;display:inline-block;">
																	<button class="btn btn-primary" type="button" id='trainingave' name='trainingave' value='Save' onclick='trainingSave();'>Save</button>
																					&nbsp;&nbsp;&nbsp;
																</div>	
															</div>	
															
														</div>
													</div>
  
												</div>
											</div>
										</div>
									</div>	<!-- tab2 End -->

									<div id="Score"> <!-- tab 3 -->
										<div class="accordion accordion-transparent"> 
											
											<div class="block">
												<h3 class="content-box-header bg-blue-alt">For All Staff (Tick as applicable and indicate the score in one of the columns below)(*)</h3>
												<div class="content controls">
													<div class="form-row">
														<div class="col-md-12">
															<table id='scorestafftable' class="table table-bordered table-striped " width="100%" border='1'>
																<thead>
																	<tr>
																		<th></th>
																		<th style='text-align:center;'>1-2</th>
																		<th style='text-align:center;'>3-4</th>
																		<th style='text-align:center;'>5-6</th>
																		<th style='text-align:center;'>7-8</th>
																		<th style='text-align:center;'>9-10</th>
																	</tr>
																
																<?
																	$scoreqry = "select * from MScore where active='1' order by sid";
																		$scoreres = exequery($scoreqry);
																		$sconumrow = mysql_num_rows($scoreres);
																		echo "<input type='hidden' id='noofscro' name='noofscro' value='".$sconumrow."'/>";
																		while($scorrow = fetch($scoreres))
																		{
																?>			
																		<tr>
																			<td style='text-align:left;' ><strong> <? echo $scorrow['decp'] ?></strong></td>
																			<td><strong><? echo $scorrow['field1'] ?></strong><br>
																				<select class='form-control' style='width:60px' id='fieldfir_<? echo $scorrow['sid'] ?>' name='fieldfir_<? echo $scorrow['sid']; ?>' onchange='scorecoTotal(1)'>
																					<option value=''> </option>
																					<option value='1'>1</option>
																					<option value='2'>2</option>
																				</select>
																			</td>
																			<td><strong><? echo $scorrow['field2'] ?></strong><br>
																				<select style='width:60px' class='form-control' id='fieldsec_<? echo $scorrow['sid'] ?>' name='fieldsec_<? echo $scorrow['sid']; ?>' onchange='scorecoTotal(2)'>
																					<option value=''> </option>
																					<option value='3'>3</option>
																					<option value='4'>4</option>
																				</select>
																			</td>
																			<td><strong><? echo $scorrow['field3'] ?></strong><br>
																				<select style='width:60px' class='form-control' id='fieldtrd_<? echo $scorrow['sid'] ?>' name='fieldtrd_<? echo $scorrow['sid'] ?>' onchange='scorecoTotal(3)' >
																					<option value=''> </option>
																					<option value='5'>5</option>
																					<option value='6'>6</option>
																				</select>
																			
																			</td>
																			<td><strong><? echo $scorrow['field4'] ?></strong><br>
																				<select class='form-control' style='width:60px' id='fieldfour_<? echo $scorrow['sid'] ?>' name='fieldfour_<? echo $scorrow['sid'] ?>' onchange='scorecoTotal(4)'>
																					<option value=''> </option>
																					<option value='7'>7</option>
																					<option value='8'>8</option>
																				</select>
																			
																			</td>
																			<td><strong><? echo $scorrow['field5'] ?></strong><br>
																				<select class='form-control' style='width:65px' id='fieldfive_<? echo $scorrow['sid'] ?>' name='fieldfive_<? echo $scorrow['sid'] ?>' onchange='scorecoTotal(5)' >
																					<option value=''> </option>
																					<option value='9'>9</option>
																					<option value='10'>10</option>
																				</select>
																			</td>
																		</tr>
																	<? } ?>
																	<tr><td><strong>Column Total</strong></td>
																		<td><input type='text' id='totalfieldfir' class='form-control' name='totalfieldfir' value='0'/></td>
																		<td><input type='text' id='totalfieldsec' class='form-control' name='totalfieldsec' value='0'/></td>
																		<td><input type='text' id='totalfieldtrd' class='form-control' name='totalfieldtrd' value='0'/></td>
																		<td><input type='text' id='totalfieldfour' class='form-control' name='totalfieldfour' value='0'/></td>
																		<td><input type='text' id='totalfieldfive' class='form-control' name='totalfieldfive' value='0'/></td>
																	</tr>
																	<tr><td><strong>Grand Total</strong></td>
																		<td colspan='5'><input type='text' class='form-control' style='width:200px' id='grandtotal' name='grandtotal' value=''/></td>
																	</tr>
																</thead>
															</table>
														</div>
													</div>
													
													</br>
													
													<div class="form-row" id='scoresave'>
														<div style="float:right;display:inline-block;">
															<button class="btn btn-primary" type="button" id='scoreave' name='scoreave' value='Save' onclick='scoreSave();'>Save</button>
																			&nbsp;&nbsp;&nbsp;
														</div>	
													</div>
													
													
												</div>
											
											</div>
										</div>	
									</div><!-- tab3 End -->
									<div id="detailstab"> <!-- tab 4 -->
										<div class="accordion accordion-transparent"> 
											
											<div class="block">
												<div class="header">
													<!--<h3 class="content-box-header bg-blue-alt">(*)</h3>-->
													<h3 onclick='details();' style='background: #65a6ff;' class="content-box-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-state-hover ui-accordion-header-active ui-state-active ui-corner-top" role="tab" id="ui-accordion-4-header-0" aria-controls="ui-accordion-1-panel-1" aria-selected="true" tabindex="0" data-toggle='collapse' data-target='#detailsReport' ><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span> (*) </h3>
													<!--<div class="content controls">-->
													<div id="detailsReport" class="collapse">
														<div class="form-row">
															<div class="col-md-12">
																<table id='' class="table table-bordered table-striped " width="100%" border='1'>
																	<thead>
																		<tr>
																			<td style='width:40%;'>Has the officer come up to your expectation of how one in this position should perform?</td>
																			<td>
																				<select style='width:100px' class='form-control' id='offiQA1' name='offiQA1'>
																					<option value='yes'>YES</option>
																					<option value='no'>No</option>
																				</select>
																			</td>
																			<td>if NO Give reason<br>
																			<textarea id='offireason1' class='form-control' name='offireason1'></textarea></td>
																			
																		</tr>
																		<tr>
																			<td style='width:40%;'>Has the officer been involved in any notable or difficult operations?</td>
																			<td>
																				<select style='width:100px' class='form-control' id='offiQA2' name='offiQA2'>
																					<option value='yes'>YES</option>
																					<option value='no'>No</option>
																				</select>
																			</td>
																			<td>
																			if YES Give reason<br><textarea id='offireason2' name='offireason2' class='form-control'></textarea></td>
																			
																		</tr>
																		<tr>
																			<td style='width:40%;'>Does the officer integrate well with all ranks?</td>
																			<td>
																				<select style='width:100px' id='offiQA3' name='offiQA3' class='form-control'>
																					<option value='yes'>YES</option>
																					<option value='no'>No</option>
																				</select>
																			</td>
																			<td>if NO Give reason<br><textarea class='form-control' id='offireason3' name='offireason3'></textarea></td>
																			
																		</tr>
																	</thead>
																</table>	
															
															</div>
														</div>
													</br>
													
													<div class="form-row" id='empDetailsSave'>
														<div style="float:right;display:inline-block;">
															<button class="btn btn-primary" type="button" id='saveDetails' name='saveDetails' value='Save' onclick='detailSave();'>Save</button>
																			&nbsp;&nbsp;&nbsp;
														</div>	
													</div>
													
													</div>
													
													</br>
													
													<!--<h3 class="content-box-header bg-blue-alt"> ASSESSMENT OF POTENTIAL(*) </h3>-->
													<h3 style='background: #65a6ff;' class="content-box-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-state-hover ui-accordion-header-active ui-state-active ui-corner-top" role="tab" id="ui-accordion-4-header-1" aria-controls="ui-accordion-1-panel-1" aria-selected="true" tabindex="0" data-toggle='collapse' data-target='#assessmentPotential' onclick='assessmentPotentialfun();'><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s" ></span> ASSESSMENT OF POTENTIAL(*) </h3>
													
													<div id="assessmentPotential" class="collapse">
														<div class="form-row">
															<div class="col-md-12">
																<table id='commofficeonly' class="table table-bordered table-striped " width="100%" border='1'>
																	<thead>
																		<tr><td >ASSESSMENT OF POTENTIAL</td><td>TICK</td><td>REMARKS</td></tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td>Suitable for accelerated promotion</td>
																			<td>
																				<select style='width:100px' class='form-control' id='offAss1' name='offAss1'>
																					<option value='yes'>YES</option>
																					<option value='no'>No</option>
																				</select>
																			</td>
																			<td>
																				<textarea id='offiAPPreason1' class='form-control' name='offiAPPreason1'></textarea>
																			</td>
																			
																		</tr>
																		<tr>
																			<td style='width:40%;'>Suitable for promotion in the normal seniority order</td>
																			<td>
																				<select style='width:100px' class='form-control' id='offAss2' name='offAss2'>
																					<option value='yes'>YES</option>
																					<option value='no'>No</option>
																				</select>
																			</td>
																			<td>
																				<textarea id='offiAPPreason2' name='offiAPPreason2' class='form-control'></textarea>
																			</td>
																			
																		</tr>
																		<tr>
																			<td style='width:40%;'>Not ready for promotion</td>
																			<td>
																				<select style='width:100px' id='offAss3' name='offAss3' class='form-control'>
																					<option value='yes'>YES</option>
																					<option value='no'>No</option>
																				</select>
																			</td>
																			<td><textarea class='form-control' id='offiAPPreason3' name='offiAPPreason3'></textarea></td>
																			
																		</tr>
																		<tr>
																			<td style='width:40%;'>Unlikey to achieve further development</td>
																			<td>
																				<select style='width:100px' id='offAss4' name='offAss4' class='form-control'>
																					<option value='yes'>YES</option>
																					<option value='no'>No</option>
																				</select>
																			</td>
																			<td><textarea class='form-control' id='offiAPPreason4' name='offiAPPreason4'></textarea></td>
																			
																		</tr>
																	</tbody>
																</table>
															</div>
														</div>
													</br>
													
													<div class="form-row" id='empDetailsSave2'>
														<div style="float:right;display:inline-block;">
															<button class="btn btn-primary" type="button" id='saveDetails2' name='saveDetails2' value='Save2' onclick='detailSave2();'>Save</button>
																			&nbsp;&nbsp;&nbsp;
														</div>	
													</div>
													</div>
													
												</div>
											</div>
										</div>
									</div>	<!-- tab4 End -->
									
									<div id="occasion" > <!-- tab 5 -->
										<div class="accordion accordion-transparent"> 
										
											<div class="block">
												<div class="header">
													
													<h3 onclick='ocasionForreptfun();' style='background: #65a6ff;' class="content-box-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-state-hover ui-accordion-header-active ui-state-active ui-corner-top" role="tab" id="ui-accordion-5-header-0" aria-controls="ui-accordion-1-panel-1" aria-selected="true" tabindex="0" data-toggle='collapse' data-target='#OccasionReport' ><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span> OCCASION FOR REPORT: Signing-off due to (*) </h3>
													
													  
													<div id="OccasionReport" class="collapse">
														<div class="form-row">
															<div class="col-md-12">
															
															
																<table id='commentbyapp' class="table table-bordered table-striped " width="100%" border='1'>
																	<thead>
																		<tr><td colspan='2'>COMMENTS BY APPRAISEE</td></tr>
																		<tr><td colspan='2' style='text-align:left;'><label>1.Self Appraisal - </label></td></tr>
																		<tr><td colspan='2' style='text-align:left;'><label>2.Training Needs - </label></td></tr>
																		<tr><td colspan='2' style='text-align:left;'><label>2.Any Other Comments </label></td></tr>
																	</thead>
																	<tbody>
																		<tr><TD>NAME (IN CAPITAL) AND SIGNATURE OF THE REPORTING OFFICER</TD><TD>NAME (IN CAPITAL) AND MASTER'S SIGNATURE</TD></tr>
																		<tr><td><br><br></td> <td><br><br></td></tr>
																	</tbody>
																</table>
																
																
															</div>
														</div>
														
														</br>
														
														<div class="form-row" id='trainingsave'>
															<div style="float:right;display:inline-block;">
																<button class="btn btn-primary" type="button" id='trainingave' name='trainingave' value='Save' onclick='trainingSave();'>Save</button>
																				&nbsp;&nbsp;&nbsp;
															</div>	
														</div>	
													</div>
												
													</br>
													
													<h3 style='background: #65a6ff;' class="content-box-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-state-hover ui-accordion-header-active ui-state-active ui-corner-top" role="tab" id="ui-accordion-5-header-1" aria-controls="ui-accordion-1-panel-1" aria-selected="true" tabindex="0" data-toggle='collapse' data-target='#forofficeuse' onclick='officeusefun();'><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s" ></span> FOR OFFICE USE ONLY (*) </h3>
												
													<div id="forofficeuse" class="collapse">
														<div class="form-row">
															<div class="col-md-12">
																<table id='commofficeonly' class="table table-bordered table-striped " width="100%" border='1'>
																	<thead>
																		<tr><td><strong>SIGHTED BY : SAAFETY DEPARTMENT</strong></td>
																			<td><strong>Date</strong><div class="input-group"><div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
																						<input type="text" maxlength="10" style="width:110px;" class="bootstrap-datepicker form-control"  id="safdeptdate" name="safdeptdate" value=""/></div> 
																			</td>
																			<td><strong>Operations Dept</strong></td>
																			<td><strong>Date</strong><div class="input-group"><div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
																						<input type="text" maxlength="10" style="width:110px;" class="bootstrap-datepicker form-control"  id="opdeptdate" name="opdeptdate" value=""/></div> 
																			</td>
																		</tr>
																		<tr>
																			<td colspan='2'><textarea id='sadeptremar' name='sadeptremar' class='form-control'> </textarea></td>
																			<td colspan='2'><textarea id='opdeptremar' name='opdeptremar' class='form-control'> </textarea></td>
																		</tr>
																		
																		<tr><td><strong>SIGHTED BY : Technical DEPARTMENT</strong></td>
																			<td> Date <div class="input-group"><div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
																						<input type="text" maxlength="10" style="width:110px;" class="bootstrap-datepicker form-control"  id="tefdeptdate" name="tefdeptdate" value=""/></div> 
																			</td>
																			<td><strong>Personnel Dept</strong></td>
																			<td><strong> Date </strong><div class="input-group"><div class="input-group-addon"><span class="icon-calendar-empty"></span></div>
																						<input type="text" maxlength="10" style="width:110px;" class="bootstrap-datepicker form-control"  id="pedeptdate" name="pedeptdate" value=""/></div> 
																			</td>
																		</tr>
																		<tr>
																			<td colspan='2'><textarea id='tedeptremar' name='tedeptremar' class='form-control'> </textarea></td>
																			<td colspan='2'><textarea id='pedeptremar' name='pedeptremar' class='form-control'> </textarea></td>
																		</tr>
																		
																		
																	</thead>
																</table>	
															</div>
														</div>
														</br>
														
														<div class="form-row" id='ocsave'>
															<div style="float:right;display:inline-block;">
																<button class="btn btn-primary" type="button" id='occsave' name='occsave' value='Save' onclick='occasionSave();'>Save</button>
																				&nbsp;&nbsp;&nbsp;
															</div>	
														</div>
													</div>
													
												</div>
											</div>
										</div>
									</div>	<!-- tab5 End -->
									
								</div>
							
								</div>
						</div>
					</form>
				</div>	
				
			</div><!-- page box -->
			</div><!-- page content -->	
		</div>
		
<script type='text/javascript'>

	$(document).ready(function(){
		var ecod = $("#ecode").val();

		$.ajax({
				url:"Appraisal.php",
				data:"action=getInitAppraisal&ecode="+ecod,
				type:"post",				  
				success:function(output)
				{
					var data = output.split("#");
					$('#comdate').val($.trim(data[0]));
					$('#name').val(data[1]);
					$('#mt').val(data[2]);
					$('#dob').val(data[3]);
					$('#jobtitle').val(data[4]);
					$('#fromd').val(data[5]);
					$('#tod').val('');
					//$('#dept').val(data[7]); 
					$('#dept').val(''); 
					/*alert(output);*/
				}
		});
	});

	function occasionfun()
	{
		//alert('occasionfun()');
		$('#ui-accordion-5-header-0').trigger('click');
		
		//occasionfun()	
	}
	
	function detailfun()
	{
		$('#ui-accordion-4-header-0').trigger('click');
	}

	function AppraisalAssment()
	{
		var empcode = $('#ecode').val();
		var dept = $('#dept').val();
		$.ajax({
				url:"Appraisal.php",
				data:"action=AppraisalAssment&empcode="+empcode+"&dept="+dept,
				type:"post",				  
				success:function(output)
				{
					//alert("Employment Code :"+empcode+ " Is Update successfully!!!!!!!");
					output1 = $.trim(output);
					//alert(output1);
					$('#Appraisalqueition').html(output1);
					
				}
		});
	}
</script> 
	
<?php
	include_once('bottom.php');
	} else {
?>
<div class="container">
	<br/><br/><h3>Error : Employee does not exists! Please input correct employee details! <a href="<?php echo "../".$_SERVER["PHP_SELF"]; ?>" style="color:BLUE;">CLICK HERE</a> to retry!</h3>
</div>
<?php
	}
} else {
?>
 <div class="container">	
		<form id="demo-form" class="form-horizontal" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<div class='row'>
				<div class='form-group'>
					<div class="content-box tabs" style="padding:20px;"> 

						<h3 class="content-box-header bg-blue-alt"><span>Employment Application Form</span></h3>
						<!-- <input class="form-control" id='myempcode' name='myempcode' value='' placeholder='address line 1'></input> -->
						<br/>
						
					<div class="form-group">
						<label for="" class="col-sm-2 control-label">Employment Code<span class="required">*</span></label>
						
						<div class="col-sm-8">
							<div class="col-sm-4">
								<input type="text" placeholder='' style="width:100%" class="form-control" id='myempcode' name='myempcode' value=""/>
							</div>
							<div class="col-sm-4">
								<button class="btn btn-primary" type="submit" id='btnMyEcode' name='btnMyEcode' value='submit' onclick=''>Submit</button>
							</div>
						</div>
					</div>
						
					</div>
				</div>
			</div>
		</form> 
</div>
<script type="text/javascript" src="js1/jquery.js"></script>		
<script type="text/javascript" src="js1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script>

<script type='text/javascript'>
$(document).ready(function(){ 
	//$("#myempcode").autocomplete("/lmcsAza3/autocompletesearch.php", {
	$("#myempcode").autocomplete("AppraisalAutoCom.php", { selectFirst: true });
	$("#myempcode").blur(function(){
		var tlen = (($(this).val()).split(":")).length;
		if( tlen < 2 )
		{
			$(this).val("");
		}
	});
}); 
</script>

<?php
}
?>
</body>