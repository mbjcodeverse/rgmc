<?php
require_once "../controllers/users.controller.php";
require_once "../models/users.model.php";

class AjaxGetOverrideKey{
    public $override_key;
    public function ajaxGetGetOverrideKey(){
      $override_key = $this->override_key;
      $answer = (new ControllerUsers)->ctrGetOverrideKey($override_key);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["override_key"])){
  $getGetOverrideKey = new AjaxGetOverrideKey();
  $getGetOverrideKey -> override_key = $_POST["override_key"];
  $getGetOverrideKey -> ajaxGetGetOverrideKey();
}