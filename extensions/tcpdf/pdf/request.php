<?php

require_once "../../../controllers/request.controller.php";
require_once "../../../models/request.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

require_once "../../../controllers/rawmaterials.controller.php";
require_once "../../../models/rawmaterials.model.php";

class printRequest{

public $reqnumber;
public function getRequestPrinting(){
  $itemRequest = "reqnumber";
  $valueRequest = $this->reqnumber;
  $request = (new ControllerRequest)->ctrShowRequest($itemRequest, $valueRequest);

  $id = $request['id'];
  $reqnumber = $request['reqnumber'];
  $reqdate = $request['reqdate'];
  $rstatus = $request['rstatus'];
  $idRequestor = $request['idRequestor'];
  $idPrepared = $request['idPrepared'];
  $remarks = $request['remarks'];
  $idMachine = $request['idMachine']; 
  $pshift = $request['pshift'];
  $mris = $request['mris'];
  $materials = json_decode($request['materials'], true);

  $itemRequestor = "id";
  $valueRequestor = $request["idRequestor"];
  $answerRequestor = (new ControllerEmployees)->ctrShowEmployees($itemRequestor, $valueRequestor);

  $itemPrepared = "id";
  $valuePrepared = $request["idPrepared"];
  $answerPrepared = (new ControllerEmployees)->ctrShowEmployees($itemPrepared, $valuePrepared);  

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->AddPage();

  $block1 = <<<EOF

	<table>
		
		<tr>
			
			<td style="width:150px"><img src="images/logo-negro-bloque.png"></td>

			<td style="background-color:white; width:140px">
				
				<div style="font-size:8.5px; text-align:right; line-height:15px;">
					
					<br>
					NIT: 71.759.963-9

					<br>
					ADDRESS: Calle 44B 92-11

				</div>

			</td>

			<td style="background-color:white; width:140px">

				<div style="font-size:8.5px; text-align:right; line-height:15px;">
					
					<br>
					CELLPHONE: 300 786 52 49
					
					<br>
					sales@inventorysystem.com

				</div>
				
			</td>

			<td style="background-color:white; width:110px; text-align:center; color:red"><br><br>BILL N.<br>$valueRequest</td>

		</tr>

	</table>

EOF;


$pdf->writeHTML($block1, false, false, false, false, '');
$pdf->Output('request.pdf', 'I');

}

}

$requestForm = new printRequest();
$requestForm -> reqnumber = $_GET["reqnumber"];
$requestForm -> getRequestPrinting();
?>