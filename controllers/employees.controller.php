<?php
class ControllerEmployees{
	static public function ctrCreateEmployee(){
		if(isset($_POST["txt-lname"])&&isset($_POST["tns-fname"])&&($_POST["trans_type"] == 'New')){
			if (isset($_POST['chk-isactive'])){
			    $isactive=$_POST['chk-isactive'];
		    }else{
		    	$isactive="0";
		    }

		    if ($_POST['date-bday'] != ''){
		    	$bday = date('Y-m-d', strtotime($_POST["date-bday"]));
		    }else{
		        $bday = '0000-00-00';
		    }

		   	$data = array("empid"=>$_POST["txt-empid"],
				          "isactive"=>$isactive,
				          "lname"=>$_POST["txt-lname"],
				          "fname"=>$_POST["tns-fname"],
				          "mi"=>$_POST["txt-mi"],
				          "bday"=>$bday,
				          "gender"=>$_POST["sel-gender"],
				          "address"=>$_POST["tns-address"],
				          "mobile"=>$_POST["num-mobile"],
				          "idPos"=>$_POST["sel-position"],
				          "sssno"=>$_POST["num-sssno"],
				          "phino"=>$_POST["num-phino"],
				          "pagibig"=>$_POST["num-pagibig"],
				          "tin"=>$_POST["num-tin"],
				          "estatus"=>$_POST["sel-estatus"]);

		   	$answer = (new ModelEmployees)->mdlAddEmployee($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Employee profile has been successfully saved!",
		                type: "success",
		                showConfirmButton: true,
				        confirmButtonText: "Ok",
				        confirmButtonClass: "btn btn-light btn-lg",
				        allowOutsideClick: false
		                }).then(function(result){
								if (result.value) {
								  $("#btn-new").click();
				 				}
	                });
				</script>';
			}
		}
	}

	static public function ctrEditEmployee(){
		if(isset($_POST["txt-lname"])&&isset($_POST["tns-fname"])&&($_POST["trans_type"] == 'Update')){
			if (isset($_POST['chk-isactive'])){
			    $isactive='1';
		    }else{
		    	$isactive='0';
		    }

		    if ($_POST['date-bday'] != ''){
		    	$bday = date('Y-m-d', strtotime($_POST["date-bday"]));
		    }else{
		        $bday = '0000-00-00';
		    }

		   	$data = array("id"=>$_POST["num-id"],
		   				  "empid"=>$_POST["txt-empid"],
				          "isactive"=>$isactive,
				          "lname"=>$_POST["txt-lname"],
				          "fname"=>$_POST["tns-fname"],
				          "mi"=>$_POST["txt-mi"],
				          "bday"=>$bday,
				          "gender"=>$_POST["sel-gender"],
				          "address"=>$_POST["tns-address"],
				          "mobile"=>$_POST["num-mobile"],
				          "idPos"=>$_POST["sel-position"],
				          "sssno"=>$_POST["num-sssno"],
				          "phino"=>$_POST["num-phino"],
				          "pagibig"=>$_POST["num-pagibig"],
				          "tin"=>$_POST["num-tin"],
				          "estatus"=>$_POST["sel-estatus"]);

		   	$answer = (new ModelEmployees)->mdlEditEmployee($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Employee profile has been successfully updated!",
		                type: "success",
		                showConfirmButton: true,
				        confirmButtonText: "Ok",
				        confirmButtonClass: "btn btn-light btn-lg",
				        allowOutsideClick: false
		                }).then(function(result){
								if (result.value) {
								  $("#btn-new").click();
				 				}
	                });
				</script>';
			}
		}
	}	

	static public function ctrShowEmployees($item, $value){
		$answer = (new ModelEmployees)->mdlShowEmployees($item, $value);
		return $answer;
	}

	static public function ctrShowEmployeeName($item, $value){
		$answer = (new ModelEmployees)->mdlShowEmployeeName($item, $value);
		return $answer;
	}	

	static public function ctrShowEmployeesPosition(){
		$answer = (new ModelEmployees)->mdlShowEmployeesPosition();
		return $answer;
	}	

	static public function ctrShowStatus(){
		$answer = (new ModelEmployees)->mdlShowStatus();
		return $answer;
	}

	static public function ctrShowGender(){
		$answer = (new ModelEmployees)->mdlShowGender();
		return $answer;
	}	

	static public function ctrShowPosition(){
		$answer = (new ModelEmployees)->mdlShowPosition();
		return $answer;
	}	

	// static public function ctrShowDriverEmployees($item, $value){
	// 	$table = "employees";
	// 	$answer = (new ModelEmployees)->mdlShowDriverEmployees($table, $item, $value);
	// 	return $answer;
	// }


	// Purchases
	static public function ctrOrderedBy(){
		$answer = (new ModelEmployees)->mdlOrderedBy();
		return $answer;
	}	

	static public function ctrReporter(){
		$answer = (new ModelEmployees)->mdlReporter();
		return $answer;
	}

	static public function ctrPurchaser(){
		$answer = (new ModelEmployees)->mdlPurchaser();
		return $answer;
	}

	// Releases
	static public function ctrReleaser(){
		$answer = (new ModelEmployees)->mdlReleaser();
		return $answer;
	}	

	// Material Request
	static public function ctrRequestor(){
		$answer = (new ModelEmployees)->mdlRequestor();
		return $answer;
	}

	static public function ctrRequestEncoder(){
		$answer = (new ModelEmployees)->mdlRequestEncoder();
		return $answer;
	}	

	// Waste/Damages Auditor
	static public function ctrAuditor(){
		$answer = (new ModelEmployees)->mdlAuditor();
		return $answer;
	}	

	static public function ctrDebrisEncoder(){
		$answer = (new ModelEmployees)->mdlDebrisEncoder();
		return $answer;
	}	

	// Excess Materials
	static public function ctrExcessOperator(){
		$answer = (new ModelEmployees)->mdlExcessOperator();
		return $answer;
	}	

	static public function ctrExcessEncoder(){
		$answer = (new ModelEmployees)->mdlExcessEncoder();
		return $answer;
	}		

	// Materials Recycling
	static public function ctrRecycler(){
		$answer = (new ModelEmployees)->mdlRecycler();
		return $answer;
	}		

	// Product Components Operators
	static public function ctrComponentsOperators(){
		$answer = (new ModelEmployees)->mdlComponentsOperators();
		return $answer;
	}	

	static public function ctrComponentsEncoder(){
		$answer = (new ModelEmployees)->mdlComponentsEncoder();
		return $answer;
	}	
	
	// Recycle Operators
	static public function ctrRecycleOperators(){
		$answer = (new ModelEmployees)->mdlRecycleOperators();
		return $answer;
	}
	
	static public function ctrRecycleEncoder(){
		$answer = (new ModelEmployees)->mdlRecycleEncoder();
		return $answer;
	}
	
	
	// Final Production Operators
	static public function ctrFinalProductionOperators(){
		$answer = (new ModelEmployees)->mdlFinalProductionOperators();
		return $answer;
	}
	
	static public function ctrFinalProductionEncoder(){
		$answer = (new ModelEmployees)->mdlFinalProductionEncoder();
		return $answer;
	}	

	// Returns
	static public function ctrReceiver(){
		$answer = (new ModelEmployees)->mdlReceiver();
		return $answer;
	}	

	static public function ctrReturnby(){
		$answer = (new ModelEmployees)->mdlReturnby();
		return $answer;
	}
	
	// Materials Return ----------------------------------------
	static public function ctrReturner(){
		$answer = (new ModelEmployees)->mdlReturner();
		return $answer;
	}	

	static public function ctrReturnerEncoder(){
		$answer = (new ModelEmployees)->mdlReturnerEncoder();
		return $answer;
	}	
	
	static public function ctrShowDepartmentList(){
	   	$answer = (new ModelEmployees)->mdlShowDepartmentList();
	   	return $answer;
	}
}
