<?php
require('fpdf.php');

class PDF extends FPDF
{
function Header()
{
    global $title;

    $this->SetLeftMargin(25);
    $this->SetRightMargin(25);
    
    // Arial bold 15
    $this->SetFont('Arial','B',15);


    $this->SetLineWidth(0.5);
    $this->Line(41,18,80,18);
    $this->Line(138,18,180,18);

    // Position at 1.5 cm from bottom
    $this->SetY(16);
    $this->SetX(25);
    // Text color in gray
    $this->SetTextColor(0);
    // Page number
    $this->Cell(0,0,'Name ',0,0,'L');
    $this->SetX(125);
    $this->Cell(0,0,'Date ',0,0);


}

function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Text color in gray
    $this->SetTextColor(128);
    // Page number
    $this->Cell(0,10,'TheMathWorksheetSite.com ',0,0,'R');
}

}

$pdf = new PDF();
$title = 'Name';
$pdf->SetTitle($title);
$pdf->SetFont('Arial','B',15);

$pdf->AddPage();

$answers=array();
$rand_nums=array();
$rand_js=array();
$write_as_words= 0;
$include_answer=0;
$dem_moving_decimal=0;
$div_decimal=array(1);
$percent = array(1, 10, 100);

if( isset ($_GET["decimal"])){
	$decimal = 	$_GET["decimal"];
	array_push($div_decimal, 0.8);
}
if( isset ($_GET["percent_1000"])){
	$use_1000= $_GET["percent_1000"];
	array_push($percent,1000);
}
if( isset ($_GET["percent_point_1"])){
	$use_point_1= $_GET["percent_point_1"];
	array_push($percent,0.1);
}

if( isset ($_GET["as_words"])){
	$write_as_words= 1;
}
if( isset ($_GET["AnswerKey"])){
	if( isset ($_GET["move_arrow"])){
		$dem_moving_decimal=1;
	}
	$include_answer= 1;
}
if( isset ($_GET["memo_line"])){
	$memo_line= $_GET["memo_line"];
}

$pdf->Ln(15);
for($i=1;$i<=10;$i++){
	$rand_num = rand(1, 100)*$div_decimal[$i%count($div_decimal)];
	$j=$i%count($percent);
	$pdf->SetLineWidth(0.1);
	$pdf->Cell(0,10,''.$rand_num .'*'.$j.' =',0,1);
	$pdf->Ln(15);
	array_push($answers,($rand_num*$j)/100);
	array_push($rand_nums, $rand_num);
	array_push($rand_js, $j);
}

//underlines
for($i=2;$i<=11;$i++){
	$pdf->Line(20,25*$i,190,25*$i);
}

//write answers
if($include_answer){
	$pdf->AddPage();

	for($i=0;$i<=9;$i++){

		$pdf->Ln(10);
		$pdf->Cell(0,10,''.$rand_nums[$i] .'*'.$rand_js[$i].' =',0,0);
		$pdf->SetX(50);
		$pdf->Cell(0,10,$answers[$i],0,0);
		$pdf->Ln(15);
	}
	//underlines
	for($i=2;$i<=11;$i++){
		$pdf->Line(20,25*$i,190,25*$i);
	}
}


$pdf->Output();

?>
<html>
<body>


</body>
</html>