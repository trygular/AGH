<? include_once("db.php"); ?>
<?
	/* --- PDF */
	/*  */
	if($_POST['Action'] == 'PDFGenerate')
	{
		$text = $_POST["text"];
		$text = str_replace('<_>', " ", $text);	// space
		$text = str_replace('<t>', "	", $text);
		$arrLines = explode("<n>", $text); // array every New line to new index
		//$text = str_replace('_', " ", $text);
				
		
		include("fpdf/fpdf.php");

		$pdf = new FPDF();
		$pdf->AddPage();
		
		$pdf->Rect(5, 5, 200, 287, 'D');

		$yaxis = 5;		//top margin
		$xaxis = 10;		//left margin
		$pdf->SetY($yaxis);
		$pdf->SetX($xaxis);
		
		$pdf->SetFont('Arial','',12);
		
		for($cntLine=0; $cntLine<sizeof($arrLines); $cntLine++)
		{
			$lineCur = $arrLines[$cntLine];

			$yaxis = $yaxis+5;
			$xaxis = 10;
			$pdf->setY($yaxis);
			$pdf->setX($xaxis);
			$pdf->Cell(50,6,$lineCur,0,1,'L');
		}
		
		$filenames = "CustomPDF.pdf";
		$pdf->Output($filenames,'F');
	
		die();
	}
	
?>
	<? include_once('header.php'); ?>
<body>

<div id="page-content">
	<div class="page-box">
		<h3 class="page-title">Custom PDF Generate</h3>
		
		<div class="example-box-wrapper" id='mainform'> 
			<form id="demo-form" class="form-horizontal" data-parsley-validate="" >
				
				<div class="row">
					<div class='col-md-12'>
						<div class="form-group">
							<a class="col-sm-3 btn btn-primary" onclick="PDFGenerate()">GENERATE CUSTOM PDF </a>
							<a class="col-sm-8"></a>
							<a class="col-sm-1 btn btn-primary" href="<?echo $_GET["fromurl"];?>">BACK</a>
						</div>
					</div>
				</div>
					
				<div class="row">
					<div class='col-md-12'>
						<div class="form-group">
								<div class="col-sm-12">
									<textarea id="areatext" class="form-control" style="height:450px;width:100%;" placeholder="Place your text here...!"></textarea>
								</div>
							</label>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
	<? include_once('bottom.php'); ?>
</body>	 
<script type='text/javascript'>	
$('#areatext').focus();
function PDFGenerate()
{
	var pdfText = $('#areatext').val(); //document.getElementById('txta').innerHTML;
	
	if(pdfText == '' || pdfText == null)
	{
		alert("Enter you text in the area for text..!");
		$('#areatext').focus();
		return;
	}
	
	pdfText = pdfText.replace(' ', '<_>');
	pdfText = pdfText.replace(/\t/g, '<t>');
	pdfText = pdfText.replace(/\r?\n/g, '<n>');

	$.ajax({
		url:"CustomPDF.php",
		data:"Action=PDFGenerate&text="+pdfText,
		type:"post",
		success:function(output){
			/* alert(output); return;*/
			var win = window.open('CustomPDF.pdf', '_blank');
			if (win){
					win.focus();
			} else{
					alert('Please allow popups for this website');
			}
		}
	});	
}
</script>

 
<script type="text/javascript" src="js1/jquery.js"></script>		
<script type="text/javascript" src="js1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="jqauto/jquery.autocomplete.css" />
<script type="text/javascript" src="jqauto/jquery.autocomplete.js"></script>
