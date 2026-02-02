<?php
require_once "../controllers/home.controller.php";
require_once "../models/home.model.php";

class AjaxStockPeriods{ 
   public function ajaxDisplayStockPeriods(){
     $periods = (new ControllerHome)->ctrShowStockPeriods();
     echo json_encode($periods);
   }
}

$stock_periods = new AjaxStockPeriods();
$stock_periods -> ajaxDisplayStockPeriods();