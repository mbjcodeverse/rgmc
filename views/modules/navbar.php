<!-- Main navbar -->
  <div class="navbar navbar-expand-md navbar-light fixed-top">
    <div>
      <a href="" class="d-inline-block">
        <img src="views/global_assets/images/rivsonlogo.png" alt="">
      </a>
    </div>

    <div class="d-md-none">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
        <i class="icon-tree5"></i>
      </button>
      <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
        <i class="icon-paragraph-justify3"></i>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
            <i class="icon-paragraph-justify3"></i>
          </a>
        </li>
      </ul>

<!--       <span class="badge bg-success my-3 my-md-0 ml-md-3 mr-md-auto">Online</span> -->

      <h2 class="card-title my-3 my-md-0 ml-md-3 mr-md-auto" style="color:gold;font-size: 1.5em;">RIVSON GOLDPLAST MANUFACTURING CORPORATION</h2>

      <!-- <p class="my-3 my-md-0 ml-md-3 mr-md-auto">HANDUMANAN BRANCH</p> -->

      <ul class="navbar-nav">
        <p style="padding-top: 14px;padding-right: 14px;color:#fff2fe;font-size: 1.3em;" id="current_date"></p>
        <p style="padding-top: 14px;padding-right: 14px;color:#fff2fe;font-size: 1.3em;" id="current_time"></p>
<!--         <li class="nav-item dropdown">
          <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
            <i class="icon-people"></i>
            <span class="d-md-none ml-2">Users</span>
          </a>
          
          <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-300">
            <div class="dropdown-content-header">
              <span class="font-weight-semibold">Users online</span>
              <a href="#" class="text-default"><i class="icon-search4 font-size-base"></i></a>
            </div>

            <div class="dropdown-content-body dropdown-scrollable">
              <ul class="media-list">
                <li class="media">
                  <div class="mr-3">
                    <?php 
                      if ($_SESSION["photo"] != "") {
                        echo '<img src="'.$_SESSION["photo"].'"class="rounded-circle" width="36" height="36">';
                      }else{
                        echo '<img class="rounded-circle" width="36" height="36" src="views/img/users/default/anonymous.png">';
                      }
                    ?>
                  </div>
                  <div class="media-body">
                    <a href="#" class="media-title text-white font-weight-semibold">Markh B. Jamandre</a>
                    <span class="d-block text-muted font-size-sm">Web Developer</span>
                  </div>
                  <div class="ml-3 align-self-center"><span class="badge badge-mark border-success"></span></div>
                </li>

                <li class="media">
                  <div class="mr-3">
                    <img src="views/global_assets/images/placeholders/placeholder.jpg" width="36" height="36" class="rounded-circle" alt="">
                  </div>
                  <div class="media-body">
                    <a href="#" class="media-title text-white font-weight-semibold">Angelo Gabriel R. Lamata</a>
                    <span class="d-block text-muted font-size-sm">Proprietor</span>
                  </div>
                  <div class="ml-3 align-self-center"><span class="badge badge-mark border-danger"></span></div>
                </li>

                <li class="media">
                  <div class="mr-3">
                    <img src="views/global_assets/images/placeholders/placeholder.jpg" width="36" height="36" class="rounded-circle" alt="">
                  </div>
                  <div class="media-body">
                    <a href="#" class="media-title text-white font-weight-semibold">Connie Gitano</a>
                    <span class="d-block text-muted font-size-sm">Manager</span>
                  </div>
                  <div class="ml-3 align-self-center"><span class="badge badge-mark border-success"></span></div>
                </li>
              </ul>
            </div>

            <div class="dropdown-content-footer">
              <a href="#" class="text-white mr-auto">All users</a>
              <a href="#" class="text-white"><i class="icon-gear"></i></a>
            </div>
          </div>
        </li> -->

        <li class="nav-item dropdown">
          <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
            <i class="icon-bubbles4"></i>
            <span class="d-md-none ml-2">Messages</span>
            <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0">2</span>
          </a>
          
          <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
            <div class="dropdown-content-header">
              <span class="font-weight-semibold">Messages</span>
              <a href="#" class="text-default"><i class="icon-compose"></i></a>
            </div>

            <div class="dropdown-content-body dropdown-scrollable">
              <ul class="media-list">
                <li class="media">
                  <div class="mr-3 position-relative">
                    <img src="views/global_assets/images/placeholders/placeholder.jpg" width="36" height="36" class="rounded-circle" alt="">
                  </div>

                  <div class="media-body">
                    <div class="media-title">
                      <a href="#">
                        <span class="font-weight-semibold text-white">Angelo Gabriel R. Lamata</span>
                        <span class="text-muted float-right font-size-sm">04:58</span>
                      </a>
                    </div>

                    <span class="text-muted">approved stock out request...</span>
                  </div>
                </li>

                <li class="media">
                  <div class="mr-3 position-relative">
                    <img src="views/global_assets/images/placeholders/placeholder.jpg" width="36" height="36" class="rounded-circle" alt="">
                  </div>

                  <div class="media-body">
                    <div class="media-title">
                      <a href="#">
                        <span class="font-weight-semibold text-white">Connie Gitano</span>
                        <span class="text-muted float-right font-size-sm">12:16</span>
                      </a>
                    </div>

                    <span class="text-muted">assessed warehouse inventory...</span>
                  </div>
                </li>

              </ul>
            </div>

            <div class="dropdown-content-footer justify-content-center p-0">
              <a href="#" class="text-muted w-100 py-2" data-popup="tooltip" title="Load more"><i class="icon-menu7 d-block top-0"></i></a>
            </div>
          </div>
        </li>

        <li class="nav-item dropdown dropdown-user">
          <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
            <?php 
              if ($_SESSION["photo"] != "") {
                echo '<img src="'.$_SESSION["photo"].'"class="rounded-circle mr-2" height="34" alt="">';
              }else{
                echo '<img class="rounded-circle mr-2" height="34" alt="" src="views/img/users/default/anonymous.png">';
              }
            ?>

            <?php
              $table = 'employees';
              $item = 'empid';
              $value = $_SESSION["empid"];
              $employee = (new ControllerEmployees)->ctrShowEmployees($item, $value);
              $employee_fname = $employee["fname"];
            ?>
            <span style="font-size: 1.3em;padding-bottom: 0;padding-top: 5px;"><?php echo $employee_fname; ?></span>
          </a>

          <div class="dropdown-menu dropdown-menu-right">
<!--             <a href="#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a> -->
            <a href="logout" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
  <!-- /main navbar -->

  <script src="views/js/navbar.js"></script> 