// ========================================
// GLOBAL VARIABLES
// ========================================
var calendarEventColorsInit = null;
var calendarInitialized = false;
var calendarEventsData = [];
var lastFetchRange = { start: null, end: null };


// ========================================
// FETCH + LOAD EVENTS
// ========================================
function maintenanceCalendarList(start = null, end = null){
    let picker = $('#lst_date_range_maintenance').data('daterangepicker');

    let start_date = start || picker.startDate.format('YYYY-MM-DD');
    let end_date = end || picker.endDate.format('YYYY-MM-DD');


    if (lastFetchRange.start === start_date && lastFetchRange.end === end_date) {
        return;
    }

    lastFetchRange = { start: start_date, end: end_date };

    let data = new FormData();
    data.append("start_date", start_date);
    data.append("end_date", end_date);

    $.ajax({
        url: "ajax/maintenance_calendar.ajax.php",
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",

        success: function(answer) {

            let colorMap = {
                "Electrical": "#EF5350",
                "Mechanical": "#26A69A",
                "Network": "#5C6BC0"
            };

            calendarEventsData = answer.map(item => ({
                title: item.failuretype,
                start: item.datereported,
                color: colorMap[item.failuretype] || "#546E7A"
            }));

            // ✅ Refresh calendar safely
            if (calendarEventColorsInit) {
                calendarEventColorsInit.removeAllEvents();
                calendarEventColorsInit.addEventSource(calendarEventsData);
            }
        }
    });
}


// ========================================
// FULLCALENDAR INIT (v5+)
// ========================================
var FullCalendarStyling = function() {

    var _componentFullCalendarStyling = function() {

        if (typeof FullCalendar == 'undefined') {
            console.warn('FullCalendar not loaded.');
            return;
        }

        var calendarEl = document.querySelector('.fullcalendar-event-colors');

        if (calendarEl) {

            calendarEventColorsInit = new FullCalendar.Calendar(calendarEl, {

                initialView: 'dayGridMonth',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },

                editable: true,
                events: calendarEventsData,

                // ========================================
                // CALENDAR → DATE PICKER
                // ========================================
                datesSet: function(info) {

                    console.log("DATES CHANGED");

                    let start = moment(info.start);
                    let end = moment(info.end).subtract(1, 'days');

                    let picker = $('#lst_date_range_maintenance').data('daterangepicker');

                    if (!picker) return;

                    // Update picker UI
                    picker.setStartDate(start);
                    picker.setEndDate(end);

                    $('#lst_date_range_maintenance').val(
                        start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY')
                    );

                    // Reload events
                    maintenanceCalendarList(
                        start.format('YYYY-MM-DD'),
                        end.format('YYYY-MM-DD')
                    );
                }
            });

            calendarEventColorsInit.render();
        }
    };

    return {
        init: function() {
            _componentFullCalendarStyling();
        }
    }

}();


// ========================================
// DOCUMENT READY
// ========================================
$(document).ready(function () {

    // ========================================
    // INIT DATERANGEPICKER
    // ========================================
    $('#lst_date_range_maintenance').daterangepicker({
        ranges:{
          'All'           : [moment('2025-12-01'), moment()],
          'Today'         : [moment(), moment()],
          'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
          'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
          'Last 30 Days'  : [moment().subtract(30,'days'), moment()],
          'This Month'    : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'    : [moment().subtract(1, 'months').startOf('month'), moment().subtract(1, 'months').endOf('month')]
        },
        startDate: moment().startOf('month'),
        endDate: moment().endOf('month'),
        minDate: moment('2025-11-01')
    });


    // ========================================
    // DATE PICKER → CALENDAR
    // ========================================
    $('#lst_date_range_maintenance').on("apply.daterangepicker", function(ev, picker) {

        let start_date = picker.startDate.format('YYYY-MM-DD');
        let end_date = picker.endDate.format('YYYY-MM-DD');

        if (calendarEventColorsInit) {

            let diffDays = picker.endDate.diff(picker.startDate, 'days') + 1;

            if (diffDays === 1) {
                calendarEventColorsInit.changeView('dayGridDay');
            } else if (diffDays <= 7) {
                calendarEventColorsInit.changeView('dayGridWeek');
            } else {
                calendarEventColorsInit.changeView('dayGridMonth');
            }

            calendarEventColorsInit.gotoDate(start_date);
        }

        maintenanceCalendarList(start_date, end_date);
    });


    // ========================================
    // TAB INIT
    // ========================================
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        var target = $(e.target).attr("href");

        if (target === "#calendar") {

            if (!calendarInitialized) {
                FullCalendarStyling.init();
                calendarInitialized = true;

                // Initial load
                maintenanceCalendarList();
            } else {
                if (calendarEventColorsInit) {
                    calendarEventColorsInit.updateSize();
                }
            }
        }
    });

});