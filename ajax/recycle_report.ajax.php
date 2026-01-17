<?php
require_once "../controllers/recycle.controller.php";
require_once "../models/recycle.model.php";

class AjaxRecycleReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $postedby;
   public $recycleby;
   public $recstatus;
   public $reptype;

   public function ajaxDisplayRecycleReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $postedby = $this->postedby;
     $recycleby = $this->recycleby;
     $recstatus = $this->recstatus;
     $reptype = $this->reptype;

     $answer = (new ControllerRecycle)->ctrShowRecycleReport($machineid, $start_date, $end_date, $categorycode, $postedby, $recycleby, $recstatus, $reptype);
     echo json_encode($answer);
   }
}

$recycle_report = new AjaxRecycleReport();
$recycle_report -> machineid = $_POST["machineid"];
$recycle_report -> start_date = $_POST["start_date"];
$recycle_report -> end_date = $_POST["end_date"];
$recycle_report -> categorycode = $_POST["categorycode"];
$recycle_report -> postedby = $_POST["postedby"];
$recycle_report -> recycleby = $_POST["recycleby"];
$recycle_report -> recstatus = $_POST["recstatus"];
$recycle_report -> reptype = $_POST["reptype"];
$recycle_report -> ajaxDisplayRecycleReport();