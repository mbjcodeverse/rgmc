<?php
require_once "../controllers/purchaseorder.controller.php";
require_once "../models/purchaseorder.model.php";

class AjaxPurchaseOrderForIncomingDetails{
    public $ponumber;
    public function ajaxGetPurchaseOrderForIncomingDetails(){
      $ponumber = $this->ponumber;
      $answer = (new ControllerPurchaseOrder)->ctrShowPurchaseOrderForIncoming($ponumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["ponumber"])){
  $getPurchaseOrderForIncoming = new AjaxPurchaseOrderForIncomingDetails();
  $getPurchaseOrderForIncoming -> ponumber = $_POST["ponumber"];
  $getPurchaseOrderForIncoming -> ajaxGetPurchaseOrderForIncomingDetails();
}