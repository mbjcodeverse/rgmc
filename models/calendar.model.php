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

        $stmt = (new Connection)->connect()->prepare("SELECT m.machinedesc,
                                                             mt.curstatus, mt.datereported, mt.phase
                                        FROM machine m INNER JOIN machinetracking mt
                                                       ON (m.machineid = mt.machineid)
                                        WHERE (phase = 'Pending' OR phase = 'Allocated') AND
                                               datereported $dateClause");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}