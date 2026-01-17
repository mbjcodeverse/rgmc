<?php 
  $fruitCategory = $_GET['fruitCategory'];

  // $inventory = (new Connection)->connect()->query("SELECT * FROM inventory WHERE id = $idInventory")->fetch(PDO::FETCH_ASSOC);

  // $id = $inventory['id'];
  // $refcode = $inventory['refcode'];
  // $tstatus = $inventory['tstatus'];
  // $tdate = $inventory['tdate'];
  // $remarks = $inventory['remarks'];
  // $idEmployee = $inventory['idEmployee']; 
  // $trans_date = substr($tdate,5,2)."/".substr($tdate,8,2)."/".substr($tdate,0,4);
  // $productlist = $inventory['productlist'];
?>
<!-- Page header -->
<div class="page-header border-bottom-0">
  <div class="page-header-content header-elements-md-inline">
    <div class="page-title d-flex">
      <h2><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">FRUITS</span> - CATEGORICAL INFORMATION</h2>
      <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>

    <div class="header-elements d-none mb-3 mb-md-0">
      <div class="d-flex justify-content-center">
        <a href="#" class="btn btn-link btn-float text-default"><i class="icon-bars-alt"></i><span>Statistics</span></a>
        <a href="#" class="btn btn-link btn-float text-default"><i class="icon-calculator"></i> <span>Deliveries</span></a>
        <a href="#" class="btn btn-link btn-float text-default"><i class="icon-calendar5"></i> <span>Schedule</span></a>
      </div>
    </div>
  </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content pt-0">
  <!-- ======= First Row Fruit Categories ===== -->
  <div class="row">
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <!-- ( Grapes ) -->
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="views/global_assets/images/fruits/grapes.jpg" alt="">
        </div>

        <div class="btn-group btn-group-justified">
          <div class="btn-group">
            <button type="button" class="btn btn-light btnFruitGrapes" data-toggle="modal" data-target="#modal-search" fruitCategory="Grapes">GRAPES</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <!-- ( Lemon ) -->
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="views/global_assets/images/fruits/lemon.jpg" alt="">
        </div>

        <div class="btn-group btn-group-justified">
          <div class="btn-group">
            <button type="button" class="btn btn-light btnFruitLemon" fruitCategory="Lemon">LEMON</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <!-- ( Apple ) -->
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="views/global_assets/images/fruits/apple.jpg" alt="">
        </div>

        <div class="btn-group btn-group-justified">
          <div class="btn-group">
            <button type="button" class="btn btn-light btnFruitApple" fruitCategory="Apple">APPLE</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <!-- ( Orange ) -->
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="views/global_assets/images/fruits/orange.jpg" alt="">
        </div>

        <div class="btn-group btn-group-justified">
          <div class="btn-group">
            <button type="button" class="btn btn-light btnFruitOrange" fruitCategory="Orange">ORANGE</button>
          </div>
        </div>
      </div>
    </div>    

  </div>    <!-- row -->

 <!-- ======= Second Row Fruit Categories ===== -->
  <div class="row">
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <!-- ( Grapes ) -->
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="views/global_assets/images/fruits/pears.jpg" alt="">
        </div>

        <div class="btn-group btn-group-justified">
          <div class="btn-group">
            <button type="button" class="btn btn-light btnFruitPears" fruitCategory="Pears">PEARS</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <!-- ( Lemon ) -->
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="views/global_assets/images/fruits/pomelo.jpg" alt="">
        </div>

        <div class="btn-group btn-group-justified">
          <div class="btn-group">
            <button type="button" class="btn btn-light btnFruitPomelo" fruitCategory="Pomelo">POMELO</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <!-- ( Apple ) -->
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="views/global_assets/images/fruits/tamarind.jpg" alt="">
        </div>

        <div class="btn-group btn-group-justified">
          <div class="btn-group">
            <button type="button" class="btn btn-light btnFruitTamarind" fruitCategory="Tamarind">TAMARIND</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <!-- ( Orange ) -->
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="views/global_assets/images/fruits/longan.jpg" alt="">
        </div>

        <div class="btn-group btn-group-justified">
          <div class="btn-group">
            <button type="button" class="btn btn-light btnFruitCategory" fruitCategory="Longan">LONGAN</button>
          </div>
        </div>
      </div>
    </div>    

  </div>    <!-- row -->  
</div>  

<div id="modal-search" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;EMPLOYEE LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <div class="modal-body">
        <table class="table table-bordered table-hover datatable-highlight datatable-responsive datatable-small-font profile-grid-header employeesTable" style="width: 100%;">
          <thead>
            <tr>
              <th>Lastname</th>
              <th>Firstname</th>
              <th>MI</th>
              <th>Position</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $employees = (new ControllerEmployees)->ctrShowEmployeesPosition();
            foreach ($employees as $key => $value) {
              echo '<tr idEmployee='.$value["id"].'>
                      <td>'.$value["lname"].'</td>
                      <td>'.$value["fname"].'</td>
                      <td>'.$value["mi"].'</td>
                      <td>'.$value["positiondesc"].'</td>
                    </tr>';
              }
          ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
