<?php
require_once "../controllers/excess.controller.php";
require_once "../models/excess.model.php";

class AjaxExcessDetails{
    public $excnumber;
    public function ajaxGetExcessDetails(){
      $excnumber = $this->excnumber;
      $answer = (new ControllerExcess)->ctrShowExcess($excnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["excnumber"])){
  $getExcess = new AjaxExcessDetails();
  $getExcess -> excnumber = $_POST["excnumber"];
  $getExcess -> ajaxGetExcessDetails();
}