<?php

$con = mysql_connect("192.168.1.24", "root", "#47@BOM23##@");
mysql_select_db("DummyData", $con) or die(" No Database Selected" . mysql_error());

switch($_POST["token"])
{

	case "insert_std_new":
		//insert_std_new($_POST['sname']);
	break;

	case "next_mid":
		$sql1 = "SELECT max(id) FROM MARKS M;"; 
		$result = mysql_query($sql1, $con);
		$str_out = 0;
		while ($row = mysql_fetch_array($result)) 
		{
			$str_out = $row[0];
		}
		echo $str_out + 1;
	break;
	
	case "next_sid":
		$sql1 = "SELECT max(id) FROM STUDENT;"; 
		$result = mysql_query($sql1, $con);
		$str_out = 0;
		while ($row = mysql_fetch_array($result)) 
		{
			$str_out = $row[0];
		}
		echo $str_out + 1;
	break;
	
	case "get_allid":
			$str_out = "<option>NEW STUDENT</option>";
			
			$result = mysql_query("SELECT * FROM DummyData.STUDENT;", $con);
			while ($row = mysql_fetch_array($result)) 
			{
				$str_out .= "<option>". $row[0] ."</option>";
			}
			
			echo $str_out;
		break;

	case "get_allname":
			$str_out = "<option>SELECT NAME</option>";
			
			$result = mysql_query("SELECT * FROM DummyData.STUDENT;", $con);
			while ($row = mysql_fetch_array($result)) 
			{
				$str_out .= "<option>". $row[1] ."</option>";
			}
			
			echo $str_out;
		break;
		
	case "get_onename":
			
			$result = mysql_query("SELECT * FROM DummyData.STUDENT where id = ".$_POST['sid'].";", $con);
			$rows = array();
			while ($row = mysql_fetch_array($result)) 
			{
				$rows[] = $row;
			}
			
			echo json_encode($rows);
		break;
		
	case "get_alldata":
		$sql = "SELECT m.id, s.id, s.sname, m.s1, m.s2, m.s3 FROM MARKS m inner join STUDENT s on m.sid = s.id where m.SID = ".$_POST['sid'].";";
		
		$str_out = "";
			$cnt = 0;
			$result = mysql_query($sql, $con);
			while ($row = mysql_fetch_array($result)) 
			{
				$str_out .= "<tr id='row".$cnt."'>";
				$str_out .= "<td id='mid".$cnt."'>". $row[0] ."</td>";
				$str_out .= "<td id='sid".$cnt."'>". $row[1] ."</td>";
				$str_out .= "<td id='sn".$cnt."'>". $row[2] ."</td>";
				$str_out .= "<td id='ma".$cnt."'>". $row[3] ."</td>";
				$str_out .= "<td id='mb".$cnt."'>". $row[4] ."</td>";
				$str_out .= "<td id='mc".$cnt."'>". $row[5] ."</td>";
				$str_out .= "<td id='avg".$cnt."'>". number_format((float)((($row[3] + $row[4] + $row[5])*100)/(100*3)), 2, '.', '') ."</td>";
				$str_out .= "<td><button class='btn btn-default' myval='".$cnt."'>Edit</button><td><button class='btn btn-danger' myval='".$cnt."' >Delete</button></td></tr>";
				$cnt++;
			}
			 
		echo "<table>".$str_out."</table>";
		break;
		
	case "dbinsert":
		$sql = "INSERT INTO DummyData.student (sname) VALUES ('".$_POST['sname']."');";
		$sql1 = "INSERT INTO DummyData.marks (sid, s1, s2, s3) VALUES ('".$_POST['sid']."',".$_POST['s1'].",".$_POST['s2'].",".$_POST['s3'].");";
            
			$retval = mysql_query( $sql, $con );
         
            if(! $retval ) {
               die('Could not enter data: ' . mysql_error() . " - " . $sql);
            }
			$retval1 = mysql_query( $sql1, $con );
			if(! $retval1 ) {
			   die('Could not enter data: ' . mysql_error() . " - " . $sql1);
			}
			echo "Entered data successfully!";
	
		break;
	
	
	// old

	case "cal_avg":
		$mo = $_POST["s1"] + $_POST["s2"] + $_POST["s3"];
		$tot = 100 * 3;
		$av = (($mo * 100) / $tot);
		echo number_format((float)$av, 2, '.', '');
	break;
	
	case "insert":
		$sql = "INSERT INTO DummyData.STUDENT (sname) VALUES ('".$_POST["sname"]."');";

		$retval = mysql_query( $sql, $con );
		if(! $retval ) {
		   //die('Could not enter data: ' . mysql_error() . " - " . $sql);
		   echo 0;
		}
		$lsid = mysql_insert_id();
		
		$data = json_decode($_POST["tabdata"],true);
				
		$s = "";
		for( $i=0; $i<count($data["myrows"]); $i++)
		{
			$row = $data["myrows"][$i];
			if($row['SID'] != ""){
				
				$sql = "INSERT INTO DummyData.MARKS (sid, s1, s2, s3) VALUES (".$row['SID'].",".$row['Marks1'].",".$row['Marks2'].",".$row['Marks3'].");";
				$res = mysql_query($sql);
				if(!$res)
				{  echo $sql.'Could not run query: ' . mysql_error();
				exit; } else { $s = "success"; }
			
			}
		}
		echo $s;
	break;
	
	case "update":
	//delete FROM MARKS where SID = 10;
		$sql = "delete FROM MARKS where SID = ".$_POST["si"].";";

		$retval = mysql_query( $sql, $con );
		if(! $retval ) {
		   //die('Could not enter data: ' . mysql_error() . " - " . $sql);
		   echo 0;
		   exit();
		}
	
		$data = json_decode($_POST["tabdata"],true);

		$s = "";
		for( $i=0; $i<count($data["myrows"]); $i++)
		{
			$row = $data["myrows"][$i];
			if($row['SID'] != ""){
				
				$sql = "INSERT INTO DummyData.MARKS (sid, s1, s2, s3) VALUES (".$row['SID'].",".$row['Marks1'].",".$row['Marks2'].",".$row['Marks3'].");";
				$res = mysql_query($sql);
				if(!$res)
				{  echo $sql.'Could not run query: ' . mysql_error();
				exit; } else { $s = "success"; }
			
			}
		}
		echo $s;
	break;
	
	case "delete":
		$sql = "DELETE FROM DummyData.students WHERE sid = ".$_POST["sid"].";";
		$retval = mysql_query( $sql, $con );
	 
		if(! $retval ) {
		   die('Could not enter data: ' . mysql_error() . " - " . $sql);
		}
		echo "Student number ".$_POST["sid"]." deleted successfully!";
	break;

	case "search":
		//echo '[{"name":"Jonathan Suh","gender":"male"},{"name":"William Philbin","gender":"male"},{"name":"Allison McKinnery","gender":"female"}]';
		
		$sql = "select * from DummyData.students WHERE sid = ".$_POST["sid"].";";
		$result = mysql_query( $sql, $con );
	 
		if (!$result) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		$row = mysql_fetch_row($result);
		echo '[{"id":'.$row[0].', "name":"'.$row[1].'", "s1":'.$row[2].', "s2":'.$row[3].', "s3":'.$row[4].'}]';

	break;
		
	case "insertjson":
		$data = json_decode($_POST["tabdata"],true);
				
		$s = "";
		for( $i=0; $i<count($data["myrows"]); $i++)
		{
			$row = $data["myrows"][$i];
			if($row['SID'] != ""){
				
				$sql = "INSERT INTO DummyData.MARKS (sid, s1, s2, s3) VALUES (".$row['SID'].",".$row['Marks1'].",".$row['Marks2'].",".$row['Marks3'].");";
				$res = mysql_query($sql);
				if(!$res)
				{  echo $sql.'Could not run query: ' . mysql_error();
				exit; } else { $s = "success"; }
			
			}
		}
		echo $s;
	break;
	
	case "cleardata":
		//delete FROM MARKS where SID = 10;
		$sql = "delete FROM MARKS where SID = ".$_POST["si"].";";

		$retval = mysql_query( $sql, $con );
		if(! $retval ) {
		   //die('Could not enter data: ' . mysql_error() . " - " . $sql);
		   echo 0;
		}
		echo 1;
	break;
	
	case "insertsname":
		$sql = "INSERT INTO DummyData.STUDENT (sname) VALUES ('".$_POST["n"]."');";

		$retval = mysql_query( $sql, $con );
		if(! $retval ) {
		   //die('Could not enter data: ' . mysql_error() . " - " . $sql);
		   echo 0;
		}
		echo mysql_insert_id();
	break;
	
	case "deletestd": 
		$sql = "delete FROM MARKS where SID = ".$_POST["si"].";";
		$retval = mysql_query( $sql, $con );
		if(! $retval ) {
			echo 0;
		} else {
			$sql = "delete FROM STUDENT where ID = ".$_POST["si"].";";
			$retval = mysql_query( $sql, $con );
			if(! $retval ) {
				echo 0;
			} else {
				echo "success";
			}
		}
	break;
	
	default:
		//header("Location: ./index.php");
		echo "DEFAULT";
	break;
}

?>