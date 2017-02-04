
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
    function BasicTable($data, $rand)
    {
        $rand_number = rand(0, 9);
        $data[$rand_number][0]='__';
        $data[$rand_number][1]='__';
        $this->Cell(50,12,'Fraction',0,0,'C');
        $this->Cell(50,12,'Decimal',0,0,'C');
        $this->Cell(50,12,'Percent',0,0,'C');
        $this->Ln();
        foreach($data[0] as $row)
        {
            if($rand==3){
                $rand_num = rand(0, 2);
                if($rand_num==1){
                    $row[0]='__';
                }else if($rand_num==2){
                    $row[1]='__';
                    $row[0]='__';
                }else{
                    $row[2]='__';
                }
            }elseif ($rand==1) {
                $rand_num = rand(0, 2);
                if($rand_num==1){
                    $row[0]='__';
                }else if($rand_num==2){
                    $row[1]='__';
                 }else{
                    $row[2]='__';
                }
            }
            elseif ($rand==2) {
                $rand_num = rand(0, 2);
                if($rand_num==1){
                    $row[1]='__';
                    $row[0]='__';
                }else if($rand_num==2){
                    $row[2]='__';
                    $row[1]='__';
                 }else{
                    $row[1]='__';
                    $row[2]='__';
                }
            }
            $this->Cell(50,12,$row[0],1,0,'C');
            $this->Cell(50,12,$row[1],1,0,'C');
            $this->Cell(50,12,$row[2].'%',1,0,'C');



            $this->Ln();
        }
    }
    // Simple table
    function BasicAnswerTable($data)
    {
        $this->Cell(50,12,'Fraction',0,0,'C');
        $this->Cell(50,12,'Decimal',0,0,'C');
        $this->Cell(50,12,'Percent',0,0,'C');
        $this->Ln();
        foreach($data as $row)
        {
            $this->Cell(50,12,$row[0],1,0,'C');
            $this->Cell(50,12,$row[1],1,0,'C');
            $this->Cell(50,12,$row[2].'%',1,0,'C');
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

$percents=array();

// options
if( isset ($_GET["memo_line"])){
    $memo_line= $_GET["memo_line"];
}

$decimals=array();
$percents_all=array();
if( isset ($_GET["render"])){
        $render= $_GET["render"];
        if($render=='truncate'){
            array_push($percents, '12','33','37','62','66');
        }else if($render=='truncate_dec'){
            array_push($percents, '12.5','33.3','37.5','62.5','66.6');
        }elseif($render=='round'){
            array_push($percents, '13','33','38','63','67');
        }else if($render=='round_dec'){
            array_push($percents, '12.5','33.3','37.5','62.5','66.7');
        }else if ($render=='fraction') {
            array_push($percents, '12 1/2','33 1/3','37 1/2','62 1/2','66 2/3');   
        }
}
if( isset ($_GET["blank"])){
    $blank= $_GET["blank"];
}
if( isset ($_GET["random"])){
    $random= $_GET["random"];
}
if( isset ($_GET["language"])){
    $language= $_GET["language"];
}
if( isset ($_GET["include3"])){
    $fraction_array = array('1/20','1/10','1/5','1/4','3/10','1/3','4/10','1/2','3/5','2/3','7/10','3/4','4/5','9/10');
    $decimals = array('0.05','0.1','0.2','0.25','0.3','0.33','0.4','0.5','0.66','0.7','0.75','0.8','0.9');
    $percents_all=array('5','10','20','25','30',$percents[1],'40','50',$percents[4],'70','75','80','90');
}else if( isset ($_GET["include8"])){
    $fraction_array = array('1/20','1/10','1/8','1/5','1/4','3/10','4/10','1/2','3/5','5/8','7/10','3/4','4/5','9/10');
    $decimals = array('0.05','0.1','.,125','0.2','0.25','0.3','0.375','0.4','0.5','0.625','0.7','075','0.8','0.9');
    $percents_all=array('5','10',$percents[0],'20','25','30',$percents[2],'40','50',$percents[3],'70','75','80','90');
}

if( isset ($_GET["include3"])&& isset ($_GET["include8"])){
    $fraction_array = array('1/20','1/10','1/8','1/5','1/4','3/10','1/3','4/10','1/2','3/5','5/8','2/3','7/10','3/4','4/5','9/10');
    $decimals = array('0.05','0.1','0.125','0.2','0.25','0.3','0.33','0.375','0.4','0.5','0.625','0.66','0.7','0.75','0.8','0.9');
    $percents_all=array('5','10',$percents[0],'20','25','30',$percents[1],$percents[2],'40','50',$percents[3],$percents[4],'70','75','80','90');
}else if( !isset ($_GET["include3"]) && !isset ($_GET["include8"])){
    $fraction_array = array('1/20','1/10','1/5','1/4','3/10','4/10','1/2','3/5','7/10','3/4','4/5','9/10');
    $decimals = array('0.05','0.1','0.2','0.25','0.3','0.4','0.5','0.7','075','0.8','0.9');
    $percents_all=array('5','10','20','25','30','40','50','70','75','80','90');
}

$rows=array();
$count = count($fraction_array);

if($random){
    $count = count($fraction_array);
    $order = range(1, $count);
    shuffle($order);
    array_multisort($order, $fraction_array, $decimals,$percents_all);
}

for ($j=0; $j < $count; $j++) { 
    array_push($rows, array($fraction_array[$j],$decimals[$j],$percents_all[$j]));
}
$data = array($rows);
array_push($answers, $data);
$pdf->BasicTable($data,$blank);

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

    $pdf->Ln(20);

    foreach ($answers as $table) {
        $pdf->BasicAnswerTable($table[0]);        
        $pdf->Ln();
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