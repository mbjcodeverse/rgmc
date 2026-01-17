<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxProdfinDetails{
    public $prodnumber;
    public function ajaxGetProdfinDetails(){
      $prodnumber = $this->prodnumber;
      $answer = (new ControllerProdfin)->ctrShowProdfin($prodnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["prodnumber"])){
  $getProdfin = new AjaxProdfinDetails();
  $getProdfin -> prodnumber = $_POST["prodnumber"];
  $getProdfin -> ajaxGetProdfinDetails();
}