<?php
require_once 'connection.php';
class ModelHome{
	static public function mdlShowFilteredMachineList($classcode, $buildingcode, $machstatus){
		if ($classcode != ''){
			$class = " AND (classcode = '$classcode')";
		}else{
			$class = "";
		}

		if ($buildingcode != ''){
			$building = " AND (buildingcode = '$buildingcode')";
		}else{
			$building = "";
		}

		if ($machstatus != ''){
			$status = " AND (machstatus = '$machstatus')";
		}else{
			$status = "";
		}								

		$whereClause = "WHERE (classcode != '')" . $class . $building . $status;

		$stmt = (new Connection)->connect()->prepare("SELECT * FROM machine $whereClause ORDER BY machinedesc");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}		

	static public function mdlMachineStatusCount($buildingcode){
		if ($buildingcode != ''){
			$building = " AND (b.buildingcode = '$buildingcode')";
		}else{
			$building = "";
		}	

		$whereClause = "WHERE (b.buildingcode != '')" . $building;
		$stmt = (new Connection)->connect()->prepare("SELECT m.machstatus, IFNULL(COUNT(m.machstatus),0) AS mcount
															 FROM machine m INNER JOIN building b
															      ON (b.buildingcode = m.buildingcode)
																  $whereClause
																  GROUP BY m.machstatus");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
	
	static public function mdlMachineCategoryPercentage($buildingcode){
		if ($buildingcode != ''){
			$building = " AND (d.buildingcode = '$buildingcode')";
		}else{
			$building = "";
		}

		$whereClause = "WHERE (c.classcode != '')" . $building;
		$stmt = (new Connection)->connect()->prepare("SELECT
								c.classcode,c.classname,
								COUNT(m.machineid) AS total_machines,
								COUNT(CASE WHEN m.machstatus = 'Operational' THEN 1 END) AS operational_machines,
								ROUND(
									IFNULL(
										(COUNT(CASE WHEN m.machstatus = 'Operational' THEN 1 END) / NULLIF(COUNT(m.machineid), 0)) * 100, 
										0
									), 
									0
								) AS operational_percentage,
    
								-- Add calculation for Under Repair Percentage
								ROUND(
									IFNULL(
										(COUNT(CASE WHEN m.machstatus = 'Under Repair' THEN 1 END) / NULLIF(COUNT(m.machineid), 0)) * 100,
										0
									),
									0
								) AS under_repair_percentage
							FROM 
								classification c
							LEFT JOIN 
								machine m ON c.classcode = m.classcode
							LEFT JOIN
							    building d ON d.buildingcode = m.buildingcode	
							$whereClause	
							GROUP BY 
								c.classname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;

		// $data = $stmt->fetchAll();
		// $stmt->closeCursor(); // Proper for PDO
		// $stmt = null;
		// return $data;
	}	

	static public function mdlShowUptimeDowntimeTrend($reptype, $buildingcode, $classcode, $machstatus, $start_date, $end_date){
		if ($buildingcode != ''){
			$building = " AND (m.buildingcode = '$buildingcode')";
		}else{
			$building = "";
		}

		if ($classcode != ''){
			$class_code = " AND (m.classcode = '$classcode')";
		}else{
			$class_code = "";
		}	

		if ($machstatus != ''){
			$mach_status = " AND (m.machstatus = '$machstatus')";
		}else{
			$mach_status = "";
		}		

		$whereClause = "WHERE (m.machineid != '')" . $building . $class_code . $mach_status;
		$stmt = (new Connection)->connect()->prepare("WITH RECURSIVE DateSeq AS (
										-- Generate the date sequence from '2025-09-01' to '2025-10-30'
										SELECT CAST('$start_date' AS DATE) AS datereported
										UNION ALL
										SELECT datereported + INTERVAL 1 DAY
										FROM DateSeq
										WHERE datereported < '$end_date'
									)
									SELECT c.classname,
										m.machinedesc,
										ds.datereported,
										IFNULL(24.00 - mt.timeduration, 24.00) AS green_line,
										IFNULL(mt.timeduration, 0.00) AS redline
									FROM classification c
									INNER JOIN machine m ON c.classcode = m.classcode
									CROSS JOIN DateSeq ds  -- Create a date sequence for each machine
									LEFT JOIN machinetracking mt 
										ON m.machineid = mt.machineid
										AND DATE(mt.datereported) = ds.datereported  -- Ensure date matching only (no time)
									$whereClause 	
									ORDER BY m.machinedesc, ds.datereported");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

static public function mdlMachineHealth($buildingcode, $start_date, $end_date){
		if ($buildingcode != ''){
			$building = " AND (m.buildingcode = '$buildingcode')";
		}else{
			$building = "";
		}	

		if(!empty($end_date)){
			$dates = " AND (mt.datereported BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}	

		$whereClause = "WHERE (m.machinedesc != '')" . $building . $dates;
		$stmt = (new Connection)->connect()->prepare("SELECT 
						c.classname,
						m.machinedesc,

						COALESCE(latest_mt.curstatus, 'Operational') AS machinestatus,

						COUNT(mt.id) AS totalfrequency,
						COALESCE(SUM(mt.timeduration), 0) AS totaldowntime,

						CASE 
							WHEN COUNT(mt.id) = 0 
							THEN (DATEDIFF('$end_date', '$start_date') * 24)
							ELSE ROUND(
								(
									(DATEDIFF('$end_date', '$start_date') * 24)
									- COALESCE(SUM(mt.timeduration), 0)
								) / COUNT(mt.id),
								2
							)
						END AS mtbf,

						CASE 
							WHEN COUNT(mt.id) = 0 
							THEN 0
							ELSE ROUND(
								COALESCE(SUM(mt.timeduration), 0) / COUNT(mt.id),
								2
							)
						END AS mttr

					FROM classification c
					JOIN machine m 
						ON c.classcode = m.classcode

					LEFT JOIN (
						SELECT machineid, curstatus
						FROM machinetracking mt1
						WHERE mt1.id = (
							SELECT MAX(mt2.id)
							FROM machinetracking mt2
							WHERE mt2.machineid = mt1.machineid
						)
					) latest_mt
						ON m.machineid = latest_mt.machineid

					LEFT JOIN machinetracking mt 
						ON m.machineid = mt.machineid

					$whereClause
					GROUP BY 
						c.classname, 
						m.machinedesc, 
						latest_mt.curstatus
					ORDER BY 
						c.classname, 
						m.machinedesc;
					");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
	
	// static public function mdlMachineHealth($buildingcode, $start_date, $end_date){
	// 	if ($buildingcode != ''){
	// 		$building = " AND (m.buildingcode = '$buildingcode')";
	// 	}else{
	// 		$building = "";
	// 	}	

	// 	if(!empty($end_date)){
	// 		$dates = " AND (mt.datereported BETWEEN '$start_date' AND '$end_date')";
	// 	}else{
	// 		$dates = "";
	// 	}	

	// 	$whereClause = "WHERE (m.machinedesc != '')" . $building . $dates;
	// 	$stmt = (new Connection)->connect()->prepare("SELECT
	// 					c.classname,
	// 					m.machinedesc,
	// 					COUNT(mt.id) AS totalfrequency,
	// 					COALESCE(SUM(mt.timeduration), 0) AS totaldowntime,
	// 					CASE 
	// 						WHEN COUNT(mt.id) = 0 THEN 0
	// 						ELSE ROUND(COALESCE(SUM(mt.timeduration), 0) / COUNT(mt.id), 2)
	// 					END AS mtbf,
	// 					CASE 
	// 						WHEN COUNT(mt.id) = 0 THEN 0
	// 						ELSE ROUND(COALESCE(SUM(mt.timeduration), 0) / COUNT(mt.id), 2)
	// 					END AS mttr
	// 				FROM classification c
	// 				JOIN machine m
	// 					ON c.classcode = m.classcode
	// 				LEFT JOIN machinetracking mt
	// 					ON m.machineid = mt.machineid
	// 				$whereClause		
	// 				GROUP BY
	// 					c.classname,
	// 					m.machinedesc
	// 				ORDER BY
	// 					c.classname,
	// 					m.machinedesc
	// 				");
	// 	$stmt -> execute();
	// 	return $stmt -> fetchAll();
	// 	$stmt -> close();
	// 	$stmt = null;
	// }
}