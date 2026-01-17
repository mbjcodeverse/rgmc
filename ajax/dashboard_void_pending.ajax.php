<?php
require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

class AjaxVoidPendingList{ 
   public $branchcode;
   public $status;
   public $salemode;

   public function ajaxDisplayVoidPendingList(){
     $branchcode = $this->branchcode;
     $status = $this->status;
     $salemode = $this->salemode;

     $answer = (new ControllerSale)->ctrDashboardVoidPendingList($branchcode, $status, $salemode);
     echo json_encode($answer);
   }
}

$void_sale = new AjaxVoidPendingList();
$void_sale -> branchcode = $_POST["branchcode"];
$void_sale -> status = $_POST["status"];
$void_sale -> salemode = $_POST["salemode"];
$void_sale -> ajaxDisplayVoidPendingList();