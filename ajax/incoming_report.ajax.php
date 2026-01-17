<?php
require_once "../controllers/incoming.controller.php";
require_once "../models/incoming.model.php";

class AjaxIncomingReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $suppliercode;
   public $delstatus;
   public $reptype;

   public function ajaxDisplayIncomingReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $suppliercode = $this->suppliercode;
     $delstatus = $this->delstatus;
     $reptype = $this->reptype;

     $answer = (new ControllerIncoming)->ctrShowIncomingReport($machineid, $start_date, $end_date, $categorycode, $suppliercode, $delstatus, $reptype);
     echo json_encode($answer);
   }
}

$incoming_report = new AjaxIncomingReport();
$incoming_report -> machineid = $_POST["machineid"];
$incoming_report -> start_date = $_POST["start_date"];
$incoming_report -> end_date = $_POST["end_date"];
$incoming_report -> categorycode = $_POST["categorycode"];
$incoming_report -> suppliercode = $_POST["suppliercode"];
$incoming_report -> delstatus = $_POST["delstatus"];
$incoming_report -> reptype = $_POST["reptype"];
$incoming_report -> ajaxDisplayIncomingReport();