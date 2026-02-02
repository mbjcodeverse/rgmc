<?php
require_once "../controllers/machine.controller.php";
require_once "../models/machine.model.php";

class saveMachineBreakdown{
  public $trans_type; 
  public $classcode;
  public $failuretype;
  public $details;
  public $breakid;

  public function savePostMachineBreakdown(){
    $trans_type = $this->trans_type;

    $classcode = $this->classcode;
  	$failuretype = $this->failuretype;
  	$details = $this->details;
    $breakid = $this->breakid;

    $data = array("classcode"=>$classcode,
                  "failuretype"=>$failuretype,
    	            "details"=>$details,
                  "breakid"=>$breakid);

    if ($trans_type == 'New'){
      $answer = (new ControllerMachine)->ctrAddMachineBreakdown($data);
    }else{
      $answer = (new ControllerMachine)->ctrEditMachineBreakdown($data);
    }

  }
}

$machine_Breakdown = new saveMachineBreakdown();

$machine_Breakdown -> trans_type = $_POST["trans_type"];
$machine_Breakdown -> classcode = $_POST["classcode"];
$machine_Breakdown -> failuretype = $_POST["failuretype"];
$machine_Breakdown -> details = $_POST["details"];
$machine_Breakdown -> breakid = $_POST["breakid"];
$machine_Breakdown -> savePostMachineBreakdown();