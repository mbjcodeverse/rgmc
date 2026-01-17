<?php
class ControllerBuilding{
	static public function ctrCreateBuilding(){
		if(isset($_POST["tns-buildingname"])&&($_POST["trans_type"] == 'New')){

		   	$data = array("buildingname"=>$_POST["tns-buildingname"],
				          "buildingcode"=>$_POST["num-buildingcode"]);  

		   	$answer = (new ModelBuilding)->mdlAddBuilding($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Building information has been successfully saved!",
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

	static public function ctrEditBuilding(){
		if(isset($_POST["tns-buildingname"])&&($_POST["trans_type"] == 'Update')){

		   	$data = array("id"=>$_POST["num-id"],
						  "buildingname"=>$_POST["tns-buildingname"],
				          "buildingcode"=>$_POST["num-buildingcode"]);
				          
		   	$answer = (new ModelBuilding)->mdlEditBuilding($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Building information has been successfully updated!",
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

	static public function ctrShowBuilding($item, $value){
		$answer = (new ModelBuilding)->mdlShowBuilding($item, $value);
		return $answer;
	}		

	static public function ctrShowBuildingList(){
		$answer = (new ModelBuilding)->mdlShowBuildingList();
		return $answer;
	}
}
