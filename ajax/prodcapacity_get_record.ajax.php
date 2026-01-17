<?php
require_once "../controllers/prodfin.controller.php";
require_once "../models/prodfin.model.php";

class AjaxProdcapacityDetails{
    public $capacitynumber;
    public function ajaxGetProdcapacityDetails(){
      $capacitynumber = $this->capacitynumber;
      $answer = (new ControllerProdfin)->ctrShowProdcapacity($capacitynumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["capacitynumber"])){
  $getProdcapacity = new AjaxProdcapacityDetails();
  $getProdcapacity -> capacitynumber = $_POST["capacitynumber"];
  $getProdcapacity -> ajaxGetProdcapacityDetails();
}