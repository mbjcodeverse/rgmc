<?php
require_once "../controllers/machinetracking.controller.php";
require_once "../models/machinetracking.model.php";

class MachineBreakdownList{ 
   public $failuretype;
   public $class_code;

   public function DisplayMachineBreakdownList(){
     $failuretype = $this->failuretype;
     $class_code = $this->class_code;
     $answer = (new ControllerMachineTracking)->ctrMachineCategoryBreakdownList($failuretype, $class_code);
     echo json_encode($answer);
   }
}

$machine_breakdown = new MachineBreakdownList();
$machine_breakdown -> failuretype = $_POST["failuretype"];
$machine_breakdown -> class_code = $_POST["class_code"];
$machine_breakdown -> DisplayMachineBreakdownList();