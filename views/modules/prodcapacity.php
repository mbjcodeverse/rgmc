<!-- Vertical form options -->
<div class="container-fluid">
  <form class="prodcapacity-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-7" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:0;">
            <h4 class="transaction-name">PRODUCTION CAPACITY TRACKER</h4>
              <!-- EMP ID -->
              <input type="hidden" name="tns-postedby" id="tns-postedby" value="<?php echo $_SESSION["empid"];?>">

              <!-- User Type -->
              <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION["utype"];?>">              

              <!-- Transaction Type -->
              <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>  
          </div>

          <div class="card-body" style="padding-bottom: 0;margin-bottom: 0;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
              <!-- <div class="row"> -->
                <div class="col-sm-7 form-group" style="padding: 0;padding-right:10px;">
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
             
                <div class="col-sm-2 form-group" style="padding: 0px;">
                    <div class="input-group">
                        <input type="text" class="form-control transaction-id" id="txt-capacitynumber" name="txt-capacitynumber" placeholder="Capacity #" autocomplete="nope" required readonly="true">
                    </div>
                </div>

                <div class="col-sm-3 form-group" style="padding: 0px;padding-left:10px;">
                    <select data-placeholder="Type" class="form-control select" data-fouc id="sel-etype" name="sel-etype" required>
                        <option></option>
                        <option value="Finished Goods" selected>Finished Goods</option>
                        <option value="Subcomponents">Subcomponents</option>
                    </select>
                </div>

                <div class="table-responsive" style="min-height:418px;max-height: clamp(65px,100vh,418px);overflow: auto;padding-top: 0px;">
                  <table class="table transaction-header-product-list">
                    <thead class="sticky-top">
                      <tr>
                      <td width="50%">Item Description</td>
                        <td width="15%" style="text-align: right;">Units/Pack</td>
                        <td width="15%" style="text-align: right;">Shift Goal</td>
                        <td width="15%" style="text-align: right;">Pack Target</td>
                      </tr>                    
                    </thead>
                    <tbody class="enlisted_products" id="product_list">
                    </tbody>
                  </table>
                </div>
              </div>                          
            
              <input type="hidden" name="productList" id="productList" style="width: 100%; padding: 10px;">                
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
                <button type="button" class="btn btn-light" id="btn-new"><i class="icon-file-empty"></i>&nbsp;&nbsp;New</button>
              </div>

              <div class="btn-group">
                <button type="submit" class="btn btn-light" name="btn-save" id="btn-save" disabled><i class="icon-floppy-disk"></i>&nbsp;&nbsp;Save</button>
              </div>

              <div class="btn-group">
                <button type="button" class="btn btn-light" id="btn-search" data-toggle="modal" data-target="#modal-search-prodcapacity"><i class="icon-file-text2"></i>&nbsp;&nbsp;Search</button>
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

      <!-- Subcomponents Table -->
      <div class="col-md-5 subcomponents_table" style="padding-left: 0px;margin-top: 13px;display: none;">
        <div class="card h-95">
          <div class="card-header header-elements-inline">
            <h5 class="card-title datatable-form-title">SUBCOMPONENTS</h5> 
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

      <!-- Finished Goods Table -->
      <div class="col-md-5 finishedgoods_table" style="padding-left: 0px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header header-elements-inline">
            <h5 class="card-title datatable-form-title">FINISHED GOODS</h5> 
          </div>
            <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header transactionFinishedGoodsTable" style="font-size: 1em;">
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

<!-- ============== Machine Capacity Search ============ -->
<div id="modal-search-prodcapacity" class="modal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;MACHINE CAPACITY LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <div class="row" pb-0 style="margin:10px;margin-bottom: -20px;">
            <div class="col-sm-8 form-group">
               <label for="lst-classcode" id="lbl-lst-classcode" style="color:aqua;">= &gt; Classification</label>
               <select data-placeholder="< Select Category >" class="form-control select-search" id="lst-classcode" name="lst-classcode">
                    <option></option>
                    <?php
                        $classification = (new ControllerClassification)->ctrShowClassificationList();
                        foreach ($classification as $key => $value) {
                          echo '<option value="'.$value["classcode"].'">'.$value["classname"].'</option>';
                        }
                     ?>
               </select>
            </div>            

            <div class="col-sm-4 form-group">
              <label for="lst-etype" id="lbl-lst-etype" style="color:aqua;">= &gt; Status</label>
              <select data-placeholder="< Select Type >" class="form-control select" data-fouc id="lst-etype" name="lst-etype" required>
                <option></option>
                <option value="Finished Goods">Finished Goods</option>
                <option value="Subcomponents">Subcomponents</option>
              </select>
            </div> 
          </div>  

          <div class="h-divider" style="margin-bottom:-21px;"></div>

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header prodcapacityTransactionTable">
          <thead>
            <tr>
              <th>Machine</th>
              <th>Type</th>
              <th style="max-width:100px;">Act</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
    </div>
  </div>
</div>

<script src="views/js/prodcapacity.js"></script>


