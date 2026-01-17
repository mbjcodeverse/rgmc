<?php
require_once "../controllers/inventoryrawmats.controller.php";
require_once "../models/inventoryrawmats.model.php";

class AjaxInventoryRawmatsTransactionList{ 
   public $start_date;
   public $end_date;   
   public $empid;
   public $invstatus;

   public function ajaxDisplayInventoryRawmatsTransactionList(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $invstatus = $this->invstatus;

     $answer = (new ControllerInventoryRawmats)->ctrShowInventoryRawmatsTransactionList($start_date, $end_date, $empid, $invstatus);
     echo json_encode($answer);
   }
}

$inventory_rawmats = new AjaxInventoryRawmatsTransactionList();
$inventory_rawmats -> start_date = $_POST["start_date"];
$inventory_rawmats -> end_date = $_POST["end_date"];
$inventory_rawmats -> empid = $_POST["empid"];
$inventory_rawmats -> invstatus = $_POST["invstatus"];
$inventory_rawmats -> ajaxDisplayInventoryRawmatsTransactionList();