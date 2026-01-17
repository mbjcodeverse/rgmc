<?php
require_once "../controllers/home.controller.php";
require_once "../models/home.model.php";

class AjaxMachineCategoryPercentage{ 
   public $buildingcode;
   public function ajaxDisplayMachineCategoryPercentage(){
     $buildingcode = $this->buildingcode;
     $answer = (new ControllerHome)->ctrMachineCategoryPercentage($buildingcode);
     echo json_encode($answer);
   }
}

$machine = new AjaxMachineCategoryPercentage();
$machine -> buildingcode = $_POST["buildingcode"];
$machine -> ajaxDisplayMachineCategoryPercentage();