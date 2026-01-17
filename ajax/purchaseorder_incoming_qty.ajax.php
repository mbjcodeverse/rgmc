<?php
require_once "../controllers/purchaseorder.controller.php";
require_once "../models/purchaseorder.model.php";

class AjaxPurchaseIncoming{ 
   public $ponumber;

   public function ajaxDisplayPurchaseIncoming(){
     $ponumber = $this->ponumber;
     $answer = (new ControllerPurchaseOrder)->ctrShowPurchaseIncoming($ponumber);
     echo json_encode($answer);
   }
  
}

if(isset($_POST["ponumber"])){
  $pur_inc = new AjaxPurchaseIncoming();
  $pur_inc -> ponumber = $_POST["ponumber"];
  $pur_inc -> ajaxDisplayPurchaseIncoming();
}