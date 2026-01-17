<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once "../../../controllers/factorydashboard.controller.php";
require_once "../../../models/factorydashboard.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printTabularData{
public $reptype;
public $start_date;
public $end_date;
public $generatedby;

public function getTabularData(){
  $reptype = $this->reptype;
  $start_date = $this->start_date;
  $end_date = $this->end_date;
  if ($start_date == $end_date){
      $trans_date = 'Date: '.substr($start_date,5,2)."/".substr($start_date,8,2)."/".substr($start_date,0,4);
  }else{
      $trans_date = 'From '.substr($start_date,5,2)."/".substr($start_date,8,2)."/".substr($start_date,0,4).' To '.substr($end_date,5,2)."/".substr($end_date,8,2)."/".substr($end_date,0,4);
  }
  $generatedby = $this->generatedby;

  $report_content = (new ControllerFactoryDashboard)->ctrShowFactoryDashboard($reptype, $start_date, $end_date);

  $empid = "empid";
  $generatedby = $this->generatedby;
  $generated_by = (new ControllerEmployees)->ctrShowEmployees($empid, $generatedby);
  if ($generated_by['mi']=='')
    $printed_by = $generated_by['fname'].' '.$generated_by['lname'];
  else  
    $printed_by = $generated_by['fname'].' '.$generated_by['mi'].'. '.$generated_by['lname'];
  
  // Date printed  
  $current_date = date("m/d/Y");
  $nRec = count($report_content);

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  
    $pdf->AddPage('L', 'LETTER'); 
    $header = <<<EOF
    <table>
        <tr>
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</td> 
        </tr>

        <tr>
          <td style="width:730px;text-align:center;font-size:1.2em;font-weight:bold;">COSTING COMPARATIVE REPORT</td> 
        </tr>

        <tr>
          <td style="width:730px;text-align:center;font-size:10px;">$trans_date</td> 
        </tr>   

        <tr>
          <td></td>
        </tr>    

        <tr>       
            <td style="width:62px;"></td> 
            <td style="background-color: #e8fac3;border: 1px solid black;width:75px;text-align:left;font-size:9px;">&nbsp; Date Series</td>  
            <td style="background-color: #f0f2f2;border: 1px solid black;width:90px;text-align:right;font-size:9px;">Production Value &nbsp;&nbsp;</td>
            <td style="background-color: #fffce6;border: 1px solid black;width:90px;text-align:right;font-size:9px;">Materials Cost &nbsp;&nbsp;</td>
            <td style="background-color: #fffce6;border: 1px solid black;width:60px;text-align:right;font-size:9px;">RM% &nbsp;&nbsp;</td>             
            <td style="background-color: #fff0fe;border: 1px solid black;width:90px;text-align:right;font-size:9px;">Manpower Cost &nbsp;&nbsp;</td>
            <td style="background-color: #fff0fe;border: 1px solid black;width:60px;text-align:right;font-size:9px;">MC% &nbsp;&nbsp;</td>             
            <td style="background-color: #f2fffe;border: 1px solid black;width:90px;text-align:right;font-size:9px;">Electricity Cost &nbsp;&nbsp;</td>
            <td style="background-color: #f2fffe;border: 1px solid black;width:60px;text-align:right;font-size:9px;">EC% &nbsp;&nbsp;</td>       
        </tr>                          
    </table>
    EOF;
        $pdf->writeHTML($header, false, false, false, false, '');

    $i = 0;
    $total_production_cost = 0.00;
    $total_materials_cost = 0.00;
    $total_manpower_cost = 0.00;
    $total_electricity_cost = 0.00;

    $total_rm_percent = 0.00;
    $total_mp_percent = 0.00;
    $total_ec_percent = 0.00;

    $r = 0;
    $e = 0;
    $m = 0;

    // Averages ----------------
    $ave_prod = 0.00;
    $ave_rawmats = 0.00;
    $ave_manpower = 0.00;
    $ave_electricity = 0.00;

    $ave_p = 0;
    $ave_r = 0;
    $ave_m = 0;
    $ave_e = 0;    
    foreach ($report_content as $key => $value){
      $dashboarddate = substr($value["date_label"],5,2)."/".substr($value["date_label"],8,2)."/".substr($value["date_label"],0,4);
      
      $production_cost = $value["production_cost"];
      $raw_materials_cost = $value["raw_materials_cost"];
      $manpower_cost = $value["manpower_cost"];
      $electricity_cost = $value["electricity_cost"];

      $actual_materials_cost = $raw_materials_cost - $value["excess_materials_cost"];

      if ($production_cost != 0) {
          $rm_percent = $actual_materials_cost / $production_cost * 100;
          $mp_percent = $manpower_cost / $production_cost * 100;
          $ec_percent = $electricity_cost / $production_cost * 100;
      }else{
          $rm_percent = 0.00;
          $mp_percent = 0.00;
          $ec_percent = 0.00;
      }

      // Average Costing
      if ($production_cost > 0.00){
          $ave_p += 1;
          $ave_prod += $production_cost;
      }

      if ($raw_materials_cost > 0.00){
          $ave_r += 1;
          $ave_rawmats += $actual_materials_cost;
      }

      if ($manpower_cost > 0.00){
          $ave_m += 1;
          $ave_manpower += $manpower_cost;
      }

      if ($electricity_cost > 0.00){
          $ave_e += 1;
          $ave_electricity += $electricity_cost;
      }

      // Divisor of % Average
      if ($rm_percent && $rm_percent > 0.00) {
          $r += 1;         // raw mats divisor
      }

      if ($mp_percent && $mp_percent > 0.00) {
          $m += 1;         // manpower divisor
      }

      if ($ec_percent && $ec_percent > 0.00) {
          $e += 1;         // electricity divisor
      }

      $total_production_cost += $production_cost;
      $total_materials_cost += $actual_materials_cost;
      $total_manpower_cost += $manpower_cost;
      $total_electricity_cost += $electricity_cost;
      
      // -----------------------------------------------------------------
      $production_cost = number_format($production_cost,2);
      $actual_materials_cost = number_format($actual_materials_cost,2);

      if ($rm_percent && $rm_percent > 0.00) {
          $rm_percent = number_format($rm_percent,2) . '%';
      }else{
          $rm_percent = '-NaN-';
      }

      $manpower_cost = number_format($manpower_cost,2);
      if ($mp_percent && $mp_percent > 0.00) {
          $mp_percent = number_format($mp_percent,2) . '%';
      }else{
          $mp_percent = '-NaN-';
      }

      $electricity_cost = number_format($electricity_cost,2);
      if ($ec_percent && $ec_percent > 0.00) {
          $ec_percent = number_format($ec_percent,2) . '%';
      }else{
          $ec_percent = '-NaN-';
      }      

      $content = <<<EOF
          <table style="border: none;">    
              <tr>
                  <td style="width:62px;"></td> 
                  <td style="border: 1px solid black;width:75px;text-align:left;font-size:9px;">&nbsp; $dashboarddate</td>  
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:9px;">$production_cost</td>
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:9px;">$actual_materials_cost</td>
                  <td style="border: 1px solid black;width:60px;text-align:right;font-size:9px;">$rm_percent</td>             
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:9px;">$manpower_cost</td>
                  <td style="border: 1px solid black;width:60px;text-align:right;font-size:9px;">$mp_percent</td>             
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:9px;">$electricity_cost</td>
                  <td style="border: 1px solid black;width:60px;text-align:right;font-size:9px;">$ec_percent</td>       
              </tr>  
          </table>
      EOF;
          $pdf->writeHTML($content, false, false, false, false, '');        

      $i = $i + 1;
    } // end of FOR statement

    if ($total_production_cost > 0.00 && $total_materials_cost > 0.00) {
        $total_rm_percent = ($total_materials_cost / $total_production_cost) * 100; // Percentage, not just a fraction
    } else {
        $total_rm_percent = 0.00; // Return 0% when there is no valid production or materials cost
    }

    if ($total_production_cost > 0.00 && $total_manpower_cost > 0.00) {
        $total_mp_percent = ($total_manpower_cost / $total_production_cost) * 100; // Percentage
    } else {
        $total_mp_percent = 0.00;
    }

    if ($total_production_cost > 0.00 && $total_electricity_cost > 0.00) {
        $total_ec_percent = ($total_electricity_cost / $total_production_cost) * 100; // Percentage
    } else {
        $total_ec_percent = 0.00;
    }

    $total_production_cost = number_format($total_production_cost,2);
    $total_materials_cost = number_format($total_materials_cost,2);
    $total_rm_percent = number_format($total_rm_percent,2) . '%';
    $total_manpower_cost = number_format($total_manpower_cost,2);
    $total_mp_percent = number_format($total_mp_percent,2) . '%';
    $total_electricity_cost = number_format($total_electricity_cost,2);
    $total_ec_percent = number_format($total_ec_percent,2) . '%';
    $totals = <<<EOF
          <table style="border: none;">    
              <tr>
                  <td style="width:62px;"></td> 
                  <td style="border: 1px solid black;width:75px;text-align:right;font-size:10px;font-weight:bold;">&nbsp;SUB-TOTAL</td>  
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;font-weight:bold;">$total_production_cost</td>
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;font-weight:bold;">$total_materials_cost</td>
                  <td style="border: 1px solid black;width:60px;text-align:right;font-size:10px;font-weight:bold;">$total_rm_percent</td>             
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;font-weight:bold;">$total_manpower_cost</td>
                  <td style="border: 1px solid black;width:60px;text-align:right;font-size:10px;font-weight:bold;">$total_mp_percent</td>             
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;font-weight:bold;">$total_electricity_cost</td>
                  <td style="border: 1px solid black;width:60px;text-align:right;font-size:10px;font-weight:bold;">$total_ec_percent</td>       
              </tr>  
          </table>
      EOF;
          $pdf->writeHTML($totals, false, false, false, false, '');            

    $ave_prod = $ave_prod / $ave_p;

    if ($ave_prod > 0.00){
        $aveProd = number_format($ave_prod,2);
    }else{
        $aveProd = 0.00;
    }

    if ($ave_rawmats > 0.00 && $ave_r > 0){
        $aveRawmats = number_format($ave_rawmats / $ave_r,2);
    }else{
        $aveRawmats = 0.00;
    }

    if (($ave_rawmats / $ave_r) > 0.00 && $ave_prod > 0.00){
        $m_percentage = number_format((($ave_rawmats / $ave_r) / $ave_prod) * 100,2) . '%';
    }else{
        $m_percentage = 0.00;
    }    

    if ($ave_manpower > 0.00 && $ave_m > 0){
        $aveManpower = number_format($ave_manpower / $ave_m,2);
    }else{
        $aveManpower = 0.00;
    }

    if (($ave_manpower / $ave_m) > 0.00 && $ave_prod > 0.00){
        $p_percentage = number_format((($ave_manpower / $ave_m) / $ave_prod) * 100,2) . '%';
    }else{
        $p_percentage = 0.00;
    }

    if ($ave_electricity > 0.00 && $ave_e > 0){
        $aveElectricity = number_format($ave_electricity / $ave_e,2);
    }else{
        $aveElectricity = 0.00;
    }

    if (($ave_electricity / $ave_e) > 0.00 && $ave_prod > 0.00){
        $e_percentage = number_format((($ave_electricity / $ave_e) / $ave_prod) * 100,2) . '%';
    }else{
        $e_percentage = 0.00;
    }  
    
    $average = <<<EOF
          <table style="border: none;">    
              <tr>
                  <td style="width:62px;"></td> 
                  <td style="border: 1px solid black;width:75px;text-align:right;font-size:10px;font-weight:bold;">&nbsp; AVERAGE</td>  
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;font-weight:bold;">$aveProd</td>
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;font-weight:bold;">$aveRawmats</td>
                  <td style="border: 1px solid black;width:60px;text-align:right;font-size:10px;font-weight:bold;">$m_percentage</td>             
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;font-weight:bold;">$aveManpower</td>
                  <td style="border: 1px solid black;width:60px;text-align:right;font-size:10px;font-weight:bold;">$p_percentage</td>             
                  <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;font-weight:bold;">$aveElectricity</td>
                  <td style="border: 1px solid black;width:60px;text-align:right;font-size:10px;font-weight:bold;">$e_percentage</td>       
              </tr>  
          </table>
      EOF;
          $pdf->writeHTML($average, false, false, false, false, '');      

    if ($i > 0){
        $footer = <<<EOF
        <table style="border: none;"> 
          <tr>  
            <td style="width:80px;"></td>
          </tr>
    
          <tr>  
            <td style="width:350px;"></td>
            <td style="width:220px;font-size:9px;">Date: $current_date</td>
            <td style="width:155px;font-size:10px;">Generated by:</td>
          </tr> 
          
          <tr>  
            <td style="width:350px;"></td>
            <td style="width:224px;"></td>
            <td style="width:95px;border-bottom: 1px solid black;"></td>
          </tr>      
        
          <tr>
            <td style="width:350px;"></td>  
            <td style="width:222px;"></td>
            <td style="width:155px;text-align:left;font-size:10px;">$printed_by</td>
          </tr>      
        </table>
    EOF;
          $pdf->writeHTML($footer, false, false, false, false, '');                    
    }
  // ----------------------------------------------------------------------------------------------------------------
  

    $pdf->Output('tabularprint.pdf', 'I');
   }  // end of getTabularData
  }   // end of class

  $tabular_data = new printTabularData();
  $tabular_data -> reptype = $_GET["reptype"];
  $tabular_data -> start_date = $_GET["start_date"];
  $tabular_data -> end_date = $_GET["end_date"];
  $tabular_data -> generatedby = $_GET["generatedby"]; 
  $tabular_data -> getTabularData();
