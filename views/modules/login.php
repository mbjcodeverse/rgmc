<div class="page-content">
  <div class="content-wrapper">
    <div class="content d-flex justify-content-center align-items-center pt-0">
      <form class="login-form" method="post" autocomplete="off" style="opacity:0.93; width: 400px;">
        <div class="card mb-0" style="box-shadow: 4px 4px 8px 1px rgba(0, 0, 0, 0.4); border-radius: 12px;">
          <div class="card-body">
            <div class="text-center mb-4">
              <img src="views/global_assets/images/rgmc_logo2.jpg" height="100" class="mb-3">

              <h1 class="mb-1 display-4" style="font-size: 2.0rem;">RIVSON GOLDPLAST</h1>

              <span class="text-muted d-block" style="font-size: 1.2rem; letter-spacing: 1px;">
               MANUFACTURING CORPORATION
              </span>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
              <input type="text" class="form-control custom-input" placeholder="Username" name="loginUser" id="loginUser" value="magnetar" required>
              <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
              </div>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
              <input type="password" class="form-control custom-input" placeholder="Password" name="loginPass" value="uyscuti" required>
              <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
              </div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block custom-btn">Sign in <i class="icon-circle-right2 ml-2"></i></button>
            </div>

            <div class="text-center">
              <a href="login_password_recover.html">Forgot password?</a>
            </div>
          </div>
        </div>

        <?php
          // $login = new ControllerUsers();
          $login = new ControllerUserRights();
          $login -> ctrUserLogin();
        ?>
      </form>
    </div>
  </div>
</div>

<style>
.login-form .card {
  border: 6px solid gray;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Optional: soft shadow */
  border-radius: 12px; /* Keep rounded corners */
}  
/* Enlarged inputs */
.custom-input {
  height: 50px;
  font-size: 1.1rem;
  padding: 0.6rem 1rem;
  border-radius: 8px;
  border: 1px solid gray !important;
  background-color: transparent;
  outline: none !important;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25); /* Stronger and more visible */
}

.custom-input:focus {
  border: 1px solid gray; /* Enforces light gray even on focus */
  outline: none;
}

/* Enlarged button */
/* Dark metallic "Sign in" button with light gray border */
.custom-btn {
  font-size: 1.1rem;
  padding: 0.75rem;
  border-radius: 8px;
  background: linear-gradient(to bottom, #4b4b4b, #2f2f2f);
  color: #f1f1f1;
  border: 1px solid gray; /* Updated border color */
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.1), 0 4px 10px rgba(0, 0, 0, 0.5);
  transition: all 0.25s ease-in-out;
  text-shadow: 0 1px 2px rgba(0,0,0,0.6);
}

/* Hover effect: brighten and scale */
.custom-btn:hover {
  background: linear-gradient(to bottom, #5e5e5e, #3a3a3a);
  transform: scale(1.05);
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.15), 0 6px 14px rgba(0, 0, 0, 0.6);
}

</style>

<script>
  document.getElementById("loginUser").focus();
</script>
