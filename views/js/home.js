// Initialize module
// ------------------------------

// document.addEventListener('DOMContentLoaded', function() {
//     EchartsBarsStackedDark.init();
// });

// Use setInterval() when you want to send AJAX request
// at a particular interval every time and don’t want to
// depend on the previous request is completed or not.
// remove AJAX 'complete' when using this

// But if you want to execute the AJAX when the previous
// one is completed then use the setTimeout() function.

if (!$.fn.DataTable.isDataTable('.productInventoryTable')) {
  var pt = $('.productInventoryTable').DataTable({
    deferRender: true,
    processing: true,
    autoWidth: true,
    scrollY: 360,
    pageLength: 25,  // Fixed typo: "pagelength" -> "pageLength"
    lengthMenu: [[25, 50], [25, 50]],
    dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer"fp>',
    language: {
      loadingRecords: 'Please wait - loading...',
      processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
      search: '<span>Filter:</span> _INPUT_',
      searchPlaceholder: 'Type to filter...',
      lengthMenu: '<span>Show:</span> _MENU_',
      paginate: { 
        'first': 'First', 
        'last': 'Last', 
        'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 
        'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' 
      }
    }
  });
}

var moulding = 0, pet = 0, mixer = 0, aux = 0, pellitizer = 0, extruder = 0, pp = 0, injection = 0,
    cutter = 0, pjar_molding = 0, gallon_molding = 0, straw = 0, cups = 0, printing = 0, fabricator = 0, 
    paper = 0, crusher = 0, cups_forming = 0, lathe = 0;

var moulding_remain = 100, pet_remain = 100, mixer_remain = 100, aux_remain = 100, pellitizer_remain = 100, 
    extruder_remain = 100, pp_remain = 100, injection_remain = 100, cutter_remain = 100, pjar_molding_remain = 100, 
    gallon_molding_remain = 100, straw_remain = 100, cups_remain = 100, printing_remain = 100, fabricator_remain = 100, 
    paper_remain = 100, crusher_remain = 100, cups_forming_remain = 100, lathe_remain = 100;

var totalPercentage = 0, validCount = 0, totalAverage = 0.00;

