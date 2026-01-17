<?php
require_once "../controllers/purchaseorder.controller.php";
require_once "../models/purchaseorder.model.php";

class AjaxPurchaseOrderDetails{
    public $ponumber;
    public function ajaxGetPurchaseOrderDetails(){
      $ponumber = $this->ponumber;
      $answer = (new ControllerPurchaseOrder)->ctrShowPurchaseOrder($ponumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["ponumber"])){
  $getPurchaseOrder = new AjaxPurchaseOrderDetails();
  $getPurchaseOrder -> ponumber = $_POST["ponumber"];
  $getPurchaseOrder -> ajaxGetPurchaseOrderDetails();
}