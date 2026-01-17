<!-- Vertical form options -->
<div class="container-fluid">
  <form class="stockout-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-7" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:0;">
            <h4 class="transaction-name">RELEASING</h4>
              <!-- EMP ID -->
              <input type="hidden" name="tns-postedby" id="tns-postedby" value="<?php echo $_SESSION["empid"];?>">

              <!-- User Type -->
              <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION["utype"];?>"> 
              
              <!-- Job Order Code -->
              <input type="hidden" name="txt-inccode" id="txt-inccode" value="">

              <!-- Transaction Type -->
              <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>  

              <!-- <div class="col-sm-5 form-group" style="padding: 0px;padding-top:8px;padding-right:0px;margin:0;">
                  <select data-placeholder="< Select Machine >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-machineid" name="sel-machineid">
                    <option></option>
                    <?php
                        $machines = (new ControllerMachine)->ctrShowMachineListLocation();
                        foreach ($machines as $key => $value) {
                          echo '<option value="'.$value["machineid"].'">'.$value["machinedesc"].' > '.$value["machabbr"].' - '.$value["buildingname"].'</option>';
                        }
                     ?>
                  </select>
              </div> 
              <div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;padding-right:0px;margin:0;">
                <button type="button" class="btn btn-light" id="btn-undo"><i class="icon-undo"></i></button>
              </div> -->
              
              <!-- <div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;padding-right:10px;margin:0;">
                <select data-placeholder="< Type >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-reltype" name="sel-reltype" required>
                  <option></option>
                  <option value="Job Order">Job Order</option>
                  <option value="Generic">Generic</option>
                </select>
              </div> -->

              <!-- <div class="col-sm-2 form-group ml-auto" style="padding: 0px;padding-top:8px;padding-right:10px;margin:0;">
                <select data-placeholder="< Type >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-reltype" name="sel-reltype" required>
                  <option></option>
                  <option value="Job Order">Job Order</option>
                  <option value="Generic">Generic</option>
                </select>
              </div> -->

            <div class="d-flex ml-auto" style="gap: 5px; padding-top: 8px;">
              <!-- Machine Select -->
              <div class="form-group mb-0" style="min-width: 300px;">
                <select data-placeholder="< Select Machine >" 
                        class="form-control select-search"
                        data-container-css-class="border-secondary"
                        data-dropdown-css-class="border-secondary"
                        id="sel-machineid" name="sel-machineid">
                  <option></option>
                  <?php
                      $machines = (new ControllerMachine)->ctrShowMachineListLocation();
                      foreach ($machines as $key => $value) {
                        echo '<option value="'.$value["machineid"].'">'.$value["machinedesc"].' > '.$value["machabbr"].' - '.$value["buildingname"].'</option>';
                      }
                  ?>
                </select>
              </div>

              <!-- Undo Button -->
              <div class="form-group mb-0" style="width: 38px;">
                <button type="button" class="btn btn-light" id="btn-undo">
                  <i class="icon-undo"></i>
                </button>
              </div>

              <!-- Release Type -->
              <div class="form-group mb-0" style="min-width: 120px;margin-right:10px;margin-left:10px;">
                <select data-placeholder="< Type >"
                        class="form-control select"
                        data-container-css-class="border-secondary"
                        data-dropdown-css-class="border-secondary"
                        id="sel-reltype" name="sel-reltype" required>
                  <option></option>
                  <option value="Job Order">Job Order</option>
                  <option value="Generic">Generic</option>
                  <option value="Department">Department</option>
                </select>
              </div>
          </div>

          </div>

          <div class="card-body" style="padding-bottom: 0;margin-bottom: 0;margin-top:-10px;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
              <!-- <div class="row"> -->
                <div class="col-sm-5 form-group" style="padding: 0;">
                    <select data-placeholder="< Requested by >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-requestby" name="sel-requestby" required>
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
                  <input type="text" class="form-control datepicker bordered-textbox" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-reqdate" name="date-reqdate" required>
                </div>    

                <div class="col-sm-2 form-group" style="padding: 0px;padding-right: 7px;">
                    <input type="text" class="form-control transaction-status bordered-textbox" id="txt-reqstatus" name="txt-reqstatus" value="Posted" autocomplete="nope" required readonly="true">
                </div>                          
             
                <div class="col-sm-3 form-group" style="padding: 0px;">
                    <div class="input-group">
                    <input type="text" class="form-control transaction-id bordered-textbox" id="txt-reqnumber" name="txt-reqnumber" placeholder="Release #" autocomplete="nope" required readonly="true">
                    </div>
                </div>

                <div class="col-sm-5 form-group" style="padding: 0;margin-top:-11px;">
                  <select data-placeholder="< Select Department >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-deptcode" name="sel-deptcode">
                    <option></option>
                    <?php
                        $department = (new ControllerEmployees)->ctrShowDepartmentList();
                        foreach ($department as $key => $value) {
                          echo '<option value="'.$value["deptcode"].'">'.$value["deptname"].'</option>';
                        }
                     ?>
                  </select>            
                </div> 

                <div class="col-sm-2 form-group" style="padding: 0px;padding-right: 7px;padding-left: 7px;margin-top:-11px;">
                  <select data-placeholder="Shift" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-shift" name="sel-shift" required>
                    <option></option>
                    <option value="Day">Day</option>
                    <option value="Night">Night</option>
                  </select>
                </div>

                <div class="col-sm-2 form-group" style="padding: 0px;padding-right: 7px;margin-top:-11px;">
                    <input type="text" class="form-control transaction-status bordered-textbox" id="txt-controlnum" name="txt-controlnum" placeholder="Control #" value="" autocomplete="nope" readonly="true">
                </div> 
                
                <div class="col-sm-3 form-group" style="padding: 0px;margin-top:-11px;">
                    <div class="input-group">
                    <input type="text" class="form-control transaction-id bordered-textbox" id="txt-curstatus" name="txt-curstatus" placeholder="Job Status" autocomplete="nope" readonly="true">
                    </div>
                </div>

                <div class="table-responsive" style="min-height:395px;max-height: clamp(65px,100vh,395px);overflow: auto;padding-top: 0px;margin-top:-13px;">
                  <table class="table transaction-header-product-list">
                    <thead class="sticky-top">
                      <tr>
                      <td width="50%">Item Description</td>
                        <td width="15%" style="text-align: right;">Qty</td>
                        <td width="15%" style="text-align: right;">Unit Cost</td>
                        <td width="15%" style="text-align: right;">Amount</td>
                        <!-- <td width="80%">Item Description</td>
                        <td width="20%" style="text-align: right;">Qty</td> -->
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
                  <td style="width:15%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;">Remarks</td>
                  <td style="width:85%;padding: 3px;">
                      <input type="text" class="form-control" id="tns-remarks" name="tns-remarks" autocomplete="nope">
                  </td>
                </tr>                

              </tbody>
             </table>
            </div>
        
            <!-- ================== Function Buttons ================= -->
            <div class="btn-group btn-group-justified" style="margin-top: 10px;">
              <div class="btn-group">
                <button type="button" class="btn btn-light" id="btn-new" onClick="location.href='stockout'"><i class="icon-file-empty"></i>&nbsp;&nbsp;New</button>
              </div>

              <div class="btn-group">
                <button type="submit" class="btn btn-light" name="btn-save" id="btn-save" disabled><i class="icon-floppy-disk"></i>&nbsp;&nbsp;Save</button>
              </div>

              <div class="btn-group">
                <button type="button" class="btn btn-light" id="btn-search" data-toggle="modal" data-target="#modal-search-releasing"><i class="icon-file-text2"></i>&nbsp;&nbsp;Search</button>
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
                  <th>Item ID</th>
                  <th class="text-center" style="max-width:25px;">Act</th>
                </tr>
              </thead>
            </table>
        </div>   
      </div>

    </div>  <!-- row -->
  </form>