document.addEventListener('DOMContentLoaded', function() {
// $(function() {
    // Hide - Report Type by default (Machiniries Tab)
    $("ul.nav-tabs-bottom li:nth-child(4)").hide();

    // Machiniries Tab date range
    $('#lst_date_range').daterangepicker({
        ranges: {
            'All'           : [moment('2025-12-01'), moment()],
            'Today'         : [moment(), moment()],
            'Yesterday'     : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days'   : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days'  : [moment().subtract(30, 'days'), moment()],
            'This Month'    : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'    : [moment().subtract(1, 'months').startOf('month'), moment().subtract(1, 'months').endOf('month')]
        },
        startDate: moment('2025-12-01'),
        endDate: moment(),
        minDate: moment('2025-12-01')
    });

    // Tasks Tab date range
    $('#tsk_date_range').daterangepicker({
        ranges: {
            'All'           : [moment('2025-12-01'), moment()],
            'Today'         : [moment(), moment()],
            'Yesterday'     : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days'   : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days'  : [moment().subtract(30, 'days'), moment()],
            'This Month'    : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'    : [moment().subtract(1, 'months').startOf('month'), moment().subtract(1, 'months').endOf('month')]
        },
        startDate: moment('2025-09-01'),
        endDate: moment(),
        minDate: moment('2025-09-01')
    }); 




    // $("#statistical").hide();
    $("#narrative").hide();

    $("#sel-displaytype").on("change", function () {
        const displayType = $(this).val();

        if (displayType === "Narrative") {
            $("#statistical").hide();
            $("#narrative").show();
        } else if (displayType === "Statistical") {
            $("#narrative").hide();
            $("#statistical").show();
        } else {
            // If nothing selected
            $("#statistical").hide();
            $("#narrative").hide();
        }
    });



    
    var tableInventory = $('.productInventoryTable').DataTable();
    tableInventory.columns.adjust();

    $('li.cur-inventory').click(function(){
        // show report type
        $("ul.nav-tabs-bottom li:nth-child(4)").hide();    
        // var tableInventory = $('.productInventoryTable').DataTable();
        // tableInventory.columns.adjust();
    });

    $('li.cur-machine').click(function(){
        // hide report type
        $("ul.nav-tabs-bottom li:nth-child(4)").hide();
    });         

    machine_status_count();
    machine_health_report();

    // Wait for machine data to load, then init charts
    machine_category_operational_percentage(function () {
      EchartsPieMultipleDark.init();
      EchartsGaugeCustomDark.init();
    });

    $("#btn-reset").click(function(){
      // $("#sel-classcode").val('').trigger('change');
      // $("#sel-buildingcode").val('').trigger('change');
      // $("#sel-machstatus").val('').trigger('change');
      // filter_machine();
    });

    $("#sel-displaytype").val('Statistical').trigger('change');

    $('#sel-buildingcode,#lst_date_range').on("change", function(){
        // Statistical
        resetVariables();
        machine_status_count();
        machine_category_operational_percentage(function() {
            EchartsPieMultipleDark.init();
            EchartsGaugeCustomDark.init();
        });
        machine_health_report();
    });

    function machine_health_report(){
        let buildingcode = $("#sel-buildingcode").val();
        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
        let machine_health = new FormData();
        machine_health.append("buildingcode", buildingcode);
        machine_health.append("start_date", start_date);
        machine_health.append("end_date", end_date);
        $.ajax({
            url:"ajax/machine_health.ajax.php",   
            method: "POST",    
            data: machine_health,                               
            cache: false,                  
            contentType: false,            
            processData: false,            
            dataType:"json",               
            success:function(answer){
                $(".health_content").empty();
                var html = [];
                html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;margin-bottom:12px;">');
                    html.push('<table class="table mx-auto w-auto itemInventoryTable">');
                        html.push('<thead>');
                            html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">CATEGORY</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">MACHINE DESCRIPTION</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">CASE</th>');
                                html.push('<th class="table_head_center_fixed" style="padding-top:8px;padding-bottom:8px;">STAT</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">FREQUENCY</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">DOWNTIME</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">MTBF</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">MTTR</th>');
                            html.push('</tr>');
                        html.push('</thead>');

                        for(var i = 0; i < answer.length; i++) {
                            var health = answer[i];
                            var classname = health.classname;
                            var machinedesc = health.machinedesc;
                            var machinestatus = health.machinestatus;
                            var totalfrequency = health.totalfrequency;
                            var totaldowntime = health.totaldowntime;
                            var mtbf = numberWithCommas(health.mtbf);
                            var mttr = numberWithCommas(health.mttr);

                            var statusColor = getStatusColor(machinestatus);

                            html.push('<tr>');
                                html.push('<td style="text-align:left;border:1px solid white;border-right:1px solid white;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+classname+'</td>');
                                html.push('<td style="text-align:left;border:1px solid white;border-right:1px solid white;font-size:1.1em;padding-top:4px;padding-bottom:4px;">'+machinedesc+'</td>');
                                html.push('<td style="text-align:center;border:1px solid white;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+''+'</td>');

                                // html.push(
                                //     '<td style="text-align:center;' +
                                //     'border:4px solid white;' +
                                //     'font-size:1.2em;' +
                                //     'padding-top:4px;' +
                                //     'padding-bottom:4px;' +
                                //     'background-color:' + statusColor + ';' +
                                //     'color:white;">' +
                                //     machinestatus +
                                //     '</td>'
                                // );

                                html.push(
                                    '<td style="text-align:center;' +
                                    'border:4px solid white;' +
                                    'font-size:1.2em;' +
                                    'padding-top:4px;' +
                                    'padding-bottom:4px;' +
                                    'background-color:' + statusColor + ';' +
                                    'color:white;"></td>'
                                );

                                //html.push('<td style="text-align:center;border:4px solid white;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+machinestatus+'</td>');
                                
                                html.push('<td style="text-align:center;border:1px solid white;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+totalfrequency+'</td>');
                                html.push('<td style="text-align:right;border:1px solid white;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+totaldowntime+'</td>');
                                html.push('<td style="text-align:right;border:1px solid white;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+mtbf+'</td>');
                                html.push('<td style="text-align:right;border:1px solid white;font-size:1.2em;padding-top:4px;padding-bottom:4px;">'+mttr+'</td>');
                            html.push('</tr>');
                        }

                    html.push('</table>');
                html.push('</div>');
                $('.health_content').html(html.join(''));
            }
        });
    }

    function getStatusColor(status) {
        switch (status) {
            case 'Operational':
                return '#22752d'; // green
            case 'Under Maintenance':
                return '#3239ab'; // blue
            case 'Under Repair':
                return '#DC3545'; // red
            case 'Idle':
                return '#FFC107'; // yellow
            default:
                return '#6C757D'; // gray
        }
    }

    function machine_status_count(){
      let buildingcode = $("#sel-buildingcode").val();
      let machine_list = new FormData();
      machine_list.append("buildingcode", buildingcode);
      $.ajax({
        url:"ajax/machine_status_count.ajax.php",   
        method: "POST",    
        data: machine_list,                               
        cache: false,                  
        contentType: false,            
        processData: false,            
        dataType:"json",               
        success:function(answer){
          document.getElementById("operational").innerHTML = '0'; 
          document.getElementById("under-repair").innerHTML = '0';
          document.getElementById("under-maintenance").innerHTML = '0';
        //   document.getElementById("standby").innerHTML = '0'; 
          for(var m = 0; m < answer.length; m++) {
            let machine_count = answer[m];
            let machstatus = machine_count.machstatus;
            let mcount =  machine_count.mcount;
            if (machstatus == 'Operational'){
              document.getElementById("operational").innerHTML = mcount;
            }else if (machstatus == 'Under Repair'){
              document.getElementById("under-repair").innerHTML = mcount;
            }else if (machstatus == 'Under Maintenance'){
              document.getElementById("under-maintenance").innerHTML = mcount;
            }else{
              document.getElementById("standby").innerHTML = mcount;
            }
          }
        }
      });
    }    

    function resetVariables(){
        moulding = 0, pet = 0, mixer = 0, aux = 0, pellitizer = 0, extruder = 0, pp = 0, injection = 0,
        cutter = 0, pjar_molding = 0, gallon_molding = 0, straw = 0, cups = 0, printing = 0, fabricator = 0, 
        paper = 0, crusher = 0, cups_forming = 0, lathe = 0;

        moulding_remain = 100, pet_remain = 100, mixer_remain = 100, aux_remain = 100, pellitizer_remain = 100, 
        extruder_remain = 100, pp_remain = 100, injection_remain = 100, cutter_remain = 100, pjar_molding_remain = 100, 
        gallon_molding_remain = 100, straw_remain = 100, cups_remain = 100, printing_remain = 100, fabricator_remain = 100, 
        paper_remain = 100, crusher_remain = 100, cups_forming_remain = 100, lathe_remain = 100;

        totalPercentage = 0, validCount = 0, totalAverage = 0.00;
    }

    $('#sel-categorycode, #sel-itemclass, #sel-itemstatus').on("change", function(){
      // filter_inventory();
    }); 

    $("#lbl-lst-buildingcode").click(function(){
        $("#sel-buildingcode").val('').trigger('change');
    });

    $("#cap-lst-buildingcode").click(function(){
        $("#cb-buildingcode").val('').trigger('change');
    });    

    $("#cap-lst-classcode").click(function(){
        $("#cb-classcode").val('').trigger('change');
    });

    $("#cap-lst-machstatus").click(function(){
        $("#cb-machstatus").val('').trigger('change');
    });    

    function machine_category_operational_percentage(onComplete){
        let buildingcode = $("#sel-buildingcode").val();
        let machine_list = new FormData();
        machine_list.append("buildingcode", buildingcode);
        $.ajax({
            url:"ajax/machine_category_percentage.ajax.php",   
            method: "POST",    
            data: machine_list,                               
            cache: false,                  
            contentType: false,            
            processData: false,            
            dataType:"json",               
            success:function(answer){
            for(var i = 0; i < answer.length; i++) {
                let machine = answer[i];
                let classcode = machine.classcode;
                let classname = machine.classname;
                let total_machines = machine.total_machines;
                let operational_machines = machine.operational_machines;
                let percentage = machine.operational_percentage;

                if (total_machines > 0){
                    if (percentage > 0){
                        totalPercentage += Number(percentage);
                        validCount++;
                    }
                    switch(classcode) {
                        case '0001': moulding = percentage; moulding_remain = 100 - percentage; break;
                        case '0002': pet = percentage; pet_remain = 100 - percentage; break;
                        case '0003': mixer = percentage; mixer_remain = 100 - percentage; break;
                        case '0004': aux = percentage; aux_remain = 100 - percentage; break;
                        case '0005': pellitizer = percentage; pellitizer_remain = 100 - percentage; break;
                        case '0006': extruder = percentage; extruder_remain = 100 - percentage; break;
                        case '0007': pp = percentage; pp_remain = 100 - percentage; break;
                        case '0008': injection = percentage; injection_remain = 100 - percentage; break;
                        case '0009': cutter = percentage; cutter_remain = 100 - percentage; break;
                        case '0010': pjar_molding = percentage; pjar_molding_remain = 100 - percentage; break;
                        case '0011': gallon_molding = percentage; gallon_molding_remain = 100 - percentage; break;
                        case '0012': straw = percentage; straw_remain = 100 - percentage; break;
                        case '0013': cups = percentage; cups_remain = 100 - percentage; break;
                        case '0014': printing = percentage; printing_remain = 100 - percentage; break;
                        case '0015': fabricator = percentage; fabricator_remain = 100 - percentage; break;
                        case '0016': paper = percentage; paper_remain = 100 - percentage; break;
                        case '0017': crusher = percentage; crusher_remain = 100 - percentage; break;
                        case '0018': cups_forming = percentage; cups_forming_remain = 100 - percentage; break;
                        case '0019': lathe = percentage; lathe_remain = 100 - percentage; break;
                        default: break;
                    }
                }
            }

            if (validCount > 0){
                    totalAverage = totalPercentage / validCount;
            }else{
                    totalAverage = 0.00;
            }

            if (typeof onComplete === 'function') {
                onComplete(); // Call the callback when done
            }
            }
        });    
    }

    function filter_machine(){
      let classcode = $("#sel-classcode").val();
      let buildingcode = $("#sel-buildingcode").val();
      let machstatus = $("#sel-machstatus").val();

      var machines = new FormData();
      machines.append("classcode", classcode);
      machines.append("buildingcode", buildingcode);
      machines.append("machstatus", machstatus);

      $.ajax({
         url:"ajax/machine_dashboard_list.ajax.php",   
         method: "POST",                
         data: machines,                    
         cache: false,                  
         contentType: false,            
         processData: false,            
         dataType:"json",               
         success:function(answer){
           $(".machinepics").empty();
           for(var i = 0; i < answer.length; i++) {
              let machine = answer[i];
              let machineid = machine.machineid;
              let machinedesc = machine.machinedesc;
              let machstatus = machine.machstatus;
              let machtype = machine.machtype;
              let image = machine.image;
              let attributelist = machine.attributelist;

              $(".machinepics").append(
                '<div class="col-sm-6 col-xl-3">'+
                  '<div class="card">'+
                    '<div class="card-img-actions mx-1 mt-1">'+
                      '<img class="card-img img-fluid" src="'+image+'" alt="">'+
                    '</div>'+
                    '<div class="card-body">'+
                      '<div class="d-flex align-items-start flex-nowrap">'+
                        '<div>'+
                          '<div class="font-weight-semibold mr-2">'+machinedesc.toUpperCase()+'</div>'+
                          '<span class="font-size-sm text-muted">'+machtype+'</span>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+
                '</div>')  
           }
        }
      });
    }

    // Uptime and Downtime Machine Trend (per day) ----------------------------------------------
	$('li.cur-tasks').click(function() {
        generateMachineUptimeDowntimeTrendWeekly();
	});    

    $('#cb-buildingcode, #cb-classcode, #cb-machstatus, #tsk_date_range').on("change", function(){
        generateMachineUptimeDowntimeTrendWeekly();
    });
	
    function generateMachineUptimeDowntimeTrend() {
        $("ul.nav-tabs-bottom li:nth-child(4)").show();

        const reptype = $("#report-type").val();
        const buildingcode = $("#cb-buildingcode").val();
        const classcode = $("#cb-classcode").val();
        const machstatus = $("#cb-machstatus").val();
        const date_range = $("#tsk_date_range").val();

        if (!date_range) return;

        const [start_date, end_date] = date_range.split(' - ').map(date =>
            moment(date, 'MM/DD/YYYY').format('YYYY-MM-DD')
        );

        const tasks = new FormData();
        tasks.append("reptype", reptype);
        tasks.append("buildingcode", buildingcode);
        tasks.append("classcode", classcode);
        tasks.append("machstatus", machstatus);
        tasks.append("start_date", start_date);
        tasks.append("end_date", end_date);

        $.ajax({
            url: "ajax/tasks.ajax.php",
            method: "POST",
            data: tasks,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(answer) {
                $(".machinepics-tasks").empty();

                if (!answer || answer.length === 0) {
                    $(".machinepics-tasks").append("<p style='color:white;'>No data available.</p>");
                    return;
                }

                let current_machine_charts = {};
                for (let i = 0; i < answer.length; i++) {
                    let a = answer[i];
                    let machinedesc = a.machinedesc;

                    let date_reported = a.datereported;
                    // Convert 'YYYY-MM-DD' → 'MM/DD/YY'
                    let formattedDate = new Date(date_reported);
                    let mm = String(formattedDate.getMonth() + 1).padStart(2, '0');
                    let dd = String(formattedDate.getDate()).padStart(2, '0');
                    let yy = String(formattedDate.getFullYear()).slice(-2);
                    let datereported = `${mm}/${dd}/${yy}`;

                    let green_line = Number(a.green_line);
                    let redline = Number(a.redline);

                    // Prolong the downtime across multiple days if redline > 24 hours
                    if (redline > 24) {
                        let downtimeDays = Math.ceil(redline / 24);  // Calculate how many days the downtime will last
                        let distributedDowntime = Array(downtimeDays).fill(24);  // 24 hours per day until downtime is exhausted

                        // If the redline is not an exact multiple of 24, we need to adjust the last day
                        let remainingDowntime = redline % 24;
                        if (remainingDowntime > 0) {
                            distributedDowntime[distributedDowntime.length - 1] = remainingDowntime;
                        }

                        // Distribute downtime across multiple days
                        for (let day = 0; day < distributedDowntime.length; day++) {
                            let dayDate = moment(datereported).add(day, 'days').format('MM/DD/YY');
                            let currentRedline = distributedDowntime[day];

                            let safeId = machinedesc.replace(/\s+/g, '_').replace(/[^\w\-]/g, '');
                            if (!current_machine_charts[machinedesc]) {
                                current_machine_charts[machinedesc] = {
                                    dates: [],
                                    uptime: [],
                                    downtime: [],
                                    chartId: 'chart_' + safeId
                                };
                            }

                            current_machine_charts[machinedesc].dates.push(dayDate);
                            current_machine_charts[machinedesc].uptime.push(24 - currentRedline);  // Green line is remaining uptime
                            current_machine_charts[machinedesc].downtime.push(currentRedline);  // Red line is the downtime for that day
                        }
                    } else {
                        let safeId = machinedesc.replace(/\s+/g, '_').replace(/[^\w\-]/g, '');
                        if (!current_machine_charts[machinedesc]) {
                            current_machine_charts[machinedesc] = {
                                dates: [],
                                uptime: [],
                                downtime: [],
                                chartId: 'chart_' + safeId
                            };
                        }

                        current_machine_charts[machinedesc].dates.push(datereported);
                        current_machine_charts[machinedesc].uptime.push(green_line);
                        current_machine_charts[machinedesc].downtime.push(redline);
                    }
                }

                for (let machinedesc in current_machine_charts) {
                    const data = current_machine_charts[machinedesc];
                    const chartElement = document.createElement('div');
                    chartElement.id = data.chartId;
                    chartElement.className = 'chart has-fixed-height mb-3';
                    chartElement.style.height = '400px';
                    $(".machinepics-tasks").append(chartElement);

                    // Delay render to ensure visible
                    setTimeout(() => {
                        UptimeDowntimeTrend.init(data.dates, data.uptime, data.downtime, data.chartId, machinedesc);
                    }, 300);
                }
            },
            error: function(err) {
                console.error("AJAX error:", err);
                $(".machinepics-tasks").append("<p style='color:red;'>Error loading chart data.</p>");
            }
        });
    }

    function generateMachineUptimeDowntimeTrendWeekly() {
        $("ul.nav-tabs-bottom li:nth-child(4)").show();

        const reptype = $("#report-type").val();
        const buildingcode = $("#cb-buildingcode").val();
        const classcode = $("#cb-classcode").val();
        const machstatus = $("#cb-machstatus").val();
        const date_range = $("#tsk_date_range").val();

        if (!date_range) return;

        const [start_date, end_date] = date_range.split(' - ').map(date =>
            moment(date, 'MM/DD/YYYY').format('YYYY-MM-DD')
        );

        const tasks = new FormData();
        tasks.append("reptype", reptype);
        tasks.append("buildingcode", buildingcode);
        tasks.append("classcode", classcode);
        tasks.append("machstatus", machstatus);
        tasks.append("start_date", start_date);
        tasks.append("end_date", end_date);

        $.ajax({
            url: "ajax/tasks.ajax.php",
            method: "POST",
            data: tasks,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(answer) {
                $(".machinepics-tasks").empty();

                if (!answer || answer.length === 0) {
                    $(".machinepics-tasks").append("<p style='color:white;'>No data available.</p>");
                    return;
                }

                let current_machine_charts = {};
                const start = moment(start_date);
                const end = moment(end_date);
                const totalWeeks = Math.ceil(end.diff(start, 'days') / 7);

                for (let i = 0; i < answer.length; i++) {
                    const a = answer[i];
                    const machinedesc = a.machinedesc;
                    const date_reported = moment(a.datereported, 'YYYY-MM-DD');
                    const redline = Number(a.redline);
                    const green_line = Number(a.green_line);

                    // find which week this date belongs to
                    const weekNumber = Math.floor(date_reported.diff(start, 'days') / 7) + 1;

                    if (!current_machine_charts[machinedesc]) {
                        current_machine_charts[machinedesc] = {
                            weeks: Array.from({ length: totalWeeks }, (_, idx) => `Week ${idx + 1}`),
                            uptime: Array(totalWeeks).fill(168), // default full uptime
                            downtime: Array(totalWeeks).fill(0),
                            chartId: 'chart_' + machinedesc.replace(/\s+/g, '_').replace(/[^\w\-]/g, ''),
                        };
                    }

                    let chart = current_machine_charts[machinedesc];

                    if (redline <= 168) {
                        chart.downtime[weekNumber - 1] += redline;
                        chart.uptime[weekNumber - 1] = 168 - chart.downtime[weekNumber - 1];
                    } else {
                        // spread over multiple weeks if > 168 hours
                        let remaining = redline;
                        let weekIdx = weekNumber - 1;

                        while (remaining > 0 && weekIdx < totalWeeks) {
                            const used = Math.min(remaining, 168);
                            chart.downtime[weekIdx] += used;
                            chart.uptime[weekIdx] = 168 - chart.downtime[weekIdx];
                            remaining -= used;
                            weekIdx++;
                        }
                    }
                }

                // Render charts
                for (let machinedesc in current_machine_charts) {
                    const data = current_machine_charts[machinedesc];
                    const chartElement = document.createElement('div');
                    chartElement.id = data.chartId;
                    chartElement.className = 'chart has-fixed-height mb-3';
                    chartElement.style.height = '400px';
                    $(".machinepics-tasks").append(chartElement);

                    setTimeout(() => {
                        UptimeDowntimeTrend.init(data.weeks, data.uptime, data.downtime, data.chartId, machinedesc);
                    }, 300);
                }
            },
            error: function(err) {
                console.error("AJAX error:", err);
                $(".machinepics-tasks").append("<p style='color:red;'>Error loading chart data.</p>");
            }
        });
    }


    
    
    $("#cap-lst-buildingcode").click(() => $("#cb-buildingcode").val('').trigger('change'));
	$("#cap-lst-classcode").click(() => $("#cb-classcode").val('').trigger('change'));
	$("#cap-lst-machstatus").click(() => $("#cb-machstatus").val('').trigger('change'));

	// Fix ECharts resize when tab is shown
	$('a[data-toggle="tab"]').on('shown.bs.tab', function() {
		document.querySelectorAll('.chart').forEach(ch => {
			const instance = echarts.getInstanceByDom(ch);
			if (instance) instance.resize();
		});
	});    
});

var EchartsPieMultipleDark = function() {
    var _pieMultipleDarkExample = function() {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        var pie_multiples_element = document.getElementById('pie_multiples');
        if (pie_multiples_element) {
            var pie_multiples = echarts.init(pie_multiples_element);
            var labelTop = {
                show: true,
                position: 'center',
                formatter: '{b}\n',
                fontSize: 15,
                lineHeight: 50,
                rich: {
                    a: {}
                }
            };

            // Background item style
            var backStyle = {
                color: 'rgba(0,0,0,0.15)'
            };

            // Bottom text label
            var labelBottom = {
                color: '#fff',
                show: true,
                position: 'center',
                formatter: function (params) {
                    return '\n\n' + (100 - params.value) + '%'
                },
                fontWeight: 500,
                lineHeight: 35,
                rich: {
                    a: {}
                }
            };

            // Set inner and outer radius
            var radius = [52, 65];  // pie circle size
            // var radius = [42, 55];
            // Options
            pie_multiples.setOption({
                // Colors
                color: [
                    '#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80',
                    '#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa',
                    '#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
                    '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089'
                ],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Add title
                title: {
                    text: 'Machine Category Utilization & Uptime Analysis',
                    //subtext: 'An analysis of machine utilization and uptime across categories assessing operational efficiency and performance.',
                    left: 'center',
                    top: '0%',
                    textStyle: {
                        fontSize: 22,
                        fontWeight: 500,
                        color: '#fff'
                    },
                    subtextStyle: {
                        fontSize: 12,
                        color: '#fff'
                    }
                },

                // Add legend
                legend: {
                    bottom: 0,
                    left: 'center',
                    data: ['Moulding', 'Pet', 'Mixer', 'Aux', 'Pellitizer', 'Extruder', 'PP', 'Injection', 'Cutter', 'Pjar Molding', 'Gallon Molding', 'Straw', 'Cups', 'Printing', 'Fabricator', 'Paper', 'Crusher', 'Cups Forming', 'Lathe'],
                    itemHeight: 8,    // bottom label colored square
                    itemWidth: 8,
                    selectedMode: false,
                    textStyle: {
                        color: '#fff'
                    }
                },

                // Add series
                series: [
                    // Moulding
                    {
                        type: 'pie',
                        center: ['10%', '18%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: moulding_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Moulding', value: moulding, label: labelTop}
                        ]
                    },
                    // Pet
                    {
                        type: 'pie',
                        center: ['30%', '18%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: pet_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Pet', value: pet, label: labelTop}
                        ]
                    },
                    // Mixer
                    {
                        type: 'pie',
                        center: ['50%', '18%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: mixer_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Mixer', value: mixer, label: labelTop}
                        ]
                    },
                    // Aux
                    {
                        type: 'pie',
                        center: ['70%', '18%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: aux_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Aux', value: aux, label: labelTop}
                        ]
                    },
                    // Pellitizer
                    {
                        type: 'pie',
                        center: ['90%', '18%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: pellitizer_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Pellitizer', value: pellitizer, label: labelTop}
                        ]
                    },
                    // Extruder
                    {
                        type: 'pie',
                        center: ['10%', '38%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: extruder_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Extruder', value: extruder, label: labelTop}
                        ]
                    },
                    // PP
                    {
                        type: 'pie',
                        center: ['30%', '38%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: pp_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'PP', value: pp, label: labelTop}
                        ]
                    },
                    // Injection
                    {
                        type: 'pie',
                        center: ['50%', '38%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: injection_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Injection', value: injection, label: labelTop}
                        ]
                    },
                    // Cutter
                    {
                        type: 'pie',
                        center: ['70%', '38%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: cutter_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Cutter', value: cutter, label: labelTop, itemStyle: { color: 'PaleTurquoise' }}
                        ]
                    },
                    // Pjar Molding
                    {
                        type: 'pie',
                        center: ['90%', '38%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: pjar_molding_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Pjar Molding', value: pjar_molding, label: labelTop}
                        ]
                    },
                    // Gallon Molding
                    {
                        type: 'pie',
                        center: ['10%', '58%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: gallon_molding_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Gallon Molding', value: gallon_molding, label: labelTop}
                        ]
                    },
                    // Straw
                    {
                        type: 'pie',
                        center: ['30%', '58%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: straw_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Straw', value: straw, label: labelTop}
                        ]
                    },
                    // Cups
                    {
                        type: 'pie',
                        center: ['50%', '58%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: cups_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Cups', value: cups, label: labelTop}
                        ]
                    },
                    // Printing
                    {
                        type: 'pie',
                        center: ['70%', '58%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: printing_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Printing', value: printing, label: labelTop}
                        ]
                    },
                    // Fabricator
                    {
                        type: 'pie',
                        center: ['90%', '58%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: fabricator_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Fabricator', value: fabricator, label: labelTop}
                        ]
                    },
                    // Paper
                    {
                        type: 'pie',
                        center: ['10%', '78%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: paper_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Paper', value: paper, label: labelTop,itemStyle: { color: 'cornsilk' }}
                        ]
                    },
                    // Crusher
                    {
                        type: 'pie',
                        center: ['30%', '78%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: crusher_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Crusher', value: crusher, label: labelTop, itemStyle: { color: 'cyan' }}
                        ]
                    },
                    // Cups Forming
                    {
                        type: 'pie',
                        center: ['50%', '78%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'other', value: cups_forming_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Cups Forming', value: cups_forming, label: labelTop, itemStyle: { color: 'greenyellow' }}
                        ]
                    },
                    // Lathe
                    {
                        type: 'pie',
                        center: ['70%', '78%'],
                        radius: radius,
                        hoverAnimation: false,
                        data: [
                            {name: 'Lathe', value: lathe_remain, label: labelBottom, itemStyle: backStyle},
                            {name: 'Lathe', value: lathe, label: labelTop,itemStyle: { color: 'violet' }}
                        ],
                        onclick: function (params) {
                            alert('Category: ' + params.name);
                        }
                    }
                ]
            });
            // Register click event
            pie_multiples.on('click', function(params) {
                // params.name will contain the name of the clicked segment
                alert('Category: ' + params.name);
            });
        }


        //
        // Resize charts
        //

        // Resize function
        var triggerChartResize = function() {
            pie_multiples_element && pie_multiples.resize();
        };

        // On sidebar width change
        var sidebarToggle = document.querySelector('.sidebar-control');
        sidebarToggle && sidebarToggle.addEventListener('click', triggerChartResize);

        // On window resize
        var resizeCharts;
        window.addEventListener('resize', function() {
            clearTimeout(resizeCharts);
            resizeCharts = setTimeout(function () {
                triggerChartResize();
            }, 200);
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _pieMultipleDarkExample();
        }
    }
}();


