<?
    session_start();
	
	include("configure.php");
	mysql_connect($mysql_erp_host,$mysql_erp_user,$mysql_erp_password);
	
	//$userrow = connectandlogin("");
	
	usedb("elokmany_SmartChat");

	$userinfoSql = "select * from userinfo where  loginsessionid='".session_id()."'";
	$userinfoRes = exequery($userinfoSql);
	$userinfoRow = fetch($userinfoRes);

	$UserMasterSql = "select * from UserMaster where Id='".$userinfoRow[1]."'";
	$UserMasterRes = exequery($UserMasterSql);
	$UserMasterRow = fetch($UserMasterRes);
	$name = $UserMasterRow[8].":".$UserMasterRow[1];

	//-------------------------------------------------------------------------------------------------------------------------------------------------//
	if($_POST['Action'] == "getbranchdetails")
	{
		$branchq = "Select * FROM `Lokmanya-Empire`.BranchMaster WHERE regioncode='".$_POST['branchdetails']."' ORDER BY branchname ";
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
		$q = "Select * FROM RetentionQuestion WHERE questiontype='".$_POST['question']."' ORDER BY questiontype ";
		$resq = exequery($q);
		while($rowq = fetch($resq))
		{
			echo $rowq[0].":".$rowq[1].";";
		}
		die();
	}

?>	

<!-- Bootstrap -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />

<!-- Header And Logo -->
<table border='0'><tr><td><img src="lok.jpg" height='6%' width='8%' style='float:center;margin-top:1%;'></td></tr></table><br><br>

<!-- menu bar -->
<? include("menubar.php"); ?>

<hr style="height:5px;width:100%;border:none;color:#428bca;background-color:#428bca;"/><br><br>


<link rel="shortcut icon" href="images/meta.png">
<link rel="stylesheet" href="style.css" />
<script type="text/javascript" src="/js/jquery.js"></script>		
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<link rel="stylesheet" href="/css/redmond/jquery-ui.css"/>
<script type="text/javascript" src="jquery.form.js"></script>
<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script>
<link media="print" rel="Alternate">	


