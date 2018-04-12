<?php include('header.php'); 
//session_start();
include("configure.php");
mysql_connect($mysql_erp_host,$mysql_erp_user,$mysql_erp_password);	
//$userrow=connectandlogin("");
usedb("elokmany_SmartChat");
?>

<script src="jquery.min.js"></script>
		<script src='/common.js'></script>
		<link rel="shortcut icon" href="images/meta.png">
		<link rel="stylesheet" href="style.css" />
		<script type="text/javascript" src="jquery-autocomplete/jquery.autocomplete.js"></script>
		<script type="text/javascript" src="/js/window.js"></script>	
		<script type="text/javascript" src="/js/jquery.js"></script>		
		<script type="text/javascript" src="/js/jquery-ui.js"></script>
		<script type="text/javascript" src="/js/jquery.timepicker.js"></script>
		<script type="text/javascript" src="/js/rating.js"></script>
		<script type="text/javascript" src="/js/apprise.js"></script>
		<script type="text/javascript" src="jquery.nyroModal/js/jquery.nyroModal.custom.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/jquery.timepicker.css" />
		<link rel="stylesheet" href="/css/redmond/jquery-ui.css"/>
		<link rel="stylesheet" href="/css/apprise.css" type="text/css" />				
		<link rel="stylesheet" type="text/css" href="jquery-autocomplete/jquery.autocomplete.css" />
		<link rel="stylesheet" type="text/css" href="jquery.nyroModal/styles/nyroModal.css" />
		<script type="text/javascript" src="jquery.form.js"></script>
		<script type="text/javascript" src="/js/jquery.fancybox.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/jquery.fancybox.css" media="screen" />
		<link rel="stylesheet" href="style1.css" type="text/css">
      <script src="amcharts.js" type="text/javascript"></script> 
<?


if($_POST['Action']=="Add")
{

if($_POST['pubdate']=="")
	{	
	?>
	<html lang="en">
	  <script>
	  $(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  buttons: {
			Ok: function() {
			  $( this ).dialog( "close" );
			  window.location = "OpinionMaster.php";
			 			}
		  }
		});
	  });
	  </script>
	</head>
	<body>
	 
	<div id="dialog-message" title="Error">
	  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
			Please Enter the Publishing date!!!!
	  </p>
	 
	</div>
	 
	 
	</body>
	</html>	
	<?	
	
exit;
	
	}
	
	if($_POST['date']=="")
	{	
	?>
	<html lang="en">
	  <script>
	  $(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  buttons: {
			Ok: function() {
			  $( this ).dialog( "close" );
			   window.location = "OpinionMaster.php";
			}
		  }
		});
	  });
	  </script>
	</head>
	<body>
	 
	<div id="dialog-message" title="Error">
	  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
			Please Enter the  date!!!!
	  </p>
	 
	</div>
	 
	 
	</body>
	</html>	
	<?	
	exit();
	}
	
	
	if($_POST['question']=="")
	{	
	?>
	<html lang="en">
	  <script>
	  $(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  buttons: {
			Ok: function() {
			  $( this ).dialog( "close" );
			   window.location = "OpinionMaster.php";
			}
		  }
		});
	  });
	  </script>
	</head>
	<body>
	 
	<div id="dialog-message" title="Error">
	  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
			Please Enter the  Opinion  !!!!
	  </p>
	 
	</div>
	 
	 
	</body>
	</html>	
	<?	
	exit();
	}

	$qry = "insert into OpinionMaster values('".$_POST['transid']."','".DMYtoYMD($_POST['date'])."','".strtoupper($_POST['question'])."','".DMYtoYMD($_POST['pubdate'])."')";
	exequery($qry);
	if (is_array($_POST['group'])) 
	{
		foreach($_POST['group'] as $value)
		{
			exequery("insert into OpinionMaster1 values('".$_POST['transid']."',".$value.")");
		}
	}
	//echo '<center><div style="padding:3px 2px;border-bottom:1px; border-radius: 10px;  border: 3px solid black;background:#C5E8A5;width:300px;  " ><font color="black"><img src="/images/info.png" width="4%" height="4%">	Opinion Added Successfully</img></div>';
	
		?>
	<html lang="en">
	  <script>
	  $(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  buttons: {
			Ok: function() {
			  $( this ).dialog( "close" );
			   window.location = "OpinionMaster.php";
			}
		  }
		});
	  });
	  </script>
	</head>
	<body>
	 
	<div id="dialog-message" title="Added">
	  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
			Opinion Added Successfully
	  </p>
	 
	</div>
	 
	 
	</body>
	</html>	
	<?	
	exit();
	
}

