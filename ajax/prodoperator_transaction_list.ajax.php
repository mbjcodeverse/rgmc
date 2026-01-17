<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxProdoperatorTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $empid;
   public $prodstatus;
   public $etype;
   public $postedby;

   public function ajaxDisplayProdoperatorTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $prodstatus = $this->prodstatus;
     $etype = $this->etype;
     $postedby = $this->postedby;

     $answer = (new ControllerProdfin)->ctrShowProdoperatorTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus, $etype, $postedby);
     echo json_encode($answer);
   }
}

$prodoperator = new AjaxProdoperatorTransactionList();
$prodoperator -> machineid = $_POST["machineid"];
$prodoperator -> start_date = $_POST["start_date"];
$prodoperator -> end_date = $_POST["end_date"];
$prodoperator -> empid = $_POST["empid"];
$prodoperator -> prodstatus = $_POST["prodstatus"];
$prodoperator -> etype = $_POST["etype"];
$prodoperator -> postedby = $_POST["postedby"];
$prodoperator -> ajaxDisplayProdoperatorTransactionList();