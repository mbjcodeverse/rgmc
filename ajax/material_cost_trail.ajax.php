<?php
require_once "../controllers/factorydashboard.controller.php";
require_once "../models/factorydashboard.model.php";

class AjaxMaterialCostTrail{ 
   public $trans_date;

   public function ajaxDisplayMaterialCostTrail(){
     $trans_date = $this->trans_date;

     $answer = (new ControllerFactoryDashboard)->ctrShowMaterialCostTrail($trans_date);
     echo json_encode($answer);
   }
}

$material_trail = new AjaxMaterialCostTrail();
$material_trail -> trans_date = $_POST["trans_date"];
$material_trail -> ajaxDisplayMaterialCostTrail();