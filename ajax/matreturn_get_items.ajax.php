<?php
require_once "../controllers/matreturn.controller.php";
require_once "../models/matreturn.model.php";

class AjaxMatreturnItems{ 
   public $retnumber;

   public function ajaxDisplayMatreturnItems(){
     $retnumber = $this->retnumber;
     $products = (new ControllerMatreturn)->ctrShowMatreturnItems($retnumber);
     echo json_encode($products);
   }
}

$matreturn_items = new AjaxMatreturnItems();
$matreturn_items -> retnumber = $_POST["retnumber"];
$matreturn_items -> ajaxDisplayMatreturnItems();