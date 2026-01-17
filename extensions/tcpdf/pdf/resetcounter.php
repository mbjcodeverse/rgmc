<?php
date_default_timezone_set('Asia/Manila');

require_once "../../../controllers/sale.controller.php";
require_once "../../../models/sale.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printCounterReset{
public $branchcode;
public $resetcode;
public $branch_name;
public $postedby;

public function getCounterReset(){
  $branchcode = $this->branchcode;
  $resetcode = $this->resetcode;
  $branch_name = strtoupper($this->branch_name . ' ' . 'Branch');
  $postedby = $this->postedby;

  $sales = (new ControllerSale)->ctrResettedCounter($resetcode);

  $item = "empid";
  $value = $postedby;
  $cashier = (new ControllerEmployees)->ctrShowEmployees($item, $value);
  $cashier_name = $cashier['fname'].' '.$cashier['mi'].'. '.$cashier['lname'];

  $current_date = date("m/d/Y"); 

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(180,200), true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  $pdf->setPrintFooter(false);  /*remove line at the bottom of the page*/
  // $pdf->AddPage();

  $width = $pdf->pixelsToUnits(230); 
  $height = $pdf->pixelsToUnits(300);

  $resolution= array($width, $height);
  $pdf->SetMargins(8, 4, 8, true);

  // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  // $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

  $pdf->AddPage('P', $resolution);

  $header = <<<EOF
    <table>
      <tr>
        <td style="width:180px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON FRUITWORLD</td> 
      </tr>

      <tr>
        <td style="width:180px;text-align:center;font-size:10px;">$branch_name</td> 
      </tr> 

      <tr>
        <td style="width:180px;text-align:center;font-size:10px;">$current_date</td> 
      </tr> 

      <tr>
        <td style="width:180px;text-align:center;font-size:1.1em;font-weight:bold;">COUNTER RESET</td> 
      </tr>  

      <tr>
        <td></td>
      </tr> 
 
      <tr style="background-color:#f2f4f7;">
        <td style="border: 1px solid #666;width:110px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Account </td>
        <td style="border: 1px solid #666;width:75px;text-align:right;font-size:11px;">Amount&nbsp;&nbsp;&nbsp;</td>              
      </tr>                  
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
$total_amount = 0.00; 
foreach ($sales as $key => $value) {
  $accountdesc = $value["accountdesc"];
  $tamount = number_format($value['tamount'],2);
  $sale_amount = $value['tamount'];
  $total_amount = $total_amount + $sale_amount;

  $content = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="width:110px;text-align:left;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp;&nbsp;$accountdesc</td>
        <td style="width:75px;text-align:right;font-size:11px;border-right: 1px solid black;">$tamount</td>              
      </tr>                 
    </table>
EOF;
  $pdf->writeHTML($content, false, false, false, false, '');
}
  $total_sales = number_format($total_amount,2);
  $footer = <<<EOF
	    <table style="border: none;">
        <tr>
          <td style="width:110px;text-align:right;font-size:11px;border-top: 1px solid black;font-weight:bold;">TOTAL SALES</td>
          <td style="width:75px;text-align:right;font-size:11px;border-top: 1px solid black;font-weight:bold;">$total_sales</td>
        </tr>

        <tr>
          <td style="width:185px;"></td>
        </tr>        
              
	      <tr>
	        <td style="width:180px;font-size:11px;">Cashiered by:</td>
        </tr>
	      <tr>
	        <td style="width:180px;font-size:11px;"></td>	      
	      </tr>
	      <tr>
	        <td style="width:110px;border-bottom: 1px solid black;font-size:11px;"></td>
        </tr>
	      <tr>
	        <td style="width:110px;font-size:11px;text-align: left">$cashier_name</td>
	      </tr> 
        <tr>
          <td style="width:180px;font-size:11px;"></td>       
        </tr>     
	    </table>
EOF;
  $pdf->writeHTML($footer, false, false, false, false, '');  

  // write some JavaScript code
  $js = "alert('mom');";
  $pdf->IncludeJS($js);

  $pdf->Output('resetcounter.pdf', 'I');
 }
}

$resetcounter = new printCounterReset();
$resetcounter -> branchcode = $_GET["branchcode"];
$resetcounter -> resetcode = $_GET["resetcode"];
$resetcounter -> branch_name = $_GET["branch_name"];
$resetcounter -> postedby = $_GET["postedby"];
$resetcounter -> getCounterReset();
?>