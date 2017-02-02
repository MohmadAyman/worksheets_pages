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
// Memo line
// Memo line
if( isset ($_GET["memo_line"])){
	$memo_line= $_GET["memo_line"];
	if(strlen($memo_line)>85){
		$pdf->Cell(10,0,substr($memo_line,0,85),0,0);
		$pdf->Ln(10);
		$pdf->Cell(10,0,substr($memo_line,85,85),0,1);
	}else{
		$pdf->Cell(10,0,$memo_line,0,0);
		$pdf->Ln(10);
	}
}

if( isset ($_GET["second_question"])){
	$second_question= $_GET["second_question"];
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
$percent = array(1, 10, 100);

$pdf->Ln(10);

//////// NEW
$shuffle=0;
$gaps=0;
$lowest_terms=0;

if( isset ($_GET["shuffle"])){
	$shuffle = 	$_GET["shuffle"];
}
if( isset ($_GET["gaps"])){
	$gaps= $_GET["gaps"];
}
if( isset ($_GET["lowest_terms"])){
	$lowest_terms= $_GET["lowest_terms"];
}

if( isset ($_GET["AnswerKey"])){
	if( isset ($_GET["move_arrow"])){
		$dem_moving_decimal=1;
	}
	$include_answer= 1;
}

// e.g. x/y 
$nom=array();
$dom=array();


$nomAnswers=array();
$domAnswers=array();

function generate($rand_num_1, $rand_num_2)
{
	$rand_num_2 = rand(1, 50);
	$rand_num_1 = rand(1, 10);
}
for($i=1;$i<=10;$i++){
	unset($nom);
	unset($dom);
	$nom=array();
	$dom=array();

	$rand_num_1 = rand(1, 10);
	$rand_num_2 = rand(1, 50);
	while($rand_num_2%$rand_num_1!=0){
		$rand_num_1 = rand(2, 10);
		$rand_num_2 = rand(1, 50);
	}
	$ratio = $rand_num_2/$rand_num_1;

	if($gaps){
		array_push($nom, $rand_num_1,$rand_num_1+2,$rand_num_1+4,$rand_num_1+6);
		array_push($dom, $rand_num_2, ($rand_num_1+2)*$ratio,($rand_num_1+4)*$ratio,($rand_num_1+6)*$ratio);
	}else{
		array_push($nom, $rand_num_1, $rand_num_1+1,$rand_num_1+2,$rand_num_1+3);
		array_push($dom, $rand_num_2, $rand_num_2+1,$rand_num_2+2,$rand_num_2+3);
	}


	if($shuffle){
		// $shuffleHelper = rand(1, 3);
		$count = count($nom);
		$order = range(1, $count);
		shuffle($order);
		array_multisort($order, $nom, $dom);
	}

	// push answers
	array_push($nomAnswers, $nom);
	array_push($domAnswers, $dom);
	
	$spacingHelper = rand(1, 3);
	if($lowest_terms){
		if($spacingHelper==1){
			$nom[1]='__';
			$nom[2]='__';
			$dom[3]='__';
		}else{
			$dom[1]='__';
			$dom[2]='__';
			$nom[3]='__';
		}
		$pdf->Cell(0,10,'' .$nom[0].' : '.$dom[0].' = '. $nom[1].' : '.$dom[1].' = '.$nom[2].' : '.$dom[2].' = '.$nom[3].' : '.$dom[3],0,1);
	}else{
		if($spacingHelper==1){
			$nom[0]='__';
			$nom[2]='__';
			$dom[3]='__';
		}else if($spacingHelper==2){
			$dom[0]='__';
			$dom[2]='__';
			$nom[1]='__';
		}else{
			$nom[0]='__';
			$dom[3]='__';
			$nom[1]='__';
		}
		$pdf->Cell(0,10,'' .$nom[0].' : '.$dom[0].' = '. $nom[1].' : '.$dom[1].' = '.$nom[2].' : '.$dom[2].' = '.$nom[3].' : '.$dom[3],0,1);
	}
	$current_y=$pdf->GetY();
	$pdf->SetLineWidth(0.1);
	$pdf->Line(20,$current_y-2,190,$current_y-2);
	$pdf->Ln(13);
	// array_push($answers,($rand_num*$percent[$j]/100));
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
		if(strlen($memo_line)>85){
			$pdf->Cell(10,0,substr($memo_line,0,85),0,0);
			$pdf->Ln(10);
			$pdf->Cell(10,0,substr($memo_line,85,85),0,1);
			$pdf->Ln(10);
		}else{
			$pdf->Cell(10,0,$memo_line,0,0);
			$pdf->Ln(10);
		}
	}
	for ($i=0; $i < 10; $i++) { 
		$pdf->Cell(0,10,'' .$nomAnswers[$i][0].' : '.$domAnswers[$i][0].' = '. $nomAnswers[$i][1].' : '.$domAnswers[$i][1].' = '.$nomAnswers[$i][2].' : '.$domAnswers[$i][2].' = '. $nomAnswers[$i][3].' : '.$domAnswers[$i][3],0,1);		
				$current_y=$pdf->GetY();
		$pdf->Line(20,$current_y-3,190,$current_y-3);

		$pdf->Ln(13);
	}
}


$pdf->Output();

?>
<html>
<body>


</body>
</html>