<?php
require_once "../controllers/prodmetrics.controller.php";
require_once "../models/prodmetrics.model.php";

class AjaxProductmetricsDetails{
    public $prodmetid;
    public function ajaxGetProductmetricsDetails(){
      $prodmetid = $this->prodmetid;
      $answer = (new ControllerProductmetrics)->ctrShowProductmetrics($prodmetid);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["prodmetid"])){
  $getProductmetrics = new AjaxProductmetricsDetails();
  $getProductmetrics -> prodmetid = $_POST["prodmetid"];
  $getProductmetrics -> ajaxGetProductmetricsDetails();
}