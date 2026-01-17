<?php

require_once "../../../controllers/sales.controller.php";
require_once "../../../models/sales.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

require_once "../../../controllers/products.controller.php";
require_once "../../../models/products.model.php";

require_once "../../../controllers/clients.controller.php";
require_once "../../../models/clients.model.php";

require_once "../../../controllers/transport.controller.php";
require_once "../../../models/transport.model.php";

class printSales{

public $invno;
public function getSalesPrinting(){
  $itemSales = "invno";
  $invSales = $this->invno;
  $sales = (new ControllerSales)->ctrShowSales($itemSales, $invSales);

  $id = $sales['id'];
  $invno = $sales['invno'];
  $invstatus = $sales['invstatus'];
  $idPrepared = $sales['idPrepared'];
  $smethod = $sales['smethod'];
  $clientid = $sales['clientid'];
  $sdate = $sales['sdate'];
  $totaldue = number_format($sales['totaldue'],2);
  $ponumber = $sales['ponumber'];
  $idriver = $sales['idriver'];
  $remarks = $sales['remarks'];
  $idTransport = $sales['idTransport'];
  $products = json_decode($sales['products'], true);
  $sale_date = substr($sdate,5,2)."/".substr($sdate,8,2)."/".substr($sdate,0,4);

  $itemClient = "id";
  $valueClient = $clientid;
  $answerClient = (new ControllerClients)->ctrShowClients($itemClient, $valueClient);
  $client_name = $answerClient['cname']; 
  $client_address = $answerClient['address']; 

  if ($idriver > 0){
    $itemDriver = "id";
    $valueDriver = $idriver;
    $answerDriver = (new ControllerEmployees)->ctrShowEmployees($itemDriver, $valueDriver);
    $driver_name = $answerDriver['fname'].' '.$answerDriver['lname']; 
  }else{
    $driver_name = '';
  }

  if ($idTransport > 0){
    $itemTransport = "id";
    $valueTransport = $idTransport;
    $answerTransport = (new ControllerTransport)->ctrShowTransport($itemTransport, $valueTransport);
    $transport_name = $answerTransport['platenum'];
  }else{
    $transport_name = '';
  }   

  $itemPrepared = "id";
  $valuePrepared = $idPrepared;
  $answerPrepared = (new ControllerEmployees)->ctrShowEmployees($itemPrepared, $valuePrepared);
  $prepared_by = $answerPrepared['fname'].' '.$answerPrepared['lname'];

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
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">Order Slip</td> 
      </tr>

      <tr>
        <td style="width:80px;text-align:right;font-size:11px;">Delivered to :</td>
        <td style="width:350px;text-align:left;font-size:11px;">&nbsp;$client_name</td>
        <td style="width:50px;text-align:right;font-size:11px;">DR # :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$invSales</td>                
      </tr>

      <tr>
        <td style="width:80px;text-align:right;font-size:11px;">Address :</td>
        <td style="width:350px;text-align:left;font-size:11px;">&nbsp;$client_address</td>
        <td style="width:50px;text-align:right;font-size:11px;">Date :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$sale_date</td>                
      </tr>    
 
      <tr style="background-color:#f2f4f7;">
        <td style="border: 1px solid #666;width:60px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>
        <td style="border: 1px solid #666;width:315px;text-align:left;font-size:11px;">&nbsp;&nbsp; Description</td>
        <td style="border: 1px solid #666;width:75px;text-align:right;font-size:11px;">Unit Price &nbsp;&nbsp;</td>
        <td style="border: 1px solid #666;width:90px;text-align:right;font-size:11px;">Total Amount &nbsp;&nbsp;</td>                
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
foreach ($products as $key => $value) {
  $pitem = "id";
  $idProduct = $value["prodid"];
  $product = controllerProducts::ctrShowProducts($pitem, $idProduct);
  $pdesc = $product['pdesc']; 
  $qty = $value["qty"];
  $uprice = number_format($value["uprice"],2);
  $tamount = number_format($value["tamount"],2);

  $num_lines = $num_lines + 1;

  $content = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="width:60px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$qty &nbsp;&nbsp;&nbsp;</td>
        <td style="width:315px;text-align:left;font-size:11px;">&nbsp;&nbsp; $pdesc</td>
        <td style="width:75px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">$uprice &nbsp;&nbsp;</td>
        <td style="width:90px;text-align:right;font-size:11px;border-right: 1px solid black;">$tamount &nbsp;&nbsp;</td>                
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
            <td style="width:75px;border-left: 1px solid black;border-right: 1px solid black;"></td>
            <td style="width:90px;border-right: 1px solid black;"></td>
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
            <td style="width:75px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
            <td style="width:90px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
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
	        <td style="width:160px;font-size:11px;">Delivered by:</td>
	        <td style="width:90px;text-align: right;font-size:11px;">Plate # :</td>
	        <td style="width:80px;text-align: left;font-size:11px;">$transport_name</td>
	        <td style="width:120px;text-align: right;font-weight:bold;">TOTAL DUE :</td>
	        <td style="width:90px;text-align: right;font-weight:bold;">$totaldue &nbsp;&nbsp;</td>
	      </tr>
	      <tr>
	        <td style="width:330px;font-size:11px;"></td>
	        <td style="width:120px;text-align: right;font-weight:bold;">DISCOUNT :</td>
	        <td style="width:90px;text-align: right;font-weight:bold;">0.00 &nbsp;&nbsp;</td>	      
	      </tr>
	      <tr>
	        <td style="width:125px;border-bottom: 1px solid black;font-size:11px;"></td>
	        <td style="width:60px;font-size:11px;"></td>
          <td style="width:128px;text-align: right;font-size:10px;"><i>Received the complete</i></td>
	        <td style="width:137px;text-align: right;font-weight:bold;">AMOUNT DUE :</td>
	        <td style="width:90px;text-align: right;font-weight:bold;">$totaldue &nbsp;&nbsp;</td>		      
	      </tr>
	      <tr>
	        <td style="width:125px;font-size:11px;text-align: left">$driver_name</td>
          <td style="width:77px;font-size:11px;"></td>
	        <td style="width:220px;font-size:10px;"><i>items in good condition</i></td>
	      </tr>
	      <tr>
	        <td style="width:545px;font-size:11px;"></td>
	      </tr>
	      <tr>
	        <td style="width:202px;font-size:11px;">Prepared by:</td>
	        <td style="width:80px;font-size:11px;">Received by:</td>	        
	      </tr>
	      <tr>
	        <td style="width:545px;font-size:11px;"></td>
	      </tr>
	      <tr>
	        <td style="width:125px;border-bottom: 1px solid black;font-size:11px;"></td>
	        <td style="width:79px;font-size:11px;"></td>
	        <td style="width:123px;border-bottom: 1px solid black;font-size:11px;"></td>
	      </tr>
	      <tr>
	        <td style="width:125px;font-size:11px;">$prepared_by</td>
	      </tr>	      
	    </table>
EOF;
  $pdf->writeHTML($footer, false, false, false, false, '');  


  $pdf->Output('salesinvoice.pdf', 'I');
 }
}

$salesForm = new printSales();
$salesForm -> invno = $_GET["invno"];
$salesForm -> getSalesPrinting();
?>