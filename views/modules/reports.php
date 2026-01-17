<?php
  $next_id = (new Connection)->connect()->query("SHOW TABLE STATUS LIKE 'employees'")->fetch(PDO::FETCH_ASSOC)['Auto_increment'];
  $empid = "EM" . str_repeat("0",4-strlen($next_id)).$next_id;
?>

<!-- Vertical form options -->
<div class="row align-items-center h-100">

  <div class="col-md-3 mx-auto">
  <form role="form" id="form-add_employee" method="POST" autocomplete="nope">
    <div class="card">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom">
        <h5 class="card-title flex-grow-1" style="color:lightblue;font-size: 1.7em;">REPORT GENERATION</h5> 
        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
            <a class="list-icons-item" data-action="reload"></a>
            <a class="list-icons-item" data-action="remove"></a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
            <div class="col-sm-12 form-group">
              <div class="form-group">
                <label>Date Range</label>
                <div class="input-group">
                  <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-calendar22"></i></span>
                  </span>
                  <input type="text" class="form-control daterange-basic" id="date_range" name="date_range" required> 
                </div>
              </div>
            </div>
        </div>

        <div class="row">                  
          <div class="col-sm-12 form-group">
            <select data-placeholder="Select Transaction" class="form-control select" data-fouc id="transaction" name="transaction" id="transaction" required>
              <option value=""></option>
              <option value="Stock Replenishment" selected>Stock Replenishment</option>
              <option value="Stock Releases">Stock Releases</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12 form-group">
            <select class="form-control select-search" id="category" name="category">
              <option value="" selected hidden>&lt;&nbsp;Product Category&nbsp;&gt;</option>
              <?php
                  $category = (new ControllerProducts)->ctrShowCategory();
                  foreach ($category as $key => $value) {
                    echo '<option value="'.$value["id"].'">'.$value["catname"].'</option>';
                  }
               ?>
            </select>
          </div>          
        </div>

        <div class="btn-group btn-group-justified" style="margin-top: 10px;">
          <div class="btn-group">
            <button type="button" class="btn btn-light" id="btnCategoryDescription">&nbsp;&nbsp;Category + Product Description</button>
          </div>
        </div> 

        <div class="btn-group btn-group-justified" style="margin-top: 10px;">
          <div class="btn-group">
            <button type="button" class="btn btn-light" id="btnDetailedSequence">&nbsp;&nbsp;Transaction Detailed Sequence</button>
          </div>
        </div>

        <div class="btn-group btn-group-justified" style="margin-top: 10px;">
          <div class="btn-group">
            <button type="button" class="btn btn-light" id="btnStockCard" onClick="location.href='inventorystockcard'">&nbsp;&nbsp;Inventory + Stock Card</button>
          </div>
        </div>            
      </div>  <!-- card body -->

    </div>
  </form>
  </div>
</div>