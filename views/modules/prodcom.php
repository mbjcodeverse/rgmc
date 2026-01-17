<!-- Vertical form options -->
<div class="container-fluid">
  <form class="prodcom-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-7" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:0;">
            <h4 class="transaction-name">COMPONENTS</h4>
              <!-- EMP ID -->
              <input type="hidden" name="tns-postedby" id="tns-postedby" value="<?php echo $_SESSION["empid"];?>">

              <!-- User Type -->
              <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION["utype"];?>">              

              <!-- Transaction Type -->
              <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>  

              <div class="col-sm-5 form-group" style="padding: 0px;padding-top:8px;padding-right:0px;margin:0;">
                  <select data-placeholder="< Select Machine >" class="form-control select-search" id="sel-machineid" name="sel-machineid">
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
              </div>
              
              <div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;padding-right:30px;margin:0;margin-left:-15px;">
                <select data-placeholder="Shift" class="form-control select" data-fouc id="sel-shift" name="sel-shift" required>
                  <option></option>
                  <option value="Day">Day</option>
                  <option value="Night">Night</option>
                </select>
              </div>
          </div>

          <div class="card-body" style="padding-bottom: 0;margin-bottom: 0;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
              <!-- <div class="row"> -->
                <div class="col-sm-5 form-group" style="padding: 0;">
                    <select data-placeholder="< Operated by >" class="form-control select-search" id="sel-operatedby" name="sel-operatedby" required>
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
                    <div class="input-group">
                    <input type="text" class="form-control transaction-id" id="txt-prodnumber" name="txt-prodnumber" placeholder="Release #" autocomplete="nope" required readonly="true">
                    </div>
                </div>

                <div class="table-responsive" style="min-height:418px;max-height: clamp(65px,100vh,418px);overflow: auto;padding-top: 0px;">
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
                  <td style="width:65%;padding: 3px;">
                      <input type="text" class="form-control" id="tns-remarks" name="tns-remarks" autocomplete="nope">
                  </td>
                  <td style="width:20%;padding: 3px;">
                      <input type="text" class="form-control" style="color:#94d1f7;font-weight:bold;text-align:right;font-size:1.3em;margin-bottom:-5px;margin-top:-9px;" id="num-amount" name="num-amount" autocomplete="nope">
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
                <button type="button" class="btn btn-light" id="btn-search" data-toggle="modal" data-target="#modal-search-prodcom"><i class="icon-file-text2"></i>&nbsp;&nbsp;Search</button>
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

<!-- ============== Production Components List ============ -->
<div id="modal-search-prodcom" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;PRODUCTION COMPONENTS LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <div class="row" pb-0 style="margin:10px;margin-bottom: -20px;">
            <div class="col-sm-4 form-group">
              <label for="lst-empid" id="lbl-lst-empid" style="color:aqua;">= &gt; Operated by</label>
              <select data-placeholder="< Select Operator >" class="form-control select-search" id="lst-empid" name="lst-empid">
                  <option></option>
                  <?php
                    $item = null;
                    $value = null;
                    $employee = (new ControllerEmployees)->ctrComponentsOperators();
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
              <label for="lst-prodstatus" id="lbl-lst-prodstatus" style="color:aqua;">= &gt; Status</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="lst-prodstatus" name="lst-prodstatus" required>
                <option></option>
                <option value="Posted">Posted</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div> 
          </div>  

          <div class="h-divider" style="margin-bottom:-21px;"></div>

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header prodcomTransactionTable">
          <thead>
            <tr>
              <th>Date</th>
              <th>Requestor</th>
              <th>Rel #</th>
              <th>Shift</th>
              <th>Machine</th>
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

<script src="views/js/prodcom.js"></script>


