<?
    session_start();
	include("configure.php");
	mysql_connect($mysql_erp_host,$mysql_erp_user,$mysql_erp_password);	
	//$userrow=connectandlogin("");
	usedb("elokmany_SmartChat");

	$userinfoSql = "select * from userinfo where  loginsessionid='".session_id()."'";
	$userinfoRes = exequery($userinfoSql);
	$userinfoRow = fetch($userinfoRes);

	$UserMasterSql = "select * from UserMaster where Id='".$userinfoRow[1]."'";
	$UserMasterRes = exequery($UserMasterSql);
	$UserMasterRow = fetch($UserMasterRes);
	$name=$UserMasterRow[8].":".$UserMasterRow[1];
?>

<?	
if($_POST['Action']=="getbranchdetails")
	{
		
		$branchq="Select * FROM `Lokmanya-Empire`.BranchMaster WHERE regioncode='".$_POST['branchdetails']."' ORDER BY branchname ";
		$resbranch = exequery($branchq);
		while($rowbranch = fetch($resbranch))
		{
			echo $rowbranch[0].":".$rowbranch[1].";";
		}
			
		
		
		die();
	}

//-------------------------------------------------------------------------------------------------------------------------------------------------//	
	
if($_POST['Action']=="getquestiondetails")
	{
		
		$q="Select * FROM RetentionQuestion WHERE questiontype='".$_POST['question']."' ORDER BY questiontype ";
		$resq = exequery($q);
		while($rowq = fetch($resq))
		{
			echo $rowq[0].":".$rowq[1].";";
		}
			
		
		
		die();
	}
	
	
//-------------------------------------------------------------------------------------------------------------------------------------------------//	
	
?>	

<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<table border='0'><tr><td><img src="lok.jpg" height='6%' width='8%' style='float:center;margin-top:1%;'></td></tr></table>
<br><br>
<?
include("menubar.php");
?>
<hr style="height:5px;width:100%;border:none;color:#428bca;background-color:#428bca;"/>
<br><br>

<link rel="shortcut icon" href="images/meta.png">
<link rel="stylesheet" href="style.css" />
<script type="text/javascript" src="/js/jquery.js"></script>		
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<link rel="stylesheet" href="/css/redmond/jquery-ui.css"/>
<script type="text/javascript" src="jquery.form.js"></script>
<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script>
<link media="print" rel="Alternate">	



