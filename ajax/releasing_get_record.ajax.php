<?php
require_once "../controllers/stockout.controller.php";
require_once "../models/stockout.model.php";

class AjaxReleasingDetails{
    public $reqnumber;
    public function ajaxGetReleasingDetails(){
      $reqnumber = $this->reqnumber;
      $answer = (new ControllerStockout)->ctrShowReleasing($reqnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["reqnumber"])){
  $getReleasing = new AjaxReleasingDetails();
  $getReleasing -> reqnumber = $_POST["reqnumber"];
  $getReleasing -> ajaxGetReleasingDetails();
}