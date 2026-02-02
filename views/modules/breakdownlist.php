<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">

  <div class="col-md-8 mx-auto">
  <form class="form-machine-breakdown" method="POST" autocomplete="nope">
    <div class="card form-effect">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom">
        <h5 class="card-title flex-grow-1 profile-header-title">MACHINE BREAKDOWN LIST</h5> 
        <input type="hidden" name="tns-postedby" id="tns-postedby" value="<?php echo $_SESSION['empid']; ?>">
        <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>  
        <input type="hidden" name="user_level" id="user_level" value="<?php echo $_SESSION['ulevel'];?>">
        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
            <a class="list-icons-item" data-action="reload"></a>
            <a class="list-icons-item" data-action="remove"></a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
            <div class="col-sm-3 form-group">
                <label for="sel-classcode">Machine Category</label>
                <select data-placeholder="< Select Category >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-classcode" name="sel-classcode" required>
                <option></option>
                <?php
                    $classification = (new ControllerClassification)->ctrShowClassificationList();
                    foreach ($classification as $key => $value) {
                        echo '<option value="'.$value["classcode"].'">'.$value["classname"].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="col-sm-3 form-group">
                <label for="sel-failuretype">Types of Failure</label>
                <select data-placeholder="< Status >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-failuretype" name="sel-failuretype" required>
                    <option></option>
                    <option value="Cooling System">Cooling System</option>
                    <option value="Electrical System">Electrical System</option>
                    <option value="Downstream Equipment">Downstream Equipment</option>
                    <option value="Heating System">Heating System</option>
                    <option value="Hydraulic System">Hydraulic System</option>
                    <option value="Heating & Sealing">Heating & Sealing</option>
                    <option value="Lubrication & Wear">Lubrication & Wear</option>
                    <option value="Material Feeding">Material Feeding</option>
                    <option value="Mechanical">Mechanical</option>  
                    <option value="Molding/Forming">Molding/Forming</option> 
                    <option value="Operator/Setup">Operator/Setup</option>  
                    <option value="Pneumatic System">Pneumatic System</option> 
                    <option value="Sensor & Control System">Sensor & Control System</option>                 
                </select>
            </div>  

            <div class="col-sm-4 form-group">
                <label for="tns-details">Details</label>
                <input type="text" class="form-control bordered-textbox" id="tns-details" name="tns-details" autocomplete="nope" required>
            </div>                                                  

            <div class="col-sm-2 form-group">
                <label for="tns-breakid">Break ID</label>
                <input type="text" class="form-control profile-code bordered-textbox" id="tns-breakid" name="tns-breakid" required readonly="true">
            </div>
        </div>
 
        <div class="clearfix">
          <span class="float-left">
            
          </span>

          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          <input type="hidden" id="num-id" name="num-id">

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new"><i class="icon-file-text mr-2"></i> New</button>
           
            <button type="submit" class="btn btn-light btn-lg"><i class="icon-floppy-disk mr-2"></i> Save</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-breakdown"><i class="icon-search4 mr-2"></i> Search</button>            
          </span>
        </div>     
      </div>  <!-- card body -->

    </div>
  </form>
  </div>
</div>

<!-- ============== Machine Breakdown List ============ -->
<div id="modal-search-breakdown" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name" style="margin-top:-3px;"><i class="icon-menu7 mr-2"></i> &nbsp; MACHINE BREAKDOWN LIST&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        <!-- <button type="button" class="btn btn-light btn-sm" id="btn-print" style="margin-top:-5px;color:#f3fcb6;border-radius: 12px;"><i class="icon-printer"></i> &nbsp;Print Invoices</button> -->
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <!-- -25px - reduces gap between row with comboboxes and table below -->
          <div class="row" pb-0 style="margin:10px;margin-bottom: -25px;">  
            <div class="col-sm-3 form-group">
                <label for="lst-classcode" id="lbl-lst-classcode" style="color:aqua;">= &gt; Machine Category</label>
                <select data-placeholder="< Select Category >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="lst-classcode" name="lst-classcode" required>
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
                <label for="lst-failuretype" id="lbl-lst-failuretype" style="color:aqua;">= &gt; Types of Failure</label>
                <select data-placeholder="< Select Type >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-failuretype" name="lst-failuretype" required>
                    <option></option>
                    <option value="Cooling System">Cooling System</option>
                    <option value="Electrical System">Electrical System</option>
                    <option value="Downstream Equipment">Downstream Equipment</option>
                    <option value="Heating System">Heating System</option>
                    <option value="Hydraulic System">Hydraulic System</option>
                    <option value="Heating & Sealing">Heating & Sealing</option>
                    <option value="Lubrication & Wear">Lubrication & Wear</option>
                    <option value="Material Feeding">Material Feeding</option>
                    <option value="Mechanical">Mechanical</option>  
                    <option value="Molding/Forming">Molding/Forming</option> 
                    <option value="Operator/Setup">Operator/Setup</option>  
                    <option value="Pneumatic System">Pneumatic System</option> 
                    <option value="Sensor & Control System">Sensor & Control System</option>
                </select>
            </div>
          </div>  

          <!-- <div class="h-divider"></div> -->

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header machinebreakdownListTable">
          <thead>
            <tr>
              <th style="min-width: 160px;">Machine Category</th>
              <th style="min-width: 242px;">Failure Type</th>
              <th style="min-width: 628px;">Details</th>
              <th>Act</th>
            </tr>
          </thead>

          <tbody>
          </tbody>
        </table>
    </div>
  </div>
</div>

<script src="views/js/breakdownlist.js"></script>

