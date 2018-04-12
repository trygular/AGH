<?php
	include('db.php');
	
	$q=$_GET['q'];
	
	/*
	if(is_numeric($q) == 1)
		$sql="SELECT concat(canid, ' : ', name, ' ', middlename, ' ', surname) accol FROM EmpMaster WHERE canid LIKE '%$q%' ORDER BY canid";
	else
		$sql="SELECT concat(canid, ' : ', name, ' ', middlename, ' ', surname) accol FROM EmpMaster WHERE name LIKE '%$q%' ORDER BY canid";
 	*/
	
	if(is_numeric($q) == 1)
		$sql="SELECT concat(ER.eid , ' : ', EM.name, ' ', EM.middlename, ' ', EM.surname) accol FROM EmpMaster EM, EmployeeReg ER WHERE EM.canid = ER.canid AND ER.state='Sign On' AND ER.eid LIKE '%$q%' ORDER BY EM.canid;";
	else
		$sql="SELECT concat(ER.eid , ' : ', EM.name, ' ', EM.middlename, ' ', EM.surname) accol FROM EmpMaster EM, EmployeeReg ER WHERE EM.canid = ER.canid AND ER.state='Sign On' AND EM.name LIKE '%$q%' ORDER BY EM.canid;";
	
 	$result = exequery($sql);
 	if($result)
 	{
 		while($row=fetch($result))
  		{
			echo $row['accol']."\n";
  		}
 	}  

?>