<?php
class ControllerClassification{
	static public function ctrCreateClassification(){
		if(isset($_POST["tns-classname"])&&($_POST["trans_type"] == 'New')){

		   	$data = array("classname"=>$_POST["tns-classname"],
				          "classcode"=>$_POST["num-classcode"]);  

		   	$answer = (new ModelClassification)->mdlAddClassification($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Machine class has been successfully saved!",
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

	static public function ctrEditClassification(){
		if(isset($_POST["tns-classname"])&&($_POST["trans_type"] == 'Update')){

		   	$data = array("id"=>$_POST["num-id"],
						  "classname"=>$_POST["tns-classname"],
				          "classcode"=>$_POST["num-classcode"]);
				          
		   	$answer = (new ModelClassification)->mdlEditClassification($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Machine class has been successfully updated!",
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

	static public function ctrShowClassification($item, $value){
		$answer = (new ModelClassification)->mdlShowClassification($item, $value);
		return $answer;
	}		

	static public function ctrShowClassificationList(){
		$answer = (new ModelClassification)->mdlShowClassificationList();
		return $answer;
	}
}
