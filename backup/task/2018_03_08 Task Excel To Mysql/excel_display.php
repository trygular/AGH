<?

$con = mysql_connect('192.168.1.24', 'root', '#47@BOM23##@');
mysql_select_db("BookLib");
     /*
		PHP Name :excel_display.php
		Created Date : 16-08-2017
		Created By : Lonica
	*/
	//include_once("db.php");
	
	

//action=searchcode&empcode="+empcode
//action=EmployeeCode&desig="+desig

function validate_mobile($mobile)
{
    return preg_match('/^[0-9]{10}+$/', $mobile);
}

function validate_email($email)
{
    return preg_match('/^[A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{2,4}$/', $email);
}



if(isset($_POST["submit"]))
{
	error_reporting(E_ALL ^ E_NOTICE);
	require_once 'excel_reader2.php';
	
	move_uploaded_file($_FILES["file"]["tmp_name"],  'contacts.xls');
	
	$data = new Spreadsheet_Excel_Reader("contacts.xls");
	
	echo"<table border='1'><tr>
		<td>Name</td>
		<td>Date</td>
		<td>Whats App No</td>
		<td>Occupation</td>
		<td>Area</td>
		<td>Addition Info</td>
		</tr>";
	
	$arrayMobileNums = array();
	for ($i = 2; $i <= 7180; $i++) 
	{
				
		$Name = mysql_real_escape_string($data->sheets[0]["cells"][$i][0]);	
		$LastName = mysql_real_escape_string($data->sheets[0]["cells"][$i][1]);
		$DisplayName = mysql_real_escape_string($data->sheets[0]["cells"][$i][2]);
		$Nickname = mysql_real_escape_string($data->sheets[0]["cells"][$i][3]);
		$EmailAddress = mysql_real_escape_string($data->sheets[0]["cells"][$i][4]);
		$Email2Address = mysql_real_escape_string($data->sheets[0]["cells"][$i][5]);
		$Email3Address = mysql_real_escape_string($data->sheets[0]["cells"][$i][6]);
		$HomePhone = mysql_real_escape_string($data->sheets[0]["cells"][$i][7]);
		$BusinessPhone = mysql_real_escape_string($data->sheets[0]["cells"][$i][8]);
		$HomeFax = mysql_real_escape_string($data->sheets[0]["cells"][$i][9]);
		$BusinessFax = mysql_real_escape_string($data->sheets[0]["cells"][$i][10]);
		$Pager = mysql_real_escape_string($data->sheets[0]["cells"][$i][11]);
		$MobilePhone = mysql_real_escape_string($data->sheets[0]["cells"][$i][13]);
		$HomeStreet = mysql_real_escape_string($data->sheets[0]["cells"][$i][13]);
		$HomeAddress2 = mysql_real_escape_string($data->sheets[0]["cells"][$i][14]);
		$HomeCity = mysql_real_escape_string($data->sheets[0]["cells"][$i][15]);
		$HomeState = mysql_real_escape_string($data->sheets[0]["cells"][$i][16]);
		$HomePostalCode = mysql_real_escape_string($data->sheets[0]["cells"][$i][17]);
		$HomeCountry = mysql_real_escape_string($data->sheets[0]["cells"][$i][18]);
		$BusinessAddress = mysql_real_escape_string($data->sheets[0]["cells"][$i][19]);
		$BusinessAddress2 = mysql_real_escape_string($data->sheets[0]["cells"][$i][20]);
		$BusinessCity = mysql_real_escape_string($data->sheets[0]["cells"][$i][21]);
		$BusinessState = mysql_real_escape_string($data->sheets[0]["cells"][$i][22]);
		$BusinessPostalCode = mysql_real_escape_string($data->sheets[0]["cells"][$i][23]);
		$BusinessCountry = mysql_real_escape_string($data->sheets[0]["cells"][$i][24]);
		$CountryCode = mysql_real_escape_string($data->sheets[0]["cells"][$i][25]);
		$Relatedname = mysql_real_escape_string($data->sheets[0]["cells"][$i][26]);
		$JobTitle = mysql_real_escape_string($data->sheets[0]["cells"][$i][27]);
		$Department = mysql_real_escape_string($data->sheets[0]["cells"][$i][28]);
		$Organization = mysql_real_escape_string($data->sheets[0]["cells"][$i][29]);
		$Notes = mysql_real_escape_string($data->sheets[0]["cells"][$i][30]);
		$Birthday = mysql_real_escape_string($data->sheets[0]["cells"][$i][31]);
		$Anniversary = mysql_real_escape_string($data->sheets[0]["cells"][$i][32]);
		$Gender = mysql_real_escape_string($data->sheets[0]["cells"][$i][33]);
		$WebPage = mysql_real_escape_string($data->sheets[0]["cells"][$i][34]);
		$WebPage2 = mysql_real_escape_string($data->sheets[0]["cells"][$i][35]);
		$Categories = mysql_real_escape_string($data->sheets[0]["cells"][$i][36]);
	
		//remove all hypnes
		$MobilePhone = str_replace("-", "", $MobilePhone);
	
		//if($i == 8)
		//	$MobilePhone = "8970746247";
			
		if(substr($MobilePhone, 0, 1) == "0")	// first char is zero
			$MobilePhone = ltrim($MobilePhone, '0');	// remove all leading zeros
		
		if(strlen($MobilePhone) == 12)	// if num starts with 91
			$MobilePhone = ltrim($MobilePhone, '91');
		
		if(strlen($MobilePhone) == 11)	// if num starts with 0
			$MobilePhone = ltrim($MobilePhone, '0');
		
		if($MobilePhone != ""							// no not blank
		&& strlen($MobilePhone) == 10					// no is 10 digs
		&& validate_mobile($MobilePhone)				// no is valid
		&& !in_array($MobilePhone, $arrayMobileNums)) 	// no not duplicate
		{
			$infoAdditional = "";
			if(validate_email($EmailAddress))
				$infoAdditional .= $EmailAddress."<br>";
			else if(validate_email($Email2Address))
				$infoAdditional .= $Email2Address."<br>";
			else if(validate_email($Email3Address))
				$infoAdditional .= $Email3Address."";
				
			//INSERT into Database
			$sqlINSERT = " INSERT INTO whatsAppno (name,date,mobno,occupation,area,additinfo) ";
			$sqlINSERT .= " VALUES ('".$DisplayName."','','".$MobilePhone."','7','','".$infoAdditional."') ";	
			$sqlResult = mysql_query($sqlINSERT);
	?>

	<tr>
<td><? echo $DisplayName;  ?></td>
<td><? echo ""; ?></td>
<td><? echo $MobilePhone; ?></td>
<td><? echo "Other"; ?></td>
<td><? echo ""; ?></td>
<td><? echo $infoAdditional; ?></td>
	</tr>
		
	<?php
			array_push($arrayMobileNums, $MobilePhone);
		}
	}
	echo "</table>";
		
die();		
}	