<body>
<?
if($_POST['Action']=='Generate')
{
?>	  
	<!-- CDN Highcharts -->
	<script src="../../code/highcharts.js"></script>
	<script src="../../code/modules/exporting.js"></script>
<?       
//if($_POST['question']=='ALL')
{
?>
	<center><h5 class="btn btn-success">From <? echo $_POST['frmdate'];?> to <? echo $_POST['todate'];?></h5></center>	

	<!-- Back button -->
	<button class='btn' id='back' name='back' style='float:right;' onclick="window.location='GraphReport2.3.php'">BACK</button>

	<!-- container for highcharts -->
	<div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<!-- Highcharts script -->
<script type="text/javascript"> 
$(document).ready(function(){

<?php

	// conditions for graph toggle
	if( $_POST["question"] == "ALL" && $_POST["subquestion"] == "ALL")
	{
		echo "var json_tot_not = $.parseJSON($('#totg_not').val());";
?>

	Highcharts.chart('container1', {
		chart: { type: 'column' },
		title: { text: 'All regions.' },
		xAxis: { categories:
		[<?
			$quest="select * from RetentionQuestion where questiontype!=9 and questiontype!=10 ";
			$resquest = exequery($quest);
			while($rowquest = fetch($resquest))
			{
				echo $rowquest[0].",";
			}
		?>], crosshair: true
		},
		yAxis: { min: 0, title: { text: 'Users' } },
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: { column: { pointPadding: 0.2, borderWidth: 0 } }, 
		series: [{
			name: 'Strongly Disagree',
			data: [
			<?
			$grone = array();
			$quest="select * from RetentionQuestion where questiontype!=9 and questiontype!=10";
			$resquest = exequery($quest);
			while($rowquest = fetch($resquest))
			{
				$remark1 = "SELECT question , count(case when remark='1' then remark end) as 'first' from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."' and R2.questiontype!=9 and R2.questiontype!=10 and R2.question='".$rowquest[0]."' and R2.empcode in(SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode=S.Branchid ";
				
				if($_POST['region']!="ALL")
					$remark1.=" and B.regioncode='".$_POST['region']."'";
				$remark1.=")";
				
				$resremark1 = exequery($remark1);
				while($rowremark1= fetch($resremark1))
				{
					array_push($grone, $rowremark1[1]);
					echo $rowremark1[1].",";
				}
			}
			?>]
		}, {
			name: 'No Opinion',
			data: [	
			<?
			$grtwo = array();
			$quest="select * from RetentionQuestion where questiontype!=9 and questiontype!=10";
			$resquest = exequery($quest);
			while($rowquest = fetch($resquest))
			{
				$remark1 = "SELECT question , count(case when remark='2' then remark end) as 'second' from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."' and R2.questiontype!=9 and R2.questiontype!=10 and R2.question='".$rowquest[0]."' and R2.empcode in (SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode=S.Branchid ";
				
				if($_POST['region']!="ALL")
					$remark1.=" and B.regioncode='".$_POST['region']."'";
				$remark1.=")";
				
				$resremark1= exequery($remark1);
				while($rowremark1= fetch($resremark1))
				{
					array_push($grtwo, $rowremark1[1]);
					echo $rowremark1[1].",";
				}
			}
			?>]
		}, {
			name: 'Strongly Agree',
			data: [	
			<?
			$grthr = array();
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
					array_push($grthr , $rowremark1[1]);
					echo $rowremark1[1].",";
				}
			}
			?>]
		}, {
			name: 'Not Attempted',
			data: json_tot_not
		}]
	});

<?php
	
	} else if($_POST["question"] != "ALL" && $_POST["subquestion"] == "ALL") {
	
	// get the grand total count
	echo "var json_totg_01 = $.parseJSON($('#tot_01_tab".$_POST["question"]."').val());";
	echo "var json_totg_02 = $.parseJSON($('#tot_02_tab".$_POST["question"]."').val());";
	echo "var json_totg_03 = $.parseJSON($('#tot_03_tab".$_POST["question"]."').val());";
	echo "var json_totg_all = $.parseJSON($('#tot_all_tab".$_POST["question"]."').val());";
	echo "var json_totg_not = $.parseJSON($('#tot_not_tab".$_POST["question"]."').val());";
	
?>

	Highcharts.chart('container1', {
		chart: { type: 'column' },
		title: { text: '' },
		xAxis: { categories: 
		[
		<?
			$quest="select * from RetentionQuestion where questiontype!=9 and questiontype!=10 ";
			$resquest = exequery($quest);
			while($rowquest = fetch($resquest))
			{
				echo $rowquest[0].",";
			}
		?>
		], crosshair: true },
		yAxis: { min: 0, title: { text: 'Users' } },
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td> <td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: { column: { pointPadding: 0.2, borderWidth: 0 } }, 
		series: [{ name: 'Strongly Disagree', data: json_totg_01
		}, { name: 'No Opinion', data: json_totg_02
		}, { name: 'Strongly Agree', data: json_totg_03
		}, { name: 'Not Attempted', data: json_totg_not
		}]
	});

<?php
	} else { // conditions for pie graph

		$graph_header = "";
		echo "var chartHeader = '<b>Pie Graph</b>';";
		
		// get values of individual question's
		if($_POST["question"] != 'ALL' || $_POST["subquestion"] != 'ALL')
		{
			$nums = $_POST["question"]."_".($_POST["subquestion"]-1);	
			
			$valid1 = "$('#totone_".$nums."').text()";
			$valid2 = "$('#tottwo_".$nums."').text()";
			$valid3 = "$('#totthr_".$nums."').text()";
			$validnot = "$('#totnot_".$nums."').text()";
			$validall = "$('#totall_".$nums."').text()";
			
			echo "chartHeader = '<b>'+$('#question_".$nums."').text()+'</b>';";
			echo "var tot_01 = parseInt(".$valid1.");";
			echo "var tot_02 = parseInt(".$valid2.");";
			echo "var tot_03 = parseInt(".$valid3.");";
			echo "var tot_not = parseInt(".$validnot.");";
			echo "var tot_all = parseInt(".$validall.");";

		} else if($_POST["question"] == 'ALL' || $_POST["subquestion"] == 'ALL') { //get total's
			
			//get region name AS header
			$region="select regionname from `Lokmanya-Empire`.RegionMaster where regioncode='".$_POST['region']."'";
			$rowregion = exequery($region);
			$resrowregion = fetch($rowregion);
			echo "chartHeader = '<b>'+'".$resrowregion[0]."'+'</b>';";
			echo "var tot_01 = parseInt($('#tot_01').val());";
			echo "var tot_02 = parseInt($('#tot_02').val());";
			echo "var tot_03 = parseInt($('#tot_03').val());";
			echo "var tot_not = parseInt($('#tot_not').val());";
			echo "var tot_all = parseInt($('#tot_all').val());";
		}
		
?>
	//alert(tot_01 +' - '+tot_02+" - "+tot_03+" - "+tot_not+" - "+ tot_all);

	Highcharts.chart('container1', {
		chart: { type: 'pie' },
		title: { text: chartHeader },
		xAxis: { categories: ['Strongly Disagree','No Opinion', 'Strongly Agree'], crosshair: true },
		yAxis: { min: 0, title: { text: 'Users' } },
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
		series: [{ name: 'Count', data: [{name:'Strongly Disagree',y: tot_01 },{name:'No Opinion',y: tot_02 },{name:'Strongly Agree',y: tot_03 },{name:'Not Attempted', y: tot_not }] }]
	});

<?php
	}
?>
});
</script>

