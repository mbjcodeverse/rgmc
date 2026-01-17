<!-- Vertical form options -->
<div class="container-fluid">
  <form role="form" id="form-users" method="POST" enctype="multipart/form-data" autocomplete="nope">
  <!-- <form class="product-list-form" method="POST" autocomplete="nope"> -->
    <div class="row">
      <div class="col-md-9" style="padding-left: 0px;padding-left: 12px;margin-top: 20px;">
      <!-- <form id="form-stock-replenishment" method="POST" autocomplete="nope"> -->
        <div class="card">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 3px;padding-right: 1px;">
<!--             <img src="views/global_assets/images/rlogo.png" height="58" style="padding-top: 10px;"> -->
            <h5 class="card-title flex-grow-1 transaction-name" style="padding-top: 14px;">&nbsp;&nbsp;USER CREDENTIAL</h5>
          </div>

          <div class="card-body">
              <div class="row">
                <input type="hidden" id="idUser" name="idUser" value="">
                <div class="col-sm-4">
                  <label for="tns-empid">Employee</label>
                  <select class="form-control select-search" id="tns-empid" name="tns-empid" required>
                    <option value="" selected hidden disabled>&lt;Select Employee&gt;</option>
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
                  <label for="txt-utype">User Type</label>
                  <select class="form-control select-search" id="txt-utype" name="txt-utype" required>
                    <option value="" selected hidden disabled>Select Type</option>
                    <?php
                        $utype = (new ControllerUsers)->ctrShowUserType();
                        foreach ($utype as $key => $value) {
                          echo '<option value="'.$value["utypedesc"].'">'.$value["utypedesc"].'</option>';
                        }
                     ?>
                  </select>
                </div>

                <div class="col-sm-3" id="branch-gap"></div>              

                <div class="col-md-1 form-group">
                    <label for="num-isactive">Status</label>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="num-isactive" name="num-isactive" value="1" checked>
                      <label class="custom-control-label" for="num-isactive">Active</label>
                    </div>
                </div>                

                <div class="col-sm-1 form-group">
                    <label for="txt-userid">User ID</label>
                    <input type="text" class="form-control profile-code" id="txt-userid" name="txt-userid" autocomplete="nope" required readonly="true">
                </div>                
             </div>  
             <div class="row">
               <div class="col-sm-3 form-group">
                  <label for="tns-user">User Name</label>
                  <input type="text" class="form-control" id="tns-user" name="tns-user" placeholder="Enter User Name" autocomplete="nope" required>
               </div>

               <div class="col-sm-3 form-group">
                  <label for="password">Password<span class="text-danger">&nbsp;*</span></label>
                  <input type="password" class="form-control" id="tns-password" name="tns-password" required placeholder="Enter Password">
               </div>

               <div class="col-sm-3 form-group">
                  <label for="tns-verify">Verify Password<span class="text-danger">&nbsp;*</span></label>
                  <input type="password" class="form-control" id="tns-verify" name="tns-verify" placeholder="Verify Password" autocomplete="nope" required>
               </div>    

               <div class="col-sm-3 form-group">
                  <label for="tns-override">Override Key</label>
                  <input type="text" class="form-control" id="tns-override" name="tns-override" placeholder="Enter Override Key" autocomplete="nope">
               </div>                                         
             </div>
             <hr>
             <div class="row">
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-mt" name="chk-mt" value="0">
                      <label class="custom-control-label" for="chk-mt">Machine Tracking</label>
                    </div>
                </div>
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-ins" name="chk-ins" value="0">
                      <label class="custom-control-label" for="chk-ins">Inspection</label>
                    </div>
                </div>               
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-po" name="chk-po" value="0">
                      <label class="custom-control-label" for="chk-po">Purchase Order</label>
                    </div>
                </div>                            
            </div>

             <div class="row">
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-inc" name="chk-inc" value="0">
                      <label class="custom-control-label" for="chk-inc">Incoming Stocks</label>
                    </div>
                </div>               
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-rel" name="chk-rel" value="0">
                      <label class="custom-control-label" for="chk-rel">Releasing</label>
                    </div>
                </div>               
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-ret" name="chk-ret" value="0">
                      <label class="custom-control-label" for="chk-ret">Return</label>
                    </div>
                </div>                            
            </div> 

             <div class="row">
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-adj" name="chk-adj" value="0">
                      <label class="custom-control-label" for="chk-adj">Adjustment</label>
                    </div>
                </div>                
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-inv" name="chk-inv" value="0">
                      <label class="custom-control-label" for="chk-inv">Physical Inventory</label>
                    </div>
                </div>                
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-rep" name="chk-rep" value="0">
                      <label class="custom-control-label" for="chk-rep">Reports</label>
                    </div>
                </div>                               
            </div>

             <div class="row">
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-su" name="chk-su" value="0">
                      <label class="custom-control-label" for="chk-su">Supplier</label>
                    </div>
                </div>                
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-em" name="chk-em" value="0">
                      <label class="custom-control-label" for="chk-em">Employees</label>
                    </div>
                </div>                
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-bd" name="chk-bd" value="0">
                      <label class="custom-control-label" for="chk-bd">Building</label>
                    </div>
                </div>                              
            </div>

            <div class="row">
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-prt" name="chk-prt" value="0">
                      <label class="custom-control-label" for="chk-prt">Parts</label>
                    </div>
                </div>                
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-cat" name="chk-cat" value="0">
                      <label class="custom-control-label" for="chk-cat">Category</label>
                    </div>
                </div>                
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-bnd" name="chk-bnd" value="0">
                      <label class="custom-control-label" for="chk-bnd">Brand</label>
                    </div>
                </div>                              
            </div> 

            <div class="row">
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-mac" name="chk-mac" value="0">
                      <label class="custom-control-label" for="chk-mac">Machines</label>
                    </div>
                </div>                
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-cls" name="chk-cls" value="0">
                      <label class="custom-control-label" for="chk-cls">Classification</label>
                    </div>
                </div>                
               <div class="col-sm-1"></div>
                <div class="col-md-3 form-group">
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="chk-ac" name="chk-ac" value="0">
                      <label class="custom-control-label" for="chk-ac">Access Privilege</label>
                    </div>
                </div>                            
            </div>         

          </div>  <!-- card body -->
          
          <div class="card-footer">        
            <!-- ================== Function Buttons ================= -->
            <div class="btn-group btn-group-justified" style="margin-top: 10px;">
              <div class="btn-group">
                <button type="submit" class="btn btn-light"><i class="icon-floppy-disk"></i>&nbsp;&nbsp;Register</button>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-light btn-lg" onClick="location.href='users'"><i class="icon-search4 mr-2"></i> Browse</button>
              </div>
            </div>
            <!-- ================== Function Buttons ================= -->
        </div>  <!-- footer -->
       </div>  <!-- card -->
      </div>

      <div class="col-md-3" style="padding-left: 0px;margin-top: 20px;">
        <div class="card">
           <div class="card-body">
           <input type="text" name="trans_type" id="trans_type" value="New" style = "visibility:hidden;" required>
           <input type="hidden" id="num-id" name="num-id">
           <hr>

           <div class="form-group row">
             <div class="col-sm-12">
               <input type="file" class="form-control newPics" id="tns-photo" name="tns-photo">
               <img src="views/img/users/default/anonymous.png" class="card-img-top preview" alt="" width="100px">
             </div>
           </div>

         </div>
      </div> 
      <!-- ========================================================================= -->

    </div>  <!-- row -->
  </form>
    <?php
      $createUser = new ControllerUsers();
      $createUser -> ctrCreateUser();
    ?> 
</div>