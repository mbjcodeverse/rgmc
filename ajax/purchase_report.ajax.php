<?php
require_once "../controllers/purchaseorder.controller.php";
require_once "../models/purchaseorder.model.php";

class AjaxPurchaseReport{ 
   public $machineid;
   public $start_date;
   public $end_date;
   public $categorycode;   
   public $suppliercode;
   public $orderedby;
   public $postatus;
   public $reptype;

   public function ajaxDisplayPurchaseReport(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $categorycode = $this->categorycode;
     $suppliercode = $this->suppliercode;
     $orderedby = $this->orderedby;
     $postatus = $this->postatus;
     $reptype = $this->reptype;

     $answer = (new ControllerPurchaseOrder)->ctrShowPurchaseReport($machineid, $start_date, $end_date, $categorycode, $suppliercode, $orderedby, $postatus, $reptype);
     echo json_encode($answer);
   }
}

$purchase_report = new AjaxPurchaseReport();
$purchase_report -> machineid = $_POST["machineid"];
$purchase_report -> start_date = $_POST["start_date"];
$purchase_report -> end_date = $_POST["end_date"];
$purchase_report -> categorycode = $_POST["categorycode"];
$purchase_report -> suppliercode = $_POST["suppliercode"];
$purchase_report -> orderedby = $_POST["orderedby"];
$purchase_report -> postatus = $_POST["postatus"];
$purchase_report -> reptype = $_POST["reptype"];
$purchase_report -> ajaxDisplayPurchaseReport();