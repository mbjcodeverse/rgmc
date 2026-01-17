<?php
require_once "../controllers/prodcom.controller.php";
require_once "../models/prodcom.model.php";

class AjaxCancelProdcom{
    public $prodnumber;
    public function ajaxCancelProdcom(){
      $prodnumber = $this->prodnumber;
      $answer = (new ControllerProdcom)->ctrCancelProdcom($prodnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["prodnumber"])){
  $cancel_prodcom = new AjaxCancelProdcom();
  $cancel_prodcom -> prodnumber = $_POST["prodnumber"];
  $cancel_prodcom -> ajaxCancelProdcom();
}