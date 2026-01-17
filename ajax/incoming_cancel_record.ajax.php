<?php
require_once "../controllers/incoming.controller.php";
require_once "../models/incoming.model.php";

class AjaxCancelIncoming{
    public $ponumber;
    public $delnumber;
    public function ajaxCancelIncoming(){
      $ponumber = $this->ponumber;
      $delnumber = $this->delnumber;
      $answer = (new ControllerIncoming)->ctrCancelIncoming($ponumber, $delnumber);
      echo json_encode($answer);
    }
}
 
// if(isset($_POST["delnumber"])){
  $cancelIncoming = new AjaxCancelIncoming();
  $cancelIncoming -> ponumber = $_POST["ponumber"];
  $cancelIncoming -> delnumber = $_POST["delnumber"];
  $cancelIncoming -> ajaxCancelIncoming();
// }