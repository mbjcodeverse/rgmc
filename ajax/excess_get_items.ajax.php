<?php
require_once "../controllers/excess.controller.php";
require_once "../models/excess.model.php";

class AjaxExcessItems{ 
   public $excnumber;

   public function ajaxDisplayExcessItems(){
     $excnumber = $this->excnumber;
     $products = (new ControllerExcess)->ctrShowExcessItems($excnumber);
     echo json_encode($products);
   }
}

$excess_items = new AjaxExcessItems();
$excess_items -> excnumber = $_POST["excnumber"];
$excess_items -> ajaxDisplayExcessItems();