<!-- STYLE -->
<style>
	table { 
		width:100%;
		height:auto;
	}

	tbody, thead tr { 
	 -- display: block; 
	}

	table.scroll tbody {
		overflow-y: auto;
		overflow-x: hidden;
	}

	table.scroll td:hover {
		background-color:#f1f0f0;
	}

	table.silent tr:hover {
		background-color:#f1f0f0;
	}
	
	.button {
	  border:1px solid #333;
	  background:#6479fd;
	}
	
	.button:hover {
	  background:#a4a9fd;
	}
	
	.dialog {
		position:fixed;top:20%; left:30%; 
	  border:5px solid #666;
	  padding:10px;
	  background:#3A3A3A; 
	  display:none;
	}
	
	.dialog label{
	  display:inline-block;
	  color:#cecece;
	}
	
	input[type=text]{
	  border:1px solid #333;
	  display:inline-block;
	  margin:5px;
	}
</style>

	<input type='hidden' id='fromdate' name='fromdate' value='<?echo $_POST['frmdate'];?>' />
	<input type='hidden' id='todate' name='todate' value='<?echo $_POST['todate'];?>' />
	<input type='hidden' id='empcode' name='empcode' value='<?echo $_POST['empcode'];?>' />
	<input type='hidden' id='region' name='region' value='<?echo $_POST['region'];?>' />
	<input type='hidden' id='branch' name='branch' value='<?echo $_POST['branch'];?>' />
	<input type='hidden' id='designation' name='designation' value='<?echo $_POST['designation'];?>' />

<?

// Conditions for table visibility
if( $_POST["question"] == "ALL" && $_POST["subquestion"] == "ALL" || $_POST["question"] != "ALL" && $_POST["subquestion"] == "ALL") {
	$tableVisib = "";
} else {
	$tableVisib = " style='display:none;'";
}
?>
	<br><br>
	<!-- <div style='text-align:right;'><input type='button' id='hideall' name='hideall' value='Close All' onclick="$('table .silent').hide();" style='margin-right: 20px;' /></div> -->
	<table border='0' width='85%' class='table scroll' <?echo $tableVisib;?>>
	
	<thead>
		<tr>
		<th style='text-align:center;width:100px'>Question ID</th>
		<th style='width:1000px'>Question</th>
		<th style='text-align:center;width:100px'>Strongly Disagree</th>
		<th style='text-align:center;width:100px'>No Opinion</th>
		<th style='text-align:center;width:100px'>Strongly Agree</th>
		<th style='text-align:center;width:100px'>Not Attempted</th>
		<th style='text-align:center;width:100px'>Total</th>
		</tr>
	</thead>
