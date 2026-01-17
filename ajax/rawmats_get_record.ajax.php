<?php
require_once "../controllers/rawmats.controller.php";
require_once "../models/rawmats.model.php";

class AjaxRawMats{
    public $itemid;
    public function ajaxGetRawMats(){
      $item = "itemid";
      $value = $this->itemid;
      $answer = (new ControllerRawMats)->ctrShowRawMat($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["itemid"])){
  $getRawMats = new AjaxRawMats();
  $getRawMats -> itemid = $_POST["itemid"];
  $getRawMats -> ajaxGetRawMats();
}