var StatisticWidgets = function() {


    //
    // Setup module components
    //

    // Messages area chart
    var _areaChartWidget = function(element, chartHeight, color) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {


            // Basic setup
            // ------------------------------

            // Define main variables
            var d3Container = d3.select(element),
                margin = {top: 0, right: 0, bottom: 0, left: 0},
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
                height = chartHeight - margin.top - margin.bottom;

            // Date and time format
            var parseDate = d3.time.format('%Y-%m-%d').parse;


            // Create SVG
            // ------------------------------

            // Container
            var container = d3Container.append('svg');

            // SVG element
            var svg = container
                .attr('width', width + margin.left + margin.right)
                .attr('height', height + margin.top + margin.bottom)
                .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


            // Construct chart layout
            // ------------------------------

            // Area
            var area = d3.svg.area()
                .x(function(d) { return x(d.date); })
                .y0(height)
                .y1(function(d) { return y(d.value); })
                .interpolate('monotone');


            // Construct scales
            // ------------------------------

            // Horizontal
            var x = d3.time.scale().range([0, width ]);

            // Vertical
            var y = d3.scale.linear().range([height, 0]);


            // Load data
            // ------------------------------

            d3.json("../../../../global_assets/demo_data/dashboard/monthly_sales.json", function (error, data) {

                // Show what's wrong if error
                if (error) return console.error(error);

                // Pull out values
                data.forEach(function (d) {
                    d.date = parseDate(d.date);
                    d.value = +d.value;
                });

                // Get the maximum value in the given array
                var maxY = d3.max(data, function(d) { return d.value; });

                // Reset start data for animation
                var startData = data.map(function(datum) {
                    return {
                        date: datum.date,
                        value: 0
                    };
                });


                // Set input domains
                // ------------------------------

                // Horizontal
                x.domain(d3.extent(data, function(d, i) { return d.date; }));

                // Vertical
                y.domain([0, d3.max( data, function(d) { return d.value; })]);



                //
                // Append chart elements
                //

                // Add area path
                svg.append("path")
                    .datum(data)
                    .attr("class", "d3-area")
                    .style('fill', color)
                    .attr("d", area)
                    .transition() // begin animation
                        .duration(1000)
                        .attrTween('d', function() {
                            var interpolator = d3.interpolateArray(startData, data);
                            return function (t) {
                                return area(interpolator (t));
                            };
                        });


                // Resize chart
                // ------------------------------

                // Call function on window resize
                window.addEventListener('resize', messagesAreaResize);

                // Call function on sidebar width change
                var sidebarToggle = document.querySelector('.sidebar-control');
                sidebarToggle && sidebarToggle.addEventListener('click', messagesAreaResize);

                // Resize function
                // 
                // Since D3 doesn't support SVG resize by default,
                // we need to manually specify parts of the graph that need to 
                // be updated on window resize
                function messagesAreaResize() {

                    // Layout variables
                    width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;


                    // Layout
                    // -------------------------

                    // Main svg width
                    container.attr("width", width + margin.left + margin.right);

                    // Width of appended group
                    svg.attr("width", width + margin.left + margin.right);

                    // Horizontal range
                    x.range([0, width]);


                    // Chart elements
                    // -------------------------

                    // Area path
                    svg.selectAll('.d3-area').datum( data ).attr("d", area);
                }
            });
        }
    };

    // Simple bar charts
    var _barChartWidget = function(element, barQty, height, animate, easing, duration, delay, color, tooltip) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {


            // Basic setup
            // ------------------------------

            // Add data set
            var bardata = [];
            for (var i=0; i < barQty; i++) {
                bardata.push(Math.round(Math.random() * 10) + 10);
            }

            // Main variables
            var d3Container = d3.select(element),
                width = d3Container.node().getBoundingClientRect().width;
            


            // Construct scales
            // ------------------------------

            // Horizontal
            var x = d3.scale.ordinal()
                .rangeBands([0, width], 0.3);

            // Vertical
            var y = d3.scale.linear()
                .range([0, height]);



            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain(d3.range(0, bardata.length));

            // Vertical
            y.domain([0, d3.max(bardata)]);



            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append('svg');

            // Add SVG group
            var svg = container
                .attr('width', width)
                .attr('height', height)
                .append('g');



            //
            // Append chart elements
            //

            // Bars
            var bars = svg.selectAll('rect')
                .data(bardata)
                .enter()
                .append('rect')
                    .attr('class', 'd3-random-bars')
                    .attr('width', x.rangeBand())
                    .attr('x', function(d,i) {
                        return x(i);
                    })
                    .style('fill', color);



            // Tooltip
            // ------------------------------

            // Initiate
            var tip = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0]);

            // Show and hide
            if(tooltip == "hours" || tooltip == "goal" || tooltip == "members") {
                bars.call(tip)
                    .on('mouseover', tip.show)
                    .on('mouseout', tip.hide);
            }

            // Daily meetings tooltip content
            if(tooltip == "hours") {
                tip.html(function (d, i) {
                    return "<div class='text-center'>" +
                            "<h6 class='mb-0'>" + d + "</h6>" +
                            "<span class='font-size-sm'>meetings</span>" +
                            "<div class='font-size-sm'>" + i + ":00" + "</div>" +
                        "</div>";
                });
            }

            // Statements tooltip content
            if(tooltip == "goal") {
                tip.html(function (d, i) {
                    return "<div class='text-center'>" +
                            "<h6 class='mb-0'>" + d + "</h6>" +
                            "<span class='font-size-sm'>statements</span>" +
                            "<div class='font-size-sm'>" + i + ":00" + "</div>" +
                        "</div>";
                });
            }

            // Online members tooltip content
            if(tooltip == "members") {
                tip.html(function (d, i) {
                    return "<div class='text-center'>" +
                            "<h6 class='mb-0'>" + d + "0" + "</h6>" +
                            "<span class='font-size-sm'>members</span>" +
                            "<div class='font-size-sm'>" + i + ":00" + "</div>" +
                        "</div>";
                });
            }



            // Bar loading animation
            // ------------------------------

            // Choose between animated or static
            if(animate) {
                withAnimation();
            } else {
                withoutAnimation();
            }

            // Animate on load
            function withAnimation() {
                bars
                    .attr('height', 0)
                    .attr('y', height)
                    .transition()
                        .attr('height', function(d) {
                            return y(d);
                        })
                        .attr('y', function(d) {
                            return height - y(d);
                        })
                        .delay(function(d, i) {
                            return i * delay;
                        })
                        .duration(duration)
                        .ease(easing);
            }

            // Load without animateion
            function withoutAnimation() {
                bars
                    .attr('height', function(d) {
                        return y(d);
                    })
                    .attr('y', function(d) {
                        return height - y(d);
                    });
            }



            // Resize chart
            // ------------------------------

            // Call function on window resize
            window.addEventListener('resize', barsResize);

            // Call function on sidebar width change
            var sidebarToggle = document.querySelector('.sidebar-control');
            sidebarToggle && sidebarToggle.addEventListener('click', barsResize);

            // Resize function
            // 
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to 
            // be updated on window resize
            function barsResize() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width;


                // Layout
                // -------------------------

                // Main svg width
                container.attr("width", width);

                // Width of appended group
                svg.attr("width", width);

                // Horizontal range
                x.rangeBands([0, width], 0.3);


                // Chart elements
                // -------------------------

                // Bars
                svg.selectAll('.d3-random-bars')
                    .attr('width', x.rangeBand())
                    .attr('x', function(d,i) {
                        return x(i);
                    });
            }
        }
    };

    // Simple line chart
    var _lineChartWidget = function(element, chartHeight, lineColor, pathColor, pointerLineColor, pointerBgColor) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {


            // Basic setup
            // ------------------------------

            // Add data set
            var dataset = [
                {
                    "date": "04/13/14",
                    "alpha": "60"
                }, {
                    "date": "04/14/14",
                    "alpha": "35"
                }, {
                    "date": "04/15/14",
                    "alpha": "65"
                }, {
                    "date": "04/16/14",
                    "alpha": "50"
                }, {
                    "date": "04/17/14",
                    "alpha": "65"
                }, {
                    "date": "04/18/14",
                    "alpha": "20"
                }, {
                    "date": "04/19/14",
                    "alpha": "60"
                }
            ];

            // Main variables
            var d3Container = d3.select(element),
                margin = {top: 0, right: 0, bottom: 0, left: 0},
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
                height = chartHeight - margin.top - margin.bottom,
                padding = 20;

            // Format date
            var parseDate = d3.time.format("%m/%d/%y").parse,
                formatDate = d3.time.format("%a, %B %e");


            // Add tooltip
            // ------------------------------

            var tooltip = d3.tip()
                .attr('class', 'd3-tip')
                .html(function (d) {
                    return "<ul class='list-unstyled mb-1'>" +
                        "<li>" + "<div class='font-size-base my-1'><i class='icon-check2 mr-2'></i>" + formatDate(d.date) + "</div>" + "</li>" +
                        "<li>" + "Sales: &nbsp;" + "<span class='font-weight-semibold float-right'>" + d.alpha + "</span>" + "</li>" +
                        "<li>" + "Revenue: &nbsp; " + "<span class='font-weight-semibold float-right'>" + "$" + (d.alpha * 25).toFixed(2) + "</span>" + "</li>" + 
                    "</ul>";
                });


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append('svg');

            // Add SVG group
            var svg = container
                    .attr('width', width + margin.left + margin.right)
                    .attr('height', height + margin.top + margin.bottom)
                    .append("g")
                        .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
                        .call(tooltip);


            // Load data
            // ------------------------------

            dataset.forEach(function (d) {
                d.date = parseDate(d.date);
                d.alpha = +d.alpha;
            });


            // Construct scales
            // ------------------------------

            // Horizontal
            var x = d3.time.scale()
                .range([padding, width - padding]);

            // Vertical
            var y = d3.scale.linear()
                .range([height, 5]);


            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain(d3.extent(dataset, function (d) {
                return d.date;
            }));

            // Vertical
            y.domain([0, d3.max(dataset, function (d) {
                return Math.max(d.alpha);
            })]);


            // Construct chart layout
            // ------------------------------

            // Line
            var line = d3.svg.line()
                .x(function(d) {
                    return x(d.date);
                })
                .y(function(d) {
                    return y(d.alpha);
                });


            //
            // Append chart elements
            //

            // Add mask for animation
            // ------------------------------

            // Add clip path
            var clip = svg.append("defs")
                .append("clipPath")
                .attr("id", "clip-line-small");

            // Add clip shape
            var clipRect = clip.append("rect")
                .attr('class', 'clip')
                .attr("width", 0)
                .attr("height", height);

            // Animate mask
            clipRect
                  .transition()
                      .duration(1000)
                      .ease('linear')
                      .attr("width", width);


            // Line
            // ------------------------------

            // Path
            var path = svg.append('path')
                .attr({
                    'd': line(dataset),
                    "clip-path": "url(#clip-line-small)",
                    'class': 'd3-line d3-line-medium'
                })
                .style('stroke', lineColor);

            // Animate path
            svg.select('.line-tickets')
                .transition()
                    .duration(1000)
                    .ease('linear');


            // Add vertical guide lines
            // ------------------------------

            // Bind data
            var guide = svg.append('g')
                .selectAll('.d3-line-guides-group')
                .data(dataset);

            // Append lines
            guide
                .enter()
                .append('line')
                    .attr('class', 'd3-line-guides')
                    .attr('x1', function (d, i) {
                        return x(d.date);
                    })
                    .attr('y1', function (d, i) {
                        return height;
                    })
                    .attr('x2', function (d, i) {
                        return x(d.date);
                    })
                    .attr('y2', function (d, i) {
                        return height;
                    })
                    .style('stroke', pathColor)
                    .style('stroke-dasharray', '4,2')
                    .style('shape-rendering', 'crispEdges');

            // Animate guide lines
            guide
                .transition()
                    .duration(1000)
                    .delay(function(d, i) { return i * 150; })
                    .attr('y2', function (d, i) {
                        return y(d.alpha);
                    });


            // Alpha app points
            // ------------------------------

            // Add points
            var points = svg.insert('g')
                .selectAll('.d3-line-circle')
                .data(dataset)
                .enter()
                .append('circle')
                    .attr('class', 'd3-line-circle d3-line-circle-medium')
                    .attr("cx", line.x())
                    .attr("cy", line.y())
                    .attr("r", 3)
                    .style({
                        'stroke': pointerLineColor,
                        'fill': pointerBgColor
                    });

            // Animate points on page load
            points
                .style('opacity', 0)
                .transition()
                    .duration(250)
                    .ease('linear')
                    .delay(1000)
                    .style('opacity', 1);

            // Add user interaction
            points
                .on("mouseover", function (d) {
                    tooltip.offset([-10, 0]).show(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 4);
                })

                // Hide tooltip
                .on("mouseout", function (d) {
                    tooltip.hide(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 3);
                });

            // Change tooltip direction of first point
            d3.select(points[0][0])
                .on("mouseover", function (d) {
                    tooltip.offset([0, 10]).direction('e').show(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 4);
                })
                .on("mouseout", function (d) {
                    tooltip.direction('n').hide(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 3);
                });

            // Change tooltip direction of last point
            d3.select(points[0][points.size() - 1])
                .on("mouseover", function (d) {
                    tooltip.offset([0, -10]).direction('w').show(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 4);
                })
                .on("mouseout", function (d) {
                    tooltip.direction('n').hide(d);

                    // Animate circle radius
                    d3.select(this).transition().duration(250).attr('r', 3);
                });


            // Resize chart
            // ------------------------------

            // Call function on window resize
            $(window).on('resize', lineChartResize);

            // Call function on sidebar width change
            $(document).on('click', '.sidebar-control', lineChartResize);

            // Resize function
            // 
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to 
            // be updated on window resize
            function lineChartResize() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;


                // Layout
                // -------------------------

                // Main svg width
                container.attr("width", width + margin.left + margin.right);

                // Width of appended group
                svg.attr("width", width + margin.left + margin.right);

                // Horizontal range
                x.range([padding, width - padding]);


                // Chart elements
                // -------------------------

                // Mask
                clipRect.attr("width", width);

                // Line path
                svg.selectAll('.d3-line').attr("d", line(dataset));

                // Circles
                svg.selectAll('.d3-line-circle').attr("cx", line.x());

                // Guide lines
                svg.selectAll('.d3-line-guides')
                    .attr('x1', function (d, i) {
                        return x(d.date);
                    })
                    .attr('x2', function (d, i) {
                        return x(d.date);
                    });
            }
        }
    };

    // Simple sparklines
    var _sparklinesWidget = function(element, chartType, qty, chartHeight, interpolation, duration, interval, color) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {


            // Basic setup
            // ------------------------------

            // Define main variables
            var d3Container = d3.select(element),
                margin = {top: 0, right: 0, bottom: 0, left: 0},
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right,
                height = chartHeight - margin.top - margin.bottom;


            // Generate random data (for demo only)
            var data = [];
            for (var i=0; i < qty; i++) {
                data.push(Math.floor(Math.random() * qty) + 5);
            }


            // Construct scales
            // ------------------------------

            // Horizontal
            var x = d3.scale.linear().range([0, width]);

            // Vertical
            var y = d3.scale.linear().range([height - 5, 5]);


            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain([1, qty - 3]);

            // Vertical
            y.domain([0, qty]);
                

            // Construct chart layout
            // ------------------------------

            // Line
            var line = d3.svg.line()
                .interpolate(interpolation)
                .x(function(d, i) { return x(i); })
                .y(function(d, i) { return y(d); });

            // Area
            var area = d3.svg.area()
                .interpolate(interpolation)
                .x(function(d,i) { 
                    return x(i); 
                })
                .y0(height)
                .y1(function(d) { 
                    return y(d); 
                });


            // Create SVG
            // ------------------------------

            // Container
            var container = d3Container.append('svg');

            // SVG element
            var svg = container
                .attr('width', width + margin.left + margin.right)
                .attr('height', height + margin.top + margin.bottom)
                .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


            // Add mask for animation
            // ------------------------------

            // Add clip path
            var clip = svg.append("defs")
                .append("clipPath")
                .attr('id', function(d, i) { return "load-clip-" + element.substring(1); });

            // Add clip shape
            var clips = clip.append("rect")
                .attr('class', 'load-clip')
                .attr("width", 0)
                .attr("height", height);

            // Animate mask
            clips
                .transition()
                    .duration(1000)
                    .ease('linear')
                    .attr("width", width);


            //
            // Append chart elements
            //

            // Main path
            var path = svg.append("g")
                .attr("clip-path", function(d, i) { return "url(#load-clip-" + element.substring(1) + ")"; })
                .append("path")
                    .datum(data)
                    .attr("transform", "translate(" + x(0) + ",0)");

            // Add path based on chart type
            if(chartType == "area") {
                path.attr("d", area).attr('class', 'd3-area').style("fill", color); // area
            }
            else {
                path.attr("d", line).attr("class", "d3-line d3-line-medium").style('stroke', color); // line
            }

            // Animate path
            path
                .style('opacity', 0)
                .transition()
                    .duration(500)
                    .style('opacity', 1);



            // Set update interval. For demo only
            // ------------------------------

            setInterval(function() {

                // push a new data point onto the back
                data.push(Math.floor(Math.random() * qty) + 5);

                // pop the old data point off the front
                data.shift();

                update();

            }, interval);



            // Update random data. For demo only
            // ------------------------------

            function update() {

                // Redraw the path and slide it to the left
                path
                    .attr("transform", null)
                    .transition()
                        .duration(duration)
                        .ease("linear")
                        .attr("transform", "translate(" + x(0) + ",0)");

                // Update path type
                if(chartType == "area") {
                    path.attr("d", area).attr('class', 'd3-area').style("fill", color);
                }
                else {
                    path.attr("d", line).attr("class", "d3-line d3-line-medium").style('stroke', color);
                }
            }



            // Resize chart
            // ------------------------------

            // Call function on window resize
            window.addEventListener('resize', resizeSparklines);

            // Call function on sidebar width change
            var sidebarToggle = document.querySelector('.sidebar-control');
            sidebarToggle && sidebarToggle.addEventListener('click', resizeSparklines);

            // Resize function
            // 
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to 
            // be updated on window resize
            function resizeSparklines() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;


                // Layout
                // -------------------------

                // Main svg width
                container.attr("width", width + margin.left + margin.right);

                // Width of appended group
                svg.attr("width", width + margin.left + margin.right);

                // Horizontal range
                x.range([0, width]);


                // Chart elements
                // -------------------------

                // Clip mask
                clips.attr("width", width);

                // Line
                svg.select(".d3-line").attr("d", line);

                // Area
                svg.select(".d3-area").attr("d", area);
            }
        }
    };

    // Animated progress with icon
    var _progressIcon = function(element, radius, border, foregroundColor, end, iconClass) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {


            // Basic setup
            // ------------------------------

            // Main variables
            var d3Container = d3.select(element),
                startPercent = 0,
                iconSize = 32,
                endPercent = end,
                twoPi = Math.PI * 2,
                formatPercent = d3.format('.0%'),
                boxSize = radius * 2;

            // Values count
            var count = Math.abs((endPercent - startPercent) / 0.01);

            // Values step
            var step = endPercent < startPercent ? -0.01 : 0.01;


            // Create chart
            // ------------------------------

            // Add SVG element
            var container = d3Container.append('svg');

            // Add SVG group
            var svg = container
                .attr('width', boxSize)
                .attr('height', boxSize)
                .append('g')
                    .attr('transform', 'translate(' + (boxSize / 2) + ',' + (boxSize / 2) + ')');


            // Construct chart layout
            // ------------------------------

            // Arc
            var arc = d3.svg.arc()
                .startAngle(0)
                .innerRadius(radius)
                .outerRadius(radius - border)
                .cornerRadius(20);


            //
            // Append chart elements
            //

            // Paths
            // ------------------------------

            // Background path
            svg.append('path')
                .attr('class', 'd3-progress-background')
                .attr('d', arc.endAngle(twoPi))
                .style('fill', foregroundColor)
                .style('opacity', 0.1);

            // Foreground path
            var foreground = svg.append('path')
                .attr('class', 'd3-progress-foreground')
                .attr('filter', 'url(#blur)')
                .style({
                    'fill': foregroundColor,
                    'stroke': foregroundColor
                });

            // Front path
            var front = svg.append('path')
                .attr('class', 'd3-progress-front')
                .style({
                    'fill': foregroundColor,
                    'fill-opacity': 1
                });


            // Text
            // ------------------------------

            // Percentage text value
            var numberText = d3.select('.progress-percentage')
                    .attr('class', 'pt-1 mt-2 mb-1');

            // Icon
            d3.select(element)
                .append("i")
                    .attr("class", iconClass + " counter-icon")
                    .style({
                        'color': foregroundColor,
                        'top': ((boxSize - iconSize) / 2) + 'px'
                    });


            // Animation
            // ------------------------------

            // Animate path
            function updateProgress(progress) {
                foreground.attr('d', arc.endAngle(twoPi * progress));
                front.attr('d', arc.endAngle(twoPi * progress));
                numberText.text(formatPercent(progress));
            }

            // Animate text
            var progress = startPercent;
            (function loops() {
                updateProgress(progress);
                if (count > 0) {
                    count--;
                    progress += step;
                    setTimeout(loops, 10);
                }
            })();
        }
    };

    // Animated progress with percentage count
    var _progressPercentage = function(element, radius, border, foregroundColor, end) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {


            // Basic setup
            // ------------------------------

            // Main variables
            var d3Container = d3.select(element),
                startPercent = 0,
                fontSize = 22,
                endPercent = end,
                twoPi = Math.PI * 2,
                formatPercent = d3.format('.0%'),
                boxSize = radius * 2;

            // Values count
            var count = Math.abs((endPercent - startPercent) / 0.01);

            // Values step
            var step = endPercent < startPercent ? -0.01 : 0.01;


            // Create chart
            // ------------------------------

            // Add SVG element
            var container = d3Container.append('svg');

            // Add SVG group
            var svg = container
                .attr('width', boxSize)
                .attr('height', boxSize)
                .append('g')
                    .attr('transform', 'translate(' + radius + ',' + radius + ')');


            // Construct chart layout
            // ------------------------------

            // Arc
            var arc = d3.svg.arc()
                .startAngle(0)
                .innerRadius(radius)
                .outerRadius(radius - border)
                .cornerRadius(20);


            //
            // Append chart elements
            //

            // Paths
            // ------------------------------

            // Background path
            svg.append('path')
                .attr('class', 'd3-progress-background')
                .attr('d', arc.endAngle(twoPi))
                .style('fill', foregroundColor)
                .style('opacity', 0.1);

            // Foreground path
            var foreground = svg.append('path')
                .attr('class', 'd3-progress-foreground')
                .attr('filter', 'url(#blur)')
                .style({
                    'fill': foregroundColor,
                    'stroke': foregroundColor
                });

            // Front path
            var front = svg.append('path')
                .attr('class', 'd3-progress-front')
                .style({
                    'fill': foregroundColor,
                    'fill-opacity': 1
                });


            // Text
            // ------------------------------

            // Percentage text value
            var numberText = svg
                .append('text')
                    .attr('dx', 0)
                    .attr('dy', (fontSize / 2) - border)
                    .style({
                        'font-size': fontSize + 'px',
                        'line-height': 1,
                        'fill': foregroundColor,
                        'text-anchor': 'middle'
                    });


            // Animation
            // ------------------------------

            // Animate path
            function updateProgress(progress) {
                foreground.attr('d', arc.endAngle(twoPi * progress));
                front.attr('d', arc.endAngle(twoPi * progress));
                numberText.text(formatPercent(progress));
            }

            // Animate text
            var progress = startPercent;
            (function loops() {
                updateProgress(progress);
                if (count > 0) {
                    count--;
                    progress += step;
                    setTimeout(loops, 10);
                }
            })();
        }
    };

    // Simple pie
    var _animatedPie = function(element, size) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Add data set
            var data = [
                {
                    "status": "Pending tickets",
                    "icon": "<i class='icon-history text-blue mr-2'></i>",
                    "value": 938,
                    "color": "#29B6F6"
                }, {
                    "status": "Resolved tickets",
                    "icon": "<i class='icon-checkmark3 text-success mr-2'></i>",
                    "value": 490,
                    "color": "#66BB6A"
                }, {
                    "status": "Closed tickets",
                    "icon": "<i class='icon-cross2 text-danger mr-2'></i>",
                    "value": 789,
                    "color": "#EF5350"
                }
            ];

            // Main variables
            var d3Container = d3.select(element),
                distance = 2, // reserve 2px space for mouseover arc moving
                radius = (size/2) - distance,
                sum = d3.sum(data, function(d) { return d.value; });


            // Tooltip
            // ------------------------------

            var tip = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0])
                .direction('e')
                .html(function (d) {
                    return "<ul class='list-unstyled mb-1'>" +
                        "<li>" + "<div class='font-size-base my-1'>" + d.data.icon + d.data.status + "</div>" + "</li>" +
                        "<li>" + "Total: &nbsp;" + "<span class='font-weight-semibold float-right'>" + d.value + "</span>" + "</li>" +
                        "<li>" + "Share: &nbsp;" + "<span class='font-weight-semibold float-right'>" + (100 / (sum / d.value)).toFixed(2) + "%" + "</span>" + "</li>" +
                    "</ul>";
                });


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg").call(tip);
            
            // Add SVG group
            var svg = container
                .attr("width", size)
                .attr("height", size)
                .append("g")
                    .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");  


            // Construct chart layout
            // ------------------------------

            // Pie
            var pie = d3.layout.pie()
                .sort(null)
                .startAngle(Math.PI)
                .endAngle(3 * Math.PI)
                .value(function (d) { 
                    return d.value;
                }); 

            // Arc
            var arc = d3.svg.arc()
                .outerRadius(radius);


            //
            // Append chart elements
            //

            // Group chart elements
            var arcGroup = svg.selectAll(".d3-arc")
                .data(pie(data))
                .enter()
                .append("g") 
                    .attr("class", "d3-arc d3-slice-border")
                    .style({
                        'cursor': 'pointer'
                    });
            
            // Append path
            var arcPath = arcGroup
                .append("path")
                .style("fill", function (d) {
                    return d.data.color;
                });

            // Add tooltip
            arcPath
                .on('mouseover', function (d, i) {

                    // Transition on mouseover
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('elastic')
                        .attr('transform', function (d) {
                            d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                            var x = Math.sin(d.midAngle) * distance;
                            var y = -Math.cos(d.midAngle) * distance;
                            return 'translate(' + x + ',' + y + ')';
                        });
                })
                .on("mousemove", function (d) {
                    
                    // Show tooltip on mousemove
                    tip.show(d)
                        .style("top", (d3.event.pageY - 40) + "px")
                        .style("left", (d3.event.pageX + 30) + "px");
                })
                .on('mouseout', function (d, i) {

                    // Mouseout transition
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('bounce')
                        .attr('transform', 'translate(0,0)');

                    // Hide tooltip
                    tip.hide(d);
                });

            // Animate chart on load
            arcPath
                .transition()
                    .delay(function(d, i) { return i * 500; })
                    .duration(500)
                    .attrTween("d", function(d) {
                        var interpolate = d3.interpolate(d.startAngle,d.endAngle);
                        return function(t) {
                            d.endAngle = interpolate(t);
                            return arc(d);  
                        }; 
                    });


            //
            // Append counter
            //

            // Append element
            d3Container
                .append('h2')
                .attr('class', 'pt-1 mt-2 mb-1 font-weight-semibold');

            // Animate counter
            d3Container.select('h2')
                .transition()
                .duration(1500)
                .tween("text", function(d) {
                    var i = d3.interpolate(this.textContent, sum);

                    return function(t) {
                        this.textContent = d3.format(",d")(Math.round(i(t)));
                    };
                });
        }
    };

    // Pie with legend
    var _animatedPieWithLegend = function(element, size) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Add data set
            var data = [
                {
                    "status": "New",
                    "value": 578,
                    "color": "#29B6F6"
                }, {
                    "status": "Pending",
                    "value": 983,
                    "color": "#66BB6A"
                }, {
                    "status": "Shipped",
                    "value": 459,
                    "color": "#EF5350"
                }
            ];

            // Main variables
            var d3Container = d3.select(element),
                distance = 2, // reserve 2px space for mouseover arc moving
                radius = (size/2) - distance,
                sum = d3.sum(data, function(d) { return d.value; });


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg");
            
            // Add SVG group
            var svg = container
                .attr("width", size)
                .attr("height", size)
                .append("g")
                    .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");  


            // Construct chart layout
            // ------------------------------

            // Pie
            var pie = d3.layout.pie()
                .sort(null)
                .startAngle(Math.PI)
                .endAngle(3 * Math.PI)
                .value(function (d) { 
                    return d.value;
                }); 

            // Arc
            var arc = d3.svg.arc()
                .outerRadius(radius);


            //
            // Append chart elements
            //

            // Group chart elements
            var arcGroup = svg.selectAll(".d3-arc")
                .data(pie(data))
                .enter()
                .append("g") 
                    .attr("class", "d3-arc d3-slice-border")
                    .style({
                        'cursor': 'pointer'
                    });
            
            // Append path
            var arcPath = arcGroup
                .append("path")
                .style("fill", function (d) {
                    return d.data.color;
                });


            // Add interactions
            arcPath
                .on('mouseover', function (d, i) {

                    // Transition on mouseover
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('elastic')
                        .attr('transform', function (d) {
                            d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                            var x = Math.sin(d.midAngle) * distance;
                            var y = -Math.cos(d.midAngle) * distance;
                            return 'translate(' + x + ',' + y + ')';
                        });

                    // Animate legend
                    $(element + ' [data-slice]').css({
                        'opacity': 0.3,
                        'transition': 'all ease-in-out 0.15s'
                    });
                    $(element + ' [data-slice=' + i + ']').css({'opacity': 1});
                })
                .on('mouseout', function (d, i) {

                    // Mouseout transition
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('bounce')
                        .attr('transform', 'translate(0,0)');

                    // Revert legend animation
                    $(element + ' [data-slice]').css('opacity', 1);
                });

            // Animate chart on load
            arcPath
                .transition()
                    .delay(function(d, i) { return i * 500; })
                    .duration(500)
                    .attrTween("d", function(d) {
                        var interpolate = d3.interpolate(d.startAngle,d.endAngle);
                        return function(t) {
                            d.endAngle = interpolate(t);
                            return arc(d);  
                        }; 
                    });


            //
            // Append counter
            //

            // Append element
            d3Container
                .append('h2')
                .attr('class', 'pt-1 mt-2 mb-1 font-weight-semibold');

            // Animate counter
            d3Container.select('h2')
                .transition()
                .duration(1500)
                .tween("text", function(d) {
                    var i = d3.interpolate(this.textContent, sum);

                    return function(t) {
                        this.textContent = d3.format(",d")(Math.round(i(t)));
                    };
                });


            //
            // Append legend
            //

            // Add element
            var legend = d3.select(element)
                .append('ul')
                .attr('class', 'chart-widget-legend')
                .selectAll('li').data(pie(data))
                .enter().append('li')
                .attr('data-slice', function(d, i) {
                    return i;
                })
                .attr('style', function(d, i) {
                    return 'border-bottom: 2px solid ' + d.data.color;
                })
                .text(function(d, i) {
                    return d.data.status + ': ';
                });

            // Add value
            legend.append('span')
                .text(function(d, i) {
                    return d.data.value;
                });
        }
    };

    // Pie arc with legend
    var _pieArcWithLegend = function(element, size) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {


            // Basic setup
            // ------------------------------

            // Add data set
            var data = [
                {
                    "status": "Pending",
                    "icon": "<i class='icon-history text-blue mr-2'></i>",
                    "value": 720,
                    "color": "#29B6F6"
                }, {
                    "status": "Resolved",
                    "icon": "<i class='icon-checkmark3 text-success mr-2'></i>",
                    "value": 990,
                    "color": "#66BB6A"
                }, {
                    "status": "Closed",
                    "icon": "<i class='icon-cross2 text-danger mr-2'></i>",
                    "value": 720,
                    "color": "#EF5350"
                }
            ];

            // Main variables
            var d3Container = d3.select(element),
                distance = 2, // reserve 2px space for mouseover arc moving
                radius = (size/2) - (distance * 2),
                sum = d3.sum(data, function(d) { return d.value; });



            // Tooltip
            // ------------------------------

            var tip = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0])
                .direction('e')
                .html(function (d) {
                    return "<ul class='list-unstyled mb-1'>" +
                        "<li>" + "<div class='font-size-base my-1'>" + d.data.icon + d.data.status + "</div>" + "</li>" +
                        "<li>" + "Total: &nbsp;" + "<span class='font-weight-semibold float-right'>" + d.value + "</span>" + "</li>" +
                        "<li>" + "Share: &nbsp;" + "<span class='font-weight-semibold float-right'>" + (100 / (sum / d.value)).toFixed(2) + "%" + "</span>" + "</li>" +
                    "</ul>";
                });



            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg").call(tip);
            
            // Add SVG group
            var svg = container
                .attr("width", size)
                .attr("height", size / 2)
                .append("g")
                    .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");  



            // Construct chart layout
            // ------------------------------

            // Pie
            var pie = d3.layout.pie()
                .sort(null)
                .startAngle(-Math.PI / 2)
                .endAngle(Math.PI / 2)
                .value(function (d) { 
                    return d.value;
                }); 

            // Arc
            var arc = d3.svg.arc()
                .outerRadius(radius)
                .innerRadius(radius / 1.3);



            //
            // Append chart elements
            //

            // Group chart elements
            var arcGroup = svg.selectAll(".d3-arc")
                .data(pie(data))
                .enter()
                .append("g") 
                    .attr("class", "d3-arc d3-slice-border")
                    .style({
                        'cursor': 'pointer'
                    });

            // Append path
            var arcPath = arcGroup
                .append("path")
                .style("fill", function (d) {
                    return d.data.color;
                });


            //
            // Interactions
            //

            // Mouse
            arcPath
                .on('mouseover', function(d, i) {

                    // Transition on mouseover
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('elastic')
                        .attr('transform', function (d) {
                            d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                            var x = Math.sin(d.midAngle) * distance;
                            var y = -Math.cos(d.midAngle) * distance;
                            return 'translate(' + x + ',' + y + ')';
                        });

                    $(element + ' [data-slice]').css({
                        'opacity': 0.3,
                        'transition': 'all ease-in-out 0.15s'
                    });
                    $(element + ' [data-slice=' + i + ']').css({'opacity': 1});
                })
                .on('mouseout', function(d, i) {

                    // Mouseout transition
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('bounce')
                        .attr('transform', 'translate(0,0)');

                    $(element + ' [data-slice]').css('opacity', 1);
                });

            // Animate chart on load
            arcPath
                .transition()
                    .delay(function(d, i) {
                        return i * 500;
                    })
                    .duration(500)
                    .attrTween("d", function(d) {
                        var interpolate = d3.interpolate(d.startAngle,d.endAngle);
                        return function(t) {
                            d.endAngle = interpolate(t);
                            return arc(d);  
                        }; 
                    });


            //
            // Append total text
            //

            svg.append('text')
                // .attr('class', 'text-muted')
                .attr({
                    'class': 'half-donut-total d3-text opacity-50',
                    'text-anchor': 'middle',
                    'dy': -33
                })
                .text('Total');


            //
            // Append count
            //

            // Text
            svg
                .append('text')
                .attr('class', 'half-conut-count d3-text')
                .attr('text-anchor', 'middle')
                .attr('dy', -5)
                .style({
                    'font-size': '21px',
                    'font-weight': 500
                });

            // Animation
            svg.select('.half-conut-count')
                .transition()
                .duration(1500)
                .ease('linear')
                .tween("text", function(d) {
                    var i = d3.interpolate(this.textContent, sum);

                    return function(t) {
                        this.textContent = d3.format(",d")(Math.round(i(t)));
                    };
                });


            //
            // Legend
            //

            // Add legend list
            var legend = d3.select(element)
                .append('ul')
                .attr('class', 'chart-widget-legend')
                .selectAll('li')
                .data(pie(data))
                .enter()
                .append('li')
                .attr('data-slice', function(d, i) {
                    return i;
                })
                .attr('style', function(d, i) {
                    return 'border-bottom: solid 2px ' + d.data.color;
                })
                .text(function(d, i) {
                    return d.data.status + ': ';
                });

            // Legend text
            legend.append('span')
                .text(function(d, i) {
                    return d.data.value;
                });
        }
    };

    // Simple donut
    var _animatedDonut = function(element, size) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Add data set
            var data = [
                {
                    "status": "Pending tickets",
                    "icon": "<i class='icon-history text-blue mr-2'></i>",
                    "value": 567,
                    "color": "#29B6F6"
                }, {
                    "status": "Resolved tickets",
                    "icon": "<i class='icon-checkmark3 text-success mr-2'></i>",
                    "value": 234,
                    "color": "#66BB6A"
                }, {
                    "status": "Closed tickets",
                    "icon": "<i class='icon-cross2 text-danger mr-2'></i>",
                    "value": 642,
                    "color": "#EF5350"
                }
            ];

            // Main variables
            var d3Container = d3.select(element),
                distance = 2, // reserve 2px space for mouseover arc moving
                radius = (size/2) - distance,
                sum = d3.sum(data, function(d) { return d.value; });


            // Tooltip
            // ------------------------------

            var tip = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0])
                .direction('e')
                .html(function (d) {
                    return "<ul class='list-unstyled mb-1'>" +
                        "<li>" + "<div class='font-size-base my-1'>" + d.data.icon + d.data.status + "</div>" + "</li>" +
                        "<li>" + "Total: &nbsp;" + "<span class='font-weight-semibold float-right'>" + d.value + "</span>" + "</li>" +
                        "<li>" + "Share: &nbsp;" + "<span class='font-weight-semibold float-right'>" + (100 / (sum / d.value)).toFixed(2) + "%" + "</span>" + "</li>" +
                    "</ul>";
                });


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg").call(tip);
            
            // Add SVG group
            var svg = container
                .attr("width", size)
                .attr("height", size)
                .append("g")
                    .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");  


            // Construct chart layout
            // ------------------------------

            // Pie
            var pie = d3.layout.pie()
                .sort(null)
                .startAngle(Math.PI)
                .endAngle(3 * Math.PI)
                .value(function (d) { 
                    return d.value;
                }); 

            // Arc
            var arc = d3.svg.arc()
                .outerRadius(radius)
                .innerRadius(radius / 1.5);


            //
            // Append chart elements
            //

            // Group chart elements
            var arcGroup = svg.selectAll(".d3-arc")
                .data(pie(data))
                .enter()
                .append("g") 
                    .attr("class", "d3-arc d3-slice-border")
                    .style({
                        'cursor': 'pointer'
                    });
            
            // Append path
            var arcPath = arcGroup
                .append("path")
                .style("fill", function (d) {
                    return d.data.color;
                });

            // Add tooltip
            arcPath
                .on('mouseover', function (d, i) {

                    // Transition on mouseover
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('elastic')
                        .attr('transform', function (d) {
                            d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                            var x = Math.sin(d.midAngle) * distance;
                            var y = -Math.cos(d.midAngle) * distance;
                            return 'translate(' + x + ',' + y + ')';
                        });
                })
                .on("mousemove", function (d) {
                    
                    // Show tooltip on mousemove
                    tip.show(d)
                        .style("top", (d3.event.pageY - 40) + "px")
                        .style("left", (d3.event.pageX + 30) + "px");
                })
                .on('mouseout', function (d, i) {

                    // Mouseout transition
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('bounce')
                        .attr('transform', 'translate(0,0)');

                    // Hide tooltip
                    tip.hide(d);
                });

            // Animate chart on load
            arcPath
                .transition()
                    .delay(function(d, i) { return i * 500; })
                    .duration(500)
                    .attrTween("d", function(d) {
                        var interpolate = d3.interpolate(d.startAngle,d.endAngle);
                        return function(t) {
                            d.endAngle = interpolate(t);
                            return arc(d);  
                        }; 
                    });


            //
            // Append counter
            //

            // Append text
            svg
                .append('text')
                .attr('class', 'd3-text')
                .attr('text-anchor', 'middle')
                .attr('dy', 6)
                .style({
                    'font-size': '17px',
                    'font-weight': 500
                });

            // Animate text
            svg.select('text')
                .transition()
                .duration(1500)
                .tween("text", function(d) {
                    var i = d3.interpolate(this.textContent, sum);
                    return function(t) {
                        this.textContent = d3.format(",d")(Math.round(i(t)));
                    };
                });
        }
    };

    // Donut with legend
    var _animatedDonutWithLegend = function(element, size) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Add data set
            var data = [
                {
                    "status": "New",
                    "value": 790,
                    "color": "#29B6F6"
                }, {
                    "status": "Pending",
                    "value": 850,
                    "color": "#66BB6A"
                }, {
                    "status": "Shipped",
                    "value": 760,
                    "color": "#EF5350"
                }
            ];

            // Main variables
            var d3Container = d3.select(element),
                distance = 2, // reserve 2px space for mouseover arc moving
                radius = (size/2) - distance,
                sum = d3.sum(data, function(d) { return d.value; });


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg");
            
            // Add SVG group
            var svg = container
                .attr("width", size)
                .attr("height", size)
                .append("g")
                    .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");  


            // Construct chart layout
            // ------------------------------

            // Pie
            var pie = d3.layout.pie()
                .sort(null)
                .startAngle(Math.PI)
                .endAngle(3 * Math.PI)
                .value(function (d) { 
                    return d.value;
                }); 

            // Arc
            var arc = d3.svg.arc()
                .outerRadius(radius)
                .innerRadius(radius / 1.5);


            //
            // Append chart elements
            //

            // Group chart elements
            var arcGroup = svg.selectAll(".d3-arc")
                .data(pie(data))
                .enter()
                .append("g") 
                    .attr("class", "d3-arc d3-slice-border")
                    .style({
                        'cursor': 'pointer'
                    });
            
            // Append path
            var arcPath = arcGroup
                .append("path")
                .style("fill", function (d) {
                    return d.data.color;
                });


            // Add interactions
            arcPath
                .on('mouseover', function (d, i) {

                    // Transition on mouseover
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('elastic')
                        .attr('transform', function (d) {
                            d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                            var x = Math.sin(d.midAngle) * distance;
                            var y = -Math.cos(d.midAngle) * distance;
                            return 'translate(' + x + ',' + y + ')';
                        });

                    // Animate legend
                    $(element + ' [data-slice]').css({
                        'opacity': 0.3,
                        'transition': 'all ease-in-out 0.15s'
                    });
                    $(element + ' [data-slice=' + i + ']').css({'opacity': 1});
                })
                .on('mouseout', function (d, i) {

                    // Mouseout transition
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('bounce')
                        .attr('transform', 'translate(0,0)');

                    // Revert legend animation
                    $(element + ' [data-slice]').css('opacity', 1);
                });

            // Animate chart on load
            arcPath
                .transition()
                    .delay(function(d, i) {
                        return i * 500;
                    })
                    .duration(500)
                    .attrTween("d", function(d) {
                        var interpolate = d3.interpolate(d.startAngle,d.endAngle);
                        return function(t) {
                            d.endAngle = interpolate(t);
                            return arc(d);  
                        }; 
                    });


            //
            // Append counter
            //

            // Append text
            svg
                .append('text')
                .attr('class', 'd3-text')
                .attr('text-anchor', 'middle')
                .attr('dy', 6)
                .style({
                    'font-size': '17px',
                    'font-weight': 500
                });

            // Animate text
            svg.select('text')
                .transition()
                .duration(1500)
                .tween("text", function(d) {
                    var i = d3.interpolate(this.textContent, sum);
                    return function(t) {
                        this.textContent = d3.format(",d")(Math.round(i(t)));
                    };
                });


            //
            // Append legend
            //

            // Add element
            var legend = d3.select(element)
                .append('ul')
                .attr('class', 'chart-widget-legend')
                .selectAll('li').data(pie(data))
                .enter().append('li')
                .attr('data-slice', function(d, i) {
                    return i;
                })
                .attr('style', function(d, i) {
                    return 'border-bottom: 2px solid ' + d.data.color;
                })
                .text(function(d, i) {
                    return d.data.status + ': ';
                });

            // Add value
            legend.append('span')
                .text(function(d, i) {
                    return d.data.value;
                });
        }
    };

    // Donut with details
    var _donutWithDetails = function(element, size) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {


            // Basic setup
            // ------------------------------

            // Add data set
            var data = [
                {
                    "status": "Pending",
                    "icon": "<i class='badge badge-mark border-blue-300 mr-2'></i>",
                    "value": 720,
                    "color": "#29B6F6"
                }, {
                    "status": "Resolved",
                    "icon": "<i class='badge badge-mark border-success-300 mr-2'></i>",
                    "value": 990,
                    "color": "#66BB6A"
                }, {
                    "status": "Closed",
                    "icon": "<i class='badge badge-mark border-danger-300 mr-2'></i>",
                    "value": 720,
                    "color": "#EF5350"
                }
            ];

            // Main variables
            var d3Container = d3.select(element),
                distance = 2, // reserve 2px space for mouseover arc moving
                radius = (size/2) - distance,
                sum = d3.sum(data, function(d) { return d.value; });


            // Tooltip
            // ------------------------------

            var tip = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0])
                .direction('e')
                .html(function (d) {
                    return "<ul class='list-unstyled mb-1'>" +
                        "<li>" + "<div class='font-size-base my-1'>" + d.data.icon + d.data.status + "</div>" + "</li>" +
                        "<li>" + "Total: &nbsp;" + "<span class='font-weight-semibold float-right'>" + d.value + "</span>" + "</li>" +
                        "<li>" + "Share: &nbsp;" + "<span class='font-weight-semibold float-right'>" + (100 / (sum / d.value)).toFixed(2) + "%" + "</span>" + "</li>" +
                    "</ul>";
                });


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg").call(tip);
            
            // Add SVG group
            var svg = container
                .attr("width", size)
                .attr("height", size)
                .append("g")
                    .attr("transform", "translate(" + (size / 2) + "," + (size / 2) + ")");  


            // Construct chart layout
            // ------------------------------

            // Pie
            var pie = d3.layout.pie()
                .sort(null)
                .startAngle(Math.PI)
                .endAngle(3 * Math.PI)
                .value(function (d) { 
                    return d.value;
                }); 

            // Arc
            var arc = d3.svg.arc()
                .outerRadius(radius)
                .innerRadius(radius / 1.35);


            //
            // Append chart elements
            //

            // Group chart elements
            var arcGroup = svg.selectAll(".d3-arc")
                .data(pie(data))
                .enter()
                .append("g") 
                    .attr("class", "d3-arc d3-slice-border")
                    .style({
                        'cursor': 'pointer'
                    });
            
            // Append path
            var arcPath = arcGroup
                .append("path")
                .style("fill", function (d) {
                    return d.data.color;
                });


            //
            // Add interactions
            //

            // Mouse
            arcPath
                .on('mouseover', function(d, i) {

                    // Transition on mouseover
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('elastic')
                        .attr('transform', function (d) {
                            d.midAngle = ((d.endAngle - d.startAngle) / 2) + d.startAngle;
                            var x = Math.sin(d.midAngle) * distance;
                            var y = -Math.cos(d.midAngle) * distance;
                            return 'translate(' + x + ',' + y + ')';
                        });

                    $(element + ' [data-slice]').css({
                        'opacity': 0.3,
                        'transition': 'all ease-in-out 0.15s'
                    });
                    $(element + ' [data-slice=' + i + ']').css({'opacity': 1});
                })
                .on('mouseout', function(d, i) {

                    // Mouseout transition
                    d3.select(this)
                    .transition()
                        .duration(500)
                        .ease('bounce')
                        .attr('transform', 'translate(0,0)');

                    $(element + ' [data-slice]').css('opacity', 1);
                });

            // Animate chart on load
            arcPath
                .transition()
                .delay(function(d, i) {
                    return i * 500;
                })
                .duration(500)
                .attrTween("d", function(d) {
                    var interpolate = d3.interpolate(d.startAngle,d.endAngle);
                    return function(t) {
                        d.endAngle = interpolate(t);
                        return arc(d);  
                    }; 
                });


            //
            // Add text
            //

            // Total
            svg.append('text')
                .attr({
                    'class': 'half-donut-total d3-text opacity-50',
                    'text-anchor': 'middle',
                    'dy': -13
                })
                .text('Total');

            // Count
            svg
                .append('text')
                .attr('class', 'half-donut-count d3-text')
                .attr('text-anchor', 'middle')
                .attr('dy', 14)
                .style({
                    'font-size': '21px',
                    'font-weight': 500
                });

            // Animate count
            svg.select('.half-donut-count')
                .transition()
                .duration(1500)
                .ease('linear')
                .tween("text", function(d) {
                    var i = d3.interpolate(this.textContent, sum);

                    return function(t) {
                        this.textContent = d3.format(",d")(Math.round(i(t)));
                    };
                });


            //
            // Add legend
            //

            // Append list
            var legend = d3.select(element)
                .append('ul')
                .attr('class', 'chart-widget-legend')
                .selectAll('li')
                .data(pie(data))
                .enter()
                .append('li')
                .attr('data-slice', function(d, i) {
                    return i;
                })
                .attr('style', function(d, i) {
                    return 'border-bottom: solid 2px ' + d.data.color;
                })
                .text(function(d, i) {
                    return d.data.status + ': ';
                });

            // Append text
            legend.append('span')
                .text(function(d, i) {
                    return d.data.value;
                });
        }
    };

    // Progress arc - single color
    var _progressArcSingle = function(element, size) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Main variables
            var d3Container = d3.select(element),
                radius = size,
                thickness = 20,
                color = '#29B6F6';


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg");
            
            // Add SVG group
            var svg = container
                .attr('width', radius * 2)
                .attr('height', radius + 20)
                .attr('class', 'gauge');


            // Construct chart layout
            // ------------------------------

            // Pie
            var arc = d3.svg.arc()
                .innerRadius(radius - thickness)
                .outerRadius(radius)
                .startAngle(-Math.PI / 2);


            // Append chart elements
            // ------------------------------

            //
            // Group arc elements
            //

            // Group
            var chart = svg.append('g')
                .attr('transform', 'translate(' + radius + ',' + radius + ')');

            // Background
            var background = chart.append('path')
                .datum({
                    endAngle: Math.PI / 2
                })
                .attr({
                    'd': arc,
                    'class': 'd3-state-empty'
                });

            // Foreground
            var foreground = chart.append('path')
                .datum({
                    endAngle: -Math.PI / 2
                })
                .style('fill', color)
                .attr('d', arc);

            // Counter value
            var value = svg.append('g')
                .attr('transform', 'translate(' + radius + ',' + (radius * 0.9) + ')')
                .append('text')
                .text(0 + '%')
                .attr({
                    'class': 'd3-text',
                    'text-anchor': 'middle'
                })
                .style({
                    'font-size': 19,
                    'font-weight': 400
                });


            //
            // Min and max text
            //

            // Group
            var scale = svg.append('g')
                .attr('transform', 'translate(' + radius + ',' + (radius + 15) + ')')
                .attr('class', 'd3-text opacity-75')
                .style({
                    'font-size': 12
                });

            // Max
            scale.append('text')
                .text(100)
                .attr({
                    'text-anchor': 'middle',
                    'x': (radius - thickness / 2)
                });

            // Min
            scale.append('text')
                .text(0)
                .attr({
                    'text-anchor': 'middle',
                    'x': -(radius - thickness / 2)
                });


            //
            // Animation
            //

            // Interval
            setInterval(function() {
                update(Math.random() * 100);
            }, 1500);

            // Update
            function update(v) {
                v = d3.format('.0f')(v);
                foreground.transition()
                    .duration(750)
                    .call(arcTween, v);

                value.transition()
                    .duration(750)
                    .call(textTween, v);
            }

            // Arc
            function arcTween(transition, v) {
                var newAngle = v / 100 * Math.PI - Math.PI / 2;
                transition.attrTween('d', function(d) {
                    var interpolate = d3.interpolate(d.endAngle, newAngle);
                    return function(t) {
                        d.endAngle = interpolate(t);
                        return arc(d);
                    };
                });
            }

            // Text
            function textTween(transition, v) {
                transition.tween('text', function() {
                    var interpolate = d3.interpolate(this.innerHTML, v),
                        split = (v + '').split('.'),
                        round = (split.length > 1) ? Math.pow(10, split[1].length) : 1;
                    return function(t) {
                        this.innerHTML = d3.format('.0f')(Math.round(interpolate(t) * round) / round) + '<tspan>%</tspan>';
                    };
                });
            }
        }
    };

    // Progress arc - multiple colors
    var _progressArcMulti = function(element, size, goal) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Main variables
            var d3Container = d3.select(element),
                radius = size,
                thickness = 20,
                startColor = '#66BB6A',
                midColor = '#FFA726',
                endColor = '#EF5350';

            // Colors
            var color = d3.scale.linear()
                .domain([0, 70, 100])
                .range([startColor, midColor, endColor]);


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg");
            
            // Add SVG group
            var svg = container
                .attr('width', radius * 2)
                .attr('height', radius + 20);


            // Construct chart layout
            // ------------------------------

            // Pie
            var arc = d3.svg.arc()
                .innerRadius(radius - thickness)
                .outerRadius(radius)
                .startAngle(-Math.PI / 2);


            // Append chart elements
            // ------------------------------

            //
            // Group arc elements
            //

            // Group
            var chart = svg.append('g')
                .attr('transform', 'translate(' + radius + ',' + radius + ')');

            // Background
            var background = chart.append('path')
                .datum({
                    endAngle: Math.PI / 2
                })
                .attr({
                    'd': arc,
                    'class': 'd3-state-empty'
                });

            // Foreground
            var foreground = chart.append('path')
                .datum({
                    endAngle: -Math.PI / 2
                })
                .style('fill', startColor)
                .attr('d', arc);

            // Counter value
            var value = svg.append('g')
                .attr('transform', 'translate(' + radius + ',' + (radius * 0.9) + ')')
                .append('text')
                .text(0 + '%')
                .attr({
                    'class': 'd3-text',
                    'text-anchor': 'middle'
                })
                .style({
                    'font-size': 19,
                    'font-weight': 400
                });


            //
            // Min and max text
            //

            // Group
            var scale = svg.append('g')
                .attr('transform', 'translate(' + radius + ',' + (radius + 15) + ')')
                .attr('class', 'd3-text opacity-75')
                .style({
                    'font-size': 12
                });

            // Max
            scale.append('text')
                .text(100)
                .attr({
                    'text-anchor': 'middle',
                    'x': (radius - thickness / 2)
                });

            // Min
            scale.append('text')
                .text(0)
                .attr({
                    'text-anchor': 'middle',
                    'x': -(radius - thickness / 2)
                });


            //
            // Animation
            //

            // Interval
            setInterval(function() {
                update(Math.random() * 100);
            }, 1500);

            // Update
            function update(v) {
                v = d3.format('.0f')(v);
                foreground.transition()
                    .duration(750)
                    .style('fill', function() {
                        return color(v);
                    })
                    .call(arcTween, v);

                value.transition()
                    .duration(750)
                    .call(textTween, v);
            }

            // Arc
            function arcTween(transition, v) {
                var newAngle = v / 100 * Math.PI - Math.PI / 2;
                transition.attrTween('d', function(d) {
                    var interpolate = d3.interpolate(d.endAngle, newAngle);
                    return function(t) {
                        d.endAngle = interpolate(t);
                        return arc(d);
                    };
                });
            }

            // Text
            function textTween(transition, v) {
                transition.tween('text', function() {
                    var interpolate = d3.interpolate(this.innerHTML, v),
                        split = (v + '').split('.'),
                        round = (split.length > 1) ? Math.pow(10, split[1].length) : 1;
                    return function(t) {
                        this.innerHTML = d3.format('.0f')(Math.round(interpolate(t) * round) / round) + '<tspan>%</tspan>';
                    };
                });
            }
        }
    };

    // Rounded progress - single arc
    var _roundedProgressSingle = function(element, size, goal, color) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Add random data
            var dataset = function () {
                return [
                    {percentage: Math.random() * 100}
                ];
            };

            // Main variables
            var d3Container = d3.select(element),
                padding = 2,
                strokeWidth = 16,
                width = size,
                height = size,
                τ = 2 * Math.PI;


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg");
            
            // Add SVG group
            var svg = container
                .attr("width", width)
                .attr("height", height)
                .append("g")
                    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");


            // Construct chart layout
            // ------------------------------

            // Foreground arc
            var arc = d3.svg.arc()
                .startAngle(0)
                .endAngle(function (d) {
                    return d.percentage / 100 * τ;
                })
                .innerRadius((size / 2) - strokeWidth)
                .outerRadius((size / 2) - padding)
                .cornerRadius(20);

            // Background arc
            var background = d3.svg.arc()
                .startAngle(0)
                .endAngle(τ)
                .innerRadius((size / 2) - strokeWidth)
                .outerRadius((size / 2) - padding);


            // Append chart elements
            // ------------------------------

            //
            // Group arc elements
            //

            // Group
            var field = svg.selectAll("g")
                .data(dataset)
                .enter().append("g");

            // Foreground arc
            field
                .append("path")
                .attr("class", "arc-foreground")
                .attr('fill', color);

            // Background arc
            field
                .append("path")
                .attr("d", background)
                .style({
                    "fill": color,
                    "opacity": 0.2
                });


            //
            // Text
            //

            // Goal
            field
                .append("text")
                .text("Out of " + goal)
                .attr({
                    'class': 'd3-text opacity-50',
                    'transform': 'translate(0,20)'
                })
                .style({
                    'font-size': 11,
                    'font-weight': 500,
                    'text-transform': 'uppercase',
                    'text-anchor': 'middle'
                });

            // Count
            field
                .append("text")
                .attr('class', 'arc-goal-completed d3-text')
                .attr("transform", "translate(0,0)")
                .style({
                    'font-size': 23,
                    'font-weight': 500,
                    'text-anchor': 'middle'
                });


            //
            // Animate elements
            //

            // Add transition
            d3.transition().duration(2500).each(update);


            // Animation
            function update() {
                field = field
                    .each(function (d) {
                        this._value = d.percentage;
                    })
                    .data(dataset)
                    .each(function (d) {
                        d.previousValue = this._value;
                    });

                // Foreground arc
                field
                    .select(".arc-foreground")
                    .transition()
                    .duration(600)
                    .ease("easeInOut")
                    .attrTween("d", arcTween);
                    
                // Update count text
                field
                    .select(".arc-goal-completed")
                    .text(function (d) {
                        return Math.round(d.percentage /100 * goal);
                    });

                // Animate count text
                svg.select('.arc-goal-completed')
                    .transition()
                    .duration(600)
                    .tween("text", function(d) {
                        var i = d3.interpolate(this.textContent, d.percentage);
                        return function(t) {
                            this.textContent = Math.floor(d.percentage/100 * goal);
                        };
                    });

                // Update every 4 seconds (for demo)
                setTimeout(update, 4000);
            }

            // Arc animation
            function arcTween(d) {
                var i = d3.interpolateNumber(d.previousValue, d.percentage);
                return function (t) {
                    d.percentage = i(t);
                    return arc(d);
                };
            }
        }
    };

    // Rounded progress - multiple arcs
    var _roundedProgressMultiple = function(element, size) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Add random data
            var data = [
                    {index: 0, name: 'Memory', percentage: 0},
                    {index: 1, name: 'CPU', percentage: 0},
                    {index: 2, name: 'Sessions', percentage: 0}
                ];

            // Main variables
            var d3Container = d3.select(element),
                padding = 2,
                strokeWidth = 8,
                width = size,
                height = size,
                τ = 2 * Math.PI;

            // Colors
            var colors = ['#78909C', '#F06292', '#4DB6AC'];


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg");
            
            // Add SVG group
            var svg = container
                .attr("width", width)
                .attr("height", height)
                .append("g")
                    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");


            // Construct chart layout
            // ------------------------------

            // Foreground arc
            var arc = d3.svg.arc()
                .startAngle(0)
                .endAngle(function (d) {
                    return d.percentage / 100 * τ;
                })
                .innerRadius(function (d) {
                    return (size / 2) - d.index * (strokeWidth + padding);
                })
                .outerRadius(function (d) {
                    return ((size / 2) - d.index * (strokeWidth + padding)) - strokeWidth;
                })
                .cornerRadius(20);

            // Background arc
            var background = d3.svg.arc()
                .startAngle(0)
                .endAngle(τ)
                .innerRadius(function (d) {
                    return (size / 2) - d.index * (strokeWidth + padding);
                })
                .outerRadius(function (d) {
                    return ((size / 2) - d.index * (strokeWidth + padding)) - strokeWidth;
                });


            // Append chart elements
            // ------------------------------

            //
            // Group arc elements
            //

            // Group
            var field = svg.selectAll("g")
                .data(data)
                .enter().append("g");

            // Foreground arcs
            field
                .append("path")
                .attr("class", "arc-foreground")
                .style("fill", function (d, i) {
                    return colors[i];
                });

            // Background arcs
            field
                .append("path")
                .style("fill", function (d, i) {
                    return colors[i];
                })
                .style("opacity", 0.1)
                .attr("d", background);


            //
            // Add legend
            //

            // Append list
            var legend = d3.select(element)
                .append('ul')
                .attr('class', 'chart-widget-legend d3-text')
                .selectAll('li')
                .data(data)
                .enter()
                .append('li')
                .attr('data-slice', function(d, i) {
                    return i;
                })
                .attr('style', function(d, i) {
                    return 'border-bottom: solid 2px ' + colors[i];
                })
                .text(function(d, i) {
                    return d.name;
                });


            //
            // Animate elements
            //

            // Add transition
            d3.transition().each(update);

            // Animation
            function update() {
                field = field
                    .each(function (d) {
                        this._value = d.percentage;
                    })
                    .data(data)
                    .each(function (d) {
                        d.previousValue = this._value;
                        d.percentage = Math.round(Math.random() * 100) + 1;
                    });

                // Foreground arcs
                field
                    .select("path.arc-foreground")
                    .transition()
                    .duration(750)
                    .ease("easeInOut")
                    .attrTween("d", arcTween);
                    
                // Update every 4 seconds
                setTimeout(update, 4000);
            }

            // Arc animation
            function arcTween(d) {
                var i = d3.interpolateNumber(d.previousValue, d.percentage);
                return function (t) {
                    d.percentage = i(t);
                    return arc(d);
                };
            }
        }
    };

    // Pie with progress bar
    var _pieWithProgress = function(element, size) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Demo dataset
            var dataset = [
                    { name: 'New', count: 639 },
                    { name: 'Pending', count: 255 },
                    { name: 'Shipped', count: 215 }
                ];

            // Main variables
            var d3Container = d3.select(element),
                total = 0,
                width = size,
                height = size,
                progressSpacing = 6,
                progressSize = (progressSpacing + 2),
                arcSize = 20,
                outerRadius = (width / 2),
                innerRadius = (outerRadius - arcSize);

            // Colors
            var color = d3.scale.ordinal()
                .range(['#EF5350', '#29b6f6', '#66BB6A']);


            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append("svg");
            
            // Add SVG group
            var svg = container
                .attr("width", width)
                .attr("height", height)
                .append("g")
                    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");


            // Construct chart layout
            // ------------------------------

            // Add dataset
            dataset.forEach(function(d){
                total+= d.count;
            });

            // Pie layout
            var pie = d3.layout.pie()
                .value(function(d){ return d.count; })
                .sort(null);

            // Inner arc
            var arc = d3.svg.arc()
                .innerRadius(innerRadius)
                .outerRadius(outerRadius);

            // Line arc
            var arcLine = d3.svg.arc()
                .innerRadius(innerRadius - progressSize)
                .outerRadius(innerRadius - progressSpacing)
                .startAngle(0);


            // Append chart elements
            // ------------------------------

            //
            // Animations
            //
            var arcTween = function(transition, newAngle) {
                transition.attrTween("d", function (d) {
                    var interpolate = d3.interpolate(d.endAngle, newAngle);
                    var interpolateCount = d3.interpolate(0, dataset[0].count);
                    return function (t) {
                        d.endAngle = interpolate(t);
                        middleCount.text(d3.format(",d")(Math.floor(interpolateCount(t))));
                        return arcLine(d);
                    };
                });
            };


            //
            // Donut paths
            //

            // Donut
            var path = svg.selectAll('path')
                .data(pie(dataset))
                .enter()
                .append('path')
                .attr('class', 'd3-slice-border')
                .attr('d', arc)
                .attr('fill', function(d, i) {
                    return color(d.data.name);
                })
                .style({
                    'cursor': 'pointer'
                });

            // Animate donut
            path
                .transition()
                .delay(function(d, i) { return i; })
                .duration(600)
                .attrTween("d", function(d) {
                    var interpolate = d3.interpolate(d.startAngle, d.endAngle);
                    return function(t) {
                        d.endAngle = interpolate(t);
                        return arc(d);  
                    }; 
                });


            //
            // Line path 
            //

            // Line
            var pathLine = svg.append('path')
                .datum({endAngle: 0})
                .attr('d', arcLine)
                .style({
                    fill: color('New')
                });

            // Line animation
            pathLine.transition()
                .duration(600)
                .delay(300)
                .call(arcTween, (2 * Math.PI) * (dataset[0].count / total));


            //
            // Add count text
            //

            var middleCount = svg.append('text')
                .datum(0)
                .attr({
                    'class': 'd3-text', 
                    'dy': 6
                })
                .style({
                    'font-size': '21px',
                    'font-weight': 500,
                    'text-anchor': 'middle'
                })
                .text(function(d){
                    return d;
                });            


            //
            // Add interactions
            //

            // Mouse
            path
                .on('mouseover', function(d, i) {
                    $(element + ' [data-slice]').css({
                        'opacity': 0.3,
                        'transition': 'all ease-in-out 0.15s'
                    });
                    $(element + ' [data-slice=' + i + ']').css({'opacity': 1});
                })
                .on('mouseout', function(d, i) {
                    $(element + ' [data-slice]').css('opacity', 1);
                });


            //
            // Add legend
            //

            // Append list
            var legend = d3.select(element)
                .append('ul')
                .attr('class', 'chart-widget-legend')
                .selectAll('li')
                .data(pie(dataset))
                .enter()
                .append('li')
                .attr('data-slice', function(d, i) {
                    return i;
                })
                .attr('style', function(d, i) {
                    return 'border-bottom: solid 2px ' + color(d.data.name);
                })
                .text(function(d, i) {
                    return d.data.name + ': ';
                });

            // Append legend text
            legend.append('span')
                .text(function(d, i) {
                    return d.data.count;
                });
        }
    };

    // Segmented gauge
    var _segmentedGauge = function(element, size, min, max, sliceQty) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Main variables
            var d3Container = d3.select(element),
                width = size,
                height = (size / 2) + 20,
                radius = (size / 2),
                ringInset = 15,
                ringWidth = 20,

                pointerWidth = 10,
                pointerTailLength = 5,
                pointerHeadLengthPercent = 0.75,
                
                minValue = min,
                maxValue = max,
                
                minAngle = -90,
                maxAngle = 90,
                
                slices = sliceQty,
                range = maxAngle - minAngle,
                pointerHeadLength = Math.round(radius * pointerHeadLengthPercent);

            // Colors
            var colors = d3.scale.linear()
                .domain([0, slices - 1])
                .interpolate(d3.interpolateHsl)
                .range(['#66BB6A', '#EF5350']);


            // Create chart
            // ------------------------------

            // Add SVG element
            var container = d3Container.append('svg');

            // Add SVG group
            var svg = container
                .attr('width', width)
                .attr('height', height);


            // Construct chart layout
            // ------------------------------
            
            // Donut  
            var arc = d3.svg.arc()
                .innerRadius(radius - ringWidth - ringInset)
                .outerRadius(radius - ringInset)
                .startAngle(function(d, i) {
                    var ratio = d * i;
                    return deg2rad(minAngle + (ratio * range));
                })
                .endAngle(function(d, i) {
                    var ratio = d * (i + 1);
                    return deg2rad(minAngle + (ratio * range));
                });

            // Linear scale that maps domain values to a percent from 0..1
            var scale = d3.scale.linear()
                .range([0, 1])
                .domain([minValue, maxValue]);
                
            // Ticks
            var ticks = scale.ticks(slices);
            var tickData = d3.range(slices)
                .map(function() {
                    return 1 / slices;
                });

            // Calculate angles
            function deg2rad(deg) {
                return deg * Math.PI / 180;
            }
                
            // Calculate rotation angle
            function newAngle(d) {
                var ratio = scale(d);
                var newAngle = minAngle + (ratio * range);
                return newAngle;
            }


            // Append chart elements
            // ------------------------------

            //
            // Append arc
            //

            // Wrap paths in separate group
            var arcs = svg.append('g')
                .attr('class', 'd3-slice-border')
                .attr('transform', "translate(" + radius + "," + radius + ")");

            // Add paths
            arcs.selectAll('path')
                .data(tickData)
                .enter()
                .append('path')
                .attr('fill', function(d, i) {
                    return colors(i);
                })
                .attr('d', arc);


            //
            // Text labels
            //

            // Wrap text in separate group
            var arcLabels = svg.append('g')
                .attr('transform', "translate(" + radius + "," + radius + ")");

            // Add text
            arcLabels.selectAll('text')
                .data(ticks)
                .enter()
                .append('text')
                .attr('class', 'd3-text opacity-50')
                .attr('transform', function(d) {
                    var ratio = scale(d);
                    var newAngle = minAngle + (ratio * range);
                    return 'rotate(' + newAngle + ') translate(0,' + (10 - radius) + ')';
                })
                .style({
                    'text-anchor': 'middle',
                    'font-size': 11
                })
                .text(function(d) { return d + "%"; });


            //
            // Pointer
            //

            // Line data
            var lineData = [
                [pointerWidth / 2, 0], 
                [0, -pointerHeadLength],
                [-(pointerWidth / 2), 0],
                [0, pointerTailLength],
                [pointerWidth / 2, 0]
            ];

            // Create line
            var pointerLine = d3.svg.line()
                .interpolate('monotone');

            // Wrap all lines in separate group
            var pointerGroup = svg
                .append('g')
                .data([lineData])
                .attr('transform', "translate(" + radius + "," + radius + ")");

            // Paths
            pointer = pointerGroup
                .append('path')
                .attr('d', pointerLine)
                .attr('transform', 'rotate(' + minAngle + ')');


            // Random update
            // ------------------------------

            // Update values
            function update() {
                var ratio = scale(Math.random() * max);
                var newAngle = minAngle + (ratio * range);
                pointer.transition()
                    .duration(2500)
                    .ease('elastic')
                    .attr('transform', 'rotate(' + newAngle + ')');
            }
            update();

            // Update values every 5 seconds
            setInterval(function() {
                update();
            }, 5000);
        }
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            // _areaChartWidget("#chart_area_basic", 50, '#5C6BC0');
            // _areaChartWidget("#chart_area_color", 50, 'rgba(255,255,255,0.75)');

            // _barChartWidget("#chart_bar_basic", 24, 50, true, "elastic", 1200, 50, "#EF5350", "members");
            // _barChartWidget("#chart_bar_color", 24, 50, true, "elastic", 1200, 50, "rgba(255,255,255,0.75)", "members");

            // _lineChartWidget('#line_chart_simple', 50, '#2196F3', 'rgba(33,150,243,0.5)', '#2196F3', '#2196F3');
            // _lineChartWidget('#line_chart_color', 50, '#fff', 'rgba(255,255,255,0.5)', '#fff', '#29B6F6');

            // _sparklinesWidget("#sparklines_basic", "area", 30, 50, "basis", 750, 2000, "#66BB6A");
            // _sparklinesWidget("#sparklines_color", "area", 30, 50, "basis", 750, 2000, "rgba(255,255,255,0.75)");

            // _progressIcon('#progress_icon_one', 42, 2.5, "#4cb6ac", 0.68, "icon-heart6");
            // _progressIcon('#progress_icon_two', 42, 2.5, "#28b6f6", 0.82, "icon-trophy3");
            // _progressIcon('#progress_icon_three', 42, 2.5, "#fff", 0.73, "icon-bag");
            // _progressIcon('#progress_icon_four', 42, 2.5, "#fff", 0.49, "icon-truck");

            _progressPercentage('#progress_percentage_one', 46, 3, "#ec3f7a", p1);
            _progressPercentage('#progress_percentage_two', 46, 3, "#66bb6a", 1.00);
            _progressPercentage('#progress_percentage_three', 46, 3, "#fff", 0.69);
            _progressPercentage('#progress_percentage_four', 46, 3, "#fff", 0.43);

            _animatedPie("#pie_basic", 120);
            _animatedPieWithLegend("#pie_basic_legend", 120);
            _pieArcWithLegend("#pie_arc_legend", 170);
            _animatedDonut("#donut_basic_stats", 120);
            _animatedDonutWithLegend("#donut_basic_legend", 120);
            _donutWithDetails("#donut_basic_details", 146);
            _progressArcSingle("#arc_single", 78);
            _progressArcMulti("#arc_multi", 78, 700);
            _roundedProgressSingle("#rounded_progress_single", 150, 700, '#EC407A');
            _roundedProgressMultiple("#rounded_progress_multiple", 140);
            _pieWithProgress("#pie_progress_bar", 146);
            _segmentedGauge("#segmented_gauge", 200, 0, 100, 5);
        }
    }
}();


