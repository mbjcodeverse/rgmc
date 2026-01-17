<!-- Content area -->
<div class="content pt-10">
	<!-- <div class="row">
		<div class="col-sm-6 col-xl-3">
			<div class="card card-body cursor-pointer" id="counter">
				<div class="media">
					<div class="mr-3 align-self-center">
						<i class="icon-cart-add2 icon-3x text-success-400"></i>
					</div>

					<div class="media-body text-right">
						<h3 class="font-weight-semibold mb-0" id="inv-risk">0.00</h3>
						<span class="text-uppercase font-size-sm text-muted">Inventory Risk Score</span>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body cursor-pointer" id="collection">
				<div class="media">
					<div class="mr-3 align-self-center">
						<i class="icon-drawer-in icon-3x text-indigo-400"></i>
					</div>

					<div class="media-body text-right">
						<h3 class="font-weight-semibold mb-0" id="inv-health">0.00</h3>
						<span class="text-uppercase font-size-sm text-muted">Inventory Health Index</span>
					</div>
				</div>
			</div>
		</div>	

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-indigo-400 has-bg-image" id="gross">
				<div class="media">
					<div class="mr-3 align-self-center">
						<i class="icon-calculator2 icon-3x opacity-75"></i>
					</div>	
									
					<div class="media-body text-right">
						<h3 class="font-weight-semibold mb-0" id="inv-stab">0.00</h3>
						<span class="text-uppercase font-size-sm text-muted">Inventory Stability Ratio</span>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body cursor-pointer" id="credit">
				<div class="media">
					<div class="mr-3 align-self-center">
						<i class="icon-basket icon-3x text-danger-400"></i>
					</div>

					<div class="media-body text-right">
						<h3 class="font-weight-semibold mb-0" id="inv-rep">0.00</h3>
						<span class="text-uppercase font-size-sm text-muted">INVENTORY REPLENISHMENT NEED</span>
					</div>
				</div>
			</div>
		</div>					
	</div> -->

	<!-- Inner container -->
	<div class="d-flex align-items-start flex-column flex-md-row">
		<!-- Left content -->
		<div class="w-100 order-2 order-md-1">
			<div class="card">
				<div class="card-header header-elements-md-inline" style="padding-top:10px;padding-bottom:10px;">
					<h2 class="card-title" style="color:#aefce0;font-size: 2em;">AGGREGATE <span class="highlight" style="color:#fad9f4;font-size: 0.8em;">TRANSACTION</span></h2>	
					<input type="hidden" name="txt-generatedby" id="txt-generatedby" value="<?php echo $_SESSION["empid"];?>">            
				</div> <!-- card header	 -->

				<div class="nav-tabs-responsive bg-dark border-top shadow-0" style="font-size: 1.3em;">
					<ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
						<li class="nav-item">
							<a href="#statistical-data" class="nav-link active" data-toggle="tab">
								<i class="icon-stats-growth mr-2 icon-2x"></i> STATISTICS
							</a>
						</li>
						<li class="nav-item">
							<a href="#tabular-data" class="nav-link" data-toggle="tab">
								<i class="icon-book mr-2 icon-2x"></i> TABULAR
							</a>
						</li>
						<li class="nav-item">
							<a href="#assessment-data" class="nav-link" data-toggle="tab">
								<i class="icon-stack4 mr-2 icon-2x"></i> INVENTORY
							</a>
						</li>
						<li class="nav-item">
							<a href="#usage-data" class="nav-link" data-toggle="tab">
								<i class="icon-folder-upload3 mr-2 icon-2x"></i> USAGE
							</a>
						</li>

						<!-- Combo box (select dropdown) before date range -->
						<li class="nav-item ml-auto">
							<div class="d-flex align-items-center">
								<div class="mr-3" style="margin-top:7px;" id="report-type-container">
									<select data-placeholder="< Select Report Type >" class="form-control select" data-container-css-class="bg-indigo-400" data-fouc id="report-type" name="report-type" required>
										<option></option>
										<option value="1" selected>Daily - Raw Materials | Production Cost</option>
										<option value="2">Weekly - Raw Materials | Production Cost</option>
											<!-- <option value="2">Category + Item Description</option>
											<option value="3">Requisition Date Sequence Details</option>
										<optgroup label="MACHINE SPECIFIC">   
											<option value="4">Machine Item Requisition Summation</option>
											<option value="4">Machine Requisition Date Trail</option> -->
									</select>
								</div>

								<!-- <div class="mr-3" style="margin-top:7px;" id="tiered-container"></div> -->
									<div class="form-check form-check-inline form-check-right" style="margin-top:7px;" id="tiered-container">
										<label class="form-check-label">
											Trackable
											<input type="checkbox" class="form-check-input-styled" data-fouc id="chk-tier">
										</label>
									</div>
								<!-- </div> -->

								<div class="mr-3" style="margin-top:7px;" id="filter-type-container">
									<select data-placeholder="< Select Filter >" class="form-control select" data-fouc id="display-type" name="display-type" required>
										<option></option>
										<option value="insight" selected>Insight</option>
										<option value="snapshot">Snapshot</option>
									</select>
								</div>
								
								<!-- Date range input group (with icon on the left) -->
								<div class="input-group" style="margin-top:6px;margin-right:10px;">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="icon-calendar22"></i></span>
									</div>
									<input type="text" class="form-control daterange-basic" id="lst_date_range" name="lst_date_range" required>
								</div>

								<div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;padding-left:7px;margin:0;" id="tabular-export">
									<!-- <button type="button" class="btn btn-light" disabled name="btn-print-report" id="btn-print-report" style="float:right;margin-right:10px;"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button> -->
									<button type="button" class="btn btn-warning" disabled name="btn-export-tabular" id="btn-export-tabular" style="float:right;margin-right:19px; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
										<i class="icon-file-spreadsheet"></i>
									</button>
								</div>

								<div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;padding-left:7px;margin:0;" id="tabular-print">
									<!-- <button type="button" class="btn btn-light" disabled name="btn-print-report" id="btn-print-report" style="float:right;margin-right:10px;"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button> -->
									<button type="button" class="btn btn-primary" disabled name="btn-print-tabular" id="btn-print-tabular" style="float:right;margin-right:19px; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
										<i class="icon-printer"></i>
									</button>
								</div>
							</div>
						</li>
					</ul>
				</div>

					<div class="tab-content">
						<!-- STATISTIC Tab -->
						<div class="tab-pane fade show active" id="statistical-data">
			               <div class="card-header"><?php echo date("Y");?> STATISTICAL DATA ANALYSIS
				           </div>

						   	<!-- Line Graph -->
							<div class="card-body">
								<div class="chart-container">
								<div class="chart has-fixed-height" id="line_zoom"></div>
							</div>





							<!-- Production Metrics Table -->
							<div class="card-body">
								<p style="font-size:1.2em;">PRODUCTION METRICS</p>
								<div class="table-responsive production_metrics" id="production_metrics">
								</div>
							</div>


							


							<!-- Scatter Plot -->
							<!-- <div class="card-body">
								<div class="chart-container">
									<div class="chart has-fixed-height" id="scatter_basic"></div>
								</div>
							</div> -->

							<div class="row">
								<div class="col-xl-6">
									<div class="card">
										<div class="card-body">
											<div class="chart-container">
												<div class="chart has-fixed-height" id="pie_basic"></div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-xl-6">
									<div class="card">
										<div class="card-body">
											<div class="chart-container">
												<div class="chart has-fixed-height" id="pie_rose_labels"></div>
											</div>
										</div>										
									</div>
								</div>
							</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tabular-data">
							<div class="card-body">
								<div class="table-responsive tabular_content" id="tabular_content">
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="assessment-data">
							<div class="container-fluid">
								<form class="overall-assessment-form" method="POST" autocomplete="nope">
									<div class="row">
										<div class="col-md-12" style="padding-left: 12px;margin-top: 1px;">
											<div class="card h-95">
												<div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:10px;">
													<h4 class="card-title flex-grow-1 transaction-name">VALUATION</h4>
													<!-- Search Label -->
													<div class="col-sm-1 form-group" style="padding: 0px;padding-top:15px;padding-right:0;margin:0;">
														<label for="tns-search" id="lbl-tns-search" style="text-align: right;font-size: 1.2em;color:aqua;margin-left:15px;">= &gt; Search</label>
													</div>

													<div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;margin:0;margin-right:18px;">
														<input type="text" class="form-control bordered-textbox" id="tns-search" placeholder="Search Product . . ." name="tns-search" disabled>
													</div>

													<div class="col-sm-4 form-group" style="padding: 0px;padding-top:8px;margin:0;">
														<select multiple="multiple" data-placeholder="< Select Category >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-categorycode" name="sel-categorycode" required>
															<option></option>
															<?php
																$category = (new ControllerCategory)->ctrShowCategoryRawMatsList();
																foreach ($category as $key => $value) {
																echo '<option value="'.$value["categorycode"].'">'.$value["catdescription"].'</option>';
																}
															?>
														</select>
													</div>
													<div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;padding-right:0px;margin:0;">
														<button type="button" class="btn btn-light" id="btn-undo"><i class="icon-undo"></i></button>
													</div>

													<!-- <div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;margin:0;margin-right:18px;">
														<input type="text" class="form-control datepicker" data-mask="99/99/9999" id="date-curdate" name="date-curdate">
													</div>													 -->

													<div class="col-sm-2 form-group">
														<div>
															<button type="button" class="btn btn-light" name="btn-period" id="btn-period" 
																	style="width: 100%; margin-top: 8px; color: #a8ffee; 
																		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
																		border: 2px solid #a8ffee; 
																		border-radius: 8px;">
															INVENTORY CYCLE
															</button>
														</div>      
													</div>

												    <div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;padding-left:7px;margin:0;">
														<!-- <button type="button" class="btn btn-light" disabled name="btn-print-report" id="btn-print-report" style="float:right;margin-right:10px;"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button> -->
														<button type="button" class="btn btn-warning" disabled name="btn-export" id="btn-export" style="float:right;margin-right:19px; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
															<i class="icon-file-spreadsheet"></i>
														</button>
													</div>
												</div>
												<!-- <div class="card-body" style="padding-bottom: 0;margin: 0;padding-top: 5px;">
												</div> -->
												<hr style="margin:0;padding: 0;padding-bottom: 24px;">

												<div class="row overall_assessment" id="overall_assessment">
												</div> 
												
												<div class="card-footer" style="padding-top: 20px;margin-top: 0;">
												</div>  <!-- footer -->
											</div>     <!-- card -->
										</div>
									</div>
								</form>
							</div>

							<!-- <div class="card-body">
								<div class="row overall_assessment" id="overall_assessment">
          						</div>
							</div> -->
                        </div> 
						
						<div class="tab-pane fade" id="usage-data">
							<div class="container-fluid">
								<form class="overall-assessment-form" method="POST" autocomplete="nope">
									<div class="row">
										<div class="col-md-12" style="padding-left: 12px;margin-top: 1px;">
											<div class="card h-95">
												<div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:10px;">
													<h4 class="card-title flex-grow-1 transaction-name" style="margin-bottom:7px;">MATERIALS USAGE</h4>
													<!-- Search Label -->
													<div class="col-sm-1 form-group" style="padding: 0px;padding-top:15px;padding-right:0;margin:0;">
														<label for="tns-search-usage" id="lbl-tns-search-usage" style="text-align: right;font-size: 1.2em;color:aqua;margin-left:15px;">= &gt; Search</label>
													</div>

													<div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;margin:0;margin-right:18px;">
														<input type="text" class="form-control bordered-textbox" id="tns-search-usage" placeholder="Search Product . . ." name="tns-search-usage" disabled>
													</div>

													<div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;margin:0;">
														<select data-placeholder="< Select Category >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-categorycode-usage" name="sel-categorycode-usage" required>
															<option></option>
															<?php
																$category = (new ControllerCategory)->ctrShowCategoryRawMatsList();
																foreach ($category as $key => $value) {
																echo '<option value="'.$value["categorycode"].'">'.$value["catdescription"].'</option>';
																}
															?>
														</select>
													</div>
													<div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;padding-right:0px;margin:0;">
														<button type="button" class="btn btn-light" id="btn-undo-usage"><i class="icon-undo"></i></button>
													</div>

													<!-- <div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;margin:0;margin-right:18px;">
														<input type="text" class="form-control datepicker" data-mask="99/99/9999" id="date-curdate" name="date-curdate">
													</div>													 -->

												    <div class="col-sm-1 form-group" style="padding: 0px;padding-top:8px;padding-left:7px;margin:0;">
														<!-- <button type="button" class="btn btn-light" disabled name="btn-print-report" id="btn-print-report" style="float:right;margin-right:10px;"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button> -->
														<button type="button" class="btn btn-warning" disabled name="btn-export-usage" id="btn-export-usage" style="float:right;margin-right:19px; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
															<i class="icon-file-spreadsheet"></i>
														</button>
													</div>
												</div>
												<!-- <div class="card-body" style="padding-bottom: 0;margin: 0;padding-top: 5px;">
												</div> -->
												<hr style="margin:0;padding: 0;padding-bottom: 24px;">

												<div class="row usage_assessment" id="usage_assessment">
												</div> 
												
												<div class="card-footer" style="padding-top: 20px;margin-top: 0;">
												</div>  <!-- footer -->
											</div>     <!-- card -->
										</div>
									</div>
								</form>
							</div>

							<!-- <div class="card-body">
								<div class="row overall_assessment" id="overall_assessment">
          						</div>
							</div> -->
                        </div> 						

						</div>
					</div>  <!-- tab content -->

			</div>  <!-- card -->
		</div>  <!-- /left content -->
	</div>  <!-- /inner container -->
