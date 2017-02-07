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
$pdf->SetFont('Arial','',15);
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

if( isset ($_GET["and"])){
	// array_push($div_decimal, 0.8);
	array_push($ops, '&');
}
if( isset ($_GET["or"])){
	array_push($ops, '|');
}
if( isset ($_GET["xor"])){
	array_push($ops, '^');
}

if( isset ($_GET["AnswerKey"])){
	if( isset ($_GET["move_arrow"])){
		$dem_moving_decimal=1;
	}
	$include_answer= 1;
}

$initX=$pdf->GetX();
$initY=$pdf->GetY();
$x1=25;
$x2=60;
$op=$ops[0];
$op_str='';
$each=array();
$anwers=array();
for($i=0;$i<15;$i++){
	$rand_op=rand(0,2);
	$op=$ops[$rand_op%count($ops)];
	$rand_num = rand(1025, 2056);
	$rand_num2 = rand(1025, 2056);
	$str = base_convert($rand_num, 10, 2);
	$str2 = base_convert($rand_num2, 10, 2);
	if($i%5==0 && $i!=0){
		$pdf->SetXY($initX,$initY);
		$initX=$initX+50;
		$x1=$x1+50;
		$x2=$x2+50;
	}
	if($op=='&'){
		$op_str='AND';
		$s = $str & $str2;
	}else if($op=='|'){
		$op_str='OR';
		$s = $str | $str2;
	}else if($op=='^'){
		$op_str='XOR';
		$bin1=decbin($rand_num);
		$bin2=decbin($rand_num2);
		$xor=$rand_num ^ $rand_num2;
		// $s=base_convert($xor, 10, 2);
		$s=decbin($xor);
		// $pdf->Cell(0,0,'Answer Key '.decbin($xor),0,1);
		// $or=$str|$str2;
		// $s = $str & $str2;
		// for ($i=0; $i < strlen($s); $i++) { 
		// 	if($s[$i]==0){
		// 		$s[$i]=1;
		// 	}else{
		// 		$s[$i]=0;
		// 	}
		// }		
		// $s = $s & $or;
	}
	$each=array();
	array_push($each,$str);
	array_push($each,$str2);
	array_push($each,$s);
	array_push($each,$op_str);
	array_push($anwers,$each);
	// unset($each);

	$pdf->SetLineWidth(0.1);
	$pdf->SetXY($initX,$pdf->GetY());
	$pdf->Cell(0,10,' '.$str,0,1);
	$pdf->SetXY($initX,$pdf->GetY());
	$pdf->Cell(0,10,' '.$str2.' '.$op_str,0,1);
	$pdf->SetXY($initX,$pdf->GetY());
	$pdf->SetLineWidth(0.5);
	$pdf->Line($x1,$pdf->GetY(),$x2,$pdf->GetY());
	$pdf->Cell(0,10,' ___________ ',0,1);
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
	for($i=0;$i<15;$i++){	
		$sss='';
		$str=$anwers[$i][0];
		$str2=$anwers[$i][1];
		$s=$anwers[$i][2];
		$op_str=$anwers[$i][3];
		if($i%5==0 && $i!=0){
			$pdf->SetXY($initX,$initY);
			$initX=$initX+50;
			$x1=$x1+50;
			$x2=$x2+50;
		}
		// $pdf->PDF_setlinewidth(0.1);
		$pdf->SetXY($initX,$pdf->GetY());
		$pdf->Cell(0,10,' '.$str,0,1);
		$pdf->SetXY($initX,$pdf->GetY());
		$pdf->Cell(0,10,' '.$str2.' '.$op_str,0,1);
		$pdf->SetXY($initX,$pdf->GetY());
		$pdf->SetLineWidth(0.5);
		if($op_str=='XOR'){
			$pdf->SetXY($initX,$pdf->GetY());
			$pdf->Line($x1,$pdf->GetY(),$x2,$pdf->GetY());
			$spaces=11-strlen($s);
			for ($k=0; $k < $spaces; $k++) { 
				$sss=$sss.'0';	
			}
			$pdf->SetXY($initX,$pdf->GetY());	
			$pdf->Cell(0,10,$sss.''.$s,0,0);
			$pdf->Ln(13);	

		}else{
			$pdf->Line($x1,$pdf->GetY(),$x2,$pdf->GetY());
			$pdf->Cell(0,10,' '.$s,0,1);
			$pdf->Ln(13);
		}
	}
}


$pdf->Output();

?>
