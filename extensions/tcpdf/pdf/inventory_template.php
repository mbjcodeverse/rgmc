<?php

require_once "../../../controllers/home.controller.php";
require_once "../../../models/home.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printTechnicalInventoryTemplate{

public $start_date;
public $from_date;
public $end_date;
public $generatedby;
public function getTechnicalInventoryTemplatePrinting(){
  $start_date = $this->start_date;
  $from_date = $this->from_date;
  $end_date = $this->end_date;
  $generatedby = $this->generatedby;
  $inventory = (new ControllerHome)->ctrShowInventoryTechnicalTemplate($start_date, $from_date, $end_date);

//   $asof_date = substr($from_date,5,2)."/".substr($from_date,8,2)."/".substr($from_date,0,4);

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
  $pdf->AddPage();

  $header = <<<EOF
    <table>
      <tr>
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON Goldplast Manufacturing Corporation</td> 
      </tr> 

      <tr>
        <td style="width:540px;text-align:center;font-size:1.1em;font-weight:bold;">INVENTORY TEMPLATE</td> 
      </tr>

      <tr>
        <td style="width:450px;text-align:right;font-size:11px;">As of:</td>
        <td style="width:75px;text-align:left;font-size:11px;">&nbsp;___________</td>                
      </tr>    

      <tr style="background-color:white;">
        <td style="width:5px;"></td>
        <td style="border: 1px solid #666;width:135px;text-align:left;font-size:9px;">&nbsp;&nbsp; Category</td>
        <td style="border: 1px solid #666;width:50px;text-align:right;font-size:9px;">Item ID &nbsp;&nbsp;</td> 
        <td style="border: 1px solid #666;width:240px;text-align:left;font-size:9px;">&nbsp;&nbsp; Description</td>            
        <td style="border: 1px solid #666;width:48px;text-align:center;font-size:9px;">Meas</td>  
        <td style="border: 1px solid #666;width:50px;text-align:right;font-size:9px;">Count &nbsp;&nbsp;</td>          
      </tr>                   
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
foreach ($inventory as $key => $value) {
    $itemcode = $value["itemcode"];
    $catdescription = $value["catdescription"];
    $meas1 = strtoupper($value["meas1"]);
    $pdesc = $value["product_display_name"];
    $beginning_qty = $value["beginning_qty"];
    $purchase_qty_total = $value["purchase_qty_total"];
    $return_qty_total = $value["return_qty_total"];
    $release_qty_total = $value["release_qty_total"];
    $ending_qty = $value["ending_qty"];

    $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:5px;"></td>
                <td style="width:135px;text-align:left;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp; $catdescription</td>
                <td style="width:50px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;">$itemcode</td> 
                <td style="width:240px;text-align:left;font-size:8px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp; $pdesc</td>
                <td style="width:48px;text-align:center;font-size:8px;border-right: 1px solid black;border-left: 1px solid black;">$meas1</td>  
                <td style="width:50px;text-align:right;font-size:9px;border-right: 1px solid black;border-left: 1px solid black;"></td> 
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
            <td style="width:50px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
	      </tr>
	    </table>
EOF;
  $pdf->writeHTML($close_content, false, false, false, false, '');

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


  $pdf->Output('inventory_template.pdf', 'I');
 }
}

$inventoryForm = new printTechnicalInventoryTemplate();
$inventoryForm -> start_date = $_GET["start_date"];
$inventoryForm -> from_date = $_GET["from_date"];
$inventoryForm -> end_date = $_GET["end_date"];
$inventoryForm -> generatedby = $_GET["generatedby"];
$inventoryForm -> getTechnicalInventoryTemplatePrinting();