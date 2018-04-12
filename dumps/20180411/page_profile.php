<?php
	include 'config.php';
		
	include("session.php");
	$session = new Session();

?>

<html>
<head>
    <script src="scripts/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <style>
		html, body, table, tr {
			height: 100%;
			width: 100%;
		}

		center { color:#FFF; }
	</style>

	<!-- style for collapse other tabs -->
	<script>
	$(function () {
        $('.panel-collapse').on('show.bs.collapse', function (e) {
			$(e.target).closest('.panel').siblings().find('.panel-collapse').collapse('hide');
        });
    });

	$(document).ready(function(){
		msgHide();
		$("#btnTabLogin").click();
	});

	function msgHide()
	{
		$("#txMessage").html("");
		$("#txMessage").hide();
	}

	function msgShow(msg)
	{
		$("#txMessage").html(msg);
		$("#txMessage").show();
	}
	</script>
</head>
<body style="background: rgb(63, 65, 148);">

<!-- common logo -->
<?php include_once("common_logo.php"); ?>

<?php

$pro_id = "";
$pro_name = "";
$pro_mobi = "";

if( !isset($_POST["submit"]) && isset($_SESSION["user"]) )
{
	$sql = "SELECT * FROM login WHERE user_id='".$_SESSION["user"]."' ";
	$result = mysql_query($sql, $con);
	$row = mysql_fetch_row($result);
	$pro_id = $row[0];
	//$pro_name = $row[3];
	//$pro_mobi = $row[4];
	$flag_show = 1;
} else {
	$flag_show = 0;
}

if($_POST["submit"] == "Login")
{
	//select txUser and txPass
	$sql = "SELECT * FROM login WHERE username='".$_POST["tlUser"]."' AND password='".$_POST["tlPass"]."' ";
	if ($result = mysql_query($sql, $con))
	{
		$flag_show = mysql_num_rows($result);
		if($flag_show != 0)
		{
			$row = mysql_fetch_row($result);
			{
				$_SESSION["user"] = $row[0];
				//$pro_name = $row[2];
				//$pro_mobi = "+91-".$row[3];
				//$pro_id = $row[5];
			}
		} else {
			// username or password missmatch.
			echo "<center>Invalid username or password..!</center>";
		}
	} else {
		// DB : Error in retriving user info
		echo "<center>DB ERR: Error in retriving user info..!</center>";
		$flag_show = 0;
	}
}

if($_POST["submit"] == "Sign Up")
{
	$sqlGetOTP = "SELECT otp FROM otp WHERE mobile='".$_POST["txMobi"]."' ORDER BY id desc";
	$resultGetOTP = mysql_query($sqlGetOTP, $con);
	if (mysql_num_rows($resultGetOTP) != 0)
	{
		$row = mysql_fetch_row($resultGetOTP);

		$otp_inp = $_POST["txOtp"];
		$otp_sms = $row[0];
		
		// check if input opt matches with sms otp
		if($otp_inp == $otp_sms)
		{
			$txActive = 0;
			//if user accepts TC&P
			if(isset($_POST['txActive']))
				$txActive = 1;

			$sql0 = "INSERT INTO login (username, password, name, mobile, email, city, active) VALUES ('".$_POST["txUser"]."', '".$_POST["txPass"]."','".$_POST["txName"]."','".$_POST["txMobi"]."', '".$_POST['txEmail']."', '".$_POST['txCity']."', '".$txActive."') ";
			if($result0 = mysql_query($sql0, $con))
			{

				$sql = "SELECT * FROM login WHERE username='".$_POST["txUser"]."' AND password='".$_POST["txPass"]."' ";
				if ($result = mysql_query($sql, $con))
				{
					if(mysql_num_rows($result) != 0)
					{
						$row = mysql_fetch_row($result);
						{
							$_SESSION["user"] = $row[0];
							//$pro_name = $row[2];
							//$pro_mobi = "+91-".$row[3];
							//$pro_id = $row[7];
							echo "<center>User registred successfully..!</center>";
							$flag_show = 2;
						}
					} else {
						// username or password missmatch.
						echo "<center>Invalid username or password..!</center>";
						$flag_show = 0;
					}
				}
			} else {
				// DB: Error in creation user info
				echo "<center>DB ERR: Error in creating user info..!</center>";
				$flag_show = 0;
			}
		} else {
			echo "<center>Wrong OTP entered..!".$otp_inp."-".$otp_sms."</center>";
			$flag_show = 0;
		}
	} else {
		echo "<center>Mobile no. not exists..!</center>";
		$flag_show = 0;
	}
}

?>
	
	<div class="container">
	
		<table>
			<tr><div id="txMessage" style="width:100%;height:auto;position:fixed;top:0px;left:0px;z-index: 1000;" class="form-control">JavaScript not enabled on your browser.</div></tr>
	<?php
		if($flag_show == 0) {			// USER login fails	
	?>
			<tr>
				<td style="height:90%;">
					<ul class="list-group">
						<li class="list-group-item">

						<div class="row panel">
							<div class="col-sm-12">
								<div role="tab" id="headingTwo">
									<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										<h6 id="btnTabSignUp">SignUp / Register</h6>
									</a>
									<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">

										<form name="frmSignUp" id="frmSignUp" action="page_profile.php" method="POST">
											<div class="row form-group">
												<div class="col-sm-12">Name<br /><input autocomplete="off" type="text" name="txName" id="txName" class="form-control" placeholder="Name" /></div>
											</div> 
											<div class="row form-group">
												<div class="col-sm-12">Username<br /><input autocomplete="off" type="text" name="txUser" id="txUser" class="form-control" placeholder="Desired username" /></div>
											</div>
											
											<div class="row form-group">
												<div class="col-sm-12">Password<br /><input autocomplete="off" type="password" name="txPass" id="txPass" class="form-control" placeholder="Password" /></div>
											</div> 
											<div class="row form-group">
												<div class="col-sm-12">Confirm Password<br /><input autocomplete="off" type="password" name="txPassConf" id="txPassConf" class="form-control" placeholder="Confirm Password" /></div>
											</div> 
											<div class="row form-group">
												<div class="col-sm-12">Email<br /><input autocomplete="off" type="text" name="txEmail" id="txEmail" class="form-control" placeholder="Email address" /></div>
											</div>
											<!--
											<div class="row form-group">
												<div class="col-sm-6">
													<input type="button" name="btnOtpEmail" id="btnOtpEmail" class="btn btn-primary" value="Send OTP" />
												</div>
												<div class="col-sm-6">
													<input type="number" name="txOtpEmail" id="txOtpEmail" class="form-control" value="" placeholder="Enter OTP sent to email" />
												</div>
											</div>
											-->
											<div class="row form-group">
												<div class="col-sm-12">Mobile<br /><input autocomplete="off" type="number" name="txMobi" id="txMobi" class="form-control" placeholder="Mobile" /></div>
											</div> 
											<div class="row form-group">
												<div class="col-sm-6">
													<input type="button" name="btnOtp" id="btnOtp" class="btn btn-primary" value="Send OTP" />
												</div>
												<div class="col-sm-6">
													<input type="number" name="txOtp" id="txOtp" class="form-control" value="" placeholder="Enter OTP sent to mobile" />
												</div>
											</div> 
											<div class="row form-group">
												<div class="col-sm-12">City<br /><input autocomplete="off" type="text" name="txCity" id="txCity" class="form-control" placeholder="City" /></div>
											</div> 
											<div class="row form-group">
												<div class="col-sm-1"> <input autocomplete="off" type="checkbox" name="txActive" id="txActive" class="form-control" /> </div>
												<div class="col-sm-11">
													<label for="txActive">
													I Accept 
													<a href="">Terms and Conditions</a> 
													and 
													<a href="">Privacy Policy</a>
													</label>
												</div>
											</div> 
											<div class="row form-group">
												<div class="col-sm-12" style="text-align:right;">
												<input autocomplete="off" type="submit" class="btn btn-default" name="submit" value="Sign Up" />
												</div>
											</div>
										</form>

									</div>  <!-- frmSignUp -->
								</div> <!-- tab 2 -->
							</div><!-- col --> 
						</div> <!-- row -->

						<div class="row panel">
							<div class="col-sm-12">
								<div role="tab" id="headingOne">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										<h6 id="btnTabLogin">Login</h6>
									</a>
									<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">

										<form name="frmLogin" id="frmLogin" action="page_profile.php" method="POST">
											<div class="row">
												<div class="col-sm-4"></div>
												<div class="col-sm-4"></div>
												<div class="col-sm-4"></div>
											</div>
											<div class="row form-group">
												<div class="col-sm-12">Username<br /><input autocomplete="off" type="text" name="tlUser" id="tlUser" class="form-control" placeholder="Username" /></div>
											</div>
											<div class="row form-group">
												<div class="col-sm-12">Password<br /><input autocomplete="off" type="password" name="tlPass" id="tlPass" class="form-control" placeholder="Password" /></div>
											</div> 
											<div class="row">
												<div class="col-sm-12" style="text-align:right;"><input autocomplete="off" type="submit" id="btnLogin" class="btn btn-default" name="submit" value="Login"></div>
											</div>
										</form>

									</div>  <!-- frmLogin -->
								</div> <!-- tab 1 -->
							</div><!-- col --> 
						</div> <!-- row -->

						</li>
					</ul>
				</td>
			</tr>
	<?php
		} else if($flag_show == 1) {
			// USER is here from login -- PROFILE PAGE AFTER LOGIN SUCCESS

			//if session is not set redirect 
			if(!isset($_SESSION["user"])) {
				header("Location : logout.php");	
			}
	?>
			<tr>
				<td>
					<ul class="list-group">

						<!-- common menu -->
						<?php include_once("common_menu.php"); ?>

						<li class="list-group-item">
							<div class="row form-group">
								<div class="col-sm-12"><a href="page_news_post.php">News Post</a></div>
							</div>
							<div class="row form-group">
								<div class="col-sm-12"><a href="page_news_list.php">My Submission</a></div>
							</div>
							<div class="row form-group">
								<div class="col-sm-12"><a href="page_news_setting.php">Setting</a></div>
							</div>
							<div class="row form-group">
								<div class="col-sm-12"><a href="logout.php">Logout</a></div>
							</div>
						</li>
					</ul>
				</td>
			</tr>
	<?php
		} else if($flag_show == 2) {
			// USER is here from signup & is logged in for first time 	-- PROFILE PAGE
			
			if(!isset($_SESSION["user"])) {
				header("Location : logout.php");	
			}
	?>
			<tr>
				<td>
					<ul class="list-group">

						<!-- app menu -->
						<?php include_once("menu_page.php"); ?>

						<li class="list-group-item">
							<div class="row form-group">
								<div class="col-xs-12"><a href="page_news_post.php">News Post</a></div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12"><a href="page_news_list.php">My Submission</a></div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12"><a href="page_news_setting.php">Setting</a></div>
							</div>
							<div class="row form-group">
								<div class="col-xs-12"><a href="logout.php">Logout</a></div>
							</div>
						</li>
					</ul>
				</td>
			</tr>
	<?php
		}
	?>
			<tr>
				<td>
				</td>
			</tr><!-- footer -->
		</table>
	</div>
</body>

<script>

function clearFrmSignUp()
{
	$("input[type='checkbox']").prop('checked',false);
	$("#txName").val("").focus();
	$("#txUser").val("");
	$("#txPass").val("");
	$("#txPassConf").val("");
	$("#txEmail").val("");
	$("#txMobi").val("").removeAttr("readonly");
	$("#txOtp").val("").prop("disabled", true);
	$("#txCity").val("");
	$('#btnOtp').prop("disabled", false);
}

function validFrmSignUp()
{
	
	if($("#txName").val() == "")
	{
		msgShow("Please enter name..");
		$("#txName").val("").focus();
		return false;
	}
	
	if($("#txUser").val() == "")
	{
		msgShow("Please enter username..");
		$("#txUser").val("").focus();
		return false;
	}
	
	if($("#txPass").val() == "")
	{
		msgShow("Please enter password..");
		$("#txPass").val("").focus();
		return false;
	}
	
	if($("#txPassConf").val() == "")
	{
		msgShow("Please confirm password..");
		$("#txPassConf").val("").focus();
		return false;
	}
	
	if($("#txPass").val() != $("#txPassConf").val())
	{
		msgShow("Confirm password mismatch password.");
		$("#txPassConf").val("");
		$("#txPass").val("").focus();
		return false;
	}
	
	if($("#txEmail").val() == "")
	{
		msgShow("Please enter email..");
		$("#txEmail").val("").focus();
		return false;
	}
	
	if($("#txMobi").val() == "")
	{
		msgShow("Please enter mobile..");
		$("#txMobi").val("").focus();
		return false;
	}
	
	if($("#txCity").val() == "")
	{
		msgShow("Please enter city..");
		$("#txCity").val("").focus();
		return false; 
	}

	var txActive = $("input[type='checkbox']").is(":checked");
	if(!txActive)
	{
		msgShow("Please accept Terms & Policy.");
		$("input[type='checkbox']").focus();
		return false;
	}
	
	return true;
}

function clearFrmLogin()
{
	$("#tlUser").val("").focus();
	$("#tlPass").val("");
}

function validFrmLogin()
{
	if($("#tlUser").val() == "")
	{
		msgShow("Please enter username..");
		$("#tlUser").val("").focus();
		return false;
	}
	
	if($("#tlPass").val() == "")
	{
		msgShow("Please enter password..");
		$("#tlPass").val("").focus();
		return false;
	}
	return true;
}

$("#btnTabSignUp").click(function(){	
	clearFrmSignUp();
	clearFrmLogin();
	msgHide();
});

$("#btnTabLogin").click(function(){
	clearFrmLogin();
	clearFrmSignUp();
	msgHide();
});

$("#frmSignUp").submit(function( event ) {
	if (!validFrmSignUp()) {	// validaion failed
		event.preventDefault();
	}
});

$("#frmLogin").submit(function( event ) {
	if (!validFrmLogin()) {		// validation failed
		event.preventDefault();
	}
});

$("#btnOtp").click(function(){

	if(	$("#txMobi").val().length == 0 )
	{
		msgShow("Invalid mobile number.");
		$("#txMobi").val("").focus();
	} else if( $("#txMobi").val().length != 10 )
	{
		msgShow("Enter 10 digit mobile number.");
		$("#txMobi").val("").focus();
	} else if( $("#txMobi").val().length == 10 )
	{
		msgHide();
		
		// lock mobile input
		$("#txMobi").prop("readonly", true);
		
		//disable btn send otp
		$('#btnOtp').prop('disabled', true);

	var jqxhr = $.post("page_sms.php", { Action: "SMS_Send", mobileno: $("#txMobi").val() })
		.done(function(resp) {
			if(resp == "SUCCESS")
			{
				msgShow("OTP sent to +91-" + $("#txMobi").val() );
				$('#txOtp').val("").prop("disabled", false).focus();
			} else if(resp == "FAILED") {
				//unlock mobile input
				$("#txMobi").removeAttr("readonly");

				//enable btn send otp
				$('#btnOtp').prop("disabled", false);

				$('#txOtp').prop("disabled", true);

				msgShow("OTP sending failed..");
			} else if(resp == "FAIL") {
				//unlock mobile input
				$("#txMobi").removeAttr("readonly");

				//enable btn send otp
				$('#btnOtp').prop("disabled", false);

				$('#txOtp').prop("disabled", true);

				msgShow("OTP Generating failed, Try again later..");
			}
		})
		.fail(function() {
			msgShow("OTP request failed..");
		})
		.always(function() {
			
		});
	}
});

$("#txOtp").change(function(){
	if( $(this).val().length > 6 ) {
		msgShow("OTP can be 6 digit only..");
		$(this).val("").focus();
	} else if( $(this).val().length == 6 )
	{
		$("#txCity").focus();
	}
});
</script>

</html>