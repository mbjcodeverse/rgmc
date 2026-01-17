<?php
require_once 'connection.php';
class ModelReport{
	static public function mdlShowCatdescReport($start_date,$end_date,$category,$trans_type){
		if ($trans_type == "Stock Replenishment"){
			if ($category > 0){
				$stmt = (new Connection)->connect()->prepare("SELECT a.catname,b.pdesc,SUM(d.qty) as qty FROM category AS a, products AS b, stockin AS c, stockinitems AS d WHERE (a.id = b.idCategory) AND (b.id = d.idProduct) AND (c.refcode = d.refcode) AND c.tdate BETWEEN '$start_date' AND '$end_date' AND (b.idCategory = '$category') GROUP BY a.catname,b.pdesc WITH ROLLUP");
			}else{
				$stmt = (new Connection)->connect()->prepare("SELECT a.catname,b.pdesc,SUM(d.qty) as qty FROM category AS a, products AS b, stockin AS c, stockinitems AS d WHERE (a.id = b.idCategory) AND (b.id = d.idProduct) AND (c.refcode = d.refcode) AND c.tdate BETWEEN '$start_date' AND '$end_date' GROUP BY a.catname,b.pdesc WITH ROLLUP");
			}
		}else{
			if ($category > 0){
				$stmt = (new Connection)->connect()->prepare("SELECT a.catname,b.pdesc,SUM(d.qty) as qty FROM category AS a, products AS b, stockout AS c, stockoutitems AS d WHERE (a.id = b.idCategory) AND (b.id = d.idProduct) AND (c.refcode = d.refcode) AND c.tdate BETWEEN '$start_date' AND '$end_date' AND (b.idCategory = '$category') GROUP BY a.catname,b.pdesc WITH ROLLUP");
			}else{
				$stmt = (new Connection)->connect()->prepare("SELECT a.catname,b.pdesc,SUM(d.qty) as qty FROM category AS a, products AS b, stockout AS c, stockoutitems AS d WHERE (a.id = b.idCategory) AND (b.id = d.idProduct) AND (c.refcode = d.refcode) AND c.tdate BETWEEN '$start_date' AND '$end_date' GROUP BY a.catname,b.pdesc WITH ROLLUP");
			}			
		}
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function mdlShowSequenceReport($start_date,$end_date,$category,$trans_type){
		if ($trans_type == "Stock Replenishment"){
			if ($category > 0){
				$stmt = (new Connection)->connect()->prepare("SELECT a.id,a.tdate,a.refcode,a.recnumber,d.pdesc,SUM(c.qty) as qty FROM stockin AS a,stockinitems AS c,products AS d,category AS e WHERE (a.refcode = c.refcode) AND (c.idProduct = d.id) AND (d.idCategory = e.id) AND (d.idCategory = '$category') AND (a.tstatus = 'Committed') AND a.tdate BETWEEN '$start_date' AND '$end_date' GROUP BY a.id,d.pdesc WITH ROLLUP");
			}else{
				$stmt = (new Connection)->connect()->prepare("SELECT a.id,a.tdate,a.refcode,a.recnumber,d.pdesc,SUM(c.qty) as qty FROM stockin AS a,stockinitems AS c,products AS d,category AS e WHERE (a.refcode = c.refcode) AND (c.idProduct = d.id) AND (d.idCategory = e.id) AND (a.tstatus = 'Committed') AND a.tdate BETWEEN '$start_date' AND '$end_date' GROUP BY a.id,d.pdesc WITH ROLLUP");
			}
		}else{
			if ($category > 0){
				$stmt = (new Connection)->connect()->prepare("SELECT a.id,a.tdate,a.refcode,a.recnumber,b.stkname,d.pdesc,SUM(c.qty) as qty FROM stockout AS a,stakeholder AS b,stockoutitems AS c,products AS d,category AS e WHERE (a.idStakeholder = b.id) AND (a.refcode = c.refcode) AND (c.idProduct = d.id) AND (d.idCategory = e.id) AND (d.idCategory = '$category') AND (a.tstatus = 'Committed') AND a.tdate BETWEEN '$start_date' AND '$end_date' GROUP BY a.id,d.pdesc WITH ROLLUP");
			}else{
				$stmt = (new Connection)->connect()->prepare("SELECT a.id,a.tdate,a.refcode,a.recnumber,b.stkname,d.pdesc,SUM(c.qty) as qty FROM stockout AS a,stakeholder AS b,stockoutitems AS c,products AS d,category AS e WHERE (a.idStakeholder = b.id) AND (a.refcode = c.refcode) AND (c.idProduct = d.id) AND (d.idCategory = e.id) AND (a.tstatus = 'Committed') AND a.tdate BETWEEN '$start_date' AND '$end_date' GROUP BY a.id,d.pdesc WITH ROLLUP");
			}			
		}
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}	
}