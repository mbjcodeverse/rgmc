<?php

require_once "../../../controllers/home.controller.php";
require_once "../../../models/home.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printInventoryInfo{

public $start_date;
public $from_date;
public $end_date;
public $generatedby;
public function getInventoryInfo(){
  $start_date = $this->start_date;
  $from_date = $this->from_date;
  $end_date = $this->end_date;
  $generatedby = $this->generatedby;
  $inventory = (new ControllerHome)->ctrPrintStockMatrix($start_date, $from_date, $end_date);

  $asof_date = substr($end_date,5,2)."/".substr($end_date,8,2)."/".substr($end_date,0,4);

  $itemPostedby = "empid";
  $valuePostedby = $generatedby;
  $answerPostedby = (new ControllerEmployees)->ctrShowEmployees($itemPostedby, $valuePostedby);

  if ($answerPostedby['mi']!=''){
    $posted_by = $answerPostedby['fname'].' '.$answerPostedby['mi'].'. '.$answerPostedby['lname']; 
  }else{
    $posted_by = $answerPostedby['fname'].' '.$answerPostedby['lname'];
  } 

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
//   $pdf->AddPage();
  $pdf->AddPage('L', 'LEGAL');

  $header = <<<EOF
    <table>
      <tr>
        <td style="width:950px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON Goldplast Manufacturing Corporation</td> 
      </tr> 

      <tr>
        <td style="width:950px;text-align:center;font-size:1.1em;font-weight:bold;">INVENTORY SUMMARY REPORT</td> 
      </tr>

      <tr>
        <td style="width:880px;text-align:right;font-size:11px;">As of:</td>
        <td style="width:75px;text-align:left;font-size:11px;">&nbsp;$asof_date</td>                
      </tr>    

      <tr style="background-color:white;">
        <td style="width:5px;"></td>
        <td style="border: 1px solid #666;width:135px;text-align:left;font-size:9px;">&nbsp;&nbsp; Category</td>
        <td style="border: 1px solid #666;width:50px;text-align:right;font-size:9px;">Item ID &nbsp;&nbsp;</td> 
        <td style="border: 1px solid #666;width:240px;text-align:left;font-size:9px;">&nbsp;&nbsp; Description</td>            
        <td style="border: 1px solid #666;width:48px;text-align:center;font-size:9px;">Meas</td>  
        <td style="border: 1px solid #666;width:55px;text-align:right;font-size:9px;">Beginning &nbsp;&nbsp;</td>
        <td style="border: 1px solid #666;width:55px;text-align:right;font-size:9px;">Purchases &nbsp;&nbsp;</td>   
        <td style="border: 1px solid #666;width:55px;text-align:right;font-size:9px;">Returns &nbsp;&nbsp;</td>   
        <td style="border: 1px solid #666;width:55px;text-align:right;font-size:9px;color:blue;">Inbound &nbsp;&nbsp;</td> 
        <td style="border: 1px solid #666;width:55px;text-align:right;font-size:9px;color:red;">Releases &nbsp;&nbsp;</td>  
        <td style="border: 1px solid #666;width:55px;text-align:right;font-size:9px;color:green;">STOCK &nbsp;&nbsp;</td> 
        <td style="border: 1px solid #666;width:55px;text-align:right;font-size:9px;">Cost &nbsp;&nbsp;</td> 
        <td style="border: 1px solid #666;width:75px;text-align:right;font-size:9px;">VALUE &nbsp;&nbsp;</td> 
      </tr>                   
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
$total_inventory_value = 0.00;
foreach ($inventory as $key => $value) {
    $itemcode = $value["itemcode"];
    $catdescription = $value["catdescription"];
    $meas1 = strtoupper($value["meas1"]);
    $pdesc = $value["product_display_name"];

    $beginning_qty = $value["beginning_qty"];
    $purchase_qty_total = $value["purchase_qty_total"];
    $return_qty_total = $value["return_qty_total"];
    $inbound = $beginning_qty + $purchase_qty_total + $return_qty_total; 

    $release_qty_total = $value["release_qty_total"];
    $ending_qty = $value["ending_qty"];

    $ucost = $value["ucost"];
    $instock = ($beginning_qty + $purchase_qty_total + $return_qty_total) - $release_qty_total;
    $current_item_value = number_format($instock * $ucost,2);

    $value = $ucost * $instock;
    $total_inventory_value = $total_inventory_value + $value;

    if ($beginning_qty == 0){
        $beginning_qty = '';
    }else{
        $beginning_qty = number_format($beginning_qty,0);
    }

    if ($purchase_qty_total == 0){
        $purchase_qty_total = '';
    }else{
        $purchase_qty_total = number_format($purchase_qty_total,0);
    }

    if ($return_qty_total == 0){
        $return_qty_total = '';
    }else{
        $return_qty_total = number_format($return_qty_total,0);
    }

    if ($inbound == 0){
        $inbound = '';
    }else{
        $inbound = number_format($inbound,0);
    }

    if ($release_qty_total == 0){
        $release_qty_total = '';
    }else{
        $release_qty_total = number_format($release_qty_total,0);
    }

    if ($instock == 0){
        $instock = '';
        $ucost = '';
        $value = '';
    }else{
        $instock = number_format($instock,0);
        $ucost = number_format($ucost,2);
        $value = number_format($value,2);
    }

    // $total_cost = number_format($value["total_cost"],2);

    $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:5px;"></td>
                <td style="width:135px;text-align:left;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp; $catdescription</td>
                <td style="width:50px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;">$itemcode</td> 
                <td style="width:240px;text-align:left;font-size:8px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp; $pdesc</td>
                <td style="width:48px;text-align:center;font-size:8px;border-right: 1px solid black;border-left: 1px solid black;">$meas1</td>  
                <td style="width:55px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;">$beginning_qty</td> 
                <td style="width:55px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;">$purchase_qty_total</td> 
                <td style="width:55px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;">$return_qty_total</td>
                <td style="width:55px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;color:blue;">$inbound</td>
                <td style="width:55px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;color:red;">$release_qty_total</td>
                <td style="width:55px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;color:green;font-weight:bold;">$instock</td>
                <td style="width:55px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;">$ucost</td>
                <td style="width:75px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;">$value</td>
            </tr>                 
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, ''); 
}

  $close_content = <<<EOF
	    <table style="border: none;">
	      <tr>
            <td style="width:5px;"></td>
	        <td style="width:135px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:50px;border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:240px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:48px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:55px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:55px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:55px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:55px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:55px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:55px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:55px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:75px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
	      </tr>
	    </table>
