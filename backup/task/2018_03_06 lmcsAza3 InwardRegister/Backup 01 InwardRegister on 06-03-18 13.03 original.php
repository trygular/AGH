<?
	/*
		PHP Name : InwardRegister.php
		Created Date : 16-05-2017
		Created By : Sneha Ambarshet (Screen Design)
		Description : This is Inward Register
	*/
	include_once("db.php");
	
	//action:inwordnoauto&cmpname='+cmpname+"&inrid="+inrid,//action:inwordnoauto
	if($_POST['action'] == 'inwordnoauto')
	{
		if($_POST['cmpname'] == 1){
			$cmpname ='AZA';
		}
		elseif($_POST['cmpname'] == 2){
			$cmpname ='LMCS';
		}
		/*
		$cmpqry = "SELECT cname FROM Mcompany WHERE  id='".$_POST['cmpname']."' ";
		$cmpres = exequery($cmpqry);
		$cmprow = fetch($cmpres);
		*/
		$serial = "SELECT inwardno FROM InwardRegister WHERE companyname='".$_POST['cmpname']."' ORDER BY did DESC";
		$serialres = exequery($serial);
		$serialrow = fetch($serialres);
		$serialnum = mysql_num_rows($serialres);
		if($serialnum >= 1){
			$outwardno11 = explode("/", $serialrow[0]);
			$outwardno12 = $outwardno11[3];
			$docid = $outwardno11[3] + 1;
		}
		else{
			$docid = '1';
		}
		
		
		
		$code = str_pad($docid, 4, "0", STR_PAD_LEFT);
		$odate = $_POST['date'];
		
		$onlyyear = date('Y', strtotime($odate));
		echo $cmpname.'/MUM/'.$onlyyear.'/'.$code;
		//$haderdate = date('y', strtotime(date('d/m/Y')));
		//$haderdate = date('y/m/d');
		//AZA/YY/MM/DD/TIME/001
		//echo $cmprow[0].'/'.$haderdate.'/'.$code;
		die();
	}
	
	//action='+actiontype+'&inrid='+inrid+'&date='+date+'&companyname='+companyname+'&departmentname='+departmentname+'&inwardno='+inwardno+'&address='+address+'&remark='+remark+'&inwardstate='+inwardstate
	if($_POST['action'] == 'Add')
	{
		$maxidno1="select max(did) from InwardRegister";
		$rs1=exequery($maxidno1);
		$out1=fetch($rs1);
		if($out1[0]!=null)
			$idmax1=$out1[0]+1;
		else{
			$idmax1 =1;
		}
		if($idmax1 != $_POST['inrid']){
			echo "Please use Given Id";
			die();
		}
		
		$qryin="insert into InwardRegister (did,date,companyname,departmentname,inwardno,address,remark,inwardstate,tmode,details,sendername,givento) values('".mysql_real_escape_string($_POST['inrid'])."','".DMYtoYMD($_POST['date'])."','".mysql_real_escape_string($_POST['companyname'])."','".mysql_real_escape_string($_POST['departmentname'])."','".mysql_real_escape_string($_POST['inwardno'])."','".mysql_real_escape_string($_POST['address'])."','".mysql_real_escape_string($_POST['remark'])."','".mysql_real_escape_string($_POST['inwardstate'])."','".mysql_real_escape_string($_POST['tmode'])."','".mysql_real_escape_string($_POST['details'])."','".mysql_real_escape_string($_POST['sendername'])."','".mysql_real_escape_string($_POST['givento'])."')";
		exequery($qryin);
		
		$qryfun=str_replace("'", " ", $qryin);
		$time = date('H:i:s');
		$UserLogsql ="INSERT INTO $logdb.UserLog VALUES ('','".$userinfoRow[1]."','".$userinfoRow[0]."','".date('Y-m-d')."','".$time."','Insert','".$qryfun."')";
		exequery($UserLogsql);
		
		echo "Added successfully!!!!!!!";
		die();
		die();
	}
	
	if($_POST['action'] == "Search")
	{
		
		$qry = "select did,date,companyname,departmentname,inwardno,address,remark,inwardstate,tmode,details,sendername,givento from InwardRegister where did='".$_POST['inrid']."'";
		//echo $qry;
		$res = exequery($qry);
		$nrow = mysql_num_rows($res);
		$row = fetch($res);
		if($nrow != 1)
		{
			echo "Notfound";
			die();
		}
		else{
			echo  $row[0]."!#!".YMDtoDMY($row[1])."!#!".$row[2]."!#!".$row[3]."!#!".$row[4]."!#!".$row[5]."!#!".$row[6]."!#!".$row[7]."!#!".$row[8]."!#!".$row[9]."!#!".$row[10]."!#!".$row[11];
			die();
		}
		die();
		
	}	

	if($_POST['action'] == "Update")
	{
		//(id,cname,ccode,address,city,state,country,pincode,phono,mobile,email,regaddr,currency,regdate,bookfrom,cstatus,cin,panorit,tanno,vattin,csttin,servicetax,cessno,remark,status) values('".mysql_real_escape_string($_POST['id'])."',,,,,,,,,,,,,,,,)";
		//whom='+whom+'&givento='+givento
		$qry = "UPDATE InwardRegister SET date='".DMYtoYMD($_POST['date'])."',companyname='".mysql_real_escape_string($_POST['companyname'])."',departmentname='".mysql_real_escape_string($_POST['departmentname'])."',inwardno='".mysql_real_escape_string($_POST['inwardno'])."',address='".mysql_real_escape_string($_POST['address'])."',remark='".mysql_real_escape_string($_POST['remark'])."',inwardstate='".mysql_real_escape_string($_POST['inwardstate'])."',tmode='".mysql_real_escape_string($_POST['tmode'])."' ,details='".mysql_real_escape_string($_POST['details'])."', sendername='".mysql_real_escape_string($_POST['sendername'])."', givento='".mysql_real_escape_string($_POST['givento'])."' where did='".mysql_real_escape_string($_POST['inrid'])."'";
		$resq = exequery($qry);
		
		$qryfun=str_replace("'", " ", $qry);
		$time = date('H:i:s');
		$UserLogsql ="INSERT INTO $logdb.UserLog VALUES ('','".$userinfoRow[1]."','".$userinfoRow[0]."','".date('Y-m-d')."','".$time."','Update','".$qryfun."')";
		exequery($UserLogsql);
		
		echo "Update successfully!!!!!!! ";
		die();
	}

	if($_POST['action'] == "Delete")
	{
		$qry = "select did from InwardRegister where did='".$_POST['inrid']."'";
		$res = exequery($qry);
		$nrow = mysql_num_rows($res);
		$row = fetch($res);
		if($nrow != 1){
			echo "Notfound";
			die();
		}
		else{
			$qry1 = "delete from InwardRegister where did='".$_POST['inrid']."'";
			$res = exequery($qry1);
			
			$qryfun=str_replace("'", " ", $qry1);
			$time = date('H:i:s');
			$UserLogsql ="INSERT INTO $logdb.UserLog VALUES ('','".$userinfoRow[1]."','".$userinfoRow[0]."','".date('Y-m-d')."','".$time."','Delete','".$qryfun."')";
			exequery($UserLogsql);
			
			echo "successfully";
		}
		
		die();
	}

	if($_POST['action'] == "loadgrid1")
	{
	?>	
		<link rel="stylesheet" type="text/css" href="assets-minified/widgets/datatable/datatable.css">
		<script type="text/javascript" src="assets-minified/widgets/datatable/datatable.js"></script>
		<script type="text/javascript" src="assets-minified/widgets/datatable/datatable-bootstrap.js"></script>
		
		<script type="text/javascript">
			/* Datatables init */
			$(document).ready(function() {
				$('#dynamic-table-example-1').dataTable();

				/* Add sorting icons */

				$("table.dataTable .sorting").append('<i class="glyph-icon"></i>');
				$("table.dataTable .sorting_asc").append('<i class="glyph-icon"></i>');
				$("table.dataTable .sorting_desc").append('<i class="glyph-icon"></i>');

			});
		</script>
		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="dynamic-table-example-1">
			<thead>
				<tr><th colspan="10" style='text-align:right'><input class='btn btn-primary' type='button' value='Back' name='Action' onclick='window.location.reload();' style='width:50px;'></th></tr>
				<tr><th class='sorting_desc'>Document Id</th><th>Date</th><th>Name of Company</th><th>Department</th><th>Inward No</th><th>From Address</th><th>Remarks(Subject)</th><th>Status</th><th>Status</th><th>Transport Mode</th></tr>
			</thead>
			<tbody>
				<?
					$i = 0;
					$qry = "select * from InwardRegister ORDER BY did DESC";
					$res1 = exequery($qry);
					while($rows = fetch($res1))
					{
						if($i == 0){
							$classn = "odd gradeA";
							$i = 1;
						}
						else{
							$classn = "even gradeA";
							$i = 0;
						}
						$qrycompany = "select id,cname from Mcompany where id='".$rows['companyname']."'";
						$rescompany = exequery($qrycompany);
						$rowcompany = fetch($rescompany);
						
						$qrydepartment = "select did,dept from Mdepartment where did='".$rows['departmentname']."'";
						$resdepartment = exequery($qrydepartment);
						$rowdepartment = fetch($resdepartment);
						
						$qryinwardstate = "select id, inwardstate from state_inward where id='".$rows['inwardstate']."'";
						$resinwardstate = exequery($qryinwardstate);
						$rowinwardstate = fetch($resinwardstate);
						
						//did,date,companyname,departmentname,inwardno,address,remark,inwardstate
						echo"<tr class='".$classn."'><td><a onclick='tableview(".$rows['did'].");' href='#'>".$rows['did']."</a></td>
						<td>".YMDtoDMY($rows['date'])."</td>
						<td>".$rowcompany[1]."</td>
						<td>".$rowdepartment[1]."</td>
						<td>".$rows['inwardno']."</td>
						<td>".$rows['address']."</td>
						<td>".$rows['remark']."</td>
						<td>".$rowinwardstate[1]."</td>
						<td>".$rows[1]."</td>
						<td>".$rows['tmode']."</td>
						
						</tr>";
					}	
				?>
			</tbody>
		</table> 


	<?
	die();
	}	

	
