<?php
require_once "../controllers/machine.controller.php";
require_once "../models/machine.model.php";

class AjaxAttributesItems{ 
   public $machineid;

   public function ajaxDisplayAttributesItems(){
     $machineid = $this->machineid;
     $attributes = (new ControllerMachine)->ctrShowAttributes($machineid);
     echo json_encode($attributes);
   }
}

$attribute_items = new AjaxAttributesItems();
$attribute_items -> machineid = $_POST["machineid"];
$attribute_items -> ajaxDisplayAttributesItems();