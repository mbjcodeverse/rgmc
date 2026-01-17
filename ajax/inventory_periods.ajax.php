<?php
require_once "../controllers/factorydashboard.controller.php";
require_once "../models/factorydashboard.model.php";

class AjaxInventoryPeriods{ 
   public $end_date;
   public function ajaxDisplayInventoryPeriods(){
      $end_date = $this->end_date;
      $periods = (new ControllerFactoryDashboard)->ctrShowInventoryPeriods($end_date);
      echo json_encode($periods);
   }
}

$inventory_periods = new AjaxInventoryPeriods();
$inventory_periods -> end_date = $_POST["end_date"];
$inventory_periods -> ajaxDisplayInventoryPeriods();