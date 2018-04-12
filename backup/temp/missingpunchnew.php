<?php
include('db.php');
    
      // usedb("TarunBharat-Empire");
    
 //  die();
	//Skills master 
	//filename		  :skillsmaster.php
	//Modified date :30-08-2013 
	//Author       :Lokesh Rajai
	//Description :to store list of skills. 	
if($_POST['Action']=="search")
	{   		
		   		
		
		
		$StaffMasterSql   = "SELECT * FROM  StaffMaster WHERE empcode='".$_POST['staffId']."' ";
		$StaffMasterQuery = exequery($StaffMasterSql);				
		while($StaffMasterRow   = fetch($StaffMasterQuery))
		{
			
	
			echo $StaffMasterRow['minentry'].":";
	
	   }
					
      die();
	}
	
	if($_POST['action']=="Add1")
		{
			
			      $sessionid = session_id();
					$userinfoSql   = "SELECT * FROM ".$security.".userinfo WHERE erpsessionid='".$sessionid."';";
					$userinfoQuery = exequery($userinfoSql);				
					$userinfoRow   = fetch($userinfoQuery);
				
				   //in
			      $arrival = $_POST['arrival'] ;
			      $thirdhalf = $_POST['thirdhalf'];
			      $fifthhalf = $_POST['fifthhalf'];
			      $seventhhalf = $_POST['seventhhalf'];
			      
			
			      //out
			      $secondhalf = $_POST['secondhalf'];
			      $fourthhalf = $_POST['fourthhalf'];
			      $sixthhalf = $_POST['sixthhalf'];
			      
			      //firsthalfcalculation
			      
			      //echo $fourthhalf ;
			      
			      $date = date('Y-m-d'); 
			      $curdate = date('Y-m-d'); 
			     	      $FirstHalfTimeSql = "select TIMEDIFF ('".$secondhalf."','".$arrival."')";
			      $FirstHalfTimeRes = exequery($FirstHalfTimeSql);
			      $FirstHalfTimeRow = fetch($FirstHalfTimeRes);
			      
			      $currentTimeex1 = explode(':',$FirstHalfTimeRow[0]);
			      $currentTime = $currentTimeex1[0].':'.$currentTimeex1[1];
			      
			      $FirstHalfTime = $FirstHalfTimeRow[0];
			      $dateA = $date.' '.$currentTime; 
					$dateB = $date.' 03:30'; 
					
					if(strtotime($dateA) >= strtotime($dateB))
					 {
			           $status = 'P';
			      	
                }
               else 
                 {
                   	
                   	$status ="A";
                 }
			      
			     
			      //secondhalfcalculation
			      
			      $SecondHalfTimeSql = "select TIMEDIFF ('".$fourthhalf."','".$thirdhalf."')";
			      $SecondHalfTimeRes = exequery($SecondHalfTimeSql);
			      $SecondHalfTimeRow = fetch($SecondHalfTimeRes);
			      
			      $currentTime1ex2 = explode(':',$SecondHalfTimeRow[0]);
			      $currentTime1 = $currentTime1ex2[0].':'.$currentTime1ex2[1];
			      
			     /* $start_date1 = new DateTime($date." ".$thirdhalf);
			      $end_date1 = $start_date1->diff(new DateTime($date." ".$fourthhalf));
			      $currentTime1 = $end_date1->h.':'.$end_date1->i;*/
			      $dateC = $date.' '.$currentTime1; 
					$dateD = $date.' 03:30'; 
					
					if(strtotime($dateC) >= strtotime($dateD))
					  {
			            $status1 = 'P';
			      	
                 }
               else 
                 {
                   	
                   	$status1 ="A";
                 }
			      
			      $finaltime = "select ADDTIME ('".$FirstHalfTime."','".$SecondHalfTimeRow[0]."')";
			      $finaltimeres = exequery($finaltime);
			      $finalrow = fetch($finaltimeres);
			      
			      $totalworkhrs = $finalrow[0];
			      //delete  query  if posting same data
			      $delqry = " delete from Attendancechk where empcode='".$_POST['empcode']."' and attendancedate='".DMYtoYMD($_POST['attendancedate'])."'";  
			      exequery($delqry);
			      
				  
				  if($_POST['fourthhalf']!="")
				  {
				  if(strtotime($_POST['fourthhalf'])<=strtotime("07:00"))
						  {
							  	$qrytemps = "select DATE_ADD('".DMYtoYMD($_POST['attendancedate'])."', INTERVAL   1 DAY)";
								$restemps = exequery($qrytemps);
								$rowtemps = fetch($restemps);
								$fou = $rowtemps[0]." ".$_POST['fourthhalf'];
						  }	  
						  else
						  $fou = DMYtoYMD($_POST['attendancedate'])." ".$_POST['fourthhalf'];
				  }
				  $first = DMYtoYMD($_POST['attendancedate'])." ".$_POST['arrival'];
				  $sec = DMYtoYMD($_POST['attendancedate'])." ".$_POST['secondhalf'];
				  if($_POST['thirdhalf']!="")
				  $thr = DMYtoYMD($_POST['attendancedate'])." ".$_POST['thirdhalf'];
				  
			 $sql = " INSERT INTO Attendancechk VALUES ('','".$_POST['empcode']."','".DMYtoYMD($_POST['attendancedate'])."','".$first."','".$sec."','".$thr."','".$fou."','','','','','','','".$FirstHalfTime."','".$SecondHalfTimeRow[0]."','".$totalworkhrs."','".$status."','".$status1."','1','".$curdate."','".$userinfoRow[1]."','".$_POST['reason']."')";
				// echo $sql ;
	 //echo '<br>';
			exequery($sql);
			      

					
					$sessionid = session_id();
		         $userinfoSql   = "SELECT * FROM ".$security.".userinfo WHERE erpsessionid='".$sessionid."';";
		         $userinfoQuery = exequery($userinfoSql);				
		         $userinfoRow   = fetch($userinfoQuery);
		         
					$SkillMasterSqlreplace=str_replace("'", " ", $sql);
					$UserLogsql ="INSERT INTO UserLog1 VALUES ('','".$userinfoRow[1]."','".$userinfoRow[0]."','".date('Y-m-d')."','".date('H-i-s')."','".$userinfoRow[5]."','".$SkillMasterSqlreplace."');";
					exequery($UserLogsql);
				
					echo ' Record added successfully... ';
					die();
		}	
