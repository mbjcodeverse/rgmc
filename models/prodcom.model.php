<?php
require_once "connection.php";
class ModelProdcom{
	static public function mdlAddProdcom($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $prodcom_id = $pdo->prepare("SELECT CONCAT('PC', LPAD((count(id)+1),7,'0')) as gen_id FROM prodcom");

            $prodcom_id->execute();
		    $prodcomid = $prodcom_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO prodcom(machineid, operatedby, prodstatus, proddate, prodnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :operatedby, :prodstatus, :proddate, :prodnumber, :shift, :postedby, :remarks, :productlist)");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":operatedby", $data["operatedby"], PDO::PARAM_STR);
			$stmt->bindParam(":prodstatus", $data["prodstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":proddate", $data["proddate"], PDO::PARAM_STR);
			$stmt->bindParam(":prodnumber", $prodcomid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO prodcomitems(prodnumber, qty, price, tamount, itemid) VALUES (:prodnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":prodnumber", $prodcomid[0]['gen_id'], PDO::PARAM_STR);
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

	static public function mdlEditProdcom($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			$stmt = $pdo->prepare("UPDATE prodcom SET machineid = :machineid, operatedby = :operatedby, prodstatus = :prodstatus,  proddate = :proddate, shift = :shift, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE prodnumber = :prodnumber");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":operatedby", $data["operatedby"], PDO::PARAM_STR);
			$stmt->bindParam(":prodstatus", $data["prodstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":proddate", $data["proddate"], PDO::PARAM_STR);
			$stmt->bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			// Delete existing Prodcom Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM prodcomitems WHERE prodnumber = :prodnumber");
		    $delete_items -> bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Prodcom Items
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO prodcomitems(prodnumber, qty, price, tamount, itemid) VALUES (:prodnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
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
	
	static public function mdlShowProdcomTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus){
		if ($machineid != ''){
			$machine = " AND (a.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($empid != ''){
			$requestor = " AND (a.operatedby = '$empid')";
		}else{
			$requestor = "";
		}	

		if ($prodstatus != ''){
			$status = " AND (a.prodstatus = '$prodstatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.proddate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.prodnumber != '')" . $machine . $requestor . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT a.proddate,CONCAT(b.lname,', ',b.fname) AS operated_by,a.prodnumber,shift,IFNULL(c.machinedesc,'') AS machinedesc, IFNULL(c.machabbr,'') AS machabbr,a.prodstatus,SUM(d.tamount) as total_amount FROM prodcom AS a INNER JOIN employees AS b ON (a.operatedby = b.empid) LEFT JOIN machine AS c ON (a.machineid = c.machineid) INNER JOIN prodcomitems AS d ON (a.prodnumber = d.prodnumber) $whereClause GROUP BY a.prodnumber");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowProdcomReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $prodstatus, $reptype){
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

		if ($prodstatus != ''){
			$req_status = " AND (c.prodstatus = '$prodstatus')";
		}else{
			$req_status = "";
		}	

		if(!empty($end_date)){
			$dates = " AND (c.proddate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.prodnumber != '')" . $machine . $req_status . $dates . $posted_by . $request_by . $category_code;
        
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN prodcomitems as d ON (b.itemid = d.itemid) INNER JOIN prodcom as c ON (c.prodnumber = d.prodnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.operatedby = j.empid) $whereClause GROUP BY a.catdescription WITH ROLLUP");
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,b.pdesc as prodname,b.meas2,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN prodcomitems AS d ON (b.itemid = d.itemid) INNER JOIN prodcom as c ON (c.prodnumber = d.prodnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.operatedby = j.empid)$whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT *
				FROM (
					-- your original query here
					SELECT 
						c.id,
						c.proddate,
						c.prodnumber,
						c.prodstatus,
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
					INNER JOIN prodcomitems AS d ON b.itemid = d.itemid
					INNER JOIN prodcom AS c ON c.prodnumber = d.prodnumber
					LEFT JOIN machine AS g ON c.machineid = g.machineid
					INNER JOIN employees AS i ON c.postedby = i.empid
					INNER JOIN employees AS j ON c.operatedby = j.empid
					$whereClause
					GROUP BY c.id, prodname WITH ROLLUP
				) AS rolled_up
				ORDER BY 
					CASE WHEN rolled_up.id IS NULL AND rolled_up.prodname IS NULL THEN 1 ELSE 0 END,
					rolled_up.proddate ASC,
					rolled_up.id ASC");
		} elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,b.pdesc AS prodname,b.meas2,SUM(d.qty) as qty,SUM(d.tamount) as tamount FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN prodcom AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  prodcomitems AS d ON (c.prodnumber = d.prodnumber) INNER JOIN rawmats AS b ON (d.itemid = b.itemid) INNER JOIN categoryrawmats AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.operatedby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    } elseif ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("SELECT *
								FROM (
									SELECT 
										c.id,
										c.proddate,
										c.prodnumber,
										c.prodstatus,
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
									INNER JOIN prodcomitems AS d ON b.itemid = d.itemid
									INNER JOIN prodcom AS c ON c.prodnumber = d.prodnumber
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
									rolled_up.proddate,
									rolled_up.id");
	    }

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}			
	
	static public function mdlShowProdcom($prodnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.machinedesc,IFNULL(a.machineid,'') AS machineid,CONCAT(b.lname,', ',b.fname) as request_by,c.operatedby,c.proddate,c.prodnumber,c.shift,c.prodstatus,c.remarks,c.postedby,c.productlist FROM machine AS a RIGHT JOIN prodcom AS c ON (a.machineid = c.machineid) INNER JOIN employees AS b ON (c.operatedby = b.empid) WHERE (c.prodnumber = '$prodnumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET Purchase Order Items
	static public function mdlShowProdcomItems($prodnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2 FROM prodcomitems AS a INNER JOIN rawmats AS b ON (a.itemid = b.itemid) WHERE (a.prodnumber = '$prodnumber')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlCancelProdcom($prodnumber){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // PO # not included
			$stmt = $pdo->prepare("UPDATE prodcom SET prodstatus = 'Cancelled' WHERE prodnumber = :prodnumber");

			$stmt->bindParam(":prodnumber", $prodnumber, PDO::PARAM_STR);	
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