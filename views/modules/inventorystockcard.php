<!-- <div class="container-fluitemid"> -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
  <div class="col-md-8 mx-auto">

<form class="stockcard-form" method="POST" autocomplete="nope">  
<!-- <div class="row">
  <div class="col-md-7 card-top-left-px"> -->

    <!-- Page length options -->
    <div class="card">
      <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:10px;">
        <h5 class="card-title flex-grow-1" style="color:lightblue;font-size: 2em;padding-top:16px;">STOCK CARD&nbsp;&nbsp;&nbsp;</h5>
      </div>

      <table class="table datatable-basic table-bordered table-hover datatable-small-font profile-gritemid-header productInventoryTable">
        <thead>
          <tr>
           <th>Products</th>
           <th>Code</th>
           <th style="width:120px;">On-hand</th>
           <th style="width:100px;">Act</th>
          </tr>
        </thead>
          <tbody>
          <?php
             $prod_stocks = (new Connection)->connect()->query("
              SELECT a.itemid,b.invdate AS tdate,a.itemcode,'Inventory' AS details,a.pdesc,c.qty,1 AS priority FROM items AS a INNER JOIN inventoryitems AS c ON (a.itemid = c.itemid) INNER JOIN inventory AS b ON (c.invnumber = b.invnumber) WHERE (b.invstatus = 'Posted') AND (b.invdate >= '2022-11-14') 
              UNION ALL
              SELECT a.itemid,b.deldate AS tdate,a.itemcode,'Incoming' AS details,a.pdesc,c.qty,1 AS priority FROM items AS a INNER JOIN incomingitems AS c ON (a.itemid = c.itemid) INNER JOIN incoming AS b ON (c.delnumber = b.delnumber) WHERE (b.delstatus = 'Posted') AND (b.deldate >= '2022-11-14') 
              UNION ALL
              SELECT a.itemid,b.reqdate AS tdate,a.itemcode,'Withdrawal' AS details,a.pdesc,c.qty,1 AS priority FROM items AS a INNER JOIN stockoutitems AS c ON (a.itemid = c.itemid) INNER JOIN stockout AS b ON (c.reqnumber = b.reqnumber) WHERE (b.reqstatus = 'Posted') AND (b.reqdate >= '2022-11-14') ORDER BY itemid,tdate,priority");

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

                $ctr = $ctr + 1;
                if ($ctr == 1){
                  $prev_itemid = $value["itemid"];
                  $prev_code = $value["itemcode"];
                }

                $curr_itemid = $value["itemid"];        /*Current Product ID*/
                $curr_code = $value["itemcode"];        /*Current Product Code*/

                if ($prev_itemid == $curr_itemid){      /*Previous Product ID =  Current Product ID*/
                  $pdesc = $value["pdesc"];
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
                      default: 
                        $onhand = $onhand - $qty;
                    }
                  // }  
                }else{
                  echo '<tr itemid='.$itemid.'>
                    <td>'.$pdesc.'</td>
                    <td>'.$prev_code.'</td>
                    <td>'.$onhand.'</td>
                    <td>
                      <button type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnStockcard" itemid="'.$itemid.'" data-toggle="modal" data-target="#stockcard"><i class="icon-stack-text"></i>
                      </button>
                    </td>
                  </tr>';

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
                      default:  
                        $onhand = $onhand - $qty;
                    }
                  // }  
                }
            }

            if ($itemid != 0){
              echo '<tr itemid='.$itemid.'>
                <td>'.$pdesc.'</td>
                <td>'.$itemcode.'</td>
                <td>'.$onhand.'</td>
                <td>
                  <button type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnStockcard" itemid="'.$itemid.'" data-toggle="modal" data-target="#stockcard"><i class="icon-stack-text"></i>
                  </button>
                </td>
              </tr>'; 

              $update_onhand = (new Connection)->connect()->prepare("UPDATE items SET onhand=? WHERE itemid=?");
              $update_onhand->execute([$onhand, $prev_itemid]);    
            }
          ?>
          </tbody>
      </table>
    </div>
  </form>
  </div>
</div>

<div id="stockcard" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title" id="product_name"><i class="icon-menu7 mr-2"></i></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <div class="modal-body">
        <div class="table-responsive" style="overflow-y: auto; max-height: 350px;">
        <table class="stockcard_content table datatable-basic table-bordered table-hover datatable-small-font profile-grid-header mx-auto w-auto">
<!--         <div class="row stockcard_content">
        </div> -->
        </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="views/js/stockcard.js"></script>