<?php
require_once "../controllers/inventory.controller.php";
require_once "../models/inventory.model.php";

class AjaxInventoryTransactionList{ 
   public $start_date;
   public $end_date;   
   public $empid;
   public $invstatus;

   public function ajaxDisplayInventoryTransactionList(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $empid = $this->empid;
     $invstatus = $this->invstatus;

     $answer = (new ControllerInventory)->ctrShowInventoryTransactionList($start_date, $end_date, $empid, $invstatus);
     echo json_encode($answer);
   }
}

$inventory = new AjaxInventoryTransactionList();
$inventory -> start_date = $_POST["start_date"];
$inventory -> end_date = $_POST["end_date"];
$inventory -> empid = $_POST["empid"];
$inventory -> invstatus = $_POST["invstatus"];
$inventory -> ajaxDisplayInventoryTransactionList();