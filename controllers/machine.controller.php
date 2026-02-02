<?php
class ControllerMachine{
	static public function ctrCreateMachine($data){
	   	$answer = (new ModelMachine)->mdlAddMachine($data);
	}

	// Update EXISTING RECORD
	static public function ctrEditMachine($data){
		$answer = (new ModelMachine)->mdlEditMachine($data);
	}

	static public function ctrShowMachine($item, $value){
		$answer = (new ModelMachine)->mdlShowMachine($item, $value);
		return $answer;
	}

	// Get ITEMS
	static public function ctrShowAttributes($machineid){
		$attributes = (new ModelMachine)->mdlShowAttributes($machineid);
		return $attributes;
	}

	static public function ctrShowMachineList(){
	   	$answer = (new ModelMachine)->mdlShowMachineList();
	   	return $answer;
	}	

	static public function ctrShowMachineListLocation(){
		$answer = (new ModelMachine)->mdlShowMachineListLocation();
		return $answer;
    }	

    // List Machines
	static public function ctrShowMachineSearchList($classcode, $buildingcode, $isactive, $machstatus){
		$answer = (new ModelMachine)->mdlShowMachineSearchList($classcode, $buildingcode, $isactive, $machstatus);
		return $answer;
	}	

	static public function ctrAddMachineBreakdown($data){
	   	$answer = (new ModelMachine)->mdlAddMachineBreakdown($data);
	}

	static public function ctrEditMachineBreakdown($data){
		$answer = (new ModelMachine)->mdlEditMachineBreakdown($data);
    }	

	static public function ctrMachineBreakdownTransactionList($classcode, $failuretype){
		$answer = (new ModelMachine)->mdlMachineBreakdownTransactionList($classcode, $failuretype);
		return $answer;
	}
	
	static public function ctrShowMachineBreakdown($breakid){
		$answer = (new ModelMachine)->mdlShowMachineBreakdown($breakid);
		return $answer;
	}	
}