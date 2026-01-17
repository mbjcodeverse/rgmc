<?php
// require_once "../controllers/incoming.controller.php";
// require_once "../models/incoming.model.php";

// class AjaxIncomingTransactionList{ 
//    public $machineid;
//    public $start_date;
//    public $end_date;   
//    public $suppliercode;
//    public $delstatus;

//    public function ajaxDisplayIncomingTransactionList(){
//      $machineid = $this->machineid;
//      $start_date = $this->start_date;
//      $end_date = $this->end_date;
//      $suppliercode = $this->suppliercode;
//      $delstatus = $this->delstatus;

//      $incoming = (new ControllerIncoming)->ctrShowIncomingTransactionList($machineid, $start_date, $end_date, $suppliercode, $delstatus);

//       if(count($incoming) == 0){
//         $jsonData = '{"data":[]}';
//         echo $jsonData;
//         return;
//       }
//       $jsonData = '{
//         "data":[';
//           for($i=0; $i < count($incoming); $i++){
//               $jsonData .='[
//               "'.$incoming[$i]["name"].'",
//               "'.$incoming[$i]["delnumber"].'",
//               "'.$incoming[$i]["bname"].'",
//               "'.$incoming[$i]["delstatus"].'",
//               "'.$incoming[$i]["netamount"].'",
//               "'.$incoming[$i]["delstatus"].'"
//             ],';
//           }
//           $jsonData = substr($jsonData, 0, -1);
//           $jsonData .= '] 
//         }';
//       echo $jsonData;
//    }
// }

// $incoming = new AjaxIncomingTransactionList();
// $incoming -> machineid = $_POST["machineid"];
// $incoming -> start_date = $_POST["start_date"];
// $incoming -> end_date = $_POST["end_date"];
// $incoming -> suppliercode = $_POST["suppliercode"];
// $incoming -> delstatus = $_POST["delstatus"];
// $incoming -> ajaxDisplayIncomingTransactionList();



require_once "../controllers/incoming.controller.php";
require_once "../models/incoming.model.php";

class AjaxIncomingTransactionList{ 
   public $machineid;
   public $start_date;
   public $end_date;   
   public $suppliercode;
   public $delstatus;

   public function ajaxDisplayIncomingTransactionList(){
     $machineid = $this->machineid;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $suppliercode = $this->suppliercode;
     $delstatus = $this->delstatus;

     $answer = (new ControllerIncoming)->ctrShowIncomingTransactionList($machineid, $start_date, $end_date, $suppliercode, $delstatus);
     echo json_encode($answer);
   }
}

$incoming = new AjaxIncomingTransactionList();
$incoming -> machineid = $_POST["machineid"];
$incoming -> start_date = $_POST["start_date"];
$incoming -> end_date = $_POST["end_date"];
$incoming -> suppliercode = $_POST["suppliercode"];
$incoming -> delstatus = $_POST["delstatus"];
$incoming -> ajaxDisplayIncomingTransactionList();