</div>  <!-- /content area -->


<!-- ============== Inventory Periods ============ -->
<div id="modal-inventory-periods" class="modal allow-modal-drag" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;STOCK PERIOD</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>      

      <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header inventoryPeriodsTable" style="font-size: 1.4em;">
        <thead>
          <tr>
            <th>Beginning Inventory</th>
            <th>Ending Inventory</th>
            <th>Act</th>
          </tr>
        </thead>
      </table>
 
    </div>
  </div>
</div>

<!-- ============== Raw Material Summary Usage ============ -->
<div id="modal-summary-usage" class="modal allow-modal-drag" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h1 class="modal-title profile-name" id="raw_material"><i class="icon-menu7 mr-2"></i></h1>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>      

      <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header summaryUsageTable" style="font-size: 1.4em;">
        <thead>
          <tr>
            <th>Date</th>
            <th style="text-align:right;">Used</th>
            <th style="text-align:right;">Ending</th>
            <th style="text-align:right;">Total Used</th>
            <th style="text-align:right;">Peso Value</th>
          </tr>
        </thead>
		<tbody>
        <!-- Data populated dynamically -->
		</tbody>
		<tfoot>
			<tr>
				<th colspan="4" style="text-align:right;color:#fcd772;border:4px solid white;">TOTAL MATERIALS COST</th>
				<th style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;"></th>
			</tr>
		</tfoot>
      </table>
    </div>
  </div>
