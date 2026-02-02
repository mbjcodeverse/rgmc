<?php
require_once "../controllers/machinetracking.controller.php";
require_once "../models/machinetracking.model.php";

class MachineTrackingsInfoList{ 
   public $machineid;
   public $datemode;
   public $start_date;
   public $end_date;   
   public $phase;
   public $postedby;

   public function DisplayMachineTrackingsInfoList(){
     $machineid = $this->machineid;
     $datemode = $this->datemode;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $phase = $this->phase;
     $postedby = $this->postedby;

     $answer = (new ControllerMachineTracking)->ctrMachineTrackingTransactionList($machineid, $datemode, $start_date, $end_date, $phase, $postedby);
     echo json_encode($answer);
   }
}

$machine_tracking = new MachineTrackingsInfoList();
$machine_tracking -> machineid = $_POST["machineid"];
$machine_tracking -> datemode = $_POST["datemode"];
$machine_tracking -> start_date = $_POST["start_date"];
$machine_tracking -> end_date = $_POST["end_date"];
$machine_tracking -> phase = $_POST["phase"];
$machine_tracking -> postedby = $_POST["postedby"];
$machine_tracking -> DisplayMachineTrackingsInfoList();