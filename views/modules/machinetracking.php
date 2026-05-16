<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
  <div class="col-md-10 mx-auto">
  <form class="machine-tracking-form" method="POST" autocomplete="nope">
    <div class="card form-effect" style="border:2px solid #d6d4d5;">
      <div class="card-header d-flex bg-transparent border-bottom">
        <h5 class="card-title flex-grow-1 profile-header-title">MACHINE INCIDENT</h5> 

        <input type="hidden" name="tns-postedby" id="tns-postedby" value="<?php echo $_SESSION['empid']; ?>">
        <input type="hidden" name="trans_type" id="trans_type" value="New" required>  
        <!-- <input type="hidden" name="txt-machstatus" id="txt-machstatus" value="Operational"> -->
        <input type="hidden" name="prod_opr" id="prod_opr" value="<?php echo $_SESSION['mopr']; ?>">
        <input type="hidden" name="user_level" id="user_level" value="<?php echo $_SESSION['ulevel']; ?>">  
        <input type="hidden" name="class_code" id="class_code">
        <input type="hidden" name="mt-id" id="mt-id">
        <button type="button" class="btn btn-sm btn-outline-success" id="btn-operator" style="display:none;" onclick="window.location.href='prodoperator'"><i class="icon-stack2"></i>
            &nbsp;&nbsp;Production
        </button>
        <div class="col-sm-2 form-group" style="margin-bottom:0;margin-right:-10px;">
            <input type="text" class="form-control profile-code bordered-textbox" id="txt-inccode" name="txt-inccode" autocomplete="nope" readonly="true" placeholder="Incident #">
        </div>


        <!-- Transaction Type -->
          
        <!-- <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
            <a class="list-icons-item" data-action="remove"></a>
          </div>
        </div> -->
      </div>
      <div class="h-divider" style="margin:0;">
      </div>

      <div class="card-body">
        <div class="row">
            <div class="col-sm-5 form-group">
                <label for="sel-machineid">MACHINE / EQUIPMENT</label>
                <select data-placeholder="< Select Machine >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-machineid" name="sel-machineid" required>
                    <option></option>
                    <?php
                        $machines = (new ControllerMachine)->ctrShowMachineListLocation();
                        foreach ($machines as $key => $value) {
                          echo '<option value="'.$value["machineid"].'">'.$value["machinedesc"].' [ '.$value["machabbr"].' ] '.$value["buildingname"].'</option>';
                        }
                     ?>
                </select>
            </div>

            <div class="col-sm-2 form-group">
                <label for="txt-phase">Phase</label>
                <input type="text" class="form-control profile-code bordered-textbox" id="txt-phase" name="txt-phase" autocomplete="nope" value="Pending" readonly="true">
            </div>

            <!-- <div class="col-sm-2 form-group">
                <label for="date-datereported">Incident Date</label>
                <input type="text" class="form-control datepicker bordered-textbox" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-datereported" name="date-datereported" required>
            </div> -->

            <div class="col-sm-3 form-group">
                <label for="sel-curstatus">Incident Desc</label>
                <select data-placeholder="< Description >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-curstatus" name="sel-curstatus" required>
                    <option></option>
                    <option value="Unplanned Downtime">Unplanned Downtime</option>
                    <option value="Planned Downtime">Planned Downtime</option>
                    <option value="Inspection">Inspection</option>
                    <!-- <option value="Power Outage">Power Outage</option> -->
                </select>
            </div>

            <div class="col-sm-2 form-group">
                <label for="sel-machstatus">Machine State</label>
                <select data-placeholder="< Status >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-machstatus" name="sel-machstatus" required>
                    <option></option>
                    <option value="Operational">Operational</option>
                    <option value="Offline">Offline</option>
                    <option value="Idle">Idle</option>
                </select>
            </div>            

            <!-- <div class="col-sm-2 form-group">
                <label for="txt-inccode">Inc Code</label>
                <input type="text" class="form-control profile-code bordered-textbox" id="txt-inccode" name="txt-inccode" autocomplete="nope" readonly="true">
            </div> -->
        </div>

        <div class="row" style="margin-top:-10px;">
            <div class="col-sm-3 form-group">
                <label for="sel-reporter">Incident Reporter</label>
                <select data-placeholder="< Reported by >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-reporter" name="sel-reporter" required>
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

            <div class="col-sm-2 form-group">
                <label for="date-datereported">Incident Date</label>
                <input type="text" class="form-control datepicker bordered-textbox" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-datereported" name="date-datereported" required>
            </div>

            <div class="col-sm-2 form-group">
                <label for="txt-inctime">Time Reported</label>
                <input type="text" class="form-control bordered-textbox" id="txt-inctime" name="txt-inctime" autocomplete="nope" required>
            </div>            

            <div class="col-sm-2 form-group">
                <label for="sel-shift">Shift</label>
                <select data-placeholder="[ Set Time ]" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-shift" name="sel-shift" required disabled>
                    <option></option>
                    <option value="Day">Day</option>
                    <option value="Night">Night</option>
                </select>
            </div>

            <div class="col-sm-3 form-group">
                <label for="sel-failuretype" id="lbl-lst-failuretype" style="color:aqua;">= &gt; Type of Failure</label>
                <select data-placeholder="< Select Type >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-failuretype" name="sel-failuretype" required>
                    <option></option>
                    <option value="Mechanical">Mechanical</option>
                    <option value="Electrical">Electrical</option>
                    <option value="Control System">Control System</option>
                    <option value="Hydraulic & Pneumatic">Hydraulic & Pneumatic</option>
                    <option value="Environmental">Environmental</option>
                    <option value="Lubrication">Lubrication</option>
                    <option value="Operator Error">Operator Error</option>                    
                </select>
            </div>  

            <!-- <div class="col-sm-2 form-group" style="text-align: center;visibility:hidden;">
                <label for="txt-controlnum" style="display: block;text-align: left;">Control #</label>
                <input type="number" class="form-control bordered-textbox" id="txt-controlnum" name="txt-controlnum" autocomplete="nope" style="text-align: center;" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div> -->

            <!-- <div class="col-sm-2 form-group">
                <label for="txt-controlnum">Control #</label>
                <input type="text" class="form-control bordered-textbox" id="txt-controlnum" name="txt-controlnum" autocomplete="nope">
            </div> -->
            
            <!-- <div class="col-sm-2 form-group">
                <label for="sel-severity" id="lbl-lst-severity" style="color:aqua;">= &gt; Severity</label>
                <select data-placeholder="< Status >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-severity" name="sel-severity">
                    <option></option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Critical">Critical</option>             
                </select>
            </div>              -->
        </div>

        <div class="row" style="margin-top:-10px;">
            <div class="col-sm-4 form-group">
                <label for="sel-breakid">Breakdown Information</label>
                <select data-placeholder="[ Breakdown List ]" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-breakid" name="sel-breakid" required>
                    <option></option>                   
                </select>
            </div>  
            <div class="col-sm-8 form-group">
                <label for="txt-incidentdetails">Incident Details</label>
                <textarea class="form-control bordered-textbox" id="txt-incidentdetails" name="txt-incidentdetails" rows="2" placeholder="Please provide details of the incident, including what occurred and any immediate actions that need to be taken."></textarea>
            </div>
        </div>

        <!-- <div class="row" id="completion_report" style="margin-top:-10px;display:none;"> -->
        <div class="row" id="completion_report" style="margin-top:-10px;">    
            <div class="col-sm-3 form-group">
                <label for="sel-technician">Assigned Technician</label>
                <select data-placeholder="< Assigned to >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-technician" name="sel-technician">
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
                <label for="sel-compreporter">Completion Reporter</label>
                <select data-placeholder="< Completion by >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-compreporter" name="sel-compreporter">
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

            <div class="col-sm-2 form-group">
                <label for="date-datecompleted">Completion Date</label>
                <input type="text" class="form-control datepicker bordered-textbox" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-datecompleted" name="date-datecompleted">
            </div> 
            
            <div class="col-sm-2 form-group">
                <label for="txt-endtime">Time Restored</label>
                <input type="text" class="form-control bordered-textbox" id="txt-endtime" name="txt-endtime" autocomplete="nope">
            </div>

            <div class="col-sm-1 form-group" style="display:none;">
                <label for="num-daysduration">Duration [ Days ]</label>
                <input type="text" class="form-control bordered-textbox" id="num-daysduration" name="num-daysduration" autocomplete="nope" required readonly="true">
            </div>    
            
            <div class="col-sm-2 form-group">
                <label for="num-timeduration">Duration [ Hrs ]</label>
                <input type="text" class="form-control bordered-textbox" id="num-timeduration" name="num-timeduration" autocomplete="nope" required readonly="true">
            </div>             
        </div>

        <!-- <div class="row" id="corrective_action" style="margin-top:-10px;display:none;"> -->
        <div class="row" id="corrective_action" style="margin-top:-10px;">    
            <div class="col-sm-6 form-group">
                <label for="txt-cause">Root Cause</label>
                <textarea class="form-control bordered-textbox" id="txt-cause" name="txt-cause" rows="3" placeholder="Identify and explain the underlying cause of the incident. Provide details on why the incident occurred."></textarea>
            </div>
            <div class="col-sm-6 form-group">
                <label for="txt-actiontaken">Corrective Actions Taken</label>
                <textarea class="form-control bordered-textbox" id="txt-actiontaken" name="txt-actiontaken" rows="3" placeholder="Describe the repair actions performed, including the steps taken to resolve the issue and restore the machine to working condition."></textarea>
            </div>
        </div>        

        <div class="clearfix">
          <span class="float-left">
          </span>

          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          <input type="hidden" id="num-id" name="num-id">
          <span class="float-left">
            <button type="button" class="btn btn-light btn-lg" id="btn-joborder" disabled><i class="icon-printer mr-2"></i> Print Job Order</button>
            <!-- <button type="button" class="btn btn-light btn-lg" id="btn-joborder" style="display:none;" disabled><i class="icon-printer mr-2"></i> Print Job Order</button> -->
          </span>

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new"><i class="icon-file-text mr-2"></i> New</button>

            <!-- <button type="button" class="btn btn-light btn-lg" id="btn-search"><i class="icon-search4 mr-2"></i> Search</button>             -->

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-machinetracking"><i class="icon-search4 mr-2"></i> Search</button>
           
            <button type="submit" class="btn btn-light btn-lg" id="btn-save" style="display:none;"><i class="icon-floppy-disk mr-2"></i> Save</button>

            <button type="button" class="btn btn-light btn-lg" style="display:none;color:#ff8375" name="btn-cancel" id="btn-cancel"><i class="icon-blocked"></i>&nbsp;&nbsp;Cancel</button>
          </span>
        </div>     
      </div>  <!-- card body -->

    </div>
  </form>
  </div>
