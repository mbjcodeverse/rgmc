<?php
require_once "../controllers/rawout.controller.php";
require_once "../models/rawout.model.php";

class AjaxRawoutDetails{
    public $reqnumber;
    public function ajaxGetRawoutDetails(){
      $reqnumber = $this->reqnumber;
      $answer = (new ControllerRawout)->ctrShowRawout($reqnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["reqnumber"])){
  $getRawout = new AjaxRawoutDetails();
  $getRawout -> reqnumber = $_POST["reqnumber"];
  $getRawout -> ajaxGetRawoutDetails();
}