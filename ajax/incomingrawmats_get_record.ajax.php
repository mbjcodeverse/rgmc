<?php
require_once "../controllers/incomingrawmats.controller.php";
require_once "../models/incomingrawmats.model.php";

class AjaxRawMatsOrderDetails{
    public $ponumber;
    public function ajaxGetRawMatsOrderDetails(){
      $ponumber = $this->ponumber;
      $answer = (new ControllerIncomingRawMatsOrder)->ctrShowIncomingRawMatsOrder($ponumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["ponumber"])){
  $getRawMatsOrder = new AjaxRawMatsOrderDetails();
  $getRawMatsOrder -> ponumber = $_POST["ponumber"];
  $getRawMatsOrder -> ajaxGetRawMatsOrderDetails();
}