</div>

<!-- ============== Machine Tracking List ============ -->
<div id="modal-search-machinetracking" class="modal" tabindex="-1">
  <div class="modal-dialog modal-full modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name" style="margin-top:-3px;"><i class="icon-menu7 mr-2"></i> &nbsp; MACHINE INCIDENT INFORMATION LIST&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        <!-- <button type="button" class="btn btn-light btn-sm" id="btn-print" style="margin-top:-5px;color:#f3fcb6;border-radius: 12px;"><i class="icon-printer"></i> &nbsp;Print Invoices</button> -->
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <!-- -25px - reduces gap between row with comboboxes and table below -->
          <div class="row" pb-0 style="margin:10px;margin-bottom: -25px;">  
            <div class="col-sm-5 form-group">
                <label for="lst-machineid" id="lbl-lst-machineid" style="color:aqua;">= &gt; Machine Description</label>
                <select class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="lst-machineid" name="lst-machineid">
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
                <label for="lst_date_range" id="lbl-lst-date-range">Date Range</label>
                <div class="input-group">
                  <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-calendar22"></i></span>
                  </span>
                  <input type="text" class="form-control daterange-basic" id="lst_date_range" name="lst_date_range" required> 
                </div>
              </div>
            </div>

            <div class="col-sm-2 form-group">
                <label for="lst-phase" id="lbl-lst-phase" style="color:aqua;">= &gt; Phase</label>
                <select data-placeholder="< Select Phase >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-phase" name="lst-phase" required>
                    <option></option>
                    <option value="- Queued -">- Queued -</option>
                    <option value="Pending">Pending</option>
                    <option value="Allocated">Allocated</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <!-- <div class="col-sm-2 form-group">
                <label for="lst-curstatus" id="lbl-lst-curstatus" style="color:aqua;">= &gt; Machine Status</label>
                <select data-placeholder="< Select Status >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-curstatus" name="lst-curstatus" required>
                    <option></option>
                    <option value="Operational">Operational</option>
                    <option value="Offline">Offline</option>
                    <option value="Idle">Idle</option>
                </select>
            </div> -->
          </div>  

          <!-- <div class="h-divider"></div> -->

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header machinetrackingListTable">
          <thead>
            <tr>
              <th style="min-width: 130px;">Date Rep</th>
              <th style="min-width: 160px;">Time</th>
              <th style="min-width: 120px;">Incident #</th>
              <th style="min-width: 325px;">Machine</th>
              <th style="min-width: 145px;">Phase</th>
              <th style="min-width: 165px;">Status</th>
              <!-- <th style="min-width: 200px;">Failure Type</th> -->
              <th style="min-width: 130px;">Date Comp</th>
              <th style="min-width: 110px;">Downtime</th>
              <th style="max-width: 110px;">Act</th>
            </tr>
          </thead>

          <!-- <tfoot>
            <tr>
                <th colspan="3" style="text-align:right;">TOTAL AMOUNT</th>
                <th><input type="text" class="form-control" id="num-totalamount" name="num-totalamount"></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
          </tfoot> -->

          <tbody>
          </tbody>
        </table>

        <!-- <div class="row">
        <div class="col-sm-2 form-group">
                <input type="text" style="font-size:1em;padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" name="num-netamount" id="num-netamount" value="0.00" required>
            </div> 
        </div> -->

    </div>
  </div>
</div>

<!-- <script src="views/js/machinetracking.js"></script> -->
<script src="views/js/machinetracking.js?v=<?php echo filemtime('views/js/machinetracking.js'); ?>"></script>

