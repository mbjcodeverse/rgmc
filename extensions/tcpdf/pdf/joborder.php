<?php

require_once "../../../controllers/machinetracking.controller.php";
require_once "../../../models/machinetracking.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printJobOrder{

public $inccode;
public $approver;
public function getJobOrderPrinting(){
  $inccode = $this->inccode;
  $approver = $this->approver;
  $joborder = (new ControllerMachineTracking)->ctrPrintMachineTracking($inccode);

  $machineid = $joborder['machineid'];
  $machinedesc = $joborder['machinedesc'];

  $datereported = $joborder['datereported'];
  $date_reported = substr($datereported,5,2)."/".substr($datereported,8,2)."/".substr($datereported,0,4);

  $machstatus = $joborder['machstatus'];
  $phase = $joborder['phase'];
  $curstatus = $joborder['curstatus'];
  $inccode = $joborder['inccode'];
  $reporter = $joborder['reporter'];
  $shift = $joborder['shift'];
  $inctime = $joborder['inctime'];
  $failuretype = $joborder['failuretype'];

  $breakid = $joborder['breakid'];  
  $details = $joborder['details']; 

  $controlnum = $joborder['controlnum'];
  $incidentdetails = $joborder['incidentdetails'];
  $technician = $joborder['technician'];
  $compreporter = $joborder['compreporter'];

  $datecompleted = $joborder['datecompleted'];
  $date_completed = substr($datecompleted,5,2)."/".substr($datecompleted,8,2)."/".substr($datecompleted,0,4);
  if ($date_completed == '00/00/0000'){
    $date_completed = '';
  }

  $endtime = $joborder['endtime'];
  $daysduration = $joborder['daysduration'];
  $timeduration = $joborder['timeduration'];
  $cause = $joborder['cause'];
  $actiontaken = $joborder['actiontaken'];
//   $amount = number_format($joborder['amount'],2);

  $reporter_field = "empid";
  $reporter_value = $reporter;
  $incident_reporter = (new ControllerEmployees)->ctrShowEmployees($reporter_field, $reporter_value);

  if ($incident_reporter['mi']!=''){
    $reported_by = $incident_reporter['fname'].' '.$incident_reporter['mi'].'. '.$incident_reporter['lname']; 
  }else{
    $reported_by = $incident_reporter['fname'].' '.$incident_reporter['lname'];
  } 
  
  $technician_field = "empid";
  $technician_value = $technician;
  $incident_technician = (new ControllerEmployees)->ctrShowEmployees($technician_field, $technician_value);

  if ($incident_technician['mi']!=''){
    $assigned_technician = $incident_technician['fname'].' '.$incident_technician['mi'].'. '.$incident_technician['lname']; 
  }else{
    $assigned_technician = $incident_technician['fname'].' '.$incident_technician['lname'];
  } 
  
  if ($compreporter != ''){
    $completer_field = "empid";
    $completer_value = $compreporter;
    $incident_completer = (new ControllerEmployees)->ctrShowEmployees($completer_field, $completer_value);

    if ($incident_completer['mi']!=''){
      $assigned_completer = $incident_completer['fname'].' '.$incident_completer['mi'].'. '.$incident_completer['lname']; 
    }else{
      $assigned_completer = $incident_completer['fname'].' '.$incident_completer['lname'];
    } 
  }else{
    $assigned_completer = '';
  } 
  
  $approver_field = "empid";
  $approver_value = $approver;
  $incident_approver = (new ControllerEmployees)->ctrShowEmployees($approver_field, $approver_value);

  if ($incident_approver['mi']!=''){
    $approved_by = $incident_approver['fname'].' '.$incident_approver['mi'].'. '.$incident_approver['lname']; 
  }else{
    $approved_by = $incident_approver['fname'].' '.$incident_approver['lname'];
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
        <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">JOB ORDER</td> 
      </tr>

      <tr>        
        <td style="width:50px;text-align:right;font-size:11px;"></td>
        <td style="width:380px;text-align:left;font-size:11px;"></td>
        <td style="width:60px;text-align:right;font-size:11px;">Incident # :</td>
        <td style="width:65px;text-align:left;font-size:11px;">&nbsp;$inccode</td>
      </tr>                     
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');

  $footer = <<<EOF
     <table style="border: none;">
       <tr>
         <td style="width:49px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Machine</td>
         <td style="width:200px;font-size:11px;border: 1px solid black;">$machinedesc</td>
         <td style="width:49px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Inc Desc</td>
         <td style="width:120px;font-size:11px;border: 1px solid black;">$curstatus</td>
         <td style="width:49px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Phase</td>
         <td style="width:78px;font-size:11px;border: 1px solid black;">$phase</td>
       </tr>

       <tr>
         <td style="width:49px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">State</td>
         <td style="width:75px;font-size:11px;border: 1px solid black;">$machstatus</td>
         <td style="width:49px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Reporter</td>
         <td style="width:140px;font-size:11px;border: 1px solid black;">$reported_by</td>
         <td style="width:56px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Date Rep</td>
         <td style="width:65px;font-size:11px;border: 1px solid black;">$date_reported</td>
         <td style="width:57px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Time Rep</td>
         <td style="width:54px;font-size:11px;border: 1px solid black;">$inctime</td>
       </tr>

       <tr>
         <td style="width:49px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Failure</td>
         <td style="width:140px;font-size:11px;border: 1px solid black;">$failuretype</td>
         <td style="width:35px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Issue</td>
         <td style="width:321px;font-size:10px;border: 1px solid black;">$details</td>
       </tr>    
       
       <tr>
         <td style="width:49px;font-size:11px;border-left:1px solid black;border-right:1px solid black;background-color:#faf8f7;">Details</td>
         <td style="width:496px;font-size:10px;border-left:1px solid black;border-right:1px solid black;">$incidentdetails</td>
       </tr> 

       <tr>
         <td style="width:49px;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;background-color:#faf8f7;"></td>
         <td style="width:496px;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;"></td>
       </tr> 

       <tr>
         <td style="width:49px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Shift</td>
         <td style="width:40px;font-size:11px;border: 1px solid black;">$shift</td>
         <td style="width:84px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Assigned Tech</td>
         <td style="width:140px;font-size:11px;border: 1px solid black;">$assigned_technician</td>
         <td style="width:56px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Date Com</td>
         <td style="width:65px;font-size:11px;border: 1px solid black;">$date_completed</td>
         <td style="width:57px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Time Com</td>
         <td style="width:54px;font-size:11px;border: 1px solid black;">$endtime</td>
       </tr>

       <tr>
         <td style="width:35px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Down</td>
         <td style="width:54px;font-size:8px;border: 1px solid black;text-align:right;">$timeduration &nbsp;hrs</td>
         <td style="width:84px;font-size:11px;border: 1px solid black;background-color:#faf8f7;">Completed by</td>
         <td style="width:140px;font-size:11px;border: 1px solid black;">$assigned_completer</td>
       </tr>

       <tr>
         <td style="width:49px;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;background-color:#faf8f7;">Cause</td>
         <td style="width:496px;font-size:10px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;">$cause</td>
       </tr> 

       <tr>
         <td style="width:49px;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;background-color:#faf8f7;"></td>
         <td style="width:496px;font-size:10px;border-left:1px solid black;border-right:1px solid black;"></td>
       </tr>

       <tr>
         <td style="width:49px;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;background-color:#faf8f7;"></td>
         <td style="width:496px;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;"></td>
       </tr> 

       <tr>
         <td style="width:49px;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;background-color:#faf8f7;">Action</td>
         <td style="width:496px;font-size:10px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;">$actiontaken</td>
       </tr> 

       <tr>
         <td style="width:49px;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;background-color:#faf8f7;"></td>
         <td style="width:496px;font-size:10px;border-left:1px solid black;border-right:1px solid black;"></td>
       </tr>

       <tr>
         <td style="width:49px;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;background-color:#faf8f7;"></td>
         <td style="width:496px;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;"></td>
       </tr> 

       <tr>
         <td style="width:545px;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:202px;font-size:11px;">Prepared by:</td>
         <td style="width:80px;font-size:11px;">Approved by:</td>          
       </tr>
       <tr>
         <td style="width:545px;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:125px;border-bottom: 1px solid black;font-size:11px;"></td>
         <td style="width:79px;font-size:11px;"></td>
         <td style="width:130px;border-bottom: 1px solid black;font-size:11px;"></td>
       </tr>
       <tr>
         <td style="width:125px;font-size:11px;">$reported_by</td>
         <td style="width:79px;font-size:11px;"></td>
         <td style="width:170px;font-size:11px;">$approved_by</td>         
       </tr>       
     </table>
EOF;
  $pdf->writeHTML($footer, false, false, false, false, '');  


  $pdf->Output('joborder.pdf', 'I');
 }
}

$joborderForm = new printJobOrder();
$joborderForm -> inccode = $_GET["inccode"];
$joborderForm -> approver = $_GET["approver"];
$joborderForm -> getJobOrderPrinting();
?>