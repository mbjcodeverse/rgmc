<?php

require_once "../../../controllers/purchase.controller.php";
require_once "../../../models/purchase.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

require_once "../../../controllers/measure.controller.php";
require_once "../../../models/measure.model.php";

require_once "../../../controllers/rawmaterials.controller.php";
require_once "../../../models/rawmaterials.model.php";

require_once "../../../controllers/suppliers.controller.php";
require_once "../../../models/suppliers.model.php";

class printPurchase{

public $ponumber;
public function getPurchasePrinting(){
  $itemPurchase = "ponumber";
  $poNumber = $this->ponumber;
  $purchase = (new ControllerPurchase)->ctrShowPurchase($itemPurchase, $poNumber);

  $id = $purchase['id'];
  $ponumber = $purchase['ponumber'];
  $idSupplier = $purchase['idSupplier'];

  $podate = $purchase['podate'];
  $po_date = substr($podate,5,2)."/".substr($podate,8,2)."/".substr($podate,0,4);

  $pstatus = $purchase['pstatus'];
  $idOrderby = $purchase['idOrderby'];
  $idPrepared = $purchase['idPrepared'];
  $tamount = number_format($purchase['tamount'],2);
  $dcost = number_format($purchase['dcost'],2);
  $acost = number_format($purchase['acost'],2);
  $remarks = $purchase['remarks'];
  $raw_materials = json_decode($purchase['materials'], true);
  
  $itemSupplier = "id";
  $valueSupplier = $idSupplier;
  $answerSupplier = (new ControllerSuppliers)->ctrShowSuppliers($itemSupplier, $valueSupplier);
  $supplier_name = $answerSupplier['sname']; 
  $supplier_address = $answerSupplier['address']; 

  $itemOrderby = "id";
  $valueOrderby = $idOrderby;
  $answerOrderby = (new ControllerEmployees)->ctrShowEmployees($itemOrderby, $valueOrderby);

  if ($answerOrderby['mi']!=''){
    $order_by = $answerOrderby['fname'].' '.$answerOrderby['mi'].'. '.$answerOrderby['lname']; 
  }else{
    $order_by = $answerOrderby['fname'].' '.$answerOrderby['lname'];
  }   

  $itemPrepared = "id";
  $valuePrepared = $idPrepared;
  $answerPrepared = (new ControllerEmployees)->ctrShowEmployees($itemPrepared, $valuePrepared);
  // $prepared_by = $answerPrepared['fname'].' '.$answerPrepared['lname'];

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
        <td style="width:430px;"></td>
        <td style="width:50px;text-align:right;font-size:11px;">PO # :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$ponumber</td>                
      </tr>

      <tr>
        <td style="width:50px;text-align:right;font-size:11px;">Supplier :</td>
        <td style="width:380px;text-align:left;font-size:11px;">&nbsp;$supplier_name</td>
        <td style="width:50px;text-align:right;font-size:11px;">Date :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$po_date</td>                
      </tr>    

      <tr style="background-color:#f2f4f7;">
        <td style="border: 1px solid #666;width:250px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Description</td>    

        <td style="border: 1px solid #666;width:40px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Meas</td>         

        <td style="border: 1px solid #666;width:65px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:75px;text-align:right;font-size:11px;">Unit Cost &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #666;width:115px;text-align:right;font-size:11px;">Total Cost &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                
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
foreach ($raw_materials as $key => $value) {
  $mitem = "id";
  $idRaw = $value["idRaw"];
  $material = controllerMaterials::ctrShowMaterials($mitem, $idRaw);
  $mdesc = $material['mdesc']; 
 
  $measid = "id";
  $m1 = $material['m1'];
  $meas = ControllerMeasure::ctrShowMeasure($measid, $m1);
  $measure = $meas['mdesc'];

  $qty = $value["qty"];
  $ucost = number_format($value["ucost"],4);
  $tcost = number_format($value["tcost"],2);

  $num_lines = $num_lines + 1;

  $content = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="width:250px;text-align:left;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $mdesc</td>

        <td style="width:40px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$measure</td> 

        <td style="width:65px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$qty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 

        <td style="width:75px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$ucost &nbsp;&nbsp;</td>

        <td style="width:115px;text-align:right;font-size:11px;border-right: 1px solid black;">$tcost &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                
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
	        <td style="width:250px;border-left: 1px solid black;border-right: 1px solid black;"></td>
          <td style="width:40px;"></td>
          <td style="width:65px;border-left: 1px solid black;border-right: 1px solid black;"></td>
          <td style="width:75px;border-left: 1px solid black;border-right: 1px solid black;"></td>
          <td style="width:115px;border-right: 1px solid black;"></td>
	      </tr>
	    </table>
EOF;
      $pdf->writeHTML($extra_lines, false, false, false, false, '');
    }	
}

  $close_content = <<<EOF
	    <table style="border: none;">
	      <tr>
	        <td style="width:250px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:40px;border-bottom: 1px solid black;"></td>
            <td style="width:65px;border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:75px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:115px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
	      </tr>
	    </table>
EOF;
  $pdf->writeHTML($close_content, false, false, false, false, '');

  $footer = <<<EOF
     <table style="border: none;">
       <tr>
         <td style="width:160px;font-size:11px;border-left: 1px solid black;">Remarks:</td>
         <td style="width:170px;text-align: left;font-size:11px;">$remarks</td>
         <td style="width:120px;text-align: right;font-weight:bold;">TOTAL COST :</td>
         <td style="width:95px;text-align: right;font-weight:bold;border-right: 1px solid black;">$tamount &nbsp;&nbsp;&nbsp;</td>
       </tr>
       <tr>
         <td style="width:330px;font-size:11px;border-left: 1px solid black;"></td>
         <td style="width:120px;text-align: right;font-weight:bold;">DISCOUNT :</td>
         <td style="width:95px;text-align: right;font-weight:bold;border-right: 1px solid black;">0.00 &nbsp;&nbsp;&nbsp;</td>       
       </tr>
       <tr>
         <td style="width:125px;border-bottom: 1px solid black;font-size:11px;border-left: 1px solid black;"></td>
         <td style="width:205px;font-size:11px;border-bottom: 1px solid black;"></td>
         <td style="width:120px;text-align: right;font-weight:bold;border-bottom: 1px solid black;">NET COST :</td>
         <td style="width:95px;text-align: right;font-weight:bold;border-bottom: 1px solid black;border-right: 1px solid black;">$acost &nbsp;&nbsp;&nbsp;</td>          
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


  $pdf->Output('purchaseform.pdf', 'I');
 }
}

$purchaseForm = new printPurchase();
$purchaseForm -> ponumber = $_GET["ponumber"];
$purchaseForm -> getPurchasePrinting();
?>