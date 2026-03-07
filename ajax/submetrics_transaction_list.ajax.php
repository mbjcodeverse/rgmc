<?php
require_once "../controllers/factorydashboard.controller.php";
require_once "../models/factorydashboard.model.php";

class AjaxSubcomponentsMetrics{ 
   public $reptype;
   public $start_date;
   public $end_date;   

   public function ajaxDisplaySubcomponentsMetrics(){
     $reptype = $this->reptype;
     $start_date = $this->start_date;
     $end_date = $this->end_date;

     $answer = (new ControllerFactoryDashboard)->ctrShowSubcomponentsMetrics($reptype, $start_date, $end_date);
     echo json_encode($answer);
   }
}

$subcomponents_metrics = new AjaxSubcomponentsMetrics();
$subcomponents_metrics -> reptype = $_POST["reptype"];
$subcomponents_metrics -> start_date = $_POST["start_date"];
$subcomponents_metrics -> end_date = $_POST["end_date"];
$subcomponents_metrics -> ajaxDisplaySubcomponentsMetrics();