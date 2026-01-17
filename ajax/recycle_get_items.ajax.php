<?php
require_once "../controllers/recycle.controller.php";
require_once "../models/recycle.model.php";

class AjaxRecycleItems{ 
   public $recnumber;

   public function ajaxDisplayRecycleItems(){
     $recnumber = $this->recnumber;
     $products = (new ControllerRecycle)->ctrShowRecycleItems($recnumber);
     echo json_encode($products);
   }
}

$recycle_items = new AjaxRecycleItems();
$recycle_items -> recnumber = $_POST["recnumber"];
$recycle_items -> ajaxDisplayRecycleItems();