<?php
require_once "../controllers/matreturn.controller.php";
require_once "../models/matreturn.model.php";

class AjaxMatreturnDetails{
    public $retnumber;
    public function ajaxGetMatreturnDetails(){
      $retnumber = $this->retnumber;
      $answer = (new ControllerMatreturn)->ctrShowMatreturn($retnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["retnumber"])){
  $getMatreturn = new AjaxMatreturnDetails();
  $getMatreturn -> retnumber = $_POST["retnumber"];
  $getMatreturn -> ajaxGetMatreturnDetails();
}