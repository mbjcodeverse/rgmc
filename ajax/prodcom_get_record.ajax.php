<?php
require_once "../controllers/prodcom.controller.php";
require_once "../models/prodcom.model.php";

class AjaxProdcomDetails{
    public $prodnumber;
    public function ajaxGetProdcomDetails(){
      $prodnumber = $this->prodnumber;
      $answer = (new ControllerProdcom)->ctrShowProdcom($prodnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["prodnumber"])){
  $getProdcom = new AjaxProdcomDetails();
  $getProdcom -> prodnumber = $_POST["prodnumber"];
  $getProdcom -> ajaxGetProdcomDetails();
}