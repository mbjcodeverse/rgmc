<?php
require_once "../controllers/rawout.controller.php";
require_once "../models/rawout.model.php";

class AjaxRawoutReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $postedby;
   public $requestby;
   public $reqstatus;
   public $reptype;
   public $costype;

   public function ajaxDisplayRawoutReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $postedby = $this->postedby;
     $requestby = $this->requestby;
     $reqstatus = $this->reqstatus;
     $reptype = $this->reptype;
     $costype = $this->costype;

     $answer = (new ControllerRawout)->ctrShowRawoutReport($machineid, $start_date, $end_date, $categorycode, $postedby, $requestby, $reqstatus, $reptype, $costype);
     echo json_encode($answer);
   }
}

$release_report = new AjaxRawoutReport();
$release_report -> machineid = $_POST["machineid"];
$release_report -> start_date = $_POST["start_date"];
$release_report -> end_date = $_POST["end_date"];
$release_report -> categorycode = $_POST["categorycode"];
$release_report -> postedby = $_POST["postedby"];
$release_report -> requestby = $_POST["requestby"];
$release_report -> reqstatus = $_POST["reqstatus"];
$release_report -> reptype = $_POST["reptype"];
$release_report -> costype = $_POST["costype"];
$release_report -> ajaxDisplayRawoutReport();