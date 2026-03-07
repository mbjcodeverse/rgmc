<?php
require_once "../controllers/submetrics.controller.php";
require_once "../models/submetrics.model.php";

class AjaxSubmetricsTransactionList{ 
   public $start_date;
   public $end_date;   
   public $empid;
   public $mstatus;

   public function ajaxDisplaySubmetricsTransactionList(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $mstatus = $this->mstatus;

     $answer = (new ControllerSubmetrics)->ctrShowSubmetricsTransactionList($start_date, $end_date, $empid, $mstatus);
     echo json_encode($answer);
   }
}

$sub_metrics = new AjaxSubmetricsTransactionList();
$sub_metrics -> start_date = $_POST["start_date"];
$sub_metrics -> end_date = $_POST["end_date"];
$sub_metrics -> empid = $_POST["empid"];
$sub_metrics -> mstatus = $_POST["mstatus"];
$sub_metrics -> ajaxDisplaySubmetricsTransactionList();