<?php
require_once "../controllers/items.controller.php";
require_once "../models/items.model.php";

class AjaxItemsList{ 
   public $categorycode;

   public function ajaxDisplayItemsList(){
     $categorycode = $this->categorycode;

     $answer = (new ControllerItems)->ctrShowItemsList($categorycode);
     echo json_encode($answer);
   }
}

$items = new AjaxItemsList();
$items -> categorycode = $_POST["categorycode"];
$items -> ajaxDisplayItemsList();