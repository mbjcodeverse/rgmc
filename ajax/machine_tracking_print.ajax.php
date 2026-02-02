<?php
require_once "../controllers/machinetracking.controller.php";
require_once "../models/machinetracking.model.php";

class MachineTrackingPrint{
    public $inccode;
    public function getMachineTrackingPrint(){
      $inccode = $this->inccode;
      $answer = (new ControllerMachineTracking)->ctrPrintMachineTracking($inccode);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["inccode"])){
  $getMachineTracking = new MachineTrackingPrint();
  $getMachineTracking -> inccode = $_POST["inccode"];
  $getMachineTracking -> getMachineTrackingPrint();
}