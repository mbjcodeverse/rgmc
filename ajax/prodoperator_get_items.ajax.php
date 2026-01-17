<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxProdoperatorItems{ 
   public $prodnumber;
   public $etype;

   public function ajaxDisplayProdoperatorItems(){
     $prodnumber = $this->prodnumber;
     $etype = $this->etype;
     $products = (new ControllerProdfin)->ctrShowProdoperatorItems($prodnumber,$etype);
     echo json_encode($products);
   }
}

$operatorout_items = new AjaxProdoperatorItems();
$operatorout_items -> prodnumber = $_POST["prodnumber"];
$operatorout_items -> etype = $_POST["etype"];
$operatorout_items -> ajaxDisplayProdoperatorItems();