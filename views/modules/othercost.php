<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">

  <div class="col-md-4 mx-auto">
  <form class="othercost-form" id="product-form" method="POST" autocomplete="nope">
    <div class="card form-effect">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom">
        <h5 class="card-title flex-grow-1" style="color:lightblue;font-size: 2em;">OTHER COST</h5> 
        <!-- EMP ID -->
              <input type="hidden" name="tns-postedby" id="tns-postedby" value="<?php echo $_SESSION["empid"];?>">
        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
            <!-- <a class="list-icons-item" data-action="reload"></a> -->
            <!-- <a class="list-icons-item" data-action="remove"></a> -->
          </div>
        </div>
      </div>

      <div class="card-body">
         <!-- 1st Row  -->
         <div class="row">   
            <div class="col-sm-6 form-group">
                <label for="date-odate">Date</label>
                <input type="text" class="form-control datepicker bordered-textbox" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-odate" name="date-odate" required>
            </div>
            
            <div class="col-sm-6 form-group">
                <label>Cost #</label>
                <input type="text" class="form-control transaction-status bordered-textbox" id="txt-ocostid" name="txt-ocostid" autocomplete="nope" required readonly="true">
            </div>
        </div>

        <!-- 2nd Row -->
        <div class="row">
            <div class="col-sm-6 form-group">
                <label for="num-electricity">Electricity</label>
                <input type="text" style="font-size:1em;padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control numeric hide-cursor bordered-textbox" id="num-electricity" name="num-electricity" value="0.00" autocomplete="nope">
            </div> 

            <div class="col-sm-6 form-group">
                <label for="num-manpower">Manpower</label>
                <input type="text" style="font-size:1em;padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control numeric hide-cursor bordered-textbox" id="num-manpower" name="num-manpower" value="0.00" autocomplete="nope">
            </div>        
        </div>

        <!-- 3rd Row -->
        <div class="row">
            <div class="col-sm-6 form-group">
                <label for="num-sales">Sales</label>
                <input type="text" style="font-size:1em;padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control numeric hide-cursor bordered-textbox" id="num-sales" name="num-sales" value="0.00" autocomplete="nope">
            </div>      
        </div>        
 
        <div class="clearfix">
          <span class="float-left">
          </span>
            
          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new"><i class="icon-file-text mr-2"></i> New</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-othercost"><i class="icon-search4 mr-2"></i> Search</button>

            <div class="btn-group">
              <button type="submit" class="btn btn-light btn-lg" name="btn-save" id="btn-save"><i class="icon-floppy-disk"></i>&nbsp;&nbsp;Save</button>
            </div>
          </span>
        </div>     
      </div>  <!-- card body -->

    </div>
  </form>
  </div>
</div>

<!-- ============== Other Cost List ============ -->
<div id="modal-search-othercost" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;OTHER COST LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <div class="row" pb-0 style="margin:10px;margin-bottom: -20px;">
            <div class="col-sm-4 form-group">
              <label for="lst-empid" id="lbl-lst-empid" style="color:aqua;">= &gt; Posted by</label>
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

            <div class="col-sm-4 form-group">
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
            </div>            

            <div class="col-sm-2 form-group">
              <label for="lst-ostatus" id="lbl-lst-ostatus" style="color:aqua;">= &gt; Status</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="lst-ostatus" name="lst-ostatus" required>
                <option></option>
                <option value="Posted">Posted</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div> 
          </div>  

          <div class="h-divider" style="margin-bottom:-21px;"></div>

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header othercostTransactionTable">
          <thead>
            <tr>
              <th>Date</th>
              <th>Cost #</th>
              <th>Sales</th>
              <th>Manpower</th>
              <th>Electricity</th>
              <th style="max-width:100px;">Act</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
    </div>
  </div>
</div>

<script src="views/js/othercost.js"></script>