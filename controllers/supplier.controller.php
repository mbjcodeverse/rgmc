<?php
class ControllerSupplier{
	static public function ctrCreateSupplier(){
		if(isset($_POST["tns-name"])&&($_POST["trans_type"] == 'New')){
			if (isset($_POST['chk-isactive'])){
			    $isactive=$_POST['chk-isactive'];
		    }else{
		    	$isactive="0";
		    }

		   	$data = array("suppliercode"=>$_POST["txt-suppliercode"],
		   		          "name"=>$_POST["tns-name"],
		   		          "tin"=>$_POST["tns-tin"],
		   		          "vatdesc"=>$_POST["sel-vatdesc"],
		   		          "description"=>$_POST["tns-description"],
		   		          "mobile"=>$_POST["tns-mobile"],
				          "landline"=>$_POST["tns-landline"],
				          "faxnum"=>$_POST["tns-faxnum"],
				          "website"=>$_POST["tns-website"],
				          "contactperson"=>$_POST["tns-contactperson"],
				          "country"=>$_POST["sel-country"],
				          "isactive"=>$isactive,
				          "address"=>$_POST["tns-address"]); 

		   	$answer = (new ModelSupplier)->mdlAddSupplier($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Supplier profile has been successfully saved!",
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

	static public function ctrEditSupplier(){
		if(isset($_POST["tns-name"])&&($_POST["trans_type"] == 'Update')){
			if (isset($_POST['chk-isactive'])){
			    $isactive='1';
		    }else{
		    	$isactive='0';
		    }

		   	$data = array("id"=>$_POST["num-id"],
                          "suppliercode"=>$_POST["txt-suppliercode"],
		   		          "name"=>$_POST["tns-name"],
		   		          "tin"=>$_POST["tns-tin"],
		   		          "vatdesc"=>$_POST["sel-vatdesc"],
		   		          "description"=>$_POST["tns-description"],
		   		          "mobile"=>$_POST["tns-mobile"],
				          "landline"=>$_POST["tns-landline"],
				          "faxnum"=>$_POST["tns-faxnum"],
				          "website"=>$_POST["tns-website"],
				          "contactperson"=>$_POST["tns-contactperson"],
				          "country"=>$_POST["sel-country"],
				          "isactive"=>$isactive,
				          "address"=>$_POST["tns-address"]);  

		   	$answer = (new ModelSupplier)->mdlEditSupplier($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Supplier profile has been successfully updated!",
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

	static public function ctrShowSupplier($item, $value){
		$answer = (new ModelSupplier)->mdlShowSupplier($item, $value);
		return $answer;
	}	

	static public function ctrShowSupplierList(){
		$answer = (new ModelSupplier)->mdlShowSupplierList();
		return $answer;
	}	
}