var EchartsGaugeCustomDark = function() {
    var _gaugeCustomDarkExample = function() {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        // Define element
        var gauge_custom_element = document.getElementById('gauge_custom');

        //
        // Charts configuration
        //
        if (gauge_custom_element) {
            var gauge_custom = echarts.init(gauge_custom_element);

            var gauge_custom_options = {
                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Add title
                title: {
                    text: 'Overall Machine Efficiency Rate',
                    subtext: 'Fixed value: 95%',
                    left: 'center',
                    textStyle: {
                        fontSize: 20,
                        fontWeight: 500,
                        color: '#fff'
                    }
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    backgroundColor: 'rgba(255,255,255,0.9)',
                    padding: [10, 15],
                    textStyle: {
                        color: '#222',
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    },
                    formatter: '{a} <br/>{b} : {c}%'
                },

                // Add series
                series: [
                    {
                        name: 'Memory usage',
                        type: 'gauge',
                        center: ['50%', '57.5%'],
                        radius: '80%',
                        startAngle: 150,
                        endAngle: -150,
                        axisLine: {
                            lineStyle: {
                                color: [
                                    [0.2, '#ff4500'], 
                                    [0.4, 'skyblue'], 
                                    [0.8, 'orange'], 
                                    [1, 'lightgreen']
                                ],
                                width: 30
                            }
                        },
                        axisTick: {
                            splitNumber: 5,
                            length: 5,
                            lineStyle: {
                                color: '#fff'
                            }
                        },
                        axisLabel: {
                            formatter: function(v) {
                                switch (v + '') {
                                    case '90': return 'High';
                                    case '60': return 'Normal';
                                    case '30': return 'Low';
                                    case '10': return 'Critical';
                                    default: return '';
                                }
                            }
                        },
                        splitLine: {
                            length: 35,
                            lineStyle: {
                                color: '#fff'
                            }
                        },
                        pointer: {
                            width: 5
                        },
                        title: {
                            offsetCenter: ['-81%', -20],
                            textStyle: {
                                fontSize: 13,
                                color: '#fff'
                            }
                        },
                        detail: {
                            offsetCenter: ['-80%', 10],
                            formatter: '{value}%',
                            textStyle: {
                                fontSize: 25,
                                fontWeight: 500
                            }
                        },
                        data: [{
                            value: totalAverage.toFixed(2),
                            name: 'Average Uptime'
                        }]
                    }
                ]
            };

            // Set chart option
            gauge_custom.setOption(gauge_custom_options);
        }

        // Resize chart on container resize
        var triggerChartResize = function() {
            gauge_custom_element && gauge_custom.resize();
        };

        // On sidebar toggle
        var sidebarToggle = document.querySelector('.sidebar-control');
        sidebarToggle && sidebarToggle.addEventListener('click', triggerChartResize);

        // On window resize
        var resizeCharts;
        window.addEventListener('resize', function() {
            clearTimeout(resizeCharts);
            resizeCharts = setTimeout(function () {
                triggerChartResize();
            }, 200);
        });
    };

    //
    // Return module
    //
    return {
        init: function() {
            _gaugeCustomDarkExample();
        }
    }
}();

