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
$pdf->SetFont('Arial','',13);
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


//////////////////////////////////////


//initilizations
$answers=array();
$rand_nums=array();
$rand_js=array();
$write_as_words= 0;
$include_answer=0;
$dem_moving_decimal=0;
$div_decimal=array(1);
$percent = array(1, 10, 100);

$decimal_factors = 	0;
$percent_decimal = 	0;
$algebraic=0;

$pdf->Ln(10);

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

$univalue="\u{058F}";
$univalue2="\u{2207}";
$pdf->Cell(0,10,' '. $univalue2.' '.$univalue,0,1);


for($i=1;$i<=10;$i++){
	$rand_num = rand(1, 100)*$div_decimal[$i%count($div_decimal)];
	$j=$i%count($percent);
	$pdf->SetLineWidth(0.1);


	if($write_as_words){
		$pdf->Cell(0,10,' What is '. $im.' percent of '.$rand_num.' ?',0,1);
		// UnderLine
		$current_y=$pdf->GetY();
		$pdf->Line(20,$current_y-3,190,$current_y-3);
	}else{

	}

	$pdf->Ln(13);
	array_push($answers,($rand_num*$percent[$j]/100));
	array_push($rand_nums, $rand_num);
	array_push($rand_js, $percent[$j]);
}

//write answers
if($include_answer){
	$pdf->AddPage();
	$pdf->Ln(4);
	$pdf->Cell(0,0,'Answer Key ',0,1);
	$pdf->Ln(10);
	// Memo line
	if( isset ($_GET["memo_line"])){
		$memo_line= $_GET["memo_line"];
		if(strlen($memo_line)>30){
				$pdf->Cell(10,0,substr($memo_line,0,30),0,0);
				$pdf->Ln(10);
				$pdf->Cell(10,0,substr($memo_line,30,30),0,1);
			}	
	}

	for($i=0;$i<=9;$i++){
		$pdf->Ln(13);
		
		if($dem_moving_decimal)
		{
			$rand_js[$i]=$rand_js[$i]/100;
			$pdf->Cell(0,10,' '.$rand_nums[$i] .'*'.$rand_js[$i].'% = '.$rand_nums[$i] .'*'.$rand_js[$i].' = ' .$answers[$i],0,1);
		}else{	
			$pdf->Cell(0,10,' '.$rand_nums[$i] .'*'.$rand_js[$i].'% = '.$answers[$i],0,1);
		}

		// UnderLines
		$current_y=$pdf->GetY();
		$pdf->Line(20,$current_y-3,190,$current_y-3);

	}
}


$pdf->Output();

?>
<html>
<body>


</body>
</html>