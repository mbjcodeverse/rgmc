<?php
require_once "../controllers/stockout.controller.php";
require_once "../models/stockout.model.php";

class AjaxReleasingItems{ 
   public $reqnumber;

   public function ajaxDisplayReleasingItems(){
     $reqnumber = $this->reqnumber;
     $products = (new ControllerStockout)->ctrShowReleasingItems($reqnumber);
     echo json_encode($products);
   }
}

$release_items = new AjaxReleasingItems();
$release_items -> reqnumber = $_POST["reqnumber"];
$release_items -> ajaxDisplayReleasingItems();