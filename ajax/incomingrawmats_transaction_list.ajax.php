<?php
require_once "../controllers/incomingrawmats.controller.php";
require_once "../models/incomingrawmats.model.php";

class AjaxRawMatsOrderTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $suppliercode;
   public $postatus;

   public function ajaxDisplayRawMatsOrderTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $suppliercode = $this->suppliercode;
     $postatus = $this->postatus;

     $answer = (new ControllerIncomingRawMatsOrder)->ctrShowIncomingRawMatsOrderTransactionList($machineid, $start_date, $end_date, $suppliercode, $postatus);
     echo json_encode($answer);
   }
}

$purchaseorder = new AjaxRawMatsOrderTransactionList();
$purchaseorder -> machineid = $_POST["machineid"];
$purchaseorder -> start_date = $_POST["start_date"];
$purchaseorder -> end_date = $_POST["end_date"];
$purchaseorder -> suppliercode = $_POST["suppliercode"];
$purchaseorder -> postatus = $_POST["postatus"];
$purchaseorder -> ajaxDisplayRawMatsOrderTransactionList();