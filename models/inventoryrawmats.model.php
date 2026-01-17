<?php
require_once "connection.php";
class ModelInventoryRawmats{
	static public function mdlAddInventoryRawmats($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $inventory_id = $pdo->prepare("SELECT CONCAT('RI', LPAD((count(id)+1),5,'0')) as gen_id FROM inventoryrawmats");

            $inventory_id->execute();
		    $inventoryid = $inventory_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO inventoryrawmats(countedby, invstatus, invdate, invnumber, postedby, remarks, productlist) VALUES (:countedby, :invstatus, :invdate, :invnumber, :postedby, :remarks, :productlist)");

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
				$items = $pdo->prepare("INSERT INTO inventoryrawmatsitems(invnumber, qty, price, tamount, itemid) VALUES (:invnumber, :qty, :price, :tamount, :itemid)");

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

	static public function mdlEditInventoryRawmats($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			$stmt = $pdo->prepare("UPDATE inventoryrawmats SET countedby = :countedby, invstatus = :invstatus, invdate = :invdate, invnumber = :invnumber, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE invnumber = :invnumber");

			$stmt->bindParam(":countedby", $data["countedby"], PDO::PARAM_STR);
			$stmt->bindParam(":invstatus", $data["invstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":invdate", $data["invdate"], PDO::PARAM_STR);
			$stmt->bindParam(":invnumber", $data["invnumber"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			// Delete existing InventoryRawmats Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM inventoryrawmatsitems WHERE invnumber = :invnumber");
		    $delete_items -> bindParam(":invnumber", $data["invnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO inventoryrawmatsitems(invnumber, qty, price, tamount, itemid) VALUES (:invnumber, :qty, :price, :tamount, :itemid)");

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

	static public function mdlShowInventoryRawmatsTransactionList($start_date, $end_date, $empid, $invstatus){
		if ($empid != ''){
			$countedby = " AND (a.countedby = '$empid')";
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

		$stmt = (new Connection)->connect()->prepare("SELECT a.invdate,CONCAT(b.lname,', ',b.fname) AS counted_by,a.invnumber,a.invstatus,SUM(d.tamount) as total_amount FROM inventoryrawmats AS a INNER JOIN employees AS b ON (a.countedby = b.empid) INNER JOIN inventoryrawmatsitems AS d ON (a.invnumber = d.invnumber) $whereClause GROUP BY a.invnumber");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
	
	static public function mdlShowInventoryRawmats($invnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT CONCAT(b.lname,', ',b.fname) as counted_by,c.countedby,c.invdate,c.invnumber,c.invstatus,c.remarks,c.postedby,c.productlist FROM inventoryrawmats AS c INNER JOIN employees AS b ON (c.countedby = b.empid) WHERE (c.invnumber = '$invnumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET InventoryRawmats Items
	static public function mdlShowInventoryRawmatsItems($invnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT CONCAT(b.pdesc,' (',UPPER(b.meas2),')') AS prodname,a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2 FROM inventoryrawmatsitems AS a INNER JOIN rawmats AS b ON (a.itemid = b.itemid) WHERE (a.invnumber = '$invnumber') ORDER BY prodname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
}