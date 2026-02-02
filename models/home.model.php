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

		// $whereClause = "WHERE mt.phase NOT IN ('Completed', 'Cancelled')" . $building;

		$whereClause = "WHERE lm.rn = 1" . $building;
		// $stmt = (new Connection)->connect()->prepare("SELECT m.machstatus, IFNULL(COUNT(m.machstatus),0) AS mcount
		// 													 FROM machine m INNER JOIN building b
		// 													      ON (b.buildingcode = m.buildingcode)
		// 														  $whereClause
		// 														  GROUP BY m.machstatus");

		// $stmt = (new Connection)->connect()->prepare("SELECT 
		// 														mt.curstatus AS machstatus,
		// 														COUNT(DISTINCT mt.machineid) AS mcount
		// 													FROM machinetracking mt
		// 													INNER JOIN machine m
		// 														ON mt.machineid = m.machineid
		// 													INNER JOIN building b
		// 														ON b.buildingcode = m.buildingcode
		// 													$whereClause
		// 													GROUP BY mt.curstatus");

		$stmt = (new Connection)->connect()->prepare("WITH latest_mt AS (
																SELECT
																	mt.machineid,
																	mt.curstatus,
																	mt.phase,
																	ROW_NUMBER() OVER (
																		PARTITION BY mt.machineid
																		ORDER BY mt.datereported DESC
																	) AS rn
																FROM machinetracking mt
															)
															SELECT
																lm.curstatus AS machstatus,
																COUNT(lm.machineid) AS mcount
															FROM latest_mt lm
															INNER JOIN machine m
																ON lm.machineid = m.machineid
															INNER JOIN building b
																ON b.buildingcode = m.buildingcode
															$whereClause
															AND lm.phase NOT IN ('Completed', 'Cancelled')
															GROUP BY lm.curstatus");

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

	// 					COALESCE(latest_mt.curstatus, 'Operational') AS machinestatus,

	// 					COUNT(mt.id) AS totalfrequency,
	// 					COALESCE(SUM(mt.timeduration), 0) AS totaldowntime,

	// 					CASE 
	// 						WHEN COUNT(mt.id) = 0 
	// 						THEN (DATEDIFF('$end_date', '$start_date') * 24)
	// 						ELSE ROUND(
	// 							(
	// 								(DATEDIFF('$end_date', '$start_date') * 24)
	// 								- COALESCE(SUM(mt.timeduration), 0)
	// 							) / COUNT(mt.id),
	// 							2
	// 						)
	// 					END AS mtbf,

	// 					CASE 
	// 						WHEN COUNT(mt.id) = 0 
	// 						THEN 0
	// 						ELSE ROUND(
	// 							COALESCE(SUM(mt.timeduration), 0) / COUNT(mt.id),
	// 							2
	// 						)
	// 					END AS mttr

	// 				FROM classification c
	// 				JOIN machine m 
	// 					ON c.classcode = m.classcode

	// 				LEFT JOIN (
	// 					SELECT machineid, curstatus
	// 					FROM machinetracking mt1
	// 					WHERE mt1.id = (
	// 						SELECT MAX(mt2.id)
	// 						FROM machinetracking mt2
	// 						WHERE mt2.machineid = mt1.machineid
	// 					)
	// 				) latest_mt
	// 					ON m.machineid = latest_mt.machineid

	// 				LEFT JOIN machinetracking mt 
	// 					ON m.machineid = mt.machineid

	// 				$whereClause
	// 				GROUP BY 
	// 					c.classname, 
	// 					m.machinedesc, 
	// 					latest_mt.curstatus
	// 				ORDER BY 
	// 					c.classname, 
	// 					m.machinedesc;
	// 				");
	// 	$stmt -> execute();
	// 	return $stmt -> fetchAll();
	// 	$stmt -> close();
	// 	$stmt = null;
	// }	

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
						m.machineid,

						COALESCE(latest_mt.machstatus, 'Operational') AS machinestatus,

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
						SELECT machineid, machstatus
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
						latest_mt.machstatus
					ORDER BY 
						c.classname, 
						m.machinedesc;
					");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
	
	// ------------------------------------------------------------------------------------
	//                                      STOCK LEDGER
	// ------------------------------------------------------------------------------------
	static public function mdlShowStockPeriods(){
		$stmt = (new Connection)->connect()->prepare("WITH inventory_ranked AS (
				SELECT 
					invdate,
					invnumber,
					ROW_NUMBER() OVER (
						ORDER BY invdate ASC
					) AS rn,
					COUNT(*) OVER () AS inv_count
				FROM inventory
				WHERE invstatus = 'Posted'
			),
			paired AS (
				SELECT 
					a.invdate AS inventoryfrom,
					a.invnumber AS invnumber_from,
					DATE_ADD(a.invdate, INTERVAL 1 DAY) AS inventoryfromnextday,
					b.invdate AS inventoryto,
					b.invnumber AS invnumber_to,
					DATE_ADD(b.invdate, INTERVAL 1 DAY) AS inventorytonextday
				FROM inventory_ranked a
				JOIN inventory_ranked b
				ON a.rn = b.rn - 1
			),
			single_entry AS (
				SELECT 
					invdate AS inventoryfrom,
					invnumber AS invnumber_from,
					DATE_ADD(invdate, INTERVAL 1 DAY) AS inventoryfromnextday,
					invdate AS inventoryto,
					invnumber AS invnumber_to,
					DATE_ADD(invdate, INTERVAL 1 DAY) AS inventorytonextday
				FROM inventory_ranked
				WHERE inv_count = 1
			)
			SELECT * FROM paired
			UNION ALL
			SELECT * FROM single_entry
			ORDER BY inventoryfrom");

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$stmt->closeCursor();
		$stmt = null;
		return $result;
	}

	static public function mdlShowStockMatrix($inventoryfrom, $inventoryfromnextday, $inventoryto) {
		$stmt = (new Connection)->connect()->prepare("SELECT mp.itemid,mp.pdesc AS product_display_name,
			COALESCE(beg.beginning_qty, 0) AS beginning_qty,
			COALESCE(beg.beginning_ucost, 0) AS beginning_ucost,
			COALESCE(beg.beginning_tamount, 0) AS beginning_tamount,
			COALESCE(pur.purchase_qty_total, 0) AS purchase_qty_total,
	 		COALESCE(pur.purchase_ucost_total, 0) AS purchase_ucost_total,
	 		COALESCE(pur.purchase_tamount_total, 0) AS purchase_tamount_total,
			COALESCE(ret.return_qty_total, 0) AS return_qty_total,
			COALESCE(rel.release_qty_total, 0) AS release_qty_total,
	 		COALESCE(rel.release_ucost_total, 0) AS release_ucost_total,
			COALESCE(end.ending_qty, 0) AS ending_qty,
			COALESCE(end.ending_ucost, 0) AS ending_ucost,
			COALESCE(end.ending_tamount, 0) AS ending_tamount
			FROM items mp
			-- Subquery for beginning inventory
			LEFT JOIN (
				SELECT ii.itemid,
					SUM(ii.qty) AS beginning_qty,
					SUM(ii.price) AS beginning_ucost,
					SUM(ii.tamount) AS beginning_tamount
				FROM inventoryitems ii
				INNER JOIN inventory inv ON inv.invnumber = ii.invnumber
					AND inv.invdate = :inventoryfrom
					AND inv.invstatus = 'Posted'
				GROUP BY ii.itemid
			) beg ON beg.itemid = mp.itemid

			-- Subquery for incoming items
	 		LEFT JOIN (
	 			SELECT pit.itemid,
	 				SUM(pit.qty) AS purchase_qty_total,
	 				AVG(pit.price) AS purchase_ucost_total,
	 				SUM(pit.tamount) AS purchase_tamount_total
	 			FROM purchaseitems pit
	 			INNER JOIN purchaseorder pur ON pur.ponumber = pit.ponumber
	 			    AND pur.postatus = 'Posted'
	 				AND pur.podate BETWEEN :inventoryfromnextday AND :inventoryto
	 			GROUP BY pit.itemid
	 		) pur ON pur.itemid = mp.itemid

			-- Subquery for returned items
	 		LEFT JOIN (
	 			SELECT rit.itemid,
	 				SUM(rit.qty) AS return_qty_total
	 			FROM returnitems rit
	 			INNER JOIN returned ret ON ret.retnumber = rit.retnumber
	 			    AND ret.retstatus = 'Posted'
	 				AND ret.retdate BETWEEN :inventoryfromnextday AND :inventoryto
	 			GROUP BY rit.itemid
	 		) ret ON ret.itemid = mp.itemid

			-- Subquery for stockout items
	 		LEFT JOIN (
	 			SELECT oit.itemid,
	 				SUM(oit.qty) AS release_qty_total,
	 				AVG(oit.price) AS release_ucost_total,
	 				SUM(oit.tamount) AS release_tamount_total
	 			FROM stockoutitems oit
	 			INNER JOIN stockout rel ON rel.reqnumber = oit.reqnumber
	 			    AND rel.reqstatus = 'Posted'
	 				AND rel.reqdate BETWEEN :inventoryfromnextday AND :inventoryto
	 			GROUP BY oit.itemid
	 		) rel ON rel.itemid = mp.itemid

			-- Subquery for beginning inventory
			LEFT JOIN (
				SELECT ei.itemid,
					SUM(ei.qty) AS ending_qty,
					SUM(ei.price) AS ending_ucost,
					SUM(ei.tamount) AS ending_tamount
				FROM inventoryitems ei
				INNER JOIN inventory inv ON inv.invnumber = ei.invnumber
					AND inv.invdate = :inventoryto
					AND inv.invstatus = 'Posted'
				GROUP BY ei.itemid
			) end ON end.itemid = mp.itemid

			WHERE mp.isactive = 1
			ORDER BY product_display_name
		");

		$stmt->bindParam(':inventoryfrom', $inventoryfrom, PDO::PARAM_STR);
		$stmt->bindParam(':inventoryfromnextday', $inventoryfromnextday, PDO::PARAM_STR);
		$stmt->bindParam(':inventoryto', $inventoryto, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll();

		$stmt->closeCursor();
		$stmt = null;
		return $result;
	}

	static public function mdlShowInventoryTechnicalTemplate($inventoryfrom, $inventoryfromnextday, $inventoryto) {
		$stmt = (new Connection)->connect()->prepare("SELECT cat.catdescription,mp.meas1,mp.itemcode,mp.pdesc AS product_display_name,
			COALESCE(beg.beginning_qty, 0) AS beginning_qty,
			COALESCE(beg.beginning_ucost, 0) AS beginning_ucost,
			COALESCE(beg.beginning_tamount, 0) AS beginning_tamount,
			COALESCE(pur.purchase_qty_total, 0) AS purchase_qty_total,
	 		COALESCE(pur.purchase_ucost_total, 0) AS purchase_ucost_total,
	 		COALESCE(pur.purchase_tamount_total, 0) AS purchase_tamount_total,
			COALESCE(ret.return_qty_total, 0) AS return_qty_total,
			COALESCE(rel.release_qty_total, 0) AS release_qty_total,
	 		COALESCE(rel.release_ucost_total, 0) AS release_ucost_total,
			COALESCE(end.ending_qty, 0) AS ending_qty,
			COALESCE(end.ending_ucost, 0) AS ending_ucost,
			COALESCE(end.ending_tamount, 0) AS ending_tamount
			FROM items mp INNER JOIN category cat ON (mp.categorycode = cat.categorycode)
			-- Subquery for beginning inventory
			LEFT JOIN (
				SELECT ii.itemid,
					SUM(ii.qty) AS beginning_qty,
					SUM(ii.price) AS beginning_ucost,
					SUM(ii.tamount) AS beginning_tamount
				FROM inventoryitems ii
				INNER JOIN inventory inv ON inv.invnumber = ii.invnumber
					AND inv.invdate = :inventoryfrom
					AND inv.invstatus = 'Posted'
				GROUP BY ii.itemid
			) beg ON beg.itemid = mp.itemid

			-- Subquery for incoming items
	 		LEFT JOIN (
	 			SELECT pit.itemid,
	 				SUM(pit.qty) AS purchase_qty_total,
	 				AVG(pit.price) AS purchase_ucost_total,
	 				SUM(pit.tamount) AS purchase_tamount_total
	 			FROM purchaseitems pit
	 			INNER JOIN purchaseorder pur ON pur.ponumber = pit.ponumber
	 			    AND pur.postatus = 'Posted'
	 				AND pur.podate BETWEEN :inventoryfromnextday AND :inventoryto
	 			GROUP BY pit.itemid
	 		) pur ON pur.itemid = mp.itemid

			-- Subquery for returned items
	 		LEFT JOIN (
	 			SELECT rit.itemid,
	 				SUM(rit.qty) AS return_qty_total
	 			FROM returnitems rit
	 			INNER JOIN returned ret ON ret.retnumber = rit.retnumber
	 			    AND ret.retstatus = 'Posted'
	 				AND ret.retdate BETWEEN :inventoryfromnextday AND :inventoryto
	 			GROUP BY rit.itemid
	 		) ret ON ret.itemid = mp.itemid

			-- Subquery for stockout items
	 		LEFT JOIN (
	 			SELECT oit.itemid,
	 				SUM(oit.qty) AS release_qty_total,
	 				AVG(oit.price) AS release_ucost_total,
	 				SUM(oit.tamount) AS release_tamount_total
	 			FROM stockoutitems oit
	 			INNER JOIN stockout rel ON rel.reqnumber = oit.reqnumber
	 			    AND rel.reqstatus = 'Posted'
	 				AND rel.reqdate BETWEEN :inventoryfromnextday AND :inventoryto
	 			GROUP BY oit.itemid
	 		) rel ON rel.itemid = mp.itemid

			-- Subquery for ending inventory
			LEFT JOIN (
				SELECT ei.itemid,
					SUM(ei.qty) AS ending_qty,
					SUM(ei.price) AS ending_ucost,
					SUM(ei.tamount) AS ending_tamount
				FROM inventoryitems ei
				INNER JOIN inventory inv ON inv.invnumber = ei.invnumber
					AND inv.invdate = :inventoryto
					AND inv.invstatus = 'Posted'
				GROUP BY ei.itemid
			) end ON end.itemid = mp.itemid

			WHERE mp.isactive = 1
			ORDER BY cat.catdescription,product_display_name
		");

		$stmt->bindParam(':inventoryfrom', $inventoryfrom, PDO::PARAM_STR);
		$stmt->bindParam(':inventoryfromnextday', $inventoryfromnextday, PDO::PARAM_STR);
		$stmt->bindParam(':inventoryto', $inventoryto, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll();

		$stmt->closeCursor();
		$stmt = null;
		return $result;
	}
}