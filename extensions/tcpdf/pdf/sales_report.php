<?php
session_start();

require_once "../../../controllers/salesreport.controller.php";
require_once "../../../models/salesreport.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

require_once "../../../controllers/clients.controller.php";
require_once "../../../models/clients.model.php";

require_once "../../../controllers/prodcategories.controller.php";
require_once "../../../models/prodcategories.model.php";

class printSales{
 public $start_sdate;
 public $end_sdate;
 public $customer;
 public $category;
 public $report_type;
 public $category_name;
 public $sales_status;

public function getSalesPrinting(){
  $id = "id";
  $empid = $_SESSION["idEmployee"];
  $employee = (new ControllerEmployees)->ctrShowEmployees($id, $empid);
  $generated_by = $employee['fname'].' '.$employee['lname'];  

  $start_sdate = $this->start_sdate;
  $end_sdate = $this->end_sdate;
  $customer = $this->customer;

  $category = $this->category;
  if ($category != ''){  
    $category_detail = (new ControllerProdcategories)->ctrShowProdcategories($id, $category);
    $category_name = $category_detail['cdesc'];
  }else{
    $category_name = '';
  }  

  $report_type = $this->report_type;
  $sales_status = $this->sales_status;
  $sales = (new ControllerSalesReport)->ctrShowSalesReport($start_sdate,$end_sdate,$customer,$category,$report_type,$sales_status);
  $nRec = count($sales);

  // Date Label
  if ($start_sdate == $end_sdate){
    $sales_date = 'Date: '.substr($start_sdate,5,2)."/".substr($start_sdate,8,2)."/".substr($start_sdate,0,4);
  }else{
    $sales_date = 'From '.substr($start_sdate,5,2)."/".substr($start_sdate,8,2)."/".substr($start_sdate,0,4).' To '.substr($end_sdate,5,2)."/".substr($end_sdate,8,2)."/".substr($end_sdate,0,4);
  }

  if ($customer != ''){
    $id = "id";
    $clientid = $customer;
    $client = (new ControllerClients)->ctrShowClients($id, $customer);
    $client_name = $client['cname']; 
  }else{
    $client_name = '';
  }   

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  // $pdf->SetLeftMargin(20);
  // $pdf->AddPage();

  // $pdf->AddPage('L', 'LEGAL');

// ================================================================
//                      Overall Sales Category
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
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">OVERALL SALES CATEGORY</td> 
      </tr>   

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:90px;"></td>
        <td style="width:180px;text-align:left;font-size:11px;">$client_name</td>
        <td style="width:180px;text-align:right;font-size:11px;">$sales_date</td>
      </tr>

      <tr>
        <td style="width:90px;"></td>

        <td style="border: 1px solid #666;width:175px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Category</td>
                                                            
        <td style="border: 1px solid #680;width:85px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #680;width:100px;text-align:right;font-size:11px;">Amount &nbsp;&nbsp;&nbsp;</td>               
      </tr>                   
    </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $i = 0;  
  foreach ($sales as $key => $value) {
    $i = $i + 1;
    $cdesc = $value["cdesc"];
    $total_qty = number_format($value["total_qty"]);
    $total_amount = number_format($value["total_amount"],2);
    
    if ($i < $nRec){
      $content = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:90px;"></td>

          <td style="width:175px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cdesc</td>
                                                              
          <td style="width:85px;text-align:right;font-size:11px;">$total_qty &nbsp;&nbsp;&nbsp;</td>

          <td style="width:100px;text-align:right;font-size:11px;">$total_amount &nbsp;&nbsp;&nbsp;</td>             
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');
    }else{
      $overall_amount = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:90px;"></td>

          <td style="width:175px;text-align:left;font-size:11px;border-top:1px solid black;font-weight:bold;">&nbsp;&nbsp;&nbsp;OVERALL AMOUNT</td>

          <td style="width:85px;text-align:right;font-size:11px;border-top:1px solid black;font-weight:bold;">$total_qty &nbsp;&nbsp;&nbsp;</td>

          <td style="width:100px;text-align:right;font-size:11px;border-top:1px solid black;font-weight:bold;">$total_amount &nbsp;&nbsp;&nbsp;</td>             
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($overall_amount, false, false, false, false, '');      
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
//               Sales Category + Product Description
// ================================================================  

  if ($report_type == 2){
    $pdf->AddPage();  /*short-size portrait*/
    $header = <<<EOF
    <table>
      <tr>
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
      </tr>

      <tr>
        <td style="width:540px;text-align:center;font-size:10px;">Purok Paho, Brgy. Felisa, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">SALES CATEGORY AND PRODUCT DESCRIPTION</td> 
      </tr>   

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:25px;"></td>
        <td style="width:240px;text-align:left;font-size:11px;">$client_name</td>
        <td style="width:240px;text-align:right;font-size:11px;">$sales_date</td>
      </tr>

      <tr>
        <td style="width:25px;"></td>

        <td style="border: 1px solid #666;width:120px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Category</td>        

        <td style="border: 1px solid #666;width:175px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Product(s)</td>
                                                            
        <td style="border: 1px solid #680;width:85px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #680;width:100px;text-align:right;font-size:11px;">Amount &nbsp;&nbsp;&nbsp;</td>               
      </tr>                   
    </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $i = 0;  
  $curr_desc = '';
  $prev_cdesc = '';
  foreach ($sales as $key => $value) {
    $i = $i + 1;
    $cdesc = $value["cdesc"];
    $pdesc = $value["pdesc"];

    if ($pdesc == null){
      $pdesc = '';
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

    $total_qty = number_format($value["total_qty"]);
    $total_amount = number_format($value["total_amount"],2);
    
    if ($value["pdesc"] == null){
      $content = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:25px;"></td>

          <td style="width:120px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cdesc</td>        

          <td style="width:182px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pdesc</td>                                                             

          <td style="width:78px;text-align:right;font-size:11px;border-top: 1px solid black;font-weight:bold;">$total_qty &nbsp;&nbsp;&nbsp;</td>

          <td style="width:100px;text-align:right;font-size:11px;border-top: 1px solid black;font-weight:bold;">$total_amount &nbsp;&nbsp;&nbsp;</td>                    
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
          <td style="width:25px;"></td>

          <td style="width:120px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cdesc</td>        

          <td style="width:182px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pdesc</td>

          <td style="width:78px;text-align:right;font-size:11px;">$total_qty &nbsp;&nbsp;&nbsp;</td>

          <td style="width:100px;text-align:right;font-size:11px;">$total_amount &nbsp;&nbsp;&nbsp;</td>
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
        <td style="width:505px;text-align:right;font-size:8px;">Generated by</td>
      </tr> 
      <tr>  
        <td style="width:505px;text-align:right;font-size:10px;">$generated_by</td>
      </tr>                              
    </table>
EOF;
      $pdf->writeHTML($footer, false, false, false, false, '');     
    }  //end report-type  




// ================================================================
//                Sales Sequence by Invoice Details
// ================================================================  

  if ($report_type == 3){
    $pdf->AddPage('L', 'LEGAL');
    $header = <<<EOF
    <table>
      <tr>
        <td style="width:25px;"></td>
        <td style="width:851px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
      </tr>

      <tr>
        <td style="width:25px;"></td>
        <td style="width:851px;text-align:center;font-size:10px;">Purok Paho, Brgy. Felisa, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:25px;"></td>
        <td style="width:851px;text-align:center;font-size:1.2em;font-weight:bold;">SALES SEQUENCE BY INVOICE DETAILS</td> 
      </tr>   

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:25px;"></td>
        <td style="width:442px;text-align:left;font-size:11px;"></td>
        <td style="width:442px;text-align:right;font-size:11px;">$sales_date</td>
      </tr>

      <tr>
        <td style="width:25px;"></td>

        <td style="border: 1px solid #666;width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Date</td>         

        <td style="border: 1px solid #666;width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Invoice #</td>  

        <td style="border: 1px solid #666;width:200px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Customer</td>              

        <td style="border: 1px solid #666;width:300px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Product(s)</td>
                                                            
        <td style="border: 1px solid #680;width:68px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #680;width:75px;text-align:right;font-size:11px;">Price &nbsp;&nbsp;&nbsp;</td>        

        <td style="border: 1px solid #680;width:90px;text-align:right;font-size:11px;">Amount &nbsp;&nbsp;&nbsp;</td>               
      </tr>                   
    </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $i = 0;  
  $prev_sdate = '';
  $prev_invno = '';
  foreach ($sales as $key => $value) {
    $i = $i + 1;
    // $sdate = $value["sdate"];
    $sdate = substr($value["sdate"],5,2)."/".substr($value["sdate"],8,2)."/".substr($value["sdate"],0,4);
    $invno = $value["invno"];
    $csi = $value["csi"];
    $ponumber = $value["ponumber"];
    $cname = $value["cname"]; 
    $pdesc = $value["pdesc"];
    $qty = number_format($value["qty"]);
    $uprice = number_format($value["uprice"],2);
    $tamount = number_format($value["tamount"],2);  

    if ($pdesc == null){
      $invno = '';
      $cname = '';
      $pdesc = '';
      $uprice = '';
      $sdate = '';
    }else{
      if ($i == 0){
        $prev_invno = $value["invno"];
        $prev_sdate = $value["sdate"];
      }else{
        $curr_invno = $value["invno"];
        if ($prev_invno == $curr_invno){
          $invno = '';
          $cname = '';
        }
        $prev_invno = $curr_invno;
        $curr_sdate = $value["sdate"];
        if ($prev_sdate == $curr_sdate){
          $sdate = '';
        }
        $prev_sdate = $curr_sdate;                    
      }                 
    }
    
    if ($value["pdesc"] != null){
      $content = <<<EOF
      <table style="border: none;">    
        <tr>
        <td style="width:25px;"></td>

        <td style="width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$sdate</td>         

        <td style="width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$invno</td>  

        <td style="width:200px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cname</td>              

        <td style="width:300px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pdesc</td>
                                                            
        <td style="width:68px;text-align:right;font-size:11px;">$qty &nbsp;&nbsp;&nbsp;</td>

        <td style="width:75px;text-align:right;font-size:11px;">$uprice &nbsp;&nbsp;&nbsp;</td>        

        <td style="width:90px;text-align:right;font-size:11px;">$tamount &nbsp;&nbsp;&nbsp;</td>                    
        </tr>                
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');
    }else{
      $sub_total = <<<EOF
      <table style="border: none;">    
        <tr>
        <td style="width:25px;"></td>

        <td style="width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$sdate</td>         

        <td style="width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$invno</td>  

        <td style="width:200px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cname</td>              

        <td style="width:300px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pdesc</td>
                                                            
        <td style="width:68px;text-align:right;font-size:11px;font-size:11px;border-top: 1px solid black;font-weight:bold;">$qty &nbsp;&nbsp;&nbsp;</td>

        <td style="width:75px;text-align:right;font-size:11px;">$uprice &nbsp;&nbsp;&nbsp;</td>        

        <td style="width:90px;text-align:right;font-size:11px;font-size:11px;border-top: 1px solid black;font-weight:bold;">$tamount &nbsp;&nbsp;&nbsp;</td> 
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
        <td style="width:917px;text-align:right;font-size:8px;">Generated by</td>
      </tr> 
      <tr>  
        <td style="width:917px;text-align:right;font-size:10px;">$generated_by</td>
      </tr>                              
    </table>
EOF;
      $pdf->writeHTML($footer, false, false, false, false, '');     
    }  //end report-type      



// ================================================================
//                 Customer Product Category Summary
// ================================================================ 
  if ($report_type == 4){
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
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">CUSTOMER PRODUCT CATEGORY SUMMARY</td> 
      </tr>  

      <tr>
        <td style="width:540px;text-align:center;font-size:10px;">$category_name</td> 
      </tr>        

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:60px;"></td>
        <td style="width:180px;text-align:left;font-size:11px;">$client_name</td>
        <td style="width:250px;text-align:right;font-size:11px;">$sales_date</td>
      </tr>

      <tr>
        <td style="width:60px;"></td>

        <td style="border: 1px solid #666;width:250px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Customers</td>
                                                            
        <td style="border: 1px solid #680;width:85px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>

        <td style="border: 1px solid #680;width:100px;text-align:right;font-size:11px;">Amount &nbsp;&nbsp;&nbsp;</td>               
      </tr>                   
    </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $i = 0;  
  foreach ($sales as $key => $value) {
    $i = $i + 1;
    $cname = $value["cname"];
    $total_qty = number_format($value["total_qty"]);
    $total_amount = number_format($value["total_amount"],2);
    
    if ($i < $nRec){
      $content = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:60px;"></td>

          <td style="width:250px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cname</td>
                                                              
          <td style="width:85px;text-align:right;font-size:11px;">$total_qty &nbsp;&nbsp;&nbsp;</td>

          <td style="width:100px;text-align:right;font-size:11px;">$total_amount &nbsp;&nbsp;&nbsp;</td>             
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');
    }else{
      $overall_amount = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:60px;"></td>

          <td style="width:250px;text-align:left;font-size:11px;border-top:1px solid black;font-weight:bold;">&nbsp;&nbsp;&nbsp;OVERALL AMOUNT</td>

          <td style="width:85px;text-align:right;font-size:11px;border-top:1px solid black;font-weight:bold;">$total_qty &nbsp;&nbsp;&nbsp;</td>

          <td style="width:100px;text-align:right;font-size:11px;border-top:1px solid black;font-weight:bold;">$total_amount &nbsp;&nbsp;&nbsp;</td>             
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($overall_amount, false, false, false, false, '');      
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


    $pdf->Output('sales_report.pdf', 'I');
   }
  }  

  $salesForm = new printSales();
  $salesForm -> start_sdate = $_GET["sdate"];
  $salesForm -> end_sdate = $_GET["edate"];
  $salesForm -> customer = $_GET["customer"];
  $salesForm -> category = $_GET["category"];
  $salesForm -> report_type = $_GET["report_type"];
  $salesForm -> sales_status = $_GET["sales_status"];
  $salesForm -> getSalesPrinting();
?>