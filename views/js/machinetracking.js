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

    var user_level = $("#user_level").val();        // Operator
    var current_user = $("#tns-postedby").val();    // Current User
    var prod_opr = $("#prod_opr").val();            // Full or Restricted

    clearForm();

    // Trigger timeDuration when dates change
    $("#date-datereported, #date-datecompleted").on("change", timeDuration);

    // Initialize field
    $("#num-duration").val("0.00 days");

    $("#btn-new").click(function(){
        swal.fire({
            title: 'Do you want to create new machine incident details?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Create!',
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

    $(".machine-tracking-form").submit(function (e) {
        e.preventDefault();

        let emptyFields = [];
        let isValid = true;
        let form = $(this);

        /* ===============================
        1️⃣ CLEAR PREVIOUS INLINE STYLES
        =============================== */
        form.find("[required]").each(function () {
            $(this).css({
                "border": "",
                "box-shadow": ""
            });
        });

        /* ===============================
        2️⃣ VALIDATE REQUIRED FIELDS
        =============================== */
        form.find("[required]:visible").each(function () {
            let val = $(this).val();
            let id = $(this).attr("id");
            let label = $("label[for='" + id + "']").text();

            if (!val || val.trim() === "") {
                isValid = false;
                emptyFields.push(label);

                // INLINE ERROR STYLE
                $(this).css({
                    "border": "1px solid #dc3545",
                    "box-shadow": "0 0 0 0.15rem rgba(220,53,69,.25)"
                });
            }
        });

        if (!isValid) {
            const spaces = "&nbsp;".repeat(20);
            swal.fire({
                // title: "Missing Required Fields",
                html:
                    `<div style="text-align:center;font-size:1.3em;">
                        Missing Required Fields: ${emptyFields.join(", ")}
                    </div>`,
                type: "warning",
                confirmButtonText: "Got it!",
                confirmButtonClass: 'btn btn-outline-danger',
                allowOutsideClick: false,
                buttonsStyling: false
            });

            form.find("[style*='dc3545']:first").focus();
            return false;
        }

        /* ===============================
        3️⃣ CONFIRM SAVE
        =============================== */
        swal.fire({
            title: 'Do you want to save new machine incident details?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Save it!',
            cancelButtonText: 'Cancel!',
            confirmButtonClass: 'btn btn-outline-success',
            cancelButtonClass: 'btn btn-outline-danger',
            allowOutsideClick: false,
            buttonsStyling: false
        }).then(function (result) {

            if (!result.value) return;

            /* ===============================
            4️⃣ COLLECT DATA (SAFE)
            =============================== */

            let date_reported = $("#date-datereported").val().split("/");
            date_reported = date_reported[2] + "-" + date_reported[0] + "-" + date_reported[1];
            // alert(date_reported);   
            
            let date_completed = $("#date-datecompleted").val().split("/");
            date_completed = date_completed[2] + "-" + date_completed[0] + "-" + date_completed[1];
            // alert(date_completed);

            let diagnosis = new FormData();
            diagnosis.append("trans_type", $("#trans_type").val());
            diagnosis.append("machineid", $("#sel-machineid").val());
            diagnosis.append("date_reported", date_reported);
            diagnosis.append("machstatus", $("#sel-machstatus").val());
            diagnosis.append("phase", $("#txt-phase").val());
            diagnosis.append("curstatus", $("#sel-curstatus").val());
            diagnosis.append("inccode", $("#txt-inccode").val());
            diagnosis.append("reporter", $("#sel-reporter").val());
            diagnosis.append("shift", $("#sel-shift").val());
            diagnosis.append("inctime", $("#txt-inctime").val());
            diagnosis.append("failuretype", $("#sel-failuretype").val());
            diagnosis.append("breakid", $("#sel-breakid").val());
            diagnosis.append("incidentdetails", $("#txt-incidentdetails").val());
            diagnosis.append("technician", $("#sel-technician").val());
            diagnosis.append("compreporter", $("#sel-compreporter").val());
            diagnosis.append("date_completed", date_completed);
            diagnosis.append("endtime", $("#txt-endtime").val());
            diagnosis.append("daysduration", $("#num-daysduration").val());
            diagnosis.append("timeduration", $("#num-timeduration").val());
            diagnosis.append("cause", $("#txt-cause").val());
            diagnosis.append("actiontaken", $("#txt-actiontaken").val());

            /* ===============================
            5️⃣ AJAX
            =============================== */
            $.ajax({
                url: "ajax/machine_diagnosis_save_record.ajax.php",
                method: "POST",
                data: diagnosis,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "text",

                success: function (answer) {
                    if ($("#txt-phase").val() === 'Allocated' && user_level !== 'Operator') {
                        let approver = $("#tns-postedby").val();
                        window.open(
                            "extensions/tcpdf/pdf/joborder.php?inccode=" + answer+"&approver="+approver,
                            "_blank"
                        );
                    }
                },

                error: function () {
                    swal.fire("Error", "Oops. Something went wrong!", "error");
                },

                complete: function () {
                    swal.fire({
                        title: 'Machine incident details successfully saved!',
                        type: 'success',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    clearForm();
                }
            });

        });
    });

    // Save Tracking
    // $(".machine-tracking-form").submit(function (e) {
    //     e.preventDefault();
    //     swal.fire({
    //         title: 'Do you want to save new machine incident details?',
    //         type: 'question',
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes, Save it!',
    //         cancelButtonText: 'Cancel!',
    //         confirmButtonClass: 'btn btn-outline-success',
    //         cancelButtonClass: 'btn btn-outline-danger',
    //         allowOutsideClick: false,
    //         buttonsStyling: false
    //     }).then(function(result) {
    //         if(result.value) {
    //             let trans_type = $("#trans_type").val();
    //             let machineid = $("#sel-machineid").val();

    //             let date_reported = $("#date-datereported").val().split("/");
    //             date_reported = date_reported[2] + "-" + date_reported[0] + "-" + date_reported[1];

    //             let machstatus = $("#sel-machstatus").val();
    //             let phase = $("#txt-phase").val();
    //             let curstatus = $("#sel-curstatus").val();
    //             let inccode = $("#txt-inccode").val();
    //             let reporter = $("#sel-reporter").val();
    //             let shift = $("#sel-shift").val();
    //             let inctime = $("#txt-inctime").val();
    //             let failuretype = $("#sel-failuretype").val();
    //             let breakid = $("#sel-breakid").val();
    //             // let controlnum = $("#txt-controlnum").val();
    //             // let severity = $("#sel-severity").val();
    //             let incidentdetails = $("#txt-incidentdetails").val();
    //             let technician = $("#sel-technician").val();
    //             let compreporter = $("#sel-compreporter").val();

    //             let date_completed = $("#date-datecompleted").val().split("/");
    //             date_completed = date_completed[2] + "-" + date_completed[0] + "-" + date_completed[1];

    //             let endtime = $("#txt-endtime").val();
    //             let daysduration = $("#num-daysduration").val();
    //             let timeduration = $("#num-timeduration").val();
    //             let cause = $("#txt-cause").val();
    //             let actiontaken = $("#txt-actiontaken").val();

    //             var diagnosis = new FormData();
    //             diagnosis.append("trans_type", trans_type);
    //             diagnosis.append("machineid", machineid);
    //             diagnosis.append("date_reported", date_reported);
    //             diagnosis.append("machstatus", machstatus);
    //             diagnosis.append("phase", phase);
    //             diagnosis.append("curstatus", curstatus);
    //             diagnosis.append("inccode", inccode);
    //             diagnosis.append("reporter", reporter);
    //             diagnosis.append("shift", shift);
    //             diagnosis.append("inctime", inctime);
    //             diagnosis.append("failuretype", failuretype);
    //             diagnosis.append("breakid", breakid);
    //             // diagnosis.append("controlnum", controlnum);
    //             // diagnosis.append("severity", severity);
    //             diagnosis.append("incidentdetails", incidentdetails);
    //             diagnosis.append("technician", technician);
    //             diagnosis.append("compreporter", compreporter);
    //             diagnosis.append("date_completed", date_completed);
    //             diagnosis.append("endtime", endtime);
    //             diagnosis.append("daysduration", daysduration);
    //             diagnosis.append("timeduration", timeduration);
    //             diagnosis.append("cause", cause);
    //             diagnosis.append("actiontaken", actiontaken);

    //             $.ajax({
    //                 url:"ajax/machine_diagnosis_save_record.ajax.php",
    //                 method: "POST",
    //                 data: diagnosis,
    //                 cache: false,
    //                 contentType: false,
    //                 processData: false,
    //                 dataType:"text",
    //                 success:function(answer){
    //                     if (phase == 'Allocated' && user_level != 'Operator'){
    //                         // $("#btn-joborder").click();
    //                         let inccode = answer;
    //                         alert(answer);
  	//                         window.open("extensions/tcpdf/pdf/joborder.php?inccode="+inccode, "_blank");
    //                     }
    //                 },
    //                 error: function () {
    //                     alert("Oops. Something went wrong!");
    //                 },
    //                 complete: function () {
    //                     swal.fire({
    //                         title: 'Machine incident details successfully saved!',
    //                         type: 'success',
    //                         allowOutsideClick: false,
    //                         showConfirmButton: false,
    //                         timer: 1500
    //                     });
    //                     clearForm();
    //                 }
    //             });
    //         }
    //     });        
    // });

    $("#btn-joborder").click(function(){
        swal.fire({
            title: 'Do you want to print job order?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Print!',
            cancelButtonText: 'Cancel!',
            confirmButtonClass: 'btn btn-outline-success',
            cancelButtonClass: 'btn btn-outline-danger',
            allowOutsideClick: false,
            buttonsStyling: false
        }).then(function(result) {
            if(result.value) {
                let approver = $("#tns-postedby").val();
                let inccode = $("#txt-inccode").val();
  	            window.open("extensions/tcpdf/pdf/joborder.php?inccode="+inccode+"&approver="+approver, "_blank");
            }
        });
    });

    function validateNumber(input) {
        // Ensures only numeric input is allowed and removes any non-numeric characters
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function clearForm(){
        $("#mt-id").val("");
        $("#trans_type").val("New");

        $("#sel-failuretype").prop('disabled', true);
        $("#sel-breakid").prop('disabled', true);

        $('.card-body')
            .find('input, select, textarea')
            .prop('disabled', true);
            
        $("#sel-machineid").prop('disabled', false);  
        $("#sel-curstatus").prop('disabled', false);  
        $("#txt-phase").prop('disabled', false);  
        $("#date-datereported").prop('disabled', false); 
        $("#txt-inctime").prop('disabled', false);    
        $("#txt-incidentdetails").prop('disabled', false);   

        setCurrentStatusOptions();
        $("#sel-machineid").val('').trigger('change');
        $("#date-datereported").val("");
        $("#sel-machstatus").val("");
        $("#sel-machstatus").prop('disabled', true);
        $("#txt-phase").val("Pending");
        $("#sel-curstatus").val("").trigger('change');
        $("#txt-inccode").val("");

        let prod_opr = $("#prod_opr").val();
        let user_level = $("#user_level").val();
        if (prod_opr == 'Full' || user_level != 'Operator'){
            $("#sel-reporter").val('').trigger('change');
        }    

        $("#sel-shift").val('').trigger('change');
        $("#txt-inctime").val("");
        $("#sel-failuretype").val('').trigger('change');
        // $("#txt-controlnum").val("");
        $("#txt-incidentdetails").val("");
        $("#sel-technician").val('').trigger('change');
        $("#sel-compreporter").val('').trigger('change');
        $("#date-datecompleted").val("");
        $("#txt-endtime").val("");
        $("#num-daysduration").val("0.00");
        $("#num-timeduration").val("0.00");
        $("#txt-cause").val("");
        $("#txt-actiontaken").val("");

        $('#btn-joborder').hide();
        $('#btn-save').show();
        $('#btn-cancel').hide();
        $("#btn-joborder").prop('disabled', true);
    }

    function setCurrentStatusOptions(){
        if (user_level == 'Operator'){
            let $curstatus = $('#sel-curstatus');
            $curstatus.empty();
            $curstatus.append('<option value=""></option>');
            $curstatus.append('<option value="Planned Downtime">Planned Downtime</option>');
            $curstatus.append('<option value="Unplanned Downtime">Unplanned Downtime</option>'); 
            $curstatus.append('<option value="Inspection">Inspection</option>');
            // $curstatus.append('<option value="Power Outage">Power Outage</option>');
        }
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
        $("#lst-phase").val('- Queued -').trigger('change');

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

    $("#lbl-lst-phase").click(function(){
        $("#lst-phase").val('- Queued -').trigger('change');
    });

    $("#lbl-lst-failuretype").click(function(){
        $("#sel-failuretype").val('').trigger('change');
    });

    $('#lst-machineid, #lst-datemode, #lst_date_range, #lst-phase').on("change", function() {
        if (prod_opr == 'Restricted' && user_level == 'Operator'){
            var postedby = current_user;
        }else{
            var postedby = '';
        }

        // alert(postedby);

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
        let phase = $("#lst-phase").val();
        
        var data = new FormData();
        data.append("machineid", machineid);
        data.append("datemode", datemode);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("phase", phase);
        data.append("postedby", postedby);
        
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
                    var phase = mt.phase;
                    var curstatus = mt.curstatus;
                    // var failuretype = mt.failuretype;
                    var datecompleted = mt.datecompleted;
                    var timeduration = mt.timeduration;

                    if (Number(timeduration) == 0.00){
                        timeduration = '';
                    }
                    // var sale_date = si.sdate;
                    // var saledate = sale_date.split("-");
                    // var sdate = saledate[1] + "/" + saledate[2] + "/" + saledate[0];
    
                    var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnDiagnosis' inccode='" + inccode + "'><i class='icon-pencil3'></i></button></td>";
                    slst.row.add([datereported, inctime, inccode, machinedesc, phase, curstatus, datecompleted, timeduration, button])
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

    let isDiagnosisLoad = false;
    $(".machinetrackingListTable tbody").on("click", "button.btnDiagnosis", function(){
        $('#btn-joborder').hide();
        $("#btn-joborder").prop('disabled', true);
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
                isDiagnosisLoad = true;
                $('#trans_type').val('Update');
                $('#mt-id').val(answer["id"]);
                $("#sel-machineid").val(answer["machineid"]).trigger('change');
                isDiagnosisLoad = false;

                let reported_date = answer["datereported"];
                let reporteddate = reported_date.split("-");
                reporteddate = reporteddate[1] + "/" + reporteddate[2] + "/" + reporteddate[0];
                $("#date-datereported").val(reporteddate);
                $("#sel-machstatus").val(answer["machstatus"]).trigger('change');
                $("#txt-phase").val(answer["phase"]);    

                $("#sel-curstatus").val(answer["curstatus"]).trigger('change');
                $("#txt-inccode").val(answer["inccode"]);
                $("#sel-reporter").val(answer["reporter"]).trigger('change');
                $("#sel-shift").val(answer["shift"]).trigger('change');
                $("#txt-inctime").val(answer["inctime"]);
                $("#sel-failuretype").val(answer["failuretype"]).trigger('change');
                $("#txt-controlnum").val(answer["controlnum"]);
                $("#txt-incidentdetails").val(answer["incidentdetails"]);
                $("#sel-technician").val(answer["technician"]).trigger('change');
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
                $("#txt-cause").val(answer["cause"]);

                // show Cancel button for Technical Head
                if (user_level != 'Operator' && answer["phase"] != 'Completed' && answer["phase"] != 'Cancelled'){
                    $('#btn-cancel').show();
                    $('#btn-save').show();
                }else{
                    $('#btn-cancel').hide();
                    $('#btn-save').hide();
                }

                setAssignedTechnician(answer["technician"]);

                setFailuretypeOptions(
                    answer["failuretype"],
                    answer["breakid"]
                );

                if (user_level == 'Operator' && answer["phase"] == 'Completed'){
                    $('.card-body')
                        .find('input, select, textarea')
                        .prop('disabled', true);   
                }else{
                    $('.card-body')
                        .find('input, select, textarea')
                        .prop('disabled', false);  
                        
                    $('#sel-shift').prop('disabled', true); 
                    $('#sel-reporter').prop('disabled', true);  

                    if (answer["curstatus"] != 'Inspection'){
                        $('#sel-machstatus').prop('disabled', true);
                    }else{
                        $('#sel-machstatus').prop('disabled', false);
                    } 
                }
            }
        })
    });     

    // Run check whenever either date changes
    $("#date-datereported, #date-datecompleted").on("change keyup blur", toggleCurStatusOptions);

    toggleCurStatusOptions();

    // Function to control enabling/disabling of Operational and Standby options
    function toggleCurStatusOptions() {
        // let reported = $("#date-datereported").val().trim();
        // let completed = $("#date-datecompleted").val().trim();

        // if (reported !== "" && completed !== "") {
        //     // Enable Operational and Standby
        //     $('#sel-curstatus option[value="Operational"]').prop('disabled', false);
        //     $('#sel-curstatus option[value="Under Repair"]').prop('disabled', true);
        //     $('#sel-curstatus option[value="Under Maintenance"]').prop('disabled', true);
        //     $('#sel-curstatus option[value="Standby"]').prop('disabled', false);
        //     $("#sel-curstatus").val('Operational').trigger('change');
        // } else {
        //     // Disable Operational and Standby
        //     $('#sel-curstatus option[value="Operational"]').prop('disabled', true);
        //     $('#sel-curstatus option[value="Under Repair"]').prop('disabled', false);
        //     $('#sel-curstatus option[value="Under Maintenance"]').prop('disabled', false);
        //     $('#sel-curstatus option[value="Standby"]').prop('disabled', false);
        //     $("#sel-curstatus").val('').trigger('change');
        // }
    }

    // let user_level = $("#user_level").val();
    // let current_user = $("#tns-postedby").val();
    if (user_level == 'Operator'){
        $('#btn-operator').show();    // Production button (use to go back to production entry)
        $("#sel-reporter").val(current_user).trigger('change');
        $("#sel-reporter").prop('disabled', true);
        $('#btn-save').show();
    }else{
        $('#completion_report').show();
        $('#corrective_action').show();
        $('#btn-save').show();
    }

    // ---- Handling Failure Type and Breakdown Details ----
    $('#sel-machineid').on('change', function () {
        if (isDiagnosisLoad) return;
        setFailuretypeOptions();
    });

    function setFailuretypeOptions(selectedFailureType = null, selectedBreakID = null) {
        let machineid = $("#sel-machineid").val();
        var data = new FormData();
        data.append("machineid", machineid);

        $.ajax({
            url: "ajax/machine_category_failuretype_list.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (answer) {
                let $failureType = $("#sel-failuretype");
                $failureType.prop('disabled', false)
                            .empty()
                            .append('<option value=""></option>');

                let $breakID = $("#sel-breakid");
                $breakID.prop('disabled', false)
                        .empty()
                        .append('<option value=""></option>');            

                let class_code = '';

                for (let i = 0; i < answer.length; i++) {
                    let ft = answer[i];
                    class_code = ft.classcode;

                    $failureType.append(
                        `<option value="${ft.failuretype}">${ft.failuretype}</option>`
                    );
                }

                $("#class_code").val(class_code);

                if (selectedFailureType) {
                    $failureType.val(selectedFailureType);
                    setBreakdownOptions(selectedBreakID);
                }
            }
        });
    }

    // function setFailuretypeOptions(){
    //     let machineid = $("#sel-machineid").val();
    //     var data = new FormData();
    //     data.append("machineid", machineid);
    //     $.ajax({
    //         url: "ajax/machine_category_failuretype_list.ajax.php",
    //         method: "POST",
    //         data: data,
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         dataType: "json",
    //         success: function(answer) {
    //             $("#sel-failuretype").prop('disabled', false);
    //             let $failureType = $('#sel-failuretype');
    //             $failureType.empty();
    //             $failureType.append('<option value=""></option>');
    //             for (var i = 0; i < answer.length; i++) {
    //                 var ft = answer[i];
    //                 var failure_type = ft.failuretype;
    //                 var class_code = ft.classcode;
    //                 $failureType.append('<option value="' + failure_type + '">' + failure_type + '</option>');
    //             }

    //             $("#class_code").val(class_code);

    //             $("#sel-breakid").prop('disabled', true);
    //             let $breakID = $('#sel-breakid');
    //             $breakID.empty();
    //             $breakID.append('<option value=""></option>');
    //         }
    //     });
    // }

    $('#sel-failuretype').on('change', function () {
        setBreakdownOptions();
    }); 
    
    function setBreakdownOptions(selectedBreakID = null) {
        let failuretype = $("#sel-failuretype").val();
        let class_code = $("#class_code").val();

        var data = new FormData();
        data.append("failuretype", failuretype);
        data.append("class_code", class_code);

        $.ajax({
            url: "ajax/machine_category_breakdown_list.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (answer) {
                let $breakID = $("#sel-breakid");
                $breakID.prop('disabled', false)
                        .empty()
                        .append('<option value=""></option>');

                for (let i = 0; i < answer.length; i++) {
                    let bd = answer[i];
                    $breakID.append(
                        `<option value="${bd.breakid}">${bd.details}</option>`
                    );
                }
            
                if (selectedBreakID) {
                    $breakID.val(selectedBreakID);
                }

                // User = Operator and Phase is Completed - disable Failuretype and Break ID
                if (user_level == 'Operator' && $('#txt-phase').val() == 'Completed'){
                    $('#sel-failuretype').prop('disabled', true);
                    $('#sel-breakid').prop('disabled', true);
                }
            }
        });
    }    

    // function setBreakdownOptions(){
    //     let failuretype = $("#sel-failuretype").val();
    //     let class_code = $("#class_code").val();
    //     var data = new FormData();
    //     data.append("failuretype", failuretype);
    //     data.append("class_code", class_code);
    //     $.ajax({
    //         url: "ajax/machine_category_breakdown_list.ajax.php",
    //         method: "POST",
    //         data: data,
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         dataType: "json",
    //         success: function(answer) {
    //             $("#sel-breakid").prop('disabled', false);
    //             let $breakID = $('#sel-breakid');
    //             $breakID.empty();
    //             $breakID.append('<option value=""></option>');
    //             for (var i = 0; i < answer.length; i++) {
    //                 var bd = answer[i];
    //                 var details = bd.details;
    //                 var breakid = bd.breakid;
    //                 $breakID.append('<option value="' + breakid + '">' + details + '</option>');
    //             }
    //         }
    //     });
    // }

    $('#txt-inctime').on('change blur', function () {
        let timeStr = $(this).val().trim();

        if (!timeStr) return;

        // Convert time to minutes since midnight
        let time = new Date("1970-01-01 " + timeStr);
        if (isNaN(time.getTime())) return;

        let minutes = time.getHours() * 60 + time.getMinutes();

        // Day shift: 08:00 AM (480) to 7:59 PM (1199)
        if (minutes >= 480 && minutes <= 1199) {
            $('#sel-shift').val('Day').trigger('change');
        } else {
            $('#sel-shift').val('Night').trigger('change');
        }
    });

    // $('#sel-curstatus').on('change', function () {
    //     $("#txt-machstatus").val("Offline");
    // }); 

    $('#sel-curstatus').on('change', function () {
        const status = $(this).val();
        const $machineStatus = $('#sel-machstatus');

        if (
            status === 'Unplanned Downtime' ||
            status === 'Planned Downtime'
            // status === 'Power Outage'
        ) {
            $("#sel-machstatus").val('Offline').trigger('change');
            $machineStatus.prop('disabled', true); // disable
        } else {
            $("#sel-machstatus").val('').trigger('change');
            $machineStatus.prop('disabled', false);
        }
    });  
    
    $('#date-datecompleted, #sel-compreporter').on('change', function () {
        if (
            $('#date-datecompleted').val().trim() !== '' &&
            $('#sel-compreporter').val().trim() !== ''
        ) {
            $('#txt-phase').val('Completed');
            $("#sel-machstatus").prop('disabled', false);
            $("#sel-machstatus").val('Operational').trigger('change');
        }
    });

    $('#sel-technician').on('change', function () {
        setAssignedTechnician();
        // if ($('#txt-phase').val().trim() !== 'Completed' && $('#sel-technician').val() !== '') {
        //     $('#txt-phase').val('Allocated');
        //     $('#btn-joborder').show();
        // }
    });

    function setAssignedTechnician(selectedTechnician = null) {
        if (selectedTechnician) {       // after searching machine incident details
            let $technician = $("#sel-technician");
            $technician.val(selectedTechnician);

            let user_level = $("#user_level").val();
            if (user_level == 'Operator'){
                $('#btn-joborder').hide();
                $("#btn-joborder").prop('disabled', true);
            }else{
                $('#btn-joborder').show();
                $("#btn-joborder").prop('disabled', false);
            }
        }else{
            if ($('#txt-phase').val().trim() !== 'Completed' && $('#txt-phase').val().trim() !== 'Cancelled' && $('#sel-technician').val() !== '') {
                $('#txt-phase').val('Allocated');
                $('#btn-joborder').show();
            }
        }
    }

    $("#btn-cancel").click(function(){
      swal.fire({
          title: 'Do you want to Cancel job order?',
          text: "You won't be able to revert this!",
          type: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, Cancel!',
          cancelButtonText: 'No',
          confirmButtonClass: 'btn btn-outline-success',
          cancelButtonClass: 'btn btn-outline-danger',
          allowOutsideClick: false,
          buttonsStyling: false
      }).then(function(result) {
          if(result.value) {
            var id = $("#mt-id").val();            
            var canceljob = new FormData();
            canceljob.append("id", id);            
            $.ajax({
               url:"ajax/joborder_cancel_record.ajax.php",
               method: "POST",
               data: canceljob,
               cache: false,
               contentType: false,
               processData: false,
               dataType:"text",
               success:function(answer){
               },
               error: function () {
                 swal.fire({
                    title: 'Cancellation Terminated!',
                    text: 'Something went wrong :(',
                    type: 'error',
                    confirmButtonText: 'Got it!',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                 }).then(function(result){
                    if(result.value) {              
                      window.location = 'machinetracking';
                    }
                 })
               },
               complete: function () {
                 swal.fire({
                    title: 'Cancellation Successful!',
                    type: 'success',
                    confirmButtonText: 'Got it!',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                 }).then(function(result){
                    if(result.value) {              
                      window.location = 'machinetracking';
                    }
                 })
               }

            })
          }
      });
   });
});