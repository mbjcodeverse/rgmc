<?php
require_once "../controllers/purchaseorder.controller.php";
require_once "../models/purchaseorder.model.php";

class AjaxPurchaseOrderTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $suppliercode;
   public $postatus;

   public function ajaxDisplayPurchaseOrderTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $suppliercode = $this->suppliercode;
     $postatus = $this->postatus;

     $answer = (new ControllerPurchaseOrder)->ctrShowPurchaseOrderTransactionList($machineid, $start_date, $end_date, $suppliercode, $postatus);
     echo json_encode($answer);
   }
}

$purchaseorder = new AjaxPurchaseOrderTransactionList();
$purchaseorder -> machineid = $_POST["machineid"];
$purchaseorder -> start_date = $_POST["start_date"];
$purchaseorder -> end_date = $_POST["end_date"];
$purchaseorder -> suppliercode = $_POST["suppliercode"];
$purchaseorder -> postatus = $_POST["postatus"];
$purchaseorder -> ajaxDisplayPurchaseOrderTransactionList();