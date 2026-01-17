<?php
require_once "../controllers/excess.controller.php";
require_once "../models/excess.model.php";

class AjaxExcessReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $postedby;
   public $operatedby;
   public $excstatus;
   public $reptype;

   public function ajaxDisplayExcessReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $postedby = $this->postedby;
     $operatedby = $this->operatedby;
     $excstatus = $this->excstatus;
     $reptype = $this->reptype;

     $answer = (new ControllerExcess)->ctrShowExcessReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $excstatus, $reptype);
     echo json_encode($answer);
   }
}

$excess_report = new AjaxExcessReport();
$excess_report -> machineid = $_POST["machineid"];
$excess_report -> start_date = $_POST["start_date"];
$excess_report -> end_date = $_POST["end_date"];
$excess_report -> categorycode = $_POST["categorycode"];
$excess_report -> postedby = $_POST["postedby"];
$excess_report -> operatedby = $_POST["operatedby"];
$excess_report -> excstatus = $_POST["excstatus"];
$excess_report -> reptype = $_POST["reptype"];
$excess_report -> ajaxDisplayExcessReport();