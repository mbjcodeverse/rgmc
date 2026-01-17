<?php
require_once "../controllers/home.controller.php";
require_once "../models/home.model.php";

class AjaxFilteredMachine{ 
   public $classcode;
   public $buildingcode;
   public $machstatus;   

   public function ajaxDisplayFilteredMachine(){
     $classcode = $this->classcode;
     $buildingcode = $this->buildingcode;
     $machstatus = $this->machstatus;

     $answer = (new ControllerHome)->ctrShowFilteredMachineList($classcode, $buildingcode, $machstatus);
     echo json_encode($answer);
   }
}

$machine = new AjaxFilteredMachine();
$machine -> classcode = $_POST["classcode"];
$machine -> buildingcode = $_POST["buildingcode"];
$machine -> machstatus = $_POST["machstatus"];
$machine -> ajaxDisplayFilteredMachine();