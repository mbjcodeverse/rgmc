<?php
require_once "../controllers/items.controller.php";
require_once "../models/items.model.php";

class AjaxProdStocks{ 
   public $itemid;
   public $asofdate;

   public function ajaxDisplayProdStocks(){
     $itemid = $this->itemid;
     $asofdate = $this->asofdate;
     $answer = (new ControllerItems)->ctrShowProdStocks($itemid, $asofdate);
     echo json_encode($answer);
   }
  
}

if(isset($_POST["itemid"])){
  $prod_stocks = new AjaxProdStocks();
  $prod_stocks -> itemid = $_POST["itemid"];
  $prod_stocks -> asofdate = $_POST["asofdate"];
  $prod_stocks -> ajaxDisplayProdStocks();
}