<?php
require_once "../controllers/home.controller.php";
require_once "../models/home.model.php";

class AjaxDashboardCounter{
    public function ajaxUpdateDashboardCounter(){
      $answer = (new ControllerHome)->ctrUpdateDashboardCounter();
      echo json_encode($answer);
    }
}
 
$updateCounter = new AjaxDashboardCounter();
$updateCounter -> ajaxUpdateDashboardCounter();
