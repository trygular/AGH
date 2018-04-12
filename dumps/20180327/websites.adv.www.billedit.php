<?
	session_start();
	header("Cache-control: private");
	include("/etc/tbdconfig.php");
	mysql_connect($mysql_erp_host,$mysql_erp_user,$mysql_erp_password);
	$userrow=connectandlogin("");
	//echo $userrow[0];
	
	if($_POST['Action']=="calulategst")
	{
		$qryacnostate = "select * from advtmasters.AccountMaster where acno = '".$_POST['accountno']."'";
		$resacnostate = exequery($qryacnostate);
		$rowacnostate = fetch($resacnostate);
		if($rowacnostate[20]!="AGENT")
		$rowacnostate[19]=0;
	
	 
	
	
	
		$qryacnogst = "select * from advt20172018.GstMaster where state = '".$rowacnostate[29]."'";
		$resacnogst = exequery($qryacnogst);
		$rowacnogst = fetch($resacnogst);
		
		echo $rowacnogst[2].":".$rowacnogst[3].":".$rowacnogst[4].":".$rowacnostate[19];
		
		die();
	}
	
	if($_POST['Action']=="splitbilldata")
	{
		$splitdata = $_POST['datasplit'];
		
		$data = explode(';',$splitdata);
		//echo $data[0];
		$qrydelete = "delete from SplitBill where billno='".$_POST['billno']."'";
		exequery($qrydelete);
		//echo $qrydelete;
			
		for($i=0;$i<=sizeof($data); $i++)
		{
			$datadisp = explode('##',$data[$i]);
			if($datadisp[0]!="")
			{
			$qryinsertsplit = "insert into SplitBill values('".$_POST['billno']."','".$datadisp[0]."','".$datadisp[1]."','".$datadisp[2]."','0','0','0')";
			exequery($qryinsertsplit);
			//echo $qryinsertsplit;
			}
			
			
		}
		echo "Added Successfully";
		die();
	}
	
?>
<script src='/common.js'></script>
	<script src="jquery.min.js"></script>   
	<!--code for datepicker-->
	<link rel="stylesheet" href="/css/redmond/jquery-ui.css"/>
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/jquery-ui.js"></script>                
	<script type="text/javascript" src="/js/jquery.timepicker.js"></script>
<?
$userrow = connectandlogin("");
if($_POST['Action']=="Update")
{
	echo "Updating";
	
	exequery("insert into DeletedBillEntries (select * from BillMaster where billno='".$_POST['billno']."')");
	
	$totalgross = ($_POST['amount']+$_POST['commissionamt']);
	
	$qryupdate = "update BillMaster set acno ='".$_POST['accountno']."',client = '".$_POST['client']."',rate='".$_POST['rate']."',grossbill = '".$totalgross."',negcharges = '".$_POST['cgst']."',othcharges = '".$_POST['sgst']."',extracharges = '".$_POST['igst']."',netbill = '".$_POST['totaldisp']."',commn='".$_POST['commissionamt']."' where billno='".$_POST['billno']."'";
	exequery($qryupdate);
	
	$finalrate=256*$_POST['cms']+$_POST['cols'];
	$qryupdate = "update NewScheduleMaster1 set agent ='".$_POST['accountno']."' where ourno='".$_POST['ourno']."'";
	exequery($qryupdate);
	if($_POST['chktype']==1)
	{	
	   
	$qryupdate = "update ScheduleMaster7 set Adrate ='".$_POST['rate']."',adsizewidth='".$_POST['cms']."',AdSize='".$finalrate."',adsizeheight='".$_POST['cols']."',twidth='',theight='',Budget='0' where Schdid='".$_POST['ourno']."' and pubdate='".DMYtoYMD($_POST['billdate'])."'";
	}
	else
	{

		$qryupdate = "update ScheduleMaster7 set Adrate ='0',adsizewidth='".$_POST['cms']."',AdSize='".$finalrate."',Adrate=0,adsizeheight='".$_POST['cols']."',twidth='',theight='',Budget='".$_POST['rate']."' where Schdid='".$_POST['ourno']."' and pubdate='".DMYtoYMD($_POST['billdate'])."'";
		}
	//echo $qryupdate ;
	exequery($qryupdate);
	
	echo "<font color='green' size='8'><br><br><br><br><br><center>Bill Updated</font><br><br><br><br><br>";
	echo "<a href='billedit.php'><font color='red' size='4'>Click here to main screen</font></a>";
	die();
}
?>


