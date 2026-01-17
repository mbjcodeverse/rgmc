<?php
require_once "connection.php";
class ModelProdfin{
	static public function mdlAddProdfin($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $prodfin_id = $pdo->prepare("SELECT CONCAT('PF', LPAD((count(id)+1),7,'0')) as gen_id FROM prodfin");

            $prodfin_id->execute();
		    $prodfinid = $prodfin_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO prodfin(machineid, operatedby, prodstatus, proddate, prodnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :operatedby, :prodstatus, :proddate, :prodnumber, :shift, :postedby, :remarks, :productlist)");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":operatedby", $data["operatedby"], PDO::PARAM_STR);
			$stmt->bindParam(":prodstatus", $data["prodstatus"], PDO::PARAM_STR);
			$stmt->bindParam(":proddate", $data["proddate"], PDO::PARAM_STR);
			$stmt->bindParam(":prodnumber", $prodfinid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO prodfinitems(prodnumber, qty, price, tamount, itemid) VALUES (:prodnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":prodnumber", $prodfinid[0]['gen_id'], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":price", $product->price, PDO::PARAM_STR);
				$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
				$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
				$items->execute();
			}

			if (!empty($data["wastelist"])) {	// add waste if there is are entries
				$debrisby_id = $pdo->prepare("SELECT CONCAT('D', LPAD((count(id)+1),7,'0')) as gen_id FROM debris");

				$debrisby_id->execute();
				$debrisbyid = $debrisby_id -> fetchAll(PDO::FETCH_ASSOC);

				$wstmt = $pdo->prepare("INSERT INTO debris(machineid, debrisby, debstatus, debdate, debnumber, prodnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :debrisby, :debstatus, :debdate, :debnumber, :prodnumber, :shift, :postedby, :remarks, :productlist)");

				$wstmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
				$wstmt->bindParam(":debrisby", $data["operatedby"], PDO::PARAM_STR);
				$wstmt->bindParam(":debstatus", $data["prodstatus"], PDO::PARAM_STR);
				$wstmt->bindParam(":debdate", $data["proddate"], PDO::PARAM_STR);
				$wstmt->bindParam(":debnumber", $debrisbyid[0]['gen_id'], PDO::PARAM_STR);
				$wstmt->bindParam(":prodnumber", $prodfinid[0]['gen_id'], PDO::PARAM_STR);
				$wstmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
				$wstmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
				$wstmt->bindParam(":remarks", $data["wremarks"], PDO::PARAM_STR);
				$wstmt->bindParam(":productlist", $data["wastelist"], PDO::PARAM_STR);	
				$wstmt->execute();

				$witemsList = json_decode($data["wastelist"]);
				foreach($witemsList as $product){
					$witems = $pdo->prepare("INSERT INTO debrisitems(debnumber, qty, price, tamount, itemid) VALUES (:debnumber, :qty, :price, :tamount, :itemid)");

					$witems->bindParam(":debnumber", $debrisbyid[0]['gen_id'], PDO::PARAM_STR);
					$witems->bindParam(":qty", $product->qty, PDO::PARAM_STR);
					$witems->bindParam(":price", $product->price, PDO::PARAM_STR);
					$witems->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
					$witems->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
					$witems->execute();
				}
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

	static public function mdlEditProdfin($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			$stmt = $pdo->prepare("UPDATE prodfin SET machineid = :machineid, operatedby = :operatedby, prodstatus = :prodstatus,  proddate = :proddate, shift = :shift, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE prodnumber = :prodnumber");

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

			// Delete existing Prodfin Items
		    $delete_items = (new Connection)->connect()->prepare("DELETE FROM prodfinitems WHERE prodnumber = :prodnumber");
		    $delete_items -> bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
		    $delete_items->execute();

		    // Insert updated/new Prodfin Items
			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO prodfinitems(prodnumber, qty, price, tamount, itemid) VALUES (:prodnumber, :qty, :price, :tamount, :itemid)");

				$items->bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":price", $product->price, PDO::PARAM_STR);
				$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
				$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
				$items->execute();
			}

			// check existence of encoded waste
			$prodnumber_exist = (new Connection)->connect()->prepare("SELECT * FROM debris WHERE prodnumber = :prodnumber");
		    $prodnumber_exist -> bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
		    $prodnumber_exist->execute();
			$has_prodid = $prodnumber_exist -> fetchAll(PDO::FETCH_ASSOC);
			// if waste items already encoded
			if (count($has_prodid) > 0){		
				if (!empty($data["wastelist"])) {
					// if ($data["debstatus"] != 'Cancelled'){
					// 	$debstatus = 'Posted';
					// }else{
					// 	$debstatus = 'Posted';
					// }
					$wstmt = $pdo->prepare("UPDATE debris SET 
														  machineid = :machineid,
														  debrisby = :debrisby,
														  debstatus = :debstatus,
														  debdate = :debdate,
														  shift = :shift,
														  postedby = :postedby,
														  remarks = :remarks,
														  productlist = :productlist
													WHERE debnumber = :debnumber");
					$debstatus = 'Posted';
					$wstmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
					$wstmt->bindParam(":debrisby", $data["operatedby"], PDO::PARAM_STR);
					$wstmt->bindParam(":debstatus", $debstatus, PDO::PARAM_STR);
					$wstmt->bindParam(":debdate", $data["proddate"], PDO::PARAM_STR);
					$wstmt->bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
					$wstmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
					$wstmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
					$wstmt->bindParam(":remarks", $data["wremarks"], PDO::PARAM_STR);
					$wstmt->bindParam(":productlist", $data["wastelist"], PDO::PARAM_STR);	
					$wstmt->execute();
					
					// delete existing waste items
					$delete_witems = (new Connection)->connect()->prepare("DELETE FROM debrisitems WHERE debnumber = :debnumber");
					$delete_witems -> bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
					$delete_witems->execute();

					// insert updated/new waste items
					$witemsList = json_decode($data["wastelist"]);
					foreach($witemsList as $product){
						$witems = $pdo->prepare("INSERT INTO debrisitems(debnumber, qty, price, tamount, itemid) VALUES (:debnumber, :qty, :price, :tamount, :itemid)");

						$witems->bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
						$witems->bindParam(":qty", $product->qty, PDO::PARAM_STR);
						$witems->bindParam(":price", $product->price, PDO::PARAM_STR);
						$witems->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
						$witems->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
						$witems->execute();
					}
				}else{
					// if waste was encoded, but after searching - it was cleared
					// Cancel waste entry...
					$debstatus = 'Cancelled';
					$cancel_waste = $pdo->prepare("UPDATE debris SET 
														  		 debstatus = :debstatus
														   WHERE debnumber = :debnumber");
					$cancel_waste->bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
					$cancel_waste->bindParam(":debstatus", $debstatus, PDO::PARAM_STR);
					$cancel_waste->execute();								
				}
			}else{
				// add new waste entries
				$debrisby_id = $pdo->prepare("SELECT CONCAT('D', LPAD((count(id)+1),7,'0')) as gen_id FROM debris");

				$debrisby_id->execute();
				$debrisbyid = $debrisby_id -> fetchAll(PDO::FETCH_ASSOC);

				$wstmt = $pdo->prepare("INSERT INTO debris(machineid, debrisby, debstatus, debdate, debnumber, prodnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :debrisby, :debstatus, :debdate, :debnumber, :prodnumber, :shift, :postedby, :remarks, :productlist)");

				$wstmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
				$wstmt->bindParam(":debrisby", $data["operatedby"], PDO::PARAM_STR);
				$wstmt->bindParam(":debstatus", $data["prodstatus"], PDO::PARAM_STR);
				$wstmt->bindParam(":debdate", $data["proddate"], PDO::PARAM_STR);
				$wstmt->bindParam(":debnumber", $debrisbyid[0]['gen_id'], PDO::PARAM_STR);
				$wstmt->bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
				$wstmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
				$wstmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
				$wstmt->bindParam(":remarks", $data["wremarks"], PDO::PARAM_STR);
				$wstmt->bindParam(":productlist", $data["wastelist"], PDO::PARAM_STR);	
				$wstmt->execute();

				$witemsList = json_decode($data["wastelist"]);
				foreach($witemsList as $product){
					$witems = $pdo->prepare("INSERT INTO debrisitems(debnumber, qty, price, tamount, itemid) VALUES (:debnumber, :qty, :price, :tamount, :itemid)");

					$witems->bindParam(":debnumber", $debrisbyid[0]['gen_id'], PDO::PARAM_STR);
					$witems->bindParam(":qty", $product->qty, PDO::PARAM_STR);
					$witems->bindParam(":price", $product->price, PDO::PARAM_STR);
					$witems->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
					$witems->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
					$witems->execute();
				}
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

	// OPERATOR Production
	static public function mdlCreateProdoperator($data){
		$db = new Connection();
		$pdo = $db->connect();
		$trans_id = null;
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			if ($data["etype"] == "Finished Goods"){
				$prodfin_id = $pdo->prepare("SELECT CONCAT('PF', LPAD((count(id)+1),7,'0')) as gen_id FROM prodfin");

				$prodfin_id->execute();
				$prodfinid = $prodfin_id -> fetchAll(PDO::FETCH_ASSOC);

				$stmt = $pdo->prepare("INSERT INTO prodfin(machineid, operatedby, prodstatus, proddate, prodnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :operatedby, :prodstatus, :proddate, :prodnumber, :shift, :postedby, :remarks, :productlist)");

				$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
				$stmt->bindParam(":operatedby", $data["operatedby"], PDO::PARAM_STR);
				$stmt->bindParam(":prodstatus", $data["prodstatus"], PDO::PARAM_STR);
				$stmt->bindParam(":proddate", $data["proddate"], PDO::PARAM_STR);
				$stmt->bindParam(":prodnumber", $prodfinid[0]['gen_id'], PDO::PARAM_STR);
				$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
				$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
				$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
				$stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
				$stmt->execute();

            	$trans_id = $prodfinid[0]['gen_id'];

				$itemsList = json_decode($data["productlist"]);
				foreach($itemsList as $product){
					$items = $pdo->prepare("INSERT INTO prodfinitems(prodnumber, qty, price, tamount, itemid) VALUES (:prodnumber, :qty, :price, :tamount, :itemid)");

					$items->bindParam(":prodnumber", $prodfinid[0]['gen_id'], PDO::PARAM_STR);
					$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
					$items->bindParam(":price", $product->price, PDO::PARAM_STR);
					$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
					$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
					$items->execute();
				}
			} elseif ($data["etype"] == "Subcomponents"){
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

                $trans_id = $prodcomid[0]['gen_id'];

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
			} elseif ($data["etype"] == "Recycle"){
				$recycle_id = $pdo->prepare("SELECT CONCAT('R', LPAD((count(id)+1),7,'0')) as gen_id FROM recycle");

				$recycle_id->execute();
				$recycleid = $recycle_id -> fetchAll(PDO::FETCH_ASSOC);

				$stmt = $pdo->prepare("INSERT INTO recycle(machineid, recycleby, recstatus, recdate, recnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :recycleby, :recstatus, :recdate, :recnumber, :shift, :postedby, :remarks, :productlist)");

				$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
				$stmt->bindParam(":recycleby", $data["operatedby"], PDO::PARAM_STR);
				$stmt->bindParam(":recstatus", $data["prodstatus"], PDO::PARAM_STR);
				$stmt->bindParam(":recdate", $data["proddate"], PDO::PARAM_STR);
				$stmt->bindParam(":recnumber", $recycleid[0]['gen_id'], PDO::PARAM_STR);
				$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
				$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
				$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
				$stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
				$stmt->execute();

				$trans_id = $recycleid[0]['gen_id'];

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
			}

			if (!empty($data["wastelist"])) {	
				$debrisby_id = $pdo->prepare("SELECT CONCAT('D', LPAD((count(id)+1),7,'0')) as gen_id FROM debris");

				$debrisby_id->execute();
				$debrisbyid = $debrisby_id -> fetchAll(PDO::FETCH_ASSOC);

				$wstmt = $pdo->prepare("INSERT INTO debris(machineid, debrisby, debstatus, debdate, debnumber, prodnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :debrisby, :debstatus, :debdate, :debnumber, :prodnumber, :shift, :postedby, :remarks, :productlist)");

				$wstmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
				$wstmt->bindParam(":debrisby", $data["operatedby"], PDO::PARAM_STR);
				$wstmt->bindParam(":debstatus", $data["prodstatus"], PDO::PARAM_STR);
				$wstmt->bindParam(":debdate", $data["proddate"], PDO::PARAM_STR);
				$wstmt->bindParam(":debnumber", $debrisbyid[0]['gen_id'], PDO::PARAM_STR);
				$wstmt->bindParam(":prodnumber", $trans_id, PDO::PARAM_STR);
				$wstmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
				$wstmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
				$wstmt->bindParam(":remarks", $data["wremarks"], PDO::PARAM_STR);
				$wstmt->bindParam(":productlist", $data["wastelist"], PDO::PARAM_STR);	
				$wstmt->execute();

				$witemsList = json_decode($data["wastelist"]);
				foreach($witemsList as $product){
					$witems = $pdo->prepare("INSERT INTO debrisitems(debnumber, qty, price, tamount, itemid, iclass) VALUES (:debnumber, :qty, :price, :tamount, :itemid, :iclass)");

					$witems->bindParam(":debnumber", $debrisbyid[0]['gen_id'], PDO::PARAM_STR);
					$witems->bindParam(":qty", $product->qty, PDO::PARAM_STR);
					$witems->bindParam(":price", $product->price, PDO::PARAM_STR);
					$witems->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
					$witems->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
					$witems->bindParam(":iclass", $product->iclass, PDO::PARAM_STR);
					$witems->execute();
				}
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

	static public function mdlEditProdoperator($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // Req # not included
			if ($data["etype"] == "Finished Goods"){
				$stmt = $pdo->prepare("UPDATE prodfin SET machineid = :machineid, operatedby = :operatedby, prodstatus = :prodstatus,  proddate = :proddate, shift = :shift, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE prodnumber = :prodnumber");

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

				// Delete existing Prodfin Items
				$delete_items = (new Connection)->connect()->prepare("DELETE FROM prodfinitems WHERE prodnumber = :prodnumber");
				$delete_items -> bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
				$delete_items->execute();

				// Insert updated/new Prodfin Items
				$itemsList = json_decode($data["productlist"]);
				foreach($itemsList as $product){
					$items = $pdo->prepare("INSERT INTO prodfinitems(prodnumber, qty, price, tamount, itemid) VALUES (:prodnumber, :qty, :price, :tamount, :itemid)");

					$items->bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
					$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
					$items->bindParam(":price", $product->price, PDO::PARAM_STR);
					$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
					$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
					$items->execute();
				}
			}elseif ($data["etype"] == "Subcomponents"){
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
			}elseif ($data["etype"] == "Recycle"){
				$stmt = $pdo->prepare("UPDATE recycle SET machineid = :machineid, recycleby = :recycleby, recstatus = :recstatus, recdate = :recdate, shift = :shift, postedby = :postedby, remarks = :remarks, productlist = :productlist WHERE recnumber = :recnumber");

				$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
				$stmt->bindParam(":recycleby", $data["operatedby"], PDO::PARAM_STR);
				$stmt->bindParam(":recstatus", $data["prodstatus"], PDO::PARAM_STR);
				$stmt->bindParam(":recdate", $data["proddate"], PDO::PARAM_STR);
				$stmt->bindParam(":recnumber", $data['prodnumber'], PDO::PARAM_STR);
				$stmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
				$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
				$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
				$stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
				$stmt->execute();

				// Delete existing Recycle Items
				$delete_items = (new Connection)->connect()->prepare("DELETE FROM recycleitems WHERE recnumber = :prodnumber");
				$delete_items -> bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
				$delete_items->execute();

				$itemsList = json_decode($data["productlist"]);
				foreach($itemsList as $product){
					$items = $pdo->prepare("INSERT INTO recycleitems(recnumber, qty, price, tamount, itemid) VALUES (:recnumber, :qty, :price, :tamount, :itemid)");

					$items->bindParam(":recnumber", $data['prodnumber'], PDO::PARAM_STR);
					$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
					$items->bindParam(":price", $product->price, PDO::PARAM_STR);
					$items->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
					$items->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
					$items->execute();
				}
			}

			// check existence of encoded waste
			$prodnumber_exist = (new Connection)->connect()->prepare("SELECT * FROM debris WHERE prodnumber = :prodnumber AND debstatus != 'Cancelled'");
		    $prodnumber_exist -> bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
		    $prodnumber_exist->execute();
			$has_prodid = $prodnumber_exist -> fetch(PDO::FETCH_ASSOC);
			// if waste items already encoded
			// if (count($has_prodid) > 0){	
			if ($has_prodid){	
				if (!empty($data["wastelist"])) {
					$wstmt = $pdo->prepare("UPDATE debris SET 
														  machineid = :machineid,
														  debrisby = :debrisby,
														  debstatus = :debstatus,
														  debdate = :debdate,
														  shift = :shift,
														  postedby = :postedby,
														  remarks = :remarks,
														  productlist = :productlist
													WHERE debnumber = :debnumber");
					$debstatus = 'Posted';
					$wstmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
					$wstmt->bindParam(":debrisby", $data["operatedby"], PDO::PARAM_STR);
					$wstmt->bindParam(":debstatus", $debstatus, PDO::PARAM_STR);
					$wstmt->bindParam(":debdate", $data["proddate"], PDO::PARAM_STR);
					$wstmt->bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
					$wstmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
					$wstmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
					$wstmt->bindParam(":remarks", $data["wremarks"], PDO::PARAM_STR);
					$wstmt->bindParam(":productlist", $data["wastelist"], PDO::PARAM_STR);	
					$wstmt->execute();
					
					// delete existing waste items
					$delete_witems = (new Connection)->connect()->prepare("DELETE FROM debrisitems WHERE debnumber = :debnumber");
					$delete_witems -> bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
					$delete_witems->execute();

					// insert updated/new waste items
					$witemsList = json_decode($data["wastelist"]);
					foreach($witemsList as $product){
						$witems = $pdo->prepare("INSERT INTO debrisitems(debnumber, qty, price, tamount, itemid, iclass) VALUES (:debnumber, :qty, :price, :tamount, :itemid, :iclass)");

						$witems->bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
						$witems->bindParam(":qty", $product->qty, PDO::PARAM_STR);
						$witems->bindParam(":price", $product->price, PDO::PARAM_STR);
						$witems->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
						$witems->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
						$witems->bindParam(":iclass", $product->iclass, PDO::PARAM_STR);
						$witems->execute();
					}
				}else{
					// if waste was encoded, but after searching - it was cleared
					// Cancel waste entry...
					$debstatus = 'Cancelled';
					$cancel_waste = $pdo->prepare("UPDATE debris SET 
														  		 debstatus = :debstatus
														   WHERE debnumber = :debnumber");
					$cancel_waste->bindParam(":debnumber", $data["debnumber"], PDO::PARAM_STR);
					$cancel_waste->bindParam(":debstatus", $debstatus, PDO::PARAM_STR);
					$cancel_waste->execute();								
				}
			}else{
				// add new waste entries
				if (!empty($data["wastelist"])) {
					$debrisby_id = $pdo->prepare("SELECT CONCAT('D', LPAD((count(id)+1),7,'0')) as gen_id FROM debris");

					$debrisby_id->execute();
					$debrisbyid = $debrisby_id -> fetchAll(PDO::FETCH_ASSOC);

					$wstmt = $pdo->prepare("INSERT INTO debris(machineid, debrisby, debstatus, debdate, debnumber, prodnumber, shift, postedby, remarks, productlist) VALUES (:machineid, :debrisby, :debstatus, :debdate, :debnumber, :prodnumber, :shift, :postedby, :remarks, :productlist)");

					$wstmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
					$wstmt->bindParam(":debrisby", $data["operatedby"], PDO::PARAM_STR);
					$wstmt->bindParam(":debstatus", $data["prodstatus"], PDO::PARAM_STR);
					$wstmt->bindParam(":debdate", $data["proddate"], PDO::PARAM_STR);
					$wstmt->bindParam(":debnumber", $debrisbyid[0]['gen_id'], PDO::PARAM_STR);
					$wstmt->bindParam(":prodnumber", $data["prodnumber"], PDO::PARAM_STR);
					$wstmt->bindParam(":shift", $data["shift"], PDO::PARAM_STR);
					$wstmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
					$wstmt->bindParam(":remarks", $data["wremarks"], PDO::PARAM_STR);
					$wstmt->bindParam(":productlist", $data["wastelist"], PDO::PARAM_STR);	
					$wstmt->execute();

					$witemsList = json_decode($data["wastelist"]);
					foreach($witemsList as $product){
						$witems = $pdo->prepare("INSERT INTO debrisitems(debnumber, qty, price, tamount, itemid, iclass) VALUES (:debnumber, :qty, :price, :tamount, :itemid, :iclass)");

						$witems->bindParam(":debnumber", $debrisbyid[0]['gen_id'], PDO::PARAM_STR);
						$witems->bindParam(":qty", $product->qty, PDO::PARAM_STR);
						$witems->bindParam(":price", $product->price, PDO::PARAM_STR);
						$witems->bindParam(":tamount", $product->tamount, PDO::PARAM_STR);
						$witems->bindParam(":itemid", $product->itemid, PDO::PARAM_STR);
						$witems->bindParam(":iclass", $product->iclass, PDO::PARAM_STR);
						$witems->execute();
					}
				}
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
	
	static public function mdlShowProdfinTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus){
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

		$stmt = (new Connection)->connect()->prepare("SELECT
		 							a.proddate,
									CONCAT(b.lname,', ',b.fname) AS operated_by,
									a.prodnumber,shift,
									IFNULL(c.machinedesc,'') AS machinedesc,
									IFNULL(c.machabbr,'') AS machabbr,
									a.prodstatus,SUM(d.tamount) as total_amount
								FROM prodfin AS a
									INNER JOIN employees AS b ON (a.operatedby = b.empid)
									LEFT JOIN machine AS c ON (a.machineid = c.machineid)
									INNER JOIN prodfinitems AS d ON (a.prodnumber = d.prodnumber)
								$whereClause GROUP BY a.prodnumber");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	// FOR PRACTICE
	static public function mdlShowProdoperatorTransactionList($machineid, $start_date, $end_date, $empid, $prodstatus, $etype, $postedby){
		if ($machineid != ''){
			$machine = " AND (a.machineid = '$machineid')";
		}else{
			$machine = "";
		}

		if ($empid != ''){
			$operator = " AND (a.operatedby = '$empid')";
			$rec_operator = " AND (a.recycleby = '$empid')";
		}else{
			$operator = "";
			$rec_operator = "";
		}	

		if ($prodstatus != ''){
			$status = " AND (a.prodstatus = '$prodstatus')";
			$rec_status = " AND (a.recstatus = '$prodstatus')";
		}else{
			$status = "";
			$rec_status = "";
		}

		if(!empty($end_date)){
			$dates = " AND (a.proddate BETWEEN '$start_date' AND '$end_date')";
			$rec_dates = " AND (a.recdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
			$rec_dates = "";
		}	
		
		if ($etype != ''){
			$etype = " HAVING (etype = '$etype')";
		}else{
			$etype = "";
		}

		if ($postedby != ''){
			$postedby = " AND (a.postedby = '$postedby')";
		}else{
			$postedby = "";
		}	

		$whereClause = "WHERE (a.prodnumber != '')" . $machine . $operator . $status . $dates . $postedby;
		$rec_whereClause = "WHERE (a.recnumber != '')" . $machine . $rec_operator . $rec_status . $rec_dates . $postedby;

		$stmt = (new Connection)->connect()->prepare("SELECT
									'Finished Goods' AS etype,
		 							a.proddate,
									CONCAT(b.lname,', ',b.fname) AS operated_by,
									a.prodnumber,shift,
									IFNULL(c.machinedesc,'') AS machinedesc,
									IFNULL(c.machabbr,'') AS machabbr,
									a.prodstatus,SUM(d.tamount) as total_amount
								FROM prodfin AS a
									INNER JOIN employees AS b ON (a.operatedby = b.empid)
									LEFT JOIN machine AS c ON (a.machineid = c.machineid)
									INNER JOIN prodfinitems AS d ON (a.prodnumber = d.prodnumber)
								    $whereClause GROUP BY a.prodnumber $etype
							UNION ALL
									SELECT 'Subcomponents' AS etype,
		 							a.proddate,
									CONCAT(b.lname,', ',b.fname) AS operated_by,
									a.prodnumber,shift,
									IFNULL(c.machinedesc,'') AS machinedesc,
									IFNULL(c.machabbr,'') AS machabbr,
									a.prodstatus,SUM(d.tamount) as total_amount
								FROM prodcom AS a
									INNER JOIN employees AS b ON (a.operatedby = b.empid)
									LEFT JOIN machine AS c ON (a.machineid = c.machineid)
									INNER JOIN prodcomitems AS d ON (a.prodnumber = d.prodnumber)
								    $whereClause GROUP BY a.prodnumber $etype
							UNION ALL
									SELECT 'Recycle' AS etype,
		 							a.recdate AS proddate,
									CONCAT(b.lname,', ',b.fname) AS operated_by,
									a.recnumber AS prodnumber,shift,
									IFNULL(c.machinedesc,'') AS machinedesc,
									IFNULL(c.machabbr,'') AS machabbr,
									a.recstatus AS prodstatus,SUM(d.tamount) as total_amount
								FROM recycle AS a
									INNER JOIN employees AS b ON (a.recycleby = b.empid)
									LEFT JOIN machine AS c ON (a.machineid = c.machineid)
									INNER JOIN recycleitems AS d ON (a.recnumber = d.recnumber)
								    $rec_whereClause GROUP BY a.recnumber $etype");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowProdfinReport($machineid, $start_date, $end_date, $categorycode, $postedby, $operatedby, $prodstatus, $reptype, $shift){
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

		if ($shift != ''){
			$prod_shift = " AND (c.shift = '$shift')";
		}else{
			$prod_shift = "";
		}	

		if(!empty($end_date)){
			$dates = " AND (c.proddate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.prodnumber != '')" . $machine . $req_status . $dates . $posted_by . $request_by . $category_code . $prod_shift;
        
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,SUM(d.qty) as total_qty,SUM(d.qty * b.pweight) as total_weight, SUM(d.tamount) as total_amount FROM categorygoods as a INNER JOIN products as b ON (a.categorycode = b.categorycode) INNER JOIN prodfinitems as d ON (b.itemid = d.itemid) INNER JOIN prodfin as c ON (c.prodnumber = d.prodnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.operatedby = j.empid) $whereClause GROUP BY a.catdescription WITH ROLLUP");
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,b.pdesc as prodname,b.meas2,SUM(d.qty) as total_qty,SUM(d.qty * b.pweight) as total_weight,SUM(d.tamount) as total_amount FROM categorygoods as a INNER JOIN products as b ON (a.categorycode = b.categorycode) INNER JOIN prodfinitems AS d ON (b.itemid = d.itemid) INNER JOIN prodfin as c ON (c.prodnumber = d.prodnumber) INNER JOIN employees as i ON (c.postedby = i.empid) INNER JOIN employees as j ON (c.operatedby = j.empid)$whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	    	
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
							SUM(d.qty) AS qty,
							IFNULL(SUM(wdi.tamount),0.00) AS waste_damage,
							IFNULL(SUM(wdi.qty * b.pweight),0.00) AS wd_weight,
							d.price,
							SUM(d.tamount) AS tamount
						FROM categorygoods AS a
						INNER JOIN products AS b ON a.categorycode = b.categorycode
						INNER JOIN prodfinitems AS d ON b.itemid = d.itemid
						INNER JOIN prodfin AS c ON c.prodnumber = d.prodnumber
						LEFT JOIN debris AS wd ON c.prodnumber = wd.prodnumber
						LEFT JOIN debrisitems AS wdi ON (wd.debnumber = wdi.debnumber)
						LEFT JOIN machine AS g ON c.machineid = g.machineid
						INNER JOIN employees AS i ON c.postedby = i.empid
						INNER JOIN employees AS j ON c.operatedby = j.empid
						$whereClause
						GROUP BY c.id, prodname WITH ROLLUP
					) AS rolled_up
					ORDER BY 
						CASE WHEN rolled_up.id IS NULL AND rolled_up.prodname IS NULL THEN 1 ELSE 0 END,
						rolled_up.proddate ASC,
						rolled_up.id ASC
					");			
		} elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT g.machinedesc,g.machabbr,h.buildingname,b.pdesc AS prodname,b.meas2,SUM(d.qty) as qty,SUM(d.qty * b.pweight) as weight,SUM(d.tamount) as tamount FROM building AS h INNER JOIN machine AS g ON (h.buildingcode = g.buildingcode) INNER JOIN prodfin AS c ON (g.machineid = c.machineid) INNER JOIN employees AS i ON (c.postedby = i.empid) INNER JOIN  prodfinitems AS d ON (c.prodnumber = d.prodnumber) INNER JOIN products AS b ON (d.itemid = b.itemid) INNER JOIN categorygoods AS a ON (a.categorycode = b.categorycode) INNER JOIN employees as j ON (c.operatedby = j.empid) $whereClause GROUP BY g.machinedesc, prodname WITH ROLLUP");
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
										SUM(d.qty) AS qty,
										d.price,
										SUM(d.tamount) AS tamount
									FROM categorygoods AS a
									INNER JOIN products AS b ON a.categorycode = b.categorycode
									INNER JOIN prodfinitems AS d ON b.itemid = d.itemid
									INNER JOIN prodfin AS c ON c.prodnumber = d.prodnumber
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
	    } elseif ($reptype == 6){
			// $stmt = (new Connection)->connect()->prepare("SELECT a.catdescription,
			// 						b.pdesc as prodname,
			// 						b.meas2,SUM(d.qty) as total_qty,
			// 						SUM(d.qty * b.pweight) as total_weight,
			// 						SUM(d.tamount) as total_amount FROM categorygoods as a
			// 							INNER JOIN products as b ON (a.categorycode = b.categorycode)
			// 							INNER JOIN prodfinitems AS d ON (b.itemid = d.itemid)
			// 							INNER JOIN prodfin as c ON (c.prodnumber = d.prodnumber)
			// 							INNER JOIN employees as i ON (c.postedby = i.empid)
			// 							INNER JOIN employees as j ON (c.operatedby = j.empid)
			// 						$whereClause GROUP BY a.catdescription,prodname WITH ROLLUP");	 
	
				$stmt = (new Connection)->connect()->prepare("SELECT CONCAT(e.lname, ', ', e.fname) AS oprname,
										a.catdescription,
										SUM(d.qty) as total_qty,
										SUM(d.tamount) as total_amount FROM categorygoods as a
											INNER JOIN products as b ON (a.categorycode = b.categorycode)
											INNER JOIN prodfinitems AS d ON (b.itemid = d.itemid)
											INNER JOIN prodfin as c ON (c.prodnumber = d.prodnumber)
											INNER JOIN employees as e ON (c.operatedby = e.empid)
											$whereClause GROUP BY oprname,a.catdescription WITH ROLLUP");	
	    } 

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}			
	
	static public function mdlShowProdfin($prodnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT
										a.machinedesc,
										IFNULL(a.machineid,'') AS machineid,
										CONCAT(b.lname,', ',b.fname) as request_by,
										c.operatedby,
										c.proddate,
										c.prodnumber,
										c.shift,
										c.prodstatus,
										c.remarks,
										c.postedby,
										c.productlist,
										d.debnumber,
										d.debstatus,
										d.remarks AS wremarks,
										d.productlist AS wastelist
									FROM machine AS a RIGHT JOIN prodfin AS c ON (a.machineid = c.machineid)
													  LEFT JOIN debris AS d ON (d.prodnumber = c.prodnumber)
													  INNER JOIN employees AS b ON (c.operatedby = b.empid)
									WHERE (c.prodnumber = '$prodnumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET Operator Production
    static public function mdlShowProdoperator($prodnumber,$etype){
		if ($etype == 'Finished Goods'){
			$stmt = (new Connection)->connect()->prepare("SELECT
											a.machinedesc,
											IFNULL(a.machineid,'') AS machineid,
											CONCAT(b.lname,', ',b.fname) as request_by,
											c.operatedby,
											c.proddate,
											c.prodnumber,
											c.shift,
											c.prodstatus,
											c.remarks,
											c.postedby,
											c.productlist,
											d.debnumber,
											d.debstatus,
											d.remarks AS wremarks,
											d.productlist AS wastelist
										FROM machine AS a RIGHT JOIN prodfin AS c ON (a.machineid = c.machineid)
														LEFT JOIN debris AS d ON (d.prodnumber = c.prodnumber)
														INNER JOIN employees AS b ON (c.operatedby = b.empid)
										WHERE (c.prodnumber = '$prodnumber')");
		}elseif ($etype == 'Subcomponents'){
			$stmt = (new Connection)->connect()->prepare("SELECT
											a.machinedesc,
											IFNULL(a.machineid,'') AS machineid,
											CONCAT(b.lname,', ',b.fname) as request_by,
											c.operatedby,
											c.proddate,
											c.prodnumber,
											c.shift,
											c.prodstatus,
											c.remarks,
											c.postedby,
											c.productlist,
											d.debnumber,
											d.debstatus,
											d.remarks AS wremarks,
											d.productlist AS wastelist
										FROM machine AS a RIGHT JOIN prodcom AS c ON (a.machineid = c.machineid)
														LEFT JOIN debris AS d ON (d.prodnumber = c.prodnumber)
														INNER JOIN employees AS b ON (c.operatedby = b.empid)
										WHERE (c.prodnumber = '$prodnumber')");
		}else{
			$stmt = (new Connection)->connect()->prepare("SELECT
											a.machinedesc,
											IFNULL(a.machineid,'') AS machineid,
											CONCAT(b.lname,', ',b.fname) as request_by,
											c.recycleby AS operatedby,
											c.recdate AS proddate,
											c.recnumber AS prodnumber,
											c.shift,
											c.recstatus AS prodstatus,
											c.remarks,
											c.postedby,
											c.productlist,
											d.debnumber,
											d.debstatus,
											d.remarks AS wremarks,
											d.productlist AS wastelist
										FROM machine AS a RIGHT JOIN recycle AS c ON (a.machineid = c.machineid)
														LEFT JOIN debris AS d ON (d.prodnumber = c.recnumber)
														INNER JOIN employees AS b ON (c.recycleby = b.empid)
										WHERE (c.recnumber = '$prodnumber')");			
		} 								

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	// GET Operator Items
	static public function mdlShowProdoperatorItems($prodnumber,$etype){
		if ($etype == 'Finished Goods'){
			$stmt = (new Connection)->connect()->prepare("SELECT
											a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2
										FROM prodfinitems AS a
											INNER JOIN products AS b ON (a.itemid = b.itemid)
										WHERE (a.prodnumber = '$prodnumber')");
		}else if ($etype == 'Subcomponents'){
			$stmt = (new Connection)->connect()->prepare("SELECT
											a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2
										FROM prodcomitems AS a
											INNER JOIN rawmats AS b ON (a.itemid = b.itemid)
										WHERE (a.prodnumber = '$prodnumber')");
		}else{
			$stmt = (new Connection)->connect()->prepare("SELECT
											a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2
										FROM recycleitems AS a
											INNER JOIN rawmats AS b ON (a.itemid = b.itemid)
										WHERE (a.recnumber = '$prodnumber')");
		}

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	


	// GET Purchase Order Items
	static public function mdlShowProdfinItems($prodnumber){
		$stmt = (new Connection)->connect()->prepare("SELECT
										a.qty,a.price,a.tamount,a.itemid,b.pdesc,b.meas2
									FROM prodfinitems AS a
										INNER JOIN products AS b ON (a.itemid = b.itemid)
									WHERE (a.prodnumber = '$prodnumber')");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlCancelProdfin($prodnumber){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // PO # not included
			$stmt = $pdo->prepare("UPDATE prodfin SET prodstatus = 'Cancelled' WHERE prodnumber = :prodnumber");

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

	// ---------- Machine Production Capacity -----------
	static public function mdlAddProdcapacity($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $prodcap_id = $pdo->prepare("SELECT CONCAT('MC', LPAD((count(id)+1),7,'0')) as gen_id FROM prodcapacity");

            $prodcap_id->execute();
		    $prodcapid = $prodcap_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO prodcapacity(machineid, etype, capacitynumber, postedby, remarks, productlist) VALUES (:machineid, :etype, :capacitynumber, :postedby, :remarks, :productlist)");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":etype", $data["etype"], PDO::PARAM_STR);
			$stmt->bindParam(":capacitynumber", $prodcapid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product){
				$items = $pdo->prepare("INSERT INTO prodcapacityitems(capacitynumber, qty, shiftgoal, packtarget, itemid) VALUES (:capacitynumber, :qty, :shiftgoal, :packtarget, :itemid)");

				$items->bindParam(":capacitynumber", $prodcapid[0]['gen_id'], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":shiftgoal", $product->shiftgoal, PDO::PARAM_STR);
				$items->bindParam(":packtarget", $product->packtarget, PDO::PARAM_STR);
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
	
	static public function mdlEditProdcapacity($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE prodcapacity SET 
												 machineid = :machineid,
												 etype = :etype,
												 capacitynumber = :capacitynumber,
												 postedby = :postedby,
												 remarks = :remarks,
												 productlist = :productlist
										   WHERE capacitynumber = :capacitynumber");

			$stmt->bindParam(":machineid", $data["machineid"], PDO::PARAM_STR);
			$stmt->bindParam(":etype", $data["etype"], PDO::PARAM_STR);
			$stmt->bindParam(":capacitynumber", $data["capacitynumber"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":productlist", $data["productlist"], PDO::PARAM_STR);	
			$stmt->execute();

			$delete_items = (new Connection)->connect()->prepare("DELETE FROM prodcapacityitems WHERE capacitynumber = :capacitynumber");
			$delete_items -> bindParam(":capacitynumber", $data["capacitynumber"], PDO::PARAM_STR);
			$delete_items->execute();

			$itemsList = json_decode($data["productlist"]);
			foreach($itemsList as $product) {
				$items = $pdo->prepare("INSERT INTO prodcapacityitems(capacitynumber, qty, shiftgoal, packtarget, itemid) VALUES (:capacitynumber, :qty, :shiftgoal, :packtarget, :itemid)");
				$items->bindParam(":capacitynumber", $data["capacitynumber"], PDO::PARAM_STR);
				$items->bindParam(":qty", $product->qty, PDO::PARAM_STR);
				$items->bindParam(":shiftgoal", $product->shiftgoal, PDO::PARAM_STR);
				$items->bindParam(":packtarget", $product->packtarget, PDO::PARAM_STR);
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

	static public function mdlShowProdcapacityTransactionList($classcode, $etype){
		if ($classcode != ''){
			$class_code = " AND (c.classcode = '$classcode')";
		}else{
			$class_code = "";
		}	
		
		if ($etype != ''){
			$entry_type = " AND (mc.etype = '$etype')";
		}else{
			$entry_type = "";
		}		

		$whereClause = "WHERE (mc.capacitynumber != '')" . $class_code . $entry_type;

		$stmt = (new Connection)->connect()->prepare("SELECT
		 							m.machinedesc,
									mc.machineid,
									mc.capacitynumber,
									mc.etype
								FROM machine AS m
									INNER JOIN classification AS c ON (c.classcode = m.classcode)
									INNER JOIN prodcapacity AS mc ON (mc.machineid = m.machineid)
								$whereClause ORDER BY m.machinedesc");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowProdcapacity($capacitynumber){
		$stmt = (new Connection)->connect()->prepare("SELECT
		 								mc.machineid,
										mc.etype,
										mc.capacitynumber,
										mc.remarks,
										mc.postedby,
										mc.productlist
									FROM prodcapacity AS mc
									WHERE (mc.capacitynumber = '$capacitynumber')");

		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}
	
	static public function mdlShowProdcapacityItems($capacitynumber, $etype){
		if ($etype == 'Finished Goods'){
			$stmt = (new Connection)->connect()->prepare("SELECT
											a.qty,a.shiftgoal,a.packtarget,a.itemid,b.pdesc,b.meas2
										FROM prodcapacityitems AS a
											INNER JOIN products AS b ON (a.itemid = b.itemid)
										WHERE (a.capacitynumber = '$capacitynumber')");
		}else{
			$stmt = (new Connection)->connect()->prepare("SELECT
											a.qty,a.shiftgoal,a.packtarget,a.itemid,b.pdesc,b.meas2
										FROM prodcapacityitems AS a
											INNER JOIN rawmats AS b ON (a.itemid = b.itemid)
										WHERE (a.capacitynumber = '$capacitynumber')");
		}
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	// Quota Report
	static public function mdlShowQuotaReport($machineid, $start_date, $end_date, $categorycode, $etype, $operatedby, $prodstatus, $reptype, $shift){
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

		if ($etype != ''){
			$entry_type = " HAVING (etype = '$etype')";
		}else{
			$entry_type = "";
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

		if ($shift != ''){
			$prod_shift = " AND (c.shift = '$shift')";
		}else{
			$prod_shift = "";
		}	

		if(!empty($end_date)){
			$dates = " AND (c.proddate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}					

		$whereClause = "WHERE (c.prodnumber != '')" . $machine . $req_status . $dates . $request_by . $category_code . $prod_shift;
        if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT 'Finished Goods' as etype,
									CONCAT(e.lname, ', ', e.fname) AS oprname,
									COUNT(c.shift) AS shifts,
									SUM(d.qty) as total_qty,
									SUM(pci.packtarget) as target_qty,
									SUM(d.tamount) as total_amount,
                                    -- Calculating the percentage of total_qty over target_qty and rounding it to 2 decimal places
    								ROUND((SUM(d.qty) / SUM(pci.packtarget)) * 100, 2) AS kpi
                                    FROM prodfinitems AS d
										INNER JOIN prodfin as c ON (c.prodnumber = d.prodnumber)
										INNER JOIN employees as e ON (c.operatedby = e.empid)
										INNER JOIN prodcapacityitems as pci ON (d.itemid = pci.itemid)
										INNER JOIN prodcapacity as pc ON (pc.capacitynumber = pci.capacitynumber) AND (pc.machineid = c.machineid)
										$whereClause GROUP BY oprname
										$entry_type 
								UNION ALL	
									SELECT 'Subcomponents' as etype,
									CONCAT(e.lname, ', ', e.fname) AS oprname,
									COUNT(c.shift) AS shifts,
									SUM(d.qty) as total_qty,
									SUM(pci.packtarget) as target_qty,
									SUM(d.tamount) as total_amount,
                                    -- Calculating the percentage of total_qty over target_qty and rounding it to 2 decimal places
    								ROUND((SUM(d.qty) / SUM(pci.packtarget)) * 100, 2) AS kpi
                                    FROM prodcomitems AS d
										INNER JOIN prodcom as c ON (c.prodnumber = d.prodnumber)
										INNER JOIN employees as e ON (c.operatedby = e.empid)
										INNER JOIN prodcapacityitems as pci ON (d.itemid = pci.itemid)
										INNER JOIN prodcapacity as pc ON (pc.capacitynumber = pci.capacitynumber) AND (pc.machineid = c.machineid)
										$whereClause GROUP BY oprname
										$entry_type	
									ORDER BY kpi DESC");
		}else if ($reptype == 2){	
			$stmt = (new Connection)->connect()->prepare("SELECT 'Finished Goods' as etype,
									CONCAT(e.lname, ', ', e.fname) AS oprname,
									COUNT(c.shift) AS shifts,
									a.catdescription,
									SUM(d.qty) as total_qty,
									SUM(pci.packtarget) as target_qty,
									SUM(d.tamount) as total_amount
									FROM categorygoods as a
										INNER JOIN products as b ON (a.categorycode = b.categorycode)
										INNER JOIN prodfinitems AS d ON (b.itemid = d.itemid)
										INNER JOIN prodfin as c ON (c.prodnumber = d.prodnumber)
										INNER JOIN employees as e ON (c.operatedby = e.empid)
										INNER JOIN prodcapacityitems as pci ON (d.itemid = pci.itemid)
										INNER JOIN prodcapacity as pc ON (pc.capacitynumber = pci.capacitynumber) AND (pc.machineid = c.machineid)
										$whereClause GROUP BY oprname,a.catdescription WITH ROLLUP
										$entry_type
									UNION ALL
									SELECT 'Subcomponents' as etype,
									CONCAT(e.lname, ', ', e.fname) AS oprname,
									COUNT(c.shift) AS shift,
									a.catdescription,
									SUM(d.qty) as total_qty,
									SUM(pci.packtarget) as target_qty,
									SUM(d.tamount) as total_amount FROM categoryrawmats as a
										INNER JOIN rawmats as b ON (a.categorycode = b.categorycode)
										INNER JOIN prodcomitems AS d ON (b.itemid = d.itemid)
										INNER JOIN prodcom as c ON (c.prodnumber = d.prodnumber)
										INNER JOIN employees as e ON (c.operatedby = e.empid)
										INNER JOIN prodcapacityitems as pci ON (d.itemid = pci.itemid)
										INNER JOIN prodcapacity as pc ON (pc.capacitynumber = pci.capacitynumber) AND (pc.machineid = c.machineid)
										$whereClause GROUP BY oprname,a.catdescription WITH ROLLUP
										$entry_type");	
	    }else if ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT 'Finished Goods' as etype,
									mc.classname,
									COUNT(c.shift) AS shifts,
									SUM(d.qty) as total_qty,
									SUM(pci.packtarget) as target_qty,
									SUM(d.tamount) as total_amount,
                                    -- Calculating the percentage of total_qty over target_qty and rounding it to 2 decimal places
    								ROUND((SUM(d.qty) / SUM(pci.packtarget)) * 100, 2) AS kpi
                                    FROM prodfinitems AS d
										INNER JOIN prodfin as c ON (c.prodnumber = d.prodnumber)
										INNER JOIN machine as m ON (m.machineid = c.machineid)
                                        INNER JOIN classification as mc ON (mc.classcode = m.classcode)
										INNER JOIN prodcapacityitems as pci ON (d.itemid = pci.itemid)
										INNER JOIN prodcapacity as pc ON (pc.capacitynumber = pci.capacitynumber) AND (pc.machineid = c.machineid)
										$whereClause GROUP BY mc.classname
										$entry_type
								UNION ALL
									SELECT 'Subcomponents' as etype,
										mc.classname,
										COUNT(c.shift) AS shifts,
										SUM(d.qty) as total_qty,
										SUM(pci.packtarget) as target_qty,
										SUM(d.tamount) as total_amount,
										-- Calculating the percentage of total_qty over target_qty and rounding it to 2 decimal places
										ROUND((SUM(d.qty) / SUM(pci.packtarget)) * 100, 2) AS kpi
										FROM prodcomitems AS d
											INNER JOIN prodcom as c ON (c.prodnumber = d.prodnumber)
											INNER JOIN machine as m ON (m.machineid = c.machineid)
											INNER JOIN classification as mc ON (mc.classcode = m.classcode)
											INNER JOIN prodcapacityitems as pci ON (d.itemid = pci.itemid)
											INNER JOIN prodcapacity as pc ON (pc.capacitynumber = pci.capacitynumber) AND (pc.machineid = c.machineid)
											$whereClause GROUP BY mc.classname
											$entry_type
										ORDER BY kpi DESC");
		}else if ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("SELECT 'Finished Goods' as etype,
									mc.classname,
									COUNT(c.shift) AS shifts,
									m.machinedesc,
									SUM(d.qty) as total_qty,
									SUM(pci.packtarget) as target_qty,
									SUM(d.tamount) as total_amount
									FROM prodfinitems AS d
										INNER JOIN prodfin as c ON (c.prodnumber = d.prodnumber)
										INNER JOIN machine as m ON (m.machineid = c.machineid)
										INNER JOIN classification as mc ON (mc.classcode = m.classcode)
										INNER JOIN prodcapacityitems as pci ON (d.itemid = pci.itemid)
										INNER JOIN prodcapacity as pc ON (pc.capacitynumber = pci.capacitynumber) AND (pc.machineid = c.machineid)
										$whereClause GROUP BY mc.classname,m.machinedesc WITH ROLLUP
										$entry_type
								UNION ALL
									SELECT 'Subcomponents' as etype,
									mc.classname,
									COUNT(c.shift) AS shifts,
									m.machinedesc,
									SUM(d.qty) as total_qty,
									SUM(pci.packtarget) as target_qty,
									SUM(d.tamount) as total_amount
									FROM prodcomitems AS d
										INNER JOIN prodcom as c ON (c.prodnumber = d.prodnumber)
										INNER JOIN machine as m ON (m.machineid = c.machineid)
										INNER JOIN classification as mc ON (mc.classcode = m.classcode)
										INNER JOIN prodcapacityitems as pci ON (d.itemid = pci.itemid)
										INNER JOIN prodcapacity as pc ON (pc.capacitynumber = pci.capacitynumber) AND (pc.machineid = c.machineid)
										$whereClause GROUP BY mc.classname,m.machinedesc WITH ROLLUP
										$entry_type");			
		}else if ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("SELECT 'Finished Goods' as etype,
									m.machinedesc,
									COUNT(c.shift) AS shifts,
									p.pdesc,
									SUM(d.qty) as total_qty,
									SUM(pci.packtarget) as target_qty,
									SUM(d.tamount) as total_amount
									FROM products AS p
                                        INNER JOIN prodfinitems AS d ON (p.itemid = d.itemid)
										INNER JOIN prodfin as c ON (c.prodnumber = d.prodnumber)
										INNER JOIN machine as m ON (m.machineid = c.machineid)
										INNER JOIN prodcapacityitems as pci ON (d.itemid = pci.itemid)
										INNER JOIN prodcapacity as pc ON (pc.capacitynumber = pci.capacitynumber) AND (pc.machineid = c.machineid)
										$whereClause GROUP BY m.machinedesc,p.pdesc WITH ROLLUP
										$entry_type
								UNION ALL
									SELECT 'Subcomponents' as etype,
									m.machinedesc,
									COUNT(c.shift) AS shifts,
									p.pdesc,
									SUM(d.qty) as total_qty,
									SUM(pci.packtarget) as target_qty,
									SUM(d.tamount) as total_amount
									FROM products AS p
                                        INNER JOIN prodcomitems AS d ON (p.itemid = d.itemid)
										INNER JOIN prodcom as c ON (c.prodnumber = d.prodnumber)
										INNER JOIN machine as m ON (m.machineid = c.machineid)
										INNER JOIN prodcapacityitems as pci ON (d.itemid = pci.itemid)
										INNER JOIN prodcapacity as pc ON (pc.capacitynumber = pci.capacitynumber) AND (pc.machineid = c.machineid)
										$whereClause GROUP BY m.machinedesc,p.pdesc WITH ROLLUP
										$entry_type");
		} 

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}