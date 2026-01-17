<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
  <div class="col-md-8 mx-auto">
  <form class="incomingbranch-form" method="POST" autocomplete="nope">
        <div class="card">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;">
            <h4 class="card-title flex-grow-1 transaction-name">INCOMING STOCKS</h4>
              <!-- Branch Code -->
              <input type="hidden" name="branch_code" id="branch_code" value="<?php echo $_SESSION["branchcode"];?>">

              <!-- Branch Name -->
              <input type="hidden" name="branch_name" id="branch_name" value=""> 

              <!-- Supplier -->
              <div class="col-sm-5 form-group" style="padding: 0px;padding-top:8px;padding-right:8px;margin:0;">
                  <input type="text" class="form-control" id="txt-name" name="txt-name" placeholder="Supplier" autocomplete="nope" required readonly="true">
              </div>

              <!-- PO #, View button -->
              <div class="col-sm-3 form-group" style="padding: 0px;padding-top:8px;padding-right:8px;margin:0;">
                  <div class="input-group">
                    <input type="text" class="form-control transaction-id" id="txt-ponumber" name="txt-ponumber" placeholder="Purchase #" autocomplete="nope" required readonly="true"><span class="input-group-append"><button class="btn btn-light" type="button" data-popup="tooltip" data-trigger="focus" title="View Incoming Qty" data-toggle="modal" data-target="#modal-view-incoming-qty" id="btn-view" disabled><i class="icon-cart-add2" style="color:orange;"></i></button></span>
                  </div>
              </div>
          </div>

          <div class="card-body" style="padding-bottom: 0;margin-bottom: 0;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
                <!-- Branch -->
                <div class="col-sm-3 form-group" style="padding: 0px;">
                  <input type="text" class="form-control" id="txt-bname" name="txt-bname" placeholder="Branch" readonly="true">
                </div>

                <!-- Delivery Date -->
                <div class="col-sm-2 form-group" style="padding: 0px;padding-right: 7px;padding-left: 7px;">
                  <input type="text" class="form-control datepicker" data-mask="99/99/9999" placeholder="Del Date&hellip;" id="date-deldate" name="date-deldate" disabled required>
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
                <div class="col-sm-3 form-group" style="padding: 0px;padding-right: 7px;">
                  <input type="text" class="form-control transaction-id" id="tns-delnumber" name="tns-delnumber" placeholder="Del #" readonly="true">
                </div>                                                       

                <div class="table-responsive" style="min-height:360px;max-height: clamp(65px,100vh,336px);overflow: auto;padding-top: 0px;">
                  <table class="table transaction-header-product-list" id="trans-table">
                    <thead class="sticky-top">
                      <tr>
                        <td width="80%">Item Description</td>
                        <td width="20%" style="text-align: right;">Qty</td>
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
                      <input type="text" class="form-control" id="tns-checkedby" name="tns-checkedby" placeholder="" disabled>
                  </td>
                  <td style="width:15%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;">Delivered by</td>
                  <td style="width:30%;padding: 3px;">
                      <input type="text" class="form-control" id="tns-deliveredby" name="tns-deliveredby" autocomplete="nope" disabled>
                  </td>
                </tr>

                <tr>
                  <td style="width:15%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;">Remarks</td>
                  <td colspan="3" style="width:30%;padding: 3px;">
                      <input type="text" class="form-control" id="tns-remarks" name="tns-remarks" autocomplete="nope" disabled>
                  </td>
                </tr>                

              </tbody>
             </table>
            </div>
        
            <!-- ================== Function Buttons ================= -->
            <div class="btn-group btn-group-justified" style="margin-top: 10px;">
              <div class="btn-group">
                <button type="button" class="btn btn-light" name="btn-search" id="btn-search" data-toggle="modal" data-target="#modal-search-incoming"><i class="icon-file-text2"></i>&nbsp;&nbsp;Search</button>
              </div>

              <div class="btn-group">
                <button type="button" class="btn btn-light" name="btn-print" id="btn-print"><i class="icon-printer"></i>&nbsp;&nbsp;Reports</button>
              </div>           
            </div>
            <!-- ================== Function Buttons ================= -->
          </div>  <!-- footer -->
       </div>     <!-- card -->
   </form>
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
            <div class="col-sm-3 form-group">
                <label>BRANCH</label>
                <input type="text" class="form-control" id="branch" name="branch" autocomplete="nope" disabled>
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

            <div class="col-sm-2 form-group">
              <label for="inc-delstatus" id="lbl-inc-delstatus" style="color:aqua;">= &gt; Status</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="inc-delstatus" name="inc-delstatus" required>
                <option></option>
                <option value="Posted">Posted</option>
                <option value="Deferred">Deferred</option>
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
              <th style="min-width: 170px;">Branch</th>
              <th style="min-width: 120px;">Status</th>
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

<script src="views/js/incomingbranch.js"></script>


