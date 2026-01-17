<!-- Vertical form options -->
<div class="container-fluid">
  <form class="purchase-report-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-12" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:10px;">
              <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION["utype"];?>">
              <input type="hidden" name="txt-generatedby" id="txt-generatedby" value="<?php echo $_SESSION["empid"];?>">

              <h4 class="card-title flex-grow-1 transaction-name">MONITORING</h4> 

              <!-- Report Type Label -->
              <div class="col-sm-1 form-group" style="padding: 0px;padding-top:15px;padding-right:0;margin:0;">
                  <label for="lst-reptype" style="text-align: right;font-size: 1.2em;">REPORT TYPE</label>
              </div>              

              <div class="col-sm-3 form-group" style="padding: 0px;padding-top:8px;margin:0;">
                <!-- <select data-placeholder="< Select Type >" class="form-control select" data-fouc id="lst-reptype" name="lst-reptype" required> -->
                <select data-placeholder="< Select Type >" class="form-control select" data-container-css-class="bg-indigo-400" data-fouc id="lst-reptype" name="lst-reptype" required>  
                   <option></option>
                   <optgroup label="GENERIC INFO">
                      <option value="1">Machine Outage Summary</option>
                      <option value="2">Category + Machine Description</option>
                      <option value="3">Failure Type + Machine Description</option>
                      <option value="4">Service Disruption Sequence</option> 
                   <optgroup label="TEMPORAL COMPARATIVES">
                      <option value="5">Monthly Downtime Grid</option>     
                </select>
              </div>              

              <!-- Branch Label -->
              <div class="col-sm-1 form-group" style="padding: 0px;padding-top:15px;padding-right:0;margin:0;">
                  <label for="lst-machineid" id="lbl-lst-machineid" style="text-align: right;font-size: 1em;color:aqua;margin-left:15px;">= &gt; Machine</label>
              </div>

              <div class="col-sm-3 form-group" style="padding: 0px;padding-top:8px;margin:0;">
                  <select data-placeholder="< Select Machine >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="lst-machineid" name="lst-machineid">
                    <option></option>
                    <?php
                        $machines = (new ControllerMachine)->ctrShowMachineListLocation();
                        foreach ($machines as $key => $value) {
                          echo '<option value="'.$value["machineid"].'">'.$value["machinedesc"].' > '.$value["machabbr"].' - '.$value["buildingname"].'</option>';
                        }
                     ?>
                  </select>               
              </div>

              <div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;padding-left:7px;margin:0;">
                <button type="button" class="btn btn-light" disabled name="btn-print-report" id="btn-print-report" style="float:right;margin-right:10px;"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button>
                <button type="button" class="btn btn-light" disabled name="btn-export" id="btn-export" style="float:right;margin-right:19px;"><i class="icon-file-spreadsheet"></i>&nbsp;&nbsp;Exp</button>
              </div> 

              <!-- <div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;padding-left:7px;margin:0;">
                <button type="button" class="btn btn-light" disabled name="btn-print-report" id="btn-print-report" style="float:right;margin-right:19px;"><i class="icon-printer"></i></button>
              </div>     -->
          </div>

          <div class="card-body" style="padding-bottom: 0;margin: 0;padding-top: 5px;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
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

                <div class="col-sm-2 form-group">
                  <label for="lst-classcode" id="lbl-lst-classcode" style="color:aqua;">= &gt; Category</label>
                  <select data-placeholder="< Select Category >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="lst-classcode" name="lst-classcode">
                    <option></option>
                    <?php
                        $classification = (new ControllerClassification)->ctrShowClassificationList();
                        foreach ($classification as $key => $value) {
                          echo '<option value="'.$value["classcode"].'">'.$value["classname"].'</option>';
                        }
                     ?>
                  </select>
                </div> 

                <div class="col-sm-2 form-group">
                  <label for="lst-reporter" id="lbl-lst-reporter" style="color:aqua;">= &gt; Reported by</label>
                  
                  <select data-placeholder="< Select Employee >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="lst-reporter" name="lst-reporter">
                    <option></option>
                    <?php
                        $item = null;
                        $value = null;
                        $employee = (new ControllerEmployees)->ctrReporter();
                        foreach ($employee as $key => $value) {
                          echo '<option value="'.$value["empid"].'">'.$value["lname"].', '.$value["fname"].'</option>';
                        }
                      ?>
                  </select>              
                </div> 
                
                <div class="col-sm-2 form-group">
                    <label for="lst-curstatus" id="lbl-lst-curstatus" style="color:aqua;">= &gt; Status</label>
                    <select data-placeholder="< Status >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-curstatus" name="lst-curstatus" required>
                        <option></option>
                        <option value="Operational">Operational</option>
                        <option value="Under Repair">Under Repair</option>
                        <option value="Under Maintenance">Under Maintenance</option>
                        <option value="Standby">Standby</option>
                    </select>              
                </div>

                <div class="col-sm-2 form-group">
                    <label for="lst-failuretype" id="lbl-lst-failuretype" style="color:aqua;">= &gt; Type</label>
                    <select data-placeholder="< Type >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-failuretype" name="lst-failuretype" required>
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

                <div class="col-sm-1 form-group">
                  <label for="lst-shift" id="lbl-lst-shift" style="color:aqua;">= &gt; Shift</label>
                  <select data-placeholder="< Select Shift >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-shift" name="lst-shift">
                    <option></option>
                    <option value="Day">Day</option>
                    <option value="Night">Night</option>
                  </select>
                </div>   
              </div>                                        
          </div>  <!-- card body -->

          <hr style="margin:0;padding: 0;padding-bottom: 24px;">

          <div class="row tracking_content" id="tracking_content">
          </div> 
          
          <div class="card-footer" style="padding-top: 0;margin-top: 0;">
          </div>  <!-- footer -->
       </div>     <!-- card -->
      </div>

    </div>  <!-- row -->
  </form>
</div>

<script src="views/js/machinetrackingreport.js"></script>