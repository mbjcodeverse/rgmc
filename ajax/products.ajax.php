<?php
require_once "../controllers/products.controller.php";
require_once "../models/products.model.php";
class AjaxProduct{
    public $idProduct;
    public function ajaxGetProduct(){
      $item = "id";
      $value = $this->idProduct;
      $answer = (new ControllerProducts)->ctrGetProduct($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idProduct"])){
  $getProduct = new AjaxProduct();
  $getProduct -> idProduct = $_POST["idProduct"];
  $getProduct -> ajaxGetProduct();
}