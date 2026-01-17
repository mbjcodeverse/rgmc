<?php
require_once "../controllers/supplier.controller.php";
require_once "../models/supplier.model.php";

class AjaxSupplier{
    public $idSupplier;
    public function ajaxGetSupplier(){
      $item = "id";
      $value = $this->idSupplier;
      $answer = (new ControllerSupplier)->ctrShowSupplier($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idSupplier"])){
  $getSupplier = new AjaxSupplier();
  $getSupplier -> idSupplier = $_POST["idSupplier"];
  $getSupplier -> ajaxGetSupplier();
}