<?php
require_once "../controllers/prodmetrics.controller.php";
require_once "../models/prodmetrics.model.php";

class AjaxProductmetricsTransactionList{ 
   public $start_date;
   public $end_date;   
   public $empid;
   public $mstatus;

   public function ajaxDisplayProductmetricsTransactionList(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $mstatus = $this->mstatus;

     $answer = (new ControllerProductmetrics)->ctrShowProductmetricsTransactionList($start_date, $end_date, $empid, $mstatus);
     echo json_encode($answer);
   }
}

$product_metrics = new AjaxProductmetricsTransactionList();
$product_metrics -> start_date = $_POST["start_date"];
$product_metrics -> end_date = $_POST["end_date"];
$product_metrics -> empid = $_POST["empid"];
$product_metrics -> mstatus = $_POST["mstatus"];
$product_metrics -> ajaxDisplayProductmetricsTransactionList();