EOF;
  $pdf->writeHTML($close_content, false, false, false, false, '');

  $inventory_value = number_format($total_inventory_value,2);
  $total_content = <<<EOF
	    <table style="border: none;">
	      <tr>
            <td style="width:5px;"></td>
	        <td style="width:748px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;font-size:1.3em;text-align:right;">TOTAL INVENTORY VALUE</td>
            <td style="width:185px;border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;font-size:1.3em;text-align:right;">$inventory_value</td>
	      </tr>
	    </table>
EOF;
  $pdf->writeHTML($total_content, false, false, false, false, '');

  $footer = <<<EOF
     <table style="border: none;">
       <tr>
         <td style="width:545px;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:5px;"></td>
         <td style="width:202px;font-size:11px;">Generated by:</td>         
       </tr>
       <tr>
         <td style="width:545px;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:5px;"></td>
         <td style="width:125px;font-size:11px;">$posted_by</td>         
       </tr>       
     </table>
EOF;
  $pdf->writeHTML($footer, false, false, false, false, '');  


  $pdf->Output('inventory_print.pdf', 'I');
 }
}

$inventoryValue = new printInventoryInfo();
$inventoryValue -> start_date = $_GET["start_date"];
$inventoryValue -> from_date = $_GET["from_date"];
$inventoryValue -> end_date = $_GET["end_date"];
$inventoryValue -> generatedby = $_GET["generatedby"];
$inventoryValue -> getInventoryInfo();