<?
  if($_POST['Action']=='Generate')
  {
?>	  
<input class='btn btn-danger' type='button' id='back' name='back' value='Back' style='float:right;margin-right:20px' onclick="window.location='GraphReport1.php'">

	  
<script src="../../code/highcharts.js"></script>
<script src="../../code/modules/exporting.js"></script>
<?       
//if($_POST['question']=='ALL')
{
		?>	
		<center><h5 class="btn btn-success">Fromdate : <? echo $_POST['frmdate'];?> Todate : <? echo $_POST['todate'];?></h5></center>	
		<?
					
	/* 					$qry = "select * from RetentionQuestionaire1";
						$res1 = exequery($qry);
						while($rows = fetch($res1))
						{
							$staffqry = "SELECT * FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode=S.Branchid ";
							if($_POST['region']!="ALL" && $_POST['region']!=' ')
							$staffqry.=" and B.regioncode='".$_POST['region']."'";
									
						
						    //echo $staffqry;
							$resstaff=exequery($staffqry);
							while($rowstaff=fetch($resstaff))
							{
								
								if($rows[1]==$rowstaff[0])
								{
									
									
									$quest="select * from RetentionQuestion";
									$resquest=exequery($quest);
									while($rowquest=fetch($resquest))
									{
										$quest1="select * from RetentionQuestionaire2 where question='".$rowquest[0]."'";
									    $resquest1=exequery($quest1);
										while($rowquest1=fetch($resquest1))
										{
											$insert="insert into tempquestion values('".$rowquest[0]."','".$rowquest1[4]."')";
											exequery($insert);
										}
									}

									
									
									
									
								}
								
							}
					
						} */
						
	/* 	 $region="select * from `Lokmanya-Empire`.RegionMaster where regioncode='".$_POST['region']."'";
		 $rowregion=exequery($region);
		 $resrowregion=fetch($rowregion);	
			
		 $quest="select * from RetentionQuestion where questiontype!=9 and questiontype!=10";
		 $resquest = exequery($quest);
		 while($rowquest = fetch($resquest))
		 {
			$qry="SELECT question , count(case when remark='1' then remark end) as 'first' ,count(case when remark='2' then remark end) as 'second', count(case when remark='3' then remark end) as 'third' from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."' and R2.questiontype!=9 and R2.questiontype!=10 and R2.question='".$rowquest[0]."' and R2.empcode in(SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode=S.Branchid";
			if($_POST['region']!="ALL")
			$qry.=" and B.regioncode='".$_POST['region']."'";
			$qry.=")";
			$resqry=exequery($qry);

			while($rowqry=fetch($resqry))
			{
				if($rowqry[1]==0&&$rowqry[2]==0&&$rowqry[3]==0)	
				{
					?>
					<script>
					alert('No Results Found');
					window.location="GraphReport1.php";
					</script>
					<?
				}	
			}
		 }*/
				?> 
						

						<div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>



<script type="text/javascript">
/* Highcharts.chart('container1', {
    chart: {
        type: 'column'
    },
    title: {
        text: '<? echo $resrowregion[1] ?>'
    },
    xAxis: {
        categories: [
		<?
		 $quest="select * from RetentionQuestion where questiontype!=9 and questiontype!=10 ";
		 $resquest = exequery($quest);
		 while($rowquest = fetch($resquest))
		 {
		
			 echo $rowquest[0].",";
		 }
		
		?>
		
          
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Users'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    }, 
    series: [{
        name: 'Strongly Disagree',
        data: [
		
			<?
		 $quest="select * from RetentionQuestion where questiontype!=9 and questiontype!=10";
		 $resquest = exequery($quest);
		 while($rowquest = fetch($resquest))
		 {
		
		      $remark1 = "SELECT question , count(case when remark='1' then remark end) as 'first' from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."' and R2.questiontype!=9 and R2.questiontype!=10 and R2.question='".$rowquest[0]."' and R2.empcode in(SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode=S.Branchid ";
			  if($_POST['region']!="ALL")
			  $remark1.=" and B.regioncode='".$_POST['region']."'";
			  $remark1.=")";
			  $resremark1= exequery($remark1);
			  while($rowremark1= fetch($resremark1))
			  {
				  echo $rowremark1[1].",";
			  }
			 
		 }
		
		?>
		
		]

    }, {
        name: 'No Opinion',
        data: [	
		
		<?
		 $quest="select * from RetentionQuestion where questiontype!=9 and questiontype!=10";
		 $resquest = exequery($quest);
		 while($rowquest = fetch($resquest))
		 {
		
		      $remark1 = "SELECT question , count(case when remark='2' then remark end) as 'second' from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."' and R2.questiontype!=9 and R2.questiontype!=10 and R2.question='".$rowquest[0]."' and R2.empcode in(SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode=S.Branchid ";
			  if($_POST['region']!="ALL")
			  $remark1.=" and B.regioncode='".$_POST['region']."'";
			  $remark1.=")";
			  $resremark1= exequery($remark1);
			  while($rowremark1= fetch($resremark1))
			  {
				  echo $rowremark1[1].",";
			  }
			 
		 }
		
		?>
	]
    }, {
        name: 'Strongly Agree',
        data: [	
		<?
		 $quest="select * from RetentionQuestion where questiontype!=9 and questiontype!=10 ";
		 $resquest = exequery($quest);
		 while($rowquest = fetch($resquest))
		 {
		
		      $remark1 = "SELECT question , count(case when remark='3' then remark end) as 'third' from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."' and R2.questiontype!=9 and R2.questiontype!=10 and R2.question='".$rowquest[0]."' and R2.empcode in(SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode=S.Branchid ";
			  if($_POST['region']!="ALL")
			  $remark1.=" and B.regioncode='".$_POST['region']."'";
			  $remark1.=")";
			  $resremark1= exequery($remark1);
			  while($rowremark1= fetch($resremark1))
			  {
				  echo $rowremark1[1].",";
			  }
			 
		 }
		
		?>
		]
    }]
}); */
		</script>
		<br><br><br>
		<style>
			table {
				width: 716px; 
				border-spacing: 0;
			}

			tbody, thead tr { display: block; }

			table.scroll tbody {
				height: 400px;
				overflow-y: auto;
				overflow-x: hidden;
			}

			
		
        </style>
	
		<script>
		function getdetails(id)
		{
			//alert(id);
			id1=id-1;
			$('.propty'+id1).slideToggle();
			$('.propty'+id).slideToggle();

		}
		$(document).ready(function () 
		{
			//alert('in');
			$('table .silent').hide();
		})
		</script>
		

<?		
//----------------------------------------------------------        S U M M A R Y         --------------------------------------------------------//
?>		 

						<input type='text' id='fromdate' name='fromdate' value='<?echo $_POST['frmdate'];?>'/>
						<input type='text' id='todate' name='todate' value='<?echo $_POST['todate'];?>'/>
						<input type='text' id='empcode' name='empcode' value='<?echo $_POST['empcode'];?>'/>
						<input type='text' id='region' name='region' value='<?echo $_POST['region'];?>'/>
						<input type='text' id='branch' name='branch' value='<?echo $_POST['branch'];?>'/>
						<input type='text' id='designation' name='designation' value='<?echo $_POST['designation'];?>'/>
						
					
		<center>
		<table border='0' width='85%' class='table scroll'>
		<thead><tr><th style='text-align:center;width:100px'>Question ID</th><th style='width:1000px'>Question</th><th style='text-align:center;width:100px'>Strongly Disagree</th><th style='text-align:center;width:100px'>No Opinion</th><th style='text-align:center;width:100px'>Strongly Agree</th><th style='text-align:center;width:100px'>Not Attempted</th><th style='text-align:center;width:100px'>Total</th></tr></thead>
		<tr><td colspan=5><hr></td></tr>
		<?
			$remark1=0;
			$remark2=0;
			$remark3=0;
			$notatt=0;
			$allusers=0;
			
			$qry="select * from RetentionQuestionType  where id!=9 and id!=10";
			if($_POST['question']!='ALL')
			{
				$qry.=" and questiontype='".$_POST['question']."'";
			}
			$resqry=exequery($qry);
			while($rowqry=fetch($resqry))
			{
				echo"<tr>
				<th colspan=7 style='text-align:left;background-color:#f1b25b;cursor:pointer;margin-right:250px'>$rowqry[1]</th>													
				</tr>";
				
			
			
				$qryques="select * from RetentionQuestion where questiontype='".$rowqry[0]."'";
				
				if($_POST['subquestion']!='ALL' && $_POST['subquestion']!='')
				{
					$qryques.=" and question='".$_POST['subquestion']."'";
				}
				$resqryques=exequery($qryques);
								
					$tabindex = 0;				
				while($rowquest=fetch($resqryques))
				{
					if($rowqry[0]!=9 && $rowqry[0]!=10)
					{
						
						 $empcode = explode(':',$_POST['empcode']);
    	                 $empcode  = $empcode[0];
						
						
						/* $qry1="SELECT question , count(case when remark='1' then remark end) as 'first' ,count(case when remark='2' then remark end) as 'second', count(case when remark='3' then remark end) as 'third' from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."' and R2.questiontype!=9 and R2.questiontype!=10 and R2.question='".$rowquest[0]."' and R2.empcode in(SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode=S.Branchid ";
						if($_POST['empcode']!='')
						{
							$qry1.=" and R2.empcode ='".$empcode."'";
						}
						if($_POST['region']!="ALL")
						{
						    $qry1.=" and B.regioncode='".$_POST['region']."'";
						}
						$qry1.=")"; */
						
						$qry1="SELECT question, count(case when remark='1' then remark end) as 'first' ,count(case when remark='2' then remark end) as 'second', count(case when remark='3' then remark end) as 'third' from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."' and R2.questiontype!=9 and R2.questiontype!=10 and R2.question='".$rowquest[0]."'";

						if($_POST['empcode']!='')
						{
							$qry1.=" and R2.empcode ='".$empcode."'";
						}

						if($_POST['region']!='ALL')
						{
							$qry1.=" and R2.empcode in (SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B,`Lokmanya-Empire`.DesignationMaster D where B.branchcode=S.Branchid and S.Designationid = D.designationid and B.regioncode='".$_POST['region']."'";
							if($_POST['branch']!='ALL')
							{
								$qry1.=" and B.branchcode='".$_POST['branch']."'";
							}
							//$qry1.=")";
							if($_POST['designation']!='ALL')
							{
								$qry1.=" and S.Designationid='".$_POST['designation']."'";
							}
							$qry1.=")";
						}
						
						
						if($_POST['empcode']=='' && $_POST['subquestion']=='ALL' && $_POST['question']=='ALL' && $_POST['region']=='ALL' && $_POST['branch']=='ALL' && $_POST['designation']=='ALL')
						{
							$qry1.=" and R2.empcode in(SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B,`Lokmanya-Empire`.DesignationMaster D  where B.branchcode=S.Branchid and S.Designationid = D.designationid )";
						}
						//echo $qry1.'<br>';
						
						//--------------------------             dialog data                  -----------------------------//
						
						$bname ="";
						
						$arrid = array("");
						$arrna = array("");
						$arrre = array("");
						$arrbr = array("");
						
						//echo $qry1.'<br>';
						$resqry1=exequery($qry1);
						while($rowqry1=fetch($resqry1))
						{
							echo"<tr>
							<td style='text-align:center;width:100px'>$rowquest[0]</td>
							<td style='width:1000px'>$rowquest[1]</td>";
							echo"<td style='text-align:center;width:100px' onclick='tabledata(".$tabindex.");'>$rowqry1[1]</td>
							<td style='text-align:center;width:100px' onclick='tabledata(".$tabindex.");'>$rowqry1[2]</td>
							<td style='text-align:center;width:100px' onclick='tabledata(".$tabindex.");'>$rowqry1[3]</td>";
							
							$totalusers="Select count(*), S.empcode, B.branchname from `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode = S.Branchid";
							if($_POST['region']!='ALL')
						    {
								$totalusers.=" and B.regioncode='".$_POST['region']."'";
								if($_POST['branch']!='ALL')
								{
									$totalusers.=" and B.branchcode='".$_POST['branch']."'";
								}
							}
						    if($_POST['designation']!='ALL')
							{
								$totalusers.=" and S.Designationid='".$_POST['designation']."'";
							}
							
							//echo $totalusers;
							$resusers = exequery($totalusers);
							$rowusers = fetch($resusers);

						    $notattusers=$rowusers[0]-($rowqry1[1]+$rowqry1[2]+$rowqry1[3]);
							
							echo "<td style='text-align:center;width:100px'>$notattusers</td>";
							echo "<td style='text-align:center;width:100px'>$rowusers[0]</td>";
							
							
							$remark1=$remark1+$rowqry1[1];
							$remark2=$remark2+$rowqry1[2];
							$remark3=$remark3+$rowqry1[3];
							$notatt = $notatt+$notattusers;
							$allusers = $allusers+$rowusers[0];

							$bname = $rowusers[2];
							$arrid = array_push($arrid, $rowusers[1]);
							//$arrna = array_push($arrna, $rowusers[0]);
							//$arrre = array_push($arrre, $rowusers[0]);
							//$arrbr = array_push($arrbr, $rowusers[0]); 
						}
						/*
						$sqlregi = "SELECT regionname FROM UserMaster U, RegionMaster R WHERE R.regioncode = U.Region AND empno = '".$eco[0]."'";
						$resregi = exequery($sqlregi);
						$rowregi = fetch($resregi);.$rowregi[0]
						*/
						echo "</tr><tr><td colspan=5><hr></td></tr><tr><td colspan=2></td><td colspan=5>";
						echo "<table border='1' id='displaytbl".$tabindex."' class='silent'>";
						echo "<tr><th>ID</th><th>Name</th><th>Region</th><th>Branch</th></tr>";
						for($i=0; $i<sizeof($arrid); $i++)
						{
							echo "<tr><td>".$arrid[$i]."</td><td>".""."</td><td>"."</td><td>".$bname."</td></tr>";
						}
						echo "</table>";						
						echo "</td></tr>";
						$tabindex++;
					}
				}
			}
			
		?>
		<tr><th colspan=2 style='width:1000px'>Total</th><th style='width:100px'><? echo $remark1;?></th><th style='width:100px'><? echo $remark2;?></th><th style='width:100px'><? echo $remark3;?></th><th style='width:100px'><? echo $notatt;?></th><th style='width:100px'><? echo $allusers;?></th></tr>
		</table></center>
		
		<script>
		function tabledata(tid)
		{
			$('table .silent').hide();
			$("#displaytbl"+tid).show();
		}
		</script>
						
	   <?		
					die();
				}
	  ?>
	  <?
//------------------------------------------------           Round Graph         ------------------------------------------------------------//	  	  
	  
	  
	  
	       if($_POST['question']!="ALL")  
		   {
	  
	        $region="select * from `Lokmanya-Empire`.RegionMaster where regioncode='".$_POST['region']."'";
			$rowregion=exequery($region);
			$resrowregion=fetch($rowregion);
			
			
			$qryquest = "select * from RetentionQuestion";
			if($_POST['question']!="ALL")
			$qryquest.=	" where id='".$_POST['question']."'";
		
		    //echo $qryquest;
			$resquest = exequery($qryquest);
			$rowquest = fetch($resquest);
			?>
			<center>
			<table width=80% border=0>
			<tr>
				<td colspan='2'>
					<header>
						<div class="icons"></div>
						<table border=0>
						
						
						<?
						if($_POST['region']!="ALL")
						{
						?>
						   <tr><td class="btn btn-success" style="text-align:left">Fromdate : <? echo $_POST['frmdate'];?> Todate : <? echo $_POST['todate'];?></td><td></td><td class="btn btn-warning" style="text-align:right">Region : <? echo $resrowregion[1];?></td></tr>
						<?
						}
						else
						{
						?>
						   <tr><td class="btn btn-success">Fromdate : <? echo $_POST['frmdate'];?> Todate : <? echo $_POST['todate'];?></td><td class="btn btn-warning">Region : <? echo 'ALL';?></td></tr>
						<?
						}
						?>
						<tr><td><br></td></tr>
					    <tr><td class="btn btn-success" colspan="3">Question : <? echo $rowquest[1];?></td></tr>
						
						</table>
					</header>
					<div id="container" style="min-width: 310px; max-width: 600px; height: 700px; margin: 0 auto;"></div>
				</td>
			</tr>
			<?


			?>
		   </table>
		   <?
		   $qry="SELECT question , count(case when remark='1' then remark end) as 'first' , count(case when remark='2' then remark end) as 'second', count(case when remark='3' then remark end) as 'third' from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."' and R2.empcode in(SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode=S.Branchid ";
			if($_POST['question']!="ALL")
			$qry.=" and R2.question='".$_POST['question']."'";
			if($_POST['region']!="ALL")
			$qry.=" and B.regioncode='".$_POST['region']."'";
			$qry.=")";
			$resqry=exequery($qry);
			while($rowqry=fetch($resqry))
			{
				if($rowqry[1]==0&&$rowqry[2]==0&&$rowqry[3]==0)	
				{
					?>
					<script>
					alert('No Results Found');
					window.location="GraphReport1.php";
					</script>
					<?
				}					
			}
		   
             ?>
			
			 <script type="text/javascript">

			/*Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
				return {
					radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
					stops: [
						[0, color],
						[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
					]
				};
			});

				Highcharts.chart('container', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,
						type: 'pie'
					},
					title: {
						text: ''
					},
					tooltip: {
							headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
							pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
							'<td style="padding:0"><b>{point.y}</b></td></tr>',
							footerFormat: '</table>',
							shared: true,
							useHTML: true
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								format: '<b>{point.name}</b>: {point.y}',
								style: {
									color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
								},
								connectorColor: 'silver'
							}
						}
					},
					series: [{
						name: 'Remark',
						data: [
								<? 
								$qry="SELECT question , count(case when remark='1' then remark end) as 'first' , count(case when remark='2' then remark end) as 'second', count(case when remark='3' then remark end) as 'third' from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."'";
								if($_POST['question']!="ALL")
								$qry.=" and R2.question='".$_POST['question']."'";
							    if($_POST['region']!="ALL")
								$qry.=" and R2.empcode in(SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode=S.Branchid and B.regioncode='".$_POST['region']."')";
								$resqry=exequery($qry);
								while($rowqry=fetch($resqry))
								{
									if($rowqry!=null)
									{
										?>
									
										['<?echo 'Strongly Disagree'?> ',<?echo $rowqry[1]?>],
										['<?echo 'No Opinion'?> ',<? echo $rowqry[2]?>],
										['<?echo 'Strongly Agree'?> ',<?echo $rowqry[3]?>],
										<?
									}
									
									
								}
								?>
								
							  ]
					}]
				}); */
				</script>
				
				
				
	  
 <?	               die();
				}
	  die();
  }

