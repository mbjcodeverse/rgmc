<?php
require_once "../controllers/machinetracking.controller.php";
require_once "../models/machinetracking.model.php";

class MachineFailuretypeList{ 
   public $machineid;

   public function DisplayMachineFailuretypeList(){
     $machineid = $this->machineid;
     $answer = (new ControllerMachineTracking)->ctrMachineCategoryFailuretypeList($machineid);
     echo json_encode($answer);
   }
}

$machine_category = new MachineFailuretypeList();
$machine_category -> machineid = $_POST["machineid"];
$machine_category -> DisplayMachineFailuretypeList();