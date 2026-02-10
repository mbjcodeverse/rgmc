<?php
class ControllerMachineTracking{
	static public function ctrCreateMachineTracking($data){
	   	$answer = (new ModelMachineTracking)->mdlAddMachineTracking($data);
	}

	static public function ctrEditMachineTracking($data){
		$answer = (new ModelMachineTracking)->mdlEditMachineTracking($data);
		return $answer;
    }	

    // List TRANSACTIONS
	static public function ctrMachineTrackingTransactionList($machineid, $datemode, $start_date, $end_date, $curstatus, $postedby){
		$answer = (new ModelMachineTracking)->mdlMachineTrackingTransactionList($machineid, $datemode, $start_date, $end_date, $curstatus, $postedby);
		return $answer;
	}	

	static public function ctrShowMachineTrackingReport($machineid, $start_date, $end_date, $categorycode, $postedby, $requestby, $reqstatus, $reptype, $costype){
		$answer = (new ModelMachineTracking)->mdlShowMachineTrackingReport($machineid, $start_date, $end_date, $categorycode, $postedby, $requestby, $reqstatus, $reptype, $costype);
		return $answer;
	}

	static public function ctrShowMachineIncidentReport($machineid, $start_date, $end_date, $classcode, $reporter, $curstatus, $failuretype, $shift, $reptype){
		$answer = (new ModelMachineTracking)->mdlShowMachineIncidentReport($machineid, $start_date, $end_date, $classcode, $reporter, $curstatus, $failuretype, $shift, $reptype);
		return $answer;
	}	

	// Get MachineTracking Details
	static public function ctrShowMachineTracking($inccode){
		$answer = (new ModelMachineTracking)->mdlShowMachineTracking($inccode);
		return $answer;
	}

	static public function ctrPrintMachineTracking($inccode){
		$answer = (new ModelMachineTracking)->mdlPrintMachineTracking($inccode);
		return $answer;
	}

	static public function ctrCancelMachineTracking($inccode){
		$answer = (new ModelMachineTracking)->mdlCancelMachineTracking($inccode);
		return $answer;
	}	

	static public function ctrMachineCategoryFailuretypeList($machineid){
		$answer = (new ModelMachineTracking)->mdlMachineCategoryFailuretypeList($machineid);
		return $answer;
	}	

	static public function ctrMachineCategoryBreakdownList($machineid,$class_code){
		$answer = (new ModelMachineTracking)->mdlMachineCategoryBreakdownList($machineid,$class_code);
		return $answer;
	}
	
	static public function ctrMachineIncidentList($machineid){
		$answer = (new ModelMachineTracking)->mdlMachineIncidentList($machineid);
		return $answer;
	}
	
	static public function ctrCancelJobOrder($field, $id){
		$answer = (new ModelMachineTracking)->mdlCancelJobOrder($field, $id);
		return $answer;
	}
}