if($_POST['Action']=="Update")
{

if($_POST['pubdate']=="")
	{	
	?>
	<html lang="en">
	  <script>
	  $(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  buttons: {
			Ok: function() {
			  $( this ).dialog( "close" );
			  window.location = "ThoughtMaster.php";
			 			}
		  }
		});
	  });
	  </script>
	</head>
	<body>
	 
	<div id="dialog-message" title="Error">
	  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
			Please Enter the Publishing date!!!!
	  </p>
	 
	</div>
	 
	 
	</body>
	</html>	
	<?	
	
exit;
	
	}
	
	if($_POST['date']=="")
	{	
	?>
	<html lang="en">
	  <script>
	  $(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  buttons: {
			Ok: function() {
			  $( this ).dialog( "close" );
			   window.location = "ThoughtMaster.php";
			}
		  }
		});
	  });
	  </script>
	</head>
	<body>
	 
	<div id="dialog-message" title="Error">
	  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
			Please Enter the  date!!!!
	  </p>
	 
	</div>
	 
	 
	</body>
	</html>	
	<?	
	exit();
	}
	
	
	if($_POST['question']=="")
	{	
	?>
	<html lang="en">
	  <script>
	  $(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  buttons: {
			Ok: function() {
			  $( this ).dialog( "close" );
			   window.location = "ThoughtMaster.php";
			}
		  }
		});
	  });
	  </script>
	</head>
	<body>
	 
	<div id="dialog-message" title="Error">
	  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
			Please Enter the  Opinion !!!!
	  </p>
	 
	</div>
	 
	 
	</body>
	</html>	
	<?	
	exit();
	}
	$qry = "Update  OpinionMaster set date='".DMYtoYMD($_POST['date'])."',question='".strtoupper($_POST['question'])."',pubdate='".DMYtoYMD($_POST['pubdate'])."' where transid='".$_POST['transid']."'";
	exequery($qry);
	exequery("delete from OpinionMaster1 where transid='".$_POST['transid']."'");
	if (is_array($_POST['group'])) 
	{
		foreach($_POST['group'] as $value)
		{
			exequery("insert into OpinionMaster1 values('".$_POST['transid']."',".$value.")");
		}
	}
//	echo '<center><div style="padding:3px 2px;border-bottom:1px; border-radius: 10px;  border: 3px solid black;background:#C5E8A5;width:300px;  " ><font color="black"><img src="/images/info.png" width="4%" height="4%">	Opinion Updated Successfully</img></div>';

		?>
	<html lang="en">
	  <script>
	  $(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  buttons: {
			Ok: function() {
			  $( this ).dialog( "close" );
			   window.location = "OpinionMaster.php";
			}
		  }
		});
	  });
	  </script>
	</head>
	<body>
	 
	<div id="dialog-message" title="Updated">
	  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
			Opinion Updated Successfully
	  </p>
	 
	</div>
	 
	 
	</body>
	</html>	
	<?	
	exit();
}