</div>

<!-- ============== Stock Withdrawal List ============ -->
<div id="modal-search-releasing" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 80%; width: 80%;">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;STOCK RELEASING LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;">
            <div class="col-sm-3 form-group">
              <label for="lst-empid" id="lbl-lst-empid" style="color:aqua;">= &gt; Requested by</label>
              <select data-placeholder="< Select Employee >" class="form-control select-search" id="lst-empid" name="lst-empid">
                  <option></option>
                  <?php
                    $item = null;
                    $value = null;
                    $employee = (new ControllerEmployees)->ctrRequestor();
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

            <div class="col-sm-1 form-group">
              <label for="lst-reqstatus" id="lbl-lst-reqstatus" style="color:aqua;">= &gt; Status</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="lst-reqstatus" name="lst-reqstatus" required>
                <option></option>
                <option value="Posted">Posted</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div> 

            <div class="col-sm-2 form-group">
              <label for="lst-reltype" id="lbl-lst-reltype" style="color:aqua;">= &gt; Type</label>
              <select data-placeholder="< Select Type >" class="form-control select" data-fouc id="lst-reltype" name="lst-reltype" required>
                <option></option>
                <option value="Job Order">Job Order</option>
                <option value="Generic">Generic</option>
              </select>
            </div>             
          </div>  

          <div class="h-divider" style="margin-top:-10px;"></div>

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header releasingTransactionTable">
          <thead>
            <tr>
              <th>Date</th>
              <th>Requestor</th>
              <th>Rel #</th>
              <th>Shift</th>
              <th>Control #</th>
              <th>Machine</th>
              <th>Department</th>
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

