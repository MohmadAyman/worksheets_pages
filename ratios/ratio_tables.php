
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
    function BasicTable($header, $data, $xpos, $ypos)
    {
        $this->SetXY($xpos,$ypos);
        // $this->SetY($ypos);
        // Header
        // foreach($header as $col)
        //     $this->Cell(15,11,$col,1,0);
        // $this->Ln();
        // Data
        foreach($data as $row)
        {
            $this->SetXY($xpos,$this->GetY());
            foreach($row as $col)
                $this->Cell(15,10,$col,1,0);
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

$xpos_array = array(10,40,70);
$ypos_array = array(30,300);
// Memo line
if( isset ($_GET["memo_line"])){
    $memo_line= $_GET["memo_line"];
    if(strlen($memo_line)>30){
        $pdf->Cell(10,0,substr($memo_line,0,30),0,0);
        $pdf->Ln(10);
        $pdf->Cell(10,0,substr($memo_line,30,30),0,1);
    }   
}

if( isset ($_GET["up_to"])){
    $up_to =  $_GET["up_to"];
}

if($up_to){
        $rand_num_1 = rand(2, 9);    
        $rand_num_2 = rand(2, 9);
    }else{
        $rand_num_1 = rand(2, 12);
        $rand_num_2 = rand(2, 12);
}

$initx=$xpos=$pdf->GetX();
$ypos=$pdf->GetY();
for ($i=0; $i < 10; $i++) {
    if($up_to){
            $rand_num_1 = rand(2, 9);    
            $rand_num_2 = rand(2, 9);
        }else{
            $rand_num_1 = rand(2, 12);
            $rand_num_2 = rand(2, 12);
    }
    if($i==5){
        $ypos=$pdf->GetY();
        $xpos=$initx;
    }
    unset($rows);
    $rows=array();
    $row_1=0;
    $ratio = $rand_num_2/$rand_num_1;
    while($ratio==1){
        if($up_to){
                $rand_num_1 = rand(2, 9);    
                $rand_num_2 = rand(2, 9);
            }else{
                $rand_num_1 = rand(2, 12);
                $rand_num_2 = rand(2, 12);
        }
        $ratio = $rand_num_2/$rand_num_1;
    }

    $header=array($rand_num_1,$rand_num_2);
    for ($j=0; $j < 10; $j++) { 
        $row_1=$row_1+$rand_num_1;
        array_push($rows, array($row_1,$ratio*$row_1));
    }
    $data = array($rows);
    $pdf->BasicTable($header,$rows, $xpos, $ypos);
    $pdf->Ln();
    $xpos=$xpos+35;
}
// $data = $pdf->LoadData('countries.txt');
// $pdf->AddPage();

// $pdf->AddPage();
// $pdf->ImprovedTable($header,$data);
// $pdf->AddPage();
// $pdf->FancyTable($header,$data);
$pdf->Output();
?>