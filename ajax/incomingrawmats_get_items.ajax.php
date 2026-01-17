<?php
require_once "../controllers/incomingrawmats.controller.php";
require_once "../models/incomingrawmats.model.php";

class AjaxIncomingRawMatsItems{ 
   public $ponumber;

   public function ajaxDisplayIncomingRawMatsItems(){
   	 // $field = "ponumber";
     $ponumber = $this->ponumber;
     $products = (new ControllerIncomingRawMatsOrder)->ctrShowIncomingRawMatsOrderItems($ponumber);
     echo json_encode($products);
   }
}

$incoming_items = new AjaxIncomingRawMatsItems();
$incoming_items -> ponumber = $_POST["ponumber"];
$incoming_items -> ajaxDisplayIncomingRawMatsItems();