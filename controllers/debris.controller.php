<?php
class ControllerDebris{
	static public function ctrCreateDebris($data){
	   	$answer = (new ModelDebris)->mdlAddDebris($data);
	}

	static public function ctrEditDebris($data){
		$answer = (new ModelDebris)->mdlEditDebris($data);
    }	

    // List TRANSACTIONS
	static public function ctrShowDebrisTransactionList($machineid, $start_date, $end_date, $empid, $debstatus){
		$answer = (new ModelDebris)->mdlShowDebrisTransactionList($machineid, $start_date, $end_date, $empid, $debstatus);
		return $answer;
	}	

	static public function ctrShowDebrisReport($machineid, $start_date, $end_date, $categorycode, $postedby, $debrisby, $debstatus, $reptype){
		$answer = (new ModelDebris)->mdlShowDebrisReport($machineid, $start_date, $end_date, $categorycode, $postedby, $debrisby, $debstatus, $reptype);
		return $answer;
	}

	// Get Debris Details
	static public function ctrShowDebris($debnumber){
		$answer = (new ModelDebris)->mdlShowDebris($debnumber);
		return $answer;
	}

    // Get ITEMS
	static public function ctrShowDebrisItems($debnumber){
		$products = (new ModelDebris)->mdlShowDebrisItems($debnumber);
		return $products;
	}

	static public function ctrCancelDebris($debnumber){
		$answer = (new ModelDebris)->mdlCancelDebris($debnumber);
		return $answer;
	}	

	static public function ctrShowWasteItems($debnumber){
		$products = (new ModelDebris)->mdlShowWasteItems($debnumber);
		return $products;
	}	

	static public function ctrShowWasteoperatorItems($debnumber){
		$products = (new ModelDebris)->mdlShowWasteoperatorItems($debnumber);
		return $products;
	}	
}