<?php

require_once "../../../controllers/incoming.controller.php";
require_once "../../../models/incoming.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

require_once "../../../controllers/items.controller.php";
require_once "../../../models/items.model.php";

class printIncoming{

public $delnumber;
public function getIncomingPrinting(){
  $delnumber = $this->delnumber;
  $incoming = (new ControllerIncoming)->ctrShowIncoming($delnumber);
  $machinedesc = $incoming['machinedesc'];
  $machineid = $incoming['machineid'];
  $name = $incoming['name'];
  $ponumber = $incoming['ponumber'];
  $iscode = $incoming['iscode'];

  $deldate = $incoming['deldate'];
  $del_date = substr($deldate,5,2)."/".substr($deldate,8,2)."/".substr($deldate,0,4);

  $delstatus = $incoming['delstatus'];
  $delnumber = $incoming['delnumber'];
  $address = $incoming['address'];
  $orderedby = $incoming['orderedby'];
  $postedby = $incoming['postedby'];
  $remarks = $incoming['remarks'];
  $amount = number_format($incoming['amount'],2);
  $discount = number_format($incoming['discount'],2);
  $netamount = number_format($incoming['netamount'],2);
  $incomingitems = json_decode($incoming['productlist'], true); 

  $itemOrderby = "empid";
  $valueOrderby = $orderedby;
  $answerOrderby = (new ControllerEmployees)->ctrShowEmployees($itemOrderby, $valueOrderby);

  if ($answerOrderby['mi']!=''){
    $order_by = $answerOrderby['fname'].' '.$answerOrderby['mi'].'. '.$answerOrderby['lname']; 
  }else{
    $order_by = $answerOrderby['fname'].' '.$answerOrderby['lname'];
  }   

  $itemPrepared = "empid";
  $valuePrepared = $postedby;
  $answerPrepared = (new ControllerEmployees)->ctrShowEmployees($itemPrepared, $valuePrepared);

  if ($answerPrepared['mi']!=''){
    $posted_by = $answerPrepared['fname'].' '.$answerPrepared['mi'].'. '.$answerPrepared['lname']; 
  }else{
    $posted_by = $answerPrepared['fname'].' '.$answerPrepared['lname'];
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
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">Incoming Stocks</td> 
      </tr>

      <tr>
        <td style="width:430px;"></td>
        <td style="width:50px;text-align:right;font-size:11px;">PO # :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$ponumber</td>                
      </tr>      

      <tr>
        <td style="width:430px;"></td>
        <td style="width:50px;text-align:right;font-size:11px;">Del # :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$delnumber</td>                
      </tr>

      <tr>
        <td style="width:50px;text-align:right;font-size:11px;">Supplier :</td>
        <td style="width:380px;text-align:left;font-size:11px;">&nbsp;$name</td>
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
foreach ($incomingitems as $key => $value) {
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
        <td style="width:250px;text-align:left;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $pdesc</td>

        <td style="width:40px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$meas1</td> 

        <td style="width:65px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$qty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 

        <td style="width:75px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$price &nbsp;&nbsp;</td>

        <td style="width:115px;text-align:right;font-size:11px;border-right: 1px solid black;">$tamount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                
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
            <td style="width:285px;text-align: left;font-size:11px;">$iscode</td>
            <td style="width:120px;text-align: right;font-weight:bold;">TOTAL COST :</td>
            <td style="width:95px;text-align: right;font-weight:bold;border-right: 1px solid black;">$amount &nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td style="width:330px;font-size:11px;border-left: 1px solid black;"></td>
            <td style="width:120px;text-align: right;font-weight:bold;">DISCOUNT :</td>
            <td style="width:95px;text-align: right;font-weight:bold;border-right: 1px solid black;">$discount  &nbsp;&nbsp;&nbsp;</td>       
        </tr>
        <tr>
            <td style="width:125px;border-bottom: 1px solid black;font-size:11px;border-left: 1px solid black;"></td>
            <td style="width:205px;font-size:11px;border-bottom: 1px solid black;"></td>
            <td style="width:120px;text-align: right;font-weight:bold;border-bottom: 1px solid black;">NET COST :</td>
            <td style="width:95px;text-align: right;font-weight:bold;border-bottom: 1px solid black;border-right: 1px solid black;">$netamount &nbsp;&nbsp;&nbsp;</td>          
        </tr>

        <tr>
            <td style="width:545px;font-size:11px;"></td>
        </tr>
        <tr>
            <td style="width:202px;font-size:11px;">Ordered by:</td>
            <td style="width:202px;font-size:11px;">Posted by:</td>
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
            <td style="width:125px;font-size:11px;">$order_by</td>
            <td style="width:79px;font-size:11px;"></td>
            <td style="width:130px;font-size:11px;">$posted_by</td>
            <td style="width:73px;font-size:11px;"></td>
            <td style="width:170px;font-size:11px;">Adrian Joshua R. Lamata</td>         
        </tr>       
        </table>
EOF;
  $pdf->writeHTML($footer, false, false, false, false, '');  


  $pdf->Output('incoming.pdf', 'I');
 }
}

$incomingForm = new printIncoming();
$incomingForm -> delnumber = $_GET["delnumber"];
$incomingForm -> getIncomingPrinting();
?>