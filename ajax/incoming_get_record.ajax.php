<?php
require_once "../controllers/incoming.controller.php";
require_once "../models/incoming.model.php";

class AjaxIncomingDetails{
    public $delnumber;
    public function ajaxGetIncomingDetails(){
      $delnumber = $this->delnumber;
      $answer = (new ControllerIncoming)->ctrShowIncoming($delnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["delnumber"])){
  $getIncoming = new AjaxIncomingDetails();
  $getIncoming -> delnumber = $_POST["delnumber"];
  $getIncoming -> ajaxGetIncomingDetails();
}