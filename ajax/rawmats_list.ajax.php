<?php
require_once "../controllers/rawmats.controller.php";
require_once "../models/rawmats.model.php";

class AjaxRawMatsList{ 
   public $categorycode;

   public function ajaxDisplayRawMatsList(){
     $categorycode = $this->categorycode;

     $answer = (new ControllerRawMats)->ctrShowRawMatsList($categorycode);
     echo json_encode($answer);
   }
}

$rawmats = new AjaxRawMatsList();
$rawmats -> categorycode = $_POST["categorycode"];
$rawmats -> ajaxDisplayRawMatsList();