if($_POST['Action']=="Delete")
{
	$qry = "Delete from  OpinionMaster  where transid='".$_POST['transid']."'";
	exequery($qry);
	exequery("delete from OpinionMaster1 where transid='".$_POST['transid']."'");
	//echo '<center><div style="padding:3px 2px;border-bottom:1px; border-radius: 10px;  border: 3px solid black;background:#C5E8A5;width:300px;  " ><font color="red"><img src="/images/info.png" width="4%" height="4%">	Opinion Deleted Successfully</img></div>';
	
	?>
	<html lang="en">
	  <script>
	  $(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  buttons: {
			Ok: function() {
			  $( this ).dialog( "close" );
			   window.location = "OpinionMaster.php";
			}
		  }
		});
	  });
	  </script>
	</head>
	<body>
	 
	<div id="dialog-message" title="Deleted">
	  <p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
			Opinion Deleted Successfully
	  </p>
	 
	</div>
	 
	 
	</body>
	</html>	
	<?	
	exit();
}
/*
box-shadow: -13px 17px 37px 4px rgba(36,26,36,0.31);
box-shadow: -13px 17px 37px 4px rgba(36,26,36,0.31);
box-shadow: -13px 17px 37px 4px rgba(36,26,36,0.31);
*/
//

?>
<script src="jquery.min.js"></script>
		<script src='/common.js'></script>
		<script type="text/javascript" src="/js/window.js"></script>	
		<script type="text/javascript" src="/js/jquery.js"></script>		
		<script type="text/javascript" src="/js/jquery-ui.js"></script>
		<link rel="stylesheet" href="/css/redmond/jquery-ui.css"/>
		<link rel="stylesheet" href="/css/apprise.css" type="text/css" />				
		<link rel="stylesheet" type="text/css" href="jquery-autocomplete/jquery.autocomplete.css" />
		<link media="print" rel="Alternate" >
		
			<script>
	 $('#ff').form({
    url:'OpinionMaster.php',
    onSubmit:function(){
	
    return $(this).form('validate');
    },
    success:function(data){

    $.messager.alert('Info', data, 'info');
    }
    });
	</script>
	
<?
	if($_GET['Action']=="Lookup")
	{
		echo"<html>
			 <body>
			<form  method='POST' action='/OpinionMaster.php' > 
			<center>
			<table border='0' width='75%'>
			<tr><th>Opinion Id </th><th>Opinion</th><th> Date</th><th>Pub Date</th></tr>";
			$qryquestion = "select * from OpinionMaster";
			$resquestion = exequery($qryquestion);
			while($rowquestion=fetch($resquestion))
			{
				
				
				echo"<tr><td><a href='OpinionMaster.php?Action=Search&transid=".$rowquestion[0]."'>".$rowquestion[0]."</a></td><td>".$rowquestion[2]."</td><td>".YMDtoDMY($rowquestion[1])."</td><td>".YMDtoDMY($rowquestion[3])."</td></tr>";
			}
			echo"</table>";
			
			die();


	}
	if($_GET['Action']=="Search")
	{
			$qryquestion = "select * from OpinionMaster where transid='".$_GET['transid']."'";
			$resquestion = exequery($qryquestion);
			$rowquestion=fetch($resquestion);
			if($rowquestion==NULL)
			{
				echo "Question Id Does Not Exists";
				die();
			}
			else
			{
				$search=1;
			}
			
	}
	if($_POST['Action']=="Search")
	{
			$qryquestion = "select * from OpinionMaster where transid='".$_POST['transid']."'";
			$resquestion = exequery($qryquestion);
			$rowquestion=fetch($resquestion);
			if($rowquestion==NULL)
			{
				echo "Question Id Does Not Exists";
				die();
			}
			else
			{
				$search=1;
			}
			
	}

?>
<script src="jquery.min.js"></script>
		<script src='/common.js'></script>
		<script type="text/javascript" src="/js/window.js"></script>	
		<script type="text/javascript" src="/js/jquery.js"></script>		
		<script type="text/javascript" src="/js/jquery-ui.js"></script>
		<link rel="stylesheet" href="/css/redmond/jquery-ui.css"/>
		<link rel="stylesheet" href="/css/apprise.css" type="text/css" />				
		<link rel="stylesheet" type="text/css" href="jquery-autocomplete/jquery.autocomplete.css" />
		<link media="print" rel="Alternate" >
		
		<script>
