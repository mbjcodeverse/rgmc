<?php
    $prod_stocks = (new Connection)->connect()->query("
    SELECT a.itemid,b.invdate AS tdate,a.itemcode,'Inventory' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty,1 AS priority FROM items AS a INNER JOIN inventoryitems AS c ON (a.itemid = c.itemid) INNER JOIN inventory AS b ON (c.invnumber = b.invnumber) WHERE (b.invstatus = 'Posted') AND (b.invdate >= '2022-11-14') 
    UNION ALL
    SELECT a.itemid,b.deldate AS tdate,a.itemcode,'Incoming' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty*a.eqnum as qty,1 AS priority FROM items AS a INNER JOIN incomingitems AS c ON (a.itemid = c.itemid) INNER JOIN incoming AS b ON (c.delnumber = b.delnumber) WHERE (b.delstatus = 'Posted') AND (b.deldate >= '2022-11-14')
    UNION ALL
    SELECT a.itemid,b.retdate AS tdate,a.itemcode,'Return' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty*a.eqnum as qty,1 AS priority FROM items AS a INNER JOIN returnitems AS c ON (a.itemid = c.itemid) INNER JOIN returned AS b ON (c.retnumber = b.retnumber) WHERE (b.retstatus = 'Posted') AND (b.retdate >= '2022-11-14')  
    UNION ALL
    SELECT a.itemid,b.reqdate AS tdate,a.itemcode,'Withdrawal' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty,1 AS priority FROM items AS a INNER JOIN stockoutitems AS c ON (a.itemid = c.itemid) INNER JOIN stockout AS b ON (c.reqnumber = b.reqnumber) WHERE (b.reqstatus = 'Posted') AND (b.reqdate >= '2022-11-14') ORDER BY pdesc,itemid,priority,tdate");

    $prev_itemid = 0;
    $curr_itemid = 0;

    $prev_code = '';
    $curr_code = '';

    $ctr = 0;
    $onhand = 0.00;
    $isInventory = 0;
    $itemid = 0;

    foreach ($prod_stocks as $key => $value) {
        $itemid = $value["itemid"];
        $itemcode = $value["itemcode"];
        $tdate = $value["tdate"];
        $details = $value["details"];
        $qty = $value["qty"];
        $priority = $value["priority"];

        if ($ctr == 0){
            $prev_itemid = $value["itemid"];
            $prev_code = $value["itemcode"];
        }

        $ctr = $ctr + 1;

        $curr_itemid = $value["itemid"];        /*Current Product ID*/
        $curr_code = $value["itemcode"];        /*Current Product Code*/

        if ($prev_itemid == $curr_itemid){      /*Previous Product ID =  Current Product ID*/
            $pdesc = $value["pdesc"];
            $meas1 = $value["meas1"];
            $eqnum = $value["eqnum"];
            $meas2 = $value["meas2"];
            $ucost = $value["ucost"];
            $reorder = $value["reorder"];
            // $pdesc = $value["pdesc"];
            if ($details == "Inventory"){
                $isInventory = 1;
            }
                
            // if ($isInventory == 1){
                switch ($details) {
                case "Inventory":
                    $onhand = $qty;
                    break;
                case "Incoming":
                    $onhand = $onhand + $qty;
                    break; 
                case "Return":
                    $onhand = $onhand + $qty;
                    break;	
                default: 
                    $onhand = $onhand - $qty;
                }
            // }  
        }else{
            $update_onhand = (new Connection)->connect()->prepare("UPDATE items SET onhand=? WHERE itemid=?");
            $update_onhand->execute([$onhand, $prev_itemid]);

            $prev_itemid = $curr_itemid;
            $prev_code = $curr_code;

            $pdesc = $value["pdesc"];

            // $pdesc = $value["pdesc"];
            $onhand = 0.00;
            $isInventory = 0;
            if ($details == "Inventory"){
                $isInventory = 1;
            }

            // if ($isInventory == 1){
                switch ($details) {
                case "Inventory":
                    $onhand = $qty;
                    break;
                case "Incoming":
                    $onhand = $onhand + $qty;
                    break;   
                case "Return":
                    $onhand = $onhand + $qty;
                    break;		
                default:  
                    $onhand = $onhand - $qty;
                }
            // }  
        }
    }

    if ($itemid != 0){
        $update_onhand = (new Connection)->connect()->prepare("UPDATE items SET onhand=? WHERE itemid=?");
        $update_onhand->execute([$onhand, $prev_itemid]);    
    }
?>

<!-- Vertical form options -->
<div class="container-fluid">
  <form class="release-report-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-12" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:10px;">
              <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION["utype"];?>">

              <h4 class="card-title flex-grow-1 transaction-name">ITEM DETAILS</h4> 

              <!-- Report Type Label -->
              <div class="col-sm-1 form-group" style="padding: 0px;padding-top:15px;padding-right:0;margin:0;">
                  <label for="lst-reptype" style="text-align: right;font-size: 1.2em;">REPORT TYPE</label>
              </div>              

              <div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;margin:0;">
                <select data-placeholder="< Select Type >" class="form-control select" data-fouc id="lst-reptype" name="lst-reptype" required>
                   <option></option>
                   <option value="1">Categorical Display</option>
                   <option value="2">List in Alphabetical</option>
                   <option value="3">Converted Items</option>
                </select>
              </div>   
              
              <!-- Filter Label -->
              <div class="col-sm-1 form-group" style="padding: 0px;padding-top:15px;padding-right:0;margin:0;padding-left:60px;">
                  <label for="lst-filter" style="text-align: right;font-size: 1.2em;">Filter</label>
              </div>              

              <div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;margin:0;">
                <select data-placeholder="< Select Filter >" class="form-control select" data-fouc id="lst-filter" name="lst-filter" required>
                   <option></option>
                   <option value="1">< All Items ></option>
                   <option value="2">Stable</option>
                   <option value="3">Critical</option>
                   <option value="4">Below Zero</option>
                </select>
              </div>              

              <!-- Category Label -->
              <div class="col-sm-1 form-group" style="padding: 0px;padding-top:15px;padding-right:0;margin:0;">
                  <label for="lst-categorycode" id="lbl-lst-categorycode" style="text-align: right;font-size: 1em;color:aqua;margin-left:15px;">= &gt; Category</label>
              </div>

              <div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;margin:0;">
                  <select data-placeholder="< Select Category >" class="form-control select-search" id="lst-categorycode" name="lst-categorycode">
                    <option></option>
                    <?php
                        $category = (new ControllerCategory)->ctrShowCategoryList();
                        foreach ($category as $key => $value) {
                          echo '<option value="'.$value["categorycode"].'">'.$value["catdescription"].'</option>';
                        }
                     ?>
                  </select>               
              </div>

              <div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;padding-left:7px;margin:0;">
                <button type="button" class="btn btn-light" disabled name="btn-print-report" id="btn-print-report" style="float:right;margin-right:19px;"><i class="icon-printer"></i></button>
              </div>    
          </div>
          <div class="card-body" style="padding-bottom: 0;margin: 0;padding-top: 5px;">
          </div>

          <hr style="margin:0;padding: 0;padding-bottom: 24px;">

          <div class="row item_content">
          </div> 
          
          <div class="card-footer" style="padding-top: 0;margin-top: 0;">
          </div>  <!-- footer -->
       </div>     <!-- card -->
      </div>

    </div>  <!-- row -->
  </form>
</div>

<script src="views/js/itemreport.js"></script>