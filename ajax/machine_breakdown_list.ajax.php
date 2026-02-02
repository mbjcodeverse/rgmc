<?php
require_once "../controllers/machine.controller.php";
require_once "../models/machine.model.php";

class MachineBreakdownInfoList{ 
   public $classcode;
   public $failuretype;

   public function DisplayMachineBreakdownInfoList(){
     $classcode = $this->classcode;
     $failuretype = $this->failuretype;

     $answer = (new ControllerMachine)->ctrMachineBreakdownTransactionList($classcode, $failuretype);
     echo json_encode($answer);
   }
}

$machine_breakdown = new MachineBreakdownInfoList();
$machine_breakdown -> classcode = $_POST["classcode"];
$machine_breakdown -> failuretype = $_POST["failuretype"];
$machine_breakdown -> DisplayMachineBreakdownInfoList();