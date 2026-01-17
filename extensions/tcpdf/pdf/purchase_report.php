<?php
session_start();

require_once "../../../controllers/purchasereport.controller.php";
require_once "../../../models/purchasereport.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

require_once "../../../controllers/suppliers.controller.php";
require_once "../../../models/suppliers.model.php";

class printPurchase{
 public $start_deldate;
 public $end_deldate;
 public $supplier;
 public $category;
 public $report_type;

public function getPurchasePrinting(){
  $id = "id";
  $empid = $_SESSION["idEmployee"];
  $employee = (new ControllerEmployees)->ctrShowEmployees($id, $empid);
  $generated_by = $employee['fname'].' '.$employee['lname'];  

  $start_deldate = $this->start_deldate;
  $end_deldate = $this->end_deldate;
  $supplier = $this->supplier;
  $category = $this->category;
  $report_type = $this->report_type;
  $purchase = (new ControllerPurchaseReport)->ctrShowPurchaseReport($start_deldate,$end_deldate,$supplier,$category,$report_type);
  $nRec = count($purchase);

  // Date Label
  if ($start_deldate == $end_deldate){
    $purchase_date = 'Date: '.substr($start_deldate,5,2)."/".substr($start_deldate,8,2)."/".substr($start_deldate,0,4);
  }else{
    $purchase_date = 'From '.substr($start_deldate,5,2)."/".substr($start_deldate,8,2)."/".substr($start_deldate,0,4).' To '.substr($end_deldate,5,2)."/".substr($end_deldate,8,2)."/".substr($end_deldate,0,4);
  }

  if ($supplier != ''){
    $id = "id";
    $idSupplier = $supplier;
    $supplier = (new ControllerSuppliers)->ctrShowSuppliers($id, $supplier);
    $supplier_name = $supplier['sname']; 
  }else{
    $supplier_name = '';
  }   

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  // $pdf->SetLeftMargin(20);
  // $pdf->AddPage();

  // $pdf->AddPage('L', 'LEGAL');

// ================================================================
//                      Overall Purchase Category
// ================================================================ 
  if ($report_type == 1){
    $pdf->AddPage();   /*short-size portrait*/
    $header = <<<EOF
    <table>
      <tr>
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
      </tr>

      <tr>
        <td style="width:540px;text-align:center;font-size:10px;">Purok Paho, Brgy. Felisa, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">OVERALL PURCHASE CATEGORY</td> 
      </tr>   

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:90px;"></td>
        <td style="width:180px;text-align:left;font-size:11px;">$supplier_name</td>
        <td style="width:180px;text-align:right;font-size:11px;">$purchase_date</td>
      </tr>

      <tr>
        <td style="width:90px;"></td>

        <td style="border: 1px solid #666;width:175px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Category</td>
                                                            
        <td style="border: 1px solid #680;width:85px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #680;width:100px;text-align:right;font-size:11px;">Cost &nbsp;&nbsp;&nbsp;</td>               
      </tr>                   
    </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $i = 0;  
  foreach ($purchase as $key => $value) {
    $i = $i + 1;
    $cdesc = $value["cdesc"];
    $total_qty = number_format($value["total_qty"]);
    $total_cost = number_format($value["total_cost"],2);
    
    if ($i < $nRec){
      $content = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:90px;"></td>

          <td style="width:175px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cdesc</td>
                                                              
          <td style="width:85px;text-align:right;font-size:11px;">$total_qty &nbsp;&nbsp;&nbsp;</td>

          <td style="width:100px;text-align:right;font-size:11px;">$total_cost &nbsp;&nbsp;&nbsp;</td>             
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');
    }else{
      $overall_cost = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:90px;"></td>

          <td style="width:175px;text-align:left;font-size:11px;border-top:1px solid black;font-weight:bold;">&nbsp;&nbsp;&nbsp;OVERALL COST</td>

          <td style="width:85px;text-align:right;font-size:11px;border-top:1px solid black;font-weight:bold;">$total_qty &nbsp;&nbsp;&nbsp;</td>

          <td style="width:100px;text-align:right;font-size:11px;border-top:1px solid black;font-weight:bold;">$total_cost &nbsp;&nbsp;&nbsp;</td>             
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($overall_cost, false, false, false, false, '');      
    }       
  } 

  $footer = <<<EOF
    <table style="border: none;">    
      <tr>
        <td></td>
      </tr>
      <tr>  
        <td style="width:450px;text-align:right;font-size:8px;">Generated by</td>
      </tr> 
      <tr>  
        <td style="width:450px;text-align:right;font-size:10px;">$generated_by</td>
      </tr>                              
    </table>
EOF;
      $pdf->writeHTML($footer, false, false, false, false, '');     
    }  //end report-type

// ================================================================
//               Purchase Category + Material Description
// ================================================================  

  if ($report_type == 2){
    $pdf->AddPage('L', 'LETTER');
    // $pdf->AddPage();  /*short-size portrait*/
    $header = <<<EOF
    <table>
      <tr>
        <td style="width:695px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
      </tr>

      <tr>
        <td style="width:695px;text-align:center;font-size:10px;">Purok Paho, Brgy. Felisa, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:695px;text-align:center;font-size:1.2em;font-weight:bold;">PURCHASE CATEGORY AND MATERIAL DESCRIPTION</td> 
      </tr>   

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:45px;"></td>
        <td style="width:315px;text-align:left;font-size:11px;">$supplier_name</td>
        <td style="width:315px;text-align:right;font-size:11px;">$purchase_date</td>
      </tr>

      <tr>
        <td style="width:45px;"></td>

        <td style="border: 1px solid #666;width:120px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Category</td>        

        <td style="border: 1px solid #666;width:240px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Material(s)</td>
                                                            
        <td style="border: 1px solid #680;width:85px;text-align:right;font-size:11px;">Qty (SKU) &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #680;width:85px;text-align:right;font-size:11px;">Qty (Whole) &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #680;width:100px;text-align:right;font-size:11px;">Cost &nbsp;&nbsp;&nbsp;</td>               
      </tr>                   
    </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $i = 0;  
  $curr_desc = '';
  $prev_cdesc = '';
  foreach ($purchase as $key => $value) {
    $i = $i + 1;
    $cdesc = $value["cdesc"];
    $mdesc = $value["mdesc"];
    $meas = $value["meas"];
    $eqnum1 = $value["eqnum1"];
    $m1 = $value["m1"];

    if ($mdesc == null){
      $mdesc = '';
      $cdesc = '';
    }else{
      if ($i == 1){
        $prev_cdesc = $cdesc;
      }else{
        $curr_desc = $cdesc;
        if ($prev_cdesc == $curr_desc){
          $cdesc = '';
        }
        $prev_cdesc = $curr_desc;
      }                 
    }

    $whole_qty = 0.00;
    if (($meas != $m1)&&($eqnum1>0.00)){
      $whole_qty = $value["total_qty"] / $eqnum1;
    }else{
      $whole_qty = '';
    }

    $total_qty = number_format($value["total_qty"]);
    $total_cost = number_format($value["total_cost"],2);
    
    if ($value["mdesc"] == null){
      $content = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:45px;"></td>

          <td style="width:120px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cdesc</td>        

          <td style="width:240px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$mdesc</td>                    

          <td style="width:85px;text-align:right;font-size:11px;border-top: 1px solid black;font-weight:bold;">$total_qty &nbsp;&nbsp;&nbsp;</td>                                         

          <td style="width:85px;text-align:right;font-size:11px;font-weight:bold;"> &nbsp;&nbsp;&nbsp;</td>

          <td style="width:100px;text-align:right;font-size:11px;border-top: 1px solid black;font-weight:bold;">$total_cost &nbsp;&nbsp;&nbsp;</td>                    
        </tr> 
        <tr>
          <td></td>
        </tr>                
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');
    }else{
      $sub_total = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:45px;"></td>

          <td style="width:120px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cdesc</td>        

          <td style="width:240px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$mdesc</td>

          <td style="width:85px;text-align:right;font-size:11px;">$total_qty &nbsp;&nbsp;&nbsp;</td>

          <td style="width:85px;text-align:right;font-size:11px;">$whole_qty &nbsp;&nbsp;&nbsp;</td>

          <td style="width:100px;text-align:right;font-size:11px;">$total_cost &nbsp;&nbsp;&nbsp;</td>
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($sub_total, false, false, false, false, '');      
    }       
  } 

  $footer = <<<EOF
    <table style="border: none;">    
      <tr>
        <td></td>
      </tr>
      <tr>  
        <td style="width:675px;text-align:right;font-size:8px;">Generated by</td>
      </tr> 
      <tr>  
        <td style="width:675px;text-align:right;font-size:10px;">$generated_by</td>
      </tr>                              
    </table>
EOF;
      $pdf->writeHTML($footer, false, false, false, false, '');     
    }  //end report-type  




// ================================================================
//                Purchase Sequence by PO Details
// ================================================================  

  if ($report_type == 4){
    $pdf->AddPage('L', 'LEGAL');
    $header = <<<EOF
    <table>
      <tr>
        <td style="width:20px;"></td>
        <td style="width:903px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
      </tr>

      <tr>
        <td style="width:20px;"></td>
        <td style="width:903px;text-align:center;font-size:10px;">Purok Paho, Brgy. Felisa, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:20px;"></td>
        <td style="width:903px;text-align:center;font-size:1.2em;font-weight:bold;">PURCHASE SEQUENCE BY INVOICE DETAILS</td> 
      </tr>   

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:20px;"></td>
        <td style="width:451px;text-align:left;font-size:11px;"></td>
        <td style="width:452px;text-align:right;font-size:11px;">$purchase_date</td>
      </tr>

      <tr>
        <td style="width:20px;"></td>

        <td style="border: 1px solid #666;width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Date</td>

        <td style="border: 1px solid #666;width:90px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;PO #</td>         

        <td style="border: 1px solid #666;width:90px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;IS #</td>  

        <td style="border: 1px solid #666;width:185px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Supplier</td>              

        <td style="border: 1px solid #666;width:230px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Material(s)</td>
                                                            
        <td style="border: 1px solid #680;width:68px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #680;width:75px;text-align:right;font-size:11px;">Cost &nbsp;&nbsp;&nbsp;</td>        

        <td style="border: 1px solid #680;width:90px;text-align:right;font-size:11px;">Total Cost &nbsp;&nbsp;&nbsp;</td>               
      </tr>                   
    </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $i = 0;  
  $prev_deldate = '';
  $prev_delnumber = '';
  foreach ($purchase as $key => $value) {
    $i = $i + 1;
    // $deldate = $value["deldate"];
    $deldate = substr($value["deldate"],5,2)."/".substr($value["deldate"],8,2)."/".substr($value["deldate"],0,4);
    $ponumber = $value["ponumber"];
    $delnumber = $value["delnumber"];
    $sname = $value["sname"]; 
    $mdesc = $value["mdesc"];
    $qty = number_format($value["qty"]);
    $ucost = number_format($value["ucost"],4);
    $tcost = number_format($value["tcost"],2);  

    if ($mdesc == null){
      $ponumber = '';
      $delnumber = '';
      $sname = '';
      $mdesc = '';
      $ucost = '';
      $deldate = '';
    }else{
      if ($i == 0){
        $prev_delnumber = $value["delnumber"];
        $prev_deldate = $value["deldate"];
      }else{
        $curr_delnumber = $value["delnumber"];
        if ($prev_delnumber == $curr_delnumber){
          $ponumber = '';
          $delnumber = '';
          $sname = '';
        }
        $prev_delnumber = $curr_delnumber;
        $curr_deldate = $value["deldate"];
        if ($prev_deldate == $curr_deldate){
          $deldate = '';
        }
        $prev_deldate = $curr_deldate;                    
      }                 
    }
    
    if ($value["mdesc"] != null){
      $content = <<<EOF
      <table style="border: none;">    
        <tr>
        <td style="width:20px;"></td>

        <td style="width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$deldate</td>         

        <td style="width:90px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$ponumber</td> 

        <td style="width:90px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$delnumber</td>  

        <td style="width:185px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$sname</td>              

        <td style="width:230px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$mdesc</td>
                                                            
        <td style="width:68px;text-align:right;font-size:11px;">$qty &nbsp;&nbsp;&nbsp;</td>

        <td style="width:75px;text-align:right;font-size:11px;">$ucost &nbsp;&nbsp;&nbsp;</td>        

        <td style="width:90px;text-align:right;font-size:11px;">$tcost &nbsp;&nbsp;&nbsp;</td>                    
        </tr>                
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');
    }else{
      $sub_total = <<<EOF
      <table style="border: none;">    
        <tr>
        <td style="width:20px;"></td>

        <td style="width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$deldate</td>      

        <td style="width:90px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$ponumber</td>   

        <td style="width:90px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$delnumber</td>  

        <td style="width:185px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$sname</td>              

        <td style="width:230px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$mdesc</td>
                                                            
        <td style="width:68px;text-align:right;font-size:11px;font-size:11px;border-top: 1px solid black;font-weight:bold;">$qty &nbsp;&nbsp;&nbsp;</td>

        <td style="width:75px;text-align:right;font-size:11px;">$ucost &nbsp;&nbsp;&nbsp;</td>        

        <td style="width:90px;text-align:right;font-size:11px;font-size:11px;border-top: 1px solid black;font-weight:bold;">$tcost &nbsp;&nbsp;&nbsp;</td> 
        </tr>  

        <tr>
          <td></td>
        </tr>                        
      </table>
EOF;
      $pdf->writeHTML($sub_total, false, false, false, false, '');      
    }       
  } 

  $footer = <<<EOF
    <table style="border: none;">    
      <tr>
        <td></td>
      </tr>
      <tr>  
        <td style="width:923px;text-align:right;font-size:8px;">Generated by</td>
      </tr> 
      <tr>  
        <td style="width:923px;text-align:right;font-size:10px;">$generated_by</td>
      </tr>                              
    </table>
EOF;
      $pdf->writeHTML($footer, false, false, false, false, '');     
    }  //end report-type      

    $pdf->Output('purchase_report.pdf', 'I');
   }
  }  

  $purchaseForm = new printPurchase();
  $purchaseForm -> start_deldate = $_GET["deldate"];
  $purchaseForm -> end_deldate = $_GET["edate"];
  $purchaseForm -> supplier = $_GET["supplier"];
  $purchaseForm -> category = $_GET["category"];
  $purchaseForm -> report_type = $_GET["report_type"];
  $purchaseForm -> getPurchasePrinting();
?>