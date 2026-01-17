<?php

require_once "../../../controllers/stakeholder.controller.php";
require_once "../../../models/stakeholder.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

require_once "../../../controllers/products.controller.php";
require_once "../../../models/products.model.php";

require_once "../../../controllers/stockin.controller.php";
require_once "../../../models/stockin.model.php";

class printStockSheet{

public $refcode;
public function getTallyStockPrinting(){
  $itemRefcode = "refcode";
  $refcode = $this->refcode;

  $stockin = (new ControllerStockin)->ctrShowStockinInfo($itemRefcode, $refcode);
  $id = $stockin['id'];
  $refcode = $stockin['refcode'];
  $recnumber = $stockin['recnumber'];
  // $idStkcat = $stockin['idStkcat'];
  // $idStakeholder = $stockin['idStakeholder'];
  $tdate = $stockin['tdate'];
  $idReason = $stockin['idReason'];
  $remarks = $stockin['remarks'];
  $idEmployee = $stockin['idEmployee']; 
  $trans_date = substr($tdate,5,2)."/".substr($tdate,8,2)."/".substr($tdate,0,4);
  $productlist = json_decode($stockin['productlist'], true);

  $itemEmployee = "id";
  $valueEmployee = $idEmployee;
  $answerEmployee = (new ControllerEmployees)->ctrShowEmployees($itemEmployee, $valueEmployee);
  $prepared_by = $answerEmployee['fname'].' '.$answerEmployee['lname'];

  // $itemStakeholder = "id";
  // $valueStakeholder = $idStakeholder;
  // $answerStakeholder = (new ControllerStakeholder)->ctrShowStakeholderInfo($itemStakeholder, $valueStakeholder);
  // $stakeholder = $answerStakeholder['stkname'];

  // $itemCategory = "id";
  // $valueCategory = $idStkcat;
  // $answerCategory = (new ControllerStakeholder)->ctrGetCategory($itemCategory, $valueCategory);
  // $category = $answerCategory['stkcat'];  

  $itemReason = "id";
  $valueReason = $idReason;
  $answerReason = (new ControllerStakeholder)->ctrGetReason($itemReason, $valueReason);
  $reason = $answerReason['rescat'];

  $source = $reason;  

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  $pdf->AddPage();

  $header = <<<EOF
    <table>
      <tr>
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON COMMERCIAL WAREHOUSE</td> 
      </tr>

      <tr>
        <td style="width:540px;text-align:center;font-size:10px;">Sharina Hts., Brgy. Taculing, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">Incoming Stock</td> 
      </tr>

      <tr>
        <td style="width:45px;text-align:right;font-size:11px;">Reason :</td>
        <td style="width:387px;text-align:left;font-size:11px;">$source</td>
        <td style="width:50px;text-align:right;font-size:11px;">SR # :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$refcode</td>                
      </tr>

      <tr>
        <td style="width:45px;text-align:right;font-size:11px;">DR # :</td>
        <td style="width:387px;text-align:left;font-size:11px;">&nbsp;$recnumber</td>
        <td style="width:50px;text-align:right;font-size:11px;">Date :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$trans_date</td>                
      </tr>    
 
      <tr style="background-color:#f2f4f7;">
        <td style="border: 1px solid #666;width:60px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>
        <td style="border: 1px solid #666;width:315px;text-align:left;font-size:11px;">&nbsp;&nbsp; Description</td>
        <td style="border: 1px solid #666;width:165px;text-align:left;font-size:11px;">&nbsp;&nbsp; Note</td>                
      </tr>   

      <tr>
        <td style="border-left: 1px solid black;border-right: 1px solid black;"></td>
        <td style="border-left: 1px solid black;border-right: 1px solid black;"></td>
        <td style="border-left: 1px solid black;border-right: 1px solid black;"></td>
        <td style="border-left: 1px solid black;border-right: 1px solid black;"></td>
      </tr>                   
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
$num_lines = 0;  
foreach ($productlist as $key => $value) {
  $pitem = "id";
  $idProduct = $value["idProduct"];
  $product = ControllerProducts::ctrGetProduct($pitem, $idProduct);
  $pdesc = $product['pdesc']; 
  $qty = $value["qty"];

  $num_lines = $num_lines + 1;

  $content = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="width:60px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$qty &nbsp;&nbsp;&nbsp;</td>
        <td style="width:315px;text-align:left;font-size:11px;">&nbsp;&nbsp; $pdesc</td>
        <td style="width:165px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;"></td>                
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
	        <td style="width:60px;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:315px;"></td>
            <td style="width:165px;border-right: 1px solid black;border-left: 1px solid black;"></td>
	      </tr>
	    </table>
EOF;
      $pdf->writeHTML($extra_lines, false, false, false, false, '');
    }	
}

  $close_content = <<<EOF
	    <table style="border: none;">
	      <tr>
	        <td style="width:60px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:315px;border-bottom: 1px solid black;"></td>
            <td style="width:165px;border-right: 1px solid black;border-left: 1px solid black;border-bottom: 1px solid black;"></td>
	      </tr>

        <tr>
          <td style="width:60px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;text-align:right;font-size:11px;">Remarks</td>
            <td style="width:480px;border-right: 1px solid black;border-bottom: 1px solid black;text-align:left;font-size:11px;">&nbsp;&nbsp;$remarks</td>
        </tr>        
	    </table>
EOF;
  $pdf->writeHTML($close_content, false, false, false, false, '');

  $footer = <<<EOF
	    <table style="border: none;">
        <tr>
          <td style="width:330px;font-size:11px;"></td>       
        </tr>              
	      <tr>
	        <td style="width:160px;font-size:11px;">Prepared by:</td>
	        <td style="width:110px;text-align: right;font-size:11px;">Delivered by:</td>
        </tr>
	      <tr>
	        <td style="width:330px;font-size:11px;"></td>	      
	      </tr>
	      <tr>
	        <td style="width:125px;border-bottom: 1px solid black;font-size:11px;"></td>
	        <td style="width:77px;font-size:11px;"></td>
          <td style="width:125px;border-bottom: 1px solid black;font-size:11px;"></td>
          <td style="width:53px;font-size:11px;"></td>
        </tr>
	      <tr>
	        <td style="width:125px;font-size:11px;text-align: left">$prepared_by</td>
	      </tr>      
	    </table>
EOF;
  $pdf->writeHTML($footer, false, false, false, false, '');  

  $pdf->Output('stockinprint.pdf', 'I');
 }
}

$stockForm = new printStockSheet();
$stockForm -> refcode = $_GET["refcode"];
$stockForm -> getTallyStockPrinting();
?>