<?php

require_once "../../../controllers/purchaseorder.controller.php";
require_once "../../../models/purchaseorder.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

require_once "../../../controllers/items.controller.php";
require_once "../../../models/items.model.php";

class printPurchase{

public $ponumber;
public function getPurchasePrinting(){
  $ponumber = $this->ponumber;
  $purchase = (new ControllerPurchaseOrder)->ctrShowPurchaseOrder($ponumber);
  $machinedesc = $purchase['machinedesc'].' | '.$purchase['machabbr'];
  // $machabbr = $purchase['machabbr'];
  if ($purchase['machinedesc'] != ''){
    $machine_label = 'Machine :';
  }else{
    $machine_label = '';
    $machinedesc = '';
  }

  $machineid = $purchase['machineid'];
  $name = $purchase['name'];

  $podate = $purchase['podate'];
  $po_date = substr($podate,5,2)."/".substr($podate,8,2)."/".substr($podate,0,4);

  $postatus = $purchase['postatus'];
  $ponumber = $purchase['ponumber'];
  $address = $purchase['address'];
  $orderedby = $purchase['orderedby'];
  $preparedby = $purchase['preparedby'];
  $remarks = $purchase['remarks'];
  $amount = number_format($purchase['amount'],2);
  $discount = number_format($purchase['discount'],2);
  $netamount = number_format($purchase['netamount'],2);
  $remarks = $purchase['remarks'];
  $purchaseitems = json_decode($purchase['productlist'], true); 

  $itemOrderby = "empid";
  $valueOrderby = $orderedby;
  $answerOrderby = (new ControllerEmployees)->ctrShowEmployees($itemOrderby, $valueOrderby);

  if ($answerOrderby['mi']!=''){
    $order_by = $answerOrderby['fname'].' '.$answerOrderby['mi'].'. '.$answerOrderby['lname']; 
  }else{
    $order_by = $answerOrderby['fname'].' '.$answerOrderby['lname'];
  }   

  $itemPrepared = "empid";
  $valuePrepared = $preparedby;
  $answerPrepared = (new ControllerEmployees)->ctrShowEmployees($itemPrepared, $valuePrepared);

  if ($answerPrepared['mi']!=''){
    $prepared_by = $answerPrepared['fname'].' '.$answerPrepared['mi'].'. '.$answerPrepared['lname']; 
  }else{
    $prepared_by = $answerPrepared['fname'].' '.$answerPrepared['lname'];
  }  

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  $pdf->AddPage();

  $header = <<<EOF
    <table>
      <tr>
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
      </tr>

      <tr>
        <td style="width:540px;text-align:center;font-size:10px;">Purok Paho, Brgy. Felisa, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">Purchase Order</td> 
      </tr>

      <tr>
        <td></td> 
      </tr>

      <tr>        
        <td style="width:50px;text-align:right;font-size:11px;">$machine_label</td>
        <td style="width:380px;text-align:left;font-size:11px;">&nbsp;$machinedesc</td>
        <td style="width:50px;text-align:right;font-size:11px;">PO # :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$ponumber</td>
      </tr>

      <tr>
        <td style="width:50px;text-align:right;font-size:11px;">Supplier :</td>
        <td style="width:380px;text-align:left;font-size:11px;">&nbsp;$name</td>
        <td style="width:50px;text-align:right;font-size:11px;">Date :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$po_date</td>                
      </tr>    

      <tr style="background-color:#f2f4f7;">
        <td style="border: 1px solid #666;width:295px;text-align:left;font-size:11px;">&nbsp;&nbsp; Description</td>    

        <td style="border: 1px solid #666;width:40px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Meas</td>         

        <td style="border: 1px solid #666;width:50px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:75px;text-align:right;font-size:11px;">Unit Cost &nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:85px;text-align:right;font-size:11px;">Total Cost &nbsp;&nbsp;</td>                
      </tr>   

      <tr>
        <td style="border-left: 1px solid black;border-right: 1px solid black;"></td>
        <td style="border-left: 1px solid black;"></td>
        <td style="border-left: 1px solid black;border-right: 1px solid black;"></td>
        <td style="border-left: 1px solid black;border-right: 1px solid black;"></td>
        <td style="border-left: 1px solid black;border-right: 1px solid black;"></td>
      </tr>                   
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
$num_lines = 0;  
foreach ($purchaseitems as $key => $value) {
  $field = "itemid";
  $itemid = $value["itemid"];
  $item = (new ControllerItems)->ctrShowItem($field, $itemid);
  $pdesc = $item['pdesc']; 
  $meas1 = $item['meas1'];

  $qty = $value["qty"];
  $price = number_format($value["price"],2);
  $tamount = number_format($value["tamount"],2);

  $num_lines = $num_lines + 1;

  $content = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="width:295px;text-align:left;font-size:10px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp;&nbsp; $pdesc</td>

        <td style="width:40px;text-align:left;font-size:10px;">&nbsp;&nbsp;&nbsp;$meas1</td> 

        <td style="width:50px;text-align:right;font-size:10px;border-right: 1px solid black;border-left: 1px solid black;">$qty</td> 

        <td style="width:75px;text-align:right;font-size:10px;border-right: 1px solid black;border-left: 1px solid black;">$price</td>

        <td style="width:85px;text-align:right;font-size:10px;border-right: 1px solid black;">$tamount</td>                
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
	        <td style="width:295px;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:40px;"></td>
            <td style="width:50px;border-left: 1px solid black;border-right: 1px solid black;"></td>
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
	        <td style="width:295px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:40px;border-bottom: 1px solid black;"></td>
            <td style="width:50px;border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:75px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:85px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
	      </tr>
	    </table>
EOF;
  $pdf->writeHTML($close_content, false, false, false, false, '');

  $footer = <<<EOF
     <table style="border: none;">
       <tr>
         <td style="width:160px;font-size:11px;border-left: 1px solid black;">Remarks:</td>
         <td style="width:188px;text-align: left;font-size:11px;">$remarks</td>
         <td style="width:120px;text-align: right;font-weight:bold;">TOTAL COST :</td>
         <td style="width:77px;text-align: right;font-weight:bold;border-right: 1px solid black;">$amount</td>
       </tr>
       <tr>
         <td style="width:348px;font-size:11px;border-left: 1px solid black;"></td>
         <td style="width:120px;text-align: right;font-weight:bold;">DISCOUNT :</td>
         <td style="width:77px;text-align: right;font-weight:bold;border-right: 1px solid black;">0.00</td>       
       </tr>
       <tr>
         <td style="width:125px;border-bottom: 1px solid black;font-size:11px;border-left: 1px solid black;"></td>
         <td style="width:223px;font-size:11px;border-bottom: 1px solid black;"></td>
         <td style="width:120px;text-align: right;font-weight:bold;border-bottom: 1px solid black;">NET COST :</td>
         <td style="width:77px;text-align: right;font-weight:bold;border-bottom: 1px solid black;border-right: 1px solid black;">$netamount</td>          
       </tr>

       <tr>
         <td style="width:545px;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:202px;font-size:11px;">Prepared by:</td>
         <td style="width:80px;font-size:11px;">Approved by:</td>          
       </tr>
       <tr>
         <td style="width:545px;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:125px;border-bottom: 1px solid black;font-size:11px;"></td>
         <td style="width:79px;font-size:11px;"></td>
         <td style="width:130px;border-bottom: 1px solid black;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:125px;font-size:11px;">$prepared_by</td>
         <td style="width:79px;font-size:11px;"></td>
         <td style="width:170px;font-size:11px;">Adrian Joshua R. Lamata</td>         
       </tr>       
     </table>
EOF;
  $pdf->writeHTML($footer, false, false, false, false, '');  


  $pdf->Output('purchaseorder.pdf', 'I');
 }
}

$purchaseForm = new printPurchase();
$purchaseForm -> ponumber = $_GET["ponumber"];
$purchaseForm -> getPurchasePrinting();
?>