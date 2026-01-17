<?php
require_once "../controllers/debris.controller.php";
require_once "../models/debris.model.php";

class AjaxCancelDebris{
    public $debnumber;
    public function ajaxCancelDebris(){
      $debnumber = $this->debnumber;
      $answer = (new ControllerDebris)->ctrCancelDebris($debnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["debnumber"])){
  $cancel_debris = new AjaxCancelDebris();
  $cancel_debris -> debnumber = $_POST["debnumber"];
  $cancel_debris -> ajaxCancelDebris();
}