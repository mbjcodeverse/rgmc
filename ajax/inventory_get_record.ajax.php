<?php
require_once "../controllers/inventory.controller.php";
require_once "../models/inventory.model.php";

class AjaxInventoryDetails{
    public $invnumber;
    public function ajaxGetInventoryDetails(){
      $invnumber = $this->invnumber;
      $answer = (new ControllerInventory)->ctrShowInventory($invnumber);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["invnumber"])){
  $getInventory = new AjaxInventoryDetails();
  $getInventory -> invnumber = $_POST["invnumber"];
  $getInventory -> ajaxGetInventoryDetails();
}