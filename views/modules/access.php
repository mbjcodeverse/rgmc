<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
  <div class="col-md-6 mx-auto">
  <form role="form" id="form-userrights" method="POST" autocomplete="nope">
    <div class="card" style="border:1px solid rgba(255, 255, 255, 0.1);box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.7); border-radius: 10px;">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom" style="border:1px solid rgba(255, 255, 255, 0.3);box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); border-radius: 10px;">
        <h5 class="card-title flex-grow-1 profile-header-title">USER ACCESS PRIVILEGE</h5>
        <input type="hidden" name="trans_type" id="trans_type" value="New" required>
        <input type="hidden" id="txt-userid" name="txt-userid" required> 
        <input type="hidden" id="txt-username" name="txt-username" required> 
        <input type="hidden" id="txt-upassword" name="txt-upassword" required> 
        <!-- <input type="hidden" id="accessprivilege-access" name="accessprivilege-access" value="<?php echo $_SESSION["accessprivilege"];?>">
        <input type="hidden" id="superadmin-access" name="superadmin-access" value="<?php echo $_SESSION["userid"];?>"> -->

        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
            <!-- <a class="list-icons-item" data-action="reload"></a> -->
            <a class="list-icons-item" data-action="remove"></a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <label for="sel-empid">USER FULL NAME</label>
                <select class="form-control select-search" data-container-css-class="bg-indigo-400" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-empid" name="sel-empid" required>
                <option value="" selected hidden disabled>&lt;&nbsp;Select Employee&nbsp;&gt;</option>
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

            <div class="col-sm-3">
                <label for="sel-ulevel">User Level</label>
                <select data-placeholder="Select Level" class="form-control select" data-container-css-class="bg-indigo-400" data-fouc data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-ulevel" name="sel-ulevel" required>
                    <option></option>
                    <option value="Master" selected>Master</option>
                    <option value="Admin">Admin</option>
                    <option value="Regular">Regular</option>
                    <option value="Operator">Operator</option>
                </select>             
            </div>             

            <div class="col-sm-4">
                <label for="sel-utype">User Classification</label>
                <select data-placeholder="Select Type" class="form-control select" data-container-css-class="bg-indigo-400" data-fouc data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-utype" name="sel-utype" required>
                    <option></option>
                    <option value="Manufacturing" selected>Manufacturing</option>
                    <option value="Technical">Technical</option>
                </select>             
            </div>            
        </div>  

        <br>

        <div class="row" style="margin-top:-15px;" id="m1">                  
            <div class="col-sm-4 form-group">
              <label for="sel-mmd" style="color:lemonchiffon;">Dashboard</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mmd" name="sel-mmd" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-mmr">Material Recycling</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mmr" name="sel-mmr" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-mrep">Reports</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mrep" name="sel-mrep" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div> 

        <div class="row" style="margin-top:-15px;" id="m2">                  
            <div class="col-sm-4 form-group">
              <label for="sel-mip" style="color:lemonchiffon;">Incoming Stocks</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mip" name="sel-mip" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-md">Damages</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-md" name="sel-md" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-mirm">Raw Materials</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mirm" name="sel-mirm" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div> 
        
        <div class="row" style="margin-top:-15px;" id="m3">                  
            <div class="col-sm-4 form-group">
              <label for="sel-mfp" style="color:lemonchiffon;">Final Product Output</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mfp" name="sel-mfp" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-mret">Returns</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mret" name="sel-mret" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-mifp">Finished Goods</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mifp" name="sel-mifp" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div>    
        
        <div class="row" style="margin-top:-15px;" id="m4">                  
            <div class="col-sm-4 form-group">
              <label for="sel-mpc" style="color:lemonchiffon;">Product Components</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mpc" name="sel-mpc" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-mir">Raw Materials Inventory</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mir" name="sel-mir" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-mcrm">Raw Materials Category</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mcrm" name="sel-mcrm" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div>         

        <div class="row" style="margin-top:-15px;" id="m5">                  
            <div class="col-sm-4 form-group">
              <label for="sel-mrm" style="color:lemonchiffon;">Raw Material Requisition</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mrm" name="sel-mrm" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-minv">Product Inventory</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-minv" name="sel-minv" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-mcfg">Product Category</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mcfg" name="sel-mcfg" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div>    
        
        <div class="row" style="margin-top:-15px;" id="m6">                  
            <div class="col-sm-4 form-group">
              <label for="sel-mopr" style="color:lemonchiffon;">Operator Production</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-mopr" name="sel-mopr" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>       
        </div>  

        <!-- ------------------------------------------------------------>

        <div class="row" style="margin-top:-15px;" id="t1">                  
            <div class="col-sm-4 form-group">
              <label for="sel-tmd" style="color:lemonchiffon;">Dashboard</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tmd" name="sel-tmd" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-trel">Releasing</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-trel" name="sel-trel" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-tprt">Parts</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tprt" name="sel-tprt" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div>

        <div class="row" style="margin-top:-15px;" id="t2">                  
            <div class="col-sm-4 form-group">
              <label for="sel-tmt" style="color:lemonchiffon;">Machine Tracking</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tmt" name="sel-tmt" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-tret">Returns</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tret" name="sel-tret" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-tcat">Category</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tcat" name="sel-tcat" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div>
        
        <div class="row" style="margin-top:-15px;" id="t3">                  
            <div class="col-sm-4 form-group">
              <label for="sel-tmi" style="color:lemonchiffon;">Machine Inspection</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tmi" name="sel-tmi" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-tadj">Adjustment</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tadj" name="sel-tadj" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-tbr">Brand</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tbr" name="sel-tbr" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div>
        
        <div class="row" style="margin-top:-15px;" id="t4">                  
            <div class="col-sm-4 form-group">
              <label for="sel-tpo" style="color:lemonchiffon;">Purchase Order</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tpo" name="sel-tpo" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-tinv">Physical Inventory</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tinv" name="sel-tinv" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-tmac">Machines</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tmac" name="sel-tmac" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div>
        
        <div class="row" style="margin-top:-15px;" id="t5">                  
            <div class="col-sm-4 form-group">
              <label for="sel-tis" style="color:lemonchiffon;">Incoming Stocks</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tis" name="sel-tis" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-trep">Reports</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-trep" name="sel-trep" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lemonchiffon;">
              <label for="sel-tcls">Classification</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-tcls" name="sel-tcls" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div>     
        
        <!-- ------------------------------------------------------------>

        <div class="row" style="margin-top:-15px;">                  
            <div class="col-sm-4 form-group">
              <label for="sel-psup" style="color:lightgreen;">Supplier</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-psup" name="sel-psup" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lightgreen;">
              <label for="sel-pemp">Employees</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-pemp" name="sel-pemp" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lightgreen;">
              <label for="sel-paccess">Access Privelege</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-paccess" name="sel-paccess" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>            
        </div>    
        
        <div class="row" style="margin-top:-15px;">                  
            <div class="col-sm-4 form-group">
              <label for="sel-plog" style="color:lightgreen;">Login Tracker</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-plog" name="sel-plog" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-4 form-group" style="color:lightgreen;">
              <label for="sel-pcost">Other Cost</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-pcost" name="sel-pcost" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>          
        </div>         

        <div class="clearfix">
          <span class="float-left">
          </span>

          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          <input type="hidden" id="num-id" name="num-id">

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new"><i class="icon-file-text mr-2"></i> New</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-users"><i class="icon-search4 mr-2"></i> Search</button>
           
            <button type="button" class="btn btn-light btn-lg" id="btn-save"><i class="icon-floppy-disk mr-2"></i> Save</button>
          </span>
        </div>
      </div>  <!-- card body -->
    </div>
  </form>
  </div>
</div>

<div id="modal-search-users" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;USERS LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <!-- <div class="modal-body"> -->
          <table class="table datatable-scroll-y table-bordered table-striped table-hover datatable-responsive datatable-small-font profile-grid-header userRightsTable" width="100%">
          <thead>
            <tr>
              <th>Lastname</th>
              <th>Firstname</th>
              <th>MI</th>
              <th>Designation</th>
              <th>User Type</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $users = (new ControllerUserRights)->ctrShowUserList();
            foreach ($users as $key => $value) {
              if (($_SESSION["userid"] == 'U0001') || ($value["userid"] != 'U0001')){  // do show me in the accessprivilege search - if it's not me logging in to the system
                echo '<tr userId='.$value["userid"].'>
                        <td>'.$value["lname"].'</td>
                        <td>'.$value["fname"].'</td>
                        <td>'.$value["mi"].'</td>
                        <td>'.$value["positiondesc"].'</td>
                        <td>'.$value["utype"].'</td>
                      </tr>';
              }
            }
          ?>
          </tbody>
        </table>
      <!-- </div> -->

    </div>
  </div>
</div>

<script src="views/js/access.js"></script>

