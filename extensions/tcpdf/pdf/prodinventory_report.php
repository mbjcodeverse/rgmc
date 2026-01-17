<?php
session_start();

require_once "../../../controllers/prodstocks.controller.php";
require_once "../../../models/prodstocks.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printProdInventory{
 public $asof_date;
 public $arrange_by;

public function getProdInventoryPrinting(){
  $id = "id";
  $empid = $_SESSION["idEmployee"];
  $employee = (new ControllerEmployees)->ctrShowEmployees($id, $empid);
  $generated_by = $employee['fname'].' '.$employee['lname']; 

  $asof_date = $this->asof_date; 
  $arrange_by = $this->arrange_by;
  $products = (new ControllerProdStocks)->ctrShowProdStocksAsofDate($asof_date,$arrange_by);
  $nRec = count($products);

  // Date Label
  $products_label_date = 'As of: '.substr($asof_date,5,2)."/".substr($asof_date,8,2)."/".substr($asof_date,0,4);

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
        <td style="width:570px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
      </tr>

      <tr>
        <td style="width:570px;text-align:center;font-size:10px;">Purok Paho, Brgy. Felisa, Bacolod City</td> 
      </tr>  

      <tr>
        <td style="width:570px;text-align:center;font-size:1.2em;font-weight:bold;">PRODUCTS INVENTORY DETAILS</td> 
      </tr>   

      <tr>
        <td></td>
      </tr>

      <tr>
        <td style="width:325px;"></td>
        <td style="width:210px;text-align:right;font-size:11px;">$products_label_date</td>
      </tr>

      <tr>
        <td style="width:5px;"></td>

        <td style="border: 1px solid #666;width:102px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Category</td>
                                                            
        <td style="border: 1px solid #680;width:312px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp; Description </td>

        <td style="border: 1px solid #680;width:70px;text-align:right;font-size:11px;">In-Stock &nbsp;&nbsp;&nbsp;</td>     

        <td style="border: 1px solid #666;width:45px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;Meas</td>                  
      </tr>                   
    </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $prev_id = 0;
  $curr_id = 0;
  $ctr = 0;
  $onhand = 0;
  $isInventory = 0;

  foreach ($products as $key => $value) {
    $id = $value["id"];
    $tdate = $value["tdate"];
    $details = $value["details"];
    $qty = $value["qty"];
    $priority = $value["priority"];
    $eqnum1 = $value["eqnum1"];  

    if ($qty == 0.00){
      $qty = 0;
    }

    $ctr = $ctr + 1;
    if ($ctr == 1){
      $prev_id = $value["id"];              
    }

    $curr_id = $id;                         

    if ($prev_id == $curr_id){   
      $cdesc = $value["cdesc"];
      $pdesc = $value["pdesc"];   
      $meas_desc = $value["whole_meas"];            
      if ($details == "Inventory"){
        $isInventory = 1;
      }

      if ($isInventory == 1){           
        switch ($details) {
          case "Inventory":
            $onhand = $qty;               
            break;
          case "Production":
            $onhand = $onhand + $qty;
            break;
          case "Repacking":
            $onhand = $onhand + $qty;
            break;
          default: 
            $onhand = $onhand - $qty;
        }
      }
    }else{
      // $in_stock = number_format(,2);
      $content = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:5px;"></td>

          <td style="width:102px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cdesc</td>
                                                              
          <td style="width:312px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp; $pdesc</td>

          <td style="width:70px;text-align:right;font-size:11px;">$onhand &nbsp;&nbsp;&nbsp;</td>  

          <td style="width:45px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$meas_desc</td>           
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');

      $prev_id = $curr_id;
      $cdesc = $value["cdesc"];
      $pdesc = $value["pdesc"];
      $meas_desc = $value["whole_meas"]; 
      $onhand = 0;
      $isInventory = 0;
      if ($details == "Inventory"){
        $isInventory = 1;
      }

      if ($isInventory == 1){
        switch ($details) {
          case "Inventory":
            $onhand = $qty;               
            break;
          case "Production":
            $onhand = $onhand + $qty;
            break;
          case "Repacking":
            $onhand = $onhand + $qty;
            break;
          default: 
            $onhand = $onhand - $qty;
        }
      }
    }  /*else*/  
  }  /*for*/   

  // ------------------------------------------------------------------------------
  // After the FOR loop in products - insert last product in the table
  // Removing this, last product is not shown in printout   

        $content = <<<EOF
      <table style="border: none;">    
        <tr>
          <td style="width:5px;"></td>

          <td style="width:102px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$cdesc</td>
                                                              
          <td style="width:312px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp; $pdesc</td>

          <td style="width:70px;text-align:right;font-size:11px;">$onhand &nbsp;&nbsp;&nbsp;</td>  

          <td style="width:45px;text-align:left;font-size:11px;">&nbsp;&nbsp;&nbsp;$meas_desc</td>           
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');

 //--------------------------------------------------------------------------------     

  $footer = <<<EOF
    <table style="border: none;">    
      <tr>
        <td></td>
      </tr>
      <tr>  
        <td style="width:532px;text-align:right;font-size:8px;">Generated by</td>
      </tr> 
      <tr>  
        <td style="width:532px;text-align:right;font-size:10px;">$generated_by</td>
      </tr>                              
    </table>
EOF;
      $pdf->writeHTML($footer, false, false, false, false, '');

    $pdf->Output('prodinventory_report.pdf', 'I');
   }
  }  

  $productsForm = new printProdInventory();
  $productsForm -> asof_date = $_GET["asof"];
  $productsForm -> arrange_by = $_GET["arrange_by"];
  $productsForm -> getProdInventoryPrinting();
?>