?>


<!--------------------------------- Header Part--------------------------------------------- -->
<? include_once('header.php'); ?>
<body>
   <!-- <div id="loading"><img src="assets-minified/images/spinner/loader-dark.gif" alt="Loading..."></div>
	<div id="sb-site">
        <div id="page-wrapper"> -->
<!--------------------------------- Top Menu Part ----------------------------------------------->
	<? //include_once('topmenu.php') ?>

<!--------------------------------- Side Menu Part ----------------------------------------------->
	<? //include_once('sidemenu.php') ?>
		<!-- <div id="page-content-wrapper" class="rm-transition"> -->
		
		<link rel="stylesheet" type="text/css" href="assets-minified/widgets/datepicker/datepicker.css">
	<script type="text/javascript" src="assets-minified/widgets/datepicker/datepicker.js"></script>
	<script type="text/javascript">
		/* Datepicker bootstrap */
		$(function() {
			$('.bootstrap-datepicker').bsdatepicker({
				format: 'dd-mm-yyyy'
			});
		});
		
		function gettime()
		{
			timezone = $('#timezone').val();			
			var timezone1 = timezone.split('-');
			
			var timezone2 = timezone1[1].split('(');
			//alert(timezone1[1]);
			$('#time').val(timezone2[0]);
		}
		function chkstatus()
		{
			tmode = $('#tmode').val();		
			if(tmode=="ByCourier")
				$('#labname').html("Courier Name");
			else
				$('#labname').html("Name ");
			
		}
	</script>
	
	
	<!--------------------------------- Subtop Menu Part ----------------------------------------------->
	<? //include_once('subtopmenu.php') ?>
		
		<div id="page-content">
			<div class="page-box">
				<h3 class="page-title">Inward Register</h3>
				
				<div class="example-box-wrapper" id='mainform'>
					<form id="demo-form" class="form-horizontal" data-parsley-validate="">
						<div class="row">
							<div class='col-md-6'>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">
										Document Id
										<?
											$maxidno="select max(did) from InwardRegister";
											$rs=exequery($maxidno);
											$out=fetch($rs);
											if($out[0]!=null)
											$idmax=$out[0]+1;	
											else{
												$idmax =1;
											}
										?>
										
										
										<span class="required">*</span>
									</label>
									<div class="col-sm-2">
										<input class="form-control" type="text" id="inrid" name="inrid" value='<?echo (($search == 1)? :$idmax)?>' onkeypress="return onlynum(event);">
									</div>
									<div class='col-sm-1'><button class="btn btn-info" type="button" id='LookUpbutton' name='LookUpbutton' value='LookUp' onclick='LookUp();'><i class="glyph-icon demo-icon tooltip-button icon-search" style='margin: -4px; border:0px;'></i></button></div>
								</div>
							</div>
							
							<div class='col-md-6'>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">
									Receipt	Date
										<span class="required">*</span>
									</label>
									<div class="col-sm-6">
										<div class="input-prepend input-group demo-margin">
											<span class="add-on input-group-addon">
											<i class="glyph-icon icon-calendar"></i>
											</span>
											<input id='date' name='date' class="bootstrap-datepicker form-control" maxlength='10' style="width: 100px" onkeypress="return onlynum(event);" value="<? echo date('d-m-Y');?>" data-date-format="dd-mm-yyyy" type="text">
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class='col-md-6'>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">
										Sender Name
									</label>
									<div class="col-sm-4">
										<input class="form-control" type="text" id="sendername" name="sendername" value=''>
									</div>
								</div>
							</div>
							
						<div class='col-md-6'>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">
										Sender Address
									</label>
									<div class="col-sm-6">
										<textarea class='form-control' id='address' name='address'> </textarea>
									</div>
								</div>
							</div>
						
						
						
						</div>	
							
						<div class="row">
							
							<div class='col-md-6'>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">
										Mode Of Transport
									</label>
									<div class="col-sm-4">
										<select class="form-control" id="tmode" name="tmode" onchange='chkstatus()'>
										   <option value=''>Select</option>
											<option value='ByCourier'>BY COURIER</option>
											<option value='ByHand'>BY HAND</option>
											<option value='Other'>OTHER</option>
										</select>
									</div>
								</div>
							</div>
							<div class='col-md-6'>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">
										<span id='labname'>Name </span>
									</label>
									<div class="col-sm-4">
										<input class="form-control" type="text" id="givento" name="givento" value='' >
									</div>
								</div>
							</div>
							
						</div>
						<br>
						<div class="row">
							<div class='col-md-6'>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">
										Inward No.										
										<span class="required">*</span>
									</label>
									<div class="col-sm-4">
										<input class="form-control" type="text" id="inwardno" name="inwardno" value='' readonly='' onkeypress="return onlynum(event);">
									</div>									
								</div>
							</div>
							
								<div class='col-md-6'>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">
										Company Name
										<span class="required">*</span>
									</label>
									<div class="col-sm-5">
										<select class="form-control" id="companyname" name="companyname" onchange="inwordnoauto();">
											<option value=''>select</option>
											<?
												$qrycompany = "select id,cname from Mcompany where status=1";
												$rescompany = exequery($qrycompany);
												while($rowcompany = fetch($rescompany))
												{
													echo "<option value=".$rowcompany[0].">".$rowcompany[1]."</option>";
												}
											?>
										</select>
									</div>
								</div>
							</div>
							
							
						</div>
						<br>
						<div class="row">							
							<div class='col-md-6'>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">
										Subject
									</label>
									<div class="col-sm-6">
										<textarea class='form-control' id='remark' name='remark'> </textarea>
									</div>
								</div>
							</div>
							
							<div class='col-md-6'>
									<div class="form-group">
										<label for="" class="col-sm-3 control-label">
											Status
										</label>
										<div class="col-sm-3">
											<select class="form-control" id="inwardstate" name="inwardstate" onchange='statustype();'>
												<?
													$qryinwardstate = "select id, inwardstate from state_inward";
													$resinwardstate = exequery($qryinwardstate);
													while($rowinwardstate = fetch($resinwardstate))
													{
														echo "<option value=".$rowinwardstate[0].">".$rowinwardstate[1]."</option>";
													}
												?>
											</select>
										</div>
									</div>
							</div>
						</div>
						
							<br>
							<div class="row">	
								
								<div class='col-md-6' id='deptstatus' style="display:none">
									<div class="form-group">
										<label for="" class="col-sm-3 control-label">
											Department
										</label>
										<div class="col-sm-5">
											<select class="form-control" id="departmentname" name="departmentname">
												<?
													$qrydepartment = "select did,dept from Mdepartment where active=1";
													$resdepartment = exequery($qrydepartment);
													while($rowdepartment = fetch($resdepartment))
													{
														echo "<option value=".$rowdepartment[0].">".$rowdepartment[1]."</option>";
													}
												?>
											</select>
										</div>
									</div>
								</div>
							
								<div class='col-md-6'>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label">
										Handed Over To
									</label>
									<div class="col-sm-6">
										<textarea class='form-control' id='details' name='details'> </textarea>
									</div>
								</div>
							</div>
						</div>
					
							<label class="control-label col-sm-3"></label>
							<div class='col-md-6'>
								<button type="button" class="btn btn-primary" id='AddorSearch' name='AddorSearch' value='Add' onclick='AddorUpdate();'>Save</button>
								<button type="button" class="btn btn-primary" id='DeleteorSearch' name='DeleteorSearch' value='Search' onclick='DeleteSearch();'>Search</button>
								<button type="button" class="btn btn-primary" id='cancel' name='cancel' value='cancel' onclick="window.location = 'InwardRegister.php'">Cancel</button>
							</div>
					
						
					</form>
				</div>
				
				<div class="example-box-wrapper" id='loadgrid' style='display:none;'>
				
				</div>
				
				
			</div><!--Page box-->	
			</div><!-- page-content-->
		
	
	
	
	
