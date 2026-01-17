<?php
class ControllerProduct{
	// Save NEW Product
	static public function ctrCreateProduct($data){
	   	$answer = (new ModelProduct)->mdlAddProduct($data);
	}

	static public function ctrEditProduct($data){
	   	$answer = (new ModelProduct)->mdlEditProduct($data);
	}	

    // GET Master Product
	static public function ctrShowTransactionProduct($prodid){
		$answer = (new ModelProduct)->mdlShowTransactionProduct($prodid);
		return $answer;
	}

    // GET Branch Product
	static public function ctrShowTransactionBranchProduct($prodid,$branchcode){
		$answer = (new ModelProduct)->mdlShowTransactionBranchProduct($prodid,$branchcode);
		return $answer;
	}			

	static public function ctrShowProduct($item, $value){
		$answer = (new ModelProduct)->mdlShowProduct($item, $value);
		return $answer;
	}

	static public function ctrShowProductList($categorycode, $brandcode, $accountcode, $meas1, $vatdesc){
		$answer = (new ModelProduct)->mdlShowProductList($categorycode, $brandcode, $accountcode, $meas1, $vatdesc);
		return $answer;
	}

	static public function ctrShowProductSalesList($branchcode){
		$answer = (new ModelProduct)->mdlShowProductSalesList($branchcode);
		return $answer;
	}	








	static public function ctr_Show_Product_Sales_List($branchcode){
		$answer = (new ModelProduct)->mdl_Show_Product_Sales_List($branchcode);
		return $answer;
	}








	static public function ctrShowPurchaseItemProducts(){
		$answer = (new ModelProduct)->mdlShowPurchaseItemProducts();
		return $answer;
	}	

	static public function ctrShowBranchProducts($branchcode){
		$answer = (new ModelProduct)->mdlShowBranchProducts($branchcode);
		return $answer;
	}	

	static public function ctrUpdateBranchProduct($uprice,$isactive,$branchprod){
		$answer = (new ModelProduct)->mdlUpdateBranchProduct($uprice,$isactive,$branchprod);
		return $answer;
	}		
}