<!-- ============== Machine Tracking List ============ -->
<div id="modal-search-machine_tracking" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name" style="margin-top:-3px;"><i class="icon-menu7 mr-2"></i> &nbsp; MACHINE DIAGNOSTIC INFORMATION LIST&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <!-- -25px - reduces gap between row with comboboxes and table below -->
          <div class="row" pb-0 style="margin:10px;margin-bottom: -25px;">  
            <div class="col-sm-5 form-group">
                <label for="lst-machine_id" id="lbl-lst-machine_id" style="color:aqua;">= &gt; Machine</label>
                <select class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="lst-machine_id" name="lst-machine_id">
                    <option value="" selected hidden disabled>&lt;&nbsp;Select Machine&nbsp;&gt;</option>
                    <?php
                        $machines = (new ControllerMachine)->ctrShowMachineListLocation();
                        foreach ($machines as $key => $value) {
                          echo '<option value="'.$value["machineid"].'">'.$value["machinedesc"].' [ '.$value["machabbr"].' ] '.$value["buildingname"].'</option>';
                        }
                     ?>
                </select>
            </div>

            <div class="col-sm-2 form-group">
                <label for="lst-datemode" id="lbl-lst-datemode">Date Mode</label>
                <select data-placeholder="Select Mode" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-datemode" name="lst-datemode" required>
                    <option></option>
                    <option value="Reported" selected>Reported</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>            

            <div class="col-sm-3 form-group">
              <div class="form-group">
                <label for="list_date_range" id="lbl-lst-date-range">Date Range</label>
                <div class="input-group">
                  <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-calendar22"></i></span>
                  </span>
                  <input type="text" class="form-control daterange-basic" id="list_date_range" name="list_date_range" required> 
                </div>
              </div>
            </div>

            <div class="col-sm-2 form-group">
                <label for="lst-curstatus" id="lbl-lst-curstatus" style="color:aqua;">= &gt; Sale Status</label>
                <select data-placeholder="< Select Status >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-curstatus" name="lst-curstatus" required>
                    <option></option>
                    <option value="Operational">Operational</option>
                    <option value="Under Repair">Under Repair</option>
                    <option value="Under Maintenance">Under Maintenance</option>
                    <option value="Repair & Maintenance">Repair & Maintenance</option>
                </select>
            </div>
          </div>  

          <!-- <div class="h-divider"></div> -->

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header machine_trackingListTable">
          <thead>
            <tr>
              <th style="min-width: 130px;">Date Rep</th>
              <th style="min-width: 160px;">Time</th>
              <th style="min-width: 120px;">JO #</th>
              <th style="min-width: 325px;">Machine</th>
              <th style="min-width: 145px;">Status</th>
              <th style="min-width: 130px;">Date Comp</th>
              <th>Act</th>
            </tr>
          </thead>

          <tbody>
          </tbody>
        </table>
    </div>
  </div>
</div>

<script src="views/js/stockout.js"></script>
<script src="views/js/stockcard.js"></script>


