<?php
require_once "../controllers/othercost.controller.php";
require_once "../models/othercost.model.php";

class AjaxOthercostTransactionList{ 
   public $start_date;
   public $end_date;   
   public $empid;
   public $ostatus;

   public function ajaxDisplayOthercostTransactionList(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $ostatus = $this->ostatus;

     $answer = (new ControllerOthercost)->ctrShowOthercostTransactionList($start_date, $end_date, $empid, $ostatus);
     echo json_encode($answer);
   }
}

$othercost = new AjaxOthercostTransactionList();
$othercost -> start_date = $_POST["start_date"];
$othercost -> end_date = $_POST["end_date"];
$othercost -> empid = $_POST["empid"];
$othercost -> ostatus = $_POST["ostatus"];
$othercost -> ajaxDisplayOthercostTransactionList();