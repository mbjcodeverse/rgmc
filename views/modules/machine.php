<!-- Vertical form options -->
<div class="container-fluid">
  <form role="form" id="machine-form" method="POST" enctype="multipart/form-data" autocomplete="nope">
  <!-- <form class="product-list-form" method="POST" autocomplete="nope"> -->
    <div class="row">
      <div class="col-md-9" style="padding-left: 0px;padding-left: 12px;margin-top: 20px;">
      <!-- <form id="form-stock-replenishment" method="POST" autocomplete="nope"> -->
        <div class="card">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 3px;padding-right: 1px;">
            <h5 class="card-title flex-grow-1 transaction-name" style="padding-top: 14px;">&nbsp;&nbsp;MACHINE INFORMATION</h5>
            <!-- Transaction Type -->
            <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          </div>

          <div class="card-body">
              <div class="row">
                <!-- <input type="hidden" id="idUser" name="idUser" value=""> -->

                <div class="col-sm-3 form-group">
                  <label for="sel-classcode">Classification</label>
                  <select data-placeholder="< Select Classification >" class="form-control select-search" id="sel-classcode" name="sel-classcode" required>
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
                  <label for="sel-machtype">Type</label>
                  <select data-placeholder="< Select Type >" class="form-control select" data-fouc id="sel-machtype" name="sel-machtype" required>
                    <option></option>
                    <option value="Machine">Machine</option>
                    <option value="Auxiliary">Auxiliary</option>
                  </select>
                </div>                                

                <div class="col-sm-2 form-group">
                  <label for="sel-buildingcode">Building</label>
                  <select data-placeholder="< Select Bldg >" class="form-control select-search" id="sel-buildingcode" name="sel-buildingcode" required>
                    <option></option>
                    <?php
                        $building = (new ControllerBuilding)->ctrShowBuildingList();
                        foreach ($building as $key => $value) {
                          echo '<option value="'.$value["buildingcode"].'">'.$value["buildingname"].'</option>';
                        }
                     ?>
                  </select>
                </div>              

                <div class="col-md-1 form-group">
                    <label for="num-isactive">State</label>
                    <div class="custom-control custom-checkbox custom-control-inline">
                      <input type="checkbox" class="custom-control-input" id="num-isactive" name="num-isactive" value="1" checked>
                      <label class="custom-control-label" for="num-isactive">Active</label>
                    </div>
                </div>                

                <div class="col-sm-1 form-group">
                    <label for="txt-machineid">Mach ID</label>
                    <input type="text" class="form-control profile-code" id="txt-machineid" name="txt-machineid" autocomplete="nope" required readonly="true">
                </div> 

                <div class="col-sm-3 form-group">
                  <label for="sel-categorycode" id="lbl-lst-categorycode" style="color:aqua;">= &gt; Product Category Output</label>
                  <select data-placeholder="< Select Category >" class="form-control select-search" id="sel-categorycode" name="sel-categorycode">
                    <option></option>
                    <?php
                        $category = (new ControllerCategory)->ctrShowCategoryGoodsList();
                        foreach ($category as $key => $value) {
                          echo '<option value="'.$value["categorycode"].'">'.$value["catdescription"].'</option>';
                        }
                    ?>
                  </select>
                </div>                                
             </div>

             <div class="row">
                <div class="col-sm-2 form-group">
                    <input type="text" class="form-control" id="txt-machabbr" name="txt-machabbr" autocomplete="nope" placeholder="Abbr">
                </div> 

                <div class="col-sm-8 form-group">
                    <input type="text" class="form-control" id="txt-machinedesc" name="txt-machinedesc" autocomplete="nope" placeholder="Enter Machine Description" required>
                </div>

                <div class="col-sm-2 form-group">
                  <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="sel-machstatus" name="sel-machstatus" required>
                    <option></option>
                    <option value="Operational">Operational</option>
                    <option value="Under Repair">Under Repair</option>
                    <option value="Under Maintenance">Under Maintenance</option>
                    <option value="Standby">Standby</option>
                  </select>                   
                </div>

                <div class="table-responsive" style="min-height:260px;max-height: clamp(65px,100vh,260px);overflow: auto;padding-top: 0px;">
                  <table class="table transaction-header-product-list">
                    <thead class="sticky-top">
                      <tr>
                        <td width="30%">Attribute</td>
                        <td width="70%">Details</td>
                      </tr>                    
                    </thead>
                    <tbody class="machineAttributes" id="machineAttributes">
                    </tbody>
                  </table>
                </div>
                <input type="hidden" name="attributelist" id="attributelist">
             </div> 
          </div>  <!-- card body -->
          
          <div class="card-footer">        
            <!-- ================== Function Buttons ================= -->
            <div class="clearfix">
              <span class="float-left">
              </span>

              <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>

              <span class="float-right">
                <button type="button" class="btn btn-light btn-lg" id="btn-new"><i class="icon-file-text mr-2"></i> New</button>

                <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-machine"><i class="icon-search4 mr-2"></i> Search</button>
               
                <button type="submit" class="btn btn-light btn-lg" id="btn-save" disabled><i class="icon-floppy-disk mr-2"></i> Save</button>

                <button type="button" class="btn btn-outline-info btn-lg" id="btn-attributes"><i class="icon-folder-plus4 mr-2"></i> Attributes</button>
              </span>
            </div> 
            <!-- ================== Function Buttons ================= -->
        </div>  <!-- footer -->
       </div>  <!-- card -->
      </div>

      <div class="col-md-3" style="padding-left: 0px;margin-top: 20px;">
        <div class="card">
           <div class="card-body">
           <!-- <input type="text" name="trans_type" id="trans_type" value="New" required> -->
           <input type="hidden" id="num-id" name="num-id">
           <hr>

           <div class="form-group row">
             <div class="col-sm-12">
               <input type="file" class="form-control" id="tns-image" name="tns-image">
               <img src="views/img/machine/default/machine.jpg" class="card-img-top preview" alt="" width="100px">
             </div>
           </div>

         </div>
      </div> 
      <!-- ========================================================================= -->

    </div>  <!-- row -->
  </form>
