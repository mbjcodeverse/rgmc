<?php
require_once "../controllers/rawout.controller.php";
require_once "../models/rawout.model.php";

class AjaxCancelRawout{
    public $reqnumber;
    public function ajaxCancelRawout(){
      $reqnumber = $this->reqnumber;
      $answer = (new ControllerRawout)->ctrCancelRawout($reqnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["reqnumber"])){
  $cancel_rawout = new AjaxCancelRawout();
  $cancel_rawout -> reqnumber = $_POST["reqnumber"];
  $cancel_rawout -> ajaxCancelRawout();
}