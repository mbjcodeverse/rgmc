<?php
require_once "../controllers/inventory.controller.php";
require_once "../models/inventory.model.php";

class AjaxInventoryItems{ 
   public $invnumber;

   public function ajaxDisplayInventoryItems(){
     $invnumber = $this->invnumber;
     $products = (new ControllerInventory)->ctrShowInventoryItems($invnumber);
     echo json_encode($products);
   }
}

$inventory_items = new AjaxInventoryItems();
$inventory_items -> invnumber = $_POST["invnumber"];
$inventory_items -> ajaxDisplayInventoryItems();