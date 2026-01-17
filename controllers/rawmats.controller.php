<?php
class ControllerRawMats{
	static public function ctrCreateRawMats($data){
	   	$answer = (new ModelRawMats)->mdlAddRawMats($data);
	}

	static public function ctrEditRawMats($data){
	   	$answer = (new ModelRawMats)->mdlEditRawMats($data);
	}	

	static public function ctrShowAllRawMats(){
		$answer = (new ModelRawMats)->mdlShowAllRawMats();
		return $answer;
	}	

	static public function ctrShowRawMatsList($categorycode){
		$answer = (new ModelRawMats)->mdlShowRawMatsList($categorycode);
		return $answer;
	}

	static public function ctrShowRawMat($item, $value){
		$answer = (new ModelRawMats)->mdlShowRawMat($item, $value);
		return $answer;
	}	

	static public function ctrShowPurchaseRawMatProducts(){
		$answer = (new ModelRawMats)->mdlShowPurchaseRawMatProducts();
		return $answer;
	}	

	static public function ctrProductComponentsList(){
		$answer = (new ModelRawMats)->mdlProductComponentsList();
		return $answer;
	}	

	static public function ctrWasteDamageList(){
		$answer = (new ModelRawMats)->mdlWasteDamageList();
		return $answer;
	}
	
	static public function ctrPurchaseItemsList(){
		$answer = (new ModelRawMats)->mdlPurchaseItemsList();
		return $answer;
	}	

	static public function ctrShowTransactionRawMat($itemid){
		$answer = (new ModelRawMats)->mdlShowTransactionRawMat($itemid);
		return $answer;
	}	

	// static public function ctrShowProdStocks($itemid){
	// 	$answer = (new ModelRawMats)->mdlShowProdStocks($itemid);
	// 	return $answer;
	// }	

	// static public function ctrRawMatReport($reptype, $filter, $categorycode){
	// 	$answer = (new ModelRawMats)->mdlRawMatReport($reptype, $filter, $categorycode);
	// 	return $answer;
	// }	
}