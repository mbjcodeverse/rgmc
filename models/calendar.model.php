<?php
require_once "connection.php";
class ModelCalendar{
    static public function mdlMaintenanceCalendarList($start_date, $end_date){
		if(!empty($end_date)){
			$dates = " BETWEEN '$start_date' AND '$end_date'";
		}else{
			$dates = "";
		}					

		$dateClause = $dates;

        $stmt = (new Connection)->connect()->prepare("SELECT * FROM machinetracking WHERE datereported $dateClause");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}