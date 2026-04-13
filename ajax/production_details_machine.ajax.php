<?php
require_once "../controllers/factorydashboard.controller.php";
require_once "../models/factorydashboard.model.php";

class AjaxProductionDetailsMachine{ 
   public $category;
   public $start_date;
   public $end_date;   

   public function ajaxDisplayProductionDetailsMachine(){
     $category = $this->category;
     $start_date = $this->start_date;
     $end_date = $this->end_date;

     $answer = (new ControllerFactoryDashboard)->ctrShowProductionDetailsMachine($category, $start_date, $end_date);
     echo json_encode($answer);
   }
}

$production_details = new AjaxProductionDetailsMachine();
$production_details -> category = $_POST["category"];
$production_details -> start_date = $_POST["start_date"];
$production_details -> end_date = $_POST["end_date"];
$production_details -> ajaxDisplayProductionDetailsMachine();