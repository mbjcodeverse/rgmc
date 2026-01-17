<?php
require_once "../controllers/purchaseorder.controller.php";
require_once "../models/purchaseorder.model.php";

class AjaxCancelPurchaseOrder{
    public $ponumber;
    public function ajaxCancelPurchaseOrder(){
      $field = "ponumber";
      $ponumber = $this->ponumber;
      $answer = (new ControllerPurchaseOrder)->ctrCancelPurchaseOrder($field, $ponumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["ponumber"])){
  $cancelPurchaseOrder = new AjaxCancelPurchaseOrder();
  $cancelPurchaseOrder -> ponumber = $_POST["ponumber"];
  $cancelPurchaseOrder -> ajaxCancelPurchaseOrder();
}