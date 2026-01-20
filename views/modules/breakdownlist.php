<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">

  <div class="col-md-7 mx-auto">
  <form role="form" id="form-class" method="POST" autocomplete="nope">
    <div class="card">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom">
        <h5 class="card-title flex-grow-1 profile-header-title">MACHINE BREAKDOWN</h5> 
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

            <div class="col-sm-3 form-group">
                <label for="sel-failuretype" id="lbl-lst-failuretype">Type of Failure</label>
                <select data-placeholder="< Status >" class="form-control select" data-fouc id="sel-failuretype" name="sel-failuretype">
                    <option></option>
                    <option value="Cooling System">Cooling System</option>
                    <option value="Electrical System">Electrical System</option>
                    <option value="Heating System">Heating System</option>
                    <option value="Hydraulic System">Hydraulic System</option>
                    <option value="Lubrication & Wear">Lubrication & Wear</option>
                    <option value="Material Feeding">Material Feeding</option>
                    <option value="Mechanical">Mechanical</option>   
                    <option value="Operator/Setup">Operator/Setup</option>  
                    <option value="Pneumatic System">Pneumatic System</option> 
                    <option value="Sensor & Control System">Sensor & Control System</option>                 
                </select>
            </div>  

            <div class="col-sm-4 form-group">
                <label for="tns-details">Details</label>
                <input type="text" class="form-control" id="tns-details" name="tns-details" autocomplete="nope" required>
            </div>                                                  

            <div class="col-sm-2 form-group">
                <label for="num-classcode">Break ID</label>
                <input type="text" class="form-control profile-code" id="num-breakcode" name="num-breakcode" required readonly="true">
            </div>
        </div>
 
        <div class="clearfix">
          <span class="float-left">
            
          </span>

          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          <input type="hidden" id="num-id" name="num-id">

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new"><i class="icon-file-text mr-2"></i> New</button>
           
            <button type="submit" class="btn btn-light btn-lg"><i class="icon-floppy-disk mr-2"></i> Save</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-class"><i class="icon-search4 mr-2"></i> Search</button>            
          </span>
        </div>     
      </div>  <!-- card body -->

    </div>
  </form>
    <?php
      $createClassification = new ControllerClassification();
      $createClassification -> ctrCreateClassification();

      $editClassification = new ControllerClassification();
      $editClassification -> ctrEditClassification();
    ?>
  </div>
</div>

<div id="modal-search-class" class="modal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;MACHINE CLASS LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <div class="modal-body" style="max-height: clamp(45em,100vh,250px);overflow: auto;padding-left:10px;padding-right: 10px;padding-top: 0px;">
        <table class="table table-bordered table-hover datatable-small-font profile-grid-header classTable" width="100%">
          <thead class="sticky-top">
            <tr>
              <th>Class Name</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $machine_classes = (new ControllerClassification)->ctrShowClassificationList();
            foreach ($machine_classes as $key => $value) {
              echo '<tr idClass='.$value["id"].'>
                      <td>'.$value["classname"].'</td>
                    </tr>';
              }
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="views/js/breakdownlist.js"></script>

