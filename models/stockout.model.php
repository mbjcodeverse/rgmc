<?php
require_once "connection.php";
class ModelStockout{
	static public function mdlAddStockout($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $stockout_id = $pdo->prepare("SELECT CONCAT('W', LPAD((count(id)+1),7,'0')) as gen_id FROM stockout");

            $stockout_id->execute();
		    $stockoutid = $stockout_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO stockout(inccode, reltype, deptcode, machineid, requestby, reqstatus, reqdate, reqnumber, shift, postedby, remarks, productlist)
			                                           VALUES (:inccode, :reltype, :deptcode, :machineid, :requestby, :reqstatus, :reqdate, :reqnumber, :shift, :postedby, :remarks, :productlist)");

			$stmt->bindParam(":inccode", $data["inccode"], PDO::PARAM_STR);
			$stmt->bindParam(":reltype", $data["reltype"], PDO::PARAM_STR);
			$stmt->bindParam(":deptcode", $data["deptcode"], PDO::PARAM_STR);
			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":requestby", $data["requestby"], PDO::PARAM_STR);
			$stmt->bindParam(":reqstatus", $data["reqstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":reqdate", $data["reqdate"], PDO::PARAM_STR);
			$stmt->bindParam(":reqnumber", $stockoutid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO stockoutitems(reqnumber, qty, price, tamount, itemid) VALUES (:reqnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":reqnumber", $stockoutid[0]['gen_id'], PDO::PARAM_STR);
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

	static public function mdlEditStockout($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			$stmt = $pdo->prepare("UPDATE stockout SET inccode = :inccode, reltype = :reltype, deptcode = :deptcode, machineid = :machineid, requestby = :requestby, reqstatus = :reqstatus,  reqdate = :reqdate, shift = :shift, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE reqnumber = :reqnumber");

			$stmt->bindParam(":inccode", $data["inccode"], PDO::PARAM_STR);
			$stmt->bindParam(":reltype", $data["reltype"], PDO::PARAM_STR);
			$stmt->bindParam(":deptcode", $data["deptcode"], PDO::PARAM_STR);
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

			// Delete existing Stockout Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM stockoutitems WHERE reqnumber = :reqnumber");
		    $delete_items -> bindParam(":reqnumber", $data["reqnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Stockout Items
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO stockoutitems(reqnumber, qty, price, tamount, itemid) VALUES (:reqnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":reqnumber", $data["reqnumber"], PDO::PARAM_STR);
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
	
	static public function mdlShowReleasingTransactionList($machineid, $start_date, $end_date, $empid, $reqstatus, $reltype){
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

		if ($reltype != ''){
			$rel_type = " AND (a.reltype = '$reltype')";
		}else{
			$rel_type = "";
		}		

		if(!empty($end_date)){
			$dates = " AND (a.reqdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.reqnumber != '')" . $machine . $requestor . $status . $dates . $rel_type;

		$stmt = (new Connection)->connect()->prepare("SELECT IFNULL(dp.deptname,'') AS deptname,IFNULL(jo.controlnum,'') AS controlnum, IFNULL(jo.curstatus,'') AS curstatus, a.reqdate,CONCAT(b.lname,', ',b.fname) AS request_by,a.reqnumber,a.shift,IFNULL(c.machinedesc,'') AS machinedesc, IFNULL(c.machabbr,'') AS machabbr,a.reqstatus,SUM(d.tamount) as total_amount FROM stockout AS a INNER JOIN employees AS b ON (a.requestby = b.empid) LEFT JOIN machine AS c ON (a.machineid = c.machineid) LEFT JOIN department AS dp ON (dp.deptcode = a.deptcode) LEFT JOIN machinetracking AS jo ON (jo.inccode = a.inccode) INNER JOIN stockoutitems AS d ON (a.reqnumber = d.reqnumber) $whereClause GROUP BY a.reqnumber");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowStockoutReport($machineid, $start_date, $end_date, $deptcode, $categorycode, $postedby, $requestby, $reqstatus, $reptype){
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
		
		if ($deptcode != ''){
			$dept_code = " AND (c.deptcode = '$deptcode')";
		}else{
			$dept_code = "";
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

		$whereClause = "WHERE (c.reqnumber != '')" . $machine . $req_status . $dates . $posted_by . $request_by . $category_code . $dept_code;
        
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM category as a INNER JOIN items as b ON (a.categorycode = b.categorycode) INNER JOIN stockoutitems as d ON (b.itemid = d.itemid) INNER JOIN stockout as c ON (c.reqnumber = d.reqnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.requestby = j.empid) $whereClause GROUP BY a.catdescription WITH ROLLUP");
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,b.pdesc as prodname,b.meas2,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM category as a INNER JOIN items as b ON (a.categorycode = b.categorycode) INNER JOIN stockoutitems AS d ON (b.itemid = d.itemid) INNER JOIN stockout as c ON (c.reqnumber = d.reqnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.requestby = j.empid)$whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT c.id,c.reqdate,c.reqnumber, c.reqstatus, c.shift, IFNULL(g.machabbr,'') AS machabbr, IFNULL(g.machinedesc,'') AS machinedesc, CONCAT(i.lname,', ',i.fname) as name, CONCAT(j.lname,', ',j.fname) as reqname,b.pdesc as prodname,b.meas2,SUM(d.qty) AS qty,d.price,SUM(d.tamount) AS tamount FROM category as a INNER JOIN items as b ON (a.categorycode = b.categorycode) INNER JOIN stockoutitems AS d ON (b.itemid = d.itemid) INNER JOIN stockout as c ON (c.reqnumber = d.reqnumber) LEFT JOIN machine as g ON (c.machineid = g.machineid) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.requestby = j.empid) $whereClause GROUP BY c.id,prodname WITH ROLLUP");
		} elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,b.pdesc AS prodname,b.meas2,SUM(d.qty) as qty,SUM(d.tamount) as tamount FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN stockout AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  stockoutitems AS d ON (c.reqnumber = d.reqnumber) INNER JOIN items AS b ON (d.itemid = b.itemid) INNER JOIN category AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.requestby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    } elseif ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,c.reqstatus,c.reqdate,c.shift,CONCAT(i.lname,', ',i.fname) as name,CONCAT(j.lname,', ',j.fname) as reqname,b.pdesc AS prodname,b.meas1,SUM(d.qty) as qty FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN stockout AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  stockoutitems AS d ON (c.reqnumber = d.reqnumber) INNER JOIN items AS b ON (d.itemid = b.itemid) INNER JOIN category AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.requestby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    }

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}			
	
	static public function mdlShowReleasing($reqnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT IFNULL(jo.controlnum,'') AS controlnum, IFNULL(jo.curstatus,'') AS curstatus, c.inccode,c.reltype,c.deptcode,a.machinedesc,IFNULL(a.machineid,'') AS machineid,CONCAT(b.lname,', ',b.fname) as request_by,c.requestby,c.reqdate,c.reqnumber,c.shift,c.reqstatus,c.remarks,c.postedby,c.productlist FROM machine AS a RIGHT JOIN stockout AS c ON (a.machineid = c.machineid) LEFT JOIN machinetracking AS jo ON (jo.inccode = c.inccode) INNER JOIN employees AS b ON (c.requestby = b.empid) WHERE (c.reqnumber = '$reqnumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET Purchase Order Items
	static public function mdlShowReleasingItems($reqnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas1 FROM stockoutitems AS a INNER JOIN items AS b ON (a.itemid = b.itemid) WHERE (a.reqnumber = '$reqnumber')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}