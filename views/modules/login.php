<div class="page-content">
  <div class="content-wrapper">
    <div class="content d-flex justify-content-center align-items-center pt-0">
      <form class="login-form" method="post" autocomplete="off" style="opacity:0.93; width: 400px;">
        <div class="card mb-0" style="box-shadow: 4px 4px 20px 5px rgba(0, 0, 0, 0.9); border-radius: 20px;">
          <div class="card-body">
            <div class="text-center mb-4">
              <img src="views/global_assets/images/rgmc_logo2.jpg" height="100" class="mb-3">

              <h1 class="mb-1 display-4 gold-accent" style="font-size: 2.0rem;">RIV GOLDPLAST</h1>

              <span class="d-block" style="font-size: 1.2rem; letter-spacing: 1px;color:gold;font-weight:600;">
               MANUFACTURING CORPORATION
              </span>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
              <input type="text" class="form-control custom-input" name="loginUser" id="loginUser" value="" required>
              <div class="form-control-feedback">
                <i class="icon-user"></i> <!-- Icon color will be gold -->
              </div>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
              <input type="password" class="form-control custom-input" name="loginPass" value="" required>
              <div class="form-control-feedback">
                <i class="icon-lock2"></i> <!-- Icon color will be gold -->
              </div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block custom-btn">Sign in <i class="icon-circle-right2 ml-2"></i></button>
            </div>

            <div class="text-center">
              <a href="login_password_recover.html" style="color:gold;">Forgot password?</a>
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

.form-control-feedback {
  position: absolute;
  top: 50%;
  left: 15px; /* Adjust this value as needed */
  transform: translateY(-50%);
}

.form-control-feedback i {
  font-size: 1.6rem; /* Enlarge the icon size */
  color: #FFD700;    /* Gold color for the icon */
}



h1.gold-accent {
  font-size: 2rem;
  font-weight: 600;
  letter-spacing: 1px;
  position: relative;
  display: inline-block;
  padding-bottom: 5px; /* Further reduced space for horizontal line */
  margin-bottom: 0px; /* Further reduced space for diamond */
  background: linear-gradient(90deg, 
      #FFF176 0%, 
      #FFD700 25%, 
      #FFB300 50%, 
      #FFEB3B 75%, 
      #FFF176 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: -1px 0 0 rgba(255,255,255,0.4), -1px -1px 0 rgba(255,255,255,0.3);
  filter: none;
}

span.d-block {
  font-size: 1.2rem;
  letter-spacing: 1px;
  color: gold;
  font-weight: 600;
  position: relative;
  display: inline-block;
}

/* Create the horizontal line */
h1.gold-accent::after {
  content: '';
  position: absolute;
  bottom: -3px; /* Further reduced gap */
  left: 0;
  width: 100%;
  height: 2px;
  background-color: gold;
}

/* Create the diamond centered on the horizontal line */
h1.gold-accent::before {
  content: '';
  position: absolute;
  bottom: -5px; /* Further reduced gap for the diamond */
  left: 50%;
  transform: translateX(-50%) rotate(45deg); /* Center and rotate to form a diamond */
  width: 10px;
  height: 10px;
  background-color: gold;
  border-radius: 2px;
}




.login-form .card {
  border: 8px solid gold;
  border-radius: 20px;
  box-shadow: 0 0 20px 5px rgba(255, 215, 0, 0.7);
  animation: glow 2s infinite alternate;
}

@keyframes glow {
  0% {
    box-shadow: 0 0 10px 2px rgba(255, 215, 0, 0.2);
  }
  50% {
    box-shadow: 0 0 25px 10px rgba(255, 215, 0, 0.4);
  }
  100% {
    box-shadow: 0 0 10px 2px rgba(255, 215, 0, 0.2);
  }
}


/* .login-form .card {
  border: 6px solid lightgray;
  border-radius: 12px;
  box-shadow: 0 0 15px 3px rgba(255, 215, 0, 0.3);
  animation: glow 2.5s infinite alternate;
}

@keyframes glow {
  0% {
    box-shadow: 0 0 10px 2px rgba(255, 215, 0, 0.35);
  }
  50% {
    box-shadow: 0 0 20px 5px rgba(255, 215, 0, 0.50);
  }
  100% {
    box-shadow: 0 0 10px 2px rgba(255, 215, 0, 0.35);
  }
} */



/* Enlarged inputs */
.custom-input {
  height: 50px;
  font-size: 1.1rem;
  padding: 0.6rem 1rem;
  border-radius: 8px;

  /* Permanent gold border */
  border: 2px solid #FFD700 !important;

  /* Gold text inside */
  color: #FFD700;

  background-color: transparent;

  /* Prevent browser focus / autofill styles from overriding border */
  outline: none !important;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;

  /* Subtle glow */
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25), 
              0 0 8px rgba(255, 215, 0, 0.25);

  transition: box-shadow 0.3s ease, border 0.3s ease;
}

/* Focus state identical to normal */
.custom-input:focus {
  border: 2px solid #FFD700 !important; /* Keep gold border */
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25), 
              0 0 8px rgba(255, 215, 0, 0.25); /* Same glow */
  outline: none !important;
}


/* Enlarged button */
/* Dark metallic "Sign in" button with light gray border */
.custom-btn {
  font-size: 1.4rem;           /* Large text for emphasis */
  padding: 0.9rem 1.5rem;
  border-radius: 8px;

  /* Darker gold gradient for metallic effect */
  background: linear-gradient(145deg, #D4AF37, #C99A1E, #B8860B);
  color: #fff;
  font-weight: 600;            /* Lighter font weight for sleekness */
  font-family: 'Poppins', sans-serif; /* Modern, clean sans-serif font */

  /* Bevel effect: combination of outer and inner shadows */
  box-shadow:
    inset 0 3px 6px rgba(255, 255, 255, 0.4),   /* Top-left highlight */
    inset 0 -3px 6px rgba(0, 0, 0, 0.4),        /* Bottom shadow */
    0 4px 10px rgba(0, 0, 0, 0.5),             /* Outer shadow for depth */
    0 0 12px rgba(212, 175, 55, 0.3);          /* Golden glow */

  border: 2px solid #B8860B;  /* Dark gold border */

  text-shadow: 0 2px 5px rgba(0, 0, 0, 0.8);  /* Dark shadow for contrast */
  letter-spacing: 1px;        /* Subtle spacing for a clean look */
  transition: all 0.3s ease-in-out;
}

/* Hover: Reverse the bevel effect (inward pressed look) */
.custom-btn:hover {
  background: linear-gradient(145deg, #D4AF37, #C99A1E, #B8860B);  /* Keep the same background */

  /* Reversed bevel: swap inner and outer shadows */
  box-shadow:
    inset 0 -3px 6px rgba(255, 255, 255, 0.4),  /* Reverse highlight (bottom) */
    inset 0 3px 6px rgba(0, 0, 0, 0.4),        /* Reverse shadow (top) */
    0 -4px 10px rgba(0, 0, 0, 0.5),             /* Reversed outer shadow (pressed) */
    0 0 12px rgba(212, 175, 55, 0.3);          /* Golden glow remains */

  transform: scale(1);  /* No scale change */
}



</style>

<script>
  document.getElementById("loginUser").focus();
</script>
