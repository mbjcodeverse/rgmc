<!-- Vertical form options -->
<div class="container-fluid">
  <form class="incoming-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-7" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:0;">
            <h4 class="transaction-name">INCOMING</h4>
              <!-- Posted by -->
              <input type="hidden" name="tns-postedby" id="tns-postedby" value="<?php echo $_SESSION["empid"];?>">

              <!-- User type -->
              <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION["utype"];?>">

              <!-- Transaction type -->
              <input type="hidden" name="trans_type" id="trans_type" value="New" required> 

              <!-- Supplier -->
              <div class="col-sm-5 form-group" style="padding: 0px;padding-top:8px;padding-right:7px;padding-left:7px;margin:0;">
                  <input type="text" class="form-control" id="txt-name" name="txt-name" placeholder="Supplier" autocomplete="nope" required readonly="true">
              </div>

              <!-- Delivery Date -->
              <div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;padding-right:7px;margin:0;">
                <input type="text" class="form-control datepicker" data-mask="99/99/9999" placeholder="Del Date&hellip;" id="date-deldate" name="date-deldate" disabled required>
              </div> 

              <!-- PO #, View button -->
              <div class="col-sm-3 form-group" style="padding: 0px;padding-top:8px;padding-right:0px;margin:0;">
                  <div class="input-group">
                    <input type="text" class="form-control transaction-id" id="txt-ponumber" name="txt-ponumber" placeholder="Purchase #" autocomplete="nope" required readonly="true"><span class="input-group-append"><button class="btn btn-light" type="button" data-popup="tooltip" data-trigger="focus" title="View Incoming Qty" data-toggle="modal" data-target="#modal-view-incoming-qty" id="btn-view" disabled><i class="icon-cart-add2" style="color:orange;"></i></button></span>
                  </div>
              </div>
          </div>

          <div class="card-body" style="padding-bottom: 0;margin-bottom:0;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
                <!-- Branch -->
                <div class="col-sm-3 form-group" style="padding: 0px;">
                  <input type="text" class="form-control" id="txt-machinedesc" name="txt-machinedesc" placeholder="Machine" readonly="true">
                </div>

                <!-- Invoice/DR -->
                <div class="col-sm-2 form-group">
                  <select data-placeholder="Type" class="form-control select" data-fouc id="sel-inctype" name="sel-inctype" required disabled>
                    <option></option>
                    <option value="Invoice">Invoice</option>
                    <option value="DR">DR</option>
                  </select>
                </div>    

                <!-- Delivery Status -->
                <div class="col-sm-2 form-group" style="padding: 0px;padding-right: 7px;">
                  <input type="text" class="form-control transaction-status" id="txt-delstatus" name="txt-delstatus" value="" placeholder="Status" readonly="true">
                </div> 

                <!-- Supplier Delivery Code -->
                <div class="col-sm-2 form-group" style="padding: 0px;padding-right: 7px;">
                  <input type="text" class="form-control" id="tns-iscode" name="tns-iscode" placeholder="Supplier IS #" autocomplete="nope" disabled>
                </div>  

                <!-- Delivery # -->
                <div class="col-sm-3 form-group" style="padding: 0px;padding-right: 0px;">
                  <input type="text" class="form-control transaction-id" id="tns-delnumber" name="tns-delnumber" placeholder="Del #" readonly="true">
                </div>                                                       

                <div class="table-responsive" style="min-height:331px;max-height: clamp(65px,100vh,336px);overflow: auto;padding-top: 0px;">
                  <table class="table transaction-header-product-list" id="trans-table">
                    <thead class="sticky-top">
                      <tr>
                        <td width="50%">Item Description</td>
                        <td width="15%" style="text-align: right;">Qty</td>
                        <td width="15%" style="text-align: right;">Unit Cost</td>
                        <td width="15%" style="text-align: right;">Amount</td>
                      </tr>                    
                    </thead>
                    <tbody class="enlisted_products" id="product_list">
                    </tbody>
                  </table>
                </div>
              </div>                          
            
              <input type="hidden" name="productList" id="productList">                
          </div>  <!-- card body -->
          
          <div class="card-footer" style="padding-top: 0;margin-top: 0;">
            <div class="row">
             <table class="table transaction-footer">
               <tbody>
                <tr>
                  <td style="width:15%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;">Checked by</td>
                  <td style="width:30%;padding:3px;">
                    <select data-placeholder="< Select Employee >" class="form-control select-search" id="sel-checkedby" name="sel-checkedby" disabled required>
                      <option></option>
                      <?php
                        $item = null;
                        $value = null;
                        $employee = (new ControllerEmployees)->ctrShowEmployees($item, $value);
                        foreach ($employee as $key => $value) {
                          echo '<option value="'.$value["empid"].'">'.$value["lname"].', '.$value["fname"].'</option>';
                        }
                      ?>
                    </select>
                  </td>
                  <td class="overall_total" style="width:15%;font-size: 1.3em;text-align: right;font-weight: 400;padding-top: 3px;padding-bottom: 3px;">TOTAL COST</td>  
                  <td style="width:20%;font-size: 1.3em;padding: 3px;;">
                      <input type="text" class="form-control" style="text-align: right;" id="num-amount" name="num-amount" autocomplete="nope" value="0.00" required readonly="true">
                  </td>
                </tr>

                <tr>
                  <td style="width:15%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;">Delivered by</td>
                  <td style="width:30%;padding: 3px;">
                      <input type="text" class="form-control" id="tns-deliveredby" name="tns-deliveredby" autocomplete="nope" disabled>
                  </td>
                  <td class="discount_total" style="width:15%;font-size: 1.3em;text-align: right;font-weight: 400;padding-top: 3px;padding-bottom: 3px;">DISCOUNT</td>  
                  <td style="width:20%;font-size: 1.3em;padding: 3px;">
                      <input type="text" style="text-align: right;" class="form-control numeric" id="num-discount" name="num-discount" value="0.00" autocomplete="nope" readonly="true" disabled>
                  </td>
                </tr>

                <tr>
                  <td style="width:15%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;">Remarks</td>
                  <td style="width:30%;padding: 3px;">
                      <input type="text" class="form-control" id="tns-remarks" name="tns-remarks" autocomplete="nope" disabled>
                  </td>
                  <td class="net_total" style="width:15%;font-size: 1.3em;text-align: right;font-weight: 400;padding-top: 3px;padding-bottom: 3px;">NET COST</td>  
                  <td style="width:20%;font-size: 1.3em;padding: 3px;">
                      <input type="text" style="text-align: right;" class="form-control" id="num-netamount" name="num-netamount" autocomplete="nope" value="0.00" required readonly="true">
                  </td>
                </tr>                

              </tbody>
             </table>
            </div>
        
            <!-- ================== Function Buttons ================= -->
            <div class="btn-group btn-group-justified" style="margin-top: 10px;">
              <div class="btn-group">
                <button type="button" class="btn btn-light" id="btn-search-po" name="btn-search-po" data-toggle="modal" data-target="#modal-search-purchaseorder"><i class="icon-file-empty"></i>&nbsp;&nbsp;Get PO</button>
              </div>

              <!-- <div class="btn-group">
                <button type="submit" class="btn btn-light" name="btn-defer" id="btn-defer" disabled><i class="icon-floppy-disk"></i>&nbsp;&nbsp;Defer</button>
              </div> -->

              <div class="btn-group">
                <button type="submit" class="btn btn-light" name="btn-post" id="btn-post" disabled><i class="icon-floppy-disk"></i>&nbsp;&nbsp;Post</button>
              </div>            

              <div class="btn-group">
                <button type="button" class="btn btn-light" name="btn-search" id="btn-search" data-toggle="modal" data-target="#modal-search-incoming"><i class="icon-file-text2"></i>&nbsp;&nbsp;Search</button>
              </div>

              <div class="btn-group">
                <button type="button" class="btn btn-light" disabled name="btn-print" id="btn-print"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button>
              </div>

              <div class="btn-group">
                <button type="button" class="btn btn-light" disabled name="btn-cancel" id="btn-cancel"><i class="icon-blocked"></i>&nbsp;&nbsp;Cancel</button>
              </div>             
            </div>
            <!-- ================== Function Buttons ================= -->
          </div>  <!-- footer -->
       </div>     <!-- card -->
      </div>    

      <!-- Products Table -->
      <div class="col-md-5" style="padding-left: 0px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header header-elements-inline">
            <h5 class="card-title datatable-form-title">ITEM LISTING</h5> 
          </div>
            <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header transactionProductsTable" style="font-size: 1em;">
              <thead>
                <tr>
                  <th>Item Description</th>
                  <th>Cost</th>
                  <th class="text-center" style="width:25px;">Act</th>
                </tr>
              </thead>
            </table>
        </div>   <!-- card -->
      </div>

    </div>  <!-- row -->
  </form>
