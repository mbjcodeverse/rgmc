<?php
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>RGMC Online</title>

  <!-- Global stylesheets -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> -->

  <link href="views/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">

  <link href="views/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">

  <!-- <link href="views/assets/css/bootstrap.css" rel="stylesheet" type="text/css"> -->

  <link href="views/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">

  <link href="views/assets/css/layout.min.css" rel="stylesheet" type="text/css">

  <link href="views/assets/css/components.min.css" rel="stylesheet" type="text/css">

  <link href="views/assets/css/colors.min.css" rel="stylesheet" type="text/css">

  <link href="views/assets/css/loader.css" rel="stylesheet" type="text/css">
  <link href="views/assets/css/mycss.css" rel="stylesheet" type="text/css">
  <link href="views/assets/css/passtrength.css" rel="stylesheet" type="text/css">
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script src="views/global_assets/js/main/jquery.min.js"></script>
  <script src="views/global_assets/js/main/bootstrap.bundle.min.js"></script>
  <script src="views/global_assets/js/plugins/loaders/blockui.min.js"></script>
  <!-- /core JS files -->

  <!-- Theme JS files -->
  <script src="views/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
  <script src="views/global_assets/js/plugins/forms/selects/select2.min.js"></script>

  <script src="views/assets/js/app.js"></script>


  <script src="views/global_assets/js/demo_pages/datatables_basic.js"></script>
  <script src="views/global_assets/js/demo_pages/datatables_advanced.js"></script>

<!--   <script src="views/global_assets/js/plugins/tables/footable/footable.min.js"></script> -->
  <script src="views/global_assets/js/demo_pages/table_responsive.js"></script>

  <script src="views/global_assets/js/demo_pages/form_checkboxes_radios.js"></script>

<!--   <script src="views/global_assets/js/plugins/sweetalert2/sweetalert2.all.js"></script> -->

  <script src="views/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
  <script src="views/global_assets/js/plugins/forms/styling/switchery.min.js"></script>
  <script src="views/global_assets/js/plugins/forms/styling/switch.min.js"></script>

  <script src="views/global_assets/js/plugins/ui/moment/moment.min.js"></script>
  <script src="views/global_assets/js/plugins/pickers/daterangepicker.js"></script>
  <script src="views/global_assets/js/plugins/pickers/anytime.min.js"></script>
  <script src="views/global_assets/js/plugins/pickers/pickadate/picker.js"></script>
  <script src="views/global_assets/js/plugins/pickers/pickadate/picker.date.js"></script>
  <script src="views/global_assets/js/plugins/pickers/pickadate/picker.time.js"></script>
  <script src="views/global_assets/js/plugins/pickers/pickadate/legacy.js"></script>
  <script src="views/global_assets/js/plugins/notifications/jgrowl.min.js"></script>
  <script src="views/global_assets/js/plugins/notifications/sweet_alert.min.js"></script>

  <script src="views/global_assets/js/plugins/notifications/pnotify.min.js"></script>

  <script src="views/global_assets/js/demo_pages/layout_fixed_sidebar_custom.js"></script>

  <script src="views/global_assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>


<!--   <script src="views/global_assets/js/plugins/forms/validation/validate.min.js"></script>
  <script src="views/global_assets/js/demo_pages/form_validation.js"></script> -->

  <!-- <script src="assets/js/app.js"></script> -->
  <script src="views/global_assets/js/demo_pages/picker_date.js"></script>

  <script src="views/global_assets/js/plugins/media/fancybox.min.js"></script>
  <script src="views/global_assets/js/demo_pages/gallery.js"></script> 

  <script src="views/global_assets/js/plugins/visualization/echarts/echarts.min.js"></script> 

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


  
  <!-- <script src="views/global_assets/js/initialization/form_checkboxes_radios.js"></script> -->
<!--   <script src="views/assets/js/app.js"></script> -->
  <!-- <script src="views/js/form_checkboxes_radios.js"></script> -->

  <!-- /theme JS files -->
</head>
<!-- <body class="navbar-top" onload="pageLoader()"> -->
  <body class="navbar-top">
  <div id="progressloader">
    <div class="straight"></div>
    <div class="curve"></div>
    <div class="center"></div>
    <div class="inner"></div>
  </div>
