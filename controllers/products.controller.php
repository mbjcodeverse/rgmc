<?php
class ControllerProducts{
	static public function ctrShowProdStocks($idProduct){
		$answer = (new ModelProducts)->mdlShowProdStocks($idProduct);
		return $answer;
	}
	
	static public function ctrShowCategory(){
		$answer = (new ModelProducts)->mdlShowCategory();
		return $answer;
	}

	static public function ctrShowProducts(){
		$answer = (new ModelProducts)->mdlShowProducts();
		return $answer;
	}	

    static public function ctrGetProduct($item, $value){
		$answer = (new ModelProducts)->mdlGetProduct($item, $value);
		return $answer;
	}	

    static public function ctrGetProductCategory($item, $value){
		$answer = (new ModelProducts)->mdlGetProductCategory($item, $value);
		return $answer;
	}	

	static public function ctrCreateProduct(){
		if(isset($_POST["newPdesc"])){
			if (isset($_POST['newIsactive'])){
			    $isactive=$_POST['newIsactive'];
		    }else{
		    	$isactive="0";
		    }

		   	$table = "products";
		   	$data = array("idCategory"=>$_POST["newIdcategory"],
		   				  "pdesc"=>$_POST["newPdesc"],
		   				  "ucost"=>$_POST["newUcost"],
				          "isactive"=>$isactive,
				          "prodid"=>$_POST["newProdid"],
				          "idMeas1"=>$_POST["newIdmeas1"],
				          "eqnum"=>$_POST["newEqnum"],
				          "idMeas2"=>$_POST["newIdmeas2"],
				          "remarks"=>$_POST["newRemarks"]);

		   	$answer = (new ModelProducts)->mdlAddProduct($table, $data);
		   	if($answer == "ok"){
				echo'<script>
				swal({
					  type: "success",
					  title: "Product has been successfully saved!",
					  showConfirmButton: true,
					  confirmButtonText: "Ok"
					  }).then(function(result){
								if (result.value) {
								window.location = "productadd";
								}
							})
				</script>';
			}
		}
	}	

	static public function ctrEditProduct(){
		if(isset($_POST["editPdesc"])){
			if (isset($_POST['editIsactive'])){
			    $isactive=$_POST['editIsactive'];
		    }else{
		    	$isactive="0";
		    }

		   	$table = "products";
		   	$data = array("id"=>$_POST["idProduct"],
		   		          "idCategory"=>$_POST["editIdcategory"],
		   				  "pdesc"=>$_POST["editPdesc"],
		   				  "ucost"=>$_POST["editUcost"],
				          "isactive"=>$isactive,
				          "prodid"=>$_POST["editProdid"],
				          "idMeas1"=>$_POST["editIdmeas1"],
				          "eqnum"=>$_POST["editEqnum"],
				          "idMeas2"=>$_POST["editIdmeas2"],
				          "remarks"=>$_POST["editRemarks"]);

		   	$answer = (new ModelProducts)->mdlEditProduct($table, $data);
		   	if($answer == "ok"){
				echo'<script>
				swal({
					  type: "success",
					  title: "Product has been successfully updated!",
					  showConfirmButton: true,
					  confirmButtonText: "Ok"
					  }).then(function(result){
								if (result.value) {
								window.location = "productadd";
								}
							})
				</script>';
			}
		}
	}		
}