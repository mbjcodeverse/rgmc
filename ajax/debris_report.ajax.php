<?php
require_once "../controllers/debris.controller.php";
require_once "../models/debris.model.php";

class AjaxDebrisReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $postedby;
   public $debrisby;
   public $debstatus;
   public $reptype;

   public function ajaxDisplayDebrisReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $postedby = $this->postedby;
     $debrisby = $this->debrisby;
     $debstatus = $this->debstatus;
     $reptype = $this->reptype;

     $answer = (new ControllerDebris)->ctrShowDebrisReport($machineid, $start_date, $end_date, $categorycode, $postedby, $debrisby, $debstatus, $reptype);
     echo json_encode($answer);
   }
}

$debris_report = new AjaxDebrisReport();
$debris_report -> machineid = $_POST["machineid"];
$debris_report -> start_date = $_POST["start_date"];
$debris_report -> end_date = $_POST["end_date"];
$debris_report -> categorycode = $_POST["categorycode"];
$debris_report -> postedby = $_POST["postedby"];
$debris_report -> debrisby = $_POST["debrisby"];
$debris_report -> debstatus = $_POST["debstatus"];
$debris_report -> reptype = $_POST["reptype"];
$debris_report -> ajaxDisplayDebrisReport();