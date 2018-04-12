<html>
	<head>
	<title>DELIVERY PERSON MASTER</title>
	</head>
	<LINK href='http://circulation.tbdnet/Menu/global.css' rel=stylesheet type=text/css>
<body bgcolor="#7B9595" class='contentfont'>
	<p align='center'>
	<font class='transhead'>%s</font>
	<form method="get" action="/cgi-bin/compdelivery_mast.cgi">
	<div align="center"><b><font class="contenthead"><br><br><br>
	<br>Delivery Person Master</font></b></font></font></b></font><br>
	</div><div align="center"><div align="center">
	<table border=1 bordercolor=#000000 cellpadding=0 cellspacing=0 width=100 height="126">
	<tbody>
	<tr bordercolor=#000000 border="1"><td height="155">
	<div align="center"><table border=0 cols=2 width="97%" >
	<tr><td width="63%" class='contentfont'>Delivery Person Code * </font></td>",company);

		<td width="50"><input TYPE="TEXT" NAME="DPCODE" size="4" maxlength="4">
		<a href="/cgi-bin/compdelivery.cgi" target="_new">
		<font class='A'>LookUp</a></td>

		<tr>
		<td class='contentfont'>Delivery Person Name</td>
		<td> <input TYPE="TEXT" NAME="DPNAME" SIZE="25" MAXLENGTH="40"></td>
		</tr>

		<tr>
		<td class='contentfont'>Location Of Person</td>
		<td><select NAME="location">
		<option value="">Select Location</option> 
		complocation();
		</td></tr>

		<tr><td class='contentfont'>Edition</td>
		<td><select name='edn'>
		edition();
		</select></td></tr>

		<tr>
		<td class='contentfont'>Operator Code </td>
		<td><input type="password" name="password" size="25"                    maxlength="30"></td>
		</tr>
		</table>
		</div>                 <div align='center'><br>

		abutton();

		</table>                 <br>                 </div>                 <div align='center'>                 <div align='center'> <br>

		</div>                 <br>
		</div>

	</form>
</body>
</html>