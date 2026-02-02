<?php
require_once "../controllers/machine.controller.php";
require_once "../models/machine.model.php";

class MachineBreakdownDetails{
    public $breakid;
    public function getMachineBreakdownDetails(){
      $breakid = $this->breakid;
      $answer = (new ControllerMachine)->ctrShowMachineBreakdown($breakid);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["breakid"])){
  $getMachineBreakdown = new MachineBreakdownDetails();
  $getMachineBreakdown -> breakid = $_POST["breakid"];
  $getMachineBreakdown -> getMachineBreakdownDetails();
}