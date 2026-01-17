<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once "../../../controllers/machinetracking.controller.php";
require_once "../../../models/machinetracking.model.php";

require_once "../../../controllers/classification.controller.php";
require_once "../../../models/classification.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printMachineIncidentInfo{
public $reptype;
public $machineid;
public $start_date;
public $end_date;
public $classcode;
public $reporter;
public $curstatus;
public $failuretype;
public $generatedby;
public $shift;

public function getMachineIncident(){
  $reptype = $this->reptype;
  $machineid = $this->machineid;  
  $start_date = $this->start_date;
  $end_date = $this->end_date;
  if ($start_date == $end_date){
      $trans_date = 'Date: '.substr($start_date,5,2)."/".substr($start_date,8,2)."/".substr($start_date,0,4);
  }else{
      $trans_date = 'From '.substr($start_date,5,2)."/".substr($start_date,8,2)."/".substr($start_date,0,4).' To '.substr($end_date,5,2)."/".substr($end_date,8,2)."/".substr($end_date,0,4);
  }
  $classcode = $this->classcode;
  $reporter = $this->reporter;
  $curstatus = $this->curstatus;
  $failuretype = $this->failuretype;
  $generatedby = $this->generatedby;
  $shift = $this->shift;
  if ($shift != ''){
    $duty_shift = ' | Shift: '.$shift;
  }else{
    $duty_shift = '';
  }

  $report_content = (new ControllerMachineTracking())->ctrShowMachineIncidentReport($machineid, $start_date, $end_date, $classcode, $reporter, $curstatus, $failuretype, $shift, $reptype);

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
            <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">MACHINE OUTAGE SUMMARY</td> 
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
              <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;">Frequency &nbsp;&nbsp;</td>
              <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;">Downtime (Hrs) &nbsp;&nbsp;</td>       
          </tr>                          
      </table>
      EOF;
          $pdf->writeHTML($header, false, false, false, false, '');

    $i = 0;
    foreach ($report_content as $key => $value){
        $classname = $value["classname"];    
        $frequency = number_format($value["frequency"],2);
        $totalduration = number_format($value["totalduration"],2);

        if ($classname == null){
            $content = <<<EOF
            <table style="border: none;">    
                <tr>
                    <td style="width:95px;"></td> 
                    <td style="width:160px;text-align:right;font-size:10px;border:1px solid black;">&nbsp;TOTAL DURATION</td>     
                    <td style="width:90px;text-align:right;font-size:10px;border:1px solid black;">&nbsp;$frequency</td>
                    <td style="width:90px;text-align:right;font-size:10px;border:1px solid black;">$totalduration</td>
                </tr>                 
            </table>
        EOF;
            $pdf->writeHTML($content, false, false, false, false, '');  
        }else{
            $content = <<<EOF
            <table style="border: none;">    
                <tr>
                    <td style="width:95px;"></td> 
                    <td style="width:160px;text-align:left;font-size:10px;border-right:1px solid black;">&nbsp;$classname</td>     
                    <td style="width:90px;text-align:right;font-size:10px;">&nbsp;$frequency</td>
                    <td style="width:90px;text-align:right;font-size:10px;">$totalduration</td>
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
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">CATEGORY + MACHINE DESCRIPTION</td> 
        </tr>

        <tr>
          <td style="width:730px;text-align:center;font-size:10px;">$trans_date $duty_shift</td> 
        </tr>   

        <tr>
          <td></td>
        </tr>    

        <tr>       
            <td style="width:102px;"></td> 
            <td style="border: 1px solid black;width:130px;text-align:left;font-size:9px;">&nbsp; Category</td>  
            <td style="border: 1px solid black;width:260px;text-align:left;font-size:9px;">&nbsp; Machine</td>
            <td style="border: 1px solid black;width:60px;text-align:right;font-size:9px;">Frequency &nbsp;&nbsp;</td>
            <td style="border: 1px solid black;width:60px;text-align:right;font-size:9px;">Downtime &nbsp;&nbsp;</td>       
        </tr>                          
    </table>
    EOF;
        $pdf->writeHTML($header, false, false, false, false, '');

    $i = 0;
    foreach ($report_content as $key => $value){
      $classname = $value["classname"];
      $machinedesc = $value["machinedesc"];

      if ($value["machinedesc"] == null){
        $machinedesc = '';
        $classname = '';
      }else{
        if ($i == 0){
          $prev_classname = $value["classname"];
        }else{
          $curr_classname = $value["classname"];
          if ($prev_classname == $curr_classname){
            $classname = '';
          }
          $prev_classname = $curr_classname;
        }                 
      }

      $frequency = number_format($value["frequency"],2);
      $totalduration = number_format($value["totalduration"],2);

      if ($value["machinedesc"] == null){
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:102px;"></td> 
                <td style="width:130px;text-align:left;font-size:9px;">&nbsp; $classname</td>      
                <td style="width:260px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>
                <td style="font-size:9px;font-weight:bold;text-align:right;width:60px;border-top: 1px solid black;">$frequency</td>
                <td style="font-size:9px;font-weight:bold;text-align:right;width:60px;border-top: 1px solid black;">$totalduration</td>
            </tr>     
            
            <tr>
                <td style="width:102px;"></td>
            </tr>             
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');        
      }else{
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:102px;"></td> 
                <td style="width:130px;text-align:left;font-size:9px;">&nbsp; $classname</td>      
                <td style="width:260px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>
                <td style="text-align:right;width:60px;font-size:9px;">$frequency</td>
                <td style="text-align:right;width:60px;font-size:9px;">$totalduration</td>
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
    $pdf->AddPage('L', 'LETTER'); 
    $header = <<<EOF
    <table>
        <tr>
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
        </tr>

        <tr>
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">FAILURE TYPE + MACHINE DESCRIPTION</td> 
        </tr>

        <tr>
          <td style="width:730px;text-align:center;font-size:10px;">$trans_date $duty_shift</td> 
        </tr>   

        <tr>
          <td></td>
        </tr>    

        <tr>       
            <td style="width:102px;"></td> 
            <td style="border: 1px solid black;width:130px;text-align:left;font-size:9px;">&nbsp; Failure Type</td>  
            <td style="border: 1px solid black;width:260px;text-align:left;font-size:9px;">&nbsp; Machine</td>
            <td style="border: 1px solid black;width:60px;text-align:right;font-size:9px;">Frequency &nbsp;&nbsp;</td>
            <td style="border: 1px solid black;width:60px;text-align:right;font-size:9px;">Downtime &nbsp;&nbsp;</td>       
        </tr>                          
    </table>
    EOF;
        $pdf->writeHTML($header, false, false, false, false, '');

    $i = 0;
    foreach ($report_content as $key => $value){
      $failuretype = $value["failuretype"];
      $machinedesc = $value["machinedesc"];

      if ($value["machinedesc"] == null){
        $machinedesc = '';
        $failuretype = '';
      }else{
        if ($i == 0){
          $prev_failuretype = $value["failuretype"];
        }else{
          $curr_failuretype = $value["failuretype"];
          if ($prev_failuretype == $curr_failuretype){
            $failuretype = '';
          }
          $prev_failuretype = $curr_failuretype;
        }                 
      }

      $frequency = number_format($value["frequency"],2);
      $totalduration = number_format($value["totalduration"],2);

      if ($value["machinedesc"] == null){
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:102px;"></td> 
                <td style="width:130px;text-align:left;font-size:9px;">&nbsp; $failuretype</td>      
                <td style="width:260px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>
                <td style="font-size:9px;font-weight:bold;text-align:right;width:60px;border-top: 1px solid black;">$frequency</td>
                <td style="font-size:9px;font-weight:bold;text-align:right;width:60px;border-top: 1px solid black;">$totalduration</td>
            </tr>     
            
            <tr>
                <td style="width:102px;"></td>
            </tr>             
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');        
      }else{
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:102px;"></td> 
                <td style="width:130px;text-align:left;font-size:9px;">&nbsp; $failuretype</td>      
                <td style="width:260px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>
                <td style="text-align:right;width:60px;font-size:9px;">$frequency</td>
                <td style="text-align:right;width:60px;font-size:9px;">$totalduration</td>
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
  } elseif ($reptype == "4"){
      $pdf->AddPage('L', 'LEGAL'); 
      $header = <<<EOF
      <table>
          <tr>
            <td style="width:950px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
          </tr> 

          <tr>
            <td style="width:950px;text-align:center;font-size:1.2em;font-weight:bold;">SERVICE DISRUPTION SEQUENCE</td> 
          </tr>

          <tr>
            <td style="width:950px;text-align:center;font-size:10px;">$trans_date</td> 
          </tr>   

          <tr>
            <td></td>
          </tr>    

          <tr>       
              <td style="width:110px;"></td> 
              <td style="border: 1px solid black;width:58px;text-align:left;font-size:9px;">&nbsp; Date Rep</td>  
              <td style="border: 1px solid black;width:58px;text-align:left;font-size:9px;">&nbsp;&nbsp; Time Rep</td>
              <td style="border: 1px solid black;width:170px;text-align:left;font-size:9px;">&nbsp;&nbsp; Machine</td>
              <td style="border: 1px solid black;width:160px;text-align:left;font-size:9px;">&nbsp;&nbsp; Fail Type</td>
              <td style="border: 1px solid black;width:43px;text-align:left;font-size:9px;">&nbsp;&nbsp;&nbsp; Shift</td>
              <td style="border: 1px solid black;width:43px;text-align:left;font-size:9px;">&nbsp; Cont #</td>
              <td style="border: 1px solid black;width:58px;text-align:left;font-size:9px;">&nbsp; Date Com</td>  
              <td style="border: 1px solid black;width:58px;text-align:left;font-size:9px;">&nbsp;&nbsp; Time Com</td> 
              <td style="border: 1px solid black;width:58px;text-align:left;font-size:9px;">&nbsp; Downtime</td>     
          </tr>                          
      </table>
      EOF;
          $pdf->writeHTML($header, false, false, false, false, '');
  

    $i = 0;
    foreach ($report_content as $key => $value){
        $datereported = $value["datereported"];
        $inctime = $value["inctime"];
        $machinedesc = $value["machinedesc"];
        $failuretype = $value["failuretype"];
        $shift = $value["shift"];
        $controlnum = $value["controlnum"];
        $datecompleted = $value["datecompleted"];
        $endtime = $value["endtime"];   
        $timeduration = $value["timeduration"]; 

        $content = <<<EOF
          <table style="border: none;">    
              <tr>
                  <td style="width:110px;"></td> 
                  <td style="width:58px;text-align:left;font-size:9px;">&nbsp;$datereported</td>    
                  <td style="width:58px;text-align:left;font-size:9px;">&nbsp; $inctime</td> 
                  <td style="width:170px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>   
                  <td style="width:160px;text-align:left;font-size:9px;">&nbsp; $failuretype</td>
                  <td style="font-size:9px;text-align:left;width:43px;">&nbsp;&nbsp; $shift</td>
                  <td style="font-size:9px;text-align:left;width:43px;">&nbsp;&nbsp; $controlnum</td>
                  <td style="width:58px;text-align:left;font-size:9px;">&nbsp;$datecompleted</td>  
                  <td style="font-size:9px;text-align:left;width:58px;">&nbsp;&nbsp; $endtime</td>
                  <td style="font-size:9px;text-align:center;width:58px;">$timeduration</td>
              </tr>                 
          </table>
      EOF;
          $pdf->writeHTML($content, false, false, false, false, '');
      $i = $i + 1;
    } // end of FOR statement
    if ($i > 0){
        $footer = <<<EOF
        <table style="border: none;"> 
          <tr>  
            <td style="width:80px;"></td>
          </tr>

          <tr>  
            <td style="width:80px;"></td>
          </tr>
    
          <tr>  
            <td style="width:475px;"></td>
            <td style="width:220px;font-size:9px;">Date: $current_date</td>
            <td style="width:155px;font-size:10px;">Generated by:</td>
          </tr> 
          
          <tr>  
            <td style="width:475px;"></td>
            <td style="width:224px;"></td>
            <td style="width:95px;border-bottom: 1px solid black;"></td>
          </tr>      
        
          <tr>
            <td style="width:475px;"></td>  
            <td style="width:222px;"></td>
            <td style="width:155px;text-align:left;font-size:10px;">$printed_by</td>
          </tr>      
        </table>
    EOF;
          $pdf->writeHTML($footer, false, false, false, false, '');                    
    }  
  // ----------------------------------------------------------------------------------------------------------------
  } elseif ($reptype == "5"){
    $pdf->AddPage('L', 'LETTER'); 
    $header = <<<EOF
    <table>
        <tr>
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
        </tr>

        <tr>
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">MACHINE PRODUCTION SUMMATION</td> 
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
      $machinedesc = $value["prodname"] . ' (' . strtoupper($value["meas2"]) . ')';

      if ($value["prodname"] == null){
        $machinedesc = '';
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

      $frequency = number_format($value["qty"],2);
      $totalduration = number_format($value["tamount"],2);

      if ($value["prodname"] == null){
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:46px;"></td> 
                <td style="width:130px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td> 
                <td style="width:90px;text-align:left;font-size:9px;">&nbsp; </td>      
                <td style="width:260px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>
                <td style="font-size:9px;font-weight:bold;text-align:right;width:80px;"></td>
                <td style="font-size:9px;font-weight:bold;text-align:right;width:80px;border-top: 1px solid black;">$totalduration</td>
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
                <td style="width:260px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>
                <td style="text-align:right;width:80px;font-size:9px;">$frequency</td>
                <td style="text-align:right;width:80px;font-size:9px;">$totalduration</td>
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
  } elseif ($reptype == "6"){
      $pdf->AddPage('L', 'LEGAL'); 
      $header = <<<EOF
      <table>
          <tr>
            <td style="width:950px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
          </tr> 

          <tr>
            <td style="width:950px;text-align:center;font-size:1.2em;font-weight:bold;">MACHINE PRODUCTION DATE TRAIL</td> 
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
              <td style="border: 1px solid black;width:82px;text-align:left;font-size:9px;">&nbsp; Prod #</td>
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
      $proddate = substr($value["proddate"],5,2)."/".substr($value["proddate"],8,2)."/".substr($value["proddate"],0,4);
      $prodnumber = $value["prodnumber"];
      $machinedesc = $value["machinedesc"];
      $machinedesc = $value["prodname"] . ' (' . strtoupper($value["meas2"]) . ')';

      if ($value["prodname"] == null){
        $prodnumber = '';
        $machinedesc = '';
        $machinedesc = '';
        $qty = '';
        $price = '';
      }else{
        if ($i == 0){
          $prev_prodnumber = $value["prodnumber"];
          $prev_machinedesc = $value["machinedesc"];
        }else{
          $curr_prodnumber = $value["prodnumber"];
          if ($prev_prodnumber == $curr_prodnumber){
            $proddate = '';
            $prodnumber = '';
            $machinedesc = '';
          }
          $prev_prodnumber = $curr_prodnumber;
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
                  <td style="width:82px;text-align:left;font-size:9px;">&nbsp; $prodnumber</td> 
                  <td style="width:170px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>   
                  <td style="width:290px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>
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
                  <td style="width:56px;text-align:left;font-size:9px;">$proddate</td>    
                  <td style="width:82px;text-align:left;font-size:9px;">&nbsp; $prodnumber</td>   
                  <td style="width:290px;text-align:left;font-size:9px;">&nbsp; $machinedesc</td>
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

    $pdf->Output('machinetrackingprint.pdf', 'I');
   }  // end of getMachineIncident
  }   // end of class

  $incident_report = new printMachineIncidentInfo();
  $incident_report -> reptype = $_GET["reptype"];
  $incident_report -> machineid = $_GET["machineid"];
  $incident_report -> start_date = $_GET["start_date"];
  $incident_report -> end_date = $_GET["end_date"];
  $incident_report -> classcode = $_GET["classcode"];
  $incident_report -> reporter = $_GET["reporter"];
  $incident_report -> curstatus = $_GET["curstatus"];
  $incident_report -> failuretype = $_GET["failuretype"];
  $incident_report -> reptype = $_GET["reptype"]; 
  $incident_report -> generatedby = $_GET["generatedby"];  
  $incident_report -> shift = $_GET["shift"];
  $incident_report -> getMachineIncident();
