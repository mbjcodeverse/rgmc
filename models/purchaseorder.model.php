<?php
require_once "connection.php";
class ModelPurchaseOrder{
	static public function mdlAddPurchaseOrder($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $purchase_id = $pdo->prepare("SELECT CONCAT('P', LPAD((count(id)+1),7,'0')) as gen_id FROM purchaseorder");

            $purchase_id->execute();
		    $purchaseid = $purchase_id -> fetchAll(PDO::FETCH_ASSOC);
			$pocode = $purchaseid[0]['gen_id'];

		    // $purchase_number = $purchaseid[0]['ponumber'];
		    // $sequence_code = strval(intval(substr($purchase_number,-5)) + 1);
		    // $pocode = 'P' . str_repeat("0",5 - strlen($sequence_code)) . $sequence_code;

			$stmt = $pdo->prepare("INSERT INTO purchaseorder(suppliercode, podate, postatus, ponumber, orderedby, machineid, preparedby, remarks, amount, discount, netamount, productlist) VALUES (:suppliercode, :podate, :postatus, :ponumber, :orderedby, :machineid, :preparedby, :remarks, :amount, :discount, :netamount, :productlist)");

			$stmt->bindParam(":suppliercode", $data["suppliercode"], PDO::PARAM_STR);
			$stmt->bindParam(":podate", $data["podate"], PDO::PARAM_STR);
			$stmt->bindParam(":postatus", $data["postatus"], PDO::PARAM_STR);
			$stmt->bindParam(":ponumber", $purchaseid[0]['gen_id'], PDO::PARAM_STR);
			// $stmt->bindParam(":ponumber", $pocode, PDO::PARAM_STR);
			$stmt->bindParam(":orderedby", $data["orderedby"], PDO::PARAM_STR);
			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":preparedby", $data["preparedby"], PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);	
            $stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
            $stmt->bindParam(":discount", $data["discount"], PDO::PARAM_STR);
            $stmt->bindParam(":netamount", $data["netamount"], PDO::PARAM_STR);	
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	

			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO purchaseitems(ponumber, qty, price, tamount, itemid) VALUES (:ponumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":ponumber", $purchaseid[0]['gen_id'], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":price", $product->price, PDO::PARAM_STR);
				$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
				$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
				$items->execute();
			}

		    $pdo->commit();
		    return $pocode;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

    // Update EXISTING RECORD
	static public function mdlEditPurchaseOrder($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // PO # not included
			$stmt = $pdo->prepare("UPDATE purchaseorder SET suppliercode = :suppliercode, podate = :podate, postatus = :postatus,  orderedby = :orderedby, machineid = :machineid, preparedby = :preparedby, remarks = :remarks, amount = :amount, discount = :discount, netamount = :netamount, productlist = :productlist WHERE ponumber = :ponumber");

			$stmt->bindParam(":suppliercode", $data["suppliercode"], PDO::PARAM_STR);
			$stmt->bindParam(":podate", $data["podate"], PDO::PARAM_STR);
			$stmt->bindParam(":postatus", $data["postatus"], PDO::PARAM_STR);
			$stmt->bindParam(":ponumber", $data["ponumber"], PDO::PARAM_STR);
			$stmt->bindParam(":orderedby", $data["orderedby"], PDO::PARAM_STR);
			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":preparedby", $data["preparedby"], PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);	
            $stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
            $stmt->bindParam(":discount", $data["discount"], PDO::PARAM_STR);
            $stmt->bindParam(":netamount", $data["netamount"], PDO::PARAM_STR);	
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			// Delete existing Purchase Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM purchaseitems WHERE ponumber = :ponumber");
		    $delete_items -> bindParam(":ponumber", $data["ponumber"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Purchase Items
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO purchaseitems(ponumber, qty, price, tamount, itemid) VALUES (:ponumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":ponumber", $data["ponumber"], PDO::PARAM_STR);
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

    // Get PO Details
	// static public function mdlShowPurchaseOrder($field, $ponumber){
	// 	$stmt = (new Connection)->connect()->prepare("SELECT * FROM purchaseorder WHERE $field = :$ponumber");
	// 	$stmt -> bindParam(":".$ponumber, $ponumber, PDO::PARAM_STR);
	// 	$stmt -> execute();
	// 	return $stmt -> fetch();
	// 	$stmt -> close();
	// 	$stmt = null;
	// }

	static public function mdlShowPurchaseOrder($ponumber){
		$stmt = (new Connection)->connect()->prepare("SELECT IFNULL(a.machinedesc,'') AS machinedesc,IFNULL(a.machabbr,'') AS machabbr,a.machineid,IFNULL(e.buildingname,'') AS buildingname, b.name,b.address,c.suppliercode,c.podate,c.postatus,c.ponumber,c.orderedby,CONCAT(d.lname,', ',d.fname) AS order_by,c.remarks,c.amount,c.discount,c.netamount,c.preparedby,c.productlist FROM building AS e INNER JOIN machine AS a ON (e.buildingcode = a.buildingcode) RIGHT JOIN purchaseorder AS c ON (a.machineid = c.machineid) INNER JOIN supplier AS b ON (c.suppliercode = b.suppliercode) INNER JOIN employees AS d ON (d.empid = c.orderedby) WHERE (c.ponumber = '$ponumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

    // Get PO Details - For Incoming
	static public function mdlShowPurchaseOrderForIncoming($ponumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.ponumber,a.suppliercode,a.productlist,IFNULL(b.machinedesc,'') AS machinedesc,c.name FROM purchaseorder AS a LEFT JOIN machine AS b ON (a.machineid = b.machineid) INNER JOIN supplier AS c ON (a.suppliercode = c.suppliercode) WHERE ponumber = :$ponumber");
		$stmt -> bindParam(":".$ponumber, $ponumber, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}		

    // Cancel PO
	static public function mdlCancelPurchaseOrder($field, $ponumber){
		$stmt = (new Connection)->connect()->prepare("UPDATE purchaseorder SET postatus = 'Cancelled' WHERE $field = :$ponumber");
		$stmt -> bindParam(":".$ponumber, $ponumber, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

    // Close PO
	static public function mdlClosePurchaseOrder($field, $ponumber){
		$stmt = (new Connection)->connect()->prepare("UPDATE purchaseorder SET postatus = 'Closed' WHERE $field = :$ponumber");
		$stmt -> bindParam(":".$ponumber, $ponumber, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}		

    // GET Purchase Order Items
	static public function mdlShowPurchaseOrderItems($ponumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas1 FROM purchaseitems AS a INNER JOIN items AS b ON (a.itemid = b.itemid) WHERE (a.ponumber = '$ponumber')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

    // Purchase | Incoming Qty Comparison
	static public function mdlShowPurchaseIncoming($ponumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.itemid,'Purchase' AS tdetails,a.pdesc,SUM(c.qty) AS ttl_qty FROM items AS a INNER JOIN purchaseitems AS c ON (a.itemid = c.itemid) WHERE (c.ponumber = '$ponumber') GROUP BY a.itemid,tdetails UNION ALL SELECT a.itemid,'Incoming' AS tdetails,a.pdesc,SUM(c.qty) AS ttl_qty FROM items AS a INNER JOIN incomingitems AS c ON (a.itemid = c.itemid) WHERE (c.ponumber = '$ponumber') GROUP BY a.itemid,tdetails ORDER BY itemid");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowPurchaseOrderTransactionList($machineid, $start_date, $end_date, $suppliercode, $postatus){
		if ($machineid != ''){
			$machine = " AND (a.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($suppliercode != ''){
			$supplier = " AND (a.suppliercode = '$suppliercode')";
		}else{
			$supplier = "";
		}	

		if ($postatus != ''){
			if ($postatus == 'Pending | Partial'){
				$status = " AND ((a.postatus = 'Pending') OR (a.postatus = 'Partial'))";
			}else{
				$status = " AND (a.postatus = '$postatus')";
			}
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.podate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.ponumber != '')" . $machine . $supplier . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT a.podate,b.name,a.ponumber,c.machinedesc,a.postatus,a.netamount FROM purchaseorder AS a INNER JOIN supplier AS b ON (a.suppliercode = b.suppliercode) LEFT JOIN machine AS c ON (a.machineid = c.machineid) $whereClause");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowPurchaseReport($machineid, $start_date, $end_date, $categorycode, $suppliercode, $orderedby, $postatus, $reptype){
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

		if ($suppliercode != ''){
			$supplier_code = " AND (c.suppliercode = '$suppliercode')";
		}else{
			$supplier_code = "";
		}	

		if ($orderedby != ''){
			$ordered_by = " AND (c.orderedby = '$orderedby')";
		}else{
			$ordered_by = "";
		}		

		if ($postatus != ''){
			$po_status = " AND (c.postatus = '$postatus')";
		}else{
			$po_status = "";
		}	

		if(!empty($end_date)){
			$dates = " AND (c.podate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.ponumber != '')" . $machine . $po_status . $dates . $supplier_code . $ordered_by . $category_code;
        
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM category as a INNER JOIN items as b ON (a.categorycode = b.categorycode) INNER JOIN purchaseitems as d ON (b.itemid = d.itemid) INNER JOIN purchaseorder as c ON (c.ponumber = d.ponumber) INNER JOIN supplier as s ON (c.suppliercode = s.suppliercode) INNER JOIN employees as i ON (c.preparedby = i.empid) INNER JOIN employees as j ON (c.orderedby = j.empid) $whereClause GROUP BY a.catdescription WITH ROLLUP");
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,b.pdesc as prodname,b.meas2,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM category as a INNER JOIN items as b ON (a.categorycode = b.categorycode) INNER JOIN purchaseitems AS d ON (b.itemid = d.itemid) INNER JOIN purchaseorder as c ON (c.ponumber = d.ponumber) INNER JOIN supplier as s ON (c.suppliercode = s.suppliercode) INNER JOIN employees as i ON (c.preparedby = i.empid) INNER JOIN employees as j ON (c.orderedby = j.empid) $whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT c.id,c.podate, s.name, c.ponumber, c.postatus, IFNULL(g.machabbr,'') AS machabbr, IFNULL(g.machinedesc,'') AS machinedesc, CONCAT(i.lname,', ',i.fname) as purchaser, CONCAT(j.lname,', ',j.fname) as ordername,b.pdesc as prodname,b.meas2,SUM(d.qty) AS qty,d.price,SUM(d.tamount) AS tamount FROM category as a INNER JOIN items as b ON (a.categorycode = b.categorycode) INNER JOIN purchaseitems AS d ON (b.itemid = d.itemid) INNER JOIN purchaseorder as c ON (c.ponumber = d.ponumber) INNER JOIN supplier as s ON (c.suppliercode = s.suppliercode) LEFT JOIN machine as g ON (c.machineid = g.machineid) INNER JOIN employees as i ON (c.preparedby = i.empid) INNER JOIN employees as j ON (c.orderedby = j.empid) $whereClause GROUP BY c.id,prodname WITH ROLLUP");
		} elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,b.pdesc AS prodname,b.meas2,SUM(d.qty) as qty,SUM(d.tamount) as tamount FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN purchaseorder AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.preparedby = i.empid) INNER JOIN  purchaseitems AS d ON (c.ponumber = d.ponumber) INNER JOIN supplier as s ON (c.suppliercode = s.suppliercode) INNER JOIN items AS b ON (d.itemid = b.itemid) INNER JOIN category AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.orderedby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    } elseif ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,c.reqstatus,c.reqdate,c.shift,CONCAT(i.lname,', ',i.fname) as name,CONCAT(j.lname,', ',j.fname) as reqname,b.pdesc AS prodname,b.meas1,SUM(d.qty) as qty FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN stockout AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  stockoutitems AS d ON (c.reqnumber = d.reqnumber) INNER JOIN items AS b ON (d.itemid = b.itemid) INNER JOIN category AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.requestby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    } elseif ($reptype == 6){
			$stmt = (new Connection)->connect()->prepare("SELECT s.name,b.pdesc AS prodname,b.meas2,SUM(d.qty) as qty,SUM(d.tamount) as tamount FROM purchaseorder AS c INNER JOIN employees AS i ON (c.preparedby = i.empid) INNER JOIN  purchaseitems AS d ON (c.ponumber = d.ponumber) INNER JOIN supplier as s ON (c.suppliercode = s.suppliercode) INNER JOIN items AS b ON (d.itemid = b.itemid) INNER JOIN category AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.orderedby = j.empid) $whereClause GROUP BY s.name, prodname WITH ROLLUP");
	    } elseif ($reptype == 7){
			$stmt = (new Connection)->connect()->prepare("SELECT s.name, c.id,c.podate, c.ponumber, c.postatus, IFNULL(g.machabbr,'') AS machabbr, IFNULL(g.machinedesc,'') AS machinedesc, CONCAT(i.lname,', ',i.fname) as purchaser, CONCAT(j.lname,', ',j.fname) as ordername,b.pdesc as prodname,b.meas2,SUM(d.qty) AS qty,d.price,SUM(d.tamount) AS tamount FROM category as a INNER JOIN items as b ON (a.categorycode = b.categorycode) INNER JOIN purchaseitems AS d ON (b.itemid = d.itemid) INNER JOIN purchaseorder as c ON (c.ponumber = d.ponumber) INNER JOIN supplier as s ON (c.suppliercode = s.suppliercode) LEFT JOIN machine as g ON (c.machineid = g.machineid) INNER JOIN employees as i ON (c.preparedby = i.empid) INNER JOIN employees as j ON (c.orderedby = j.empid) $whereClause GROUP BY s.name,c.id,prodname WITH ROLLUP");
	    } 

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}		
}