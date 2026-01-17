<?php
require_once "../controllers/return.controller.php";
require_once "../models/return.model.php";

class AjaxReturnItems{ 
   public $retnumber;

   public function ajaxDisplayReturnItems(){
     $retnumber = $this->retnumber;
     $products = (new ControllerReturn)->ctrShowReturnItems($retnumber);
     echo json_encode($products);
   }
}

$return_items = new AjaxReturnItems();
$return_items -> retnumber = $_POST["retnumber"];
$return_items -> ajaxDisplayReturnItems();