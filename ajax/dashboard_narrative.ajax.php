<?php
require_once "../controllers/home.controller.php";
require_once "../models/home.model.php";

class AjaxDashboardNarrative{
    public function ajaxUpdateDashboardNarrative(){
      $answer = (new ControllerHome)->ctrUpdateDashboardNarrative();
      echo json_encode($answer);
    }
}
 
$updateNarrative = new AjaxDashboardNarrative();
$updateNarrative -> ajaxUpdateDashboardNarrative();
