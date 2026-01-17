<!-- Vertical form options -->
<div class="container-fluid">
  <form class="prodoperator-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-7" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
            <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:0;">
                <input type="hidden" name="tns-postedby" id="tns-postedby" value="<?php echo $_SESSION['empid']; ?>">
                <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION['utype']; ?>">   
                <input type="hidden" name="user_level" id="user_level" value="<?php echo $_SESSION['ulevel']; ?>">   
                <input type="hidden" name="prod_opr" id="prod_opr" value="<?php echo $_SESSION['mopr']; ?>">  
                <input type="hidden" class="form-control transaction-id" id="txt-prodnumber" name="txt-prodnumber" placeholder="Production #" autocomplete="nope" required readonly="true">
                <input type="hidden" class="form-control transaction-id" id="txt-debnumber" name="txt-debnumber" placeholder="Release #" autocomplete="nope" required readonly="true">
                <input type="hidden" class="form-control transaction-id" id="txt-debstatus" name="txt-debstatus" placeholder="Release #" autocomplete="nope" required readonly="true">
                <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
                  
                <div class="row w-100" style="padding: 0;margin-bottom: 0; align-items: center;">    
                    <!-- PRODUCTION Label -->
                    <div class="col-sm-5 form-group" style="padding: 0px; margin-bottom: 0; padding-right: 15px;margin-left:-165px;margin-top:-8px;">
                        <h4 class="transaction-name col-auto" style="margin-bottom: 5px;">PRODUCTION</h4>
                    </div>

                    <!-- <input type="hidden" name="tns-postedby" id="tns-postedby" value="<?php echo $_SESSION['empid']; ?>">
                    <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION['utype']; ?>">       
                    <input type="hidden" class="form-control transaction-id" id="txt-prodnumber" name="txt-prodnumber" placeholder="Production #" autocomplete="nope" required readonly="true">
                    <input type="hidden" class="form-control transaction-id" id="txt-debnumber" name="txt-debnumber" placeholder="Release #" autocomplete="nope" required readonly="true">
                    <input type="hidden" class="form-control transaction-id" id="txt-debstatus" name="txt-debstatus" placeholder="Release #" autocomplete="nope" required readonly="true">
                    <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>   -->

                    <!-- Machine Dropdown -->
                    <div class="col-sm-7 form-group" style="padding: 0px; margin-bottom: 5px; padding-right: 5px;margin-left:40px;">
                        <select data-placeholder="< Select Machine >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-machineid" name="sel-machineid" required>
                            <option></option>
                            <?php
                                $machines = (new ControllerMachine)->ctrShowMachineListLocation();
                                foreach ($machines as $key => $value) {
                                echo '<option value="'.$value["machineid"].'">'.$value["machinedesc"].' > '.$value["machabbr"].' - '.$value["buildingname"].'</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <!-- Shift Dropdown -->
                    <div class="col-sm-2 form-group" style="padding: 0px; margin-bottom: 5px; padding-right: 12px;">
                        <select data-placeholder="Shift" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-shift" name="sel-shift" required>
                            <option></option>
                            <option value="Day">Day</option>
                            <option value="Night">Night</option>
                        </select>
                    </div>
                </div>     
            </div>

          <div class="card-body" style="padding-bottom: 0;margin-bottom: 0;margin-top:-15px;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
              <!-- <div class="row"> -->
                <div class="col-sm-5 form-group" style="padding: 0;">
                    <select data-placeholder="< Operated by >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-operatedby" name="sel-operatedby" required>
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
                </div>               

                <div class="col-sm-2 form-group" style="padding: 0px;padding-right: 7px;padding-left: 7px;">
                  <input type="text" class="form-control datepicker" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-proddate" name="date-proddate" required>
                </div>    

                <div class="col-sm-2 form-group" style="padding: 0px;padding-right: 7px;">
                    <input type="text" class="form-control transaction-status" id="txt-prodstatus" name="txt-prodstatus" value="Posted" autocomplete="nope" required readonly="true">
                </div>                          
             
                <div class="col-sm-3 form-group" style="padding: 0px;">
                    <select data-placeholder="Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-etype" name="sel-etype" required>
                        <option></option>
                        <option value="Finished Goods" selected>Finished Goods</option>
                        <option value="Subcomponents">Subcomponents</option>
                        <option value="Recycle">Recycle</option>
                        <!-- <option value="Waste">Waste</option> -->
                    </select>
                </div>

                <!-- ******* Finished Goods, Subcomponents, Recycle Form ******* -->
                <div class="table-responsive" style="min-height:213px;max-height: clamp(65px,100vh,213px);overflow: auto;padding-top: 0px;margin-top:-15px;">
                  <table class="table transaction-header-product-list">
                    <thead class="sticky-top">
                      <tr>
                      <td width="50%">Product Description</td>
                        <td width="15%" style="text-align: right;">Qty</td>
                        <td width="15%" style="text-align: right;">Cost</td>
                        <td width="15%" style="text-align: right;">Amount</td>
                      </tr>                    
                    </thead>
                    <tbody class="enlisted_products" id="product_list">
                    </tbody>
                  </table>
                </div>

                <div class="table-responsive">
                    <div class="row">
                        <table class="table transaction-footer">
                            <tbody>
                                <tr>
                                <td style="width:15%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;">Remarks</td>
                                <td style="width:65%;padding: 3px;">
                                    <input type="text" class="form-control" id="tns-remarks" name="tns-remarks" autocomplete="nope">
                                </td>
                                <td style="width:20%;padding: 3px;">
                                    <input type="text" class="form-control" style="color:#94d1f7;font-weight:bold;text-align:right;font-size:1.3em;margin-bottom:-5px;margin-top:-9px;" id="num-amount" name="num-amount" value="0.00" autocomplete="nope">
                                </td>   
                                </tr>                
                            </tbody>
                        </table>
                    </div>
                </div>

                <input type="hidden" name="productList" id="productList">

                <!-- ******* WASTE/DAMAGAES Form ******* -->
                <div class="table-responsive" style="min-height:149px;max-height: clamp(65px,100vh,149px);overflow: auto;padding-top: 0px;margin-top:-15px;">
                  <table class="table transaction-header-product-list">
                    <thead class="sticky-top">
                      <tr>
                        <td width="47%" style="padding:0;">
                          <button type="button" class="btn btn-light" id="btn-wastedamage" style="width:100%;">&nbsp;&nbsp;Waste/Damages</button>
                          <!-- <button class="btn btn-primary" style="width: 100%; padding: 10px;">Waste/Damages</button> -->
                        </td>
                      <!-- <td width="47%">Waste/Damages</td> -->
                        <td width="16%" style="text-align: left;">Cls</td>
                        <td width="10%" style="text-align: right;">Qty</td>
                        <td width="10%" style="text-align: right;">Cost</td>
                        <td width="12%" style="text-align: right;">Amount</td>
                        <!-- <td width="80%">Item Description</td>
                        <td width="20%" style="text-align: right;">Qty</td> -->
                      </tr>                    
                    </thead>
                    <tbody class="enlisted_waste" id="waste_list">
                    </tbody>
                  </table>
                </div>                
              </div>                          
               
              <input type="hidden" name="wasteList" id="wasteList">             
          </div>  <!-- card body -->
          
          <div class="card-footer" style="padding-top: 0;margin-top: 0;">
            <div class="row">
             <table class="table transaction-footer">
               <tbody>
                <tr>
                  <td style="width:15%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;">Remarks</td>
                  <td style="width:65%;padding: 3px;">
                      <input type="text" class="form-control" id="tns-wremarks" name="tns-wremarks" autocomplete="nope">
                  </td>
                  <td style="width:20%;padding: 3px;">
                      <input type="text" class="form-control" style="color:#94d1f7;font-weight:bold;text-align:right;font-size:1.3em;margin-bottom:-5px;margin-top:-9px;padding-right:6px;" id="num-wamount" name="num-wamount" value="0.00" autocomplete="nope">
                  </td>   
                </tr>                
              </tbody>
             </table>
            </div>
        
            <!-- ================== Function Buttons ================= -->
            <div class="btn-group btn-group-justified" style="margin-top: 10px;">
              <div class="btn-group">
                <button type="button" class="btn btn-light" id="btn-new"><i class="icon-file-empty"></i>&nbsp;&nbsp;New</button>
              </div>

              <div class="btn-group">
                <button type="submit" class="btn btn-light" name="btn-save" id="btn-save" disabled><i class="icon-floppy-disk"></i>&nbsp;&nbsp;Save</button>
              </div>

              <div class="btn-group">
                <button type="button" class="btn btn-light" id="btn-search" data-toggle="modal" data-target="#modal-search-prodoperator"><i class="icon-file-text2"></i>&nbsp;&nbsp;Search</button>
              </div>

              <!-- <div class="btn-group">
                <button type="button" class="btn btn-light" disabled name="btn-print" id="btn-print"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button>
              </div> -->

              <div class="btn-group">
                <button type="button" class="btn btn-light" name="btn-report" id="btn-report"><i class="icon-printer"></i>&nbsp;&nbsp;Report</button>
              </div>

              <div class="btn-group">
                <button type="button" class="btn btn-light" name="btn-authentication" id="btn-authentication" onclick="window.location.href='resetoperatoraccount'" style="color:lightgreen;"><i class="icon-cog3"></i>&nbsp;&nbsp;Authentication</button>
              </div>             
            </div>
            <!-- ================== Function Buttons ================= -->
          </div>  <!-- footer -->
       </div>     <!-- card -->
      </div>    

      <!-- Finished Goods Item List -->
      <div class="col-md-5 products_table" style="padding-left: 0px;margin-top: 13px;">
        <div class="card h-98">
          <div class="card-header header-elements-inline">
            <h5 class="card-title datatable-form-title" style="margin-top:5px;">FINISHED GOODS</h5> 
            <button type="button" class="btn btn-sm btn-danger" onclick="window.location.href='machinetracking'"><i class="icon-hammer-wrench"></i>
                &nbsp;&nbsp;Machine Incident
            </button>
          </div>
            <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header transactionProductsTable" style="font-size: 1em;">
              <thead>
                <tr>
                  <th>Item Description</th>
                  <th>Item ID</th>
                  <th>Cost</th>
                  <th class="text-center" style="min-width:40px;">Act</th>
                </tr>
              </thead>
            </table>
        </div>   
      </div>

      <!-- Subcomponents Item List -->
      <div class="col-md-5 subcomponents_table" style="padding-left: 0px;margin-top: 13px;display: none;">
        <div class="card h-95">
          <div class="card-header header-elements-inline">
            <h5 class="card-title datatable-form-title" style="margin-top:5px;">SUBCOMPONENTS</h5> 
            <button type="button" class="btn btn-sm btn-danger" onclick="window.location.href='machinetracking'"><i class="icon-hammer-wrench"></i>
                &nbsp;&nbsp;Machine Incident
            </button>
          </div>
            <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header transactionSubcomponentsTable" style="font-size: 1em;">
              <thead>
                <tr>
                  <th>Item Description</th>
                  <th>Item ID</th>
                  <th>Cost</th>
                  <th class="text-center" style="min-width:40px;">Act</th>
                </tr>
              </thead>
            </table>
        </div>   
      </div>

      <!-- Raw Materials Item List -->
      <div class="col-md-5 recycle_table" style="padding-left: 0px;margin-top: 13px;display: none;">
        <div class="card h-95">
          <div class="card-header header-elements-inline">
            <h5 class="card-title datatable-form-title" style="margin-top:5px;">RAW MATERIALS</h5>
            <button type="button" class="btn btn-sm btn-danger" onclick="window.location.href='machinetracking'"><i class="icon-hammer-wrench"></i>
                &nbsp;&nbsp;Machine Incident
            </button> 
          </div>
            <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header transactionRecycleTable" style="font-size: 1em;">
              <thead>
                <tr>
                  <th>Item Description</th>
                  <th>Item ID</th>
                  <th>Cost</th>
                  <th class="text-center" style="min-width:40px;">Act</th>
                </tr>
              </thead>
            </table>
        </div>   
      </div>

      <!-- Waste/Damages Item List -->
      <div class="col-md-5 waste_table" style="padding-left: 0px;margin-top: 13px;display: none;">
        <div class="card h-95">
          <div class="card-header header-elements-inline">
            <h5 class="card-title datatable-form-title" style="margin-top:5px;">WASTE/DAMAGES</h5> 
          </div>
            <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header transactionWasteTable" style="font-size: 1em;">
              <thead>
                <tr>
                  <th>Item Description</th>
                  <th>Item ID</th>
                  <th>Cost</th>
                  <th class="text-center" style="min-width:40px;">Act</th>
                </tr>
              </thead>
            </table>
        </div>   
      </div>

    </div>  <!-- row -->
  </form>
</div>

<!-- ============== Search Production ============ -->
<div id="modal-search-prodoperator" class="modal" tabindex="-1">
  <div class="modal-dialog modal-full modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;PRODUCTION LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <div class="row" pb-0 style="margin:10px;margin-bottom: -20px;">
            <div class="col-sm-3 form-group">
              <label for="lst-empid" id="lbl-lst-empid" style="color:aqua;">= &gt; Operated by</label>
              <select data-placeholder="< Select Employee >" class="form-control select-search" id="lst-empid" name="lst-empid">
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
              <label for="lst-etype" id="lbl-lst-etype" style="color:aqua;">= &gt; Prod Type</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="lst-etype" name="lst-etype" required>
                <option></option>
                <option value="Finished Goods">Finished Goods</option>
                <option value="Subcomponents">Subcomponents</option>
                <option value="Recycle">Recycle</option>
              </select>
            </div> 

            <div class="col-sm-1 form-group">
              <label for="lst-prodstatus" id="lbl-lst-prodstatus" style="color:aqua;">= &gt; Status</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="lst-prodstatus" name="lst-prodstatus" required>
                <option></option>
                <option value="Posted">Posted</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div> 
          </div>  

          <div class="h-divider" style="margin-bottom:-21px;"></div>

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header prodoperatorTransactionTable">
          <thead>
            <tr>
              <th min-width="10%">Date</th>
              <th min-width="25%">Operator</th>
              <th min-width="10%">Prod #</th>
              <th min-width="10%">Shift</th>
              <th min-width="30%">Machine</th>
              <th min-width="15%">Type</th>
              <th style="max-width:120px;">Cost</th>
              <th style="max-width:100px;">Act</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
    </div>
  </div>
</div>

<script src="views/js/prodoperator.js"></script>