?>


</body>
<script>
$(document).ready(function()
{
		$('#frmdate').datepicker({
		dateFormat: 'dd-mm-yy',
		showOn: "button",	
		buttonImage: "/images/calendar.gif",
		buttonImageOnly: true});
		
		$('#todate').datepicker({
		dateFormat: 'dd-mm-yy',
		showOn: "button",	
		buttonImage: "/images/calendar.gif",
		buttonImageOnly: true});
});

//-------------------------------------------------------------------------------------------------------------------------------------------------//

$(document).ready(function()
			{
				$("#empcode").autocomplete("empsearchfetch.php", 
				{
					selectFirst: true
					});
								
			});
			
			
			
//-------------------------------------------------------------------------------------------------------------------------------------------------//

function getbranch()
			{
				
				branchdetails = $('#region').val();
				$.ajax({
				url: "/GraphReport1.php" ,
				data: "Action=getbranchdetails&branchdetails="+branchdetails,
				type : 'POST',
				success: function(output)
				{
							
				    //alert(output);
					
					 $('#branch').html('');	
					 catdata=output.split(';');
					 $('#branch').append("<option value='ALL'>ALL</option>");
					for(i=0;i<catdata.length-1;i++)
					{
						datainfo = catdata[i].split(':');
						$('#branch').append("<option value="+datainfo[0]+">"+datainfo[1]+"</option>");	
					}
				
				}
				});
			}
			
