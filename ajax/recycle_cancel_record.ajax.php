<?php
require_once "../controllers/recycle.controller.php";
require_once "../models/recycle.model.php";

class AjaxCancelRecycle{
    public $recnumber;
    public function ajaxCancelRecycle(){
      $recnumber = $this->recnumber;
      $answer = (new ControllerRecycle)->ctrCancelRecycle($recnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["recnumber"])){
  $cancel_recycle = new AjaxCancelRecycle();
  $cancel_recycle -> recnumber = $_POST["recnumber"];
  $cancel_recycle -> ajaxCancelRecycle();
}