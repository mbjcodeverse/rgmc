<?php
require_once "../controllers/incomingrawmats.controller.php";
require_once "../models/incomingrawmats.model.php";

class AjaxCancelPurchaseOrder{
    public $ponumber;
    public function ajaxCancelPurchaseOrder(){
      $ponumber = $this->ponumber;
      $answer = (new ControllerIncomingRawMatsOrder)->ctrCancelPurchaseOrder($ponumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["ponumber"])){
  $cancel_purchase = new AjaxCancelPurchaseOrder();
  $cancel_purchase -> ponumber = $_POST["ponumber"];
  $cancel_purchase -> ajaxCancelPurchaseOrder();
}