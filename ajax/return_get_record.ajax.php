<?php
require_once "../controllers/return.controller.php";
require_once "../models/return.model.php";

class AjaxReturnDetails{
    public $retnumber;
    public function ajaxGetReturnDetails(){
      $retnumber = $this->retnumber;
      $answer = (new ControllerReturn)->ctrShowReturn($retnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["retnumber"])){
  $getReturn = new AjaxReturnDetails();
  $getReturn -> retnumber = $_POST["retnumber"];
  $getReturn -> ajaxGetReturnDetails();
}