<?php


$con = mysql_connect("192.168.1.24", "root", "#47@BOM23##@");
mysql_select_db("DummyData", $con) or die(" No Database Selected" . mysql_error());


switch($_POST["token"])
{
	
	case "insert":
		$sql = "INSERT INTO DummyData.students (sname, ssub1, ssub2, ssub3) VALUES ('".$_POST['sname']."',".$_POST['ssub1'].",".$_POST['ssub2'].",".$_POST['ssub3'].");";
            $retval = mysql_query( $sql, $con );
         
            if(! $retval ) {
               die('Could not enter data: ' . mysql_error() . " - " . $sql);
            }
			
            echo "Entered data successfully!";
	break;
	
	case "delete":
		
		$sql = "DELETE FROM DummyData.students WHERE sid = ".$_POST["sid"].";";
		$retval = mysql_query( $sql, $con );
	 
		if(! $retval ) {
		   die('Could not enter data: ' . mysql_error() . " - " . $sql);
		} 
		echo "Student number ".$_POST["sid"]." deleted successfully!";
		
		/*
		$res = array('code' => "1",'message' => "success",'error' => "no");
		array_push($json, $res);
		$jsonstring = json_encode($json);
		echo $jsonstring;
		*/
	break;

	case "search":
		/**/
		$res = array('code' => "",'message' => "",'error' => "");
		array_push($json, $res);
		$jsonstring = json_encode($json);
		echo $jsonstring;
		
	break;
	
	default:
		//header("Location: ./index.php");
		echo "";
	break;
}

?>