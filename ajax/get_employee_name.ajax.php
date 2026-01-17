<?php
require_once "../controllers/employees.controller.php";
require_once "../models/employees.model.php";
class AjaxEmployeeName{
    public $empid;
    public function ajaxGetEmployeeName(){
      $item = "empid";
      $value = $this->empid;
      $answer = (new ControllerEmployees)->ctrShowEmployeeName($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["empid"])){
  $getEmployeeName = new AjaxEmployeeName();
  $getEmployeeName -> empid = $_POST["empid"];
  $getEmployeeName -> ajaxGetEmployeeName();
}