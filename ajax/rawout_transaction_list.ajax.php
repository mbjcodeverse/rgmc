<?php
require_once "../controllers/rawout.controller.php";
require_once "../models/rawout.model.php";

class AjaxRawoutTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $empid;
   public $reqstatus;

   public function ajaxDisplayRawoutTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $reqstatus = $this->reqstatus;

     $answer = (new ControllerRawout)->ctrShowRawoutTransactionList($machineid, $start_date, $end_date, $empid, $reqstatus);
     echo json_encode($answer);
   }
}

$rawout = new AjaxRawoutTransactionList();
$rawout -> machineid = $_POST["machineid"];
$rawout -> start_date = $_POST["start_date"];
$rawout -> end_date = $_POST["end_date"];
$rawout -> empid = $_POST["empid"];
$rawout -> reqstatus = $_POST["reqstatus"];
$rawout -> ajaxDisplayRawoutTransactionList();