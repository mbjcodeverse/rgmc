<?php
require_once "../controllers/factorydashboard.controller.php";
require_once "../models/factorydashboard.model.php";

class AjaxDashboardAssessment{ 
   public $start_date;
   public $end_date;   
   public $categorycode;
   public $tier;

   public function ajaxDisplayDashboardAssessment(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $tier = $this->tier;

     $answer = (new ControllerFactoryDashboard)->ctrShowDashboardAssessment($start_date, $end_date, $categorycode, $tier);
     echo json_encode($answer);
   }
}

$assessment_dashboard = new AjaxDashboardAssessment();
$assessment_dashboard -> start_date = $_POST["start_date"];
$assessment_dashboard -> end_date = $_POST["end_date"];
$assessment_dashboard -> categorycode = $_POST["categorycode"];
$assessment_dashboard -> tier = $_POST["tier"];
$assessment_dashboard -> ajaxDisplayDashboardAssessment();