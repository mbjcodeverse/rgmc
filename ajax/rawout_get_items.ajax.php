<?php
require_once "../controllers/rawout.controller.php";
require_once "../models/rawout.model.php";

class AjaxRawoutItems{ 
   public $reqnumber;

   public function ajaxDisplayRawoutItems(){
     $reqnumber = $this->reqnumber;
     $products = (new ControllerRawout)->ctrShowRawoutItems($reqnumber);
     echo json_encode($products);
   }
}

$rawout_items = new AjaxRawoutItems();
$rawout_items -> reqnumber = $_POST["reqnumber"];
$rawout_items -> ajaxDisplayRawoutItems();