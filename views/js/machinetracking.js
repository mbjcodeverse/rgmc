if (!$.fn.DataTable.isDataTable('.machinetrackingListTable')) {
    var slst = $('.machinetrackingListTable').DataTable({
        deferRender: true,
        processing: true,
        autoWidth: true,
        scrollY: 360,
        pageLength: 25,
        lengthMenu: [[25, 50], [25, 50]],
        dom: '<"datatable-header"><"extra-row"> <"datatable-scroll"t><"datatable-footer"fp>',
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

$(function() {
    flatpickr("#txt-inctime", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K", // 12-hour format with AM/PM
        time_24hr: false,
        onClose: function() {
            timeDuration(); // recalculate duration after selecting time
        }
    });

    flatpickr("#txt-endtime", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K", // 12-hour format with AM/PM
        time_24hr: false,
        onClose: function() {
            timeDuration();
        }
    });    

    // Check the trans_type value
    if ($("#trans_type").val() === "New") {
        // Disable the "Operational" option in the #sel-curstatus select tag
        $("#sel-curstatus option[value='Operational']").prop("disabled", true);
    }

    clearForm();

    // $("#date-datereported, #txt-inctime, #date-datecompleted, #txt-endtime").on("change", timeDuration);

    // Trigger timeDuration when dates change
    $("#date-datereported, #date-datecompleted").on("change", timeDuration);

    // Initialize field
    $("#num-duration").val("0.00 days");

    $("#btn-new").click(function(){
        swal.fire({
            title: 'Do you want to create new machine diagnosis details?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Save it!',
            cancelButtonText: 'Cancel!',
            confirmButtonClass: 'btn btn-outline-success',
            cancelButtonClass: 'btn btn-outline-danger',
            allowOutsideClick: false,
            buttonsStyling: false
        }).then(function(result) {
            if(result.value) {
                clearForm();
            }
        });
    }); 

    // Save Tracking
    $(".machine-tracking-form").submit(function (e) {
        e.preventDefault();
        swal.fire({
            title: 'Do you want to save new machine diagnosis details?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Save it!',
            cancelButtonText: 'Cancel!',
            confirmButtonClass: 'btn btn-outline-success',
            cancelButtonClass: 'btn btn-outline-danger',
            allowOutsideClick: false,
            buttonsStyling: false
        }).then(function(result) {
            if(result.value) {
                let trans_type = $("#trans_type").val();
                let machineid = $("#sel-machineid").val();

                let date_reported = $("#date-datereported").val().split("/");
                date_reported = date_reported[2] + "-" + date_reported[0] + "-" + date_reported[1];

                let curstatus = $("#sel-curstatus").val();
                let inccode = $("#txt-inccode").val();
                let reporter = $("#sel-reporter").val();
                let shift = $("#sel-shift").val();
                let inctime = $("#txt-inctime").val();
                let failuretype = $("#sel-failuretype").val();
                let controlnum = $("#txt-controlnum").val();
                // let severity = $("#sel-severity").val();
                let incidentdetails = $("#txt-incidentdetails").val();
                let compreporter = $("#sel-compreporter").val();

                let date_completed = $("#date-datecompleted").val().split("/");
                date_completed = date_completed[2] + "-" + date_completed[0] + "-" + date_completed[1];

                let endtime = $("#txt-endtime").val();
                let daysduration = $("#num-daysduration").val();
                let timeduration = $("#num-timeduration").val();
                let actiontaken = $("#txt-actiontaken").val();

                var diagnosis = new FormData();
                diagnosis.append("trans_type", trans_type);
                diagnosis.append("machineid", machineid);
                diagnosis.append("date_reported", date_reported);
                diagnosis.append("curstatus", curstatus);
                diagnosis.append("inccode", inccode);
                diagnosis.append("reporter", reporter);
                diagnosis.append("shift", shift);
                diagnosis.append("inctime", inctime);
                diagnosis.append("failuretype", failuretype);
                diagnosis.append("controlnum", controlnum);
                // diagnosis.append("severity", severity);
                diagnosis.append("incidentdetails", incidentdetails);
                diagnosis.append("compreporter", compreporter);
                diagnosis.append("date_completed", date_completed);
                diagnosis.append("endtime", endtime);
                diagnosis.append("daysduration", daysduration);
                diagnosis.append("timeduration", timeduration);
                diagnosis.append("actiontaken", actiontaken);

                $.ajax({
                    url:"ajax/machine_diagnosis_save_record.ajax.php",
                    method: "POST",
                    data: diagnosis,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"text",
                    success:function(answer){
                    },
                    error: function () {
                        alert("Oops. Something went wrong!");
                    },
                    complete: function () {
                        swal.fire({
                            title: 'Machine diagnostic details successfully saved!',
                            type: 'success',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        clearForm();
                    }
                });
            }
        });        
    });

    function validateNumber(input) {
        // Ensures only numeric input is allowed and removes any non-numeric characters
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function clearForm(){
        $("#sel-curstatus option[value='Operational']").prop("disabled", true);
        $('#sel-curstatus option[value="Under Repair"]').prop('disabled', false);
        $('#sel-curstatus option[value="Under Maintenance"]').prop('disabled', false);
        $('#sel-curstatus option[value="Standby"]').prop('disabled', false);

        $("#sel-machineid").val('').trigger('change');
        $("#date-datereported").val("");
        $("#sel-curstatus").val('Under Repair').trigger('change');
        $("#txt-inccode").val("");
        $("#sel-reporter").val('').trigger('change');
        $("#sel-shift").val('').trigger('change');
        $("#txt-inctime").val("");
        $("#sel-failuretype").val('').trigger('change');
        // $("#sel-severity").val('').trigger('change');
        $("#txt-controlnum").val("");
        $("#txt-incidentdetails").val("");
        $("#sel-compreporter").val('').trigger('change');
        $("#date-datecompleted").val("");
        $("#txt-endtime").val("");
        $("#num-daysduration").val("0.00");
        $("#num-timeduration").val("0.00");
        $("#txt-actiontaken").val("");
    }

    function timeDuration() {
        let startDate = $("#date-datereported").val();
        let startTime = $("#txt-inctime").val();
        let endDate = $("#date-datecompleted").val();
        let endTime = $("#txt-endtime").val();

        if (startDate && startTime && endDate && endTime) {
            // Parse input dates
            let start = new Date(startDate + " " + startTime);
            let end = new Date(endDate + " " + endTime);

            // Function to adjust time while keeping same date
            function adjustTime(dateObj) {
                let adjusted = new Date(dateObj);
                adjusted.setHours(dateObj.getHours() - 8);

                // Keep same calendar date (don’t roll to previous day)
                if (adjusted.getDate() !== dateObj.getDate()) {
                    adjusted.setDate(dateObj.getDate());
                }
                return adjusted;
            }

            // Adjust both start and end times
            let adjustedStart = adjustTime(start);
            let adjustedEnd = adjustTime(end);

            // Compute duration (milliseconds)
            let diffMs = adjustedEnd - adjustedStart;
            if (diffMs < 0) {
                // If end is earlier than start, assume next day
                diffMs += 24 * 60 * 60 * 1000;
            }

            // Convert to hours
            let totalHours = diffMs / (1000 * 60 * 60);
            let diffHours = Math.floor(totalHours);
            let diffMinutes = Math.floor((totalHours - diffHours) * 60);

            // Format
            let startFormatted = adjustedStart.toLocaleString();
            let endFormatted = adjustedEnd.toLocaleString();

            // alert(
            //     "Adjusted Start Time: " + startFormatted +
            //     "\nAdjusted End Time: " + endFormatted +
            //     "\n\nTotal Duration: " + diffHours + " hour(s) and " + diffMinutes + " minute(s)" +
            //     "\nEquivalent to: " + totalHours.toFixed(2) + " hours"
            // );

            $("#num-timeduration").val(totalHours.toFixed(2));
        } else {
            //alert("Please fill in all date and time fields.");
        }
    }
    
    $('#lst_date_range').daterangepicker({
        ranges:{
          'All'           : [moment('2025-12-01'), moment()],
          'Today'         : [moment(),moment()],
          'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
          'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
          'This Month'    : [moment().startOf('month'), moment().endOf('month')],
          'This Year'     : [moment().startOf('year'), moment().endOf('year')]
        }
    });

    $('#modal-search-machinetracking').on('shown.bs.modal', function () {
        slst.search('').draw();
        slst.table().container().querySelector('.dataTables_filter input').focus(); 
        $("#lst-machineid").val('').trigger('change');
        $("#lst-datemode").val('Reported').trigger('change');
        $("#lst-curstatus").val('').trigger('change');

        $('#lst_date_range').data('daterangepicker').setStartDate(moment('2025-12-01'));
        $('#lst_date_range').data('daterangepicker').setEndDate(moment());
    });

    $("#lbl-lst-date-range").click(function(){
        $('#lst_date_range').data('daterangepicker').setStartDate(moment('2025-12-01'));
        $('#lst_date_range').data('daterangepicker').setEndDate(moment());
        
        slst.search('').draw();
        slst.table().container().querySelector('.dataTables_filter input').focus(); 
    });

    $("#lbl-lst-machineid").click(function(){
        $("#lst-machineid").val('').trigger('change');
    });

    $("#lbl-lst-curstatus").click(function(){
        $("#lst-curstatus").val('').trigger('change');
    });

    $("#lbl-lst-failuretype").click(function(){
        $("#sel-failuretype").val('').trigger('change');
    });

    $('#lst-machineid, #lst-datemode, #lst_date_range, #lst-curstatus').on("change", function() {
        let machineid = $("#lst-machineid").val();
        if (machineid == null){
            machineid = '';
        }
        let datemode = $("#lst-datemode").val();
        
        var date_range = $("#lst_date_range").val();
        if (date_range != ''){
            var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
            var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
        } else {
            var start_date = '';
            var end_date = '';
        }
        let curstatus = $("#lst-curstatus").val();
        
        var data = new FormData();
        data.append("machineid", machineid);
        data.append("datemode", datemode);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("curstatus", curstatus);
        
        $.ajax({
            url: "ajax/machine_tracking_list.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(answer) {
                $(".machinetrackingListTable").DataTable().clear();   
                for (var i = 0; i < answer.length; i++) {
                    var mt = answer[i];
    
                    var datereported = mt.datereported;
                    var inctime = mt.inctime;
                    var inccode = mt.inccode;
                    var controlnum = mt.controlnum;
                    var machinedesc = mt.machinedesc;
                    var curstatus = mt.curstatus;
                    var datecompleted = mt.datecompleted;
                    // var sale_date = si.sdate;
                    // var saledate = sale_date.split("-");
                    // var sdate = saledate[1] + "/" + saledate[2] + "/" + saledate[0];
    
                    var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnDiagnosis' inccode='" + inccode + "'><i class='icon-pencil3'></i></button></td>";
                    slst.row.add([datereported, inctime, controlnum, machinedesc, curstatus, datecompleted, button])
                }
                slst.draw();
            },
            beforeSend: function() {},
            complete: function() {
                $(".machinetrackingListTable td").css({
                    "padding-top": "5px",
                    "padding-bottom": "5px"
                });
            }
        });
    });    

    // Ensure that padding is applied whenever DataTable redraws (e.g., page switch or filtering)
    $(".machinetrackingListTable").on("draw.dt", function () {
        $(".machinetrackingListTable td").css({
            "padding-top": "5px",
            "padding-bottom": "5px"
        });
    });

    $(".machinetrackingListTable tbody").on("click", "button.btnDiagnosis", function(){
        $("#modal-search-machinetracking").modal("hide");
        $("#trans_type").val("Update");
        var inccode = $(this).attr("inccode");
        var data = new FormData();
        data.append("inccode", inccode);
        $.ajax({
            url:"ajax/machine_tracking_get_record.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $('#trans_type').val('Update');
                $("#sel-machineid").val(answer["machineid"]).trigger('change');

                let reported_date = answer["datereported"];
                let reporteddate = reported_date.split("-");
                reporteddate = reporteddate[1] + "/" + reporteddate[2] + "/" + reporteddate[0];
                $("#date-datereported").val(reporteddate);

                $("#sel-curstatus").val(answer["curstatus"]).trigger('change');
                $("#txt-inccode").val(answer["inccode"]);
                $("#sel-reporter").val(answer["reporter"]).trigger('change');
                $("#sel-shift").val(answer["shift"]).trigger('change');
                $("#txt-inctime").val(answer["inctime"]);
                $("#sel-failuretype").val(answer["failuretype"]).trigger('change');
                $("#txt-controlnum").val(answer["controlnum"]);
                // $("#sel-severity").val(answer["severity"]).trigger('change');
                $("#txt-incidentdetails").val(answer["incidentdetails"]);
                $("#sel-compreporter").val(answer["compreporter"]).trigger('change');

                let completed_date = answer["datecompleted"];
                if (completed_date != '0000-00-00'){
                    var completeddate = completed_date.split("-");
                    completeddate = completeddate[1] + "/" + completeddate[2] + "/" + completeddate[0];
                }else{
                    var completeddate = '';
                }
                $("#date-datecompleted").val(completeddate);

                $("#txt-endtime").val(answer["endtime"]);
                $("#num-daysduration").val(numberWithCommas(answer["daysduration"]));
                $("#num-timeduration").val(numberWithCommas(answer["timeduration"]));
                $("#txt-actiontaken").val(answer["actiontaken"]);
            }
        })
    });     

    // Run check whenever either date changes
    $("#date-datereported, #date-datecompleted").on("change keyup blur", toggleCurStatusOptions);

    toggleCurStatusOptions();

    // Function to control enabling/disabling of Operational and Standby options
    function toggleCurStatusOptions() {
        let reported = $("#date-datereported").val().trim();
        let completed = $("#date-datecompleted").val().trim();

        if (reported !== "" && completed !== "") {
            // Enable Operational and Standby
            $('#sel-curstatus option[value="Operational"]').prop('disabled', false);
            $('#sel-curstatus option[value="Under Repair"]').prop('disabled', true);
            $('#sel-curstatus option[value="Under Maintenance"]').prop('disabled', true);
            $('#sel-curstatus option[value="Standby"]').prop('disabled', false);
            $("#sel-curstatus").val('Operational').trigger('change');
        } else {
            // Disable Operational and Standby
            $('#sel-curstatus option[value="Operational"]').prop('disabled', true);
            $('#sel-curstatus option[value="Under Repair"]').prop('disabled', false);
            $('#sel-curstatus option[value="Under Maintenance"]').prop('disabled', false);
            $('#sel-curstatus option[value="Standby"]').prop('disabled', false);
            $("#sel-curstatus").val('').trigger('change');
        }
    }

    let user_level = $("#user_level").val();
    let current_user = $("#tns-postedby").val();
    if (user_level == 'Operator'){
        $('#btn-operator').css('visibility', 'visible');
        $("#sel-reporter").val(current_user).trigger('change');
        $("#sel-reporter").prop('disabled', true);
        // $('#btn-save').show();
    }else{
        $('#completion_report').show();
        $('#corrective_action').show();
        $('#btn-save').show();
    }
});