<?php
require_once "../controllers/recycle.controller.php";
require_once "../models/recycle.model.php";

class AjaxRecycleDetails{
    public $recnumber;
    public function ajaxGetRecycleDetails(){
      $recnumber = $this->recnumber;
      $answer = (new ControllerRecycle)->ctrShowRecycle($recnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["recnumber"])){
  $getRecycle = new AjaxRecycleDetails();
  $getRecycle -> recnumber = $_POST["recnumber"];
  $getRecycle -> ajaxGetRecycleDetails();
}