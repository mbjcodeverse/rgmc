<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
      <!-- Content area -->
      <div class="col-md-11 mx-auto">

        <!-- Page length options -->
        <div class="card">
          <div class="card-header d-flex bg-transparent border-bottom">
            <h5 class="card-title flex-grow-1" style="color:lightblue;font-size: 1.7em;">USER INFORMATION&nbsp;&nbsp;&nbsp;</h5> 
            <button type="button" class="btn btn-light" onClick="location.href='useradd'"><i class="icon-user-plus mr-2"></i> Add User</button>
<!--             <div class="header-elements">
              <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
                <a class="list-icons-item" data-action="reload"></a>
                <a class="list-icons-item" data-action="remove"></a>
              </div>
            </div> -->
          </div>

          <table class="table datatable-scroll-y table-bordered table-striped table-hover datatable-responsive datatable-small-font profile-grid-header usersTable" width="100%"">
            <thead>
              <tr>
               <th style="min-width:70px;">Photo</th> 
               <th style="min-width:100px;">Name</th>
               <th style="min-width:100px;">Username</th>
               <th style="min-width:70px;">Level</th>
               <th style="min-width:70px;">Status</th>
               <th style="min-width:110px;">Act</th>
              </tr>
            </thead>
              <tbody>
                <?php
                  $item = null; 
                  $value = null;
                  $users = (new ControllerUsers)->ctrShowUsers($item, $value);
                  foreach ($users as $key => $value) {
                    echo '<tr>';
                        if ($value["photo"] != ""){
                          echo '<td style="padding-left:55px;"><img src="'.$value["photo"].'" width="70px" style="border-radius:5px;"></td>';
                        }else{
                          echo '<td style="padding-left:55px;"><img src="views/img/users/default/anonymous.png" width="70px" style="border-radius:5px;"></td>';
                        }

                        $item = "empid";
                        $value1 = $value["empid"];
                        $employee = (new ControllerEmployees)->ctrShowEmployees($item, $value1);        
                                    
                        echo '<td>'.$employee["lname"].'</td>
                        <td>'.$value["user"].'</td>';

                        echo '<td>'.$value["utype"].'</td>';

                        // pass user ID and isactive to users.js
                        if($value["isactive"] != 0){
                          echo '<td><button class="btn btn-light rounded-round btnActivate" idUser="'.$value["id"].'" userIsactive="0">Activated</button></td>';
                        }else{
                          echo '<td><button class="btn btn-danger rounded-round btnActivate" idUser="'.$value["id"].'" userIsactive="1">Deactivated</button></td>';
                        }

                        echo '<td>
                          <button type="button" class="btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEditUser" idUser="'.$value["id"].'"><i class="icon-pencil"></i>
                          </button> 
                          <button type="button" class="btn btn-danger btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2 btnDeleteUser" idUser="'.$value["id"].'" userPhoto="'.$value["photo"].'"><i class="icon-trash"></i>
                          </button>
                        </td>
                      </tr>';
                  }
                ?>
              </tbody>
          </table>
        </div>
      </div>
    </div>

    <?php
      $deleteUser = new ControllerUsers();
      $deleteUser -> ctrDeleteUser();
    ?>