</div>

<!-- ============== Active Purchase Order List ============ -->
<div id="modal-search-purchaseorder" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;PENDING | PARTIAL PURCHASE ORDER</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;">
            <div class="col-sm-4 form-group">
              <label for="lst-suppliercode" id="lbl-lst-suppliercode" style="color:aqua;">= &gt; Supplier</label>
              <select data-placeholder="< Select Supplier >" class="form-control select-search" id="lst-suppliercode" name="lst-suppliercode" required>
                <option></option>
                <?php
                  $suppliers = (new ControllerSupplier)->ctrShowSupplierList();
                  foreach ($suppliers as $key => $value) {
                    echo '<option value="'.$value["suppliercode"].'">'.$value["name"].'</option>';
                  }
                ?>
              </select>              
            </div>

            <div class="col-sm-3 form-group">
              <div class="form-group">
                <label>Date Range</label>
                <div class="input-group">
                  <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-calendar22"></i></span>
                  </span>
                  <input type="text" class="form-control daterange-basic" id="lst_date_range" name="lst_date_range" required> 
                </div>
              </div>
            </div>

            <div class="col-sm-3 form-group">
               <label for="lst-machineid" id="lbl-lst-machineid" style="color:aqua;">= &gt; Machine</label>
               <select data-placeholder="< Select Machine >" class="form-control select-search" id="lst-machineid" name="lst-machineid">
                  <option></option>
                  <?php
                      $machines = (new ControllerMachine)->ctrShowMachineList();
                      foreach ($machines as $key => $value) {
                        echo '<option value="'.$value["machineid"].'">'.$value["machinedesc"].'</option>';
                      }
                  ?>
              </select>
            </div>            

            <div class="col-sm-2 form-group">
              <label for="lst-postatus" id="lbl-lst-postatus" style="color:aqua;">= &gt; Status</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="lst-postatus" name="lst-postatus" required>
                <option></option>
                <option value="Pending | Partial">Pending | Partial</option>
                <option value="Pending">Pending</option>
                <option value="Partial">Partial</option>
              </select>
            </div> 
          </div>  

          <div class="h-divider"></div>

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header purchaseorderTransactionTable">  

          <thead>
            <tr>
              <th style="min-width: 130px;">Date</th>
              <th style="min-width: 345px;">Supplier</th>
              <th style="min-width: 120px;">PO #</th>
              <th style="min-width: 170px;">Machine</th>
              <th style="min-width: 120px;">Status</th>
              <th style="min-width: 150px;">Amount</th>
              <th>Act</th>
            </tr>
          </thead>
          <tbody>
        </table>

    </div>
  </div>
