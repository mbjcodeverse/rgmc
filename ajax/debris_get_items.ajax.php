<?php
require_once "../controllers/debris.controller.php";
require_once "../models/debris.model.php";

class AjaxDebrisItems{ 
   public $debnumber;

   public function ajaxDisplayDebrisItems(){
     $debnumber = $this->debnumber;
     $products = (new ControllerDebris)->ctrShowDebrisItems($debnumber);
     echo json_encode($products);
   }
}

$debris_items = new AjaxDebrisItems();
$debris_items -> debnumber = $_POST["debnumber"];
$debris_items -> ajaxDisplayDebrisItems();