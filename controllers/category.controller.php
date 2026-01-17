<?php
class ControllerCategory{
	// Parts Category -----------------------------------------------------------
	static public function ctrCreateCategory(){
		if(isset($_POST["txt-catdescription"])&&($_POST["trans_type"] == 'New')){

		   	$data = array("catdescription"=>$_POST["txt-catdescription"],
				          "categorycode"=>$_POST["num-categorycode"]);  

		   	$answer = (new ModelCategory)->mdlAddCategory($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Product category has been successfully saved!",
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

	static public function ctrEditCategory(){
		if(isset($_POST["txt-catdescription"])&&($_POST["trans_type"] == 'Update')){

		   	$data = array("id"=>$_POST["num-id"],
						  "catdescription"=>$_POST["txt-catdescription"],
				          "categorycode"=>$_POST["num-categorycode"]);
 
		   	$answer = (new ModelCategory)->mdlEditCategory($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Product category has been successfully updated!",
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

	static public function ctrShowCategory($item, $value){
		$answer = (new ModelCategory)->mdlShowCategory($item, $value);
		return $answer;
	}		

	static public function ctrShowCategoryList(){
		$answer = (new ModelCategory)->mdlShowCategoryList();
		return $answer;
	}

    // Raw Materials Category -----------------------------------------------------------
	static public function ctrCreateCategoryRawMats(){
		if(isset($_POST["txt-catdescription"])&&($_POST["trans_type"] == 'New')){

		   	$data = array("catdescription"=>$_POST["txt-catdescription"],
				          "categorycode"=>$_POST["num-categorycode"]);  

		   	$answer = (new ModelCategory)->mdlAddCategoryRawMats($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Raw Materials category has been successfully saved!",
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

	static public function ctrEditCategoryRawMats(){
		if(isset($_POST["txt-catdescription"])&&($_POST["trans_type"] == 'Update')){

		   	$data = array("id"=>$_POST["num-id"],
						  "catdescription"=>$_POST["txt-catdescription"],
				          "categorycode"=>$_POST["num-categorycode"]);
 
		   	$answer = (new ModelCategory)->mdlEditCategoryRawMats($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Raw Materials category has been successfully updated!",
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

	static public function ctrShowCategoryRawMats($item, $value){
		$answer = (new ModelCategory)->mdlShowCategoryRawMats($item, $value);
		return $answer;
	}		

	static public function ctrShowCategoryRawMatsList(){
		$answer = (new ModelCategory)->mdlShowCategoryRawMatsList();
		return $answer;
	}	

    // Finished Goods Category -----------------------------------------------------------
	static public function ctrCreateCategoryGoods(){
		if(isset($_POST["txt-catdescription"])&&($_POST["trans_type"] == 'New')){

		   	$data = array("catdescription"=>$_POST["txt-catdescription"],
				          "categorycode"=>$_POST["num-categorycode"]);  

		   	$answer = (new ModelCategory)->mdlAddCategoryGoods($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Finished Goods category has been successfully saved!",
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

	static public function ctrEditCategoryGoods(){
		if(isset($_POST["txt-catdescription"])&&($_POST["trans_type"] == 'Update')){

		   	$data = array("id"=>$_POST["num-id"],
						  "catdescription"=>$_POST["txt-catdescription"],
				          "categorycode"=>$_POST["num-categorycode"]);
 
		   	$answer = (new ModelCategory)->mdlEditCategoryGoods($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Finished Goods category has been successfully updated!",
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

	static public function ctrShowCategoryGoods($item, $value){
		$answer = (new ModelCategory)->mdlShowCategoryGoods($item, $value);
		return $answer;
	}		

	static public function ctrShowCategoryGoodsList(){
		$answer = (new ModelCategory)->mdlShowCategoryGoodsList();
		return $answer;
	}		
}