<!--		</div><!-- page-content-wrapper-->	
<!--		</div><!-- page-wrapper-->
<!--	</div><!-- sb-site-->
	<? include_once('bottom.php'); ?>
	<script type='text/javascript'>
		function statustype(){
			var inwardstate = $('#inwardstate').val();
			//alert(inwardstate);
			if(inwardstate==2)
			{
				$('#deptstatus').show();
			}
			else
			$('#deptstatus').hide();
			
		}
		function inwordnoauto(){
			var cmpname = $('#companyname').val();
			var inrid = $('#inrid').val();
			var date = $('#date').val();
			
			$.ajax({
				url:'InwardRegister.php',
				data:'action=inwordnoauto&cmpname='+cmpname+"&inrid="+inrid+"&date="+date,
				type:'post',
				success:function(output)
				{
					//alert(output);
					$('#inwardno').val($.trim(output));
					return false;
				}
			});
		}
		
		function AddorUpdate(){
			var inrid = $.trim($('#inrid').val());
			if(inrid == ''){
				alert('Document Id I Blank..!!!!');
				return false;
			}
			
			var date = $('#date').val();
			var companyname = $.trim($('#companyname').val());
			var departmentname = $('#departmentname').val();
			var inwardno = $.trim($('#inwardno').val());
			if(inwardno == ""){
				alert("Outward No Is Blank");
				return false;
			}
			var address = $.trim($('#address').val());
			var remark = $('#remark').val();
			var inwardstate = $.trim($('#inwardstate').val());
			var tmode = $.trim($('#tmode').val());
			var details = $.trim($('#details').val());
			
			if(companyname == ''){
				alert('Please Select Company Name..!!!!');
				return false;
			}
			
			var sendername = $.trim($('#sendername').val());
			var givento = $.trim($('#givento').val());
			
			var actiontype = $('#AddorSearch').val();
			
			$.ajax({
				url:'InwardRegister.php',
				data:'action='+actiontype+'&inrid='+inrid+'&date='+date+'&companyname='+companyname+'&departmentname='+departmentname+'&inwardno='+inwardno+'&address='+address+'&remark='+remark+'&inwardstate='+inwardstate+'&tmode='+tmode+'&details='+details+'&sendername='+sendername+'&givento='+givento,
				type:'post',
				success:function(output)
				{
					alert($.trim(output));
					window.location.reload(true);
					return false;
				}
			});
		}
	
		function DeleteSearch(){
			var inrid = $('#inrid').val();
			if(inrid == ""){
				alert("Please Enter id");
				return false;
			}
			
			var actiontype = $('#DeleteorSearch').val();
			$.ajax({
				url:"InwardRegister.php",
				data:"action="+actiontype+"&inrid="+inrid,
				type:"post",
				success:function(output)
				{
					//alert(output);
					if($.trim(output) == "Notfound")
					{
						alert("Record not found");
						//swal("Record not found");
						return false;
					}
					else if($.trim(output) == "successfully")
					{
						alert("Record Deleted ...!!");
						//swal("Record Deleted ...!!");
						window.location.reload(true);
						return false;
					}
					else{
						var result = $.trim(output).split('!#!');
						$('#inrid').val(result[0]);
						$("#inrid").prop("readonly", true);
						$('#date').val(result[1]);
						$('#companyname').val(result[2]);
						$('#departmentname').val(result[3]);
						$('#inwardno').val(result[4]);
						$('#address').val(result[5]);
						$('#remark').val(result[6]);
						$('#inwardstate').val(result[7]);
						$('#tmode').val(result[8]);
						$('#details').val(result[9]);
						$('#sendername').val(result[10]);
						$('#givento').val(result[11]);
						
						$('#AddorSearch').val('Update');
						$('#AddorSearch').html('Update');
						$('#DeleteorSearch').val('Delete');
						$('#DeleteorSearch').html('Delete');
						
					}
				}
			});			
		}
	
		function LookUp(){
			$("#loadgrid").show();
			$.ajax({
				url:"InwardRegister.php",
				data:"action=loadgrid1",
				type:"POST",
				success:function(output)
				{
					//alert(output);
					$("#mainform").hide();
					$('#loadgrid').html($.trim(output));
					return false;
				}
			});	
		}

		function  tableview(id){
			$('#inrid').val(id);
			$("#mainform").show();
			$("#loadgrid").hide();
			$('#DeleteorSearch').val('Search');
			DeleteSearch();
		}
	</script>
	<?
		if($_GET['report'] =='getreport'){
			echo "<script>
			 tableview(".$_GET['id'].");
			
			</script>";
		}
	?>
</body>	


