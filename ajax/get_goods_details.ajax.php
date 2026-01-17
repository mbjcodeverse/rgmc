<?php
require_once "../controllers/goods.controller.php";
require_once "../models/goods.model.php";

class AjaxGoodDetails{
    public $itemid;
    public function ajaxGetGoodDetails(){
      $itemid = $this->itemid;
      $answer = (new ControllerGoods)->ctrShowTransactionGood($itemid);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["itemid"])){
  $getGood = new AjaxGoodDetails();
  $getGood -> itemid = $_POST["itemid"];
  $getGood -> ajaxGetGoodDetails();
}