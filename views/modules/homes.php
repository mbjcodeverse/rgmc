<div class="content pt-10">
	<div class="row">
		<!-- Operational -->
		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-success-400 has-bg-image">
				<div class="media">
					<div class="mr-3 align-self-center">
						<i class="icon-pulse2 icon-3x opacity-75" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);"></i>
					</div>
					<div class="media-body text-right">
						<h2 class="mb-0" id="operational">0</h2>
						<span class="text-uppercase font-size-xs" style="font-size:1.3em;">Operational</span>
					</div>
				</div>
			</div>
		</div>

		<!-- Under Repair -->
		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-danger-400 has-bg-image">
				<div class="media">
					<div class="media-body">
						<h2 class="mb-0" id="under-repair">0</h2>
						<span class="text-uppercase font-size-xs" style="font-size:1.3em;">Under Repair</span>
					</div>
					<div class="ml-3 align-self-center">
						<i class="icon-hammer icon-3x opacity-75"></i>
					</div>
				</div>
			</div>
		</div>

		<!-- Under Maintenance -->
		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-blue-400 has-bg-image">
				<div class="media">
					<div class="media-body">
						<h2 class="mb-0" id="under-maintenance">0</h2>
						<span class="text-uppercase font-size-xs" style="font-size:1.3em;">Under Maintenance</span>
					</div>
					<div class="ml-3 align-self-center">
						<i class="icon-alarm icon-3x opacity-75"></i>
					</div>
				</div>
			</div>
		</div>

		<!-- Standby -->
		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-indigo-400 has-bg-image">
				<div class="media">
					<div class="media-body">
						<h2 class="mb-0" id="standby">0</h2>
						<span class="text-uppercase font-size-xs" style="font-size:1.3em;">Standby</span>
					</div>
					<div class="ml-3 align-self-center">
						<i class="icon-esc icon-3x opacity-75"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Inner container -->
	<div class="d-flex align-items-start flex-column flex-md-row">
		<div class="w-100 order-2 order-md-1">
			<div class="card">
				<div class="card-header header-elements-md-inline" style="padding-top:10px;padding-bottom:10px;"></div>

				<!-- Nav Tabs -->
				<div class="nav-tabs-responsive bg-dark border-top shadow-0" style="font-size: 1.3em;">
					<ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
						<li class="nav-item cur-machine">
							<a href="#machine-data" class="nav-link active" data-toggle="tab"><i class="icon-cogs mr-2 icon-2x"></i> Machineries</a>
						</li>
						<li class="nav-item cur-tasks">
							<a href="#tabular-data" class="nav-link" data-toggle="tab"><i class="icon-calendar2 mr-2 icon-2x"></i> Tasks</a>
						</li>
						<li class="nav-item cur-inventory">
							<a href="#inventory-data" class="nav-link" data-toggle="tab"><i class="icon-stack4 mr-2 icon-2x"></i> Inventory</a>
						</li>
						<li class="nav-item ml-auto">
							<div class="d-flex align-items-center" id="report-type-container" style="margin-top:7px;">
								<select class="form-control select" id="report-type">
									<option value="1" selected>Machine Uptime & Downtime Trends</option>
									<option value="2">Machine Yield Analysis</option>
								</select>
							</div>
						</li>
					</ul>
				</div>

				<div class="tab-content">
					<!-- [1] Machineries Tab -->
					<div class="tab-pane fade show active" id="machine-data">
						<div class="card-body">
							<div class="row machinepics-machineries"></div>
							<div class="chart-container" style="height: 700px;">
								<div id="pie_multiples" class="chart has-fixed-height" style="height: 700px;"></div>
							</div>
						</div>
					</div>

					<!-- [2] Tasks Tab -->
					<div class="tab-pane fade" id="tabular-data">
						<div class="card-header border-bottom" style="padding-top: 10px; padding-bottom: 0px;">
							<div class="row d-flex align-items-center" style="display: flex; flex-wrap: nowrap; justify-content: flex-start; align-items: center; gap: 10px;">
								<!-- Building -->
								<div class="col-form-label col-lg-1 text-right" style="color: aqua; font-size: 1.2em; min-width: 120px;">
									<label id="cap-lst-buildingcode">=&gt; Building</label>
								</div>
								<div class="col-sm-2 form-group">
									<select data-placeholder="< Select Bldg >" class="form-control select-search" id="cb-buildingcode" name="cb-buildingcode" required>
										<option></option>
										<?php $building = (new ControllerBuilding)->ctrShowBuildingList(); foreach ($building as $key => $value) { echo '<option value="'.$value["buildingcode"].'">'.$value["buildingname"].'</option>'; } ?>
									</select>
								</div>

								<!-- Class -->
								<div class="col-form-label col-lg-1 text-right" style="font-size: 1.2em; color: aqua;">
									<label id="cap-lst-classcode">=&gt; Class</label>
								</div>
								<div class="col-sm-2 form-group">
									<select data-placeholder="< Select Classification >" class="form-control select-search" id="cb-classcode" name="cb-classcode" required>
										<option></option>
										<?php $classification = (new ControllerClassification)->ctrShowClassificationList(); foreach ($classification as $key => $value) { echo '<option value="'.$value["classcode"].'">'.$value["classname"].'</option>'; } ?>
									</select>
								</div>

								<!-- Status -->
								<div class="col-form-label col-lg-1 text-right" style="font-size: 1.2em; color: aqua;">
									<label id="cap-lst-machstatus">=&gt; Status</label>
								</div>
								<div class="col-sm-2 form-group">
									<select data-placeholder="< Select Status >" class="form-control select" id="cb-machstatus" name="cb-machstatus" required>
										<option></option>
										<option value="Operational">Operational</option>
										<option value="Under Repair">Under Repair</option>
										<option value="Under Maintenance">Under Maintenance</option>
										<option value="Standby">Standby</option>
									</select>
								</div>

								<!-- Date -->
								<div class="col-form-label col-lg-1 text-right" style="font-size: 1.2em; color: aqua;">
									<label>Date</label>
								</div>
								<div class="col-sm-2 form-group">
									<input type="text" class="form-control daterange-basic" id="tsk_date_range" name="tsk_date_range" required>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="row machinepics-tasks"></div>
						</div>
					</div>

					<!-- [3] Inventory Tab -->
					<div class="tab-pane fade" id="inventory-data">
						<p class="p-3 text-white">Inventory section coming soon...</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="views/js/homes.js"></script>