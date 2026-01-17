<?php
class ControllerProdcom{
	static public function ctrCreateProdcom($data){
	   	$answer = (new ModelProdcom)->mdlAddProdcom($data);
	}

	static public function ctrEditProdcom($data){
		$answer = (new ModelProdcom)->mdlEditProdcom($data);
    }	

    // List TRANSACTIONS
	static public function ctrShowProdcomTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus){
		$answer = (new ModelProdcom)->mdlShowProdcomTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus);
		return $answer;
	}	

	static public function ctrShowProdcomReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $prodstatus, $reptype){
		$answer = (new ModelProdcom)->mdlShowProdcomReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $prodstatus, $reptype);
		return $answer;
	}

	// Get Prodcom Details
	static public function ctrShowProdcom($prodnumber){
		$answer = (new ModelProdcom)->mdlShowProdcom($prodnumber);
		return $answer;
	}

    // Get ITEMS
	static public function ctrShowProdcomItems($prodnumber){
		$products = (new ModelProdcom)->mdlShowProdcomItems($prodnumber);
		return $products;
	}

	static public function ctrCancelProdcom($prodnumber){
		$answer = (new ModelProdcom)->mdlCancelProdcom($prodnumber);
		return $answer;
	}	
}