<!--     <?php
      // $createUser = new ControllerUsers();
      // $createUser -> ctrCreateUser();
    ?>  -->
</div>

<!-- ============== Machine List ============ -->
<div id="modal-search-machine" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;MACHINE LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;">
            <div class="col-sm-3 form-group">
               <label for="lst-classcode" id="lbl-lst-classcode" style="color:aqua;">= &gt; Classification</label>
               <select data-placeholder="< Select Machine >" class="form-control select-search" id="lst-classcode" name="lst-classcode">
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
              <label for="lst-buildingcode" id="lbl-lst-buildingcode" style="color:aqua;">= &gt; Building</label>
              <select data-placeholder="< Select Bldg >" class="form-control select-search" id="lst-buildingcode" name="lst-buildingcode" required>
                <option></option>
                <?php
                    $building = (new ControllerBuilding)->ctrShowBuildingList();
                    foreach ($building as $key => $value) {
                      echo '<option value="'.$value["buildingcode"].'">'.$value["buildingname"].'</option>';
                    }
                  ?>
              </select>
            </div>    

            <div class="col-sm-2 form-group">
              <label for="lst-isactive" id="lbl-lst-isactive" style="color:aqua;">= &gt; Status</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="lst-isactive" name="lst-isactive" required>
                <option></option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div> 

            <div class="col-sm-3"></div>

            <div class="col-sm-2 form-group">
              <label for="lst-machstatus" id="lbl-lst-machstatus" style="color:aqua;">= &gt; Condition</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="lst-machstatus" name="lst-machstatus" required>
                <option></option>
                <option value="Operational">Operational</option>
                <option value="Under Repair">Under Repair</option>
                <option value="Idle">Idle</option>
                <option value="Unusable">Unusable</option>
              </select>
            </div>            
          </div>  

          <div class="h-divider"></div>

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header machineListTable">
          <thead>
            <tr>
              <th style="min-width: 375px;">Description</th>
              <th style="min-width: 180px;">Classification</th>
              <th style="min-width: 125px;">Building</th>
              <th style="min-width: 135px;">Status</th>
              <th style="min-width: 160px;">Condition</th>
              <th>Act</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
    </div>
  </div>
</div>

<script src="views/js/machine.js"></script>