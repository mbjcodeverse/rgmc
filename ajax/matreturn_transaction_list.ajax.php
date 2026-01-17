<?php
require_once "../controllers/matreturn.controller.php";
require_once "../models/matreturn.model.php";

class AjaxMatreturnTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $empid;
   public $retstatus;
   public $returntype;

   public function ajaxDisplayMatreturnTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $retstatus = $this->retstatus;
     $returntype = $this->returntype;

     $answer = (new ControllerMatreturn)->ctrShowMatreturnTransactionList($machineid, $start_date, $end_date, $empid, $retstatus, $returntype);
     echo json_encode($answer);
   }
}

$matreturn = new AjaxMatreturnTransactionList();
$matreturn -> machineid = $_POST["machineid"];
$matreturn -> start_date = $_POST["start_date"];
$matreturn -> end_date = $_POST["end_date"];
$matreturn -> empid = $_POST["empid"];
$matreturn -> retstatus = $_POST["retstatus"];
$matreturn -> returntype = $_POST["returntype"];
$matreturn -> ajaxDisplayMatreturnTransactionList();