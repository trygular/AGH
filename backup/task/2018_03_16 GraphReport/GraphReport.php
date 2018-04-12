<?php

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




<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />

<img src="lok.jpg" height='12%' width='18%' style='margin-left:-80%;'>
<?
include("menubar.php");
?>
<br><br>
<hr style="height:5px;border:none;color:#428bca;background-color:#428bca;">
<br><br>

<link rel="shortcut icon" href="images/meta.png">
<link rel="stylesheet" href="style.css" />
<script type="text/javascript" src="/js/jquery.js"></script>		
<script type="text/javascript" src="/js/jquery-ui.js"></script>
<link rel="stylesheet" href="/css/redmond/jquery-ui.css"/>
<script type="text/javascript" src="jquery.form.js"></script>
<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script>
<link media="print" rel="Alternate" >





<?
		if($_POST['action']=="Generate")
		{
			$region="select * from `Lokmanya-Empire`.RegionMaster where regioncode='".$_POST['region']."'";
			$rowregion=exequery($region);
			$resrowregion=fetch($rowregion);
			
			
			$qryquest = "select * from RetentionQuestion";
			if($_POST['question']!="ALL")
			$qryquest.=	" where id='".$_POST['question']."'";
		
			$resquest = exequery($qryquest);
			$rowquest = fetch($resquest);
			?>
			<center>
			<table width=80% border=0>
			<tr>
				<td colspan='2'>
					<header>
						<div class="icons"></div>
						<h1 class="btn btn-warning">Region : <? echo $resrowregion[1];?></h1>
						<br><br><br>
						<h5 class="btn btn-success">Question : <? echo $rowquest[1];?></h5>
					</header>
					<div id="container" style="min-width: 310px; max-width: 800px; height: 700px; margin: 0 auto;"></div>
				</td>
			</tr>
			<?


			?>
		   </table>
		   
		
           <?
			die();
		}
?>
<br>
<form method="POST" action="GraphReport.php" name="form">
<center>
<table class='table' style='width:25%;'>

<tr><th colspan=2>Graph Report</th></tr>

<?

include("configure.php");
mysql_connect($mysql_erp_host,$mysql_erp_user,$mysql_erp_password);	
usedb("elokmany_SmartChat");

  $userinfoSql = "select * from userinfo where loginsessionid='".session_id()."'";
  $userinfoRes = exequery($userinfoSql);
  $userinfoRow = fetch($userinfoRes);
  
  $UserMasterSql = "select * from UserMaster where Id='".$userinfoRow[1]."'";
  $UserMasterRes = exequery($UserMasterSql);
  $UserMasterRow = fetch($UserMasterRes);
  $name=$UserMasterRow[0].":".$UserMasterRow[1]

?>
	
	<td>Region:</td>
			<?
				echo"<td><select class='span11' name='region' id='region'>";
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
	<td>Question:</td>

	<?
				echo "<td><select class='span12' name='question' id='question'>";
				$qrybranch = ("select * from RetentionQuestion");
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
<td  style='text-align:center' colspan=2><input class="btn btn-primary" type="submit" name='action' value="Generate" >
<!--<td  style='text-align:center'><input class="btn btn-primary" type="submit" name='action' value="Search" >-->
</td>
<tr>
	
	</table>	
	
	
</form>


</body>
</html>