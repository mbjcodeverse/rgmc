<?php
require_once "../controllers/debris.controller.php";
require_once "../models/debris.model.php";

class AjaxDebrisDetails{
    public $debnumber;
    public function ajaxGetDebrisDetails(){
      $debnumber = $this->debnumber;
      $answer = (new ControllerDebris)->ctrShowDebris($debnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["debnumber"])){
  $getDebris = new AjaxDebrisDetails();
  $getDebris -> debnumber = $_POST["debnumber"];
  $getDebris -> ajaxGetDebrisDetails();
}