<?php
require_once "../controllers/items.controller.php";
require_once "../models/items.model.php";

class AjaxItemDetails{
    public $itemid;
    public function ajaxGetItemDetails(){
      $itemid = $this->itemid;
      $answer = (new ControllerItems)->ctrShowTransactionItem($itemid);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["itemid"])){
  $getItem = new AjaxItemDetails();
  $getItem -> itemid = $_POST["itemid"];
  $getItem -> ajaxGetItemDetails();
}