//-------------------------------------------------------------------------------------------------------------------------------------------------//

function getquestion()
			{
				
				question = $('#question').val();
				$.ajax({
				url: "/GraphReport1.php" ,
				data: "Action=getquestiondetails&question="+question,
				type : 'POST',
				success: function(output)
				{
							
				    //alert(output);
					
					 $('#subquestion').html('');	
					 catdata=output.split(';');
					 $('#subquestion').append("<option value='ALL'>ALL</option>");
					for(i=0;i<catdata.length-1;i++)
					{
						datainfo = catdata[i].split(':');
						$('#subquestion').append("<option value="+datainfo[0]+">"+datainfo[1]+"</option>");	
					}
				
				}
				});
			}
			
</script>
	
<form method="POST" Action="GraphReport1.php" name="form">
	<center>
	<br>
	<table class='table' style='width:25%;'>

		<tr>
		     <th colspan=2>Graph Report</th>
		</tr>
		<tr>
		     <td>From Date </td><td><input  id='frmdate' name='frmdate'  value='<? echo "01-01-2018"; //date('d-m-Y') ?>' ></td>
		</tr>
		<tr>
		     <td>To Date </td><td><input id='todate' name='todate'  value='<?echo "01-03-2018"; //date('d-m-Y')?>' ></td>
		</tr>
		<tr>
		    <td>Empcode </td>
		    <td><input type='text' name='empcode' id='empcode' style='width:40%;height:10%'></td>
		</tr>
        <tr>
			<td>Region </td>
			<?
			echo"<td style='width:20%'>
			     <select class='span11' name='region' id='region' onchange='getbranch();'>";
			
					$qryregion = "select * from `Lokmanya-Empire`.RegionMaster ORDER BY regionname";
					$resregion = exequery($qryregion);
					
					echo"<option value='ALL'>ALL</option>";
			while($rowregion = fetch($resregion))
			{
				
			     echo "<option value='".$rowregion[0]."'>" .$rowregion[1]. "</option>";
				 
			}
			echo"</select></td>";

			?>
		</tr>
		
		<tr>
			<td>Branch </td>
			<?
			echo"<td style='width:20%'>
			     <select class='span11' name='branch' id='branch'>";
			
					
			echo"</select></td>";

			?>
		</tr>

		<tr>
			<td>Designation:</td>

			<?
				echo "<td><select class='span12' name='designation' id='designation'>";
				
						$qrybranch = ("select * from `Lokmanya-Empire`.DesignationMaster");
						$resbranch = exequery($qrybranch);
						
				echo "<option value='ALL'>ALL</option>";
				
				while($rowbranch = fetch($resbranch))
				{
					
				     echo "<option value='".$rowbranch[0]."'>" .$rowbranch[1]. "</option>";
					 
				}
				echo "</select></td>";

			?>
		</tr>
		
		<tr>
			<td>Question:</td>

			<?
				echo "<td><select class='span12' name='question' id='question' onchange='getquestion();'>";
				
						$qrybranch = ("select * from RetentionQuestionType");
						$resbranch = exequery($qrybranch);
						
				echo "<option value='ALL'>ALL</option>";
				
				while($rowbranch = fetch($resbranch))
				{
					
				     echo "<option value='".$rowbranch[0]."'>" .$rowbranch[1]. "</option>";
					 
				}
				echo "</select></td>";

			?>
		</tr>
		
		<tr>
			<td>Sub Question:</td>

			<?
				echo "<td><select class='span12' name='subquestion' id='subquestion'>";
				echo "<option value='ALL'>ALL</option>";
				echo "</select></td>";

			?>
		</tr>

		<tr>
		     <td style='text-align:center' colspan=2><input class="btn btn-primary" type="submit" name='Action' value="Generate" ></td>
		<tr>

	</table>	


</form>		
</html>
