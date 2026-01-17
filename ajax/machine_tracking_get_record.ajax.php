<?php
require_once "../controllers/machinetracking.controller.php";
require_once "../models/machinetracking.model.php";

class MachineTrackingDetails{
    public $inccode;
    public function getMachineTrackingDetails(){
      $inccode = $this->inccode;
      $answer = (new ControllerMachineTracking)->ctrShowMachineTracking($inccode);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["inccode"])){
  $getMachineTracking = new MachineTrackingDetails();
  $getMachineTracking -> inccode = $_POST["inccode"];
  $getMachineTracking -> getMachineTrackingDetails();
}