</div>

<!-- ============== Material Cost Trail ============ -->
<div id="modal-material-cost" class="modal allow-modal-drag" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h1 class="modal-title profile-name" id="trans_date"><i class="icon-menu7 mr-2"></i></h1>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>      

      <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header materialCostTrailTable" style="font-size: 1.4em;">
        <thead>
          <tr>
            <th>Type</th>
			<th>Machine</th>
            <th style="text-align:right;">Raw Qty</th>
            <th style="text-align:right;">Raw Cost</th>
			<th style="text-align:right;">AP Qty</th>
            <th style="text-align:right;">AP Cost</th>
          </tr>
        </thead>
		<tbody>
        <!-- Data populated dynamically -->
		</tbody>
		<tfoot>
			<tr>
				<th colspan="2" style="text-align:right;color:#fcd772;border:4px solid white;">TOTAL MATERIALS COST</th>
				<th colspan="2" style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;"></th>
				<th colspan="2" style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;"></th>
			</tr>
		</tfoot>
      </table>
    </div>
  </div>
</div>

<!-- ============== Production Cost Trail ============ -->
<div id="modal-production-cost" class="modal allow-modal-drag" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h1 class="modal-title profile-name" id="trans-date"><i class="icon-menu7 mr-2"></i></h1>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>      

      <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header productionCostTrailTable" style="font-size: 1.4em;">
        <thead>
          <tr>
            <th>Type</th>
			<th>Machine</th>
            <th style="text-align:right;">Qty</th>
            <th style="text-align:right;">Cost</th>
          </tr>
        </thead>
		<tbody>
        <!-- Data populated dynamically -->
		</tbody>
		<tfoot>
			<tr>
				<th colspan="3" style="text-align:right;color:#fcd772;border:4px solid white;">TOTAL PRODUCTION COST</th>
				<th style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;"></th>
			</tr>
		</tfoot>
      </table>
    </div>
  </div>
</div>

<!-- ============== Production Details ============ -->
<div id="modal-production-details" class="modal allow-modal-drag" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h1 class="modal-title profile-name" id="trans_prod_info"><i class="icon-menu7 mr-2"></i></h1>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>      

      <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header productionDetailsTable" style="font-size: 1.4em;">
        <thead>
          <tr>
            <th>Product Name</th>
            <th style="text-align:right;">Value</th>
            <th style="text-align:right;">Qty</th>
			<th style="text-align:right;">Wt.</th>
            <th style="text-align:right;">Total Wt.</th>
          </tr>
        </thead>
		<tbody>
        <!-- Data populated dynamically -->
		</tbody>
		<tfoot>
			<tr>
				<th style="text-align:right;color:#fcd772;border:4px solid white;">TOTAL PRODUCTION</th>
				<th style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;"></th>
				<th style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;"></th>
				<th colspan="2" style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;"></th>
			</tr>
		</tfoot>
      </table>
    </div>
  </div>
</div>

<script src="views/js/factorydashboard.js"></script>
		
					