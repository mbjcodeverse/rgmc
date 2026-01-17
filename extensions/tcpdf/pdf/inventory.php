<?php

require_once "../../../controllers/inventory.controller.php";
require_once "../../../models/inventory.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printInventory{

public $invnumber;
public function getInventoryPrinting(){
  $invnumber = $this->invnumber;
  $inventory = (new ControllerInventory)->ctrShowInventory($invnumber);

  $invdate = $inventory['invdate'];
  $inv_date = substr($invdate,5,2)."/".substr($invdate,8,2)."/".substr($invdate,0,4);

  $invnumber = $inventory['invnumber'];
  $postedby = $inventory['postedby'];
  $remarks = $inventory['remarks'];
  // $amount = number_format($inventory['amount'],2);

  $inventoryitems = (new ControllerInventory)->ctrShowInventoryItems($invnumber);

  $itemPostedby = "empid";
  $valuePostedby = $postedby;
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
        <td style="width:540px;text-align:center;font-size:1.1em;font-weight:bold;">Physical Inventory</td> 
      </tr>

      <tr>
        <td style="width:437px;"></td>
        <td style="width:50px;text-align:right;font-size:11px;">Inv # :</td>
        <td style="width:80px;text-align:left;font-size:11px;">&nbsp;$invnumber</td>                
      </tr>

      <tr>
        <td style="width:487px;text-align:right;font-size:11px;">Date :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$inv_date</td>                
      </tr>    

      <tr style="background-color:#f2f4f7;">
        <td style="border: 1px solid #666;width:320px;text-align:left;font-size:11px;">&nbsp;&nbsp; Description</td>            
        <td style="border: 1px solid #666;width:65px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;</td>
        <td style="border: 1px solid #666;width:75px;text-align:right;font-size:11px;">Unit Cost &nbsp;&nbsp;</td>
        <td style="border: 1px solid #666;width:85px;text-align:right;font-size:11px;">Total Cost &nbsp;&nbsp;</td>                
      </tr>                   
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
$num_lines = 0;  
$inv_amount = 0.00;
foreach ($inventoryitems as $key => $value) {
  $prodname = $value["prodname"];
  $qty = number_format($value["qty"],2);
  $price = number_format($value["price"],2);
  $tamount = number_format($value["tamount"],2);

  $inv_amount = $inv_amount + $value["tamount"];

  $num_lines = $num_lines + 1;

  $content = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="width:320px;text-align:left;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp; $prodname</td>
        <td style="width:65px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$qty</td> 
        <td style="width:75px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$price</td>
        <td style="width:85px;text-align:right;font-size:11px;border-right: 1px solid black;">$tamount</td>                
      </tr>                 
    </table>
EOF;
  $pdf->writeHTML($content, false, false, false, false, '');
}

// Extra blank lines
if ($num_lines < 6){
	$num_lines = 6 - $num_lines;
	for ($e = 0; $e <= $num_lines; $e++) {
	  $extra_lines = <<<EOF
	    <table style="border: none;">
	      <tr>
	        <td style="width:320px;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:65px;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:75px;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:85px;border-right: 1px solid black;"></td>
	      </tr>
	    </table>
EOF;
      $pdf->writeHTML($extra_lines, false, false, false, false, '');
    }	
}

  $close_content = <<<EOF
	    <table style="border: none;">
	      <tr>
	        <td style="width:320px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:65px;border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:75px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:85px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
	      </tr>
	    </table>
EOF;
  $pdf->writeHTML($close_content, false, false, false, false, '');

  $amount = number_format($inv_amount,2);
  $footer = <<<EOF
     <table style="border: none;">
       <tr>
         <td style="border-bottom:1px solid black;width:55px;font-size:11px;border-left: 1px solid black;">Remarks:</td>
         <td style="border-bottom:1px solid black;width:275px;text-align: left;font-size:11px;">$remarks</td>
         <td style="border-bottom:1px solid black;width:120px;text-align: right;font-weight:bold;">TOTAL COST :</td>
         <td style="border-bottom:1px solid black;width:95px;text-align: right;font-weight:bold;border-right: 1px solid black;">$amount</td>
       </tr>

       <tr>
         <td style="width:545px;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:202px;font-size:11px;">Posted by:</td>         
       </tr>
       <tr>
         <td style="width:545px;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:125px;border-bottom: 1px solid black;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:125px;font-size:11px;">$posted_by</td>         
       </tr>       
     </table>
EOF;
  $pdf->writeHTML($footer, false, false, false, false, '');  


  $pdf->Output('inventory.pdf', 'I');
 }
}

$inventoryForm = new printInventory();
$inventoryForm -> invnumber = $_GET["invnumber"];
$inventoryForm -> getInventoryPrinting();
?>