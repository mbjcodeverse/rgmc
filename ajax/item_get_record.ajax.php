<?php
require_once "../controllers/items.controller.php";
require_once "../models/items.model.php";

class AjaxItems{
    public $itemid;
    public function ajaxGetItems(){
      $item = "itemid";
      $value = $this->itemid;
      $answer = (new ControllerItems)->ctrShowItem($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["itemid"])){
  $getItems = new AjaxItems();
  $getItems -> itemid = $_POST["itemid"];
  $getItems -> ajaxGetItems();
}