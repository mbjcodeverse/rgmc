<?php
require_once "../controllers/machinetracking.controller.php";
require_once "../models/machinetracking.model.php";

class saveMachineDiagnosis{
  public $trans_type; 
  public $machineid;
  public $date_reported;
  public $curstatus;
  public $inccode;
  public $reporter;
  public $shift;
  public $inctime;
  public $failuretype;
  public $controlnum;
  public $incidentdetails;
  public $compreporter;
  public $date_completed;
  public $endtime;
  public $daysduration;
  public $timeduration;
  public $actiontaken;

  public function savePostMachineDiagnosis(){
    $trans_type = $this->trans_type;
    $machineid = $this->machineid;
  	$date_reported = $this->date_reported;
  	$curstatus = $this->curstatus;
    $inccode = $this->inccode;
    $reporter = $this->reporter;
  	$shift = $this->shift;
    $inctime = $this->inctime;
  	$failuretype = $this->failuretype;
  	$controlnum = $this->controlnum;
    $incidentdetails = $this->incidentdetails;
    $compreporter = $this->compreporter;
    $date_completed = $this->date_completed;
    $endtime = $this->endtime;
    $daysduration = $this->daysduration;
    $timeduration = $this->timeduration;
    $actiontaken = $this->actiontaken;

    $data = array("machineid"=>$machineid,
                  "date_reported"=>$date_reported,
    	            "curstatus"=>$curstatus,
                  "inccode"=>$inccode,
                  "reporter"=>$reporter,
                  "shift"=>$shift,
                  "inctime"=>$inctime,
                  "failuretype"=>$failuretype,
                  "controlnum"=>$controlnum,
                  "incidentdetails"=>$incidentdetails,
                  "compreporter"=>$compreporter,
                  "date_completed"=>$date_completed,
                  "endtime"=>$endtime,
                  "daysduration"=>$daysduration,
                  "timeduration"=>$timeduration,
                  "actiontaken"=>$actiontaken);

    if ($trans_type == 'New'){
      $answer = (new ControllerMachineTracking)->ctrCreateMachineTracking($data);
    }else{
      $answer = (new ControllerMachineTracking)->ctrEditMachineTracking($data);
    }

  }
}

$machine_diagnosis = new saveMachineDiagnosis();

$machine_diagnosis -> trans_type = $_POST["trans_type"];
$machine_diagnosis -> machineid = $_POST["machineid"];
$machine_diagnosis -> date_reported = $_POST["date_reported"];
$machine_diagnosis -> curstatus = $_POST["curstatus"];
$machine_diagnosis -> inccode = $_POST["inccode"];
$machine_diagnosis -> reporter = $_POST["reporter"];
$machine_diagnosis -> shift = $_POST["shift"];
$machine_diagnosis -> inctime = $_POST["inctime"];
$machine_diagnosis -> failuretype = $_POST["failuretype"];
$machine_diagnosis -> controlnum = $_POST["controlnum"];
$machine_diagnosis -> incidentdetails = $_POST["incidentdetails"];
$machine_diagnosis -> compreporter = $_POST["compreporter"];
$machine_diagnosis -> date_completed = $_POST["date_completed"];
$machine_diagnosis -> endtime = $_POST["endtime"];
$machine_diagnosis -> daysduration = $_POST["daysduration"];
$machine_diagnosis -> timeduration = $_POST["timeduration"];
$machine_diagnosis -> actiontaken = $_POST["actiontaken"];
$machine_diagnosis -> savePostMachineDiagnosis();