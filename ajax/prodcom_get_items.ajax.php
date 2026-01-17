<?php
require_once "../controllers/prodcom.controller.php";
require_once "../models/prodcom.model.php";

class AjaxProdcomItems{ 
   public $prodnumber;

   public function ajaxDisplayProdcomItems(){
     $prodnumber = $this->prodnumber;
     $products = (new ControllerProdcom)->ctrShowProdcomItems($prodnumber);
     echo json_encode($products);
   }
}

$rawout_items = new AjaxProdcomItems();
$rawout_items -> prodnumber = $_POST["prodnumber"];
$rawout_items -> ajaxDisplayProdcomItems();