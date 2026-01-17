<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxProdfinItems{ 
   public $prodnumber;

   public function ajaxDisplayProdfinItems(){
     $prodnumber = $this->prodnumber;
     $products = (new ControllerProdfin)->ctrShowProdfinItems($prodnumber);
     echo json_encode($products);
   }
}

$rawout_items = new AjaxProdfinItems();
$rawout_items -> prodnumber = $_POST["prodnumber"];
$rawout_items -> ajaxDisplayProdfinItems();