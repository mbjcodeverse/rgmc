<?php
require_once "../controllers/factorydashboard.controller.php";
require_once "../models/factorydashboard.model.php";

class AjaxUsageSummary{ 
   public $itemid;
   public $start_date;
   public $end_date;

   public function ajaxDisplayUsageSummary(){
     $itemid = $this->itemid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $answer = (new ControllerFactoryDashboard)->ctrShowUsageSummary($itemid, $start_date, $end_date);
     echo json_encode($answer);
   }
}

$summary_usage = new AjaxUsageSummary();
$summary_usage -> itemid = $_POST["itemid"];
$summary_usage -> start_date = $_POST["start_date"];
$summary_usage -> end_date = $_POST["end_date"];
$summary_usage -> ajaxDisplayUsageSummary();