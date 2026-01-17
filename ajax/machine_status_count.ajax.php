<?php
require_once "../controllers/home.controller.php";
require_once "../models/home.model.php";

class AjaxMachineStatusCount{ 
   public $buildingcode;
   public function ajaxDisplayMachineStatusCount(){
     $buildingcode = $this->buildingcode;
     $answer = (new ControllerHome)->ctrMachineStatusCount($buildingcode);
     echo json_encode($answer);
   }
}

$machine = new AjaxMachineStatusCount();
$machine -> buildingcode = $_POST["buildingcode"];
$machine -> ajaxDisplayMachineStatusCount();