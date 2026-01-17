<?php
require_once "connection.php";
class ModelInventory{
	static public function mdlAddInventory($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $inventory_id = $pdo->prepare("SELECT CONCAT('TI', LPAD((count(id)+1),5,'0')) as gen_id FROM inventory");

            $inventory_id->execute();
		    $inventoryid = $inventory_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO inventory(countedby, invstatus, invdate, invnumber, postedby, remarks, productlist) VALUES (:countedby, :invstatus, :invdate, :invnumber, :postedby, :remarks, :productlist)");

			$stmt->bindParam(":countedby", $data["countedby"], PDO::PARAM_STR);
			$stmt->bindParam(":invstatus", $data["invstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":invdate", $data["invdate"], PDO::PARAM_STR);
			$stmt->bindParam(":invnumber", $inventoryid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO inventoryitems(invnumber, qty, price, tamount, itemid) VALUES (:invnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":invnumber", $inventoryid[0]['gen_id'], PDO::PARAM_STR);
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

	static public function mdlEditInventory($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			$stmt = $pdo->prepare("UPDATE inventory SET countedby = :countedby, invstatus = :invstatus, invdate = :invdate, invnumber = :invnumber, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE invnumber = :invnumber");

			$stmt->bindParam(":countedby", $data["countedby"], PDO::PARAM_STR);
			$stmt->bindParam(":invstatus", $data["invstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":invdate", $data["invdate"], PDO::PARAM_STR);
			$stmt->bindParam(":invnumber", $data["invnumber"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			// Delete existing Inventory Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM inventoryitems WHERE invnumber = :invnumber");
		    $delete_items -> bindParam(":invnumber", $data["invnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO inventoryitems(invnumber, qty, price, tamount, itemid) VALUES (:invnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":invnumber", $data["invnumber"], PDO::PARAM_STR);
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

	static public function mdlShowInventoryTransactionList($start_date, $end_date, $empid, $invstatus){
		if ($empid != ''){
			$countedby = " AND (a.requestby = '$empid')";
		}else{
			$countedby = "";
		}	

		if ($invstatus != ''){
			$status = " AND (a.invstatus = '$invstatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.invdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.invnumber != '')" . $countedby . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT a.invdate,CONCAT(b.lname,', ',b.fname) AS counted_by,a.invnumber,a.invstatus,SUM(d.tamount) as total_amount FROM inventory AS a INNER JOIN employees AS b ON (a.countedby = b.empid) INNER JOIN inventoryitems AS d ON (a.invnumber = d.invnumber) $whereClause GROUP BY a.invnumber");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
	
	static public function mdlShowInventory($invnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT CONCAT(b.lname,', ',b.fname) as counted_by,c.countedby,c.invdate,c.invnumber,c.invstatus,c.remarks,c.postedby,c.productlist FROM inventory AS c INNER JOIN employees AS b ON (c.countedby = b.empid) WHERE (c.invnumber = '$invnumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET Inventory Items
	static public function mdlShowInventoryItems($invnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT CONCAT(b.pdesc,' (',UPPER(b.meas2),')') AS prodname,a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2 FROM inventoryitems AS a INNER JOIN items AS b ON (a.itemid = b.itemid) WHERE (a.invnumber = '$invnumber') ORDER BY prodname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowInventoryTechnical($categorycode, $asofdate) {
		if ($categorycode != ''){
			$category_code = " AND (a.categorycode = '$categorycode')";
		}else{
			$category_code = "";
		}
		$stmt = (new Connection)->connect()->prepare("SELECT a.itemid, b.invdate AS tdate, a.itemcode, 'Inventory' AS details, a.pdesc, a.meas1, a.eqnum, a.meas2, a.ucost, a.reorder, c.qty, 1 AS priority 
				FROM items AS a 
				INNER JOIN category AS ci ON (a.categorycode = ci.categorycode)
				INNER JOIN inventoryitems AS c ON (a.itemid = c.itemid) 
				INNER JOIN inventory AS b ON (c.invnumber = b.invnumber) 
				WHERE (b.invstatus = 'Posted') AND (b.invdate >= '2025-12-27') $category_code 
			UNION ALL
				SELECT a.itemid, b.podate AS tdate, a.itemcode, 'Incoming' AS details, a.pdesc, a.meas1, a.eqnum, a.meas2, a.ucost, a.reorder, c.qty * a.eqnum AS qty, 1 AS priority 
				FROM items AS a 
				INNER JOIN category AS ci ON (a.categorycode = ci.categorycode)
				INNER JOIN purchaseitems AS c ON (a.itemid = c.itemid) 
				INNER JOIN purchaseorder AS b ON (c.ponumber = b.ponumber) 
				WHERE (b.postatus = 'Posted') AND b.podate BETWEEN '2025-12-28' AND :asofdate $category_code
			UNION ALL
				SELECT a.itemid, b.retdate AS tdate, a.itemcode, 'Return' AS details, a.pdesc, a.meas1, a.eqnum, a.meas2, a.ucost, a.reorder, c.qty * a.eqnum AS qty, 1 AS priority 
				FROM items AS a 
				INNER JOIN category AS ci ON (a.categorycode = ci.categorycode)
				INNER JOIN returnitems AS c ON (a.itemid = c.itemid) 
				INNER JOIN returned AS b ON (c.retnumber = b.retnumber) 
				WHERE (b.retstatus = 'Posted') AND b.retdate BETWEEN '2025-12-28' AND :asofdate $category_code
			UNION ALL
				SELECT a.itemid, b.reqdate AS tdate, a.itemcode, 'Withdrawal' AS details, a.pdesc, a.meas1, a.eqnum, a.meas2, a.ucost, a.reorder, c.qty, 1 AS priority 
				FROM items AS a 
				INNER JOIN category AS ci ON (a.categorycode = ci.categorycode)
				INNER JOIN stockoutitems AS c ON (a.itemid = c.itemid) 
				INNER JOIN stockout AS b ON (c.reqnumber = b.reqnumber) 
				WHERE (b.reqstatus = 'Posted') AND b.reqdate BETWEEN '2025-12-28' AND :asofdate $category_code
				ORDER BY pdesc, itemid, priority, tdate
		");

		// Bind the $asofdate parameter to the query using bindParam (or bindValue)
		$stmt->bindParam(':asofdate', $asofdate, PDO::PARAM_STR);

		// Execute the statement
		$stmt->execute();

		// Fetch the results
		return $stmt->fetchAll();
		
		// Close the statement
		$stmt->close();
		$stmt = null;
	}

	
	// static public function mdlShowInventoryTechnical($categorycode, $asofdate){
	// 	// if ($invstatus != ''){
	// 	// 	$status = " AND (a.invstatus = '$invstatus')";
	// 	// }else{
	// 	// 	$status = "";
	// 	// }

	// 	// if(!empty($end_date)){
	// 	// 	$dates = " AND (a.invdate BETWEEN '$start_date' AND '$end_date')";
	// 	// }else{
	// 	// 	$dates = "";
	// 	// }					

	// 	// $whereClause = "WHERE (a.invnumber != '')" . $countedby . $status . $dates;

	// 	// $stmt = (new Connection)->connect()->prepare("SELECT a.itemid,b.invdate AS tdate,a.itemcode,'Inventory' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty,1 AS priority FROM items AS a INNER JOIN inventoryitems AS c ON (a.itemid = c.itemid) INNER JOIN inventory AS b ON (c.invnumber = b.invnumber) WHERE (b.invstatus = 'Posted') AND (b.invdate >= '2025-11-30') 
	// 	// 							UNION ALL
	// 	// 							SELECT a.itemid,b.podate AS tdate,a.itemcode,'Incoming' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty*a.eqnum as qty,1 AS priority FROM items AS a INNER JOIN purchaseitems AS c ON (a.itemid = c.itemid) INNER JOIN purchaseorder AS b ON (c.ponumber = b.ponumber) WHERE (b.postatus = 'Posted') AND (b.podate >= '2025-12-01')
	// 	// 							UNION ALL
	// 	// 							SELECT a.itemid,b.retdate AS tdate,a.itemcode,'Return' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty*a.eqnum as qty,1 AS priority FROM items AS a INNER JOIN returnitems AS c ON (a.itemid = c.itemid) INNER JOIN returned AS b ON (c.retnumber = b.retnumber) WHERE (b.retstatus = 'Posted') AND (b.retdate >= '2025-12-01')  
	// 	// 							UNION ALL
	// 	// 							SELECT a.itemid,b.reqdate AS tdate,a.itemcode,'Withdrawal' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty,1 AS priority FROM items AS a INNER JOIN stockoutitems AS c ON (a.itemid = c.itemid) INNER JOIN stockout AS b ON (c.reqnumber = b.reqnumber) WHERE (b.reqstatus = 'Posted') AND (b.reqdate >= '2025-12-01') ORDER BY pdesc,itemid,priority,tdate");

	// 	$stmt = (new Connection)->connect()->prepare("SELECT a.itemid,b.invdate AS tdate,a.itemcode,'Inventory' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty,1 AS priority FROM items AS a INNER JOIN inventoryitems AS c ON (a.itemid = c.itemid) INNER JOIN inventory AS b ON (c.invnumber = b.invnumber) WHERE (b.invstatus = 'Posted') AND (b.invdate >= '2025-11-30') 
	// 								UNION ALL
	// 								SELECT a.itemid,b.podate AS tdate,a.itemcode,'Incoming' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty*a.eqnum as qty,1 AS priority FROM items AS a INNER JOIN purchaseitems AS c ON (a.itemid = c.itemid) INNER JOIN purchaseorder AS b ON (c.ponumber = b.ponumber) WHERE (b.postatus = 'Posted') AND b.podate BETWEEN '2025-12-01' AND '$asofdate'
	// 								UNION ALL
	// 								SELECT a.itemid,b.retdate AS tdate,a.itemcode,'Return' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty*a.eqnum as qty,1 AS priority FROM items AS a INNER JOIN returnitems AS c ON (a.itemid = c.itemid) INNER JOIN returned AS b ON (c.retnumber = b.retnumber) WHERE (b.retstatus = 'Posted') AND b.retdate BETWEEN '2025-12-01' AND '$asofdate'  
	// 								UNION ALL
	// 								SELECT a.itemid,b.reqdate AS tdate,a.itemcode,'Withdrawal' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty,1 AS priority FROM items AS a INNER JOIN stockoutitems AS c ON (a.itemid = c.itemid) INNER JOIN stockout AS b ON (c.reqnumber = b.reqnumber) WHERE (b.reqstatus = 'Posted') AND b.reqdate BETWEEN '2025-12-01' AND '$asofdate' ORDER BY pdesc,itemid,priority,tdate");

	// 	$stmt -> execute();
	// 	return $stmt -> fetchAll();
	// 	$stmt -> close();
	// 	$stmt = null;
	// }	
}