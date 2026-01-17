<?php
require_once "../controllers/factorydashboard.controller.php";
require_once "../models/factorydashboard.model.php";

class AjaxFactoryDashboard{ 
   public $reptype;
   public $start_date;
   public $end_date;   

   public function ajaxDisplayFactoryDashboard(){
     $reptype = $this->reptype;
     $start_date = $this->start_date;
     $end_date = $this->end_date;

     $answer = (new ControllerFactoryDashboard)->ctrShowFactoryDashboard($reptype, $start_date, $end_date);
     echo json_encode($answer);
   }
}

$factory_dashboard = new AjaxFactoryDashboard();
$factory_dashboard -> reptype = $_POST["reptype"];
$factory_dashboard -> start_date = $_POST["start_date"];
$factory_dashboard -> end_date = $_POST["end_date"];
$factory_dashboard -> ajaxDisplayFactoryDashboard();