<?php
require_once "../controllers/othercost.controller.php";
require_once "../models/othercost.model.php";

class AjaxOthercostDetails{
    public $ocostid;
    public function ajaxGetOthercostDetails(){
      $ocostid = $this->ocostid;
      $answer = (new ControllerOthercost)->ctrShowOthercost($ocostid);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["ocostid"])){
  $getOthercost = new AjaxOthercostDetails();
  $getOthercost -> ocostid = $_POST["ocostid"];
  $getOthercost -> ajaxGetOthercostDetails();
}