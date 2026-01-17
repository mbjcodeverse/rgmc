<!-- Page header -->
<div class="page-header border-bottom-0">
  <div class="page-header-content header-elements-md-inline">
    <div class="page-title d-flex">
      <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Dashboard ( ...TENTATIVE... )</h4>
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
  <!-- Main charts -->
  <div class="row">
    <div class="col-xl-7">
      <!-- Traffic sources -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h6 class="card-title">Traffic sources</h6>
          <div class="header-elements">
            <div class="form-check form-check-right form-check-switchery form-check-switchery-sm">
              <label class="form-check-label">
                Live update:
                <input type="checkbox" class="form-input-switchery" checked data-fouc>
              </label>
            </div>
          </div>
        </div>

        <div class="card-body py-0">
          <div class="row">
            <div class="col-sm-4">
              <div class="d-flex align-items-center justify-content-center mb-2">
                <a href="#" class="btn bg-transparent border-success-300 text-success-300 rounded-round border-2 btn-icon mr-3">
                  <i class="icon-plus3"></i>
                </a>
                <div>
                  <div class="font-weight-semibold">New Incoming</div>
                  <span class="text-muted">2,349 avg</span>
                </div>
              </div>
              <div class="w-75 mx-auto mb-3" id="new-visitors"></div>
            </div>

            <div class="col-sm-4">
              <div class="d-flex align-items-center justify-content-center mb-2">
                <a href="#" class="btn bg-transparent border-orange-300 text-orange-300 rounded-round border-2 btn-icon mr-3">
                  <i class="icon-watch2"></i>
                </a>
                <div>
                  <div class="font-weight-semibold">New Requisition</div>
                  <span class="text-muted">08:20 avg</span>
                </div>
              </div>
              <div class="w-75 mx-auto mb-3" id="new-sessions"></div>
            </div>

            <div class="col-sm-4">
              <div class="d-flex align-items-center justify-content-center mb-2">
                <a href="#" class="btn bg-transparent border-blue-300 text-blue-300 rounded-round border-2 btn-icon mr-3">
                  <i class="icon-accessibility"></i>
                </a>
                <div>
                  <div class="font-weight-semibold">Total Delivery</div>
                  <span class="text-muted"><span class="badge badge-mark border-success mr-2"></span> 5,378 avg</span>
                </div>
              </div>
              <div class="w-75 mx-auto mb-3" id="total-online"></div>
            </div>
          </div>
        </div>

        <div class="chart position-relative" id="traffic-sources"></div>
      </div>
      <!-- /traffic sources -->
    </div>

    <div class="col-xl-5">
      <!-- Sales stats -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h6 class="card-title">Delivery Statistics</h6>
          <div class="header-elements">
            <select class="form-control custom-select" id="select_date">
              <option value="val1">June, 29 - July, 5</option>
              <option value="val2">June, 22 - June 28</option>
              <option value="val3" selected>June, 15 - June, 21</option>
              <option value="val4">June, 8 - June, 14</option>
            </select>
                  </div>
        </div>

        <div class="card-body py-0">
          <div class="row text-center">
            <div class="col-4">
              <div class="mb-3">
                <h5 class="font-weight-semibold mb-0">5,689</h5>
                <span class="text-muted font-size-sm">new delivery</span>
              </div>
            </div>

            <div class="col-4">
              <div class="mb-3">
                <h5 class="font-weight-semibold mb-0">32,568</h5>
                <span class="text-muted font-size-sm">this month</span>
              </div>
            </div>

            <div class="col-4">
              <div class="mb-3">
                <h5 class="font-weight-semibold mb-0">23,464</h5>
                <span class="text-muted font-size-sm">expected cost</span>
              </div>
            </div>
          </div>
        </div>

        <div class="chart mb-2" id="app_sales"></div>
        <div class="chart" id="monthly-sales-stats"></div>
      </div>
      <!-- /sales stats -->
    </div>
  </div>
  <!-- /main charts -->

        <!-- Image grid -->
        <div class="mb-3">
          <h6 class="mb-0 font-weight-semibold">
            Image grid
          </h6>
          <span class="text-muted d-block">Image grid with 4 - 2 - 1 columns</span>
        </div>

  <!-- ======= First Row Fruit Categories ===== -->
  <div class="row">
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="../../../../global_assets/images/placeholders/placeholder.jpg" alt="">
          <div class="card-img-actions-overlay card-img">
            <a href="../../../../global_assets/images/placeholders/placeholder.jpg" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
              <i class="icon-plus3"></i>
            </a>

            <a href="#" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
              <i class="icon-link"></i>
            </a>
          </div>
        </div>

        <div class="card-body">
          <div class="d-flex align-items-start flex-nowrap">
            <div>
              <div class="font-weight-semibold mr-2">For ostrich much</div>
              <span class="font-size-sm text-muted">Size: 432kb</span>
            </div>

            <div class="list-icons list-icons-extended ml-auto">
              <a href="#" class="list-icons-item"><i class="icon-download top-0"></i></a>
              <a href="#" class="list-icons-item"><i class="icon-bin top-0"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="../../../../global_assets/images/placeholders/placeholder.jpg" alt="">
          <div class="card-img-actions-overlay card-img">
            <a href="../../../../global_assets/images/placeholders/placeholder.jpg" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
              <i class="icon-plus3"></i>
            </a>

            <a href="#" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
              <i class="icon-link"></i>
            </a>
          </div>
        </div>

        <div class="card-body">
          <div class="d-flex align-items-start flex-nowrap">
            <div>
              <div class="font-weight-semibold mr-2">An so vulgar</div>
              <span class="font-size-sm text-muted">Size: 543kb</span>
            </div>

            <div class="list-icons list-icons-extended ml-auto">
              <a href="#" class="list-icons-item"><i class="icon-download top-0"></i></a>
              <a href="#" class="list-icons-item"><i class="icon-bin top-0"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="../../../../global_assets/images/placeholders/placeholder.jpg" alt="">
          <div class="card-img-actions-overlay card-img">
            <a href="../../../../global_assets/images/placeholders/placeholder.jpg" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
              <i class="icon-plus3"></i>
            </a>

            <a href="#" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
              <i class="icon-link"></i>
            </a>
          </div>
        </div>

        <div class="card-body">
          <div class="d-flex align-items-start flex-nowrap">
            <div>
              <div class="font-weight-semibold mr-2">Not rapturous</div>
              <span class="font-size-sm text-muted">Size: 219kb</span>
            </div>

            <div class="list-icons list-icons-extended ml-auto">
              <a href="#" class="list-icons-item"><i class="icon-download top-0"></i></a>
              <a href="#" class="list-icons-item"><i class="icon-bin top-0"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-img-actions mx-1 mt-1">
          <img class="card-img img-fluid" src="../../../../global_assets/images/placeholders/placeholder.jpg" alt="">
          <div class="card-img-actions-overlay card-img">
            <a href="../../../../global_assets/images/placeholders/placeholder.jpg" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
              <i class="icon-plus3"></i>
            </a>

            <a href="#" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
              <i class="icon-link"></i>
            </a>
          </div>
        </div>

        <div class="card-body">
          <div class="d-flex align-items-start flex-nowrap">
            <div>
              <div class="font-weight-semibold mr-2">He it otherwise</div>
              <span class="font-size-sm text-muted">Size: 329kb</span>
            </div>

            <div class="list-icons list-icons-extended ml-auto">
              <a href="#" class="list-icons-item"><i class="icon-download top-0"></i></a>
              <a href="#" class="list-icons-item"><i class="icon-bin top-0"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>    <!-- row -->
</div>  