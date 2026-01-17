<?php
require_once "../controllers/home.controller.php";
require_once "../models/home.model.php";

class AjaxMachineHealth{ 
   public $buildingcode;
   public $start_date;
   public $end_date;
   public function ajaxDisplayMachineHealth(){
     $buildingcode = $this->buildingcode;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $answer = (new ControllerHome)->ctrMachineHealth($buildingcode, $start_date, $end_date);
     echo json_encode($answer);
   }
}

$machine = new AjaxMachineHealth();
$machine -> buildingcode = $_POST["buildingcode"];
$machine -> start_date = $_POST["start_date"];
$machine -> end_date = $_POST["end_date"];
$machine -> ajaxDisplayMachineHealth();