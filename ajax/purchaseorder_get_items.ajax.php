<?php
require_once "../controllers/purchaseorder.controller.php";
require_once "../models/purchaseorder.model.php";

class AjaxPurchaseOrderItems{ 
   public $ponumber;

   public function ajaxDisplayPurchaseOrderItems(){
   	 // $field = "ponumber";
     $ponumber = $this->ponumber;
     $products = (new ControllerPurchaseOrder)->ctrShowPurchaseOrderItems($ponumber);
     echo json_encode($products);
   }
}

$purchase_items = new AjaxPurchaseOrderItems();
$purchase_items -> ponumber = $_POST["ponumber"];
$purchase_items -> ajaxDisplayPurchaseOrderItems();