?>

<?
//include_once('header.php');
?>
<body>
  <!--  <div id="loading"><img src="assets-minified/images/spinner/loader-dark.gif" alt="Loading..."></div>
	<div id="sb-site">
        <div id="page-wrapper">
<!--------------------------------- Top Menu Part ----------------------------------------------->
	<? // include_once('topmenu.php') ?>

<!--------------------------------- Side Menu Part ----------------------------------------------->
	<? // include_once('sidemenu.php') ?>
		<!-- <div id="page-content-wrapper" class="rm-transition">
		-->
		<link rel="stylesheet" type="text/css" href="assets-minified/widgets/datepicker/datepicker.css">
		<script type="text/javascript" src="assets-minified/widgets/datepicker/datepicker.js"></script>
		<script type="text/javascript">
			/* Datepicker bootstrap */
			$(function() {
				$('.bootstrap-datepicker').bsdatepicker({
					format: 'dd-mm-yyyy'
				});
			});
		</script>
		
		
		
		<!--------------------------------- Subtop Menu Part ----------------------------------------------->
	<? //include_once('subtopmenu.php') ?>
	<link rel="stylesheet" type="text/css" href="assets-minified/widgets/tabs-ui/tabs.css">
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
		<div id="page-content">
			<!--<input type='hidden' id='ecode' name='ecode' value='4'/> -->
			<div class="page-box">
				<h3 class="page-title">Upload</h3>
		
	<form method="POST" action="excel_display.php" enctype="multipart/form-data">

		<div class="form-group">

			<label>Upload Excel File</label>

			<input type="file" name="file" >

		</div>

		<div class="form-group">
			<input type='submit' id='submit' name='submit' value='submit'>
          <!--<button type="import" name="import" class="btn btn-primary">Upload</button>-->
        </div>
      </form>
       <div class="example-box-wrapper" id='loadgrid' style='display:none;'>
		</div>
		</div>
		</div>

	<!--	</div><!-- page-content-wrapper-->	
	<!--	</div><!-- page-wrapper-->
	<!-- </div><!-- sb-site-->
	<?
	//include_once('bottom.php')
	?>
</body>

<script type='text/javascript'>

</script>

