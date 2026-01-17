<?php
require_once "connection.php";
class ModelMachineTracking{
	static public function mdlAddMachineTracking($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $diagnosis_id = $pdo->prepare("SELECT CONCAT('M', LPAD((count(id)+1),7,'0')) as gen_id FROM machinetracking");

            $diagnosis_id->execute();
		    $diagnosisid = $diagnosis_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO machinetracking(machineid, datereported, curstatus, inccode, reporter, shift, inctime, failuretype, controlnum, incidentdetails, compreporter, datecompleted, endtime, daysduration, timeduration, actiontaken) 
                                                              VALUES (:machineid, :datereported, :curstatus, :inccode, :reporter, :shift, :inctime, :failuretype, :controlnum, :incidentdetails, :compreporter, :datecompleted, :endtime, :daysduration, :timeduration, :actiontaken)");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":datereported", $data["date_reported"], PDO::PARAM_STR);
			$stmt->bindParam(":curstatus", $data["curstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":inccode", $diagnosisid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":reporter", $data["reporter"], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":inctime", $data["inctime"], PDO::PARAM_STR);
            $stmt->bindParam(":failuretype", $data["failuretype"], PDO::PARAM_STR);
            $stmt->bindParam(":controlnum", $data["controlnum"], PDO::PARAM_STR);
            $stmt->bindParam(":incidentdetails", $data["incidentdetails"], PDO::PARAM_STR);
            $stmt->bindParam(":compreporter", $data["compreporter"], PDO::PARAM_STR);
            $stmt->bindParam(":datecompleted", $data["date_completed"], PDO::PARAM_STR);
            $stmt->bindParam(":endtime", $data["endtime"], PDO::PARAM_STR);
            $stmt->bindParam(":daysduration", $data["daysduration"], PDO::PARAM_STR);
            $stmt->bindParam(":timeduration", $data["timeduration"], PDO::PARAM_STR);
            $stmt->bindParam(":actiontaken", $data["actiontaken"], PDO::PARAM_STR);	
			$stmt->execute();

			$machine_stmt = $pdo->prepare("UPDATE machine SET
                                                 		 machstatus = :machstatus
                                           		   WHERE machineid = :machineid");

			$machine_stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$machine_stmt->bindParam(":machstatus", $data["curstatus"], PDO::PARAM_STR);
			$machine_stmt->execute();

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlEditMachineTracking($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE machinetracking SET
                                                 machineid = :machineid,
                                                 datereported = :datereported,
                                                 curstatus = :curstatus,
                                                 reporter = :reporter,
                                                 shift = :shift,
                                                 inctime = :inctime,
                                                 failuretype = :failuretype,
                                                 controlnum = :controlnum,
                                                 incidentdetails = :incidentdetails,
                                                 compreporter = :compreporter,
                                                 datecompleted = :datecompleted,
                                                 endtime = :endtime,
                                                 daysduration = :daysduration,
                                                 timeduration = :timeduration,
                                                 actiontaken = :actiontaken
                                           WHERE inccode = :inccode");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":datereported", $data["date_reported"], PDO::PARAM_STR);
			$stmt->bindParam(":curstatus", $data["curstatus"], PDO::PARAM_STR);
            $stmt->bindParam(":inccode", $data["inccode"], PDO::PARAM_STR);
			$stmt->bindParam(":reporter", $data["reporter"], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":inctime", $data["inctime"], PDO::PARAM_STR);
			$stmt->bindParam(":failuretype", $data["failuretype"], PDO::PARAM_STR);
            $stmt->bindParam(":controlnum", $data["controlnum"], PDO::PARAM_STR);
            $stmt->bindParam(":incidentdetails", $data["incidentdetails"], PDO::PARAM_STR);
            $stmt->bindParam(":compreporter", $data["compreporter"], PDO::PARAM_STR);
            $stmt->bindParam(":datecompleted", $data["date_completed"], PDO::PARAM_STR);
            $stmt->bindParam(":endtime", $data["endtime"], PDO::PARAM_STR);
            $stmt->bindParam(":daysduration", $data["daysduration"], PDO::PARAM_STR);
            $stmt->bindParam(":timeduration", $data["timeduration"], PDO::PARAM_STR);
            $stmt->bindParam(":actiontaken", $data["actiontaken"], PDO::PARAM_STR);		
			$stmt->execute();

			$machine_stmt = $pdo->prepare("UPDATE machine SET
                                                 		 machstatus = :machstatus
                                           		   WHERE machineid = :machineid");

			$machine_stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$machine_stmt->bindParam(":machstatus", $data["curstatus"], PDO::PARAM_STR);
			$machine_stmt->execute();

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}
	
	static public function mdlMachineTrackingTransactionList($machineid, $datemode, $start_date, $end_date, $curstatus){
		if ($machineid != ''){
			$machine = " AND (b.machineid = '$machineid')";
		}else{
			$machine = "";
		}	

		if ($curstatus != ''){
			$status = " AND (a.curstatus = '$curstatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
            if ($datemode == 'Reported'){
			    $dates = " AND (a.datereported BETWEEN '$start_date' AND '$end_date')";
            }else{
                $dates = " AND (a.datecompleted BETWEEN '$start_date' AND '$end_date')";
            }
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.inccode != '')" . $machine . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT
                                        DATE_FORMAT(a.datereported, '%m/%d/%Y') AS datereported,
                                                    a.inctime,
                                                    a.inccode,
													a.controlnum,
                                                    b.machinedesc,
                                                    a.curstatus,
                                                    IF(a.datecompleted IS NULL OR a.datecompleted = '0000-00-00', '', DATE_FORMAT(a.datecompleted, '%m/%d/%Y')) AS datecompleted
                                                FROM machinetracking a
                                                INNER JOIN machine b ON a.machineid = b.machineid
                                                $whereClause
                                                ORDER BY a.datereported");
        $stmt->execute();
        $results = $stmt->fetchAll();
        $stmt = null; 
        return $results;
	}	

	static public function mdlShowMachineTrackingReport($machineid, $start_date, $end_date, $categorycode, $postedby, $requestby, $reqstatus, $reptype, $costype){
		if ($machineid != ''){
			$machine = " AND (c.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($costype == 'Actual Cost'){
			$cost_type = " AND (d.matcost = 1)";
		}else{
			$cost_type = "";
		}

		if ($categorycode != ''){
			$category_code = " AND (a.categorycode = '$categorycode')";
		}else{
			$category_code = "";
		}		

		if ($postedby != ''){
			$posted_by = " AND (c.postedby = '$postedby')";
		}else{
			$posted_by = "";
		}	

		if ($requestby != ''){
			$request_by = " AND (c.requestby = '$requestby')";
		}else{
			$request_by = "";
		}		

		if ($reqstatus != ''){
			$req_status = " AND (c.reqstatus = '$reqstatus')";
		}else{
			$req_status = "";
		}	

		if(!empty($end_date)){
			$dates = " AND (c.reqdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.reqnumber != '')" . $machine . $req_status . $dates . $posted_by . $request_by . $category_code . $cost_type;
        
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN rawoutitems as d ON (b.itemid = d.itemid) INNER JOIN rawout as c ON (c.reqnumber = d.reqnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.requestby = j.empid) $whereClause GROUP BY a.catdescription WITH ROLLUP");
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,b.pdesc as prodname,b.meas2,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN rawoutitems AS d ON (b.itemid = d.itemid) INNER JOIN rawout as c ON (c.reqnumber = d.reqnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.requestby = j.empid)$whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT *
				FROM (
					-- your original query here
					SELECT 
						c.id,
						c.reqdate,
						c.reqnumber,
						c.reqstatus,
						c.shift,
						IFNULL(g.machabbr, '') AS machabbr,
						IFNULL(g.machinedesc, '') AS machinedesc,
						CONCAT(i.lname, ', ', i.fname) AS name,
						CONCAT(j.lname, ', ', j.fname) AS reqname,
						b.pdesc AS prodname,
						b.meas2,
						d.qty,
						d.price,
						SUM(d.tamount) as tamount
					FROM categoryrawmats AS a
					INNER JOIN rawmats AS b ON a.categorycode = b.categorycode
					INNER JOIN rawoutitems AS d ON b.itemid = d.itemid
					INNER JOIN rawout AS c ON c.reqnumber = d.reqnumber
					LEFT JOIN machine AS g ON c.machineid = g.machineid
					INNER JOIN employees AS i ON c.postedby = i.empid
					INNER JOIN employees AS j ON c.requestby = j.empid
					$whereClause
					GROUP BY c.id, prodname WITH ROLLUP
				) AS rolled_up
				ORDER BY 
					CASE WHEN rolled_up.id IS NULL AND rolled_up.prodname IS NULL THEN 1 ELSE 0 END,
					rolled_up.reqdate ASC,
					rolled_up.id ASC");
		} elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,b.pdesc AS prodname,b.meas2,SUM(d.qty) as qty,SUM(d.tamount) as tamount FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN rawout AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  rawoutitems AS d ON (c.reqnumber = d.reqnumber) INNER JOIN rawmats AS b ON (d.itemid = b.itemid) INNER JOIN categoryrawmats AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.requestby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    } elseif ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("SELECT *
								FROM (
									SELECT 
										c.id,
										c.reqdate,
										c.reqnumber,
										c.reqstatus,
										c.shift,
										IFNULL(g.machabbr, '') AS machabbr,
										IFNULL(g.machinedesc, '') AS machinedesc,
										CONCAT(i.lname, ', ', i.fname) AS name,
										CONCAT(j.lname, ', ', j.fname) AS reqname,
										b.pdesc AS prodname,
										b.meas2,
										d.qty,
										d.price,
										SUM(d.tamount) as tamount,
										--COUNT(d.qty) as num_items
									FROM categoryrawmats AS a
									INNER JOIN rawmats AS b ON a.categorycode = b.categorycode
									INNER JOIN rawoutitems AS d ON b.itemid = d.itemid
									INNER JOIN rawout AS c ON c.reqnumber = d.reqnumber
									LEFT JOIN machine AS g ON c.machineid = g.machineid
									INNER JOIN employees AS i ON c.postedby = i.empid
									INNER JOIN employees AS j ON c.requestby = j.empid
									$whereClause
									GROUP BY g.machinedesc, c.id, b.pdesc WITH ROLLUP
								) AS rolled_up
								WHERE NOT (
									rolled_up.machinedesc IS NULL AND 
									rolled_up.id IS NULL AND 
									rolled_up.prodname IS NULL
								)
								ORDER BY 
									CASE 
										WHEN rolled_up.machinedesc IS NULL THEN 1
										WHEN rolled_up.id IS NULL THEN 1
										ELSE 0 
									END,
									rolled_up.machinedesc,
									rolled_up.reqdate,
									rolled_up.id");
	    }

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
	
	static public function mdlShowMachineIncidentReport($machineid, $start_date, $end_date, $classcode, $reporter, $curstatus, $failuretype, $shift, $reptype){
		if ($machineid != ''){
			$machine = " AND (t.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if(!empty($end_date)){
			$dates = " AND (t.datereported BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}

		if ($classcode != ''){
			$class_code = " AND (c.classcode = '$classcode')";
		}else{
			$class_code = "";
		}	
		
		if ($reporter != ''){
			$reported_by = " AND (t.reporter = '$reporter')";
		}else{
			$reported_by = "";
		}	
		
		if ($curstatus != ''){
			$cur_status = " AND (t.curstatus = '$curstatus')";
		}else{
			$cur_status = "";
		}	
		
		if ($failuretype != ''){
			$failure_type = " AND (t.failuretype = '$failuretype')";
		}else{
			$failure_type = "";
		}

		if ($shift != ''){
			$duty_shift = " AND (t.shift = '$shift')";
		}else{
			$duty_shift = "";
		}

		$whereClause = "WHERE (t.inccode != '')" . $machine . $dates . $class_code . $reported_by . $cur_status . $failure_type . $duty_shift;

		if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT
						c.classname,COUNT(t.timeduration) AS frequency,
						SUM(t.timeduration) AS totalduration
						FROM classification c INNER JOIN machine m ON (c.classcode = m.classcode)
											  INNER JOIN machinetracking t ON (m.machineid = t.machineid)
						$whereClause					  
						GROUP BY c.classname WITH ROLLUP");
		}elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT
						c.classname,
						m.machinedesc,
						COUNT(t.timeduration) AS frequency,
						SUM(t.timeduration) AS totalduration
						FROM classification c INNER JOIN machine m ON (c.classcode = m.classcode)
											  INNER JOIN machinetracking t ON (m.machineid = t.machineid)
						$whereClause					  
						GROUP BY c.classname,m.machinedesc WITH ROLLUP");
		}elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT
						t.failuretype,
						m.machinedesc,
						COUNT(t.timeduration) AS frequency,
						SUM(t.timeduration) AS totalduration
						FROM classification c INNER JOIN machine m ON (c.classcode = m.classcode)
											  INNER JOIN machinetracking t ON (m.machineid = t.machineid)
						$whereClause					  
						GROUP BY t.failuretype,m.machinedesc WITH ROLLUP");				
		}elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT
						DATE_FORMAT(t.datereported, '%m/%d/%Y') AS datereported,
						t.inctime,
						m.machinedesc,
						t.failuretype,
						t.shift,
						t.controlnum,
						IF(t.datecompleted = '0000-00-00' OR t.datecompleted IS NULL, '', DATE_FORMAT(t.datecompleted, '%m/%d/%Y')) AS datecompleted,
						t.endtime,
						IF(t.endtime IS NULL, '', t.timeduration) AS timeduration
						FROM machine m INNER JOIN machinetracking t ON (m.machineid = t.machineid)
									   INNER JOIN classification c ON (c.classcode = m.classcode)
						$whereClause
						ORDER BY t.datereported");
		}elseif ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("SELECT 
							DATE_FORMAT(t.datereported, '%M %Y') AS month_year,
							COUNT(t.machineid) AS frequency,
							SUM(t.timeduration) AS timeduration
						FROM machine m INNER JOIN machinetracking t ON (m.machineid = t.machineid)
									   INNER JOIN classification c ON (c.classcode = m.classcode)
						$whereClause	
						GROUP BY 
							YEAR(t.datereported), MONTH(t.datereported)
						ORDER BY 
							YEAR(t.datereported) DESC, MONTH(t.datereported) DESC");
		}

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
	
	static public function mdlShowMachineTracking($inccode){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM machinetracking WHERE (inccode = '$inccode')");

		$stmt -> execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();
        $stmt = null;
        return $result;
	}

	static public function mdlCancelMachineTracking($reqnumber){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE rawout SET reqstatus = 'Cancelled' WHERE reqnumber = :reqnumber");

			$stmt->bindParam(":reqnumber", $reqnumber, PDO::PARAM_STR);	
			$stmt->execute();

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}	
}