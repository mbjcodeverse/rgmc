<?php
require_once "../controllers/machinetracking.controller.php";
require_once "../models/machinetracking.model.php";

class AjaxMachineTrackingReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $classcode;   
   public $reporter;
   public $curstatus;
   public $failuretype;
   public $shift;
   public $reptype;

   public function ajaxDisplayMachineTrackingReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $classcode = $this->classcode;
     $reporter = $this->reporter;
     $curstatus = $this->curstatus;
     $failuretype = $this->failuretype;
     $shift = $this->shift;
     $reptype = $this->reptype;

     $answer = (new ControllerMachineTracking)->ctrShowMachineIncidentReport($machineid, $start_date, $end_date, $classcode, $reporter, $curstatus, $failuretype, $shift, $reptype);
     echo json_encode($answer);
   }
}

$machine_incident = new AjaxMachineTrackingReport();
$machine_incident -> machineid = $_POST["machineid"];
$machine_incident -> start_date = $_POST["start_date"];
$machine_incident -> end_date = $_POST["end_date"];
$machine_incident -> classcode = $_POST["classcode"];
$machine_incident -> reporter = $_POST["reporter"];
$machine_incident -> curstatus = $_POST["curstatus"];
$machine_incident -> failuretype = $_POST["failuretype"];
$machine_incident -> shift = $_POST["shift"];
$machine_incident -> reptype = $_POST["reptype"];
$machine_incident -> ajaxDisplayMachineTrackingReport();