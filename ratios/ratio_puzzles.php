
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

    // Load data
    function LoadData($file)
    {
        // Read file lines
        $lines = file($file);
        $data = array();
        foreach($lines as $line)
            $data[] = explode(';',trim($line));
        return $data;
    }

    // Simple table
    function BasicTable($data, $xpos, $ypos)
    {
        $this->SetXY($xpos,$ypos);
        $i=$data[0][0]*$data[0][2];
        $i2=$data[0][1]*$data[0][2];
        $i3=$data[0][1]*$data[0][3];
        $i4=$data[0][0]*$data[0][3];
        // $rand_number = rand(0, 9);
        // $data[$rand_number][0]='__';
        // $data[$rand_number][1]='__';
        foreach($data as $row)
        {
            $rand_num = rand(0, 3);
            if($rand_num==1){
                $i3='__';
            }else if($rand_num==2){
                $i2='__';
            }else if($rand_num==3){
                $i1="__";
            }else{
                $i='__';
            }
            $this->SetXY($xpos,$this->GetY());
            $this->Cell(40,10,'__'.'     '.'__',0,1,'C');
            // $this->Ln();
            $this->SetXY($xpos,$this->GetY());
            $this->Cell(10,10,'__',0,0);
            $this->Cell(10,10,$i,1,0,'C');
            $this->Cell(10,10,$i2,1,0,'C');
            $this->Cell(10,10,'   '.'__',0,0);

            $this->Ln();
            $this->SetXY($xpos,$this->GetY());
            $this->Cell(10,10,'__',0,0);
            $this->Cell(10,10,$i3,1,0,'C');
            $this->Cell(10,10,$i4,1,0,'C');
            $this->Cell(10,10,'   '.'__',0,0);

            $this->Ln();
            $this->SetXY($xpos,$this->GetY());
            $this->Cell(40,10,'__'.'    '.'__',0,0,'C');
            $this->Ln();
        }
    }
        // Simple table
    function BasicAnswerTable   ($data, $xpos, $ypos)
    {
        $this->SetXY($xpos,$ypos);
        // $rand_number = rand(0, 9);
        // $data[$rand_number][0]='__';
        // $data[$rand_number][1]='__';
        foreach($data as $row)
        {
            // $rand_num = rand(0, 1);
            // if($rand_num){
            //     $row[0]='__';
            // }else{
            //     $row[1]='__';
            // }
            $this->SetXY($xpos,$this->GetY());
            $this->Cell(40,10,$row[0].'    '.$row[1],0,1,'C');
            // $this->Ln();
            $this->SetXY($xpos,$this->GetY());
            $this->Cell(10,10,$row[2],0,0);
            $this->Cell(10,10,$row[0]*$row[2],1,0,'C');
            $this->Cell(10,10,$row[1]*$row[2],1,0,'C');
            $this->Cell(10,10,'   '.$row[2],0,0);

            $this->Ln();
            $this->SetXY($xpos,$this->GetY());
            $this->Cell(10,10,$row[3],0,0);
            $this->Cell(10,10,$row[1]*$row[3],1,0,'C');
            $this->Cell(10,10,$row[0]*$row[3],1,0,'C');
            $this->Cell(10,10,'   '.$row[3],0,0);

            $this->Ln();
            $this->SetXY($xpos,$this->GetY());
            $this->Cell(40,10,$row[0].'   '.$row[1],0,0,'C');
            $this->Ln();
        }
    }
}

$pdf = new PDF();
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
$rows=array();
$up_to=0;
$answers=array();
$xpos_array = array(10,40,70);
$ypos_array = array(30,300);
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

$initx=$xpos=$pdf->GetX();
$ypos=$pdf->GetY();
for ($i=0; $i < 9; $i++) { 
    $rand_num1 = rand(1, 9);
    $rand_num2 = rand(1, 9);
    $rand_num3 = rand(1, 9);
    $rand_num4 = rand(1, 9);

    if($i%3==0){
        $ypos=$pdf->GetY()+20;
        $xpos=$initx;
    }
    // $ypos=$ypos+50;
    $rows=array($rand_num1,$rand_num2,$rand_num3,$rand_num4);

    $data = array($rows);
    array_push($answers, $data);
    $pdf->BasicTable($data, $xpos, $ypos);
    $pdf->Ln();
    $xpos=$xpos+60;
}

if( isset ($_GET["AnswerKey"])){
    $pdf->AddPage();

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
    $k=0;
    $initx=$xpos=$pdf->GetX();
    $ypos=$pdf->GetY();
    // for ($i=0; $i < 10; $i++) { 
        
    // }
    $pdf->Ln(20);
    for ($i=0; $i < 9; $i++) {  
         if($i%3==0){
            $ypos=$pdf->GetY()+20;
            $xpos=$initx;
        }
        $pdf->BasicAnswerTable($answers[$i], $xpos, $ypos);        
        $pdf->Ln();
        $xpos=$xpos+60;       
    }

}

// $data = $pdf->LoadData('countries.txt');
// $pdf->AddPage();

// $pdf->AddPage();
// $pdf->ImprovedTable($header,$data);
// $pdf->AddPage();
// $pdf->FancyTable($header,$data);
$pdf->Output();
?>