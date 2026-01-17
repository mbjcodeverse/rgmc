<?php
require_once "../controllers/incoming.controller.php";
require_once "../models/incoming.model.php";

class AjaxIncomingItems{ 
   public $delnumber;

   public function ajaxDisplayIncomingItems(){
     $delnumber = $this->delnumber;
     $products = (new ControllerIncoming)->ctrShowIncomingItems($delnumber);
     echo json_encode($products);
   }
}

$incoming_items = new AjaxIncomingItems();
$incoming_items -> delnumber = $_POST["delnumber"];
$incoming_items -> ajaxDisplayIncomingItems();