<!-- Site wrapper -->
  <?php
    if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == "ok"){
      // echo '<div class="wrapper" id="element">';
        if ($_SESSION["ulevel"] != "Operator"){
          include "modules/navbar.php";
          echo '<div class="page-content">';
          include "modules/sidebar.php";
          echo '<div class="content-wrapper">';
        }else{
          include "modules/navbarsimple.php";
          echo '<div class="page-content">';
          echo '<div class="content-wrapper">';
        }
      // echo '<div class="preloader"></div>';
      if(isset($_GET["route"])){
        if ($_GET["route"] == 'home' ||
            $_GET["route"] == 'default' ||
            $_GET["route"] == 'users' ||
            $_GET["route"] == 'useradd' ||
            $_GET["route"] == 'userupdate' ||
            $_GET["route"] == 'category' ||
            $_GET["route"] == 'categoryrawmats' ||
            $_GET["route"] == 'categorygoods' ||
            $_GET["route"] == 'employees' ||
            $_GET["route"] == 'supplier' ||
            $_GET["route"] == 'brand' ||
            $_GET["route"] == 'items' ||         
            $_GET["route"] == 'purchaseorder' ||
            $_GET["route"] == 'machinetracking' ||  
            $_GET["route"] == 'incoming' ||
            $_GET["route"] == 'reports' ||
            $_GET["route"] == 'keydatatable' ||
            $_GET["route"] == 'classification' ||
            $_GET["route"] == 'building' ||
            $_GET["route"] == 'machine' ||
            $_GET["route"] == 'inventory' ||
            $_GET["route"] == 'stockout' ||
            $_GET["route"] == 'return' ||
            $_GET["route"] == 'returnreport' ||
            $_GET["route"] == 'inventorystockcard' ||
            $_GET["route"] == 'incomingreport' ||
            $_GET["route"] == 'releasereport' ||
            $_GET["route"] == 'purchasereport' ||
            $_GET["route"] == 'itemreport' ||
            $_GET["route"] == 'rawmats' ||
            $_GET["route"] == 'finishedgoods' ||
            $_GET["route"] == 'incomingrawmats' ||
            $_GET["route"] == 'rawout' ||
            $_GET["route"] == 'rawoutreport' ||
            $_GET["route"] == 'prodcom' ||
            $_GET["route"] == 'prodfin' ||
            $_GET["route"] == 'access' ||
            $_GET["route"] == 'prodcomreport' ||
            $_GET["route"] == 'prodfinreport' ||
            $_GET["route"] == 'recycle' ||
            $_GET["route"] == 'debris' ||
            $_GET["route"] == 'resetloginaccount' ||
            $_GET["route"] == 'inventoryrawmats' ||
            $_GET["route"] == 'factorydashboard' ||
            $_GET["route"] == 'loginreport' ||
            $_GET["route"] == 'debrisreport' ||
            $_GET["route"] == 'recyclereport' ||
            $_GET["route"] == 'excess' ||
            $_GET["route"] == 'matreturn' ||
            $_GET["route"] == 'matreturnreport' ||
            $_GET["route"] == 'excessreport' ||
            $_GET["route"] == 'othercost' ||
            $_GET["route"] == 'prodmetrics' ||
            $_GET["route"] == 'switchview' ||
            $_GET["route"] == 'prodwaste' ||
            $_GET["route"] == 'joborder' ||
            $_GET["route"] == 'machinetrackingreport' ||
            $_GET["route"] == 'prodcapacity' ||
            $_GET["route"] == 'prodoperator' ||
            $_GET["route"] == 'quotareport' ||
            $_GET["route"] == 'resetoperatoraccount' ||
            $_GET["route"] == 'logout'){
            include "modules/".$_GET["route"].".php";
        }else{
           include "modules/404.php";
        }
      }else{
        //include "modules/home.php"; 
        include "modules/default.php";
      }
 
      // include "modules/footer.php";
      echo '</div>';
      echo '</div>';  // page content
      // echo '</div>';  // wrapper
    }else{
       // include "modules/navbarlogin.php";
       include "modules/login.php";
       // include "modules/footer.php";
    }
  ?>
<!-- ./wrapper -->

<script>
  // $("#progressloader").css("display", "none");
  $("#progressloader").fadeOut(500);
  // $("#progressloader").fadeOut(1000, function() {
    // $(".page-content").fadeIn(1000);        
  // });
