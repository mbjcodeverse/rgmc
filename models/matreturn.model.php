<?php
require_once "connection.php";
class ModelMatreturn{
	static public function mdlAddMatreturn($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $matreturn_id = $pdo->prepare("SELECT CONCAT('RM', LPAD((count(id)+1),7,'0')) as gen_id FROM matreturn");

            $matreturn_id->execute();
		    $matreturnid = $matreturn_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO matreturn(machineid, returnby, returntype, retstatus, retdate, retnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :returnby, :returntype, :retstatus, :retdate, :retnumber, :shift, :postedby, :remarks, :productlist)");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":returnby", $data["returnby"], PDO::PARAM_STR);
			$stmt->bindParam(":returntype", $data["returntype"], PDO::PARAM_STR);
			$stmt->bindParam(":retstatus", $data["retstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":retdate", $data["retdate"], PDO::PARAM_STR);
			$stmt->bindParam(":retnumber", $matreturnid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO matreturnitems(retnumber, qty, price, tamount, itemid) VALUES (:retnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":retnumber", $matreturnid[0]['gen_id'], PDO::PARAM_STR);
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

	static public function mdlEditMatreturn($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			$stmt = $pdo->prepare("UPDATE matreturn SET machineid = :machineid, returnby = :returnby, returntype = :returntype, retstatus = :retstatus,  retdate = :retdate, shift = :shift, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE retnumber = :retnumber");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":returnby", $data["returnby"], PDO::PARAM_STR);
			$stmt->bindParam(":returntype", $data["returntype"], PDO::PARAM_STR);
			$stmt->bindParam(":retstatus", $data["retstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":retdate", $data["retdate"], PDO::PARAM_STR);
			$stmt->bindParam(":retnumber", $data["retnumber"], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			// Delete existing Matreturn Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM matreturnitems WHERE retnumber = :retnumber");
		    $delete_items -> bindParam(":retnumber", $data["retnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Matreturn Items
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO matreturnitems(retnumber, qty, price, tamount, itemid) VALUES (:retnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":retnumber", $data["retnumber"], PDO::PARAM_STR);
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
	
	static public function mdlShowMatreturnTransactionList($machineid, $start_date, $end_date, $empid, $retstatus, $returntype){
		if ($machineid != ''){
			$machine = " AND (a.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($empid != ''){
			$requestor = " AND (a.returnby = '$empid')";
		}else{
			$requestor = "";
		}	

		if ($retstatus != ''){
			$status = " AND (a.retstatus = '$retstatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.retdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}	
		
		if ($returntype != ''){
			$return_type = " AND (a.returntype = '$returntype')";
		}else{
			$return_type = "";
		}

		$whereClause = "WHERE (a.retnumber != '')" . $machine . $requestor . $status . $dates . $return_type;

		$stmt = (new Connection)->connect()->prepare("SELECT a.retdate,CONCAT(b.lname,', ',b.fname) AS return_by,a.retnumber,shift,IFNULL(c.machinedesc,'') AS machinedesc, IFNULL(c.machabbr,'') AS machabbr,a.retstatus,SUM(d.tamount) as total_amount FROM matreturn AS a INNER JOIN employees AS b ON (a.returnby = b.empid) LEFT JOIN machine AS c ON (a.machineid = c.machineid) INNER JOIN matreturnitems AS d ON (a.retnumber = d.retnumber) $whereClause GROUP BY a.retnumber");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowMatreturnReport($machineid, $start_date, $end_date, $categorycode, $postedby, $returnby, $retstatus, $reptype, $returntype){
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

		if ($returnby != ''){
			$return_by = " AND (c.returnby = '$returnby')";
		}else{
			$return_by = "";
		}		

		if ($retstatus != ''){
			$ret_status = " AND (c.retstatus = '$retstatus')";
		}else{
			$ret_status = "";
		}	

		if ($returntype != ''){
			$return_type = " AND (c.returntype = '$returntype')";
		}else{
			$return_type = "";
		}

		if(!empty($end_date)){
			$dates = " AND (c.retdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.retnumber != '')" . $machine . $ret_status . $dates . $posted_by . $return_by . $category_code . $return_type;
        
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN matreturnitems as d ON (b.itemid = d.itemid) INNER JOIN matreturn as c ON (c.retnumber = d.retnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.returnby = j.empid) $whereClause GROUP BY a.catdescription WITH ROLLUP");
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,b.pdesc as prodname,b.meas2,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN matreturnitems AS d ON (b.itemid = d.itemid) INNER JOIN matreturn as c ON (c.retnumber = d.retnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.returnby = j.empid)$whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT *
				FROM (
					-- your original query here
					SELECT 
						c.id,
						c.retdate,
						c.retnumber,
						c.retstatus,
						c.shift,
						IFNULL(g.machabbr, '') AS machabbr,
						IFNULL(g.machinedesc, '') AS machinedesc,
						CONCAT(i.lname, ', ', i.fname) AS name,
						CONCAT(j.lname, ', ', j.fname) AS retname,
						b.pdesc AS prodname,
						b.meas2,
						d.qty,
						d.price,
						SUM(d.tamount) as tamount
					FROM categoryrawmats AS a
					INNER JOIN rawmats AS b ON a.categorycode = b.categorycode
					INNER JOIN matreturnitems AS d ON b.itemid = d.itemid
					INNER JOIN matreturn AS c ON c.retnumber = d.retnumber
					LEFT JOIN machine AS g ON c.machineid = g.machineid
					INNER JOIN employees AS i ON c.postedby = i.empid
					INNER JOIN employees AS j ON c.returnby = j.empid
					$whereClause
					GROUP BY c.id, prodname WITH ROLLUP
				) AS rolled_up
				ORDER BY 
					CASE WHEN rolled_up.id IS NULL AND rolled_up.prodname IS NULL THEN 1 ELSE 0 END,
					rolled_up.retdate ASC,
					rolled_up.id ASC");
		} elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,b.pdesc AS prodname,b.meas2,SUM(d.qty) as qty,SUM(d.tamount) as tamount FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN matreturn AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  matreturnitems AS d ON (c.retnumber = d.retnumber) INNER JOIN rawmats AS b ON (d.itemid = b.itemid) INNER JOIN categoryrawmats AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.returnby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    } elseif ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("SELECT *
								FROM (
									SELECT 
										c.id,
										c.retdate,
										c.retnumber,
										c.retstatus,
										c.shift,
										IFNULL(g.machabbr, '') AS machabbr,
										IFNULL(g.machinedesc, '') AS machinedesc,
										CONCAT(i.lname, ', ', i.fname) AS name,
										CONCAT(j.lname, ', ', j.fname) AS retname,
										b.pdesc AS prodname,
										b.meas2,
										d.qty,
										d.price,
										SUM(d.tamount) as tamount,
										--COUNT(d.qty) as num_items
									FROM categoryrawmats AS a
									INNER JOIN rawmats AS b ON a.categorycode = b.categorycode
									INNER JOIN matreturnitems AS d ON b.itemid = d.itemid
									INNER JOIN matreturn AS c ON c.retnumber = d.retnumber
									LEFT JOIN machine AS g ON c.machineid = g.machineid
									INNER JOIN employees AS i ON c.postedby = i.empid
									INNER JOIN employees AS j ON c.returnby = j.empid
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
									rolled_up.retdate,
									rolled_up.id");
	    }

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}			
	
	static public function mdlShowMatreturn($retnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.machinedesc,IFNULL(a.machineid,'') AS machineid,CONCAT(b.lname,', ',b.fname) as return_by,c.returnby,c.returntype,c.retdate,c.retnumber,c.shift,c.retstatus,c.remarks,c.postedby,c.productlist FROM machine AS a RIGHT JOIN matreturn AS c ON (a.machineid = c.machineid) INNER JOIN employees AS b ON (c.returnby = b.empid) WHERE (c.retnumber = '$retnumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET Purchase Order Items
	static public function mdlShowMatreturnItems($retnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2 FROM matreturnitems AS a INNER JOIN rawmats AS b ON (a.itemid = b.itemid) WHERE (a.retnumber = '$retnumber')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlCancelMatreturn($retnumber){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE matreturn SET retstatus = 'Cancelled' WHERE retnumber = :retnumber");

			$stmt->bindParam(":retnumber", $retnumber, PDO::PARAM_STR);	
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