?>
<? include('header.php');?>
<title>Missing Punch Entry</title>
<script type="text/javascript" src="/js1/jquery.js"></script>		
	<script type="text/javascript" src="/js1/jquery-ui.js"></script>
	<script type="text/javascript" src="/js1/jquery.timepicker.js"></script>
	<script type="text/javascript" src="/js1/rating.js"></script>
	<script type="text/javascript" src="/js1/apprise.js"></script>
	<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
		<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script>
			<script type="text/javascript" src="src/DateTimePicker.js"></script>
			<link rel="stylesheet" type="text/css" href="src/DateTimePicker.css" />
			

  <script>

			$(document).ready(function(){
				$("#staffId").autocomplete("empsearchfetch.php", {
					selectFirst: true
					});
								
			});
			
	function insert1() 
	{
		staffId1 = $('#staffId').val();
		empcode1 = staffId1.split(':');
		empcode = empcode1[0];
		attendancedate = $('#attendancedate').val();
		shiftname      = $('#shiftname').val();
		arrival        = $('#arrival').val();
		departure      = $('#departure').val();
		secondhalf     = $('#secondhalf').val();
		thirdhalf      = $('#thirdhalf').val();
		fourthhalf     = $('#fourthhalf').val();
		firststatus    = $('#firststatus').val();
		secstatus      = $('#secstatus').val();
		thirdstatus    = $('#thirdstatus').val();
		fourthstatus   = $('#fourthstatus').val();
		reason         = $('#reason').val();

		//nopunch        = $('#nopunch').val();
		
		//if($("input:radio[id='nopunch1']").is(":checked")) 
		{
		//write your code 
		nopunch = $('#nopunch1').val();        
		}
	 
		//if($("input:radio[id='nopunch']").is(":checked")) 
		{
			//write your code 
			// nopunch        = $('#nopunch').val();        
		}

		$.ajax({
			url: "missingpunchnew.php" ,
			data: "action=Add1&empcode="+empcode+"&attendancedate="+attendancedate+"&arrival="+arrival+"&departure="+departure+"&secondhalf="+secondhalf+"&thirdhalf="+thirdhalf+"&fourthhalf="+fourthhalf+"&firststatus="+firststatus+"&secstatus="+secstatus+"&thirdstatus="+thirdstatus+"&fourthstatus="+fourthstatus+"&reason="+reason+"&nopunch="+nopunch,
			type : 'POST',
			success: function(output)
			{
				alert(output);
				$('#staffId').val(staffId1);
				$('#attendancedate').val("");
				$('#arrival').val("");
				$('#departure').val("");
				$('#secondhalf').val("");
				$('#reason').val("");
				$('#thirdhalf').val("");
				$('#fourthhalf').val("");
			}
		});	
	}
			
	function validateHhMm(inputField)
	{
        var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(inputField.value);

        if (isValid) {
            //inputField.style.backgroundColor = 'red';
            //inputField.value(00:00:00);
        } else {
			//inputField.style.backgroundColor = '#fba';
			//inputField.value(00:00:00);
			//alert("Invalid time format\n The valid format is hh:mm:ss\n");
			inputField.value = "00:00:00";
			//return false;
        }

        //return isValid;
         //$('#departure').val(00:00:00);
    }