</div>

<!-- ============== Incoming Transaction List ============ -->
<div id="modal-search-incoming" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;INCOMING TRANSACTION LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;">
            <div class="col-sm-4 form-group">
              <label for="inc-suppliercode" id="lbl-inc-suppliercode" style="color:aqua;">= &gt; Supplier</label>
              <select data-placeholder="< Select Supplier >" class="form-control select-search" id="inc-suppliercode" name="inc-suppliercode" required>
                <option></option>
                <?php
                  $suppliers = (new ControllerSupplier)->ctrShowSupplierList();
                  foreach ($suppliers as $key => $value) {
                    echo '<option value="'.$value["suppliercode"].'">'.$value["name"].'</option>';
                  }
                ?>
              </select>              
            </div>

            <div class="col-sm-3 form-group">
              <div class="form-group">
                <label>Date Range</label>
                <div class="input-group">
                  <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-calendar22"></i></span>
                  </span>
                  <input type="text" class="form-control daterange-basic" id="inc_date_range" name="inc_date_range" required> 
                </div>
              </div>
            </div>

            <div class="col-sm-3 form-group">
               <label for="inc-machineid" id="lbl-inc-machineid" style="color:aqua;">= &gt; Machine</label>
               <select data-placeholder="< Select Machine >" class="form-control select-search" id="inc-machineid" name="inc-machineid">
                <option></option>
                  <?php
                      $machines = (new ControllerMachine)->ctrShowMachineList();
                      foreach ($machines as $key => $value) {
                        echo '<option value="'.$value["machineid"].'">'.$value["machinedesc"].'</option>';
                      }
                  ?>
              </select>
            </div>            

            <div class="col-sm-2 form-group">
              <label for="inc-delstatus" id="lbl-inc-delstatus" style="color:aqua;">= &gt; Status</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="inc-delstatus" name="inc-delstatus" required>
                <option></option>
                <option value="Posted">Posted</option>
                <!-- <option value="Deferred">Deferred</option> -->
                <option value="Cancelled">Cancelled</option>
              </select>
            </div> 
          </div>  

          <div class="h-divider"></div>

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header incomingTransactionTable"> 

          <thead>
            <tr>
              <th style="min-width: 130px;">Date</th>
              <th style="min-width: 345px;">Supplier</th>
              <th style="min-width: 120px;">PO #</th>
              <th style="min-width: 170px;">Machine</th>
              <th style="min-width: 120px;">Status</th>
              <th style="min-width: 150px;">Amount</th>
              <th>Act</th>
            </tr>
          </thead>
          <tbody>
        </table>

    </div>
  </div>
</div>

<!-- ============== Purchase Order | Incoming Qty ============ -->
<div id="modal-view-incoming-qty" class="modal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;PURCHASE ORDER | INCOMING QUANTITY</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
 
      <div class="h-divider"></div>

      <table class="table table-striped table-bordered profile-grid-header" style="font-size: 1.1em;">
        <thead>
          <tr>
            <th style="min-width: 330px;text-align: left;">Product</th>
            <th style="min-width: 100px;text-align: right;">Pur Qty</th>
            <th style="min-width: 100px;text-align: right;">Del Qty</th>
          </tr>
        </thead>
        <tbody id="purchase-inc-qty">
        </tbody>
      </table>

    </div>
  </div>
</div>

<script src="views/js/incoming.js"></script>


