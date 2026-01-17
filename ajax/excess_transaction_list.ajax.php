<?php
require_once "../controllers/excess.controller.php";
require_once "../models/excess.model.php";

class AjaxExcessTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $empid;
   public $excstatus;

   public function ajaxDisplayExcessTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $excstatus = $this->excstatus;

     $answer = (new ControllerExcess)->ctrShowExcessTransactionList($machineid, $start_date, $end_date, $empid, $excstatus);
     echo json_encode($answer);
   }
}

$excess = new AjaxExcessTransactionList();
$excess -> machineid = $_POST["machineid"];
$excess -> start_date = $_POST["start_date"];
$excess -> end_date = $_POST["end_date"];
$excess -> empid = $_POST["empid"];
$excess -> excstatus = $_POST["excstatus"];
$excess -> ajaxDisplayExcessTransactionList();