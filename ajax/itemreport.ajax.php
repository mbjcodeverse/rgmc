<?php
require_once "../controllers/items.controller.php";
require_once "../models/items.model.php";

class AjaxItemsReport{ 
   public $reptype;
   public $filter;
   public $categorycode;  
   public function ajaxDisplayItemsReport(){
     $reptype = $this->reptype;
     $filter = $this->filter;
     $categorycode = $this->categorycode;
     
     $answer = (new ControllerItems)->ctrItemReport($reptype, $filter, $categorycode);
     echo json_encode($answer);
   }
}

$item_report = new AjaxItemsReport();
$item_report -> reptype = $_POST["reptype"];
$item_report -> filter = $_POST["filter"];
$item_report -> categorycode = $_POST["categorycode"];

$item_report -> ajaxDisplayItemsReport();