<HTML>
<HEAD>
<LINK href='/Menu/global.css' rel=stylesheet type=text/css>
<title>BILL EDIT</TITLE>
</HEAD>
<body class='bkcolor'>
<p>

<?
if($_POST['Action']=="Search")
{
?>
<form method="POST" action="billedit.php">
<center><table width='100%' border='1'>
					
					<?
							
							$qry="select * from BillMaster where billno='".$_POST['billno']."'";
							$resqry=exequery($qry);
							$rowqry=fetch($resqry);
							$ourno =$rowqry[2];
							$acno =$rowqry[3];
							if($rowqry==NULL)
							{
								echo "<font color='red' size=8><br><br><br><br><br><center>Bill No does not exist</center>";
								die();
								
							}
							 
							$qry1="select * from ReceiptMaster2 where debitorbillno='".$_POST['billno']."' and amount!=0";
							$resqry1=exequery($qry1);
							$rowqry1=fetch($resqry1);
							if($rowqry1!=NULL)
							{
								echo "<font color='red' size=8><br><br><br><br><br><center>Receipt Already Removed so cant edit bill</center>";
								die();
								
							}
							
							$qry1="select * from CreditMaster2 where debitorbillno='".$_POST['billno']."' and amount!=0 ";
							$resqry1=exequery($qry1);
							$rowqry1=fetch($resqry1);
							if($rowqry1!=NULL)
							{
								echo "<font color='red' size=8><br><br><br><br><br><center>Credit Already Removed so cant edit bill</center>";
								die();
								
							}
							
						?>
								<tr>
								<td style='text-align:center' colspan='3'>TARUN BHARAT DAILY PVT. LTD.<br>
								H.O 3524,Narvekar Street, Belgaum. Phone 437333 4 lines,<br>
								Fax 0831-428603 P.O Box:181 Email: webmasters@tarunbharat.com</td>
								</tr>
					

						<?						
							
							$qry1="select * from advtmasters.AccountMaster where acno='".$rowqry[3]."'";
							$resqry1=exequery($qry1);
							$rowqry1=fetch($resqry1);
							
							if($rowqry1[20]!="AGENT" && $rowqry1[0]!='G1110' && $rowqry1[0]!='g5' && $rowqry1[0]!='g1111')
							{
								$rowqry1[19]=0;
								$rowqry[12]=0;
							}
							 
							
							if($rowqry[16]==0 || $rowqry[16]==1 )
							{
									$qrydetails="SELECT date_format(b.date,'%d-%m-%Y'),d.descpn,c.descpn,e.descpn,a.colcm,r.rate,p.descpn,c.displaycode,d.pagecode,e.editioncode,p.positioncode,a.keyno,a.ptname,a.material/(128*256*256*256),a.advttype from ScheduleMaster1 a,ScheduleMaster2 b,DisplayMaster c,PageMaster d,EditionMaster e,RateMaster r,PositionMaster p WHERE a.ourno='".$rowqry[2]."' and b.billno='".$rowqry[0]."' and a.advttype=c.displaycode and b.ourno=a.ourno and d.pagecode=b.page and e.editioncode=b.edition and p.positioncode=b.position and r.page=b.page and r.display=a.advttype and r.edition=b.edition and r.position=b.position";
									$resqrydetails=exequery($qrydetails);
									$rowqrydetails=fetch($resqrydetails);
									
									if($rowqrydetails[13]==1)
									$color="COLOR";
									else
									$color="BLACK & WHITE";
									
									$advttype=$rowqrydetails[14];
						//	echo "0".$qrydetails;
							}
							if($rowqry[16]=="2a")
							{
							   $qrydetails="SELECT date_format(b.date,'%d-%m-%Y'),d.descpn,c.descpn,e.descpn,a.colcm,p.descpn,c.displaycode,d.pagecode,e.editioncode,p.positioncode,b.contractrate,b.budget,a.keyno,a.billclient,a.contractno,a.material/(128*256*256*256),a.advttype from SchagencyconMaster1 a,SchagencyconMaster2 b,DisplayMaster c,PageMaster d,EditionMaster e,PositionMaster p WHERE a.ourno='".$rowqry[2]."'and b.billno='".$rowqry[0]."' and a.advttype=c.displaycode and b.ourno=a.ourno and d.pagecode=b.page and e.editioncode=b.edition and p.positioncode=b.position;";
							   $resqrydetails=exequery($qrydetails);
							   $rowqrydetails=fetch($resqrydetails);
							   if($rowqrydetails[15]==1)
									$color="COLOR";
									else
									$color="BLACK & WHITE";
									
									$advttype=$rowqrydetails[16];
							//echo "1".$qrydetails;
							}
							if($rowqry[16]=="2c")
							{
								$qrydetails="SELECT date_format(b.date,'%d-%m-%Y'),d.descpn,c.descpn,e.descpn,a.colcm,p.descpn,c.displaycode,d.pagecode,e.editioncode,p.positioncode,b.contractrate,b.budget,a1.name,b.agency,a.keyno,a.billclient,a.contractno,a.material/(128*256*256*256),a.advttype from SchclientconMaster1 a,SchclientconMaster2 b,DisplayMaster c,PageMaster d,EditionMaster e,PositionMaster p,AgentMaster1 a1 WHERE a.ourno='".$rowqry[2]."' and b.billno='".$rowqry[0]."' and a.advttype=c.displaycode and b.ourno=a.ourno and d.pagecode=b.page and e.editioncode=b.edition and p.positioncode=b.position and a1.agentcode=b.agency";
								$resqrydetails=exequery($qrydetails);
							   $rowqrydetails=fetch($resqrydetails);
							   if($rowqrydetails[17]==1)
									$color="COLOR";
									else
									$color="BLACK & WHITE";
									
									$advttype=$rowqrydetails[18];
							//echo "2".$qrydetails;
							}
							
							if($rowqry[16]=="tc" ||$rowqry[16]=="tcs" )
							{
									 $qrydetails="SELECT date_format(b.date,'%d-%m-%Y'),d.descpn,c.descpn,e.descpn,a.colcm,p.descpn,c.displaycode,d.pagecode,e.editioncode,p.positioncode,b.rate,b.budget,a.keyno,a.billclient,a.material/(128*256*256*256),a.advttype from SchtmpconMaster1 a,SchtmpconMaster2 b,DisplayMaster c,PageMaster d,EditionMaster e,PositionMaster p WHERE a.ourno='".$rowqry[2]."'and b.billno='".$rowqry[0]."' and a.advttype=c.displaycode and b.ourno=a.ourno and d.pagecode=b.page and e.editioncode=b.edition and p.positioncode=b.position";
									 $resqrydetails=exequery($qrydetails);
									 $rowqrydetails=fetch($resqrydetails);
									 if($rowqrydetails[14]==1)
										$color="COLOR";
									 else
										$color="BLACK & WHITE";
									
									$advttype=$rowqrydetails[15];
					
					//echo "3".$qrydetails;
							}
							if($rowqry[16]=="s" ||$rowqry[16]=="s1" )
							{
									  $qrydetails="SELECT date_format(b.date,'%d-%m-%Y'),d.descpn,c.descpn,e.descpn,a.colcm,p.descpn,c.displaycode,d.pagecode,e.editioncode,p.positioncode,a.rate,a.budget,a.keyno,a.billclient,a.material/(128*256*256*256),a.advttype,p1.descpn,b.name from SupplimentschMaster a,SupplimentMaster1 b,DisplayMaster c,PageMaster d,EditionMaster e,PositionMaster p,PagesizeMaster p1 WHERE a.ourno='".$rowqry[2]."' and a.pagesize=p1.pagesizecode and a.billno='".$rowqry[0]."' and a.advttype=c.displaycode and b.suppcode=a.suppliment and d.pagecode=a.page and e.editioncode=b.edition and p.positioncode=a.position";
									  $resqrydetails=exequery($qrydetails);
									 $rowqrydetails=fetch($resqrydetails);
									 if($rowqrydetails[14]==1)
										$color="COLOR";
									 else
										$color="BLACK & WHITE";
									
									$advttype=$rowqrydetails[15];
									
							//echo "4".$qrydetails;
							}
							
							$edt=$rowqrydetails[8];
							echo "edition".$edt;
							//echo "4".$qrydetails;
							$ScheduleMaster7Sql = "SELECT * FROM ScheduleMaster7  WHERE Schdid ='".$rowqry[2]."' and pubdate='".$rowqry[1]."'";
							 
							$ScheduleMaster7Res = exequery($ScheduleMaster7Sql); 
							$ScheduleMaster7Row = fetch($ScheduleMaster7Res);
							if($ScheduleMaster7Row[12]!="")
							{
								$colsdisp = $ScheduleMaster7Row[12];
							}
							else
							{
								$colsdisp = $ScheduleMaster7Row[15];
							}
							
							if($ScheduleMaster7Row[11]!="")
							{
								$cmsdisp = $ScheduleMaster7Row[11];
							}
							else
							{
								$cmsdisp = $ScheduleMaster7Row[14];
							}
							$tempcol=$ScheduleMaster7Row[12];
							$tempclm=$ScheduleMaster7Row[11];
						
						
					?>
					<tr>
					   <td>BILL NO.: <input type='text' name='billno' id='billno' value='<?echo $rowqry[0]?>' readonly > </td>
					   <td>DATE.: <input type='text' name='billdate' id='billdate' value='<?echo YMDtoDMY($rowqry[1])?>' readonly />	 </td>
					   <td>OUR NO.: <input type='text' name='ourno' id='ourno' value='<?echo $rowqry[2]?>' readonly />	</td>
					</tr>
					<script>
						function accountcal()
						{
							accountno = $('#accountno').val();
							amount = $('#amount').val();
							billno = $('#billno').val();
							$.ajax({
								url: "billedit.php" ,
								data: "Action=calulategst&accountno="+accountno+"&billno="+billno,
								type : 'POST',
								success: function(output)
								{
								 //alert(output);
								  outputdata = output.split(':');
							
								  $('#commission').val(outputdata[3]);
								  
								  general();
								}
							});
						}
					</script>
					<tr>
					   <td colspan='2'>ACCOUNT NO.: <input type='text' name='accountno' id='accountno' value='<?echo $rowqry[3]?>' onchange = 'accountcal()'/>	 </td>					 
					   <?
						    if($rowqry[16]==0)	
							{		
								$type="STD";							
								$type1="STANDARD";
							}
							if($rowqry[16]==1)	
							{
								$type="SPL";
								$type1="SPLIT";
								//echo"<td>SPLIT</td>";
							}
							if($rowqry[16]=="tc")	
							{	
								$type="TMP";
								$type1="TEMPCONTRACT";
								
							}
							if($rowqry[16]=="tcs")	
							{	
								$type="SPLIT TMP";
								$type1="TEMPCONTRACT";
								//echo"<td>SPLIT TEMPCONTRACT</td>";
							}
							if($rowqry[16]=="tcs")	
							{		
								$type="SUP";
								$type1="SUPPLIMENT";
								//echo"<td>SUPPLIMENT</td>";
							}
							if($rowqry[16]=="tcs")	
							{	
								$type="SplitSUP";
								$type1="SplitSupp";
								//echo"<td>SplitSupp</td>";
							}
							echo"<td><input type='text' name='typedisp' id='typedisp' value='".$type1."' readonly />	</td>";
							

					?>
					</tr>
					<tr>
						<td colspan='3'>A/C NAME:<?echo $rowqry1[1]?></td>
					</tr>
					<tr>
						<td colspan='3'>ADDR 1: <?echo $rowqry1[11]?></td>
					</tr>
					<tr>
						<td colspan='3'>ADDR 2: <?echo $rowqry1[12]?></td>
					</tr>
					<tr>
						<td colspan='3'>R.O. NO./DATE: <? echo $rowqry[4] ?>
						 /<? echo YMDtoDMY($rowqry[5])?></td>
					</tr>
					<tr>
					   <?
					   
					   
					         
					   
					        if($rowqry[16]==0 || $rowqry[16]==1 )
							{
									$key=$rowqrydetails[11];
							
							}
							if($rowqry[16]=="2a")
							{
							   $key=$rowqrydetails[12];
							
							}
							if($rowqry[16]=="2c")
							{
								$key=$rowqrydetails[14];
							
							}
							
							if($rowqry[16]=="tc" ||$rowqry[16]=="tcs" )
							{
									$key=$rowqrydetails[12];
					
					
							}
							if($rowqry[16]=="s" ||$rowqry[16]=="s1" )
							{
									  $key=$rowqrydetails[12];
							
							}
					   
					   
					   ?>
						<td colspan='3'>Key No. :<? echo $key?></td>
					</tr>
					<tr>
						<td colspan='3'>CLIENT: <textarea  name='client' id='client' ><?echo $rowqry[20] ?></textarea>	 </td>
					</tr>
					</table>
					
					<table border='1' width='100%'>
						
						<?
						
								
						
						
						      if($rowqry[16]==1||$rowqry[16]=="s1"||$rowqry[16]=="tcs")
							   {
								

								 $spbills="SELECT * from SplitBill where billno='".$rowqry[0]."'";
						         $resspbills=exequery($spbills);
								 $rowspbills=fetch($resspbills);

								 $amount1=$rowspbills[3];
							   }
						
						
						//echo $rowqry[16];
						
						
						   $a=$rowqrydetails[4];
						   $al=$a%256;
						   $a=$a/256;
						   $ac=$a%256;
						
							 if($rowqry[16]=="0")
							  {
							 
								$rate=$rowqry[6];
								$amount=$rate*$al*$ac;
								//echo "222";
								
							  }
							  else
								if($rowqry[16]=="2a"||$rowqry[16]=="2c")
								{
								   $rate=$rowqrydetails[10];
								   $budget=$rowqrydetails[11];

								   if($rate>0.00)
									 $amount=$rate*$al*$ac;
								   else
									 $amount=$budget;
									 //echo "333";
								}

							   else
								 if($rowqry[16]=="tc")
								{
								   $rate=$rowqrydetails[10];
								   $budget=$rowqrydetails[11];

								   if($rate>0.00)
									 $amount=$rate*$al*$ac;
								   else
									 $amount=$budget;
									
									//echo "444";
								}
							  else
								 if($rowqry[16]=="tcs")
								{
								   $rate=$rowqrydetails[10];
								   $budget=$rowqrydetails[11];
							
								   
								   if($rate>0.00)
									 $amount=$rate*$al*$ac;
								   else
									 $amount=$budget;
									 //echo "555";
								}
							  else 
								if($rowqry[16]=="s1")
								{         
								 $rate=$rowqrydetails[10];
								   $budget=$rowqrydetails[11];

								
								   if($a>0)
								   {
								   if($rate>0.00)
									 $amount=$rate*$al*$ac;
								   else
									 $amount=$budget;
								   }
								   else
								   if($rate>0.00)
									 $amount=$rate;
									 else
									 $amount=$budget;
									// echo "666";
								}
								  
												
								//if($rowqry[16]=="s"||$rowqry[16]=="s1")
								 {
								 	 if($rowqry[16]=="s")
								{
								 $rate=$rowqrydetails[10];
								
								   $budget=$rowqrydetails[11];
								   if($a>0)
								   { 
								   if($rate>0.00)
									 $amount=$rate*$al*$ac;
								   else
									 $amount=$budget;
								  }
								  else
								   if($rate>0.00)
									 $amount=$rate;
									 else
									 $amount=$budget;
									 
									//echo "777";
								}
								
									
							
							$totalcolcms = $al*$ac;
							?>
							  
							<?
								
							   if($rate>0)
									{
									   echo"<td>".$rate."</td>";
									  
									 }
									 else
									 {
										 echo"<td>".$budget."</td>";
										
									 }
							
								echo"<td>$amount</td>";
								
							
							}
							//else
							{
							 $al=$colsdisp;
							 $ac=$cmsdisp;
							 
							 $totalcolcms = $al*$ac;
							?>
							<script>
								function calamount()
								{
									cols = $('#cols').val();
									cms = $('#cms').val();
									total = cols * cms;
									$('#totalcolcms').val(total);
									rate = $('#rate').val();
									amountdisp = parseFloat(cols)*parseFloat(cms)*parseFloat(rate);
									amountdisp=amountdisp.toFixed(2);
									$('#amount').val(amountdisp);
								}
								
								
								function general()
								{
									if($('#rdrate').is(":checked"))
									{
										//alert("rate");
										totalcolcms = $('#totalcolcms').val();
										rate = $('#rate').val();
										totalrate = totalcolcms * rate;
										//alert(totalrate);
										totalrate=totalrate.toFixed(2);
										$('#amount').val(totalrate);
										
										commission = $('#commission').val();	
										if(commission>0)
										{
											commamt = (parseFloat(totalrate)*parseFloat(commission))/100;
											$('#commissionamt').val(commamt.toFixed(2));
										}
										else
										commamt=0;										
										totalrate=parseFloat(totalrate)-parseFloat(commamt);											
										$('#amount').val(totalrate.toFixed(2));
										amount=parseFloat(totalrate);
									}
									else
									{
									//	alert("budget");
										rate = $('#rate').val();
										$('#amount').val(rate);
										amount=rate;
										totalrate=amount;
										commission = $('#commission').val();	
										if(commission>0)										
										{
											commamt = (parseFloat(amount)*parseFloat(commission))/100;
											$('#commissionamt').val(commamt.toFixed(2));
										}
										else
										commamt=0;
										totalrate=parseFloat(totalrate)-parseFloat(commamt);						
										$('#amount').val(totalrate.toFixed(2));
										amount=parseFloat(totalrate);
									}
									
									
									
									cgsttax = $('#cgsttax').val();
									cgstcal = (parseFloat(amount)*parseFloat(cgsttax))/100;
									$('#cgst').val(cgstcal.toFixed(2));
									
									sgsttax = $('#sgsttax').val();
									sgstcal = (parseFloat(amount)*parseFloat(sgsttax))/100;
									$('#sgst').val(sgstcal.toFixed(2));
									
									igsttax = $('#igsttax').val();
									igstcal = (parseFloat(amount)*parseFloat(igsttax))/100;
									$('#igst').val(igstcal.toFixed(2));
									
									totalnetamt = parseFloat(amount)+parseFloat(cgstcal)+parseFloat(sgstcal)+parseFloat(igstcal);
									 
									totalnetamt= Math.round(totalnetamt);
									
									$('#totaldisp').val(totalnetamt);
									$('#totaldispround').val((totalnetamt));
								}
								
								function splitamtcal()
								{
									totalsplitamt=0;
									cnt = $('#textcnt').val();
									//alert(cnt);
									for(j=0;j<cnt;j++)
									{
										splitamt = $('#splitamt'+j).val();
										//alert(splitamt);
										totalsplitamt = parseFloat(totalsplitamt)+parseFloat(splitamt);
									}
									$('#splittotal').val(totalsplitamt);
								}
								
								function addsplitrec()
								{
									
									cnt = $('#textcnt').val();
									billno = $('#billno').val();
									splittotal = $('#splittotal').val();
									totaldisp = $('#totaldisp').val();
									totaldispround = $('#totaldispround').val();
									//alert(billno);
									datasplit = "";
									for(i=0;i<cnt;i++)
									{
										splitbillno = $('#splitbillno'+i).val();
										splitclientname = $('#splitclientname'+i).val();
										splitamt = $('#splitamt'+i).val();
										datasplit = splitbillno+"##"+splitclientname+"##"+splitamt+";"+datasplit;
										//alert(datasplit);
									}
									if(splittotal!=totaldispround)
									{
										alert('Check Total Amount');
										die();
									}
									$.ajax({
									url: "billedit.php",
									data: "Action=splitbilldata&datasplit="+datasplit+"&billno="+billno,
									type : 'POST',
									success: function(output)
									{
										alert(output);
									}
									});
								}
							</script>
							<tr><td>COLS</td><td>CMS</td><td>TOTAL</td><td>RATE</td><td>AMOUNT</td></tr>
						<tr><td><input type='text' name='cols' id='cols' value='<?echo 	$tempcol ?>' onchange = 'calamount()'/>	</td>
						<td><input type='text' name='cms' id='cms' value='<?echo $tempclm?>' onchange = 'calamount()'/></td>
						<td><input type='text' name='totalcolcms' id='totalcolcms' value='<?echo $totalcolcms?>' readonly />	</td>
							<?
							
								if($ScheduleMaster7Row[3]>0)
								{
									$amt = $rowqry[6];
									$rdchk = "checked";
									$brdchk = " ";
								}
								else
								{
									$brdchk = "checked";
									$rdchk = "";
									$amt = $rowqry[6];
								}
									
								echo"<td><input type='text' name='rate' id='rate' value='".$amt."' onchange = 'general()'/><input type='radio' name='chktype' id='rdrate' value='1' $rdchk onclick = 'general()'/>Rate<input type='radio' name='chktype' id='rdbudget' value='2' onclick = 'general()' $brdchk/>Budget &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Commission <input type='text' name='commission' id='commission' value='".$rowqry1[19]."' size='5' readonly/>&nbsp;&nbsp;<input type='text' name='commissionamt' id='commissionamt' value='".$rowqry[12]."' size='5' readonly/></td>";
									   
								$amount=$rowqry[7];
								$amount = $rowqry[7]-$rowqry[12];
								echo"<td><input type='text' name='amount' id='amount' value='".$amount."' readonly/></td>";
								
							}
						?>
						
						</tr>
						
						
						<?						
								$comm=0;
								if($rowqry[12]>0)
								{								
									$comm=$rowqry[12];								
								}
								$netbill=$amount+$rowqry[9]+$rowqry[10]+$rowqry[11];
						
						
						
						$qrytax = "select state,gstno from advtmasters.AccountMaster where acno='".$acno."'";
						//echo $qrytax;
						$restax = exequery($qrytax);
						$rowtax = fetch($restax);
						
						
						$ScheduleMaster2Sql = "SELECT * FROM NewScheduleMaster2  WHERE ourno ='".$ourno."'  ";
						$ScheduleMaster2Res = exequery($ScheduleMaster2Sql); 
						$ScheduleMaster2Row = fetch($ScheduleMaster2Res);
						
					
						$qrygst1 = "select * from EditionMaster where editioncode = '".$ScheduleMaster2Row[1]."'";
						$resgst1 = exequery($qrygst1);
						$rowgst1 = fetch($resgst1);
						 
						 
						
						$qrytempss =  "select * from EditionMaster where editioncode='".$ScheduleMaster2Row[1]."' ";
						$restempss = exequery($qrytempss);
						$rowtempss = fetch($restempss);
						if($rowtempss!=null)
						{
							if($rowtax[0]==$rowtempss[2])
							{
										$qrystate = "SELECT * FROM GstMaster  where state='".$rowtax[0]."' limit 1" ; 
										//	echo 								$qrystate;	
										$resstate = exequery($qrystate);
										$rowstate = fetch($resstate);
										if($rowstate==NULL)
										{
										$cgst=0;
										$sgst=0;
										$igst=0;

										$cgstamt = 0;
										$sgstamt = 0;
										$igstamt = 0;

										}
										else
										{
										$cgst=$rowstate[2];
										$sgst=$rowstate[3];
										$igst=$rowstate[4];

									 

										}

							}
							else
							{
										$cgst=0;
										$sgst=0;
										$igst=5;

										 

							}												
							
							
							
							
						}
						
						
						
						
						
						
						?>
						
						<tr><td colspan='3' rowspan='5'><lable for='remark'>Remark</lable><textarea name='remark' id='remark'></textarea></td><td>	CGST <input type='text' size='2' name='cgsttax' id='cgsttax' value='<? echo $cgst?>' readonly /> %</td><td><input type='text' name='cgst' id='cgst' value='<?echo $rowqry[9]?>' readonly /> </td></tr>
						<tr><td>SGST <input type='text' size='2' name='sgsttax' id='sgsttax' value='<? echo $sgst?>' readonly /> %</td><td> <input type='text' name='sgst' id='sgst' value='<?echo $rowqry[10]?>' readonly /> </td></tr>
						<tr><td>IGST <input type='text' size='2' name='igsttax' id='igsttax' value='<? echo $igst?>' readonly /> %</td><td> <input type='text' name='igst' id='igst' value='<?echo $rowqry[11]?>' readonly />  </td></tr>
						<tr><td>TOTAL</td><td><input type='hidden' name='totaldisp' id='totaldisp' value='<?echo $netbill?>' readonly /><input type='text' name='totaldispround' id='totaldispround' value='<?echo round($netbill)?>' readonly /></td></tr>
						
						<tr><td>Page No. <input type='text' name='pageno' id='pageno' value='<?echo $rowqry[15]?>' readonly /></td><td colspan='4'><input type="submit" name="Action" id="Action" value="Update"  ></td></tr>
						
									
					</table>
					</br></br>
					<table style='width:75%'>
					<tr><th colspan='3'>Split Bill Details</th></tr>
					<tr><th>Split Bill No.</th><th>Client</th><th>Split Amount</th></tr>
					
					<?
						$i=0;
						$totalsplit=0;
						$qrybillsplit = "select * from SplitBill where billno='".$_POST['billno']."'";
						$resbillsplit = exequery($qrybillsplit);
						while($rowbillsplit = fetch($resbillsplit))
						{
							echo "<tr><td><input type='hidden' name='splitbillno".$i."' id='splitbillno".$i."' value='".$rowbillsplit[1]."'/>".$rowbillsplit[1]."</td><td><input type='text' name='splitclientname".$i."' id='splitclientname".$i."' value='".$rowbillsplit[2]."' size='100'/></td><td  style='text-align:right'><input  style='text-align:right' type='text' name='splitamt".$i."' id='splitamt".$i."' value='".$rowbillsplit[3]."' onchange='splitamtcal();'/></td></tr>";
							$totalsplit = $totalsplit+$rowbillsplit[3];
							$i++;
						}
					?>
					<input type='hidden' name='textcnt' id='textcnt' value='<? echo $i ?>'/>
					<tr><th colspan=2 style='text-align:right'>Total</th><th style='text-align:right'><input style='text-align:right' type='text' name='splittotal' id='splittotal' value='<? echo $totalsplit?>' readonly /></th></tr>
					<tr><th colspan=3><input TYPE="button" NAME="Action"  VALUE="Add" onclick='addsplitrec()'></th></tr>
					
					</table>
						
					<script>
					general();
					</script>	
					
				</center>

<?
		
		die();
	}
?>

<form method="POST" action="billedit.php">
<center>
<table>

Year : <? echo getyearstr($userrow); ?>
<tr>
	<th>
BILL EDIT
	</th>
</tr>
<tr>
<tr><td> 
</font><p>Enter the Bill No.</font>
<INPUT TYPE="TEXT" NAME="billno"></FONT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input TYPE="SUBMIT" NAME="Action"  VALUE="Search"></P>
</td></tr></table>
</form>
</body>
</HTML>
