<?php
require_once "connection.php";
class ModelRawout{
	static public function mdlAddRawout($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $rawout_id = $pdo->prepare("SELECT CONCAT('R', LPAD((count(id)+1),7,'0')) as gen_id FROM rawout");

            $rawout_id->execute();
		    $rawoutid = $rawout_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO rawout(machineid, requestby, reqstatus, reqdate, reqnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :requestby, :reqstatus, :reqdate, :reqnumber, :shift, :postedby, :remarks, :productlist)");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":requestby", $data["requestby"], PDO::PARAM_STR);
			$stmt->bindParam(":reqstatus", $data["reqstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":reqdate", $data["reqdate"], PDO::PARAM_STR);
			$stmt->bindParam(":reqnumber", $rawoutid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO rawoutitems(reqnumber, qty, price, tamount, itemid, matcost) VALUES (:reqnumber, :qty, :price, :tamount, :itemid, :matcost)");

				$items->bindParam(":reqnumber", $rawoutid[0]['gen_id'], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":price", $product->price, PDO::PARAM_STR);
				$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
				$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
				$items->bindParam(":matcost", $product->matcost, PDO::PARAM_INT);
				$items->execute();
			}

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlEditRawout($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			$stmt = $pdo->prepare("UPDATE rawout SET machineid = :machineid, requestby = :requestby, reqstatus = :reqstatus,  reqdate = :reqdate, shift = :shift, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE reqnumber = :reqnumber");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":requestby", $data["requestby"], PDO::PARAM_STR);
			$stmt->bindParam(":reqstatus", $data["reqstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":reqdate", $data["reqdate"], PDO::PARAM_STR);
			$stmt->bindParam(":reqnumber", $data["reqnumber"], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			// Delete existing Rawout Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM rawoutitems WHERE reqnumber = :reqnumber");
		    $delete_items -> bindParam(":reqnumber", $data["reqnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Rawout Items
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO rawoutitems(reqnumber, qty, price, tamount, itemid, matcost) VALUES (:reqnumber, :qty, :price, :tamount, :itemid, :matcost)");

				$items->bindParam(":reqnumber", $data["reqnumber"], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":price", $product->price, PDO::PARAM_STR);
				$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
				$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
				$items->bindParam(":matcost", $product->matcost, PDO::PARAM_INT);
				$items->execute();
			}

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}
	
	static public function mdlShowRawoutTransactionList($machineid, $start_date, $end_date, $empid, $reqstatus){
		if ($machineid != ''){
			$machine = " AND (a.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($empid != ''){
			$requestor = " AND (a.requestby = '$empid')";
		}else{
			$requestor = "";
		}	

		if ($reqstatus != ''){
			$status = " AND (a.reqstatus = '$reqstatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.reqdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.reqnumber != '')" . $machine . $requestor . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT a.reqdate,CONCAT(b.lname,', ',b.fname) AS request_by,a.reqnumber,shift,IFNULL(c.machinedesc,'') AS machinedesc, IFNULL(c.machabbr,'') AS machabbr,a.reqstatus,SUM(d.tamount) as total_amount FROM rawout AS a INNER JOIN employees AS b ON (a.requestby = b.empid) LEFT JOIN machine AS c ON (a.machineid = c.machineid) INNER JOIN rawoutitems AS d ON (a.reqnumber = d.reqnumber) $whereClause GROUP BY a.reqnumber");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowRawoutReport($machineid, $start_date, $end_date, $categorycode, $postedby, $requestby, $reqstatus, $reptype, $costype){
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
	
	static public function mdlShowRawout($reqnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.machinedesc,IFNULL(a.machineid,'') AS machineid,CONCAT(b.lname,', ',b.fname) as request_by,c.requestby,c.reqdate,c.reqnumber,c.shift,c.reqstatus,c.remarks,c.postedby,c.productlist FROM machine AS a RIGHT JOIN rawout AS c ON (a.machineid = c.machineid) INNER JOIN employees AS b ON (c.requestby = b.empid) WHERE (c.reqnumber = '$reqnumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET Purchase Order Items
	static public function mdlShowRawoutItems($reqnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,a.price,a.tamount,a.itemid,a.matcost,b.pdesc,b.meas2 FROM rawoutitems AS a INNER JOIN rawmats AS b ON (a.itemid = b.itemid) WHERE (a.reqnumber = '$reqnumber')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlCancelRawout($reqnumber){
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