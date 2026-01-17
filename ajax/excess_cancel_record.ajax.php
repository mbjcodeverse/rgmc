<?php
require_once "../controllers/excess.controller.php";
require_once "../models/excess.model.php";

class AjaxCancelExcess{
    public $excnumber;
    public function ajaxCancelExcess(){
      $excnumber = $this->excnumber;
      $answer = (new ControllerExcess)->ctrCancelExcess($excnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["excnumber"])){
  $cancel_excess = new AjaxCancelExcess();
  $cancel_excess -> excnumber = $_POST["excnumber"];
  $cancel_excess -> ajaxCancelExcess();
}