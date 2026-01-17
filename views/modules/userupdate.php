<?php 
  $idUser = $_GET['idUser'];

  $user = (new Connection)->connect()->query("SELECT * FROM users WHERE id = $idUser")->fetch(PDO::FETCH_ASSOC);

  $idUser = $user['id'];
  $idEmployee = $user['idEmployee'];
  $user_name = $user['user'];
  $override = $user['override'];
  $utype = $user['utype'];

  if($user['photo'] == ''){
    $photo = "views/img/users/default/anonymous.png";
  }else{
    $photo = $user['photo']; 
  }
    
  $isactive = $user['isactive'];
  $sd = $user['sd'];
  $si = $user['si'];
  $so = $user['so'];
  $pi = $user['pi'];
  $rg = $user['rg'];
  $st = $user['st'];
  $pr = $user['pr'];
  $bs = $user['bs'];
  $wh = $user['wh'];
  $em = $user['em'];
  $ac = $user['ac'];
?>

<!-- Vertical form options -->
<div class="container-fluid">
  <form role="form" method="POST" enctype="multipart/form-data" autocomplete="nope">
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
                <input type="hidden" id="idUser" name="idUser" value="<?php echo $idUser;?>">

                <div class="col-sm-5">
                  <label for="editIdemployee">Employee</label>
                  <select class="form-control select-search" id="editIdemployee" name="editIdemployee" required>
                    <?php
                      $item = null;
                      $value = null;
                      $employee = (new ControllerEmployees)->ctrShowEmployees($item, $value);
                      foreach ($employee as $key => $value) {
                        $selected = ($idEmployee == $value['id'] ? 'selected="selected"' : '');
                        echo '<option value="'.$value['id'].'" '. $selected .'>'.$value['lname'].', '.$value['fname'].'</option>';
                      }
                    ?>
                  </select>              
                </div>

                <div class="col-sm-3">
                  <label for="editUtype">User Type</label>
                  <select class="form-control select-search" id="editUtype" name="editUtype" required>
                    <?php
                        $usertype = (new ControllerUsers)->ctrShowUserType();
                        foreach ($usertype as $key => $value) {
                          $selected = ($utype == $value['utypedesc'] ? 'selected="selected"' : '');
                          echo '<option value="'.$value['utypedesc'].'" '. $selected .'>'.$value['utypedesc'].'</option>';
                        }
                     ?>
                  </select>
                </div>             
                <div class="col-sm-3"></div>
                <div class="col-sm-1">
                  <label for="newIsactive">Active&nbsp;</label>
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editIsactive" name="editIsactive" value="1" <?php if ($isactive == "1") echo 'checked'; ?> data-fouc>
                    </label>
                  </div>
                </div> 
             </div>  
             <br>
             <div class="row">
               <div class="col-sm-3 form-group">
                  <label for="editUser">User Name</label>
                  <input type="text" class="form-control" id="editUser" name="editUser" placeholder="Enter User Name" value="<?php echo $user_name;?>" autocomplete="nope" required>
               </div>

               <div class="col-sm-3 form-group">
                  <label for="editPassword">Password</label>
                  <input type="text" class="form-control" id="editPassword" name="editPassword" placeholder="Enter User Name" autocomplete="nope" required>
               </div>

               <div class="col-sm-3 form-group">
                  <label for="editVerify">Verify Password</label>
                  <input type="text" class="form-control" id="editVerify" name="editVerify" placeholder="Verify Password" autocomplete="nope" required>
               </div>    

               <div class="col-sm-3 form-group">
                  <label for="editOverride">Override Key</label>
                  <input type="text" class="form-control" id="editOverride" name="editOverride" placeholder="Enter Override Key" value="<?php echo $override;?>" autocomplete="nope">
               </div>                                         
             </div>
             <hr>

             <div class="row">
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editSd" name="editSd" value="1" <?php if ($sd == "1") echo 'checked'; ?> data-fouc>Supplier Delivery
                    </label>
                  </div>
               </div>
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editBs" name="editBs" value="1" <?php if ($bs == "1") echo 'checked'; ?> data-fouc>Store Branch
                    </label>
                  </div>
               </div>
            </div>

             <div class="row">
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editSi" name="editSi" value="1" <?php if ($si == "1") echo 'checked'; ?> data-fouc>Stock Replenishment
                    </label>
                  </div>
               </div>
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editSt" name="editSt" value="1" <?php if ($st == "1") echo 'checked'; ?> data-fouc>Stakeholder
                    </label>
                  </div>
               </div>
            </div>

             <div class="row">
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editSo" name="editSo" value="1" <?php if ($so == "1") echo 'checked'; ?> data-fouc>Stock Releases
                    </label>
                  </div>
               </div>
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editPr" name="editPr" value="1" <?php if ($pr == "1") echo 'checked'; ?> data-fouc>Products
                    </label>
                  </div>
               </div>
            </div> 

             <div class="row">
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editPi" name="editPi" value="1" <?php if ($pi == "1") echo 'checked'; ?> data-fouc>Physical Inventory
                    </label>
                  </div>
               </div>
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editEm" name="editEm" value="1" <?php if ($em == "1") echo 'checked'; ?> data-fouc>Employees
                    </label>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editWh" name="editWh" value="1" <?php if ($wh == "1") echo 'checked'; ?> data-fouc>Warehouse
                    </label>
                  </div>
               </div>
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editAc" name="editAc" value="1" <?php if ($ac == "1") echo 'checked'; ?> data-fouc>Access Privelege
                    </label>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-sm-2"></div>
               <div class="col-sm-3 form-group">
                  <div class="form-check form-check-switchery">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input-switchery" id="editRg" name="editRg" value="1" <?php if ($rg == "1") echo 'checked'; ?> data-fouc>Reports
                    </label>
                  </div>
               </div>
               <div class="col-sm-5"></div>
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

      <!-- ========================================================================= -->
      <div class="col-md-3" style="padding-left: 0px;margin-top: 20px;">
        <div class="card">
           <div class="card-body">
           <!-- <div class="card-title">Product Image</div> -->
           <hr>

           <div class="form-group row">
             <div class="col-sm-12">
               <input type="file" class="form-control newPics" id="editPhoto" name="editPhoto">
               <img src="<?php echo $photo;?>" class="card-img-top preview" alt="" width="100px">
               <input type="hidden" name="currentImage" id="currentImage" value="<?php echo $photo;?>">
             </div>
           </div>

         </div>
      </div> 
      <!-- ========================================================================= -->

    </div>  <!-- row -->
  </form>
    <?php
      $editUser = new ControllerUsers();
      $editUser -> ctrEditUser();
    ?> 
</div>
