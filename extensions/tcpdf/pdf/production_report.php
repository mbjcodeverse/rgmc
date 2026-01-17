<?php

require_once "../../../controllers/productionreport.controller.php";
require_once "../../../models/productionreport.model.php";

require_once "../../../controllers/products.controller.php";
require_once "../../../models/products.model.php";

class printProduction{
 public $start_pdate;
 public $end_pdate;
 public $machine;
 public $pshift;
 public $product;
 public $report_type;

public function getProductionPrinting(){
  $start_pdate = $this->start_pdate;
  $end_pdate = $this->end_pdate;
  $machine = $this->machine;
  $pshift = $this->pshift;
  $product = $this->product;
  $report_type = $this->report_type;
  $production = (new ControllerProductionReport)->ctrShowProductionReport($start_pdate,$end_pdate,$machine,$pshift,$product,$report_type);

  // Date Label
  if ($start_pdate == $end_pdate){
    $production_date = 'Date: '.substr($start_pdate,5,2)."/".substr($start_pdate,8,2)."/".substr($start_pdate,0,4);
  }else{
    $production_date = 'From '.substr($start_pdate,5,2)."/".substr($start_pdate,8,2)."/".substr($start_pdate,0,4).' To '.substr($end_pdate,5,2)."/".substr($end_pdate,8,2)."/".substr($end_pdate,0,4);
  }

  if ($product != ''){
    $id = "id";
    $productid = $product;
    $product = (new controllerProducts)->ctrShowProducts($id, $product);
    $product_name = $product['pdesc']; 
  }else{
    $product_name = '';
  }  

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  // $pdf->AddPage();


if (($report_type == 1)&&($product == '')){
  $pdf->AddPage('L', 'LEGAL');
  $header = <<<EOF
    <table>
      <tr>
        <td style="width:932px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
      </tr>

      <tr>
        <td style="width:932px;text-align:center;font-size:10px;">Purok Paho, Brgy. Felisa, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:932px;text-align:center;font-size:1.2em;font-weight:bold;">OVERALL PRODUCTION TRAIL</td> 
      </tr>   

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:466px;text-align:left;font-size:11px;">$product_name</td>
        <td style="width:466px;text-align:right;font-size:11px;">$production_date</td>
      </tr>      

      <tr style="background-color:#f2f4f7;">
        <td style="border: 1px solid #666;width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Date</td>

        <td style="border: 1px solid #666;width:53px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Mach #</td>

        <td style="border: 1px solid #666;width:53px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Shift</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Tot Kgs &nbsp;&nbsp;&nbsp;</td> 
        
        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Prod Kgs &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">End Kgs &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Dam Kgs &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Was Kgs &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:200px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Product(s)</td>                                                               

        <td style="border: 1px solid #666;width:61px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Tot Wt &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Ave Wt &nbsp;&nbsp;&nbsp;</td>                
      </tr>                   
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
$i = 0;
$prev_prodnumber = '';
$curr_prodnumber = '';

$total_kgs = 0.00;
$prod_kgs = 0.00;
$end_kgs = 0.00;
$damage_kgs = 0.00;
$waste_kgs = 0.00;
$prod_qty = 0.00;
$total_wt = 0.00;

foreach ($production as $key => $value) {
  $i = $i + 1;
  $prodnumber = $value["prodnumber"];
  $pdate = substr($value["pdate"],5,2)."/".substr($value["pdate"],8,2)."/".substr($value["pdate"],0,4);
  $idMachine = $value["idMachine"];
  $pshift = $value["pshift"];
  $totalkgs = number_format($value["totalkgs"],2);
  $prodkgs = number_format($value["prodkgs"],2);
  $endkgs = number_format($value["endkgs"],2);
  $damagekgs = number_format($value["damagekgs"],2);
  $wastekgs = number_format($value["wastekgs"],2);
  $pdesc = $value["pdesc"];
  $qty = number_format($value["qty"],2);
  $tweight = number_format($value["tweight"],2);
  $waverage = number_format($value["waverage"],3);

  if ($i == 0){
    $prev_prodnumber = $prodnumber;
  }else{
    $curr_prodnumber= $prodnumber;
    if ($prev_prodnumber == $curr_prodnumber){
      $pdate = '';
      $idMachine = '';
      $pshift = '';
      $totalkgs = '';
      $prodkgs = '';
      $endkgs = '';
      $damagekgs = '';
      $wastekgs = '';
      $tweight = '';
      $waverage = '';
    }
    $prev_prodnumber = $curr_prodnumber;
  }  

  if ($pdate != ''){
    $total_kgs = $total_kgs + $value["totalkgs"];
    $prod_kgs = $prod_kgs + $value["prodkgs"];
    $end_kgs = $end_kgs + $value["endkgs"];
    $damage_kgs = $damage_kgs + $value["damagekgs"];
    $waste_kgs = $waste_kgs + $value["wastekgs"];
    $total_wt = $total_wt + $value["tweight"];  
  }
  $prod_qty = $prod_qty + $value["qty"];

  $content = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pdate</td>

        <td style="width:53px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$idMachine</td>

        <td style="width:53px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pshift</td>

        <td style="width:70px;text-align:right;font-size:11px;">$totalkgs &nbsp;&nbsp;&nbsp;</td> 
        
        <td style="width:70px;text-align:right;font-size:11px;">$prodkgs &nbsp;&nbsp;&nbsp;</td>

        <td style="width:70px;text-align:right;font-size:11px;">$endkgs &nbsp;&nbsp;&nbsp;</td>

        <td style="width:70px;text-align:right;font-size:11px;">$damagekgs &nbsp;&nbsp;&nbsp;</td>

        <td style="width:70px;text-align:right;font-size:11px;">$wastekgs &nbsp;&nbsp;&nbsp;</td>

        <td style="width:200px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pdesc</td>                                                               

        <td style="width:70px;text-align:right;font-size:11px;">$qty &nbsp;&nbsp;&nbsp;</td>

        <td style="width:70px;text-align:right;font-size:11px;">$tweight &nbsp;&nbsp;&nbsp;</td>

        <td style="width:70px;text-align:right;font-size:11px;">$waverage &nbsp;&nbsp;&nbsp;</td>              
      </tr>                 
    </table>
EOF;
  $pdf->writeHTML($content, false, false, false, false, '');
}

  $a = number_format($total_kgs,2);
  $b = number_format($prod_kgs,2);
  $c = number_format($end_kgs,2);
  $d = number_format($damage_kgs,2);
  $e = number_format($waste_kgs,2);
  $f = number_format($prod_qty,2);
  $g = number_format($total_wt,2);

  $totals = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:75px;text-align:left;font-size:11px;"></td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:53px;text-align:left;font-size:11px;"></td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:53px;text-align:left;font-size:11px;"></td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$a &nbsp;&nbsp;&nbsp;</td> 
        
        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$b &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$c &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$d &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$e &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:200px;text-align:left;font-size:11px;"></td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$f &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$g &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;"></td>              
      </tr>                 
    </table>
EOF;
    $pdf->writeHTML($totals, false, false, false, false, '');
  }else{ /*===============================================================================*/
  $pdf->AddPage('L', 'LEGAL');
  $header = <<<EOF
    <table>
      <tr>
        <td style="width:932px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
      </tr>

      <tr>
        <td style="width:932px;text-align:center;font-size:10px;">Purok Paho, Brgy. Felisa, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:932px;text-align:center;font-size:1.2em;font-weight:bold;">OVERALL PRODUCTION TRAIL</td> 
      </tr>   

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:100px;"></td>
        <td style="width:366px;text-align:left;font-size:12px;"><strong>$product_name</strong></td>
        <td style="width:366px;text-align:right;font-size:11px;">$production_date</td>
      </tr>      

      <tr style="background-color:#f2f4f7;">
        <td style="width:100px;background-color:#ffffff;"></td>

        <td style="border: 1px solid #666;width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Date</td>

        <td style="border: 1px solid #666;width:53px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Mach #</td>

        <td style="border: 1px solid #666;width:53px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Shift</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Tot Kgs &nbsp;&nbsp;&nbsp;</td> 
        
        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Prod Kgs &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">End Kgs &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Dam Kgs &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Was Kgs &nbsp;&nbsp;&nbsp;</td>
                                                               
        <td style="border: 1px solid #666;width:61px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Tot Wt &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:70px;text-align:right;font-size:11px;">Ave Wt &nbsp;&nbsp;&nbsp;</td>                
      </tr>                   
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
$i = 0;
$prev_prodnumber = '';
$curr_prodnumber = '';

$total_kgs = 0.00;
$prod_kgs = 0.00;
$end_kgs = 0.00;
$damage_kgs = 0.00;
$waste_kgs = 0.00;
$prod_qty = 0.00;
$total_wt = 0.00;

foreach ($production as $key => $value) {
  $i = $i + 1;
  $prodnumber = $value["prodnumber"];
  $pdate = substr($value["pdate"],5,2)."/".substr($value["pdate"],8,2)."/".substr($value["pdate"],0,4);
  $idMachine = $value["idMachine"];
  $pshift = $value["pshift"];
  $totalkgs = number_format($value["totalkgs"],2);
  $prodkgs = number_format($value["prodkgs"],2);
  $endkgs = number_format($value["endkgs"],2);
  $damagekgs = number_format($value["damagekgs"],2);
  $wastekgs = number_format($value["wastekgs"],2);
  $pdesc = $value["pdesc"];
  $qty = number_format($value["qty"],2);
  $tweight = number_format($value["tweight"],2);
  $waverage = number_format($value["waverage"],3);

  if ($i == 0){
    $prev_prodnumber = $prodnumber;
  }else{
    $curr_prodnumber= $prodnumber;
    if ($prev_prodnumber == $curr_prodnumber){
      $pdate = '';
      $idMachine = '';
      $pshift = '';
      $totalkgs = '';
      $prodkgs = '';
      $endkgs = '';
      $damagekgs = '';
      $wastekgs = '';
      $tweight = '';
      $waverage = '';
    }
    $prev_prodnumber = $curr_prodnumber;
  }  

  if ($pdate != ''){
    $total_kgs = $total_kgs + $value["totalkgs"];
    $prod_kgs = $prod_kgs + $value["prodkgs"];
    $end_kgs = $end_kgs + $value["endkgs"];
    $damage_kgs = $damage_kgs + $value["damagekgs"];
    $waste_kgs = $waste_kgs + $value["wastekgs"]; 
    $total_wt = $total_wt + $value["tweight"]; 
  }
  $prod_qty = $prod_qty + $value["qty"];

  $content = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="width:100px;"></td>

        <td style="width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pdate</td>

        <td style="width:53px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$idMachine</td>

        <td style="width:53px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pshift</td>

        <td style="width:70px;text-align:right;font-size:11px;">$totalkgs &nbsp;&nbsp;&nbsp;</td> 
        
        <td style="width:70px;text-align:right;font-size:11px;">$prodkgs &nbsp;&nbsp;&nbsp;</td>

        <td style="width:70px;text-align:right;font-size:11px;">$endkgs &nbsp;&nbsp;&nbsp;</td>

        <td style="width:70px;text-align:right;font-size:11px;">$damagekgs &nbsp;&nbsp;&nbsp;</td>

        <td style="width:70px;text-align:right;font-size:11px;">$wastekgs &nbsp;&nbsp;&nbsp;</td>                                                             

        <td style="width:70px;text-align:right;font-size:11px;">$qty &nbsp;&nbsp;&nbsp;</td>

        <td style="width:70px;text-align:right;font-size:11px;">$tweight &nbsp;&nbsp;&nbsp;</td>

        <td style="width:70px;text-align:right;font-size:11px;">$waverage &nbsp;&nbsp;&nbsp;</td>              
      </tr>                 
    </table>
EOF;
  $pdf->writeHTML($content, false, false, false, false, '');
}

  $a = number_format($total_kgs,2);
  $b = number_format($prod_kgs,2);
  $c = number_format($end_kgs,2);
  $d = number_format($damage_kgs,2);
  $e = number_format($waste_kgs,2);
  $f = number_format($prod_qty,2);
  $g = number_format($total_wt,2);

  $totals = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="width:100px;"></td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:75px;text-align:left;font-size:11px;"></td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:53px;text-align:left;font-size:11px;"></td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:53px;text-align:left;font-size:11px;"></td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$a &nbsp;&nbsp;&nbsp;</td> 
        
        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$b &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$c &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$d &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$e &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$f &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;">$g &nbsp;&nbsp;&nbsp;</td>

        <td style="border-top: 1px solid black;border-bottom: 1px solid black;width:70px;text-align:right;font-size:11px;"></td>              
      </tr>                 
    </table>
EOF;
    $pdf->writeHTML($totals, false, false, false, false, '');
  }


// ------------------------------------------------------------
// $num_lines = 0;  
// foreach ($products as $key => $value) {
//   $pitem = "id";
//   $idProduct = $value["prodid"];
//   $product = controllerProducts::ctrShowProducts($pitem, $idProduct);
//   $pdesc = $product['pdesc']; 
//   $qty = $value["qty"];
//   $uprice = number_format($value["uprice"],2);
//   $tamount = number_format($value["tamount"],2);

//   $num_lines = $num_lines + 1;

//   $content = <<<EOF
//     <table style="border: none;">    
//       <tr>
//         <td style="width:80px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$qty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
//         <td style="width:250px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $pdesc</td>
//         <td style="width:100px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$uprice &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
//         <td style="width:115px;text-align:right;font-size:11px;border-right: 1px solid black;">$tamount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                
//       </tr>                 
//     </table>
// EOF;
//   $pdf->writeHTML($content, false, false, false, false, '');
// }


//   $close_content = <<<EOF
// 	    <table style="border: none;">
// 	      <tr>
// 	        <td style="width:80px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
//             <td style="width:250px;border-bottom: 1px solid black;"></td>
//             <td style="width:100px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
//             <td style="width:115px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
// 	      </tr>
// 	    </table>
// EOF;
//   $pdf->writeHTML($close_content, false, false, false, false, ''); 

  $pdf->Output('production_report.pdf', 'I');
 }
}

  $productionForm = new printProduction();
  $productionForm -> start_pdate = $_GET["sdate"];
  $productionForm -> end_pdate = $_GET["edate"];
  $productionForm -> machine = $_GET["machine"];
  $productionForm -> pshift = $_GET["pshift"];
  $productionForm -> product = $_GET["product"];
  $productionForm -> report_type = $_GET["report_type"];
  $productionForm -> getProductionPrinting();
?>