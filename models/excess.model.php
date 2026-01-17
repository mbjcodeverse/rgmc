<?php
require_once "connection.php";
class ModelExcess{
	static public function mdlAddExcess($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $excess_id = $pdo->prepare("SELECT CONCAT('E', LPAD((count(id)+1),7,'0')) as gen_id FROM excess");

            $excess_id->execute();
		    $excessid = $excess_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO excess(machineid, operatedby, excstatus, excdate, excnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :operatedby, :excstatus, :excdate, :excnumber, :shift, :postedby, :remarks, :productlist)");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":operatedby", $data["operatedby"], PDO::PARAM_STR);
			$stmt->bindParam(":excstatus", $data["excstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":excdate", $data["excdate"], PDO::PARAM_STR);
			$stmt->bindParam(":excnumber", $excessid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO excessitems(excnumber, qty, price, tamount, itemid) VALUES (:excnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":excnumber", $excessid[0]['gen_id'], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":price", $product->price, PDO::PARAM_STR);
				$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
				$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
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

	static public function mdlEditExcess($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			$stmt = $pdo->prepare("UPDATE excess SET machineid = :machineid, operatedby = :operatedby, excstatus = :excstatus,  excdate = :excdate, shift = :shift, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE excnumber = :excnumber");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":operatedby", $data["operatedby"], PDO::PARAM_STR);
			$stmt->bindParam(":excstatus", $data["excstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":excdate", $data["excdate"], PDO::PARAM_STR);
			$stmt->bindParam(":excnumber", $data["excnumber"], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			// Delete existing Excess Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM excessitems WHERE excnumber = :excnumber");
		    $delete_items -> bindParam(":excnumber", $data["excnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Excess Items
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO excessitems(excnumber, qty, price, tamount, itemid) VALUES (:excnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":excnumber", $data["excnumber"], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":price", $product->price, PDO::PARAM_STR);
				$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
				$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
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
	
	static public function mdlShowExcessTransactionList($machineid, $start_date, $end_date, $empid, $excstatus){
		if ($machineid != ''){
			$machine = " AND (a.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($empid != ''){
			$operatedby = " AND (a.operatedby = '$empid')";
		}else{
			$operatedby = "";
		}	

		if ($excstatus != ''){
			$status = " AND (a.excstatus = '$excstatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.excdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.excnumber != '')" . $machine . $operatedby . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT a.excdate,CONCAT(b.lname,', ',b.fname) AS operated_by,a.excnumber,shift,IFNULL(c.machinedesc,'') AS machinedesc, IFNULL(c.machabbr,'') AS machabbr,a.excstatus,SUM(d.tamount) as total_amount FROM excess AS a INNER JOIN employees AS b ON (a.operatedby = b.empid) LEFT JOIN machine AS c ON (a.machineid = c.machineid) INNER JOIN excessitems AS d ON (a.excnumber = d.excnumber) $whereClause GROUP BY a.excnumber");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowExcessReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $excstatus, $reptype){
		if ($machineid != ''){
			$machine = " AND (c.machineid = '$machineid')";
		}else{
			$machine = "";
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

		if ($operatedby != ''){
			$request_by = " AND (c.operatedby = '$operatedby')";
		}else{
			$request_by = "";
		}		

		if ($excstatus != ''){
			$exc_status = " AND (c.excstatus = '$excstatus')";
		}else{
			$exc_status = "";
		}	

		if(!empty($end_date)){
			$dates = " AND (c.excdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.excnumber != '')" . $machine . $exc_status . $dates . $posted_by . $request_by . $category_code;
        
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN excessitems as d ON (b.itemid = d.itemid) INNER JOIN excess as c ON (c.excnumber = d.excnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.operatedby = j.empid) $whereClause GROUP BY a.catdescription WITH ROLLUP");
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,b.pdesc as prodname,b.meas2,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN excessitems AS d ON (b.itemid = d.itemid) INNER JOIN excess as c ON (c.excnumber = d.excnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.operatedby = j.empid)$whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT *
				FROM (
					-- your original query here
					SELECT 
						c.id,
						c.excdate,
						c.excnumber,
						c.excstatus,
						c.shift,
						IFNULL(g.machabbr, '') AS machabbr,
						IFNULL(g.machinedesc, '') AS machinedesc,
						CONCAT(i.lname, ', ', i.fname) AS name,
						CONCAT(j.lname, ', ', j.fname) AS oprname,
						b.pdesc AS prodname,
						b.meas2,
						d.qty,
						d.price,
						SUM(d.tamount) as tamount
					FROM categoryrawmats AS a
					INNER JOIN rawmats AS b ON a.categorycode = b.categorycode
					INNER JOIN excessitems AS d ON b.itemid = d.itemid
					INNER JOIN excess AS c ON c.excnumber = d.excnumber
					LEFT JOIN machine AS g ON c.machineid = g.machineid
					INNER JOIN employees AS i ON c.postedby = i.empid
					INNER JOIN employees AS j ON c.operatedby = j.empid
					$whereClause
					GROUP BY c.id, prodname WITH ROLLUP
				) AS rolled_up
				ORDER BY 
					CASE WHEN rolled_up.id IS NULL AND rolled_up.prodname IS NULL THEN 1 ELSE 0 END,
					rolled_up.excdate ASC,
					rolled_up.id ASC");
		} elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,b.pdesc AS prodname,b.meas2,SUM(d.qty) as qty,SUM(d.tamount) as tamount FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN excess AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  excessitems AS d ON (c.excnumber = d.excnumber) INNER JOIN rawmats AS b ON (d.itemid = b.itemid) INNER JOIN categoryrawmats AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.operatedby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    } elseif ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("SELECT *
								FROM (
									SELECT 
										c.id,
										c.excdate,
										c.excnumber,
										c.excstatus,
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
									INNER JOIN excessitems AS d ON b.itemid = d.itemid
									INNER JOIN excess AS c ON c.excnumber = d.excnumber
									LEFT JOIN machine AS g ON c.machineid = g.machineid
									INNER JOIN employees AS i ON c.postedby = i.empid
									INNER JOIN employees AS j ON c.operatedby = j.empid
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
									rolled_up.excdate,
									rolled_up.id");
	    }

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}			
	
	static public function mdlShowExcess($excnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.machinedesc,IFNULL(a.machineid,'') AS machineid,CONCAT(b.lname,', ',b.fname) as request_by,c.operatedby,c.excdate,c.excnumber,c.shift,c.excstatus,c.remarks,c.postedby,c.productlist FROM machine AS a RIGHT JOIN excess AS c ON (a.machineid = c.machineid) INNER JOIN employees AS b ON (c.operatedby = b.empid) WHERE (c.excnumber = '$excnumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET Purchase Order Items
	static public function mdlShowExcessItems($excnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2 FROM excessitems AS a INNER JOIN rawmats AS b ON (a.itemid = b.itemid) WHERE (a.excnumber = '$excnumber')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlCancelExcess($excnumber){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE excess SET excstatus = 'Cancelled' WHERE excnumber = :excnumber");

			$stmt->bindParam(":excnumber", $excnumber, PDO::PARAM_STR);	
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