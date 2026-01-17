<?php
require_once "../controllers/stockout.controller.php";
require_once "../models/stockout.model.php";

class AjaxReleasingTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $empid;
   public $reqstatus;
   public $reltype;

   public function ajaxDisplayReleasingTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $reqstatus = $this->reqstatus;
     $reltype = $this->reltype;

     $answer = (new ControllerStockout)->ctrShowReleasingTransactionList($machineid, $start_date, $end_date, $empid, $reqstatus, $reltype);
     echo json_encode($answer);
   }
}

$Releasing = new AjaxReleasingTransactionList();
$Releasing -> machineid = $_POST["machineid"];
$Releasing -> start_date = $_POST["start_date"];
$Releasing -> end_date = $_POST["end_date"];
$Releasing -> empid = $_POST["empid"];
$Releasing -> reqstatus = $_POST["reqstatus"];
$Releasing -> reltype = $_POST["reltype"];
$Releasing -> ajaxDisplayReleasingTransactionList();