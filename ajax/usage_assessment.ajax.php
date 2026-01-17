<?php
require_once "../controllers/factorydashboard.controller.php";
require_once "../models/factorydashboard.model.php";

class AjaxUsageAssessment{ 
   public $start_date;
   public $end_date;   
   public $categorycode;

   public function ajaxDisplayUsageAssessment(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;

     $answer = (new ControllerFactoryDashboard)->ctrShowUsageAssessment($start_date, $end_date, $categorycode);
     echo json_encode($answer);
   }
}

$assessment_usage = new AjaxUsageAssessment();
$assessment_usage -> start_date = $_POST["start_date"];
$assessment_usage -> end_date = $_POST["end_date"];
$assessment_usage -> categorycode = $_POST["categorycode"];
$assessment_usage -> ajaxDisplayUsageAssessment();