$(document).ready(function()
{
			$('#date').datepicker({
		dateFormat: 'dd-mm-yy',
		showOn: "button",	
		buttonImage: "/images/calendar.gif",
		buttonImageOnly: true});
		
		
			$('#pubdate').datepicker({
		dateFormat: 'dd-mm-yy',
		showOn: "button",	
		buttonImage: "/images/calendar.gif",
		buttonImageOnly: true});
		
		});
</script>		
<html>
<body>
<form  id='ff'  method="POST" action="/OpinionMaster.php" > 
<center>
<table border="0" width='75%'> 
<div style="padding:3px 2px;border-bottom:1px solid #ccc"></div>
<tr>
<? 
$qrytemp = "SELECT max(transid) FROM OpinionMaster";
$restemp = exequery($qrytemp);
$rowtemp = fetch($restemp);
if($rowtemp==NULL)
{
$opinion = 1;
}
else
$opinion = $rowtemp[0]+1;
?>
<th colspan='2'>OPINION MASTER</th>
</tr>
<tr>
<td>OPINION NO.</td> 
<td><input type="text" name="transid" size='10'  value='<?   echo ($search == 1)? $rowquestion[0] :$opinion; ?>'  ><a href='OpinionMaster.php?Action=Lookup'> Lookup</a></td> 
</tr>
<tr>
<td> DATE</td>
<td><input type="text"  name="date" id="date" size='12'  value='<?   echo ($search == 1)? YMDtoDMY($rowquestion[1]): ''; ?>'  ></td>
</tr>
<tr>
<td> PUB DATE</td>
<td> <input type="text" name="pubdate"  id="pubdate" size='12' value='<?   echo ($search == 1)? YMDtoDMY($rowquestion[3]): '';?>'  ></td>
</tr>
<tr>
<td>OPINION</td>
<td> 


<link type="text/css" rel="stylesheet" href="demo.css">
<link type="text/css" rel="stylesheet" href="../jquery-te-1.4.0.css">


<script type="text/javascript" src="../jquery-te-1.4.0.min.js" charset="utf-8"></script>


<textarea class="jqte-test" name="question" cols='60' rows='3' ><?   echo ($search == 1)? $rowquestion[2] :""; ?></textarea>



<script>
	$('.jqte-test').jqte();
	
	// settings of status
	var jqteStatus = true;
	$(".status").click(function()
	{
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({"status" : jqteStatus})
	});
</script>


</td>
</tr>
<tr>
<td> OPINION For </td>

<td><select name='group[]' multiple="multiple" size='5' >
<?
	if($search==1)
	{
	//<input type="text" name="mainanswer" size='60' value='<?   echo ($search == 1)? $rowquestion[7] :""; 
	
		$qrytemp = "select * from GroupMaster1 G,OpinionMaster1 Q where Q.transid='".$rowquestion[0]."' and Q.group=G.groupid";
		$restemp = exequery($qrytemp);
		while($rowtemp =fetch($restemp))
		{
		echo "<option value='".$rowtemp[0]."' selected>".$rowtemp[1]."</option>";
		}
	}
	$tempflag = 0;
	$qrytemp = "select * from GroupMaster1 where groupid not in (select `group` from OpinionMaster1 where transid='".$rowquestion[0]."')";
	$restemp = exequery($qrytemp);
	while($rowtemp =fetch($restemp))
	{
		if($tempflag==0)
		echo "<option value='".$rowtemp[0]."' selected>".$rowtemp[1]."</option>";
		else
		echo "<option value='".$rowtemp[0]."' >".$rowtemp[1]."</option>";
		$tempflag++;
		
	}

?>
</select>
</td>
</tr>
<tr>
<td colspan='2' align='center'>
<input type='submit' value='<? echo ($search == 1)?"Update":"Add";?>' 	 name='Action'> 
			<input type='submit' value='<? echo ($search == 1)?"Delete":"Search";?>' name='Action'> 	
		<input type='submit' value='Cancel' name='Cancel'> 	</td>
</tr>
</center>
</table>
</form>
</body>
</html>
<?
include('footer.php');
mysql_close();

?>
