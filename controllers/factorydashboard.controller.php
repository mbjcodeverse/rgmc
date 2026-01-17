<?php
class ControllerFactoryDashboard{
	// STATISTICS Tab
    static public function ctrShowFactoryDashboard($reptype, $start_date, $end_date){
		$answer = (new ModelFactoryDashboard)->mdlShowFactoryDashboard($reptype, $start_date, $end_date);
		return $answer;
	}





    // STATISTICS Tab - below Line Graph
	static public function ctrShowProductionMetrics($reptype, $start_date, $end_date){
		$answer = (new ModelFactoryDashboard)->mdlShowProductionMetrics($reptype, $start_date, $end_date);
		return $answer;
	}	

	
	static public function ctrShowProductionDetails($category, $start_date, $end_date){
		$answer = (new ModelFactoryDashboard)->mdlShowProductionDetails($category, $start_date, $end_date);
		return $answer;
	}



    static public function ctrShowDashboardAssessment($start_date, $end_date, $categorycode, $tier){
		$answer = (new ModelFactoryDashboard)->mdlShowDashboardAssessment($start_date, $end_date, $categorycode, $tier);
		return $answer;
	}	

	static public function ctrShowInventoryPeriods($end_date){
		$answer = (new ModelFactoryDashboard)->mdlShowInventoryPeriods($end_date);
		return $answer;
	}
	
    static public function ctrShowUsageAssessment($start_date, $end_date, $categorycode){
		$answer = (new ModelFactoryDashboard)->mdlShowUsageAssessment($start_date, $end_date, $categorycode);
		return $answer;
	}	

    static public function ctrShowUsageSummary($itemid, $start_date, $end_date){
		$answer = (new ModelFactoryDashboard)->mdlShowUsageSummary($itemid, $start_date, $end_date);
		return $answer;
	}	

    static public function ctrShowMaterialCostTrail($trans_date){
		$answer = (new ModelFactoryDashboard)->mdlShowMaterialCostTrail($trans_date);
		return $answer;
	}	

    static public function ctrShowProductionCostTrail($trans_date){
		$answer = (new ModelFactoryDashboard)->mdlShowProductionCostTrail($trans_date);
		return $answer;
	}	
}