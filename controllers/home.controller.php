<?php
class ControllerHome{
    static public function ctrShowFilteredMachineList($classcode, $buildingcode, $machstatus){
		$answer = (new ModelHome)->mdlShowFilteredMachineList($classcode, $buildingcode, $machstatus);
		return $answer;
	}	
	
    static public function ctrMachineStatusCount($buildingcode){
		$answer = (new ModelHome)->mdlMachineStatusCount($buildingcode);
		return $answer;
	}	

    static public function ctrMachineCategoryPercentage($buildingcode){
		$answer = (new ModelHome)->mdlMachineCategoryPercentage($buildingcode);
		return $answer;
	}	

    static public function ctrShowUptimeDowntimeTrend($reptype, $buildingcode, $classcode, $machstatus, $start_date, $end_date){
		$answer = (new ModelHome)->mdlShowUptimeDowntimeTrend($reptype, $buildingcode, $classcode, $machstatus, $start_date, $end_date);
		return $answer;
	}	

	static public function ctrMachineHealth($buildingcode, $start_date, $end_date){
		$answer = (new ModelHome)->mdlMachineHealth($buildingcode, $start_date, $end_date);
		return $answer;
	}	

	// ------------------------------------------------------------------------------------
	//                                      STOCK LEDGER
	// ------------------------------------------------------------------------------------
	static public function ctrShowStockPeriods(){
		$answer = (new ModelHome)->mdlShowStockPeriods();
		return $answer;
	}

	static public function ctrShowStockMatrix($inventoryfrom, $inventoryfromnextday, $inventoryto){
		$answer = (new ModelHome)->mdlShowStockMatrix($inventoryfrom, $inventoryfromnextday, $inventoryto);
		return $answer;
	}

	static public function ctrShowInventoryTechnicalTemplate($start_date, $from_date, $end_date){
		$answer = (new ModelHome)->mdlShowInventoryTechnicalTemplate($start_date, $from_date, $end_date);
		return $answer;
	}
}