<?
	// init variables
	$remark1 = 0;
	$remark2 = 0;
	$remark3 = 0;
	$notatt = 0;
	$allusers = 0;
	$tabindex = 0;
	$cnt_qid = 0;
	
	$json_totg01 = array();
	$json_totg02 = array();
	$json_totg03 = array();
	$json_totgall = array();
	$json_totgnot = array();

	//get all question
	$qry = "select * from RetentionQuestionType  where id!=9 and id!=10 ";
	$resqry = exequery($qry);
	while($rowqry = fetch($resqry))
	{
		// inti vars
		$json_tot01 = array();
		$json_tot02 = array();
		$json_tot03 = array();
		$json_totall = array();
		$json_totnot = array();
	
		echo "<tr class='propty_tab_".++$cnt_qid."' onclick='getdetails(".$cnt_qid.");'>
		<th colspan=7 style='text-align:left;background-color:#f1b25b;cursor:pointer;margin-right:250px'>$rowqry[1]</th>
		</tr>";
		
		$qryques = "select * from RetentionQuestion where questiontype='".$rowqry[0]."'";
		/*
		if($_POST['subquestion']!='ALL' && $_POST['subquestion']!='')
		{
			$qryques.=" and question='".$_POST['subquestion']."'";
		}*/
		$resqryques = exequery($qryques);

		while($rowquest = fetch($resqryques))
		{
			if($rowqry[0]!=9 && $rowqry[0]!=10)
			{
				//echo $rowquest[0]."<br>";
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

				$qry1="SELECT question, (case when remark='1' then remark end) as 'first' ,(case when remark='2' then remark end) as 'second', (case when remark='3' then remark end) as 'third', R1.empcode from RetentionQuestionaire2 R2,RetentionQuestionaire1 R1 where R1.empcode = R2.empcode and R1.wefdate>='".DMYtoYMD($_POST['frmdate'])."' and R1.wefdate<='".DMYtoYMD($_POST['todate'])."' and R2.questiontype!=9 and R2.questiontype!=10 and R2.question='".$rowquest[0]."'";
				//question number  $rowquest[0]

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
					$qry1.=" and R2.empcode in (SELECT empcode FROM `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B,`Lokmanya-Empire`.DesignationMaster D  where B.branchcode=S.Branchid and S.Designationid = D.designationid )";
				}
				//echo $qry1.'<br>';

				//--------------------------             dialog data                  -----------------------------//
				$cntone = 0;
				$cnttwo = 0;
				$cntthr = 0;
				
				$arrone = array();
				$arrtwo = array();
				$arrthr = array();
				$resqry1 = exequery($qry1);
				while($rowqry1 = fetch($resqry1))
				{
					if($rowqry1[1] != NULL)
						array_push($arrone, $rowqry1[4]);
					else if($rowqry1[2] != NULL)
						array_push($arrtwo, $rowqry1[4]);
					else if($rowqry1[3] != NULL)
						array_push($arrthr, $rowqry1[4]);
					//array_push($arrid, $rowqry1[4]);
					
					$cntone += $rowqry1[1];	// remark = 1	so incremented by 1
					$cnttwo += $rowqry1[2];	// remark = 2	so incremented by 2
					$cntthr += $rowqry1[3];	// remark = 3	so incremented by 3
				}
				
					$cntone = $cntone;
					$cnttwo = $cnttwo/2;
					$cntthr = $cntthr/3;
					
				echo "<tr id='' class='propty".$cnt_qid."' style='display:none;'>";
				echo "<td style='text-align:center;width:100px'>$rowquest[0]</td>";
				echo "<td style='width:1000px' id='question_".$cnt_qid."_".($tabindex)."'>$rowquest[1]</td>";
				
				$onclick0 = " onclick='alert(\"Count is Zero...!\");'";
				$onclick1 = " onclick='tabledata(".$tabindex.",1);'";
				$onclick2 = " onclick='tabledata(".$tabindex.",2);'";
				$onclick3 = " onclick='tabledata(".$tabindex.",3);'";
				
				if($cntone != 0)
					echo "<td style='text-align:center;width:100px' ".$onclick1." id='totone_".$cnt_qid."_".($tabindex)."'>".$cntone."</td>";
				else
					echo "<td style='text-align:center;width:100px' ".$onclick0." id='totone_".$cnt_qid."_".($tabindex)."'>".$cntone."</td>";

				if($cnttwo != 0)
					echo "<td style='text-align:center;width:100px' ".$onclick2." id='tottwo_".$cnt_qid."_".($tabindex)."'>".$cnttwo."</td>";
				else
					echo "<td style='text-align:center;width:100px' ".$onclick0." id='tottwo_".$cnt_qid."_".($tabindex)."'>".$cnttwo."</td>";

				if($cntthr != 0)
					echo "<td style='text-align:center;width:100px' ".$onclick3." id='totthr_".$cnt_qid."_".($tabindex)."'>".$cntthr."</td>";
				else
					echo "<td style='text-align:center;width:100px' ".$onclick0." id='totthr_".$cnt_qid."_".($tabindex)."'>".$cntthr."</td>";
					
				$totalusers = "Select count(*), S.empcode, B.branchname from `Lokmanya-Empire`.StaffMaster S,`Lokmanya-Empire`.BranchMaster B where B.branchcode = S.Branchid";
				if($_POST['region']!='ALL')
				{
					$totalusers .= " and B.regioncode='".$_POST['region']."'";
					if($_POST['branch']!='ALL')
					{
						$totalusers .= " and B.branchcode='".$_POST['branch']."'";
					}
				}
				if($_POST['designation']!='ALL')
				{
					$totalusers .= " and S.Designationid='".$_POST['designation']."'";
				}
					
				//echo $totalusers;
				$resusers = exequery($totalusers);
				$rowusers = fetch($resusers);

				$notattusers = $rowusers[0]-($cntone+$cnttwo+$cntthr);
				
				echo "<td style='text-align:center;width:100px' id='totnot_".$cnt_qid."_".($tabindex)."'>$notattusers</td>";
				echo "<td style='text-align:center;width:100px' id='totall_".$cnt_qid."_".($tabindex)."'>$rowusers[0]</td>";
				
				
				
				$remark1 = $remark1 + $cntone;
				$remark2 = $remark2 + $cnttwo;
				$remark3 = $remark3 + $cntthr;
				$notatt = $notatt + $notattusers;
				$allusers = $allusers + $rowusers[0];

				// TOTAL	Count
				array_push($json_tot01, $cntone);
				array_push($json_tot02, $cnttwo);
				array_push($json_tot03, $cntthr);
				array_push($json_totall, $rowusers[0]);
				array_push($json_totnot, $notattusers);
				
				// GRAND TOTAL	Count
				array_push($json_totg01, $cntone);
				array_push($json_totg02, $cnttwo);
				array_push($json_totg03, $cntthr);
				array_push($json_totgall, $rowusers[0]);
				array_push($json_totgnot, $notattusers);
				
				echo "</tr><tr style='display:none;'><td colspan='2'></td><td colspan='6'>";
				
				//	START OF DIALOG 
				$myqyer = "SELECT S.empcode, S.Name, R.regionname, B.branchname FROM `Lokmanya-Empire`.StaffMaster S, `Lokmanya-Empire`.BranchMaster B, `Lokmanya-Empire`.RegionMaster R WHERE B.branchcode = S.Branchid AND R.regioncode = B.regioncode AND empcode = '";
				
				echo "<table border='1' id='displaytbl".$tabindex."one' class='silent'  style='background-color:#dadada;border-color: #000000;display:none;'>";
				echo "<tr style='max-height:10px;'><th style='width:10%;'>ID</th><th style='width:30%'>Name</th><th style='width:30%'>Region</th><th style='width:30%'>Branch</th></tr>";
				for($i=0; $i<sizeof($arrone); $i++)
				{
					$myqye = $myqyer . $arrone[$i]."'";
					$resqyer = exequery($myqye);
					$rowqyer = fetch($resqyer);
					
					echo "<tr><td style='text-align:center;'>".$arrone[$i]."</td><td>".$rowqyer[1]."</td><td>".$rowqyer[2]."</td><td>".$rowqyer[3]."</td></tr>";
				}
				echo "</table>";
				
				echo "<table border='1' id='displaytbl".$tabindex."two' class='silent'  style='background-color:#dadada;border-color: #000000;display:none;'>";
				echo "<tr><th style='width:10%;'>ID</th><th style='width:30%'>Name</th><th style='width:30%'>Region</th><th style='width:30%'>Branch</th></tr>";
				for($i=0; $i<sizeof($arrtwo); $i++)
				{
					$myqye = $myqyer . $arrtwo[$i]."'";
					$resqyer = exequery($myqye);
					$rowqyer = fetch($resqyer);
					
					echo "<tr><td style='text-align:center;'>".$arrtwo[$i]."</td><td>".$rowqyer[1]."</td><td>".$rowqyer[2]."</td><td>".$rowqyer[3]."</td></tr>";
				}
				echo "</table>";
				
				echo "<table border='1' id='displaytbl".$tabindex."thr' class='silent'  style='background-color:#dadada;border-color: #000000;width: 100%;display:none;'>";
				echo "<tr><th style='width:10%;'>ID</th><th style='width:30%'>Name</th><th style='width:30%'>Region</th><th style='width:30%'>Branch</th></tr>";
				for($i=0; $i<sizeof($arrthr); $i++)
				{
					$myqye = $myqyer . $arrthr[$i]."'";
					$resqyer = exequery($myqye);
					$rowqyer = fetch($resqyer);
					
					echo "<tr><td style='text-align:center;'>".$arrthr[$i]."</td><td>".$rowqyer[1]."</td><td>".$rowqyer[2]."</td><td>".$rowqyer[3]."</td></tr>";
				}
				echo "</table>";
				$tabindex++;
				//	END OF DIALOG
				
				echo "</td></tr>";
			}
		}
		// Print total of individual tabs
		
		echo "<tr  class='propty".$cnt_qid."' id='propty".$cnt_qid."_tot' style='display:none;background-color: #a4d2f5;text-align:center;' >";
		echo "<td colspan=2 style='width:1000px;'>Total</td>";
		//echo "<th style='width:100px'></th>";
		//echo "<th style='width:100px'>Total</th>";
		echo "<td style='width:100px'>".array_sum($json_tot01)."</td>";
		echo "<td style='width:100px'>".array_sum($json_tot02)."</td> ";
		echo "<td style='width:100px'>".array_sum($json_tot03)."</td> ";
		echo "<td style='width:100px'>".array_sum($json_totnot)."</td> ";
		echo "<td style='width:100px'>".array_sum($json_totall)."</td> ";
		echo "</tr> ";
		
		
		//create 5 hidden inputs > total	id=tot_tab$cnt_qid
		echo "<input type='hidden' id='tot_01_tab".$cnt_qid."' value='".json_encode($json_tot01)."' />";
		echo "<input type='hidden' id='tot_02_tab".$cnt_qid."' value='".json_encode($json_tot02)."' />";
		echo "<input type='hidden' id='tot_03_tab".$cnt_qid."' value='".json_encode($json_tot03)."' />";
		echo "<input type='hidden' id='tot_not_tab".$cnt_qid."' value='".json_encode($json_totnot)."' />";
		echo "<input type='hidden' id='tot_all_tab".$cnt_qid."' value='".json_encode($json_totall)."' />";

	}
			//create 5 hidden inputs > GrandTotal	id=tot_tab$cnt_qid
			echo "<input type='hidden' id='totg_01' value='".json_encode($json_totg01)."' />";
			echo "<input type='hidden' id='totg_02' value='".json_encode($json_totg02)."' />";
			echo "<input type='hidden' id='totg_03' value='".json_encode($json_totg03)."' />";
			echo "<input type='hidden' id='totg_not' value='".json_encode($json_totgnot)."' />";
			echo "<input type='hidden' id='totg_all' value='".json_encode($json_totgall)."' />";

		?>

		<tr>
			<th colspan=2 style='width:1000px'>Grand Total</th>
			<th style='width:100px'><? echo $remark1;?></th><input type="hidden" id="tot_01" value="<?echo $remark1;?>" />
			<th style='width:100px'><? echo $remark2;?></th><input type="hidden" id="tot_02" value="<?echo $remark2;?>" />
			<th style='width:100px'><? echo $remark3;?></th><input type="hidden" id="tot_03" value="<?echo $remark3;?>" />
			<th style='width:100px'><? echo $notatt;?></th><input type="hidden" id="tot_not" value="<?echo $notatt;?>" />
			<th style='width:100px'><? echo $allusers;?></th><input type="hidden" id="tot_all" value="<?echo $allusers;?>" />
		</tr> 
		
		</table></center>

