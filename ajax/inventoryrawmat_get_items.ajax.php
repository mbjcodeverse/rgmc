<?php
require_once "../controllers/inventoryrawmats.controller.php";
require_once "../models/inventoryrawmats.model.php";

class AjaxInventoryRawmatsItems{ 
   public $invnumber;

   public function ajaxDisplayInventoryRawmatsItems(){
     $invnumber = $this->invnumber;
     $products = (new ControllerInventoryRawmats)->ctrShowInventoryRawmatsItems($invnumber);
     echo json_encode($products);
   }
}

$inventory_items = new AjaxInventoryRawmatsItems();
$inventory_items -> invnumber = $_POST["invnumber"];
$inventory_items -> ajaxDisplayInventoryRawmatsItems();