<?php
require_once "../controllers/return.controller.php";
require_once "../models/return.model.php";

class AjaxReturnReport{ 
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $postedby;
   public $returnby;
   public $retstatus;
   public $reptype;

   public function ajaxDisplayReturnReport(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $postedby = $this->postedby;
     $returnby = $this->returnby;
     $retstatus = $this->retstatus;
     $reptype = $this->reptype;

     $answer = (new ControllerReturn)->ctrShowReturnReport($start_date, $end_date, $categorycode, $postedby, $returnby, $retstatus, $reptype);
     echo json_encode($answer);
   }
}

$return_report = new AjaxReturnReport();
$return_report -> start_date = $_POST["start_date"];
$return_report -> end_date = $_POST["end_date"];
$return_report -> categorycode = $_POST["categorycode"];
$return_report -> postedby = $_POST["postedby"];
$return_report -> returnby = $_POST["returnby"];
$return_report -> retstatus = $_POST["retstatus"];
$return_report -> reptype = $_POST["reptype"];
$return_report -> ajaxDisplayReturnReport();