</script> 
	<script type="text/javascript" src="/js1/jquery.js"></script>		
	<script type="text/javascript" src="/js1/jquery-ui.js"></script>
	<script type="text/javascript" src="/js1/jquery.timepicker.js"></script>
	<script type="text/javascript" src="/js1/rating.js"></script>
	<script type="text/javascript" src="/js1/apprise.js"></script>
	<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
	<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script>
	<script src="jquery.maskedinput.js" type="text/javascript"></script>	
	<script type="text/javascript">
	$(function() 
	{
		$.mask.definitions['~'] = "[+-]";
		$("#arrival").mask("99:99");
		//$("#departure").mask("99:99");
		$("#secondhalf").mask("99:99");
		$("#thirdhalf").mask("99:99");
		$("#fourthhalf").mask("99:99");
    });
	</script>
<body>

<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<!--Top Menu start-->
<? include('top.php'); ?>
<!--logo End-->
<!--Top Menu End-->
<? include('left.php'); ?>
</header>
<!--header end-->
<? include('menu.php'); ?>
<!--sidebar end-->
    <!--main content start-->
    <section id="main-content" class="">
        <section class="wrapper">
        <!-- page start-->
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            MISSING PUNCH ENTRY
                        </header>
                        <div class="panel-body">
                            <div class="position-center">
     
                                	<form action="missingpunchnew.php" method="post" enctype="multipart/form-data" class="form-horizontal ">
                                	  
                                   
                                <center>
                                    
                                   
                                    <table class="table">
                                    
                                    <tr>
                                       <td>Staff Id or Name <a style="color:red;">*</a></td>
                                       <td>
                                            <input class="form-control" id="staffId" size="40" name="staffId" placeholder="Staff id"  value="<?echo $_GET['staffid'];?>" type="text" style="width:250px;;">
							                  </td>
                                     
                                    </tr>
                                    <tr>
                                    <td>Date of Punch</td>
                                    <td>
                                    <div data-date-viewmode="day" data-date-format="dd-mm-yyyy" style="width:110px" data-date="<? echo date('d-m-Y');?>"  class="input-append date dpYears">
                                       <input class="form-control dpd1" style="width:110px" id="attendancedate" name="attendancedate" value="<? echo date('d-m-Y');?>"/>
							                             <span class="input-group-btn add-on">
                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                              </span>
                                    </div>
                                    </td>
                                    
                                    </tr>
                                     </table>
				<table class="table">
                                    
                                    <tr>
                                    <td> 1st In </td>
                                    <td><input type="text" name="arrival"  id="arrival" style="width:90px" data-field="time" ></td>
                                    <td>
                                    
                                          <select name="firststatus" id="firststatus">
                                              <option value="I">In</option>
                                               <option value="O">Out</option>
                                          </select>
                                    </td>
                                    <td> 1st Out</td>
                                       <td><input type="text" name="secondhalf"  id="secondhalf" style="width:90px" data-field="time" ></td>
                                    <td>
                                          <select name="secstatus" id="secstatus">
                                               <option value="O">Out</option>
                                               <option value="I">In</option>
                                          </select>
                                    </td>
                                    <td></td>
                                    </tr>
                                   <tr>
                                      <td>2nd  In</td>
                                      <td><input type="text" name="thirdhalf"  id="thirdhalf" style="width:90px" data-field="time"  ></td>
                                      <td>
                                           <select name="thirdstatus" id="thirdstatus">
                                              <option value="I">In</option>
                                              <option value="O">Out</option>
                                          </select>
                                     </td>
                                    <td>2nd Out</td>
                                       <td><input type="text" name="fourthhalf"  id="fourthhalf" style="width:90px" data-field="time"  ></td>
                                    <td>
                                          <select name="fourthstatus" id="fourthstatus">
                                          <option value="O">Out</option>
                                              <option value="I">In</option>
                                               
                                          </select>
                                    </td>
                                    </tr>
								<tr><td ></td></tr>
						              </table>
						              <table class="table">
						                   <tr>
                                       <td >Reason</td>
                                       <td><textarea class="form-control "  name="reason"  id="reason"></textarea></td>
                                 
                                 </tr>
                                   <tr>
                                    
                                       <td><input  type="radio" name="nopunch"  id="nopunch1" value="1" checked> No ID</td>
                                       
                                      
                                    
                                 
                                 </tr>
                                    
                                <tr>
                                    
                               
                                    <td>
                                        <center> <input class="btn btn-info" type="button" name="action" value="ADD "  onclick="insert1();"/>   </center> 
						                 </td>
						      
						              </tr>
						              
				</table>
                            </form>
					</section>
            </div>
        </div>








   

      


        <!-- page end-->
        </section>
    </section>
    <!--main content end-->
