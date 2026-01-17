<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxProdcapacityItems{ 
   public $capacitynumber;
   public $etype;

   public function ajaxDisplayProdcapacityItems(){
     $capacitynumber = $this->capacitynumber;
     $etype = $this->etype;
     $products = (new ControllerProdfin)->ctrShowProdcapacityItems($capacitynumber, $etype);
     echo json_encode($products);
   }
}

$capacity_items = new AjaxProdcapacityItems();
$capacity_items -> capacitynumber = $_POST["capacitynumber"];
$capacity_items -> etype = $_POST["etype"];
$capacity_items -> ajaxDisplayProdcapacityItems();