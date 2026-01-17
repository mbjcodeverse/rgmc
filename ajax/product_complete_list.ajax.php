<?php
require_once "../controllers/product.controller.php";
require_once "../models/product.model.php";

class AjaxProductSalesList{
   public $branchcode;

   public function ajaxDisplayProductSalesList(){
     $branchcode = $this->branchcode;

     $answer = (new ControllerProduct)->ctr_Show_Product_Sales_List($branchcode);
     echo json_encode($answer);
   }
}

$product = new AjaxProductSalesList();
$product -> branchcode = $_POST["branchcode"];
$product -> ajaxDisplayProductSalesList();