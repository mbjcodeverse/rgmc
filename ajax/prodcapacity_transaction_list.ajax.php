<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxProdcapacityTransactionList{ 
   public $classcode;
   public $etype;

   public function ajaxDisplayProdcapacityTransactionList(){
     $classcode = $this->classcode;
     $etype = $this->etype;

     $answer = (new ControllerProdfin)->ctrShowProdcapacityTransactionList($classcode, $etype);
     echo json_encode($answer);
   }
}

$prod_capacity = new AjaxProdcapacityTransactionList();
$prod_capacity -> classcode = $_POST["classcode"];
$prod_capacity -> etype = $_POST["etype"];
$prod_capacity -> ajaxDisplayProdcapacityTransactionList();