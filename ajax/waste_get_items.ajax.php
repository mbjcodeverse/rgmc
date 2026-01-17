<?php
require_once "../controllers/debris.controller.php";
require_once "../models/debris.model.php";

class AjaxWasteItems{ 
   public $debnumber;

   public function ajaxDisplayWasteItems(){
     $debnumber = $this->debnumber;
     $products = (new ControllerDebris)->ctrShowWasteItems($debnumber);
     echo json_encode($products);
   }
}

$debris_items = new AjaxWasteItems();
$debris_items -> debnumber = $_POST["debnumber"];
$debris_items -> ajaxDisplayWasteItems();