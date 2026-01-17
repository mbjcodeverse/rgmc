<?php
session_start();

require_once "../../../controllers/report.controller.php";
require_once "../../../models/report.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

require_once "../../../controllers/products.controller.php";
require_once "../../../models/products.model.php";

class printReport{
 public $start_date;
 public $end_date;
 public $category;
 public $trans_type;

public function getReportPrinting(){
  $id = "id";
  $empid = $_SESSION["idEmployee"];
  $employee = (new ControllerEmployees)->ctrShowEmployees($id, $empid);
  $generated_by = $employee['fname'].' '.$employee['lname'];  

  $start_date = $this->start_date;
  $end_date = $this->end_date;

  $category = $this->category;
  if ($category != ''){  
    $category_detail = (new ControllerProducts)->ctrGetProductCategory($id, $category);
    $category_name = $category_detail['catname'];
  }else{
    $category_name = '';
  }  

  $trans_type = $this->trans_type;
  if ($trans_type == 'Stock Replenishment'){
    $trans_title = "STOCK-IN PRODUCT CATEGORY AND DESCRIPTION";
  }else{
    $trans_title = "STOCK-OUT PRODUCT CATEGORY AND DESCRIPTION";
  }

  $recordset = (new ControllerReport)->ctrShowCatdescReport($start_date,$end_date,$category,$trans_type);
  $nRec = count($recordset);

  // Date Label
  if ($start_date == $end_date){
    $report_date = 'Date: '.substr($start_date,5,2)."/".substr($start_date,8,2)."/".substr($start_date,0,4);
  }else{
    $report_date = 'From '.substr($start_date,5,2)."/".substr($start_date,8,2)."/".substr($start_date,0,4).' To '.substr($end_date,5,2)."/".substr($end_date,8,2)."/".substr($end_date,0,4);
  }  

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  // $pdf->SetLeftMargin(20);
  // $pdf->AddPage();

  // $pdf->AddPage('L', 'LEGAL');  


  $pdf->AddPage();  /*short-size portrait*/
  $header = <<<EOF
  <table>
    <tr>
      <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON COMMERCIAL WAREHOUSING</td> 
    </tr>

    <tr>
      <td style="width:540px;text-align:center;font-size:10px;">Sharina Hts, Bacolod City</td> 
    </tr>  

    <tr>
      <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">$trans_title</td> 
    </tr>   

    <tr>
      <td></td>
    </tr>

    <tr>
      <td style="width:25px;"></td>
      <td style="width:240px;text-align:left;font-size:11px;">$category_name</td>
      <td style="width:240px;text-align:right;font-size:11px;">$report_date</td>
    </tr>

    <tr>
        <td style="width:25px;"></td>

        <td style="border: 1px solid #666;width:150px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Category</td>        

        <td style="border: 1px solid #666;width:255px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Product(s)</td>
                                                            
        <td style="border: 1px solid #680;width:75px;text-align:right;font-size:11px;">Qty &nbsp;&nbsp;&nbsp;</td>               
      </tr>                          
  </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $i = 0;  
  $curr_desc = '';
  $prev_catname = '';
  foreach ($recordset as $key => $value) {
    $i = $i + 1;
    $catname = $value["catname"];
    $pdesc = $value["pdesc"];

    if ($pdesc == null){
      $pdesc = '';
      $catname = '';
    }else{
      if ($i == 1){
        $prev_catname = $catname;
      }else{
        $curr_desc = $catname;
        if ($prev_catname == $curr_desc){
          $catname = '';
        }
        $prev_catname = $curr_desc;
      }                 
    }
    $qty = number_format($value["qty"]);
    
   if ($value["pdesc"] == null){
   $subtotal = <<<EOF
    <table style="border: none;">    
      <tr>
        <td style="width:25px;"></td>

        <td style="width:150px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$catname</td>        

        <td style="width:287px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pdesc</td>

        <td style="width:48px;text-align:right;font-size:11px;border-top: 1px solid black;font-weight:bold;">$qty &nbsp;&nbsp;&nbsp;</td>
      </tr>         
      <tr>
        <td></td>
      </tr>        
    </table>
EOF;
      $pdf->writeHTML($subtotal, false, false, false, false, '');
  }else{
    $content = <<<EOF
     <table style="border: none;">    
        <tr>
          <td style="width:25px;"></td>

          <td style="width:150px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$catname</td>        

          <td style="width:287px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$pdesc</td>

          <td style="width:48px;text-align:right;font-size:11px;">$qty &nbsp;&nbsp;&nbsp;</td>
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');      
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
     

    $pdf->Output('catdescreport.pdf', 'I');
   }
  }  

  $printForm = new printReport();
  $printForm -> start_date = $_GET["start_date"];
  $printForm -> end_date = $_GET["end_date"];
  $printForm -> category = $_GET["category"];
  $printForm -> trans_type = $_GET["trans_type"];
  $printForm -> getReportPrinting();
?>