<!--right sidebar start-->
<? include ('rightsidebar.php'); ?>
<!--right sidebar end-->

</section>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="bs3/js/bootstrap.min.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/easypiechart/jquery.easypiechart.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>

<script src="js/bootstrap-switch.js"></script>

<script type="text/javascript" src="js/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script type="text/javascript" src="js/bootstrap-daterangepicker/moment.min.js"></script>

<script type="text/javascript" src="js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="js/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="js/jquery-multi-select/js/jquery.quicksearch.js"></script>

<script type="text/javascript" src="js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>

<script src="js/jquery-tags-input/jquery.tagsinput.js"></script>

<script src="js/select2/select2.js"></script>
<script src="js/select-init.js"></script>


<!--common script init for all pages-->
<script src="js/scripts.js"></script>
<!--
<script src="js/toggle-init.js"></script>-->

<script src="js/advanced-form.js"></script>
<!--Easy Pie Chart-->
<script src="js/easypiechart/jquery.easypiechart.js"></script>
<!--Sparkline Chart-->
<script src="js/sparkline/jquery.sparkline.js"></script>
<!--jQuery Flot Chart-->
<script src="js/flot-chart/jquery.flot.js"></script>
<script src="js/flot-chart/jquery.flot.tooltip.min.js"></script>
<script src="js/flot-chart/jquery.flot.resize.js"></script>
<script src="js/flot-chart/jquery.flot.pie.resize.js"></script>
<script src="js/iCheck/jquery.icheck.js"></script>
<script src="js/icheck-init.js"></script>

<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>
<!--dynamic table initialization -->
<script src="js/dynamic_table_init.js"></script>

</body>
</html>
