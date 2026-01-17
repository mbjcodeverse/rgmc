<?php
require_once "connection.php";
class ModelFactoryDashboard{
  static public function mdlShowFactoryDashboard($reptype, $start_date, $end_date){
		if(!empty($end_date)){
			$dates = " BETWEEN '$start_date' AND '$end_date'";
		}else{
			$dates = "";
		}					

		$dateClause = $dates;
        
      if ($reptype == 1){
			          $stmt = (new Connection)->connect()->prepare("SELECT 
                    d.date AS date_label,
                    IFNULL(r.raw_materials_cost, 0) AS raw_materials_cost,
                    IFNULL(e.excess_materials_cost, 0) AS excess_materials_cost,
                    IFNULL(_r.return_cost, 0) AS return_cost,
                    IFNULL(p.production_cost, 0) AS production_cost,
                    IFNULL(o.electricity, 0) AS electricity_cost,
                    IFNULL(o.manpower, 0) AS manpower_cost,
                    IFNULL(o.sales, 0) AS sales_amount
                FROM
                    (
                        SELECT DISTINCT reqdate AS date 
                        FROM rawout 
                        WHERE reqstatus = 'Posted' AND reqdate $dateClause

                        UNION

                        SELECT DISTINCT excdate AS date 
                        FROM excess 
                        WHERE excstatus = 'Posted' AND excdate $dateClause

                        UNION

                        SELECT DISTINCT retdate AS date 
                        FROM matreturn 
                        WHERE retstatus = 'Posted' AND returntype = 'Return to Cost' AND retdate $dateClause
                        
                        UNION
                        
                        SELECT DISTINCT proddate AS date 
                        FROM prodfin 
                        WHERE prodstatus = 'Posted' AND proddate $dateClause

                        UNION
                        
                        SELECT DISTINCT odate AS date 
                        FROM othercost 
                        WHERE ostatus = 'Posted' AND odate $dateClause
                    ) d
                LEFT JOIN (
                    SELECT reqdate, SUM(ri.tamount) AS raw_materials_cost
                    FROM rawout ro
                    JOIN rawoutitems ri ON ro.reqnumber = ri.reqnumber
                    WHERE ro.reqstatus = 'Posted' AND (ri.matcost = 1) AND ro.reqdate $dateClause
                    GROUP BY ro.reqdate
                ) r ON d.date = r.reqdate
                LEFT JOIN (
                    SELECT excdate, SUM(exi.tamount) AS excess_materials_cost
                    FROM excess ex
                    JOIN excessitems exi ON ex.excnumber = exi.excnumber
                    WHERE ex.excstatus = 'Posted' AND ex.excdate $dateClause
                    GROUP BY ex.excdate
                ) e ON d.date = e.excdate
                LEFT JOIN (
                    SELECT proddate, SUM(pfi.tamount) AS production_cost
                    FROM prodfin pf
                    JOIN prodfinitems pfi ON pf.prodnumber = pfi.prodnumber
                    WHERE pf.prodstatus = 'Posted' AND pf.proddate $dateClause
                    GROUP BY pf.proddate
                ) p ON d.date = p.proddate
                LEFT JOIN (
                    SELECT retdate, SUM(rti.tamount) AS return_cost
                    FROM matreturn rt
                    JOIN matreturnitems rti ON rt.retnumber = rti.retnumber
                    WHERE rt.retstatus = 'Posted' AND (rt.returntype = 'Return to Cost') AND rt.retdate $dateClause
                    GROUP BY rt.retdate
                ) _r ON d.date = _r.retdate
                LEFT JOIN (
                    SELECT odate, SUM(electricity) AS electricity, SUM(manpower) AS manpower, SUM(sales) AS sales
                    FROM othercost
                    WHERE ostatus = 'Posted' AND odate $dateClause
                    GROUP BY odate
                ) o ON d.date = o.odate
                ORDER BY d.date
            ");
	    } elseif ($reptype == 2){
			          $stmt = (new Connection)->connect()->prepare("SELECT 
                    CONCAT(DATE_FORMAT(d.date, '%M %Y'), ' [ Week ', FLOOR((DAY(d.date) - 1) / 7) + 1, ' ]') AS date_label,
                    SUM(IFNULL(r.raw_materials_cost, 0)) AS raw_materials_cost,
                    SUM(IFNULL(e.excess_materials_cost, 0)) AS excess_materials_cost,
                    SUM(IFNULL(_r.return_cost, 0)) AS return_cost,
                    SUM(IFNULL(p.production_cost, 0)) AS production_cost,
                    SUM(IFNULL(o.electricity, 0)) AS electricity_cost,
                    SUM(IFNULL(o.manpower, 0)) AS manpower_cost,
                    SUM(IFNULL(o.sales, 0)) AS sales_amount
                FROM
                    (
                        SELECT DISTINCT reqdate AS date 
                        FROM rawout 
                        WHERE reqstatus = 'Posted' AND reqdate $dateClause

                        UNION

                        SELECT DISTINCT excdate AS date 
                        FROM excess 
                        WHERE excstatus = 'Posted' AND excdate $dateClause

                        UNION

                        SELECT DISTINCT retdate AS date 
                        FROM matreturn 
                        WHERE retstatus = 'Posted' AND returntype = 'Return to Cost' AND retdate $dateClause
                        
                        UNION
                        
                        SELECT DISTINCT proddate AS date 
                        FROM prodfin 
                        WHERE prodstatus = 'Posted' AND proddate $dateClause

                        UNION
                        
                        SELECT DISTINCT odate AS date 
                        FROM othercost 
                        WHERE ostatus = 'Posted' AND odate $dateClause
                    ) d
                LEFT JOIN (
                    SELECT reqdate, SUM(ri.tamount) AS raw_materials_cost
                    FROM rawout ro
                    JOIN rawoutitems ri ON ro.reqnumber = ri.reqnumber
                    WHERE ro.reqstatus = 'Posted' AND ri.matcost = 1 AND ro.reqdate $dateClause
                    GROUP BY ro.reqdate
                ) r ON d.date = r.reqdate
                LEFT JOIN (
                    SELECT excdate, SUM(exi.tamount) AS excess_materials_cost
                    FROM excess ex
                    JOIN excessitems exi ON ex.excnumber = exi.excnumber
                    WHERE ex.excstatus = 'Posted' AND ex.excdate $dateClause
                    GROUP BY ex.excdate
                ) e ON d.date = e.excdate
                LEFT JOIN (
                    SELECT proddate, SUM(pfi.tamount) AS production_cost
                    FROM prodfin pf
                    JOIN prodfinitems pfi ON pf.prodnumber = pfi.prodnumber
                    WHERE pf.prodstatus = 'Posted' AND pf.proddate $dateClause
                    GROUP BY pf.proddate
                ) p ON d.date = p.proddate
                LEFT JOIN (
                    SELECT retdate, SUM(rti.tamount) AS return_cost
                    FROM matreturn rt
                    JOIN matreturnitems rti ON rt.retnumber = rti.retnumber
                    WHERE rt.retstatus = 'Posted' AND (rt.returntype = 'Return to Cost') AND rt.retdate $dateClause
                    GROUP BY rt.retdate
                ) _r ON d.date = _r.retdate
                LEFT JOIN (
                    SELECT odate, SUM(electricity) AS electricity, SUM(manpower) AS manpower, SUM(sales) AS sales
                    FROM othercost
                    WHERE ostatus = 'Posted' AND odate $dateClause
                    GROUP BY odate
                ) o ON d.date = o.odate
                GROUP BY YEAR(d.date), MONTH(d.date), FLOOR((DAY(d.date) - 1) / 7) + 1
                ORDER BY MIN(d.date)");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("");
		  } elseif ($reptype == 4){
			$stmt = (new Connection)->connect()->prepare("");
	    } elseif ($reptype == 5){
			$stmt = (new Connection)->connect()->prepare("");
	    }

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

  static public function mdlShowProductionMetrics($reptype, $start_date, $end_date){
		  if(!empty($end_date)){
        $dates = " BETWEEN '$start_date' AND '$end_date'";
      }else{
        $dates = "";
      }					

		  $dateClause = $dates;
        
      if ($reptype == 1){
        $stmt = (new Connection)->connect()->prepare("SELECT 
                      c.catdescription AS category,
                      IFNULL(p.production_cost, 0) AS production_cost,
                      IFNULL(p.production_qty, 0) AS production_qty,
                      IFNULL(p.production_weight, 0) AS production_weight,
                      IFNULL(r.material_cost, 0) AS material_cost,
                      IFNULL(r.material_qty, 0) AS material_qty,
                      IFNULL(a.accessories_cost, 0) AS accessories_cost,
                      IFNULL(a.accessories_qty, 0) AS accessories_qty,
                      IFNULL(e.excess_cost, 0) AS excess_cost,
                      IFNULL(e.excess_qty, 0) AS excess_qty,
                      IFNULL(_r.return_cost, 0) AS return_cost,
                      IFNULL(_r.return_qty, 0) AS return_qty,
                      IFNULL(m.head_count, 0) AS head_count,
                      IFNULL(m.manpower_cost, 0) AS manpower_cost
                  FROM
                      (                        
                          SELECT c.catdescription AS catdescription
                          FROM categorygoods c 
                          INNER JOIN products p ON c.categorycode = p.categorycode
                          INNER JOIN prodfinitems pfi ON p.itemid = pfi.itemid
                          INNER JOIN prodfin pf ON pfi.prodnumber = pf.prodnumber
                          WHERE pf.prodstatus = 'Posted' 
                            AND pf.proddate $dateClause
                          GROUP BY c.catdescription

                          UNION

                          SELECT c.catdescription AS catdescription
                          FROM categorygoods c 
                          INNER JOIN machine m ON c.categorycode = m.categorycode
                          INNER JOIN rawout r ON r.machineid = m.machineid
                          INNER JOIN rawoutitems ro ON ro.reqnumber = r.reqnumber
                          INNER JOIN rawmats mat ON ro.itemid = mat.itemid
                          WHERE r.reqstatus = 'Posted' 
                            AND r.reqdate $dateClause
                            AND mat.categorycode != '0005' AND mat.categorycode != '0010'  -- without Accessories & PE Packaging Pcs
                          GROUP BY c.catdescription

                          UNION

                          SELECT c.catdescription AS catdescription
                          FROM categorygoods c 
                          INNER JOIN machine m ON c.categorycode = m.categorycode
                          INNER JOIN rawout r ON r.machineid = m.machineid
                          INNER JOIN rawoutitems ro ON ro.reqnumber = r.reqnumber
                          INNER JOIN rawmats mat ON ro.itemid = mat.itemid
                          WHERE r.reqstatus = 'Posted' 
                            AND r.reqdate $dateClause
                            AND mat.categorycode = '0005' OR mat.categorycode = '0010'  -- Accessories & PE Packaging Pcs
                          GROUP BY c.catdescription

                          UNION

                          SELECT c.catdescription AS catdescription
                          FROM categorygoods c 
                          INNER JOIN machine m ON c.categorycode = m.categorycode
                          INNER JOIN excess e ON e.machineid = m.machineid
                          INNER JOIN excessitems ei ON ei.excnumber = e.excnumber
                          WHERE e.excstatus = 'Posted' 
                            AND e.excdate $dateClause
                          GROUP BY c.catdescription

                          UNION

                          SELECT c.catdescription AS catdescription
                          FROM categorygoods c 
                          INNER JOIN machine m ON c.categorycode = m.categorycode
                          INNER JOIN matreturn rm ON rm.machineid = m.machineid
                          INNER JOIN matreturnitems rmi ON rmi.retnumber = rm.retnumber
                          WHERE rm.retstatus = 'Posted' 
                            AND rm.returntype = 'Return to Cost'
                            AND rm.retdate $dateClause
                          GROUP BY c.catdescription

                          UNION

                          SELECT c.catdescription AS catdescription
                          FROM categorygoods c 
                          INNER JOIN productmetrics pm ON c.categorycode = pm.categorycode
                          WHERE pm.mstatus = 'Posted'
                            AND pm.mdate $dateClause
                          GROUP BY c.catdescription
                      ) c
                      LEFT JOIN (
                          SELECT pc.catdescription, SUM(pfi.tamount) AS production_cost, SUM(pfi.qty) AS production_qty, SUM(pfi.qty * p.pweight) AS production_weight
                          FROM categorygoods pc 
                          INNER JOIN products p ON pc.categorycode = p.categorycode
                          INNER JOIN prodfinitems pfi ON p.itemid = pfi.itemid
                          INNER JOIN prodfin pf ON pfi.prodnumber = pf.prodnumber
                          WHERE pf.prodstatus = 'Posted' 
                            AND pf.proddate $dateClause
                          GROUP BY pc.catdescription
                      ) p ON c.catdescription = p.catdescription

                      LEFT JOIN (
                          SELECT pc.catdescription, SUM(ro.tamount) AS material_cost, SUM(ro.qty) AS material_qty
                          FROM categorygoods pc 
                          INNER JOIN machine m ON pc.categorycode = m.categorycode
                          INNER JOIN rawout r ON r.machineid = m.machineid
                          INNER JOIN rawoutitems ro ON ro.reqnumber = r.reqnumber
                          INNER JOIN rawmats mat ON ro.itemid = mat.itemid
                          WHERE r.reqstatus = 'Posted'
                            AND ro.matcost = 1 
                            AND r.reqdate $dateClause
                          GROUP BY pc.catdescription
                      ) r ON c.catdescription = r.catdescription

                      LEFT JOIN (
                          SELECT pc.catdescription, SUM(ro.tamount) AS accessories_cost, SUM(ro.qty) AS accessories_qty
                          FROM categorygoods pc 
                          INNER JOIN machine m ON pc.categorycode = m.categorycode
                          INNER JOIN rawout r ON r.machineid = m.machineid
                          INNER JOIN rawoutitems ro ON ro.reqnumber = r.reqnumber
                          INNER JOIN rawmats mat ON ro.itemid = mat.itemid
                          WHERE r.reqstatus = 'Posted'
                            AND ro.matcost = 1 
                            AND r.reqdate $dateClause
                            AND (mat.categorycode = '0005' OR mat.categorycode = '0010')  -- Accessories & PE Packaging Pcs
                          GROUP BY pc.catdescription
                      ) a ON c.catdescription = a.catdescription

                      LEFT JOIN (
                          SELECT pc.catdescription, SUM(ei.tamount) AS excess_cost, SUM(ei.qty) AS excess_qty
                          FROM categorygoods pc 
                          INNER JOIN machine m ON pc.categorycode = m.categorycode
                          INNER JOIN excess e ON e.machineid = m.machineid
                          INNER JOIN excessitems ei ON ei.excnumber = e.excnumber
                          WHERE e.excstatus = 'Posted'
                            AND e.excdate $dateClause
                          GROUP BY pc.catdescription
                      ) e ON c.catdescription = e.catdescription  

                      LEFT JOIN (
                          SELECT pc.catdescription, SUM(rmi.tamount) AS return_cost, SUM(rmi.qty) AS return_qty
                          FROM categorygoods pc 
                          INNER JOIN machine m ON pc.categorycode = m.categorycode
                          INNER JOIN matreturn rm ON rm.machineid = m.machineid
                          INNER JOIN matreturnitems rmi ON rmi.retnumber = rm.retnumber
                          WHERE rm.retstatus = 'Posted'
                            AND rm.returntype = 'Return to Cost'
                            AND rm.retdate $dateClause
                          GROUP BY pc.catdescription
                      ) _r ON c.catdescription = _r.catdescription                     

                      LEFT JOIN (
                          SELECT pc.catdescription, SUM(pm.headcount) AS head_count, SUM(pm.amount) AS manpower_cost
                          FROM categorygoods pc 
                          INNER JOIN productmetrics pm ON pc.categorycode = pm.categorycode
                          WHERE pm.mstatus = 'Posted'
                            AND pm.mdate $dateClause
                          GROUP BY pc.catdescription
                      ) m ON c.catdescription = m.catdescription
                  ORDER BY c.catdescription");
      } elseif ($reptype == 2){
			  $stmt = (new Connection)->connect()->prepare("");  
	    } elseif ($reptype == 3){
			  $stmt = (new Connection)->connect()->prepare("");
		  } elseif ($reptype == 4){
			  $stmt = (new Connection)->connect()->prepare("");
	    } elseif ($reptype == 5){
			  $stmt = (new Connection)->connect()->prepare("");
	    }

    $stmt->execute();
    $result = $stmt->fetchAll();
    $stmt = null;
    return $result;
	}

  static public function mdlShowProductionDetails($category, $start_date, $end_date){
		  if(!empty($end_date)){
        $dates = " BETWEEN '$start_date' AND '$end_date'";
      }else{
        $dates = "";
      }					

		  $dateClause = $dates;

      $stmt = (new Connection)->connect()->prepare("SELECT c.categorycode,
                            c.catdescription,
                            p.pdesc,
                            p.pweight,
                            p.wmeas,
                            SUM(pi.qty) AS qty,
                            SUM(pi.tamount) AS tamount
                            FROM categorygoods c INNER JOIN products p ON (c.categorycode = p.categorycode)
                                                 INNER JOIN prodfinitems pi ON (p.itemid = pi.itemid)
                                                 INNER JOIN prodfin pd ON (pd.prodnumber = pi.prodnumber)
                            WHERE (c.catdescription = '$category') AND
                                  (pd.proddate $dateClause)
                            GROUP BY p.pdesc");
      $stmt->execute();
      $result = $stmt->fetchAll();
      $stmt = null;
      return $result;
  }
  
  static public function mdlShowDashboardAssessment($start_date, $end_date, $categorycode, $tier) {
    $categorycode = ltrim($categorycode, ',');

    // if ($categorycode != ''){
		// 	$category_code = " AND (c.categorycode = '$categorycode')";
		// }else{
		// 	$category_code = "";
		// }

    // Handle categorycode - multiple values
    if (!empty($categorycode)) {
        $codesArray = explode(',', $categorycode);
        $safeCodes = array_map('trim', $codesArray);

        if (count($safeCodes) == 1) {
            $category_code = " AND (c.categorycode = '" . $safeCodes[0] . "')";
        } else {
            $quotedCodes = "'" . implode("','", $safeCodes) . "'";
            $category_code = " AND (c.categorycode IN ($quotedCodes))";
        }
    } else {
        $category_code = "";
    }

    if ($tier == 1){
			$tier_code = " AND (rm.critical > 0.00)";
		}else{
			$tier_code = "";
		}    

    $whereClause = "WHERE (rm.itemid != '')" . $category_code . $tier_code;

		$stmt = (new Connection)->connect()->prepare("SELECT 
            rm.itemid,rm.ucost,c.categorycode,rm.critical,rm.low,rm.moderate,rm.high,
            CONCAT(rm.pdesc, ' (', UPPER(rm.meas2), ')') AS pdesc,
            COALESCE(inv.inventory_qty, 0) AS inventory_qty,
            COALESCE(inv.inventory_ucost, 0) AS inventory_ucost,
            COALESCE(inv.inventory_tamount, 0) AS inventory_tamount,
            COALESCE(inc.incoming_qty_total, 0) AS incoming_qty_total,
            COALESCE(inc.incoming_average_ucost, 0) AS incoming_average_ucost,
            COALESCE(inc.incoming_tamount_total, 0) AS incoming_tamount_total,
            COALESCE(pc.subcom_qty_total, 0) AS subcom_qty_total,
            COALESCE(pc.subcom_average_ucost, 0) AS subcom_average_ucost,
            COALESCE(pc.subcom_tamount_total, 0) AS subcom_tamount_total,
            COALESCE(rc.recycle_qty_total, 0) AS recycle_qty_total,
            COALESCE(rc.recycle_average_ucost, 0) AS recycle_average_ucost,
            COALESCE(rc.recycle_tamount_total, 0) AS recycle_tamount_total,
            COALESCE(d.debris_qty_total, 0) AS debris_qty_total,
            COALESCE(d.debris_average_ucost, 0) AS debris_average_ucost,
            COALESCE(d.debris_tamount_total, 0) AS debris_tamount_total,
            COALESCE(ret.return_qty_total, 0) AS return_qty_total,
            COALESCE(ret.return_average_ucost, 0) AS return_average_ucost,
            COALESCE(ret.return_tamount_total, 0) AS return_tamount_total,
            COALESCE(r.rawout_qty_total, 0) AS rawout_qty_total,
            COALESCE(r.rawout_average_ucost, 0) AS rawout_average_ucost,
            COALESCE(r.rawout_tamount_total, 0) AS rawout_tamount_total
        FROM rawmats rm INNER JOIN categoryrawmats c ON rm.categorycode = c.categorycode
        -- Subquery for beginning inventory
        LEFT JOIN (
            SELECT 
                ii.itemid,
                SUM(ii.qty) AS inventory_qty,
                SUM(ii.price) AS inventory_ucost,
                SUM(ii.tamount) AS inventory_tamount
            FROM inventoryrawmatsitems ii
            INNER JOIN inventoryrawmats i ON i.invnumber = ii.invnumber
                AND i.invdate BETWEEN :start_date AND :start_date
            WHERE i.invstatus = 'Posted'
            GROUP BY ii.itemid
        ) inv ON inv.itemid = rm.itemid

        -- Subquery for incoming items
        LEFT JOIN (
            SELECT iit.itemid,
          SUM(iit.qty) AS incoming_qty_total,
          AVG(iit.price) AS incoming_average_ucost,
          SUM(iit.tamount) AS incoming_tamount_total
            FROM incomingrawmatsitems iit
          INNER JOIN incomingrawmats inc ON inc.ponumber = iit.ponumber
            AND inc.podate BETWEEN :start_date AND :end_date
                        AND inc.postatus = 'Completed'
          GROUP BY iit.itemid
        ) inc ON inc.itemid = rm.itemid

        -- Subquery for subcomponents
        LEFT JOIN (
            SELECT pci.itemid,
          SUM(pci.qty) AS subcom_qty_total,
          AVG(pci.price) AS subcom_average_ucost,
          SUM(pci.tamount) AS subcom_tamount_total
            FROM prodcomitems pci
          INNER JOIN prodcom pc ON pc.prodnumber = pci.prodnumber
            AND pc.proddate BETWEEN :start_date AND :end_date
                        AND pc.prodstatus = 'Posted'
          GROUP BY pci.itemid
        ) pc ON pc.itemid = rm.itemid

        -- Subquery for recycle
        LEFT JOIN (
            SELECT rci.itemid,
          SUM(rci.qty) AS recycle_qty_total,
          AVG(rci.price) AS recycle_average_ucost,
          SUM(rci.tamount) AS recycle_tamount_total
            FROM recycleitems rci
          INNER JOIN recycle rc ON rc.recnumber = rci.recnumber
            AND rc.recdate BETWEEN :start_date AND :end_date
                        AND rc.recstatus = 'Posted'
          GROUP BY rci.itemid
        ) rc ON rc.itemid = rm.itemid

        -- Subquery for waste/damages
        LEFT JOIN (
            SELECT di.itemid,
          SUM(di.qty) AS debris_qty_total,
          AVG(di.price) AS debris_average_ucost,
          SUM(di.tamount) AS debris_tamount_total
            FROM debrisitems di
          INNER JOIN debris d ON d.debnumber = di.debnumber
            AND d.debdate BETWEEN :start_date AND :end_date
                        AND d.debstatus = 'Posted'
          GROUP BY di.itemid
        ) d ON d.itemid = rm.itemid

        -- Subquery for materials return
        LEFT JOIN (
            SELECT ri.itemid,
          SUM(ri.qty) AS return_qty_total,
          AVG(ri.price) AS return_average_ucost,
          SUM(ri.tamount) AS return_tamount_total
            FROM matreturnitems ri
          INNER JOIN matreturn ret ON ret.retnumber = ri.retnumber
            AND ret.retdate BETWEEN :start_date AND :end_date
            AND ret.retstatus = 'Posted'
            AND ret.returntype = 'Return to Inventory'
          GROUP BY ri.itemid
        ) ret ON ret.itemid = rm.itemid        

        -- Subquery for requisition
        LEFT JOIN (
            SELECT roi.itemid,
          SUM(roi.qty) AS rawout_qty_total,
          AVG(roi.price) AS rawout_average_ucost,
          SUM(roi.tamount) AS rawout_tamount_total
            FROM rawoutitems roi
          INNER JOIN rawout r ON r.reqnumber = roi.reqnumber
            AND r.reqdate BETWEEN :start_date AND :end_date
                        AND r.reqstatus = 'Posted'
          GROUP BY roi.itemid
        ) r ON r.itemid = rm.itemid
        $whereClause
        ORDER BY pdesc
		");
    $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
		$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
    $stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;

		// $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
		// $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
		// $stmt->execute();
		// $result = $stmt->fetchAll();

		// $stmt->closeCursor();
		// $stmt = null;
		// return $result;
	} 

  static public function mdlShowInventoryPeriods($end_date) {
		$stmt = (new Connection)->connect()->prepare("SELECT 
              invdate,
              DATE_FORMAT(invdate, '%m/%d/%Y') AS formatted_invdate,
              DATE_ADD(invdate, INTERVAL 1 DAY) AS next_invdate,
              DATE_FORMAT(DATE_ADD(invdate, INTERVAL 1 DAY), '%m/%d/%Y') AS formatted_next_invdate
          FROM 
              (SELECT DISTINCT invdate FROM inventoryrawmats
              UNION
              SELECT '$end_date' AS invdate) AS combined_dates
          ORDER BY invdate
		");

		$stmt->execute();
		$result = $stmt->fetchAll();

		$stmt->closeCursor();
		$stmt = null;
		return $result;
	} 

  static public function mdlShowUsageAssessment($start_date, $end_date, $categorycode) {
    // If categorycode is provided, append to query
    if ($categorycode != '') {
        $category_code = " AND b.categorycode = :categorycode";
    } else {
        $category_code = "";
    }

    $stmt = (new Connection)->connect()->prepare("WITH RequisitionQty AS (
                SELECT b.itemid, SUM(d.qty) AS req_qty, SUM(d.tamount) AS used_amount
                FROM categoryrawmats a 
                JOIN rawmats b ON a.categorycode = b.categorycode
                JOIN rawoutitems d ON d.itemid = b.itemid
                JOIN rawout c ON c.reqnumber = d.reqnumber
                WHERE c.reqstatus = 'Posted'
                  AND c.reqdate BETWEEN :start_date AND :end_date
                  AND d.matcost = 1
                GROUP BY b.itemid
            ),

            ExcessQty AS (
                SELECT b.itemid, SUM(d.qty) AS excess_qty, SUM(d.tamount) AS excess_amount
                FROM categoryrawmats a 
                JOIN rawmats b ON a.categorycode = b.categorycode
                JOIN excessitems d ON d.itemid = b.itemid
                JOIN excess c ON c.excnumber = d.excnumber
                WHERE c.excstatus = 'Posted'
                  AND c.excdate BETWEEN :start_date AND :end_date
                GROUP BY b.itemid
            )

            SELECT 
                i.itemid,
                i.categorycode,
                i.pdesc,
                i.meas2,
                COALESCE(r.req_qty, 0) AS used_materials,
                COALESCE(e.excess_qty, 0) AS ending,
                COALESCE(r.req_qty, 0) - COALESCE(e.excess_qty, 0) AS total_used,
                i.ucost,
                i.ucost * (COALESCE(r.req_qty, 0) - COALESCE(e.excess_qty, 0)) AS total_cost,
                COALESCE(e.excess_amount, 0) AS excess_amount,
                COALESCE(r.used_amount, 0) AS used_amount
            FROM (
                SELECT DISTINCT 
                    b.itemid, 
                    a.categorycode, 
                    b.pdesc, 
                    b.meas2, 
                    b.ucost
                FROM categoryrawmats a 
                JOIN rawmats b ON a.categorycode = b.categorycode
                $category_code
            ) AS i
            LEFT JOIN RequisitionQty r ON r.itemid = i.itemid
            LEFT JOIN ExcessQty e ON e.itemid = i.itemid
            WHERE COALESCE(r.req_qty, 0) <> 0 OR COALESCE(e.excess_qty, 0) <> 0
    ");

    // Bind parameters
    $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
    if ($categorycode != '') {
        $stmt->bindParam(':categorycode', $categorycode, PDO::PARAM_STR);
    }

    // Execute the query
    $stmt->execute();

    // Fetch and return the results
    return $stmt->fetchAll();
  }

  static public function mdlShowUsageSummary($itemid, $start_date, $end_date) {
    $sql = "WITH RECURSIVE DateRange AS (
              SELECT CAST(:start_date AS DATE) AS udate
              UNION ALL
              SELECT DATE_ADD(udate, INTERVAL 1 DAY)
              FROM DateRange
              WHERE udate < :end_date
          ),

          ActiveItems AS (
              SELECT DISTINCT itemid
              FROM (
                  SELECT d.itemid
                  FROM rawoutitems d
                  JOIN rawout c ON c.reqnumber = d.reqnumber
                  WHERE c.reqstatus = 'Posted' AND c.reqdate BETWEEN :start_date AND :end_date
                  
                  UNION

                  SELECT d.itemid
                  FROM excessitems d
                  JOIN excess c ON c.excnumber = d.excnumber
                  WHERE c.excstatus = 'Posted' AND c.excdate BETWEEN :start_date AND :end_date
              ) AS combined
          ),

          AllItems AS (
              SELECT DISTINCT 
                  b.itemid, 
                  a.catdescription, 
                  b.pdesc, 
                  b.meas2, 
                  b.ucost
              FROM categoryrawmats a 
              JOIN rawmats b ON a.categorycode = b.categorycode
              JOIN ActiveItems ai ON ai.itemid = b.itemid
          ),

          RequisitionQty AS (
              SELECT 
                  b.itemid, 
                  c.reqdate AS udate, 
                  SUM(d.qty) AS req_qty,
                  SUM(d.tamount) AS used_amount
              FROM categoryrawmats a 
              JOIN rawmats b ON a.categorycode = b.categorycode
              JOIN rawoutitems d ON d.itemid = b.itemid
              JOIN rawout c ON c.reqnumber = d.reqnumber
              WHERE c.reqstatus = 'Posted'
                AND c.reqdate BETWEEN :start_date AND :end_date
                AND d.matcost = 1
              GROUP BY b.itemid, c.reqdate
          ),

          ExcessQty AS (
              SELECT 
                  b.itemid, 
                  c.excdate AS udate, 
                  SUM(d.qty) AS excess_qty,
                  SUM(d.tamount) AS excess_amount
              FROM categoryrawmats a 
              JOIN rawmats b ON a.categorycode = b.categorycode
              JOIN excessitems d ON d.itemid = b.itemid
              JOIN excess c ON c.excnumber = d.excnumber
              WHERE c.excstatus = 'Posted'
                AND c.excdate BETWEEN :start_date AND :end_date
              GROUP BY b.itemid, c.excdate
          )

          SELECT 
              i.itemid,
              i.catdescription,
              i.pdesc,
              i.meas2,
              d.udate,
              COALESCE(r.req_qty, 0) AS used_materials,
              COALESCE(e.excess_qty, 0) AS ending,
              COALESCE(r.req_qty, 0) - COALESCE(e.excess_qty, 0) AS total_used,
              i.ucost,
              GREATEST(i.ucost * (COALESCE(r.req_qty, 0) - COALESCE(e.excess_qty, 0)), 0) AS total_cost,
              COALESCE(e.excess_amount, 0) AS excess_amount,
              COALESCE(r.used_amount, 0) AS used_amount
          FROM AllItems i
          CROSS JOIN DateRange d
          LEFT JOIN RequisitionQty r ON r.itemid = i.itemid AND r.udate = d.udate
          LEFT JOIN ExcessQty e ON e.itemid = i.itemid AND e.udate = d.udate
          WHERE i.itemid = :itemid
            AND (COALESCE(r.req_qty, 0) <> 0 OR COALESCE(e.excess_qty, 0) <> 0)
          ORDER BY i.itemid, d.udate
    ";

    $stmt = (new Connection)->connect()->prepare($sql);
    $stmt->bindParam(':itemid', $itemid, PDO::PARAM_STR);
    $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Return as associative array
  }

  // static public function mdlShowMaterialCostTrail($trans_date) {
  //   $sql = "SELECT 1 AS priority,'Requisition' AS trans_type,m.machinedesc,
  //                   SUM(d.qty) AS qty,SUM(d.tamount) AS tamount
  //                   FROM machine m INNER JOIN rawout c ON (m.machineid = c.machineid)
  //                                   INNER JOIN rawoutitems d ON (c.reqnumber = d.reqnumber)
  //                   WHERE (c.reqstatus = 'Posted') AND
  //                         (c.reqdate = :trans_date) AND
  //                         (d.matcost = 1)
  //                   GROUP BY m.machinedesc,c.reqdate
  //             UNION
  //             SELECT 2 AS priority,'Excess' AS trans_type,m.machinedesc,
  //                   SUM(f.qty) AS qty,SUM(f.tamount) AS tamount
  //                   FROM machine m INNER JOIN excess e ON (m.machineid = e.machineid)
  //                                   INNER JOIN excessitems f ON (e.excnumber = f.excnumber)
  //                   WHERE (e.excstatus = 'Posted') AND
  //                         (e.excdate = :trans_date)
  //                   GROUP BY m.machinedesc,e.excdate
  //             UNION
  //             SELECT 3 AS priority,'Return' AS trans_type,m.machinedesc,
  //                   SUM(h.qty) AS qty,SUM(h.tamount) AS tamount
  //                   FROM machine m INNER JOIN matreturn g ON (m.machineid = g.machineid)
  //                                   INNER JOIN matreturnitems h ON (g.retnumber = h.retnumber)
  //                   WHERE (g.retstatus = 'Posted') AND
  //                         (g.retdate = :trans_date) AND
  //                         (g.returntype = 'Return to Cost')
  //                   GROUP BY m.machinedesc,g.retdate
  //               ORDER BY priority
  //   ";

  //   $stmt = (new Connection)->connect()->prepare($sql);
  //   $stmt->bindParam(':trans_date', $trans_date, PDO::PARAM_STR);
  //   $stmt->execute();
  //   return $stmt->fetchAll(PDO::FETCH_ASSOC);
  // } 

static public function mdlShowMaterialCostTrail($trans_date) {
    $sql = "SELECT 1 AS priority, 'Requisition' AS trans_type, m.machinedesc,
                   SUM(CASE WHEN r.categorycode != '0005' AND r.categorycode != '0010' THEN d.qty ELSE 0 END) AS qty,
                   SUM(CASE WHEN r.categorycode != '0005' AND r.categorycode != '0010' THEN d.tamount ELSE 0 END) AS tamount,
                   SUM(CASE WHEN r.categorycode = '0005' OR r.categorycode = '0010' THEN d.qty ELSE 0 END) AS aqty,
                   SUM(CASE WHEN r.categorycode = '0005' OR r.categorycode = '0010' THEN d.tamount ELSE 0 END) AS atamount
            FROM machine m 
            INNER JOIN rawout c ON m.machineid = c.machineid
            INNER JOIN rawoutitems d ON c.reqnumber = d.reqnumber
            INNER JOIN rawmats r ON r.itemid = d.itemid
            WHERE c.reqstatus = 'Posted' 
              AND c.reqdate = :trans_date 
              AND d.matcost = 1
            GROUP BY m.machinedesc, c.reqdate

            UNION

            SELECT 2 AS priority, 'Excess' AS trans_type, m.machinedesc,
                   SUM(CASE WHEN r.categorycode != '0005' AND r.categorycode != '0010' THEN f.qty ELSE 0 END) AS qty,
                   SUM(CASE WHEN r.categorycode != '0005' AND r.categorycode != '0010' THEN f.tamount ELSE 0 END) AS tamount,
                   SUM(CASE WHEN r.categorycode = '0005' OR r.categorycode = '0010' THEN f.qty ELSE 0 END) AS aqty,
                   SUM(CASE WHEN r.categorycode = '0005' OR r.categorycode = '0010' THEN f.tamount ELSE 0 END) AS atamount
            FROM machine m 
            INNER JOIN excess e ON m.machineid = e.machineid
            INNER JOIN excessitems f ON e.excnumber = f.excnumber
            INNER JOIN rawmats r ON r.itemid = f.itemid
            WHERE e.excstatus = 'Posted' 
              AND e.excdate = :trans_date
            GROUP BY m.machinedesc, e.excdate

            UNION

            SELECT 3 AS priority, 'Return' AS trans_type, m.machinedesc,
                   SUM(CASE WHEN r.categorycode != '0005' AND r.categorycode != '0010' THEN h.qty ELSE 0 END) AS qty,
                   SUM(CASE WHEN r.categorycode != '0005' AND r.categorycode != '0010' THEN h.tamount ELSE 0 END) AS tamount,
                   SUM(CASE WHEN r.categorycode = '0005' OR r.categorycode = '0010' THEN h.qty ELSE 0 END) AS aqty,
                   SUM(CASE WHEN r.categorycode = '0005' OR r.categorycode = '0010' THEN h.tamount ELSE 0 END) AS atamount
            FROM machine m 
            INNER JOIN matreturn g ON m.machineid = g.machineid
            INNER JOIN matreturnitems h ON g.retnumber = h.retnumber
            INNER JOIN rawmats r ON r.itemid = h.itemid
            WHERE g.retstatus = 'Posted' 
              AND g.retdate = :trans_date 
              AND g.returntype = 'Return to Cost'
            GROUP BY m.machinedesc, g.retdate

            ORDER BY priority
    ";

    $stmt = (new Connection)->connect()->prepare($sql);
    $stmt->bindParam(':trans_date', $trans_date, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


  
  static public function mdlShowProductionCostTrail($trans_date) {
    $sql = "SELECT 1 AS priority,'Production' AS trans_type,m.machinedesc,
                    SUM(b.qty) AS qty,SUM(b.tamount) AS tamount
                    FROM machine m INNER JOIN prodfin a ON (m.machineid = a.machineid)
                                   INNER JOIN prodfinitems b ON (a.prodnumber = b.prodnumber)
                    WHERE (a.prodstatus = 'Posted') AND
                          (a.proddate = :trans_date) 
                    GROUP BY m.machinedesc,a.proddate
    ";

    $stmt = (new Connection)->connect()->prepare($sql);
    $stmt->bindParam(':trans_date', $trans_date, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }  
}