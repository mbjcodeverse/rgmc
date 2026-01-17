<?php

require_once "../../../controllers/inventory.controller.php";
require_once "../../../models/inventory.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printTechnicalInventory{

public $categorycode;
public $asofdate;
public $generatedby;
public function getTechnicalInventoryPrinting(){
  $categorycode = $this->categorycode;
  $asofdate = $this->asofdate;
  $generatedby = $this->generatedby;
  $inventory = (new ControllerInventory)->ctrShowInventoryTechnical($categorycode, $asofdate);

  $asof_date = substr($asofdate,5,2)."/".substr($asofdate,8,2)."/".substr($asofdate,0,4);

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
        <td style="width:540px;text-align:center;font-size:1.1em;font-weight:bold;">PERPETUAL INVENTORY</td> 
      </tr>

      <tr>
        <td style="width:424px;text-align:right;font-size:11px;">As of:</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$asof_date</td>                
      </tr>    

      <tr style="background-color:white;">
        <td style="width:51px;"></td>
        <td style="border: 1px solid #666;width:320px;text-align:left;font-size:11px;">&nbsp;&nbsp; Description</td>            
        <td style="border: 1px solid #666;width:55px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;</td>   
        <td style="border: 1px solid #666;width:55px;text-align:center;font-size:11px;">Meas</td>           
      </tr>                   
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
$prev_itemid = 0;
$curr_itemid = 0;

$prev_code = '';
$curr_code = '';

$ctr = 0;
$onhand = 0.00;
$isInventory = 0;
$itemid = 0;

foreach ($inventory as $key => $value) {
    $itemid = $value["itemid"];
    $itemcode = $value["itemcode"];
    $tdate = $value["tdate"];
    $details = $value["details"];
    $qty = $value["qty"];
    $priority = $value["priority"];

    if ($ctr == 0){
        $prev_itemid = $value["itemid"];
        $prev_code = $value["itemcode"];
    }

    $ctr = $ctr + 1;

    $curr_itemid = $value["itemid"];        
    $curr_code = $value["itemcode"];        

    if ($prev_itemid == $curr_itemid){      
        $pdesc = $value["pdesc"];
        $meas1 = $value["meas1"];
        $eqnum = $value["eqnum"];
        $meas2 = strtoupper($value["meas2"]);
        $ucost = $value["ucost"];
        $reorder = $value["reorder"];

        if ($details == "Inventory"){
            $isInventory = 1;
        }
            
        switch ($details) {
            case "Inventory":
                $onhand = $qty;
                break;
            case "Incoming":
                $onhand = $onhand + $qty;
                break; 
            case "Return":
                $onhand = $onhand + $qty;
                break;	
            default: 
                $onhand = $onhand - $qty;
        } 
    }else{
        $onhand = number_format($onhand,0);
        $content = <<<EOF
            <table style="border: none;">    
            <tr>
                <td style="width:51px;"></td>
                <td style="width:320px;text-align:left;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp; $pdesc</td>
                <td style="width:55px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$onhand</td> 
                <td style="width:55px;text-align:center;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$meas2</td>  
            </tr>                 
            </table>
        EOF;
          $pdf->writeHTML($content, false, false, false, false, '');

        $prev_itemid = $curr_itemid;
        $prev_code = $curr_code;

        $pdesc = $value["pdesc"];

        $onhand = 0.00;
        $isInventory = 0;
        if ($details == "Inventory"){
            $isInventory = 1;
        }

        switch ($details) {
            case "Inventory":
                $onhand = $qty;
                break;
            case "Incoming":
                $onhand = $onhand + $qty;
                break;   
            case "Return":
                $onhand = $onhand + $qty;
                break;		
            default:  
                $onhand = $onhand - $qty;
        }  
    }
}

if ($itemid != 0){
    $content = <<<EOF
    <table style="border: none;">    
        <tr>
            <td style="width:51px;"></td>
            <td style="width:320px;text-align:left;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp; $pdesc</td>
            <td style="width:55px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$onhand</td> 
            <td style="width:55px;text-align:center;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$meas2</td>  
        </tr>                 
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');
}

  $close_content = <<<EOF
	    <table style="border: none;">
	      <tr>
            <td style="width:51px;"></td>
	        <td style="width:320px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:55px;border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:55px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
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
         <td style="width:48px;"></td>
         <td style="width:202px;font-size:11px;">Generated by:</td>         
       </tr>
       <tr>
         <td style="width:545px;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:48px;"></td>
         <td style="width:125px;font-size:11px;">$posted_by</td>         
       </tr>       
     </table>
EOF;
  $pdf->writeHTML($footer, false, false, false, false, '');  


  $pdf->Output('inventory_report.pdf', 'I');
 }
}

$inventoryForm = new printTechnicalInventory();
$inventoryForm -> categorycode = $_GET["categorycode"];
$inventoryForm -> asofdate = $_GET["asofdate"];
$inventoryForm -> generatedby = $_GET["generatedby"];
$inventoryForm -> getTechnicalInventoryPrinting();