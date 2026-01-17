<?php
require_once "../controllers/debris.controller.php";
require_once "../models/debris.model.php";

class AjaxWasteoperatorItems{ 
   public $debnumber;

   public function ajaxDisplayWasteoperatorItems(){
     $debnumber = $this->debnumber;
     $products = (new ControllerDebris)->ctrShowWasteoperatorItems($debnumber);
     echo json_encode($products);
   }
}

$debris_items = new AjaxWasteoperatorItems();
$debris_items -> debnumber = $_POST["debnumber"];
$debris_items -> ajaxDisplayWasteoperatorItems();