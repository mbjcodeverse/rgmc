<?php
require_once "../controllers/rawmats.controller.php";
require_once "../models/rawmats.model.php";

class AjaxItemDetails{
    public $itemid;
    public function ajaxGetItemDetails(){
      $itemid = $this->itemid;
      $answer = (new ControllerRawMats)->ctrShowTransactionRawMat($itemid);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["itemid"])){
  $getItem = new AjaxItemDetails();
  $getItem -> itemid = $_POST["itemid"];
  $getItem -> ajaxGetItemDetails();
}