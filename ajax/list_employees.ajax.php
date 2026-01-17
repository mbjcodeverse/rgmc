<?php
require_once "../controllers/employees.controller.php";
require_once "../models/employees.model.php";

class employeeList{
	public function showEmployeeList(){
		$employee = (new ControllerEmployees)->ctrShowEmployeesPosition();
		if(count($employee) == 0){
			$jsonData = '{"data":[]}';
			echo $jsonData;
			return;
		}
		$jsonData = '{
			"data":[';
				for($i=0; $i < count($employee); $i++){
				  	$jsonData .='[
						"'.$employee[$i]["lname"].'",
						"'.$employee[$i]["fname"].'",
						"'.$employee[$i]["mi"].'",
						"'.$employee[$i]["positiondesc"].'"
					],';

		  			// $buttons =  "<div class='btn-group'><button class='btn btn-light btn-sm waves-effect waves-light m-1 addProductSale recoverButton' idProduct='".$employee[$i]["id"]."'><i class='fa fa-plus'></i></button></div>";

					// $jsonData .='[
					// 	"'.$employee[$i]["pdesc"].'",
					// 	"'.$employee[$i]["uprice"].'",
					// 	"'.$buttons.'"
					// ],';
				}
				$jsonData = substr($jsonData, 0, -1);
				$jsonData .= '] 
			}';
		echo $jsonData;
	}
}

$activateProductsSales = new employeeList();
$activateProductsSales -> showEmployeeList();