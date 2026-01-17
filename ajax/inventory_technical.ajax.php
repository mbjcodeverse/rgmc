<?php
require_once "../controllers/inventory.controller.php";
require_once "../models/inventory.model.php";

class AjaxInventoryTechnical{ 
   public $categorycode;
   public $asofdate;

   public function ajaxDisplayInventoryTechnical(){
     $categorycode = $this->categorycode;
     $asofdate = $this->asofdate;

     $answer = (new ControllerInventory)->ctrShowInventoryTechnical($categorycode, $asofdate);
     echo json_encode($answer);
   }
}

$inventory = new AjaxInventoryTechnical();
$inventory -> categorycode = $_POST["categorycode"];
$inventory -> asofdate = $_POST["asofdate"];
$inventory -> ajaxDisplayInventoryTechnical();