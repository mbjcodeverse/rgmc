<?php
require_once "../controllers/inventoryrawmats.controller.php";
require_once "../models/inventoryrawmats.model.php";

class AjaxInventoryRawmatsDetails{
    public $invnumber;
    public function ajaxGetInventoryRawmatsDetails(){
      $invnumber = $this->invnumber;
      $answer = (new ControllerInventoryRawmats)->ctrShowInventoryRawmats($invnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["invnumber"])){
  $getInventoryRawmats = new AjaxInventoryRawmatsDetails();
  $getInventoryRawmats -> invnumber = $_POST["invnumber"];
  $getInventoryRawmats -> ajaxGetInventoryRawmatsDetails();
}