<!-- Show / Hide Main Table -->
<script>
	$(document).ready(function(){
		$('table .silent').hide();

<?
	if( $_POST["question"] == "ALL" && $_POST["subquestion"] == "ALL")
	{
		
	} else if($_POST["question"] != "ALL" && $_POST["subquestion"] == "ALL") {
		// show all questoins except the selected
		
		//get  max value of getdetails
		//echo "alert(".$cnt_qid.");";
		
		for($i=0; $i<=$cnt_qid; $i++)
		{
			if($_POST["question"] != $i)	// hide all TR > class = propty$i and propty_tab_$i
				echo "$('.propty".$i."').hide();"."$('.propty_tab_".$i."').hide();"."$('.propty".$i."_tot').hide();";
			else
				echo "$('.propty".$i."_tot').show();";
		}
		
?>
	
<?
	}
?>

	});

	// show hide toggle for all tr's
	function getdetails(id)
	{
		$('.propty'+id).slideToggle();
		//alert(id);
	}
	
	function tabledata(tid, cas)
	{
		$('table .silent').hide();
		$("#displaytbl"+tid).show();
		
		var dialog_width = 700;
		if(cas == 1) {
			$("#displaytbl"+tid+"one").show();
			$("#displaytbl"+tid+"one").dialog({title: "Strongly Disagree",width: dialog_width, position: { my: "center", at: "center", of: window}	});
		} else if(cas == 2) {
			$("#displaytbl"+tid+"two").show(); 
			$("#displaytbl"+tid+"two").dialog({title: "No Opinion",width: dialog_width, position: { my: "center", at: "center", of: window}	});
		} else if(cas == 3) {
			$("#displaytbl"+tid+"thr").show();
			$("#displaytbl"+tid+"thr").dialog({title: "Strongly Agree",width: dialog_width, position: { my: "center", at: "center", of: window}	});
		}
	}
</script>

<?		
	die();
}

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
	$("#empcode").autocomplete("empsearchfetch.php", {
		selectFirst: true
	});
});

//-------------------------------------------------------------------------------------------------------------------------------------------------//

function getbranch()
{
	
	branchdetails = $('#region').val();
	$.ajax({
	url: "/GraphReport2.3.php" ,
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
	url: "/GraphReport2.3.php" ,
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
	
<form method="POST" Action="GraphReport2.3.php" name="form">
	<center>
	<br>
	<table class='table' style='width:25%;'>

		<tr>
		     <th colspan=2>Graph Report</th>
		</tr>
		<tr>
		     <td>From Date </td><td><input  id='frmdate' name='frmdate'  value='<?echo date('d-m-Y')?>' ></td>
		</tr>
		<tr>
		     <td>To Date </td><td><input id='todate' name='todate'  value='<?echo date('d-m-Y')?>' ></td>
		</tr>
		<tr>
		    <td>Empcode </td>
		    <td><input type='text' name='empcode' id='empcode' style='width:40%;height:10%'></td>
		</tr>
        <tr>
			<td>Region</td>
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
		</tr>

	</table>	
	</center>
</form>		
</html>
