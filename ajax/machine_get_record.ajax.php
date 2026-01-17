<?php
require_once "../controllers/machine.controller.php";
require_once "../models/machine.model.php";

class AjaxMachine{
    public $machineid;
    public function ajaxGetMachine(){
      $item = "machineid";
      $value = $this->machineid;
      $answer = (new ControllerMachine)->ctrShowMachine($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["machineid"])){
  $getMachine = new AjaxMachine();
  $getMachine -> machineid = $_POST["machineid"];
  $getMachine -> ajaxGetMachine();
}