</script>  

<!-- Forms -->
<script src="views/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="views/global_assets/js/initialization/form_inputs.js"></script>

<!-- Forms Checkboxes Radios -->
<!-- <script src="views/global_assets/js/plugins/forms/styling/switchery.min.js"></script>
<script src="views/global_assets/js/plugins/forms/styling/switch.min.js"></script>
<script src="views/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="views/global_assets/js/initialization/form_checkboxes_radios.js"></script> -->

<!-- Select 2 -->
<script src="views/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="views/global_assets/js/plugins/forms/selects/select2.min.js"></script>


<!-- Form Input Groups -->
<script src="views/global_assets/js/plugins/forms/inputs/touchspin.min.js"></script>
<script src="views/global_assets/js/initialization/form_input_groups.js"></script>  


<!-- Form Control Extended -->
<!-- <script src="views/global_assets/js/plugins/forms/inputs/inputmask.js"></script> -->
<script src="views/global_assets/js/plugins/forms/inputs/autosize.min.js"></script>
<script src="views/global_assets/js/plugins/forms/inputs/formatter.min.js"></script>

<script src="views/global_assets/js/plugins/forms/inputs/passy.js"></script>
<script src="views/global_assets/js/plugins/forms/inputs/maxlength.min.js"></script>
<script src="views/global_assets/js/initialization/form_controls_extended.js"></script>

<!-- Form Validation -->
<script src="views/global_assets/js/plugins/forms/validation/validate.min.js"></script>
<script src="views/global_assets/js/initialization/form_validation.js"></script> 

<!-- Form Actions -->
<script src="views/global_assets/js/initialization/form_actions.js"></script> 



<!-- JQuery UI thing -->
<script src="views/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="views/global_assets/js/plugins/extensions/jquery_ui/widgets.min.js"></script>
<script src="views/global_assets/js/plugins/extensions/jquery_ui/effects.min.js"></script>
<script src="views/global_assets/js/plugins/extensions/mousewheel.min.js"></script>
<script src="views/global_assets/js/plugins/extensions/jquery_ui/globalize/globalize.js"></script>
<script src="views/global_assets/js/plugins/extensions/jquery_ui/globalize/cultures/globalize.culture.de-DE.js"></script>
<script src="views/global_assets/js/plugins/extensions/jquery_ui/globalize/cultures/globalize.culture.ja-JP.js"></script>


<!-- <script src="views/assets/js/app.js"></script> -->
<script src="views/assets/js/custom.js"></script>
<script src="views/global_assets/js/initialization/jqueryui_forms.js"></script>
<script src="views/global_assets/js/initialization/form_select2.js"></script>
<script src="views/global_assets/js/initialization/form_layouts.js"></script>

<script src="views/js/template.js"></script>
<script src="views/helpers/helper.js"></script>
<script src="views/helpers/jquery.mask.js"></script>
<script src="views/helpers/jquery.passtrength.min.js"></script>
<script src="views/helpers/shortcut.js"></script>

<script src="views/global_assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<script src="views/global_assets/js/plugins/visualization/d3/d3.min.js"></script>
<script src="views/global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>

<script src="views/js/users.js"></script>
<script src="views/js/disable_right_click.js"></script>
<script src="views/js/reports.js"></script>
<script src="views/js/stockcard.js"></script>
<script src="views/js/keydatatable.js"></script>

<!-- Customize numeric input -->
<script src="views/js/script.numeric_key_binding.js"></script>
<script>
  _gblBindNumericClasses('numeric');
</script>

<!-- <script src="views/js/script.numeric_key_binding_3dec.js"></script>
<script>
  _gblBindNumericClasses3dec('numeric3dec');
</script> -->

<script>
  $(".datepicker").datepicker();
  $(".datepicker").datepicker("option", "dateFormat", "mm/dd/yy");
</script>
</body>
<!-- <script>
  function GoInFullscreen(element) {
    if(element.requestFullscreen)
      element.requestFullscreen();
    else if(element.mozRequestFullScreen)
      element.mozRequestFullScreen();
    else if(element.webkitRequestFullscreen)
      element.webkitRequestFullscreen();
    else if(element.msRequestFullscreen)
      element.msRequestFullscreen();
  }
  GoInFullscreen($("#element").get(0));
</script> -->
</html>