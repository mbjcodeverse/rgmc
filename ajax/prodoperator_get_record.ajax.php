<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxProdoperatorDetails{
    public $prodnumber;
    public $etype;
    public function ajaxGetProdoperatorDetails(){
      $prodnumber = $this->prodnumber;
      $etype = $this->etype;
      $answer = (new ControllerProdfin)->ctrShowProdoperator($prodnumber,$etype);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["prodnumber"])){
  $getProdoperator = new AjaxProdoperatorDetails();
  $getProdoperator -> prodnumber = $_POST["prodnumber"];
  $getProdoperator -> etype = $_POST["etype"];
  $getProdoperator -> ajaxGetProdoperatorDetails();
}