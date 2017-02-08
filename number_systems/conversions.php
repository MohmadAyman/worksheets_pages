<?php
require('fpdf.php');

class PDF extends FPDF
{
	function Header()
	{
	    global $title;

	    $this->SetLeftMargin(25);
	    $this->SetRightMargin(25);
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
$pdf->AddPage();
// Arial bold 15
$pdf->SetFont('Arial','',16);
$pdf->SetLineWidth(0.5);
$pdf->Line(41,18,80,18);
$pdf->Line(138,18,180,18);
// Position 
$pdf->SetY(16);
$pdf->SetX(25);
// Text color in gray
$pdf->SetTextColor(0);
$pdf->Cell(0,0,'Name ',0,0,'L');
$pdf->SetX(125);
$pdf->Cell(0,0,'Date ',0,1);
$pdf->Ln(13);

// Memo line
if( isset ($_GET["memo_line"])){
	$memo_line= $_GET["memo_line"];
	if(strlen($memo_line)>30){
		$pdf->Cell(10,0,substr($memo_line,0,30),0,0);
		$pdf->Ln(10);
		$pdf->Cell(10,0,substr($memo_line,30,30),0,1);
	}	
}

//////////////////////////////////////

//initilizations
$answers=array();
$rand_nums=array();
$rand_js=array();
$write_as_words= 0;
$include_answer=0;
$dem_moving_decimal=0;
$div_decimal=array(1);

$ops=array();

if( isset ($_GET["base"])){
	// array_push($div_decimal, 0.8);
	$bb = explode(':', $_GET["base"]);

	$b=$bb[0];
	$i2=$bb[2];//last
	$i1=$bb[1];//first
}

if( isset ($_GET["conversion"])){
	$conversion=$_GET["conversion"];
	// 0 from base 10
	$to=$b;
	if($conversion==0){
		$from=10;
		$bbb=$b;

	}else{
		// $to=10;
		$bbb=10;
		$from=$b;
	}
}

if( isset ($_GET["AnswerKey"])){
	$include_answer= 1;
}
$pdf->Cell(0,10,'Convert from base '.$from.' to base '.$bbb,0,1);
$pdf->Ln();

$initX=$pdf->GetX();
$initY=$pdf->GetY();
$x1=25;
$x2=60;
// $op=$ops[0];
$op_str='';
$each=array();
$anwers=array();

// $pdf->Cell(0,10,'Convert from base '.$from.' to base '.$bbb,0,1);

for($i=0;$i<20;$i++){

	$each=array();
	$rand_num = rand($i1, $i2);

	$str = base_convert($rand_num, 10, $to);

	// $pdf->Cell(0,10,' '.$rand_num.'= ___'.$str,0,1);

	if($i%10==0 && $i!=0){
		$pdf->SetXY($initX,$initY);
		$initX=$initX+80;
		$x1=$x1+80;
		$x2=$x2+80;
	}

	// $pdf->SetLineWidth(0.1);
	$pdf->SetXY($initX,$pdf->GetY());
	if($from==10){
		$pdf->Cell(0,10,' ('.$rand_num.')= ____',0,1);
		array_push($each, $rand_num,$str);
		array_push($anwers,$each);
	}else{
		$pdf->Cell(0,10,' ('.$str.')= ____',0,1);
		array_push($each, $str, $rand_num);
		array_push($anwers,$each);
		}
	$pdf->Ln(13);
}

//write answers
if($include_answer){
	$pdf->AddPage();
	$pdf->Ln(4);
	$pdf->Cell(0,0,'Answer Key ',0,1);
	$pdf->Ln(15);
	// Memo line
	if( isset ($_GET["memo_line"])){
		$memo_line= $_GET["memo_line"];
		if(strlen($memo_line)>30){
				$pdf->Cell(10,0,substr($memo_line,0,30),0,0);
				$pdf->Ln(10);
				$pdf->Cell(10,0,substr($memo_line,30,30),0,1);
			}	
	}

	$x1=25;
	$x2=60;
	$initX=$pdf->GetX();
	$pdf->Ln(18);
	for($i=0;$i<20;$i++){	
		$str1=$anwers[$i][0];
		$str2=$anwers[$i][1];
		
		if($i%10==0 && $i!=0){
			$pdf->SetXY($initX,$initY);
			$initX=$initX+80;
			$x1=$x1+80;
			$x2=$x2+80;
		}
		// $pdf->SetLineWidth(0.1);
		$pdf->SetXY($initX,$pdf->GetY());
		$pdf->Cell(0,10,' ('.$str1.')= '.$str2,0,0);
		$pdf->Ln(23);
	}
}


$pdf->Output();

?>
