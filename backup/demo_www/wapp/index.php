
<!-- <iframe id="if1" width="100%" height="254" style="visibility:visible" src="http://www.google.com"></iframe> -->

<?php

//include("/etc/tbdconfig.php");
//include("fpdf/fpdf.php");
$con = mysql_connect("192.168.1.24","root","#47@BOM23##@");	
//usedb("BookLib");
mysql_select_db("BookLib");


	 echo "<body>"; 
	$sql = "SELECT name, mobno FROM whatsAppno ";
	$res = mysql_query($sql, $con);
	
	//create file
	//$myfile = fopen("test.csv", "wr") or die("Unable to open file!");
	//fclose($myfile);
	//echo '<a href="http://jsfiddle.net/2gou4yen/">jsfid</a>';

	echo "<table border=1>";
	echo "<tr><th>Name</th><th>Link to WhatsApp  </th></tr>";
	//echo "<tr><th>CSV</th><th><a href=''>Download CSV</a></th></tr>";
	while ($row = mysql_fetch_assoc($res))
	{
		//echo "<a href='https://api.whatsapp.com/send?phone=91".r[0]."&text=TEST%20MESSAGE%20TB' >"."Send whats app to ".r[0]."</a>";
		echo '<tr><td>Name : '.$row['name'].'</td><td><a href="https://api.whatsapp.com/send?phone=91'.$row['mobno'].'&text=TEST%20MESSAGE%20TB" target="frm">'.'Message to '.$row['mobno'].' </a></td></tr>';
		
		/*
		//text to insert in file
		$textCVF = "\nBEGIN:VCARD";
		$textCVF .= "BDAY;VALUE=DATE:2018-03-08";
		$textCVF .= "VERSION:3.0";
		$textCVF .= "N:".$row['name'].";TB";
		$textCVF .= "FN:".$row['name']."";
		$textCVF .= "ORG:Microsoft Corporation";
		$textCVF .= "TEL;TYPE=WORK,MSG:+91-".$row['mobno']."";
		$textCVF .= "END:VCARD";
		
		file_put_contents('test.csv', $textCVF.PHP_EOL , FILE_APPEND | LOCK_EX);
		*/
	}
	echo "</table>";
	echo '<iframe src="http://www.google.co.in" name="frm" style="position:fixed; top:20px; left:500px;width:70%;height:750px;"></iframe>';

	echo "</body>";

?>