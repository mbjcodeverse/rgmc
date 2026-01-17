<?php
session_start();

require_once "../../../controllers/rawstocks.controller.php";
require_once "../../../models/rawstocks.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printRawInventory{
 public $asof_date;

public function getRawInventoryPrinting(){
  $id = "id";
  $empid = $_SESSION["idEmployee"];
  $employee = (new ControllerEmployees)->ctrShowEmployees($id, $empid);
  $generated_by = $employee['fname'].' '.$employee['lname']; 

  $asof_date = $this->asof_date; 
  $rawmats = (new ControllerRawStocks)->ctrShowRawStocksAsofDate($asof_date);
  $nRec = count($rawmats);

  // Date Label
  $rawmats_label_date = 'As of: '.substr($asof_date,5,2)."/".substr($asof_date,8,2)."/".substr($asof_date,0,4);

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  // $pdf->SetLeftMargin(20);
  // $pdf->AddPage();

  // $pdf->AddPage('L', 'LEGAL');

// ================================================================
//                      Overall Sales Category
// ================================================================ 

    $pdf->AddPage();   /*short-size portrait*/
    $header = <<<EOF
    <table>
      <tr>
        <td style="width:560px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
      </tr>

      <tr>
        <td style="width:560px;text-align:center;font-size:10px;">Purok Paho, Brgy. Felisa, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:560px;text-align:center;font-size:1.2em;font-weight:bold;">RAW MATERIALS INVENTORY DETAILS</td> 
      </tr>   

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:325px;"></td>
        <td style="width:210px;text-align:right;font-size:11px;">$rawmats_label_date</td>
      </tr>

      <tr>
        <td style="width:22px;"></td>

        <td style="border: 1px solid #666;width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Category</td>
                                                            
        <td style="border: 1px solid #680;width:305px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp; Description </td>

        <td style="border: 1px solid #680;width:85px;text-align:right;font-size:11px;">In-Stock &nbsp;&nbsp;&nbsp;</td>     

        <td style="border: 1px solid #666;width:45px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Meas</td>                  
      </tr>                   
    </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $prev_id = 0;
  $curr_id = 0;
  $ctr = 0;
  $onhand = 0.00;
  $isInventory = 0;

  foreach ($rawmats as $key => $value) {
    $id = $value["id"];
    $tdate = $value["tdate"];
    $details = $value["details"];
    // $cdesc = $value["cdesc"];
    // $mdesc = $value["mdesc"];
    $qty = $value["qty"];
    $priority = $value["priority"];
    $eqnum1 = $value["eqnum1"];

    if ($qty == 0.00){
      $qty = 0;
    }  
      
    if (($eqnum1 > 0.00) && ($qty > 0.00)){
      $whole = round($qty / $eqnum1,2);
    }else{
      $whole = $qty;
    }  

    $ctr = $ctr + 1;
    if ($ctr == 1){
      $prev_id = $value["id"];              
    }

    $curr_id = $id;                         

    if ($prev_id == $curr_id){   
      $cdesc = $value["cdesc"];
      $mdesc = $value["mdesc"];   
      $meas_desc = $value["whole_meas"];            
      if ($details == "Inventory"){
        $isInventory = 1;
      }

      if ($isInventory == 1){           
        switch ($details) {
          case "Inventory":
            $onhand = $whole;               
            break;
          case "Purchase":
            $onhand = $onhand + $whole;
            break;
          case "Recycle":
            $onhand = $onhand + $whole;
            break;
          case "Stock Return":
            $onhand = $onhand + $whole;
            break;            
          default: 
            $onhand = $onhand - $whole;
        }
      }
    }else{
      // $in_stock = number_format(,2);
      $content = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:22px;"></td>

          <td style="width:75px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cdesc</td>
                                                              
          <td style="width:305px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp; $mdesc</td>

          <td style="width:85px;text-align:right;font-size:11px;">$onhand &nbsp;&nbsp;&nbsp;</td>  

          <td style="width:45px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$meas_desc</td>           
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');

      $prev_id = $curr_id;
      $cdesc = $value["cdesc"];
      $mdesc = $value["mdesc"];
      $meas_desc = $value["whole_meas"]; 
      $onhand = 0;
      $isInventory = 0;
      if ($details == "Inventory"){
        $isInventory = 1;
      }

      if ($isInventory == 1){
        switch ($details) {
          case "Inventory":
            $onhand = $whole;
            break;
          case "Purchase":
            $onhand = $onhand + $whole;
            break;
          case "Recycle":
            $onhand = $onhand + $whole;
            break;
          case "Stock Return":
            $onhand = $onhand + $whole;
            break;            
          default:  
            $onhand = $onhand - $whole;
        }
      }
    }  /*else*/  
  }  /*for*/      

  $footer = <<<EOF
    <table style="border: none;">    
      <tr>
        <td></td>
      </tr>
      <tr>  
        <td style="width:522px;text-align:right;font-size:8px;">Generated by</td>
      </tr> 
      <tr>  
        <td style="width:522px;text-align:right;font-size:10px;">$generated_by</td>
      </tr>                              
    </table>
EOF;
      $pdf->writeHTML($footer, false, false, false, false, '');

    $pdf->Output('rawmaterials_report.pdf', 'I');
   }
  }  

  $rawMaterialsForm = new printRawInventory();
  $rawMaterialsForm -> asof_date = $_GET["asof"];
  $rawMaterialsForm -> getRawInventoryPrinting();
?>