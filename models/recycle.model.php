<?php
require_once "connection.php";
class ModelRecycle{
	static public function mdlAddRecycle($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $recycle_id = $pdo->prepare("SELECT CONCAT('R', LPAD((count(id)+1),7,'0')) as gen_id FROM recycle");

            $recycle_id->execute();
		    $recycleid = $recycle_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO recycle(machineid, recycleby, recstatus, recdate, recnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :recycleby, :recstatus, :recdate, :recnumber, :shift, :postedby, :remarks, :productlist)");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":recycleby", $data["recycleby"], PDO::PARAM_STR);
			$stmt->bindParam(":recstatus", $data["recstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":recdate", $data["recdate"], PDO::PARAM_STR);
			$stmt->bindParam(":recnumber", $recycleid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO recycleitems(recnumber, qty, price, tamount, itemid) VALUES (:recnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":recnumber", $recycleid[0]['gen_id'], PDO::PARAM_STR);
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

	static public function mdlEditRecycle($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			$stmt = $pdo->prepare("UPDATE recycle SET machineid = :machineid, recycleby = :recycleby, recstatus = :recstatus,  recdate = :recdate, shift = :shift, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE recnumber = :recnumber");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":recycleby", $data["recycleby"], PDO::PARAM_STR);
			$stmt->bindParam(":recstatus", $data["recstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":recdate", $data["recdate"], PDO::PARAM_STR);
			$stmt->bindParam(":recnumber", $data["recnumber"], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			// Delete existing Recycle Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM recycleitems WHERE recnumber = :recnumber");
		    $delete_items -> bindParam(":recnumber", $data["recnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Recycle Items
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO recycleitems(recnumber, qty, price, tamount, itemid) VALUES (:recnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":recnumber", $data["recnumber"], PDO::PARAM_STR);
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
	
	static public function mdlShowRecycleTransactionList($machineid, $start_date, $end_date, $empid, $recstatus){
		if ($machineid != ''){
			$machine = " AND (a.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($empid != ''){
			$requestor = " AND (a.recycleby = '$empid')";
		}else{
			$requestor = "";
		}	

		if ($recstatus != ''){
			$status = " AND (a.recstatus = '$recstatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.recdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.recnumber != '')" . $machine . $requestor . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT a.recdate,CONCAT(b.lname,', ',b.fname) AS request_by,a.recnumber,shift,IFNULL(c.machinedesc,'') AS machinedesc, IFNULL(c.machabbr,'') AS machabbr,a.recstatus,SUM(d.tamount) as total_amount FROM recycle AS a INNER JOIN employees AS b ON (a.recycleby = b.empid) LEFT JOIN machine AS c ON (a.machineid = c.machineid) INNER JOIN recycleitems AS d ON (a.recnumber = d.recnumber) $whereClause GROUP BY a.recnumber");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowRecycleReport($machineid, $start_date, $end_date, $categorycode, $postedby, $recycleby, $recstatus, $reptype){
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

		if ($recycleby != ''){
			$request_by = " AND (c.recycleby = '$recycleby')";
		}else{
			$request_by = "";
		}		

		if ($recstatus != ''){
			$req_status = " AND (c.recstatus = '$recstatus')";
		}else{
			$req_status = "";
		}	

		if(!empty($end_date)){
			$dates = " AND (c.recdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.recnumber != '')" . $machine . $req_status . $dates . $posted_by . $request_by . $category_code;
        
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN recycleitems as d ON (b.itemid = d.itemid) INNER JOIN recycle as c ON (c.recnumber = d.recnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.recycleby = j.empid) $whereClause GROUP BY a.catdescription WITH ROLLUP");
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,b.pdesc as prodname,b.meas2,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN recycleitems AS d ON (b.itemid = d.itemid) INNER JOIN recycle as c ON (c.recnumber = d.recnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.recycleby = j.empid) $whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT *
				FROM (
					-- your original query here
					SELECT 
						c.id,
						c.recdate,
						c.recnumber,
						c.recstatus,
						c.shift,
						IFNULL(g.machabbr, '') AS machabbr,
						IFNULL(g.machinedesc, '') AS machinedesc,
						CONCAT(i.lname, ', ', i.fname) AS name,
						CONCAT(j.lname, ', ', j.fname) AS recname,
						b.pdesc AS prodname,
						b.meas2,
						d.qty,
						d.price,
						SUM(d.tamount) as tamount
					FROM categoryrawmats AS a
					INNER JOIN rawmats AS b ON a.categorycode = b.categorycode
					INNER JOIN recycleitems AS d ON b.itemid = d.itemid
					INNER JOIN recycle AS c ON c.recnumber = d.recnumber
					LEFT JOIN machine AS g ON c.machineid = g.machineid
					INNER JOIN employees AS i ON c.postedby = i.empid
					INNER JOIN employees AS j ON c.recycleby = j.empid
					$whereClause
					GROUP BY c.id, prodname WITH ROLLUP
				) AS rolled_up
				ORDER BY 
					CASE WHEN rolled_up.id IS NULL AND rolled_up.prodname IS NULL THEN 1 ELSE 0 END,
					rolled_up.recdate ASC,
					rolled_up.id ASC");
		} elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,b.pdesc AS prodname,b.meas2,SUM(d.qty) as qty,SUM(d.tamount) as tamount FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN recycle AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  recycleitems AS d ON (c.recnumber = d.recnumber) INNER JOIN rawmats AS b ON (d.itemid = b.itemid) INNER JOIN category AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.recycleby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    } elseif ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("SELECT *
								FROM (
									SELECT 
										c.id,
										c.recdate,
										c.recnumber,
										c.recstatus,
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
									INNER JOIN recycleitems AS d ON b.itemid = d.itemid
									INNER JOIN recycle AS c ON c.recnumber = d.recnumber
									LEFT JOIN machine AS g ON c.machineid = g.machineid
									INNER JOIN employees AS i ON c.postedby = i.empid
									INNER JOIN employees AS j ON c.recycleby = j.empid
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
									rolled_up.recdate,
									rolled_up.id");
	    }

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}			
	
	static public function mdlShowRecycle($recnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.machinedesc,IFNULL(a.machineid,'') AS machineid,CONCAT(b.lname,', ',b.fname) as request_by,c.recycleby,c.recdate,c.recnumber,c.shift,c.recstatus,c.remarks,c.postedby,c.productlist FROM machine AS a RIGHT JOIN recycle AS c ON (a.machineid = c.machineid) INNER JOIN employees AS b ON (c.recycleby = b.empid) WHERE (c.recnumber = '$recnumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET Purchase Order Items
	static public function mdlShowRecycleItems($recnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2 FROM recycleitems AS a INNER JOIN rawmats AS b ON (a.itemid = b.itemid) WHERE (a.recnumber = '$recnumber')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}


	static public function mdlCancelRecycle($recnumber){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // PO # not included
			$stmt = $pdo->prepare("UPDATE recycle SET recstatus = 'Cancelled' WHERE recnumber = :recnumber");

			$stmt->bindParam(":recnumber", $recnumber, PDO::PARAM_STR);	
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