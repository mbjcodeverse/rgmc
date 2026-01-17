<!-- Vertical form options -->
<div class="container-fluid">
  <form class="incoming-rawmats-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-7" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:10px;">
            <h4 class="card-title flex-grow-1 transaction-name">PRIMARY MATERIALS</h4>
              <!-- EMP ID -->
              <input type="hidden" name="tns-preparedby" id="tns-preparedby" value="<?php echo $_SESSION["empid"];?>">

              <!-- User Type -->
              <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION["utype"];?>">              

              <!-- Transaction Type -->
              <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>      
          </div>

          <div class="card-body" style="padding-bottom: 0;margin-bottom: 0;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
              <!-- <div class="row"> -->
                <div class="col-sm-5 form-group" style="padding: 0;">
                  <select data-placeholder="< Select Supplier >" class="form-control select-search" id="sel-suppliercode" name="sel-suppliercode" required>
                    <option></option>
                    <?php
                      $suppliers = (new ControllerSupplier)->ctrShowSupplierList();
                      foreach ($suppliers as $key => $value) {
                        echo '<option value="'.$value["suppliercode"].'">'.$value["name"].'</option>';
                      }
                    ?>
                  </select>              
                </div>               

                <div class="col-sm-2 form-group" style="padding: 0px;padding-right: 7px;padding-left: 7px;">
                  <input type="text" class="form-control datepicker" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-podate" name="date-podate" required>
                </div>    

                <div class="col-sm-2 form-group" style="padding: 0px;padding-right: 7px;">
                    <input type="text" class="form-control transaction-status" id="txt-postatus" name="txt-postatus" value="Completed" autocomplete="nope" required readonly="true">
                </div>                          
             
                <div class="col-sm-3 form-group" style="padding: 0px;">
                    <div class="input-group">
                    <input type="text" class="form-control transaction-id" id="txt-ponumber" name="txt-ponumber" placeholder="Purchase #" autocomplete="nope" required readonly="true"><span class="input-group-append"><button class="btn btn-light" type="button" data-popup="tooltip" data-trigger="focus" title="View Incoming Qty" data-toggle="modal" data-target="#modal-view-incoming-qty" id="btn-view" disabled><i class="icon-cart-add2" style="color:orange;"></i></button></span>
                    </div>
                </div>

                <div class="table-responsive" style="min-height:333px;max-height: clamp(65px,100vh,336px);overflow: auto;padding-top: 0px;">
                  <table class="table transaction-header-product-list">
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
                  <td style="width:15%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;">Ordered by</td>
                  <td style="width:30%;padding:3px;">
                    <select data-placeholder="< Select Employee >" class="form-control select-search" id="sel-orderedby" name="sel-orderedby" required>
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
                  <td style="width:15%;font-size: 1.1em;padding-top: 9px;padding-bottom: 3px;text-align: right;"><label for="sel-machineid" id="lbl-lst-sel-machineid" style="color:aqua;">= &gt; Machine</label></td>
                  <td style="width:30%;padding: 3px;">
                    <select data-placeholder="< Select Machine >" class="form-control select-search" id="sel-machineid" name="sel-machineid">
                      <option></option>
                      <?php
                          $machines = (new ControllerMachine)->ctrShowMachineListLocation();
                          foreach ($machines as $key => $value) {
                            echo '<option value="'.$value["machineid"].'">'.$value["machinedesc"].' > '.$value["machabbr"].' - '.$value["buildingname"].'</option>';
                          }
                      ?>
                    </select>
                  </td>
                  <td class="discount_total" style="width:15%;font-size: 1.3em;text-align: right;font-weight: 400;padding-top: 3px;padding-bottom: 3px;">DISCOUNT</td>  
                  <td style="width:20%;font-size: 1.3em;padding: 3px;">
                      <input type="text" style="text-align: right;" class="form-control numeric" id="num-discount" name="num-discount" value="0.00" autocomplete="nope" required readonly="true">
                  </td>
                </tr>

                <tr>
                  <td style="width:15%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;">Remarks</td>
                  <td style="width:30%;padding: 3px;">
                      <input type="text" class="form-control" id="tns-remarks" name="tns-remarks" autocomplete="nope">
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
                <button type="button" class="btn btn-light" id="btn-new" onClick="location.href='incomingrawmats'"><i class="icon-file-empty"></i>&nbsp;&nbsp;New</button>
              </div>

              <div class="btn-group">
                <button type="submit" class="btn btn-light" name="btn-save" id="btn-save" disabled><i class="icon-floppy-disk"></i>&nbsp;&nbsp;Save</button>
              </div>

              <div class="btn-group">
                <button type="button" class="btn btn-light" id="btn-search" data-toggle="modal" data-target="#modal-search-incoming-rawmats"><i class="icon-file-text2"></i>&nbsp;&nbsp;Search</button>
              </div>

              <div class="btn-group" style="visibility:hidden;">
                <button type="button" class="btn btn-light" disabled name="btn-print" id="btn-print"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button>
              </div>

              <div class="btn-group" style="visibility:hidden;">
                <button type="button" class="btn btn-light" disabled name="btn-close" id="btn-close"><i class="icon-cross2"></i>&nbsp;&nbsp;Close</button>
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
                  <th>Item ID</th>
                  <th>Cost</th>
                  <th class="text-center" style="max-width:25px;">Act</th>
                </tr>
              </thead>
            </table>
        </div>   
      </div>

    </div>  <!-- row -->
  </form>
</div>

<!-- ============== Supplier Raw Materials List ============ -->
<div id="modal-search-incoming-rawmats" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;SUPPLIER RAW MATERIAL LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <div class="row" pb-0 style="margin:10px;margin-bottom: -20px;">
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
                      $machines = (new ControllerMachine)->ctrShowMachineListLocation();
                      foreach ($machines as $key => $value) {
                        echo '<option value="'.$value["machineid"].'">'.$value["machinedesc"].' - '.$value["buildingname"].'</option>';
                      }
                   ?>
               </select>
            </div>            

            <div class="col-sm-2 form-group">
              <label>Status</label>  
              <!-- <label for="lst-postatus" id="lbl-lst-postatus" style="color:aqua;">= &gt; Status</label> -->
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="lst-postatus" name="lst-postatus" required>
                <option></option>
                <!-- <option value="Pending | Partial">Pending | Partial</option>
                <option value="Pending">Pending</option> -->
                <option value="Completed" selected>Completed</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div> 
          </div>  

          <div class="h-divider" style="margin-bottom:-21px;"></div>

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header incomingRawMatsTransactionTable">

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
          </tbody>
        </table>
    </div>
  </div>
</div>

<!-- ============== Purchase Order | Incoming Qty ============ -->
<!-- <div id="modal-view-incoming-qty" class="modal allow-modal-drag" tabindex="-1">
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
        <tbody id="purchase-incoming-qty">
        </tbody>
      </table>

    </div>
  </div>
</div> -->

<script src="views/js/incomingrawmats.js"></script>


