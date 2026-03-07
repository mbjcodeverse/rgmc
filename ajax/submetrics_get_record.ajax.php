<?php
require_once "../controllers/submetrics.controller.php";
require_once "../models/submetrics.model.php";

class AjaxSubmetricsDetails{
    public $submetid;
    public function ajaxGetSubmetricsDetails(){
      $submetid = $this->submetid;
      $answer = (new ControllerSubmetrics)->ctrShowSubmetrics($submetid);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["submetid"])){
  $getSubmetrics = new AjaxSubmetricsDetails();
  $getSubmetrics -> submetid = $_POST["submetid"];
  $getSubmetrics -> ajaxGetSubmetricsDetails();
}