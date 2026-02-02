<?php
require_once "../controllers/machinetracking.controller.php";
require_once "../models/machinetracking.model.php";

class MachineIncidentList{ 
   public $machineid;

   public function DisplayMachineIncidentList(){
     $machineid = $this->machineid;

     $answer = (new ControllerMachineTracking)->ctrMachineIncidentList($machineid);
     echo json_encode($answer);
   }
}

$machine_tracking = new MachineIncidentList();
$machine_tracking -> machineid = $_POST["machineid"];
$machine_tracking -> DisplayMachineIncidentList();