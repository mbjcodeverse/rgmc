<?php
require_once "../controllers/return.controller.php";
require_once "../models/return.model.php";

class AjaxReturnTransactionList{ 
   public $start_date;
   public $end_date;   
   public $empid;
   public $retstatus;

   public function ajaxDisplayReturnTransactionList(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $retstatus = $this->retstatus;

     $answer = (new ControllerReturn)->ctrShowReturnTransactionList($start_date, $end_date, $empid, $retstatus);
     echo json_encode($answer);
   }
}

$return = new AjaxReturnTransactionList();
$return -> start_date = $_POST["start_date"];
$return -> end_date = $_POST["end_date"];
$return -> empid = $_POST["empid"];
$return -> retstatus = $_POST["retstatus"];
$return -> ajaxDisplayReturnTransactionList();