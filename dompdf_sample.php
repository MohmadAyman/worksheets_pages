<?php

// include autoloader
require_once 'dompdf/autoload.inc.php';
// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf('1.0', 'UTF-8');

$html='    
<table style="margin: 50 0 -125 0;width:25%" cellpadding="10">
  <tr>
    <th>&#9733;</th>
  </tr>
   <tr>
    <th>Firstname</th>
  </tr>
</table>

<table  style="margin: 0 0 0 125; width:25%" cellpadding="10" border="1">
  <tr>
    <th>Firstname</th>
    <th>Lastname</th> 
  </tr>
  <tr>
    <td>Jill</td>
    <td>Smith</td> 
</tr>
  <tr>
    <th>Firstname</th>
	<th>Firstname</th>
</tr>
  <tr>
    <th>Firstname</th>
    <th>Lastname</th> 
  </tr>
</table>

<table style="margin: -125 0 0 300; width:25%" cellpadding="10">
  <tr>
    <th>Firstname</th>
  </tr>
   <tr>
    <th>Firstname</th>
  </tr>
</table>';
$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

?>