<?php
require_once "../controllers/matreturn.controller.php";
require_once "../models/matreturn.model.php";

class AjaxCancelMatreturn{
    public $retnumber;
    public function ajaxCancelMatreturn(){
      $retnumber = $this->retnumber;
      $answer = (new ControllerMatreturn)->ctrCancelMatreturn($retnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["retnumber"])){
  $cancel_matreturn = new AjaxCancelMatreturn();
  $cancel_matreturn -> retnumber = $_POST["retnumber"];
  $cancel_matreturn -> ajaxCancelMatreturn();
}