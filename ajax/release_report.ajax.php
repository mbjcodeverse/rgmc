<?php
require_once "../controllers/stockout.controller.php";
require_once "../models/stockout.model.php";

class AjaxStockoutReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $deptcode;
   public $categorycode;   
   public $postedby;
   public $requestby;
   public $reqstatus;
   public $reptype;

   public function ajaxDisplayStockoutReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $deptcode = $this->deptcode;
     $categorycode = $this->categorycode;
     $postedby = $this->postedby;
     $requestby = $this->requestby;
     $reqstatus = $this->reqstatus;
     $reptype = $this->reptype;

     $answer = (new ControllerStockout)->ctrShowStockoutReport($machineid, $start_date, $end_date, $deptcode, $categorycode, $postedby, $requestby, $reqstatus, $reptype);
     echo json_encode($answer);
   }
}

$release_report = new AjaxStockoutReport();
$release_report -> machineid = $_POST["machineid"];
$release_report -> start_date = $_POST["start_date"];
$release_report -> end_date = $_POST["end_date"];
$release_report -> deptcode = $_POST["deptcode"];
$release_report -> categorycode = $_POST["categorycode"];
$release_report -> postedby = $_POST["postedby"];
$release_report -> requestby = $_POST["requestby"];
$release_report -> reqstatus = $_POST["reqstatus"];
$release_report -> reptype = $_POST["reptype"];
$release_report -> ajaxDisplayStockoutReport();