<?php
require_once "connection.php";
class ModelIncoming{
	static public function mdlAddIncoming($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $incoming_id = $pdo->prepare("SELECT CONCAT('SD', LPAD((count(id)+1),5,'0')) as gen_id  FROM incoming FOR UPDATE");

            $incoming_id->execute();
		    $incomingid = $incoming_id -> fetchAll(PDO::FETCH_ASSOC);
			$delcode = $incomingid[0]['gen_id'];

			$stmt = $pdo->prepare("INSERT INTO incoming(ponumber, deldate, delstatus, inctype, iscode, delnumber, checkedby, deliveredby, postedby, remarks, amount, discount, netamount, productlist) VALUES (:ponumber, :deldate, :delstatus, :inctype, :iscode, :delnumber, :checkedby, :deliveredby, :postedby, :remarks, :amount, :discount, :netamount, :productlist)");

			$stmt->bindParam(":ponumber", $data["ponumber"], PDO::PARAM_STR);
			$stmt->bindParam(":deldate", $data["deldate"], PDO::PARAM_STR);
			$stmt->bindParam(":delstatus", $data["delstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":inctype", $data["inctype"], PDO::PARAM_STR);
			$stmt->bindParam(":iscode", $data["iscode"], PDO::PARAM_STR);
			$stmt->bindParam(":delnumber", $incomingid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":checkedby", $data["checkedby"], PDO::PARAM_STR);
			$stmt->bindParam(":deliveredby", $data["deliveredby"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);	
            $stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
            $stmt->bindParam(":discount", $data["discount"], PDO::PARAM_STR);
            $stmt->bindParam(":netamount", $data["netamount"], PDO::PARAM_STR);	
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
            // for ($x = 0; $x <= 20000; $x++) {
			$stmt->execute();
		    // }

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO incomingitems(delnumber, ponumber, qty, price, tamount, itemid) VALUES (:delnumber, :ponumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":delnumber", $incomingid[0]['gen_id'], PDO::PARAM_STR);
				$items->bindParam(":ponumber", $data["ponumber"], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":price", $product->price, PDO::PARAM_STR);
				$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
				$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
				$items->execute();
			}		

            $purchase_number = $data["ponumber"]; 

            // Match Purchase Incoming # of Items ------------

			// $numItems = $pdo->prepare("SELECT itemid FROM purchaseitems WHERE ponumber = '$purchase_number' AND NOT EXISTS (SELECT incomingitems.itemid FROM incoming INNER JOIN incomingitems ON (incoming.delnumber = incomingitems.delnumber) WHERE (incoming.delstatus != 'Cancelled') AND (incomingitems.ponumber = '$purchase_number') AND (purchaseitems.itemid = incomingitems.itemid))");

			$numItems = $pdo->prepare("SELECT itemid FROM purchaseitems WHERE ponumber = '$purchase_number' AND NOT EXISTS (SELECT itemid FROM incomingitems WHERE ponumber = '$purchase_number' AND purchaseitems.itemid = incomingitems.itemid)");
			$numItems -> execute();	
			$num_items = $numItems -> fetchAll(PDO::FETCH_ASSOC);

			// Match Purchase Incoming # of Qty ------------------

			// $numQty = $pdo->prepare("SELECT a.itemid,a.qty,SUM(b.qty) as incqty FROM purchaseitems AS a,incomingitems AS b WHERE (b.ponumber = '$purchase_number') AND (a.itemid = b.itemid) AND (a.ponumber = b.ponumber) GROUP BY a.itemid,a.qty HAVING a.qty > SUM(b.qty)");

			$numQty = $pdo->prepare("SELECT a.itemid,a.qty,SUM(b.qty) as incqty FROM purchaseitems AS a, incomingitems AS b, incoming AS c WHERE (b.ponumber = '$purchase_number') AND (a.itemid = b.itemid) AND (a.ponumber = b.ponumber) AND (b.delnumber = c.delnumber) AND (c.delstatus != 'Cancelled') GROUP BY a.itemid,a.qty HAVING a.qty > SUM(b.qty)");

		    $numQty -> execute();
		    $num_qty = $numQty -> fetchAll(PDO::FETCH_ASSOC);

		    if ((count($num_items) > 0)||(count($num_qty) > 0)){
		    	$po_status = 'Partial';
		    }else{
		    	$po_status = 'Completed';
		    }

		    $updatePO = $pdo->prepare("UPDATE purchaseorder SET postatus = '$po_status' WHERE ponumber = :ponumber");
		    $updatePO->bindParam(":ponumber", $data["ponumber"], PDO::PARAM_STR);	
		    $updatePO->execute();				

		    $pdo->commit();
		    return $delcode;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

    // Update EXISTING RECORD
	static public function mdlEditIncoming($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Incoming # not included
			$stmt = $pdo->prepare("UPDATE incoming SET deldate = :deldate, delstatus = :delstatus, inctype = :inctype, iscode = :iscode, delnumber = :delnumber, checkedby = :checkedby, deliveredby = :deliveredby, postedby = :postedby, remarks = :remarks, amount = :amount, discount = :discount, netamount = :netamount, productlist = :productlist WHERE delnumber = :delnumber");

			$stmt->bindParam(":deldate", $data["deldate"], PDO::PARAM_STR);
			$stmt->bindParam(":delstatus", $data["delstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":inctype", $data["inctype"], PDO::PARAM_STR);
			$stmt->bindParam(":iscode", $data["iscode"], PDO::PARAM_STR);
			$stmt->bindParam(":delnumber", $data["delnumber"], PDO::PARAM_STR);
			$stmt->bindParam(":checkedby", $data["checkedby"], PDO::PARAM_STR);
			$stmt->bindParam(":deliveredby", $data["deliveredby"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);	
            $stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
            $stmt->bindParam(":discount", $data["discount"], PDO::PARAM_STR);
            $stmt->bindParam(":netamount", $data["netamount"], PDO::PARAM_STR);	
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);
			$stmt->execute();

			// Delete existing Incoming Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM incomingitems WHERE delnumber = :delnumber");
		    $delete_items -> bindParam(":delnumber", $data["delnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Incoming Items
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO incomingitems(delnumber, ponumber, qty, price, tamount, itemid) VALUES (:delnumber, :ponumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":delnumber", $data["delnumber"], PDO::PARAM_STR);
				$items->bindParam(":ponumber", $data["ponumber"], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":price", $product->price, PDO::PARAM_STR);
				$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
				$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
				$items->execute();
			}

            $purchase_number = $data["ponumber"]; 
            // Match Purchase Incoming # of Items
			$numItems = $pdo->prepare("SELECT itemid FROM purchaseitems WHERE ponumber = '$purchase_number' AND NOT EXISTS (SELECT itemid FROM incomingitems WHERE ponumber = '$purchase_number' AND purchaseitems.itemid = incomingitems.itemid)");
			$numItems -> execute();	
			$num_items = $numItems -> fetchAll(PDO::FETCH_ASSOC);

			// Match Purchase Incoming # of Qty	
			$numQty = $pdo->prepare("SELECT a.itemid,a.qty,SUM(b.qty) as incqty FROM purchaseitems AS a,incomingitems AS b WHERE (b.ponumber = '$purchase_number') AND (a.itemid = b.itemid) AND (a.ponumber = b.ponumber) GROUP BY a.itemid,a.qty HAVING a.qty > SUM(b.qty)");
		    $numQty -> execute();
		    $num_qty = $numQty -> fetchAll(PDO::FETCH_ASSOC);

		    if ((count($num_items) > 0)||(count($num_qty) > 0)){
		    	$po_status = 'Partial';
		    }else{
		    	$po_status = 'Completed';
		    }

		    $updatePO = $pdo->prepare("UPDATE purchaseorder SET postatus = '$po_status' WHERE ponumber = :ponumber");
		    $updatePO->bindParam(":ponumber", $data["ponumber"], PDO::PARAM_STR);	
		    $updatePO->execute();			

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlShowIncomingReport($machineid, $start_date, $end_date, $categorycode, $suppliercode, $delstatus, $reptype){
		if ($machineid != ''){
			$machine = " AND (f.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($categorycode != ''){
			$category_code = " AND (a.categorycode = '$categorycode')";
		}else{
			$category_code = "";
		}		

		if ($suppliercode != ''){
			$supplier_code = " AND (e.suppliercode = '$suppliercode')";
		}else{
			$supplier_code = "";
		}	

		if ($delstatus != ''){
			$del_status = " AND (c.delstatus = '$delstatus')";
		}else{
			$del_status = "";
		}	

		if(!empty($end_date)){
			$dates = " AND (c.deldate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.delnumber != '')" . $machine . $del_status . $dates . $supplier_code . $category_code;
        
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM category as a INNER JOIN items as b ON (a.categorycode = b.categorycode) INNER JOIN incomingitems as d ON (b.itemid = d.itemid) INNER JOIN incoming as c ON (c.delnumber = d.delnumber) INNER JOIN purchaseorder as f ON (c.ponumber = f.ponumber) INNER JOIN supplier as e ON (f.suppliercode = e.suppliercode) $whereClause GROUP BY a.catdescription WITH ROLLUP");
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,b.pdesc as prodname,b.meas1,SUM(d.qty) as total_qty,SUM(d.tamount) as total_amount FROM category as a INNER JOIN items as b ON (a.categorycode = b.categorycode) INNER JOIN incomingitems AS d ON (b.itemid = d.itemid) INNER JOIN incoming as c ON (c.delnumber = d.delnumber) INNER JOIN purchaseorder as f ON (c.ponumber = f.ponumber) INNER JOIN supplier as e ON (f.suppliercode = e.suppliercode) $whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT c.id,c.deldate,c.delnumber, c.delstatus,IFNULL(e.name,'') as name,b.pdesc as prodname,b.meas1,d.qty,d.price,SUM(d.tamount) as tamount FROM category as a INNER JOIN items as b ON (a.categorycode = b.categorycode) INNER JOIN incomingitems AS d ON (b.itemid = d.itemid) INNER JOIN incoming as c ON (c.delnumber = d.delnumber) INNER JOIN purchaseorder as f ON (c.ponumber = f.ponumber) INNER JOIN supplier as e ON (f.suppliercode = e.suppliercode) $whereClause GROUP BY c.id,prodname WITH ROLLUP");
		} elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,h.buildingname,c.delstatus,c.deldate,e.name,b.pdesc AS prodname,b.meas1,d.qty,d.price,SUM(d.tamount) AS tamount FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN purchaseorder AS f ON (g.machineid = f.machineid) INNER JOIN supplier AS e ON (f.suppliercode = e.suppliercode) INNER JOIN incoming AS c ON (f.ponumber = c.ponumber) INNER JOIN incomingitems AS d ON (c.delnumber = d.delnumber) INNER JOIN items AS b ON (d.itemid = b.itemid) INNER JOIN category AS a ON (a.categorycode = b.categorycode) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
	    }

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}		

    // Get Incoming Details
	static public function mdlShowIncoming($delnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.ponumber,a.deldate,a.delstatus,a.inctype,a.iscode,a.delnumber,a.checkedby,a.deliveredby,a.postedby,a.remarks,a.amount,a.discount,a.netamount,a.productlist,b.orderedby,c.name,c.address,IFNULL(d.machinedesc,'') AS machinedesc,CONCAT(e.lname,', ',e.fname) AS check_by,d.machineid FROM incoming AS a INNER JOIN purchaseorder AS b ON (a.ponumber = b.ponumber) INNER JOIN supplier AS c ON (b.suppliercode = c.suppliercode) LEFT JOIN machine AS d ON (b.machineid = d.machineid) INNER JOIN employees AS e ON (a.checkedby = e.empid) WHERE a.delnumber = :$delnumber");
		$stmt -> bindParam(":".$delnumber, $delnumber, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}		

    // Cancel Incoming
	// static public function mdlCancelIncoming($field, $delnumber){
	// 	$stmt = (new Connection)->connect()->prepare("UPDATE incoming SET delstatus = 'Cancelled' WHERE $field = :$delnumber");
	// 	$stmt -> bindParam(":".$delnumber, $delnumber, PDO::PARAM_STR);
	// 	$stmt -> execute();
	// 	return $stmt -> fetch();
	// 	$stmt -> close();
	// 	$stmt = null;
	// }

	static public function mdlCancelIncoming($ponumber, $delnumber){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE incoming SET delstatus = 'Cancelled' WHERE delnumber = :$delnumber");
			$stmt -> bindParam(":".$delnumber, $delnumber, PDO::PARAM_STR);
			$stmt -> execute();

            $purchase_number = $ponumber; 
            // Match Purchase Incoming # of Items
			$numItems = $pdo->prepare("SELECT itemid FROM purchaseitems WHERE ponumber = '$purchase_number' AND NOT EXISTS (SELECT incomingitems.itemid FROM incoming INNER JOIN incomingitems ON (incoming.delnumber = incomingitems.delnumber) WHERE (incoming.delstatus != 'Cancelled') AND (incomingitems.ponumber = '$purchase_number') AND (purchaseitems.itemid = incomingitems.itemid))");
			$numItems -> execute();	
			$num_items = $numItems -> fetchAll(PDO::FETCH_ASSOC);

			// Match Purchase Incoming # of Qty	
			$numQty = $pdo->prepare("SELECT a.itemid,a.qty,SUM(b.qty) as incqty FROM purchaseitems AS a, incomingitems AS b, incoming AS c WHERE (b.ponumber = '$purchase_number') AND (a.itemid = b.itemid) AND (a.ponumber = b.ponumber) AND (b.delnumber = c.delnumber) AND (c.delstatus != 'Cancelled') GROUP BY a.itemid,a.qty HAVING a.qty > SUM(b.qty)");
		    $numQty -> execute();
		    $num_qty = $numQty -> fetchAll(PDO::FETCH_ASSOC);

		    if ((count($num_items) > 0)||(count($num_qty) > 0)){
		    	$po_status = 'Partial';
		    }else{
		    	$po_status = 'Completed';
		    }

		    $updatePO = $pdo->prepare("UPDATE purchaseorder SET postatus = '$po_status' WHERE ponumber = :ponumber");
		    $updatePO->bindParam(":ponumber", $purchase_number, PDO::PARAM_STR);	
		    $updatePO->execute();			

			$pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;				
	}			

    // GET Purchase Order Items
	static public function mdlShowIncomingItems($delnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas1 FROM incomingitems AS a INNER JOIN items AS b ON (a.itemid = b.itemid) WHERE (a.delnumber = '$delnumber')");
		// $stmt -> bindParam(":".$ponumber, $ponumber, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowIncomingTransactionList($machineid, $start_date, $end_date, $suppliercode, $delstatus){
        if ($machineid != ''){
			$machine = " AND (b.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($suppliercode != ''){
			$supplier = " AND (b.suppliercode = '$suppliercode')";
		}else{
			$supplier = "";
		}	

		if ($delstatus != ''){
			$status = " AND (a.delstatus = '$delstatus')";
		}else{
			$status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.deldate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (a.ponumber != '')" . $machine . $supplier . $status . $dates;

		$stmt = (new Connection)->connect()->prepare("SELECT a.deldate,c.name,a.delnumber,IFNULL(d.machinedesc,'') AS machinedesc,a.delstatus,a.netamount FROM incoming AS a INNER JOIN purchaseorder AS b ON (a.ponumber = b.ponumber) INNER JOIN supplier AS c ON (b.suppliercode = c.suppliercode) LEFT JOIN machine AS d ON (b.machineid = d.machineid) $whereClause");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
}