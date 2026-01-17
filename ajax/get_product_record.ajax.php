<?php
require_once "../controllers/product.controller.php";
require_once "../models/product.model.php";

class AjaxProduct{
    public $prodid;
    public function ajaxGetProduct(){
      $item = "prodid";
      $value = $this->prodid;
      $answer = (new ControllerProduct)->ctrShowProduct($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["prodid"])){
  $getProduct = new AjaxProduct();
  $getProduct -> prodid = $_POST["prodid"];
  $getProduct -> ajaxGetProduct();
}