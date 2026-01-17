<!-- Content area -->
<div class="content pt-10">
	<div class="row">
		<input type="hidden" name="txt-generatedby" id="txt-generatedby" value="<?php echo $_SESSION["empid"];?>">
		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-success-400 has-bg-image">
				<div class="media">
					<div class="mr-3 align-self-center">
						<i class="icon-pulse2 icon-3x opacity-75" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);"></i>
					</div>

					<div class="media-body text-right">
						<h2 class="mb-0" id="operational" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);">0</h2>
						<span class="text-uppercase font-size-xs" style="font-size:1.3em;text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);">Operational</span>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-danger-400 has-bg-image">
				<div class="media">
					<div class="media-body">
						<h2 class="mb-0" id="under-repair" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);">0</h2>
						<span class="text-uppercase font-size-xs" style="font-size:1.3em;text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);">Under Repair</span>
					</div>

					<div class="ml-3 align-self-center">
						<i class="icon-hammer icon-3x opacity-75" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);"></i>
					</div>
				</div>
			</div>
		</div>		

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-blue-400 has-bg-image">
				<div class="media">
					<div class="media-body">
						<h2 class="mb-0" id="under-maintenance" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);">0</h2>
						<span class="text-uppercase font-size-xs" style="font-size:1.3em;text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);">Under Maintenance</span>
					</div>

					<div class="ml-3 align-self-center">
						<i class="icon-alarm icon-3x opacity-75" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);"></i>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-indigo-400 has-bg-image">
				<div class="media">
					<div class="media-body">
						<h2 class="mb-0" id="under-maintenance" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);">0</h2>
						<span class="text-uppercase font-size-xs" style="font-size:1.3em;text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);">Standby</span>
					</div>

					<div class="ml-3 align-self-center">
						<i class="icon-esc icon-3x opacity-75" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);"></i>
					</div>
				</div>
			</div>
		</div>		

		<!-- <div class="col-sm-6 col-xl-3">
			<div class="card card-body bg-indigo-400 has-bg-image">
				<div class="media">
					<div class="mr-3 align-self-center">
						<i class="icon-esc icon-3x opacity-75" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);"></i>
					</div>

					<div class="media-body text-right">
						<h2 class="mb-0" id="standby" style="text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);">0</h2>
						<span class="text-uppercase font-size-xs" style="font-size:1.3em;text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7);">Standby</span>
					</div>
				</div>
			</div>
		</div> -->
	</div>


	<!-- Inner container -->
	<div class="d-flex align-items-start flex-column flex-md-row">
		<!-- Left content -->
		<div class="w-100 order-2 order-md-1">
			<div class="card">
				<div class="card-header header-elements-md-inline" style="padding-top:10px;padding-bottom:10px;">
