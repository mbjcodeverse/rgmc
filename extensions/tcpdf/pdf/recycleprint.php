<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once "../../../controllers/recycle.controller.php";
require_once "../../../models/recycle.model.php";

require_once "../../../controllers/category.controller.php";
require_once "../../../models/category.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printRecycleInfo{
public $reptype;
public $machineid;
public $start_date;
public $end_date;
public $categorycode;
public $postedby;
public $recycleby;
public $recstatus;
public $generatedby;

public function getRecycle(){
  $reptype = $this->reptype;
  $machineid = $this->machineid;  
  $start_date = $this->start_date;
  $end_date = $this->end_date;
  if ($start_date == $end_date){
      $trans_date = 'Date: '.substr($start_date,5,2)."/".substr($start_date,8,2)."/".substr($start_date,0,4);
  }else{
      $trans_date = 'From '.substr($start_date,5,2)."/".substr($start_date,8,2)."/".substr($start_date,0,4).' To '.substr($end_date,5,2)."/".substr($end_date,8,2)."/".substr($end_date,0,4);
  }
  $categorycode = $this->categorycode;
  $postedby = $this->postedby;
  $recycleby = $this->recycleby;
  $recstatus = $this->recstatus;
  $generatedby = $this->generatedby;

  $report_content = (new ControllerRecycle)->ctrShowRecycleReport($machineid, $start_date, $end_date, $categorycode, $postedby, $recycleby, $recstatus, $reptype);

  $empid = "empid";
  $generatedby = $this->generatedby;
  $generated_by = (new ControllerEmployees)->ctrShowEmployees($empid, $generatedby);
  if ($generated_by['mi']=='')
    $printed_by = $generated_by['fname'].' '.$generated_by['lname'];
  else  
    $printed_by = $generated_by['fname'].' '.$generated_by['mi'].'. '.$generated_by['lname'];
  
  // Date printed  
  $current_date = date("m/d/Y");
  $nRec = count($report_content);

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  // $pdf->SetLeftMargin(20);
  // $pdf->AddPage();

  // $pdf->AddPage('L', 'LEGAL');
  // $pdf->AddPage('L', 'LETTER');  

  //$pdf->AddPage();  /*short-size portrait*/
  
  if ($reptype == "1"){
      $pdf->AddPage();  
      $header = <<<EOF
      <table>
          <tr>
            <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
          </tr>

          <tr>
            <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">OVERALL RECYCLE CATEGORY</td> 
          </tr>

          <tr>
            <td style="width:540px;text-align:center;font-size:10px;">$trans_date</td> 
          </tr>   

          <tr>
            <td></td>
          </tr>    

          <tr>       
              <td style="width:95px;"></td> 
              <td style="border: 1px solid black;width:160px;text-align:left;font-size:10px;">&nbsp; Category</td>  
              <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;">Qty &nbsp;&nbsp;</td>
              <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;">Amount &nbsp;&nbsp;</td>       
          </tr>                          
      </table>
      EOF;
          $pdf->writeHTML($header, false, false, false, false, '');

    $i = 0;
    foreach ($report_content as $key => $value){
        $catdescription = $value["catdescription"];    
        $total_qty = number_format($value["total_qty"],2);
        $total_amount = number_format($value["total_amount"],2);

        if ($catdescription == null){
            $content = <<<EOF
            <table style="border: none;">    
                <tr>
                    <td style="width:95px;"></td> 
                    <td style="width:160px;text-align:right;font-size:10px;border:1px solid black;">&nbsp;OVERALL AMOUNT</td>     
                    <td style="width:90px;text-align:right;font-size:10px;border:1px solid black;">&nbsp;$total_qty</td>
                    <td style="width:90px;text-align:right;font-size:10px;border:1px solid black;">$total_amount</td>
                </tr>                 
            </table>
        EOF;
            $pdf->writeHTML($content, false, false, false, false, '');  
        }else{
            $content = <<<EOF
            <table style="border: none;">    
                <tr>
                    <td style="width:95px;"></td> 
                    <td style="width:160px;text-align:left;font-size:10px;border-right:1px solid black;">&nbsp;$catdescription</td>     
                    <td style="width:90px;text-align:right;font-size:10px;">&nbsp;$total_qty</td>
                    <td style="width:90px;text-align:right;font-size:10px;">$total_amount</td>
                </tr>                 
            </table>
        EOF;
            $pdf->writeHTML($content, false, false, false, false, '');           
        } 
        $i = $i + 1;
    }

    if ($i > 0){
        $footer = <<<EOF
        <table style="border: none;"> 
          <tr>  
            <td style="width:80px;"></td>
          </tr>
    
          <tr>  
            <td style="width:98px;"></td>
            <td style="width:220px;font-size:9px;">Date: $current_date</td>
            <td style="width:155px;font-size:10px;">Generated by:</td>
          </tr> 
          
          <tr>  
            <td style="width:98px;"></td>
            <td style="width:224px;"></td>
            <td style="width:95px;border-bottom: 1px solid black;"></td>
          </tr>      
        
          <tr>
            <td style="width:98px;"></td>  
            <td style="width:222px;"></td>
            <td style="width:155px;text-align:left;font-size:10px;">$printed_by</td>
          </tr>      
        </table>
    EOF;
          $pdf->writeHTML($footer, false, false, false, false, '');                    
    }
  // ----------------------------------------------------------------------------------------------------------------
  } elseif ($reptype == "2"){
    $pdf->AddPage('L', 'LETTER'); 
    $header = <<<EOF
    <table>
        <tr>
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
        </tr>

        <tr>
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">RECYCLE CATEGORY + DESCRIPTION</td> 
        </tr>

        <tr>
          <td style="width:730px;text-align:center;font-size:10px;">$trans_date</td> 
        </tr>   

        <tr>
          <td></td>
        </tr>    

        <tr>       
            <td style="width:92px;"></td> 
            <td style="border: 1px solid black;width:130px;text-align:left;font-size:9px;">&nbsp; Category</td>  
            <td style="border: 1px solid black;width:260px;text-align:left;font-size:9px;">&nbsp; Product</td>
            <td style="border: 1px solid black;width:80px;text-align:right;font-size:9px;">Qty &nbsp;&nbsp;</td>
            <td style="border: 1px solid black;width:80px;text-align:right;font-size:9px;">Amount &nbsp;&nbsp;</td>       
        </tr>                          
    </table>
    EOF;
        $pdf->writeHTML($header, false, false, false, false, '');

    $i = 0;
    foreach ($report_content as $key => $value){
      $catdescription = $value["catdescription"];
      $pdesc = $value["prodname"] . ' (' . strtoupper($value["meas2"]) . ')';

      if ($value["prodname"] == null){
        $pdesc = '';
        $catdescription = '';
      }else{
        if ($i == 0){
          $prev_catdescription = $value["catdescription"];
        }else{
          $curr_catdescription = $value["catdescription"];
          if ($prev_catdescription == $curr_catdescription){
            $catdescription = '';
          }
          $prev_catdescription = $curr_catdescription;
        }                 
      }

      $total_qty = number_format($value["total_qty"],2);
      $total_amount = number_format($value["total_amount"],2);

      if ($value["prodname"] == null){
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:92px;"></td> 
                <td style="width:130px;text-align:left;font-size:9px;">&nbsp; $catdescription</td>      
                <td style="width:260px;text-align:left;font-size:9px;">&nbsp; $pdesc</td>
                <td style="font-size:9px;font-weight:bold;text-align:right;width:80px;border-top: 1px solid black;">$total_qty</td>
                <td style="font-size:9px;font-weight:bold;text-align:right;width:80px;border-top: 1px solid black;">$total_amount</td>
            </tr>     
            
            <tr>
                <td style="width:92px;"></td>
            </tr>             
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');        
      }else{
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:92px;"></td> 
                <td style="width:130px;text-align:left;font-size:9px;">&nbsp; $catdescription</td>      
                <td style="width:260px;text-align:left;font-size:9px;">&nbsp; $pdesc</td>
                <td style="text-align:right;width:80px;font-size:9px;">$total_qty</td>
                <td style="text-align:right;width:80px;font-size:9px;">$total_amount</td>
            </tr>                 
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');
      }

      $i = $i + 1;
    } // end of FOR statement

    if ($i > 0){
        $footer = <<<EOF
        <table style="border: none;"> 
          <tr>  
            <td style="width:80px;"></td>
          </tr>
    
          <tr>  
            <td style="width:320px;"></td>
            <td style="width:220px;font-size:9px;">Date: $current_date</td>
            <td style="width:155px;font-size:10px;">Generated by:</td>
          </tr> 
          
          <tr>  
            <td style="width:320px;"></td>
            <td style="width:224px;"></td>
            <td style="width:95px;border-bottom: 1px solid black;"></td>
          </tr>      
        
          <tr>
            <td style="width:320px;"></td>  
            <td style="width:222px;"></td>
            <td style="width:155px;text-align:left;font-size:10px;">$printed_by</td>
          </tr>      
        </table>
    EOF;
          $pdf->writeHTML($footer, false, false, false, false, '');                    
    }
  // ----------------------------------------------------------------------------------------------------------------
  } elseif ($reptype == "3"){
      $pdf->AddPage('L', 'LEGAL'); 
      $header = <<<EOF
      <table>
          <tr>
            <td style="width:950px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
          </tr> 

          <tr>
            <td style="width:950px;text-align:center;font-size:1.2em;font-weight:bold;">RECYCLE DATE SEQUENCE DETAILS</td> 
          </tr>

          <tr>
            <td style="width:950px;text-align:center;font-size:10px;">$trans_date</td> 
          </tr>   

          <tr>
            <td></td>
          </tr>    

          <tr>       
              <td style="width:92px;"></td> 
              <td style="border: 1px solid black;width:58px;text-align:left;font-size:9px;">&nbsp; Date</td>  
              <td style="border: 1px solid black;width:82px;text-align:left;font-size:9px;">&nbsp; Prod #</td>
              <td style="border: 1px solid black;width:170px;text-align:left;font-size:9px;">&nbsp; Machine</td>
              <td style="border: 1px solid black;width:290px;text-align:left;font-size:9px;">&nbsp; Products</td>
              <td style="border: 1px solid black;width:55px;text-align:right;font-size:9px;">Qty &nbsp;&nbsp;</td>
              <td style="border: 1px solid black;width:65px;text-align:right;font-size:9px;">Cost &nbsp;&nbsp;</td>
              <td style="border: 1px solid black;width:69px;text-align:right;font-size:9px;">Amount &nbsp;&nbsp;</td>      
          </tr>                          
      </table>
      EOF;
          $pdf->writeHTML($header, false, false, false, false, '');
  

    $i = 0;
    foreach ($report_content as $key => $value){
      $recdate = substr($value["recdate"],5,2)."/".substr($value["recdate"],8,2)."/".substr($value["recdate"],0,4);
      $recnumber = $value["recnumber"];
      $machinedesc = $value["machinedesc"];
      $pdesc = $value["prodname"] . ' (' . strtoupper($value["meas2"]) . ')';

      if ($value["prodname"] == null){
        $recnumber = '';
        $machinedesc = '';
        $pdesc = '';
        $qty = '';
        $price = '';
      }else{
        if ($i == 0){
          $prev_recnumber = $value["recnumber"];
        }else{
          $curr_recnumber = $value["recnumber"];
          if ($prev_recnumber == $curr_recnumber){
            $recdate = '';
            $recnumber = '';
            $machinedesc = '';
          }
          $prev_recnumber = $curr_recnumber;
        }                 
      }

      $qty = number_format($value["qty"],2);
      $price = number_format($value["price"],2);
      $tamount = number_format($value["tamount"],2);

        if ($value["prodname"] == null){
          $content = <<<EOF
          <table style="border: none;">    
              <tr>
                  <td style="width:92px;"></td> 
                  <td style="width:57px;text-align:left;font-size:9px;"></td>    
                  <td style="width:82px;text-align:left;font-size:9px;">&nbsp; $recnumber</td> 
                  <td style="width:170px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>   
                  <td style="width:290px;text-align:left;font-size:9px;">&nbsp; $pdesc</td>
                  <td style="font-size:9px;font-weight:bold;text-align:right;width:55px;"></td>
                  <td style="font-size:9px;font-weight:bold;text-align:right;width:65px;"></td>
                  <td style="font-size:9px;font-weight:bold;text-align:right;width:70px;border-top: 1px solid black;">$tamount</td>
              </tr>  
              
              <tr>
                  <td style="width:92px;"></td>
              </tr>
          </table>
      EOF;
          $pdf->writeHTML($content, false, false, false, false, '');        
        }else{
          $content = <<<EOF
          <table style="border: none;">    
              <tr>
                  <td style="width:92px;"></td> 
                  <td style="width:56px;text-align:left;font-size:9px;">$recdate</td>    
                  <td style="width:82px;text-align:left;font-size:9px;">&nbsp; $recnumber</td> 
                  <td style="width:170px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>   
                  <td style="width:290px;text-align:left;font-size:9px;">&nbsp; $pdesc</td>
                  <td style="font-size:9px;text-align:right;width:55px;">$qty</td>
                  <td style="font-size:9px;text-align:right;width:65px;">$price</td>
                  <td style="font-size:9px;text-align:right;width:70px;">$tamount</td>
              </tr>                 
          </table>
      EOF;
          $pdf->writeHTML($content, false, false, false, false, '');
        }

      $i = $i + 1;
    } // end of FOR statement
    if ($i > 0){
        $footer = <<<EOF
        <table style="border: none;"> 
          <tr>  
            <td style="width:80px;"></td>
          </tr>
    
          <tr>  
            <td style="width:565px;"></td>
            <td style="width:220px;font-size:9px;">Date: $current_date</td>
            <td style="width:155px;font-size:10px;">Generated by:</td>
          </tr> 
          
          <tr>  
            <td style="width:565px;"></td>
            <td style="width:224px;"></td>
            <td style="width:95px;border-bottom: 1px solid black;"></td>
          </tr>      
        
          <tr>
            <td style="width:565px;"></td>  
            <td style="width:222px;"></td>
            <td style="width:155px;text-align:left;font-size:10px;">$printed_by</td>
          </tr>      
        </table>
    EOF;
          $pdf->writeHTML($footer, false, false, false, false, '');                    
    }  
  // ----------------------------------------------------------------------------------------------------------------
  } elseif ($reptype == "4"){
    $pdf->AddPage('L', 'LETTER'); 
    $header = <<<EOF
    <table>
        <tr>
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
        </tr>

        <tr>
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">MACHINE ITEM RECYCLE SUMMATION</td> 
        </tr>

        <tr>
          <td style="width:730px;text-align:center;font-size:10px;">$trans_date</td> 
        </tr>   

        <tr>
          <td></td>
        </tr>    

        <tr>       
            <td style="width:46px;"></td> 
            <td style="border: 1px solid black;width:130px;text-align:left;font-size:9px;">&nbsp; Machine</td>  
            <td style="border: 1px solid black;width:90px;text-align:left;font-size:9px;">&nbsp; Machine #</td>
            <td style="border: 1px solid black;width:260px;text-align:left;font-size:9px;">&nbsp; Product</td>
            <td style="border: 1px solid black;width:80px;text-align:right;font-size:9px;">Qty &nbsp;&nbsp;</td>
            <td style="border: 1px solid black;width:80px;text-align:right;font-size:9px;">Amount &nbsp;&nbsp;</td>       
        </tr>                          
    </table>
    EOF;
        $pdf->writeHTML($header, false, false, false, false, '');

    $i = 0;
    foreach ($report_content as $key => $value){
      $machinedesc = $value["machinedesc"];
      $machabbr = $value["machabbr"];
      $pdesc = $value["prodname"] . ' (' . strtoupper($value["meas2"]) . ')';

      if ($value["prodname"] == null){
        $pdesc = '';
        $machinedesc = '';
        $machabbr = '';
      }else{
        if ($i == 0){
          $prev_machinedesc = $value["machinedesc"];
        }else{
          $curr_machinedesc = $value["machinedesc"];
          if ($prev_machinedesc == $curr_machinedesc){
            $machinedesc = '';
            $machabbr = '';
          }
          $prev_machinedesc = $curr_machinedesc;
        }                 
      }

      $total_qty = number_format($value["qty"],2);
      $total_amount = number_format($value["tamount"],2);

      if ($value["prodname"] == null){
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:46px;"></td> 
                <td style="width:130px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td> 
                <td style="width:90px;text-align:left;font-size:9px;">&nbsp; </td>      
                <td style="width:260px;text-align:left;font-size:9px;">&nbsp; $pdesc</td>
                <td style="font-size:9px;font-weight:bold;text-align:right;width:80px;"></td>
                <td style="font-size:9px;font-weight:bold;text-align:right;width:80px;border-top: 1px solid black;">$total_amount</td>
            </tr>     
            
            <tr>
                <td style="width:92px;"></td>
            </tr>             
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');        
      }else{
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:46px;"></td> 
                <td style="width:130px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>
                <td style="width:90px;text-align:left;font-size:9px;">&nbsp; $machabbr</td>      
                <td style="width:260px;text-align:left;font-size:9px;">&nbsp; $pdesc</td>
                <td style="text-align:right;width:80px;font-size:9px;">$total_qty</td>
                <td style="text-align:right;width:80px;font-size:9px;">$total_amount</td>
            </tr>                 
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');
      }

      $i = $i + 1;
    } // end of FOR statement

    if ($i > 0){
        $footer = <<<EOF
        <table style="border: none;"> 
          <tr>  
            <td style="width:80px;"></td>
          </tr>
    
          <tr>  
            <td style="width:366px;"></td>
            <td style="width:220px;font-size:9px;">Date: $current_date</td>
            <td style="width:155px;font-size:10px;">Generated by:</td>
          </tr> 
          
          <tr>  
            <td style="width:366px;"></td>
            <td style="width:224px;"></td>
            <td style="width:95px;border-bottom: 1px solid black;"></td>
          </tr>      
        
          <tr>
            <td style="width:366px;"></td>  
            <td style="width:222px;"></td>
            <td style="width:155px;text-align:left;font-size:10px;">$printed_by</td>
          </tr>      
        </table>
    EOF;
          $pdf->writeHTML($footer, false, false, false, false, '');                    
    }
  // ----------------------------------------------------------------------------------------------------------------
  } elseif ($reptype == "5"){
      $pdf->AddPage('L', 'LEGAL'); 
      $header = <<<EOF
      <table>
          <tr>
            <td style="width:950px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
          </tr> 

          <tr>
            <td style="width:950px;text-align:center;font-size:1.2em;font-weight:bold;">MACHINE RECYCLE DATE TRAIL</td> 
          </tr>

          <tr>
            <td style="width:950px;text-align:center;font-size:10px;">$trans_date</td> 
          </tr>   

          <tr>
            <td></td>
          </tr>    

          <tr>       
              <td style="width:92px;"></td> 
              <td style="border: 1px solid black;width:170px;text-align:left;font-size:9px;">&nbsp; Machine</td>              
              <td style="border: 1px solid black;width:58px;text-align:left;font-size:9px;">&nbsp; Date</td>  
              <td style="border: 1px solid black;width:82px;text-align:left;font-size:9px;">&nbsp; Rec #</td>
              <td style="border: 1px solid black;width:290px;text-align:left;font-size:9px;">&nbsp; Products</td>
              <td style="border: 1px solid black;width:55px;text-align:right;font-size:9px;">Qty &nbsp;&nbsp;</td>
              <td style="border: 1px solid black;width:65px;text-align:right;font-size:9px;">Cost &nbsp;&nbsp;</td>
              <td style="border: 1px solid black;width:69px;text-align:right;font-size:9px;">Amount &nbsp;&nbsp;</td>      
          </tr>                          
      </table>
      EOF;
          $pdf->writeHTML($header, false, false, false, false, '');
  

    $i = 0;
    foreach ($report_content as $key => $value){
      $recdate = substr($value["recdate"],5,2)."/".substr($value["recdate"],8,2)."/".substr($value["recdate"],0,4);
      $recnumber = $value["recnumber"];
      $machinedesc = $value["machinedesc"];
      $pdesc = $value["prodname"] . ' (' . strtoupper($value["meas2"]) . ')';

      if ($value["prodname"] == null){
        $recnumber = '';
        $machinedesc = '';
        $pdesc = '';
        $qty = '';
        $price = '';
      }else{
        if ($i == 0){
          $prev_recnumber = $value["recnumber"];
          $prev_machinedesc = $value["machinedesc"];
        }else{
          $curr_recnumber = $value["recnumber"];
          if ($prev_recnumber == $curr_recnumber){
            $recdate = '';
            $recnumber = '';
            $machinedesc = '';
          }
          $prev_recnumber = $curr_recnumber;
          // don't display same machine name
          $curr_machinedesc = $value["machinedesc"];
          if ($prev_machinedesc == $curr_machinedesc){
            $machinedesc = '';
          }
          $prev_machinedesc = $curr_machinedesc;
        }                 
      }

      $qty = number_format($value["qty"],2);
      $price = number_format($value["price"],2);
      $tamount = number_format($value["tamount"],2);

        if ($value["prodname"] == null){
          $content = <<<EOF
          <table style="border: none;">    
              <tr>
                  <td style="width:92px;"></td> 
                  <td style="width:57px;text-align:left;font-size:9px;"></td>    
                  <td style="width:82px;text-align:left;font-size:9px;">&nbsp; $recnumber</td> 
                  <td style="width:170px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>   
                  <td style="width:290px;text-align:left;font-size:9px;">&nbsp; $pdesc</td>
                  <td style="font-size:9px;font-weight:bold;text-align:right;width:55px;"></td>
                  <td style="font-size:9px;font-weight:bold;text-align:right;width:65px;"></td>
                  <td style="font-size:9px;font-weight:bold;text-align:right;width:70px;border-top: 1px solid black;">$tamount</td>
              </tr>  
              
              <tr>
                  <td style="width:92px;"></td>
              </tr>
          </table>
      EOF;
          $pdf->writeHTML($content, false, false, false, false, '');        
        }else{
          $content = <<<EOF
          <table style="border: none;">    
              <tr>
                  <td style="width:92px;"></td> 
                  <td style="width:170px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td> 
                  <td style="width:56px;text-align:left;font-size:9px;">$recdate</td>    
                  <td style="width:82px;text-align:left;font-size:9px;">&nbsp; $recnumber</td>   
                  <td style="width:290px;text-align:left;font-size:9px;">&nbsp; $pdesc</td>
                  <td style="font-size:9px;text-align:right;width:55px;">$qty</td>
                  <td style="font-size:9px;text-align:right;width:65px;">$price</td>
                  <td style="font-size:9px;text-align:right;width:70px;">$tamount</td>
              </tr>                 
          </table>
      EOF;
          $pdf->writeHTML($content, false, false, false, false, '');
        }
        // the query in prodfin.model.php for report #5, display extra row after the grand total
        // to put a halt of displaying the rows after the grand total, the condition below is made.
        // try copy and paste the SQL command from the model file, then go to XAMPP
        if ($value["machinedesc"] == ''){
          break;
        }

        $i = $i + 1;
    } // end of FOR statement
    if ($i > 0){
        $footer = <<<EOF
        <table style="border: none;"> 
          <tr>  
            <td style="width:80px;"></td>
          </tr>
    
          <tr>  
            <td style="width:565px;"></td>
            <td style="width:220px;font-size:9px;">Date: $current_date</td>
            <td style="width:155px;font-size:10px;">Generated by:</td>
          </tr> 
          
          <tr>  
            <td style="width:565px;"></td>
            <td style="width:224px;"></td>
            <td style="width:95px;border-bottom: 1px solid black;"></td>
          </tr>      
        
          <tr>
            <td style="width:565px;"></td>  
            <td style="width:222px;"></td>
            <td style="width:155px;text-align:left;font-size:10px;">$printed_by</td>
          </tr>      
        </table>
    EOF;
          $pdf->writeHTML($footer, false, false, false, false, '');                    
    }  
  // ----------------------------------------------------------------------------------------------------------------
  }

    $pdf->Output('recycleprint.pdf', 'I');
   }  // end of getRecycle
  }   // end of class

  $recycle_fields = new printRecycleInfo();
  $recycle_fields -> reptype = $_GET["reptype"];
  $recycle_fields -> machineid = $_GET["machineid"];
  $recycle_fields -> start_date = $_GET["start_date"];
  $recycle_fields -> end_date = $_GET["end_date"];
  $recycle_fields -> categorycode = $_GET["categorycode"];
  $recycle_fields -> postedby = $_GET["postedby"];
  $recycle_fields -> recycleby = $_GET["recycleby"];
  $recycle_fields -> recstatus = $_GET["recstatus"];
  $recycle_fields -> reptype = $_GET["reptype"]; 
  $recycle_fields -> generatedby = $_GET["generatedby"];  
  $recycle_fields -> getRecycle();
