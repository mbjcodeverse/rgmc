<?php
require_once "../controllers/purchaseorder.controller.php";
require_once "../models/purchaseorder.model.php";

class AjaxClosePurchaseOrder{
    public $ponumber;
    public function ajaxClosePurchaseOrder(){
      $field = "ponumber";
      $ponumber = $this->ponumber;
      $answer = (new ControllerPurchaseOrder)->ctrClosePurchaseOrder($field, $ponumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["ponumber"])){
  $closePurchaseOrder = new AjaxClosePurchaseOrder();
  $closePurchaseOrder -> ponumber = $_POST["ponumber"];
  $closePurchaseOrder -> ajaxClosePurchaseOrder();
}