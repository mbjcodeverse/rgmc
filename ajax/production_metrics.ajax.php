<?php
require_once "../controllers/factorydashboard.controller.php";
require_once "../models/factorydashboard.model.php";

class AjaxProductionMetrics{ 
   public $reptype;
   public $start_date;
   public $end_date;   

   public function ajaxDisplayProductionMetrics(){
     $reptype = $this->reptype;
     $start_date = $this->start_date;
     $end_date = $this->end_date;

     $answer = (new ControllerFactoryDashboard)->ctrShowProductionMetrics($reptype, $start_date, $end_date);
     echo json_encode($answer);
   }
}

$production_metrics = new AjaxProductionMetrics();
$production_metrics -> reptype = $_POST["reptype"];
$production_metrics -> start_date = $_POST["start_date"];
$production_metrics -> end_date = $_POST["end_date"];
$production_metrics -> ajaxDisplayProductionMetrics();