<!-- 					<h2 class="card-title" style="color:#aefce0;font-size: 2em;">AGGREGATE <span class="highlight" style="color:#fad9f4;font-size: 0.8em;">TRANSACTION TRAIL</span></h2>   -->          
				</div> 

				<div class="nav-tabs-responsive bg-dark border-top shadow-0" style="font-size: 1.3em;">
					<ul class="nav nav-tabs nav-tabs-bottom flex-nowrap mb-0">
						<li class="nav-item cur-machine"><a href="#machine-data" class="nav-link active" data-toggle="tab"><i class="icon-cogs mr-2 icon-2x"></i> Machineries</a></li>
						<li class="nav-item cur-tasks"><a href="#tabular-data" class="nav-link" data-toggle="tab"><i class="icon-calendar2 mr-2 icon-2x"></i> Tasks</a></li>						
						<li class="nav-item cur-inventory"><a href="#inventory-data" class="nav-link" data-toggle="tab"><i class="icon-stack4 mr-2 icon-2x"></i> Inventory</a></li>
						<li class="nav-item ml-auto">
							<div class="d-flex align-items-center">
								<!-- <label style="margin-top:16px;">Report Type</label> -->
								<div class="mr-3" style="margin-top:7px;" id="report-type-container">
									<select data-placeholder="< Select Report Type >" class="form-control select" data-fouc id="report-type" name="report-type" required>
										<option></option>
										<option value="1" selected>Machine Uptime & Downtime Trends</option>
										<option value="2">Machine Yield Analysis</option>
									</select>
								</div>
							</div>
						</li>
					</ul>
				</div>

				<div class="tab-content">
					<!-- [1] Machineries Tab -->
					<div class="tab-pane fade show active" id="machine-data">
						<div class="card-header border-bottom" style="padding-top: 10px; padding-bottom: 0px;">
							<div class="row d-flex align-items-center" style="display: flex; flex-wrap: nowrap; justify-content: flex-start; align-items: center; gap: 10px;">
								<!-- Building Label and Dropdown -->
								<div class="col-form-label col-lg-1 text-right" style="color: aqua; font-size: 1.2em; min-width: 120px; max-width: 150px; flex: 0 0 auto;">
									<label id="lbl-lst-buildingcode">=&gt; Building</label>
								</div>
								<div class="col-sm-2 form-group" style="margin-bottom: 7px; flex: 1;">
									<select data-placeholder="< Select Bldg >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-buildingcode" name="sel-buildingcode" required style="width: 100%;">
										<option></option>
										<?php $building = (new ControllerBuilding)->ctrShowBuildingList(); foreach ($building as $key => $value) { echo '<option value="'.$value["buildingcode"].'">'.$value["buildingname"].'</option>'; } ?>
									</select>
								</div>

								<!-- Date Label and Range -->
								<div class="col-form-label col-lg-1 text-right" style="font-size: 1.2em; color: aqua; flex: 0 0 auto;">
									<label id="lbl-lst-machstatus">Date</label>
								</div>
								<div class="col-sm-2 form-group" style="margin-bottom: 7px; flex: 1;">
									<input type="text" class="form-control daterange-basic" style="border: 1px solid #6e6e6e; width: 100%;" id="lst_date_range" name="lst_date_range" required>
								</div>

								<!-- Display Type -->
								<div class="col-form-label col-lg-1 text-right" style="font-size: 1.2em; min-width: 120px; max-width: 150px; flex: 0 0 auto;">
									<label id="lbl-lst-displaytype">Display</label>
								</div>
								<div class="col-sm-2 form-group" style="margin-bottom: 7px; flex: 1;">
									<select data-placeholder="Select Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-displaytype" name="sel-displaytype" required>
										<option></option>
										<option value="Statistical">Statistical</option>
										<option value="Narrative">Narrative</option>
									</select>
								</div>								
							</div>
						</div>

						<div class="card-body" style="margin-top: 20px;" id="statistical">
							<!-- <div class="row machinepics"></div> -->
							<div class="card-body">
								<div class="chart-container">
									<div class="chart has-fixed-height" id="gauge_custom"></div>
								</div>
							</div>
							<div class="chart-container" style="height: 700px;">
								<div class="chart has-fixed-height" id="pie_multiples" style="height: 700px;"></div>
							</div>
						</div>
						<!-- <div class="card-body" style="margin-top: 20px;" id="narrative">
							<div class="card-body">
								<label>Narrative</label>
							</div>
						</div> -->

						<div class="row health_content" id="health_content">
          				</div>
					</div>

					<!-- [2] Tasks Tab -->
					<div class="tab-pane fade" id="tabular-data">
						<div class="card-header border-bottom" style="padding-top: 10px; padding-bottom: 0px;">
							<div class="row d-flex align-items-center" style="display: flex; flex-wrap: nowrap; justify-content: flex-start; align-items: center; gap: 10px;">
								<!-- Building Label and Dropdown -->
								<div class="col-form-label col-lg-1 text-right" style="color: aqua; font-size: 1.2em; min-width: 120px; max-width: 150px; flex: 0 0 auto;">
									<label id="cap-lst-buildingcode">=&gt; Building</label>
								</div>
								<div class="col-sm-2 form-group" style="margin-bottom: 7px; flex: 1;">
									<select data-placeholder="< Select Bldg >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="cb-buildingcode" name="cb-buildingcode" required style="width: 100%;">
										<option></option>
										<?php $building = (new ControllerBuilding)->ctrShowBuildingList(); foreach ($building as $key => $value) { echo '<option value="'.$value["buildingcode"].'">'.$value["buildingname"].'</option>'; } ?>
									</select>
								</div>

								<!-- Classification Label and Dropdown -->
								<div class="col-form-label col-lg-1 text-right" style="font-size: 1.2em; color: aqua; flex: 0 0 auto;">
									<label id="cap-lst-classcode">=&gt; Class</label>
								</div>
								<div class="col-sm-2 form-group" style="margin-bottom: 7px; flex: 1;">
									<select data-placeholder="< Select Classification >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="cb-classcode" name="cb-classcode" required style="width: 100%;">
										<option></option>
										<?php $classification = (new ControllerClassification)->ctrShowClassificationList(); foreach ($classification as $key => $value) { echo '<option value="'.$value["classcode"].'">'.$value["classname"].'</option>'; } ?>
									</select>
								</div>
								
								<!-- Status Label and Dropdown -->
								<div class="col-form-label col-lg-1 text-right" style="font-size: 1.2em; color: aqua; flex: 0 0 auto;">
									<label id="cap-lst-machstatus">=&gt; Status</label>
								</div>
								<div class="col-sm-2 form-group" style="margin-bottom: 7px; flex: 1;">
									<select data-placeholder="< Select Status >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="cb-machstatus" name="cb-machstatus" required style="width: 100%;">
										<option></option>
										<option value="Operational">Operational</option>
										<option value="Under Repair">Under Repair</option>
										<option value="Under Maintenance">Under Maintenance</option>
										<option value="Standby">Standby</option>
									</select>
								</div>								

								<!-- Date Label and Range -->
								<div class="col-form-label col-lg-1 text-right" style="font-size: 1.2em; color: aqua; flex: 0 0 auto;">
									<label>Date</label>
								</div>
								<div class="col-sm-2 form-group" style="margin-bottom: 7px; flex: 1;">
									<input type="text" class="form-control daterange-basic" style="border: 1px solid #6e6e6e; width: 100%;" id="tsk_date_range" name="tsk_date_range" required>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="row machinepics-tasks"></div>
						</div>
					</div>

					<!-- <div class="card-body">
						<div class="chart-container" style="height:700px;">
							<div class="chart has-fixed-height" id="pie_multiples" style="height:700px;"></div>
						</div>
					</div> -->

					<!-- [3] Inventory Tab -->
					<div class="tab-pane fade" id="inventory-data">
		               <div class="card-header border-bottom" style="padding-top: 10px;padding-bottom: 0px;">
		               	  <div class="row">
		               	  	<label class="col-form-label col-lg-1" style="text-align: right;">Category</label>
			                <div class="col-sm-3 form-group"  style="margin-bottom: 3px;">
			                  <select data-placeholder="< Select Category >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-categorycode" name="sel-categorycode" required>
			                    <option></option>
								<?php
									$category = (new ControllerCategory)->ctrShowCategoryList();
									foreach ($category as $key => $value) {
									echo '<option value="'.$value["categorycode"].'">'.$value["catdescription"].'</option>';
									}
								?>
			                  </select>
			                </div> 

			                <!-- <label class="col-form-label col-lg-1" style="text-align: right;">Ratio</label>
			                <div class="col-sm-2 form-group" style="margin-bottom: 7px;">
			                  <select data-placeholder="< Select Classification >" class="form-control select-search" id="sel-itemclass" name="sel-itemclass" required>
			                    <option></option>
								<option value="One to One">One to One</option>
			                    <option value="One to Many">One to Many</option>
			                  </select>
			                </div> -->
							
							<label class="col-form-label col-lg-1" style="text-align: right;">As of</label>
							<div class="col-sm-2 form-group">
								<input type="text" class="form-control datepicker bordered-textbox" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-asofdate" name="date-atereported" required>
							</div>

							<label class="col-form-label col-lg-1" style="text-align: right;color:aqua;" id="lbl-tns-search">= &gt;Search</label>
							<div class="col-sm-2 form-group">
								<input type="text" class="form-control bordered-textbox" id="tns-search" placeholder="Search Item . . ." name="tns-search">
							</div>

			                <!-- <label class="col-form-label col-lg-1" style="text-align: right;">Status</label>
						    <div class="col-sm-2 form-group" style="margin-bottom: 7px;">
			                  <select data-placeholder="< Select Status >" class="form-control select" data-fouc id="sel-itemstatus" name="sel-itemstatus" required>
			                    <option></option>
								<option value="Stable">Stable</option>
			                    <option value="Critical">Critical</option>
			                    <option value="Below Zero">Below Zero</option>
			                  </select>
			                </div>  -->

			                <!-- <div class="btn-group col-sm-1" style="padding: 0px;margin: 0px;margin-bottom:15px;">
			                  <button type="button" class="btn btn-lg btn-block btn-outline-warning" id="btn-reset-inventory" style="margin: 0px;"><i class="icon-rotate-ccw2"></i>&nbsp;&nbsp;Reset</button>
							  <button type="button" class="btn btn-lg btn-block btn-outline-primary" id="btn-reset-inventory" style="margin: 0px;"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button>
			                </div> -->

							<!-- <div class="col-sm-2 form-group d-flex align-items-end" style="margin-bottom: 7px;margin-top:15px;">
								<button type="button"
										class="btn btn-outline-warning mr-2 w-50"
										id="btn-reset-inventory">
									<i class="icon-rotate-ccw2"></i>&nbsp;Reset
								</button>

								<button type="button"
										class="btn btn-outline-primary w-50"
										id="btn-print-inventory">
									<i class="icon-printer"></i>&nbsp;Print
								</button>
							</div> -->

							<div class="col-sm-2 form-group" style="margin-bottom: 3px;">
								<div class="d-flex">
									<button type="button"
											class="btn btn-outline-warning mr-2"
											id="btn-reset-inventory">
										<i class="icon-rotate-ccw2"></i>&nbsp;Reset
									</button>

									<button type="button"
											class="btn btn-outline-primary"
											id="btn-print-inventory">
										<i class="icon-printer"></i>&nbsp;Print
									</button>
								</div>
							</div>

			              </div>  
			           </div>

			           <!-- <div class="card-body" style="margin-top: -5px;">    
						    <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header productInventoryTable" style="font-size: 1em;">
								<thead>
									<tr>
									<th style="min-width:450px;">ITEMS</th>
									<th style="min-width:90px;">PU</th>
									<th style="min-width:110px;">EQ Qty</th>
									<th style="min-width:90px;">SKU</th>
									<th style="min-width:125px;">Cost</th>
									<th style="min-width:110px;">Re-order</th>
									<th style="min-width:110px;">Stock</th>
									<th style="min-width:100px;">Act</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$prod_stocks = (new Connection)->connect()->query("
									SELECT a.itemid,b.invdate AS tdate,a.itemcode,'Inventory' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty,1 AS priority FROM items AS a INNER JOIN inventoryitems AS c ON (a.itemid = c.itemid) INNER JOIN inventory AS b ON (c.invnumber = b.invnumber) WHERE (b.invstatus = 'Posted') AND (b.invdate >= '2025-11-30') 
									UNION ALL
									SELECT a.itemid,b.podate AS tdate,a.itemcode,'Incoming' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty*a.eqnum as qty,1 AS priority FROM items AS a INNER JOIN purchaseitems AS c ON (a.itemid = c.itemid) INNER JOIN purchaseorder AS b ON (c.ponumber = b.ponumber) WHERE (b.postatus = 'Posted') AND (b.podate >= '2025-12-01')
									UNION ALL
									SELECT a.itemid,b.retdate AS tdate,a.itemcode,'Return' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty*a.eqnum as qty,1 AS priority FROM items AS a INNER JOIN returnitems AS c ON (a.itemid = c.itemid) INNER JOIN returned AS b ON (c.retnumber = b.retnumber) WHERE (b.retstatus = 'Posted') AND (b.retdate >= '2025-12-01')  
									UNION ALL
									SELECT a.itemid,b.reqdate AS tdate,a.itemcode,'Withdrawal' AS details,a.pdesc,a.meas1,a.eqnum,a.meas2,a.ucost,a.reorder,c.qty,1 AS priority FROM items AS a INNER JOIN stockoutitems AS c ON (a.itemid = c.itemid) INNER JOIN stockout AS b ON (c.reqnumber = b.reqnumber) WHERE (b.reqstatus = 'Posted') AND (b.reqdate >= '2025-12-01') ORDER BY pdesc,itemid,priority,tdate");

									$prev_itemid = 0;
									$curr_itemid = 0;

									$prev_code = '';
									$curr_code = '';

									$ctr = 0;
									$onhand = 0.00;
									$isInventory = 0;
									$itemid = 0;

									foreach ($prod_stocks as $key => $value) {
										$itemid = $value["itemid"];
										$itemcode = $value["itemcode"];
										$tdate = $value["tdate"];
										$details = $value["details"];
										$qty = $value["qty"];
										$priority = $value["priority"];

										if ($ctr == 0){
											$prev_itemid = $value["itemid"];
											$prev_code = $value["itemcode"];
										}

										$ctr = $ctr + 1;

										$curr_itemid = $value["itemid"];        
										$curr_code = $value["itemcode"];        

										if ($prev_itemid == $curr_itemid){      
											$pdesc = $value["pdesc"];
											$meas1 = $value["meas1"];
											$eqnum = $value["eqnum"];
											$meas2 = $value["meas2"];
											$ucost = $value["ucost"];
											$reorder = $value["reorder"];

											if ($details == "Inventory"){
												$isInventory = 1;
											}
												
											switch ($details) {
												case "Inventory":
													$onhand = $qty;
													break;
												case "Incoming":
													$onhand = $onhand + $qty;
													break; 
												case "Return":
													$onhand = $onhand + $qty;
													break;	
												default: 
													$onhand = $onhand - $qty;
											} 
										}else{
											echo '<tr itemid='.$prev_itemid.'>
												<td>'.$pdesc.'</td>
												<td>'.strtoupper($meas1).'</td>
												<td>'.$eqnum.'</td>
												<td>'.strtoupper($meas2).'</td>
												<td>'.$ucost.'</td>
												<td>'.$reorder.'</td>
												<td>'.$onhand.'</td>
												<td>
												<button type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnStockcard" itemid="'.$prev_itemid.'" data-toggle="modal" data-target="#stockcard"><i class="icon-stack-text"></i>
												</button>
												</td>
											</tr>';

											$update_onhand = (new Connection)->connect()->prepare("UPDATE items SET onhand=? WHERE itemid=?");
											$update_onhand->execute([$onhand, $prev_itemid]);

											$prev_itemid = $curr_itemid;
											$prev_code = $curr_code;

											$pdesc = $value["pdesc"];

											$onhand = 0.00;
											$isInventory = 0;
											if ($details == "Inventory"){
												$isInventory = 1;
											}

											switch ($details) {
												case "Inventory":
													$onhand = $qty;
													break;
												case "Incoming":
													$onhand = $onhand + $qty;
													break;   
												case "Return":
													$onhand = $onhand + $qty;
													break;		
												default:  
													$onhand = $onhand - $qty;
											}
										}
									}

									if ($itemid != 0){
									echo '<tr itemid='.$itemid.'>
										<td>'.$pdesc.'</td>
										<td>'.strtoupper($meas1).'</td>
										<td>'.$eqnum.'</td>
										<td>'.strtoupper($meas2).'</td>
										<td>'.$ucost.'</td>
										<td>'.$reorder.'</td>
										<td>'.$onhand.'</td>
										<td>
										<button type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnStockcard" itemid="'.$itemid.'" data-toggle="modal" data-target="#stockcard"><i class="icon-stack-text"></i>
										</button>
										</td>
									</tr>'; 

									$update_onhand = (new Connection)->connect()->prepare("UPDATE items SET onhand=? WHERE itemid=?");
									$update_onhand->execute([$onhand, $prev_itemid]);    
									}
								?>
								</tbody>
							</table>
					   </div>			           	 -->
					
						<div class="row inventory_content" id="inventory_content">
          				</div> 

					</div>					
					<!-- END of Inventory Tab -->

				</div>  <!-- tab content -->

			</div>  <!-- card -->
		</div>  <!-- /left content -->
	</div>  <!-- /inner container -->
</div>  <!-- /content area -->

<div id="stockcard" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title" id="product_name"><i class="icon-menu7 mr-2"></i></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <div class="modal-body">
        <div class="table-responsive" style="overflow-y: auto; max-height: 350px;">
        <table class="stockcard_content table datatable-basic table-bordered table-hover datatable-small-font profile-grid-header mx-auto w-auto">
<!--         <div class="row stockcard_content">
        </div> -->
        </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="views/js/home.js"></script>
<script src="views/js/home_inventory.js"></script>
<script src="views/js/stockcard_home.js"></script>
		
					