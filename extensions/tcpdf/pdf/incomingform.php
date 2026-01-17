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

class printIncoming{

public $ponumber;
public function getIncomingPrinting(){
  // $itemIncoming = "ponumber";
  // $poNumber = $this->ponumber;
  // $incoming = (new ControllerPurchase)->ctrShowIndividualIncoming($itemIncoming, $poNumber);

  $itemIncoming = "delnumber";
  $del_Number = $this->delnumber;
  $incoming = (new ControllerPurchase)->ctrShowIndividualIncoming($itemIncoming, $del_Number);  

  $id = $incoming['id'];
  $ponumber = $incoming['ponumber'];
  $delnumber = $incoming['delnumber'];
  $idSupplier = $incoming['idSupplier'];

  $deldate = $incoming['deldate'];
  $del_date = substr($deldate,5,2)."/".substr($deldate,8,2)."/".substr($deldate,0,4);

  $pstatus = $incoming['pstatus'];
  $idCheckby = $incoming['idCheckby'];
  $idPrepared = $incoming['idPrepared'];
  $tamount = number_format($incoming['tamount'],2);
  $dcost = number_format($incoming['dcost'],2);
  $acost = number_format($incoming['acost'],2);
  $isnum = $incoming['isnum'];
  $materials = json_decode($incoming['materials'], true);
  
  $itemSupplier = "id";
  $valueSupplier = $idSupplier;
  $answerSupplier = (new ControllerSuppliers)->ctrShowSuppliers($itemSupplier, $valueSupplier);
  $supplier_name = $answerSupplier['sname']; 
  $supplier_address = $answerSupplier['address']; 

  $itemCheckby = "id";
  $valueCheckby = $idCheckby;
  $answerCheckby = (new ControllerEmployees)->ctrShowEmployees($itemCheckby, $valueCheckby);

  if ($answerCheckby['mi']!=''){
    $check_by = $answerCheckby['fname'].' '.$answerCheckby['mi'].'. '.$answerCheckby['lname']; 
  }else{
    $check_by = $answerCheckby['fname'].' '.$answerCheckby['lname'];
  }   

  $itemPrepared = "id";
  $valuePrepared = $idPrepared;
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
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">Incoming Sheet</td> 
      </tr>

      <tr>
        <td style="width:430px;"></td>
        <td style="width:50px;text-align:right;font-size:11px;">PO # :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$ponumber</td>                
      </tr>

      <tr>
        <td style="width:430px;"></td>
        <td style="width:50px;text-align:right;font-size:11px;">IS # :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$delnumber</td>                
      </tr>      

      <tr>
        <td style="width:50px;text-align:right;font-size:11px;">Supplier :</td>
        <td style="width:380px;text-align:left;font-size:11px;">&nbsp;$supplier_name</td>
        <td style="width:50px;text-align:right;font-size:11px;">Date :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$del_date</td>                
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
foreach ($materials as $key => $value) {
  $mitem = "id";
  $idRaw = $value["idRaw"];
  $material = controllerMaterials::ctrShowMaterials($mitem, $idRaw);
  $mdesc = $material['mdesc']; 
  // $m1 = $material['m1']; 

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
         <td style="width:45px;font-size:11px;border-left: 1px solid black;">&nbsp;&nbsp;&nbsp;Del #:</td>
         <td style="width:285px;text-align: left;font-size:11px;">$isnum</td>
         <td style="width:120px;text-align: right;font-weight:bold;">TOTAL COST :</td>
         <td style="width:95px;text-align: right;font-weight:bold;border-right: 1px solid black;">$tamount &nbsp;&nbsp;&nbsp;</td>
       </tr>
       <tr>
         <td style="width:330px;font-size:11px;border-left: 1px solid black;"></td>
         <td style="width:120px;text-align: right;font-weight:bold;">DISCOUNT :</td>
         <td style="width:95px;text-align: right;font-weight:bold;border-right: 1px solid black;">$dcost  &nbsp;&nbsp;&nbsp;</td>       
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
         <td style="width:202px;font-size:11px;">Posted by:</td>
         <td style="width:202px;font-size:11px;">Checked by:</td>
         <td style="width:80px;font-size:11px;">Approved by:</td>          
       </tr>
       <tr>
         <td style="width:545px;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:125px;border-bottom: 1px solid black;font-size:11px;"></td>
         <td style="width:79px;font-size:11px;"></td>
         <td style="width:130px;border-bottom: 1px solid black;font-size:11px;"></td>
         <td style="width:73px;font-size:11px;"></td>
         <td style="width:130px;border-bottom: 1px solid black;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:125px;font-size:11px;">$prepared_by</td>
         <td style="width:79px;font-size:11px;"></td>
         <td style="width:130px;font-size:11px;">$check_by</td>
         <td style="width:73px;font-size:11px;"></td>
         <td style="width:170px;font-size:11px;">Adrian Joshua R. Lamata</td>         
       </tr>       
     </table>
EOF;
  $pdf->writeHTML($footer, false, false, false, false, '');  


  $pdf->Output('incomingform.pdf', 'I');
 }
}

$incomingForm = new printIncoming();
// $incomingForm -> ponumber = $_GET["ponumber"];
$incomingForm -> delnumber = $_GET["delnumber"];
$incomingForm -> getIncomingPrinting();
?>