// Uptime and Downtime Machine Trend (per day)
var UptimeDowntimeTrend = (function() {
	var _renderChart = function(dates, uptime, downtime, chartId, machinedesc) {
		if (typeof echarts === 'undefined') {
			console.warn('ECharts not loaded.');
			return;
		}
		const chartEl = document.getElementById(chartId);
		if (!chartEl) return;

		const chart = echarts.init(chartEl);
		chart.setOption({
			color: ["#AED581", "#E57373"],
			textStyle: { fontFamily: 'Roboto, Arial, sans-serif', fontSize: 13 },
			grid: { left: 10, right: 40, top: 70, bottom: 60, containLabel: true },
			title: {
				text: machinedesc,
				left: 'center',
				top: 10,
				textStyle: {
					color: '#fff',
					fontSize: 16,
					fontWeight: 'bold'
				}
			},
			legend: {
				data: ['Uptime', 'Downtime'],
				textStyle: { color: '#fff' },
				itemHeight: 8,
				itemGap: 20,
				top: 40
			},
			tooltip: {
				trigger: 'axis',
				backgroundColor: 'rgba(255,255,255,0.9)',
				padding: [10, 15],
				textStyle: { color: '#222', fontSize: 13 }
			},
			xAxis: {
				type: 'category',
				boundaryGap: false,
				axisLabel: { color: '#fff' },
				data: dates
			},
			yAxis: {
				type: 'value',
				axisLabel: { color: '#fff' }
			},
			dataZoom: [
				{ type: 'inside', start: 0, end: 100 },
				{
					show: true,
					type: 'slider',
					start: 30,
					end: 70,
					height: 40,
					bottom: 0,
					textStyle: { color: '#fff' }
				}
			],
			series: [
				{ name: 'Uptime', type: 'line', smooth: true, data: uptime },
				{ name: 'Downtime', type: 'line', smooth: true, data: downtime }
			]
		});
	};
	return { init: _renderChart };
})();
