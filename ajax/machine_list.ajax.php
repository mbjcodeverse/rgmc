<?php
require_once "../controllers/machine.controller.php";
require_once "../models/machine.model.php";

class AjaxMachineList{ 
   public $classcode;
   public $buildingcode;
   public $isactive;   
   public $machstatus;

   public function ajaxDisplayMachineList(){
     $classcode = $this->classcode;
     $buildingcode = $this->buildingcode;
     $isactive = $this->isactive;
     $machstatus = $this->machstatus;

     $answer = (new ControllerMachine)->ctrShowMachineSearchList($classcode, $buildingcode, $isactive, $machstatus);
     echo json_encode($answer);
   }
}

$Machine = new AjaxMachineList();
$Machine -> classcode = $_POST["classcode"];
$Machine -> buildingcode = $_POST["buildingcode"];
$Machine -> isactive = $_POST["isactive"];
$Machine -> machstatus = $_POST["machstatus"];
$Machine -> ajaxDisplayMachineList();