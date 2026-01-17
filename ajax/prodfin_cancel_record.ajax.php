<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxCancelProdfin{
    public $prodnumber;
    public function ajaxCancelProdfin(){
      $prodnumber = $this->prodnumber;
      $answer = (new ControllerProdfin)->ctrCancelProdfin($prodnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["prodnumber"])){
  $cancel_prodfin = new AjaxCancelProdfin();
  $cancel_prodfin -> prodnumber = $_POST["prodnumber"];
  $cancel_prodfin -> ajaxCancelProdfin();
}