<?php
require_once "../controllers/matreturn.controller.php";
require_once "../models/matreturn.model.php";

class AjaxMatreturnReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $postedby;
   public $returnby;
   public $retstatus;
   public $reptype;
   public $returntype;

   public function ajaxDisplayMatreturnReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $postedby = $this->postedby;
     $returnby = $this->returnby;
     $retstatus = $this->retstatus;
     $reptype = $this->reptype;
     $returntype = $this->returntype;

     $answer = (new ControllerMatreturn)->ctrShowMatreturnReport($machineid, $start_date, $end_date, $categorycode, $postedby, $returnby, $retstatus, $reptype, $returntype);
     echo json_encode($answer);
   }
}

$mat_return = new AjaxMatreturnReport();
$mat_return -> machineid = $_POST["machineid"];
$mat_return -> start_date = $_POST["start_date"];
$mat_return -> end_date = $_POST["end_date"];
$mat_return -> categorycode = $_POST["categorycode"];
$mat_return -> postedby = $_POST["postedby"];
$mat_return -> returnby = $_POST["returnby"];
$mat_return -> retstatus = $_POST["retstatus"];
$mat_return -> reptype = $_POST["reptype"];
$mat_return -> returntype = $_POST["returntype"];
$mat_return -> ajaxDisplayMatreturnReport();