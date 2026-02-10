<?php
require_once "../controllers/machinetracking.controller.php";
require_once "../models/machinetracking.model.php";

class AjaxCancelJobOrder{
    public $id;
    public function ajaxCancelJobOrder(){
      $field = "id";
      $id = $this->id;
      $answer = (new ControllerMachineTracking())->ctrCancelJobOrder($field, $id);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["id"])){
  $cancelJobOrder = new AjaxCancelJobOrder();
  $cancelJobOrder -> id = $_POST["id"];
  $cancelJobOrder -> ajaxCancelJobOrder();
}