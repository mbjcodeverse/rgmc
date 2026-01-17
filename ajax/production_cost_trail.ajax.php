<?php
require_once "../controllers/factorydashboard.controller.php";
require_once "../models/factorydashboard.model.php";

class AjaxProductionCostTrail{ 
   public $trans_date;

   public function ajaxDisplayProductionCostTrail(){
     $trans_date = $this->trans_date;

     $answer = (new ControllerFactoryDashboard)->ctrShowProductionCostTrail($trans_date);
     echo json_encode($answer);
   }
}

$production_trail = new AjaxProductionCostTrail();
$production_trail -> trans_date = $_POST["trans_date"];
$production_trail -> ajaxDisplayProductionCostTrail();