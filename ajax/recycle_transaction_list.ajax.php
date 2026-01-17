<?php
require_once "../controllers/recycle.controller.php";
require_once "../models/recycle.model.php";

class AjaxRecycleTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $empid;
   public $recstatus;

   public function ajaxDisplayRecycleTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $recstatus = $this->recstatus;

     $answer = (new ControllerRecycle)->ctrShowRecycleTransactionList($machineid, $start_date, $end_date, $empid, $recstatus);
     echo json_encode($answer);
   }
}

$rawout = new AjaxRecycleTransactionList();
$rawout -> machineid = $_POST["machineid"];
$rawout -> start_date = $_POST["start_date"];
$rawout -> end_date = $_POST["end_date"];
$rawout -> empid = $_POST["empid"];
$rawout -> recstatus = $_POST["recstatus"];
$rawout -> ajaxDisplayRecycleTransactionList();