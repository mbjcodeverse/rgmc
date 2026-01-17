<?php
require_once "connection.php";
class ModelDebris{
	static public function mdlAddDebris($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $debrisby_id = $pdo->prepare("SELECT CONCAT('D', LPAD((count(id)+1),7,'0')) as gen_id FROM debris");

            $debrisby_id->execute();
		    $debrisbyid = $debrisby_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO debris(machineid, debrisby, debstatus, debdate, debnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :debrisby, :debstatus, :debdate, :debnumber, :shift, :postedby, :remarks, :productlist)");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":debrisby", $data["debrisby"], PDO::PARAM_STR);
			$stmt->bindParam(":debstatus", $data["debstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":debdate", $data["debdate"], PDO::PARAM_STR);
			$stmt->bindParam(":debnumber", $debrisbyid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO debrisitems(debnumber, qty, price, tamount, itemid) VALUES (:debnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":debnumber", $debrisbyid[0]['gen_id'], PDO::PARAM_STR);
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

	static public function mdlEditDebris($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			$stmt = $pdo->prepare("UPDATE debris SET machineid = :machineid, debrisby = :debrisby, debstatus = :debstatus,  debdate = :debdate, shift = :shift, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE debnumber = :debnumber");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":debrisby", $data["debrisby"], PDO::PARAM_STR);
			$stmt->bindParam(":debstatus", $data["debstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":debdate", $data["debdate"], PDO::PARAM_STR);
			$stmt->bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			// Delete existing Debris Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM debrisitems WHERE debnumber = :debnumber");
		    $delete_items -> bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Debris Items
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO debrisitems(debnumber, qty, price, tamount, itemid) VALUES (:debnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
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
	
	static public function mdlShowDebrisTransactionList($machineid, $start_date, $end_date, $empid, $debstatus){
		if ($machineid != ''){
			$machine = " AND (a.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($empid != ''){
			$requestor = " AND (a.debrisby = '$empid')";
		}else{
			$requestor = "";
		}	

		if ($debstatus != ''){
			$status = " AND (a.debstatus = '$debstatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.debdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.debnumber != '')" . $machine . $requestor . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT a.debdate,CONCAT(b.lname,', ',b.fname) AS request_by,a.debnumber,shift,IFNULL(c.machinedesc,'') AS machinedesc, IFNULL(c.machabbr,'') AS machabbr,a.debstatus,SUM(d.tamount) as total_amount FROM debris AS a INNER JOIN employees AS b ON (a.debrisby = b.empid) LEFT JOIN machine AS c ON (a.machineid = c.machineid) INNER JOIN debrisitems AS d ON (a.debnumber = d.debnumber) $whereClause GROUP BY a.debnumber");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowDebrisReport($machineid, $start_date, $end_date, $categorycode, $postedby, $debrisby, $debstatus, $reptype){
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

		if ($debrisby != ''){
			$request_by = " AND (c.debrisby = '$debrisby')";
		}else{
			$request_by = "";
		}		

		if ($debstatus != ''){
			$req_status = " AND (c.debstatus = '$debstatus')";
		}else{
			$req_status = "";
		}	

		if(!empty($end_date)){
			$dates = " AND (c.debdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.debnumber != '')" . $machine . $req_status . $dates . $posted_by . $request_by . $category_code;
        
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN debrisitems as d ON (b.itemid = d.itemid) INNER JOIN debris as c ON (c.debnumber = d.debnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.debrisby = j.empid) $whereClause GROUP BY a.catdescription WITH ROLLUP");
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,b.pdesc as prodname,b.meas2,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM categoryrawmats as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN debrisitems AS d ON (b.itemid = d.itemid) INNER JOIN debris as c ON (c.debnumber = d.debnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.debrisby = j.empid) $whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT c.id,c.debdate,c.debnumber, c.debstatus, c.shift, IFNULL(g.machabbr,'') AS machabbr, IFNULL(g.machinedesc,'') AS machinedesc, CONCAT(i.lname,', ',i.fname) as name, CONCAT(j.lname,', ',j.fname) as debname,b.pdesc as prodname,b.meas2,SUM(d.qty) AS qty,d.price,SUM(d.tamount) AS tamount FROM category as a INNER JOIN rawmats as b ON (a.categorycode = b.categorycode) INNER JOIN debrisitems AS d ON (b.itemid = d.itemid) INNER JOIN debris as c ON (c.debnumber = d.debnumber) LEFT JOIN machine as g ON (c.machineid = g.machineid) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.debrisby = j.empid) $whereClause GROUP BY c.id,prodname WITH ROLLUP");
		} elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,b.pdesc AS prodname,b.meas2,SUM(d.qty) as qty,SUM(d.tamount) as tamount FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN debris AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  debrisitems AS d ON (c.debnumber = d.debnumber) INNER JOIN rawmats AS b ON (d.itemid = b.itemid) INNER JOIN category AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.debrisby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    } elseif ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,c.debstatus,c.debdate,c.shift,CONCAT(i.lname,', ',i.fname) as name,CONCAT(j.lname,', ',j.fname) as reqname,b.pdesc AS prodname,b.meas1,SUM(d.qty) as qty FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN debris AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  debrisitems AS d ON (c.debnumber = d.debnumber) INNER JOIN rawmats AS b ON (d.itemid = b.itemid) INNER JOIN category AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.debrisby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    }

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}			
	
	static public function mdlShowDebris($debnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.machinedesc,IFNULL(a.machineid,'') AS machineid,CONCAT(b.lname,', ',b.fname) as request_by,c.debrisby,c.debdate,c.debnumber,c.shift,c.debstatus,c.prodnumber,c.remarks,c.postedby,c.productlist FROM machine AS a RIGHT JOIN debris AS c ON (a.machineid = c.machineid) INNER JOIN employees AS b ON (c.debrisby = b.empid) WHERE (c.debnumber = '$debnumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET Purchase Order Items
	static public function mdlShowDebrisItems($debnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2 FROM debrisitems AS a INNER JOIN rawmats AS b ON (a.itemid = b.itemid) WHERE (a.debnumber = '$debnumber')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlCancelDebris($debnumber){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE debris SET debstatus = 'Cancelled' WHERE debnumber = :debnumber");

			$stmt->bindParam(":debnumber", $debnumber, PDO::PARAM_STR);	
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
	
	static public function mdlShowWasteItems($debnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,
												a.price,
												a.tamount,
												a.itemid,
												b.pdesc,
												b.meas2
											FROM debrisitems AS a
												INNER JOIN debris AS d ON (a.debnumber = d.debnumber)
												INNER JOIN rawmats AS b ON (a.itemid = b.itemid)
											WHERE (a.debnumber = '$debnumber') AND (d.debstatus != 'Cancelled')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	// FOR PRACTICE
	static public function mdlShowWasteoperatorItems($debnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,
												a.price,
												a.tamount,
												a.itemid,
												a.iclass,
												b.pdesc,
												b.meas2
											FROM debrisitems AS a
												INNER JOIN debris AS d ON (a.debnumber = d.debnumber)
												INNER JOIN rawmats AS b ON (a.itemid = b.itemid)
											WHERE (a.debnumber = '$debnumber') AND (d.debstatus != 'Cancelled')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
}