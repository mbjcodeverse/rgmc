if (!$.fn.DataTable.isDataTable('.inventoryPeriodsTable')) {
    var ip = $('.inventoryPeriodsTable').DataTable({
        processing: true,
        autoWidth: true,
        scrollY: '55vh',
        keys: true,
        ordering: false,
        paging: false,
        dom: '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"i>',
                language: {
                    loadingRecords: 'Loading inventory cycle...',
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
    });
}

if (!$.fn.DataTable.isDataTable('.summaryUsageTable')) {
    var sut = $('.summaryUsageTable').DataTable({
        processing: true,
        autoWidth: true,
        scrollY: '55vh',
        keys: true,
        paging: false,
        dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer">',
        //dom: '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"i>',
                language: {
                    loadingRecords: 'Loading incoming details...',
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                },
                columnDefs: [
                    { targets: [1, 2], className: 'dt-right' }
                ]
    });
}

if (!$.fn.DataTable.isDataTable('.materialCostTrailTable')) {
    var mc = $('.materialCostTrailTable').DataTable({
        processing: true,
        autoWidth: true,
        scrollY: '55vh',
        keys: true,
        paging: false,
        dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer">',
        //dom: '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"i>',
                language: {
                    loadingRecords: 'Loading incoming details...',
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
    });
}

if (!$.fn.DataTable.isDataTable('.productionCostTrailTable')) {
    var pc = $('.productionCostTrailTable').DataTable({
        processing: true,
        autoWidth: true,
        scrollY: '55vh',
        keys: true,
        paging: false,
        dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer">',
        //dom: '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"i>',
                language: {
                    loadingRecords: 'Loading cost details...',
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
    });
}

if (!$.fn.DataTable.isDataTable('.productionDetailsTable')) {
    var pd = $('.productionDetailsTable').DataTable({
        processing: true,
        autoWidth: true,
        scrollY: '55vh',
        keys: true,
        paging: false,
        dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer">',
        //dom: '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"i>',
                language: {
                    loadingRecords: 'Loading production details...',
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    $('#lst_date_range').daterangepicker({
        ranges:{
          'All'           : [moment('2025-06-30'), moment()],
          'Today'         : [moment(),moment()],
          'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
          'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
          'Last 30 Days'  : [moment().subtract(30,'days'), moment()],
          'This Month'    : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'    : [moment().subtract(1, 'months').startOf('month'), moment().subtract(1, 'months').endOf('month')]
        },
        startDate: moment().startOf('month'), 
        endDate: moment().endOf('month'),
        minDate: moment('2025-06-30')
    });

    $('#tabular-export').hide();
    $('#tabular-print').hide();
    $('#filter-type-container').hide();
    $('#tiered-container').hide();
    $('a[data-toggle="tab"]').on('click', function () {
        const target = $(this).attr('href');
        const picker = $('#lst_date_range').data('daterangepicker');

        if (target === '#statistical-data') {
            picker.setStartDate(moment().startOf('month'));
            picker.setEndDate(moment().endOf('month'));
            $('#report-type-container').show();
            $('#tabular-export').hide();
            $('#tabular-print').hide();
            $('#filter-type-container').hide();
            $('#tiered-container').hide();
        } else if (target === '#tabular-data') {
            picker.setStartDate(moment().startOf('month'));
            picker.setEndDate(moment().endOf('month'));
            $('#report-type-container').show();
            $('#tabular-export').show();
            $('#tabular-print').show();
            $('#filter-type-container').hide();
            $('#tiered-container').hide();            
        } else if (target === '#assessment-data') {
            // Set to All
            picker.setStartDate(moment('2025-06-30'));
            picker.setEndDate(moment());
            $('#report-type-container').hide();
            $('#tabular-export').hide();
            $('#tabular-print').hide();
            $('#filter-type-container').show();
            $('#tiered-container').show();
            // $(".overall_assessment").empty();
            $("#btn-export").prop('disabled', true);
            $("#tns-search").prop('disabled', true);
            generateAssessment();
        } else{
            picker.setStartDate(moment('2025-06-30'));
            picker.setEndDate(moment());
            $('#report-type-container').hide();
            $('#tabular-export').hide();
            $('#tabular-print').hide();
            $('#filter-type-container').hide();
            $('#tiered-container').hide();
            $("#btn-export-usage").prop('disabled', true);
            $("#tns-search-usage").prop('disabled', true);
            generateUsageMatrix();
        }
    });


    $('#report-type, #lst_date_range').on("change", function() {
        fetchFactoryDashboardData();
        generateUsageMatrix();
        fetchProductionMetrics();   // Table below Line Graph
    });

    $('#report-type').trigger("change");

    // 3rd Tab - Assessment ----------------------------------------------------
    $('#btn-period').on('click', function() {
        $('#modal-inventory-periods').modal('show'); 
        load_inventory_periods();	
    });  

    // Selected Inventory Period (From - To)
    $(".inventoryPeriodsTable tbody").on('click', '.btnInvPeriod', function () {
        $(".overall_assessment").empty();
        let inventoryfrom = $(this).attr("inventoryfrom");
        let inventoryto = $(this).attr("inventoryto");
        let categorycode = $("#sel-categorycode").val();
        let tier = $('#chk-tier').is(':checked') ? 1 : 0;
        $("#modal-inventory-periods").modal('hide');
        assessmentMatrix(inventoryfrom, inventoryto, categorycode, tier);
    });

    $('#chk-tier').on('click', generateInventoryMatrix);
    $('#display-type, #sel-categorycode').on('change', generateInventoryMatrix);

    $('#btn-undo').on('click', function() {
        $('#sel-categorycode').val('').trigger('change');
        generateInventoryMatrix();
    });

    $("#btn-export").click(function(){
        exportToExcel();
    });

    $('#btn-undo-usage').on('click', function() {
        $('#sel-categorycode-usage').val('').trigger('change');
        generateUsageMatrix();
    });

    $("#btn-export-usage").click(function(){
        exportToExcelUsage();
    });   
    
    // Dashboard - TABULAR -------------------
    $("#btn-export-tabular").click(function(){
        exportToExcelTabular();
    });   
    
    $("#btn-print-tabular").click(function(){
        let reptype = $("#report-type").val();
        let date_range = $("#lst_date_range").val();

        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

        let generatedby = $("#txt-generatedby").val();
        window.open("extensions/tcpdf/pdf/tabularprint.php?reptype="+reptype+"&start_date="+start_date+"&end_date="+end_date+"&generatedby="+generatedby, "_blank");
    });
    // ---------------------------------------

    $('#sel-categorycode-usage').on('change', generateUsageMatrix);
    
    // -------------------------------------------------------------------------

    // function display_line_graph(){
    //     EchartsLinesZoomDark.init();
    // }

    // setTimeout(display_line_graph, 5000);
    // EchartsLinesZoomDark.init();
    // EchartsLinesPointValuesDark.init();
    // EchartsPieBasicDark.init();
    // EchartsPieRoseLabelsDark.init();
});

// Line chart with zoom
// Define the ECharts chart function
var EchartsLinesZoomDark = function() {
    var _linesZoomDarkExample = function(dates, productionData, materialsData) {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        var line_zoom_element = document.getElementById('line_zoom');

        if (line_zoom_element) {
            var line_zoom = echarts.init(line_zoom_element);
            line_zoom.setOption({
                color: ["#AED581", "#E57373", '#4FC3F7'],
                textStyle: { fontFamily: 'Roboto, Arial, Verdana, sans-serif', fontSize: 13 },
                animationDuration: 750,
                grid: { left: 0, right: 40, top: 35, bottom: 60, containLabel: true },
                legend: {
                    data: ['Production', 'Materials'],
                    itemHeight: 8,
                    itemGap: 20,
                    textStyle: { color: '#fff' }
                },
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(255,255,255,0.9)',
                    padding: [10, 15],
                    textStyle: { color: '#222', fontSize: 13, fontFamily: 'Roboto, sans-serif' }
                },
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    axisLabel: { color: '#fff' },
                    axisLine: { lineStyle: { color: 'rgba(255,255,255,0.25)' } },
                    data: dates // Use the dynamically populated dates
                }],
                yAxis: [{
                    type: 'value',
                    axisLabel: { formatter: '{value} ', color: '#fff' },
                    axisLine: { lineStyle: { color: 'rgba(255,255,255,0.25)' } },
                    splitLine: { lineStyle: { color: 'rgba(255,255,255,0.1)' } },
                    splitArea: { show: true, areaStyle: { color: ['rgba(255,255,255,0.01)', 'rgba(0,0,0,0.01)'] } }
                }],
                axisPointer: [{
                    lineStyle: { color: 'rgba(255,255,255,0.25)' }
                }],
                dataZoom: [
                    { type: 'inside', start: 0, end: 100 },         // zoom (30,70)
                    {
                        show: true,
                        type: 'slider',
                        start: 30,
                        end: 70,
                        height: 40,
                        bottom: 0,
                        borderColor: 'rgba(255,255,255,0.1)',
                        fillerColor: 'rgba(0,0,0,0.1)',
                        handleStyle: { color: '#585f63' },
                        textStyle: { color: '#fff' },
                        dataBackground: {
                            areaStyle: { color: 'rgba(0,0,0,0.5)' }
                        }
                    }
                ],
                series: [
                    {
                        name: 'Production',
                        type: 'line',
                        smooth: true,
                        symbol: 'circle',
                        symbolSize: 6,
                        itemStyle: { normal: { borderWidth: 2 } },
                        data: productionData // Use the dynamically populated data
                    },
                    {
                        name: 'Materials',
                        type: 'line',
                        smooth: true,
                        symbol: 'circle',
                        symbolSize: 6,
                        itemStyle: { normal: { borderWidth: 2 } },
                        data: materialsData // Use the dynamically populated data
                    }
                ]
            });
        }

        // Resize function
        var triggerChartResize = function() {
            line_zoom_element && line_zoom.resize();
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

    return {
        init: function(dates, productionData, materialsData) {
            _linesZoomDarkExample(dates, productionData, materialsData);
        }
    };
}();


var EchartsScatterBasicDark = function() {
    var _scatterBasicDarkExample = function(dates, productionData, materialsData) {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        var el = document.getElementById('scatter_basic');
        if (!el) return;

        var chart = echarts.init(el);

        // Build scatter data: [materialsCost, productionCost, dateLabel]
        var dataPoints = materialsData.map((matCost, i) => [
            matCost,
            productionData[i],
            dates[i]
        ]);     

        chart.setOption({
            color: ['#5ab1ef'], // single color for both
            textStyle: {
                fontFamily: 'Roboto, Arial',
                fontSize: 13
            },
            animationDuration: 750,
            grid: { left: 40, right: 20, top: 35, bottom: 40, containLabel: true },
            // Add legend
            // legend: {
            //     data: ['Materials Cost', 'Production Cost'],
            //     itemGap: 20,
            //     textStyle: {
            //         color: '#fff'
            //     }
            // },
            tooltip: {
                trigger: 'item',
                backgroundColor: 'rgba(255,255,255,0.9)',
                padding: [10, 15],
                textStyle: { color: '#222', fontSize: 13 },
                axisPointer: {
                    type: 'cross',
                    lineStyle: {
                        type: 'dashed',
                        width: 1
                    }
                },
                // formatter: params => {
                //     const [mat, prod, date] = params.value;
                //     return `
                //         <strong>Date:</strong> ${date}<br/>
                //         <strong>Materials Cost:</strong> ${mat.toLocaleString()}<br/>
                //         <strong>Production Cost:</strong> ${prod.toFixed(2)}
                //     `;
                // }

                formatter: params => {
                    const [mat, prod, date] = params.value;
                    return `
                        <strong>Date:</strong> ${date}<br/>
                        <strong>Materials Cost:</strong> ${mat.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}<br/>
                        <strong>Production Cost:</strong> ${prod.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    `;
                }
            },
            xAxis: [{
                type: 'value',
                name: 'Materials Cost',
                nameTextStyle: { color: '#fcc088' },
                axisLabel: { color: '#fcc088' },
                axisLine: { lineStyle: { color: 'rgba(255,255,255,0.25)' } },
                splitLine: { lineStyle: { color: 'rgba(255,255,255,0.1)', type: 'dashed' } }
            }],
            yAxis: [{
                type: 'value',
                name: 'Production Cost',
                nameTextStyle: { color: '#fff' },
                axisLabel: { color: '#fff' },
                axisLine: { lineStyle: { color: 'rgba(255,255,255,0.25)' } },
                splitLine: { lineStyle: { color: 'rgba(255,255,255,0.1)' } }
            }],

            // Axis pointer
            axisPointer: [{
                label: {
                    backgroundColor: '#b6a2de',
                    shadowBlur: 0
                }
            }],
            series: [             
                {
                    name: 'Cost Comparison',
                    type: 'scatter',
                    data: dataPoints,
                    markLine: {
                        data: [{ type: 'average', name: 'Avg' }]
                    }
                }
            ]
        });
// Resize function
        var triggerChartResize = function() {
            scatter_basic_element && scatter_basic.resize();
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
        //window.addEventListener('resize', () => chart.resize());
    };

    return { init: _scatterBasicDarkExample };
}();







var EchartsLinesPointValuesDark = function() {
    var _linesPointValuesDarkExample = function() {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        // Define element
        var line_values_element = document.getElementById('line_values');
        if (line_values_element) {

            // Initialize chart
            var line_values = echarts.init(line_values_element);


            //
            // Chart config
            //

            // Options
            line_values.setOption({

                // Define colors
                color: ['#4DB6AC', '#F06292'],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Chart animation duration
                animationDuration: 750,

                // Setup grid
                grid: {
                    left: 0,
                    right: 40,
                    top: 35,
                    bottom: 0,
                    containLabel: true
                },

                // Add legend
                legend: {
                    data: ['Maximum', 'Minimum'],
                    itemHeight: 8,
                    itemGap: 20,
                    textStyle: {
                        color: '#fff'
                    }
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(255,255,255,0.9)',
                    padding: [10, 15],
                    textStyle: {
                        color: '#222',
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    }
                },

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    axisLabel: {
                        color: '#fff'
                    },
                    axisLine: {
                        lineStyle: {
                            color: 'rgba(255,255,255,0.25)'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: 'rgba(255,255,255,0.1)'
                        }
                    }
                }],

                // Vertical axis
                yAxis: [{
                    type: 'value',
                    axisLabel: {
                        formatter: '{value} °C',
                        color: '#fff'
                    },
                    axisLine: {
                        lineStyle: {
                            color: 'rgba(255,255,255,0.25)'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: 'rgba(255,255,255,0.1)'
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(255,255,255,0.01)', 'rgba(0,0,0,0.01)']
                        }
                    }
                }],

                // Axis pointer
                axisPointer: [{
                    lineStyle: {
                        color: 'rgba(255,255,255,0.25)'
                    }
                }],

                // Add series
                series: [
                    {
                        name: 'Maximum',
                        type: 'line',
                        data: [2, 37, 9, 32, -5, 10, 28],
                        smooth: true,
                        symbol: 'circle',
                        symbolSize: 7,
                        label: {
                            normal: {
                                show: true
                            } 
                        },
                        itemStyle: {
                            normal: {
                                borderWidth: 2
                            }
                        }
                    },
                    {
                        name: 'Minimum',
                        type: 'line',
                        data: [10, -12, 28, -8, 30, 22, 9],
                        smooth: true,
                        symbol: 'circle',
                        symbolSize: 7,
                        label: {
                            normal: {
                                show: true
                            } 
                        },
                        itemStyle: {
                            normal: {
                                borderWidth: 2
                            }
                        }
                    }
                ]
            });
        }

        // Resize function
        var triggerChartResize = function() {
            line_values_element && line_values.resize();
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

    return {
        init: function() {
            _linesPointValuesDarkExample();
        }
    }
}();

// Pie Basic
var EchartsPieBasicDark = function() {
    var _scatterPieBasicDarkExample = function() {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }
        var pie_basic_element = document.getElementById('pie_basic');

        if (pie_basic_element) {
            var pie_basic = echarts.init(pie_basic_element);
            pie_basic.setOption({

                // Colors
                color: [
                    '#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80',
                    '#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa',
                    '#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
                    '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089',
                    '#66bb6a'
                ],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Add title
                title: {
                    text: 'Browser popularity',
                    subtext: 'Open source information',
                    left: 'center',
                    textStyle: {
                        fontSize: 17,
                        fontWeight: 500,
                        color: '#fff'
                    },
                    subtextStyle: {
                        fontSize: 12,
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
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    top: 'center',
                    left: 0,
                    data: ['IE', 'Opera', 'Safari', 'Firefox', 'Chrome'],
                    itemHeight: 8,
                    itemWidth: 8,
                    textStyle: {
                        color: '#fff'
                    }
                },

                // Add series
                series: [{
                    name: 'Browsers',
                    type: 'pie',
                    radius: '70%',
                    center: ['50%', '57.5%'],
                    itemStyle: {
                        normal: {
                            borderWidth: 2,
                            borderColor: '#353f53'
                        }
                    },
                    data: [
                        {value: 335, name: 'IE'},
                        {value: 310, name: 'Opera'},
                        {value: 234, name: 'Safari'},
                        {value: 135, name: 'Firefox'},
                        {value: 1548, name: 'Chrome'}
                    ]
                }]
            });
        }

        var triggerChartResize = function() {
            pie_basic_element && pie_basic.resize();
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

    return {
        init: function() {
            _scatterPieBasicDarkExample();
        }
    }
}();

var EchartsPieRoseLabelsDark = function() {
    var _pieRoseLabelsDarkExample = function() {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        // Define element
        var pie_rose_labels_element = document.getElementById('pie_rose_labels');
        if (pie_rose_labels_element) {
            var pie_rose_labels = echarts.init(pie_rose_labels_element);
            pie_rose_labels.setOption({

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
                    text: 'Employee\'s salary review',
                    subtext: 'Senior front end developer',
                    left: 'center',
                    textStyle: {
                        fontSize: 17,
                        fontWeight: 500,
                        color: '#fff'
                    },
                    subtextStyle: {
                        fontSize: 12,
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
                    formatter: '{a} <br/>{b}: +{c}$ ({d}%)'
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    top: 'center',
                    left: 0,
                    data: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                    itemHeight: 8,
                    itemWidth: 8,
                    textStyle: {
                        color: '#fff'
                    }
                },

                // Add series
                series: [
                    {
                        name: 'Increase (brutto)',
                        type: 'pie',
                        radius: ['15%', '80%'],
                        center: ['50%', '57.5%'],
                        roseType: 'radius',
                        itemStyle: {
                            normal: {
                                borderWidth: 2,
                                borderColor: '#353f53'
                            }
                        },
                        data: [
                            {value: 440, name: 'Jan'},
                            {value: 260, name: 'Feb'},
                            {value: 350, name: 'Mar'},
                            {value: 250, name: 'Apr'},
                            {value: 210, name: 'May'},
                            {value: 350, name: 'Jun'},
                            {value: 300, name: 'Jul'},
                            {value: 430, name: 'Aug'},
                            {value: 400, name: 'Sep'},
                            {value: 450, name: 'Oct'},
                            {value: 330, name: 'Nov'},
                            {value: 200, name: 'Dec'}
                        ]
                    }
                ]
            });
        }

        var triggerChartResize = function() {
            pie_rose_labels_element && pie_rose_labels.resize();
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

    return {
        init: function() {
            _pieRoseLabelsDarkExample();
        }
    }
}();

// INVENTORY Tab Metrics
function generateInventoryMatrix(){
    $("#tns-search").val('');
    // $('#sel-categorycode').val('').trigger('change');
    let date_range = $("#lst_date_range").val();
    let inventoryfrom = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let inventoryto = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
    let tier = $('#chk-tier').is(':checked') ? 1 : 0;
    let categorycode = $("#sel-categorycode").val();
    assessmentMatrix(inventoryfrom, inventoryto, categorycode, tier);
}

// INVENTORY Tab Metrics
function assessmentMatrix(inventoryfrom, inventoryto, categorycode, tier) {
    let start_date = inventoryfrom;
    let end_date = inventoryto;

    var display = $("#display-type").val();

    // Current Date
    let today = new Date();
    let year = today.getFullYear();
    let month = String(today.getMonth() + 1).padStart(2, '0'); 
    let day = String(today.getDate()).padStart(2, '0');
    var date_today = year + '-' + month + '-' + day;

    // Prepare the data for the AJAX request
    let data_assessment = new FormData();
    data_assessment.append("start_date", start_date);
    data_assessment.append("end_date", end_date);
    data_assessment.append("categorycode", categorycode);
    data_assessment.append("tier", tier);
    
    $.ajax({
        url: "ajax/dashboard_assessment.ajax.php",
        method: "POST",
        data: data_assessment,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            var html = [];
            html.push('<div class="table-responsive" style="margin-top:-40px;margin-bottom:-28px;margin-left:18px;margin-right:18px;overflow-y: auto; overflow-x: auto; max-height: 500px;">');
            html.push('<table class="table table-hover table-striped mx-auto w-auto productInventoryTable" style="border-collapse: separate; border-spacing: 0;">');
            html.push('<thead>');
                html.push('<tr>');
                    html.push('<th class="table_head_left_fixed" style="position: sticky; left: 0; z-index: 10; background-color: #1f1f1f; padding-top:8px; padding-bottom:8px; min-width:400px;font-size:1.1em;">SKU DESCRIPTION</th>');
                    // html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#89fa91;">Level</th>');
                    html.push('<th class="table_head_left_fixed" style="padding-top:8px; padding-bottom:8px; width: 110px; max-width: 110px; color:#89fa91;">Level</th>');
                    html.push('<th class="table_head_left_fixed" style="position: sticky; left: 0; z-index: 10; background-color: #1f1f1f; padding-top:8px;padding-bottom:8px; min-width:400px;font-size:1.1em;">Category</th>');
                    
                    if (display == 'insight'){
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#89fa91;">Inventory</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#89fa91;">Incoming</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#89fa91;">Components</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#89fa91;">Recycled</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#89fa91;">Waste/Damage</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:85px;color:#89fa91;">Returns</th>');    
                    }  

                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#7FFF00;border-right:3px solid yellow;border-left:3px solid yellow;">INBOUND</th>');
                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#fac189;border-right:3px solid yellow;">OUTBOUND</th>');
                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#aedefc;border-right:3px solid yellow;">STOCK</th>');

                    if (date_today == end_date){
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#fac189;border-right:3px solid yellow;">Rate</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#aedefc;border-right:3px solid yellow;">Cost</th>');
                    }
                html.push('</tr>');
            html.push('</thead>');
            var total_stock_cost = 0.00;
            for (var i = 0; i < answer.length; i++) {
                let matrix = answer[i];
                let pdesc = matrix.pdesc;
                let itemid = matrix.itemid;
                let categorycode = matrix.categorycode;

                let inventory_qty = parseInt(matrix.inventory_qty);
                let incoming_qty_total = parseInt(matrix.incoming_qty_total);
                let subcom_qty_total = parseInt(matrix.subcom_qty_total);
                let recycle_qty_total = parseInt(matrix.recycle_qty_total);
                let debris_qty_total = parseInt(matrix.debris_qty_total);
                let return_qty_total = parseInt(matrix.return_qty_total);
                let rawout_qty_total = parseInt(matrix.rawout_qty_total);

                let critical = parseInt(matrix.critical);
                let low = parseInt(matrix.low);
                let moderate = parseInt(matrix.moderate);
                let high = parseInt(matrix.high);

                // Inbound Total
                let total_stockin = inventory_qty + incoming_qty_total + subcom_qty_total + recycle_qty_total + debris_qty_total + return_qty_total;

                // On-hand
                let onhand = total_stockin - rawout_qty_total;
                let formattedOnhand = onhand < 0 ? `(${Math.abs(onhand)})` : numberWithCommasNoDecimal(onhand);

                let rate = Number(matrix.ucost);
                let stock_cost = rate * onhand;
                total_stock_cost = total_stock_cost + stock_cost;
                //let formattedStockCost = stock_cost < 0 ? `(${Math.abs(stock_cost)})` : numberWithCommas(stock_cost);
                let formattedStockCost = stock_cost < 0 
                    ? `(${Math.abs(stock_cost).toFixed(2)})` 
                    : numberWithCommas(stock_cost.toFixed(2));
                // Check if any of the critical, low, moderate, or high values are > 0
                if (critical > 0 || low > 0 || moderate > 0 || high > 0) {
                    // Determine level based on on-hand quantity
                    let levelColor = "#80c90a"; // Default to green (high)
                    let levelText = "High";

                    if (onhand <= critical) {
                        levelColor = "#ff7a70";
                        levelText = "Critical";
                    } else if (onhand <= low) {
                        levelColor = "yellow";
                        levelText = "Low";
                    } else if (onhand <= moderate) {
                        levelColor = "#c8ff70";
                        levelText = "Moderate";
                    }

                    // Calculate where the vertical line should be placed based on the threshold values
                    // Calculate where the vertical line should be placed based on the threshold values
                    let linePosition = 0;

                    // Check which range the onhand value falls into and calculate the position
                    if (onhand <= critical) {
                        // Critical range (red)
                        linePosition = (onhand / critical) * 25; // 25% for the critical section
                    } else if (onhand <= low) {
                        // Low range (yellow)
                        linePosition = 25 + ((onhand - critical) / (low - critical)) * 25; // 25% to 50%
                    } else if (onhand <= moderate) {
                        // Moderate range (lightgreen)
                        linePosition = 50 + ((onhand - low) / (moderate - low)) * 25; // 50% to 75%
                    } else if (onhand <= high) {
                        // High range (green)
                        linePosition = 75 + ((onhand - moderate) / (high - moderate)) * 25; // 75% to 100%
                    } else {
                        // If inventory is higher than 'high' (position at the far right of the green section)
                        linePosition = 100; // Far right of the green section (end of the 100% bar)
                    }

                    // Smooth gradient bar with color transitions (red to green) and vertical white line
                    var levelBar = `<div style="width: 150%; height: 12px; 
                            background: linear-gradient(to right, 
                                        red 0%, 
                                        yellow 25%, 
                                        lightgreen 50%, 
                                        green 100%); 
                            position: relative; 
                            left: -25%;
                            border: 2px solid #dcdcdc; /* Light-colored border */
                            border-radius: 6px; /* Rounded corners for the bar */
                            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1), 
                                        0px 2px 3px rgba(255, 255, 255, 0.3); /* Shadow for 3D effect */
                            background-clip: padding-box;">  

                            <!-- Adjust the position to align properly within the container -->
                            <!-- Vertical white line representing inventory level -->
                            <div style="position: absolute; 
                                        top: 0; 
                                        bottom: 0; 
                                        left: ${linePosition}%; 
                                        width: 4px; /* Increased thickness */
                                        background-color: white;
                                        box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.9); /* Added shadow for better visibility */
                                        border-radius: 2px;">
                            </div>
                        </div>`;

                    // Centered Text below the bar
                    var levelTextElement = `<div style="text-align: center; font-size: 1em; color: ${levelColor};">
                                            ${levelText}
                                        </div>`;
                }                    

                html.push('<tr>');
                    html.push('<td style="position: sticky; left: 0; z-index: 5; background-color: #1f1f1f; border-right: 1px solid white; font-size:1.1em; color:lightyellow;font-size:1.3em;">' + pdesc + '</td>');
                    
                    if (critical > 0 || low > 0 || moderate > 0 || high > 0) {
                        html.push('<td style="text-align:center; font-size:1.1em; color:lightyellow; width: 200px; max-width: 110px;">' + 
                            '<div style="width: 100%; max-width: 110px; margin: 0 auto;">' + 
                                levelBar + 
                                levelTextElement +  
                            '</div>' +
                        '</td>');  
                    }else{
                        html.push('<td style="text-align:center; font-size:1.1em; color:lightyellow; width: 110px;"></td>');                    
                    }

                    html.push('<td style="position: sticky; left: 0; z-index: 5; background-color: #1f1f1f; border-right: 1px solid white; font-size:1.1em; color:lightyellow;">' + categorycode + '</td>');
                    
                    if (display == 'insight'){
                        html.push('<td style="text-align:right;border-right:1px solid white;border-left:1px solid white;color:#89fa91;font-size:1.1em;">' + (inventory_qty !== 0 ? numberWithCommasNoDecimal(inventory_qty) : '') + '</td>');
                        html.push('<td style="border-right:1px solid white;">' +
                            (incoming_qty_total !== 0 ? '<button style="z-index:2;width:80px;padding-top:1px;padding-bottom:1px;" type="button" class="btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon ml-2 btnIncoming" itemid="' + itemid + '" pdesc="' + pdesc + '" start_date="' + start_date + '" end_date="' + end_date + '">' + numberWithCommasNoDecimal(incoming_qty_total) + '</button>' : '') +
                            '</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:#89fa91;font-size:1.1em;">' + (subcom_qty_total !== 0 ? numberWithCommasNoDecimal(subcom_qty_total) : '') + '</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:#89fa91;font-size:1.1em;">' + (recycle_qty_total !== 0 ? numberWithCommasNoDecimal(recycle_qty_total) : '') + '</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:#89fa91;font-size:1.1em;">' + (debris_qty_total !== 0 ? numberWithCommasNoDecimal(debris_qty_total) : '') + '</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:#89fa91;font-size:1.1em;">' + (return_qty_total !== 0 ? numberWithCommasNoDecimal(return_qty_total) : '') + '</td>');
                    }

                    html.push('<td style="text-align:right;border-right:1px solid white;border-left:1px solid white;color:#7FFF00;font-size:1.1em;">' + (total_stockin !== 0 ? numberWithCommasNoDecimal(total_stockin) : '') + '</td>');
                    html.push('<td style="text-align:right;border-right:1px solid white;color:#fac189;font-size:1.1em;">' + (rawout_qty_total !== 0 ? numberWithCommasNoDecimal(rawout_qty_total) : '') + '</td>');
                    
                    if (onhand > 0){
                        html.push('<td style="text-align:right;color:#aedefc;font-size:1.1em;border-right:4px solid white;">' + (onhand !== 0 ? formattedOnhand : '') + '</td>');
                    }else{
                        html.push('<td style="text-align:right;color:red;font-size:1.1em;border-right:4px solid white;">' + (onhand !== 0 ? formattedOnhand : '') + '</td>');
                    }

                    if (date_today == end_date && onhand != 0){
                        html.push('<td style="text-align:right;border-right:1px solid white;color:#fac189;font-size:1.1em;">' + numberWithCommas(rate) + '</td>');
                        html.push('<td style="text-align:right;color:#aedefc;font-size:1.1em;border-right:4px solid white;">' + formattedStockCost + '</td>');
                    }else{
                        html.push('<td style="text-align:right;border-right:1px solid white;color:#fac189;font-size:1.1em;"></td>');
                        html.push('<td style="text-align:right;color:#aedefc;font-size:1.1em;border-right:4px solid white;"></td>');
                    }
                html.push('</tr>');
            }

            if (total_stock_cost > 0.00){
                 html.push('<tr>');
                    html.push('<td colspan="12" style="text-align:right;border:4px solid white;color:honeydew;font-size:1.5em;font-weight:bold;">TOTAL STOCK COST</td>');
                    html.push('<td style="text-align:right;color:greenyellow;font-size:1.5em;border-right:4px solid white;border-top:4px solid white;border-bottom:4px solid white;font-weight:bold;">' + numberWithCommas(total_stock_cost) + '</td>');
                 html.push('</tr>');
            }

            html.push('</table>');
            html.push('</div>');

            $('.overall_assessment').html(html.join(''));

            $('<style>.productInventoryTable tbody td { padding-top: 2px; padding-bottom: 2px; }</style>').appendTo('head');
            $('<style>.productInventoryTable td:nth-child(3), .productInventoryTable th:nth-child(3) { display: none; }</style>').appendTo('head');

            $("#btn-export").prop('disabled', false);
            $("#tns-search").prop('disabled', false);
            $('#tns-search').prop('disabled', false);
            $('#tns-search').focus();
        }
    });
}

function calculateInventoryMetrics(inventory_qty, critical, low, moderate, high) {
    let IRS = (inventory_qty / critical) * 100;
    let IHI = (high / inventory_qty) * 1 + (moderate / inventory_qty) * 0.75 + (low / inventory_qty) * 0.5 + (critical / inventory_qty) * 0.25;
    let ISR = ((moderate + high) / inventory_qty) * 100;
    let IRN = critical + (low / 2);

    return {
        IRS: IRS.toFixed(2),
        IHI: IHI.toFixed(2),
        ISR: ISR.toFixed(2),
        IRN: IRN.toFixed(2)
    };
}

// TABULAR Report Generation
function fetchFactoryDashboardData() {
    var reptype = $("#report-type").val();
    let date_range = $("#lst_date_range").val();

    // Format the date strings to yyyy-mm-dd
    let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

    // Prepare the data for the AJAX request
    let data = new FormData();
    data.append("reptype", reptype);
    data.append("start_date", start_date);
    data.append("end_date", end_date);

    $.ajax({
        url: "ajax/factorydashboard.ajax.php",
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            var productionData = [];
            var materialsData = [];
            let dates = [];

            $(".tabular_content").empty();
            var html = [];

            html.push('<style>');
                html.push('.scrollable-table-wrapper { position: relative; width: 100%; margin: 0 auto; font-size: 1.1em; }');
                html.push('.scrollable-table-header, .scrollable-table-footer { width: 100%; table-layout: fixed; background-color: #2a3141; }');
                html.push('.scrollable-table-footer td { font-weight: bold; }');
                html.push('.scrollable-table-body-container { max-height: 400px; overflow-y: auto; width: 100%; }'); // Ensured the body container is 100% width
                html.push('.scrollable-table-body { width: 100%; table-layout: fixed; }'); // Ensured table-layout: fixed
                html.push('thead th, tfoot td { position: sticky; background-color: #2a3141; z-index: 2; top: 0; }'); // Sticky header
                html.push('.scrollable-table-wrapper table tr td, .scrollable-table-wrapper table tr th { padding-top: 4px; padding-bottom: 4px; }');
                html.push('.scrollable-table-body td, .scrollable-table-header th { text-align: right; }'); // Ensured text-align: right for both header and body
            html.push('</style>');

            html.push('<div class="scrollable-table-wrapper">');

            // Header Table
            html.push('<table class="table table-bordered scrollable-table-header">');
                html.push('<thead>');
                    html.push('<tr>');
                        html.push('<th style="font-size:1.1em;width:200px;">Date Series</th>');
                        html.push('<th style="text-align:right;font-size:1.1em;width:175px;">Sales Amount</th>');
                        html.push('<th style="text-align:right;font-size:1.1em;width:175px;">Production Value</th>');
                        html.push('<th style="text-align:right;font-size:1.1em;width:175px;">Materials Cost</th>');
                        html.push('<th style="text-align:right;font-size:1.1em;width:120px;">RM%</th>');
                        html.push('<th style="text-align:right;font-size:1.1em;width:120px;">Manpower Cost</th>');
                        html.push('<th style="text-align:right;font-size:1.1em;width:120px;">MC%</th>');
                        html.push('<th style="text-align:right;font-size:1.1em;width:120px;">Electricity Cost</th>');
                        html.push('<th style="text-align:right;font-size:1.1em;width:120px;">EC%</th>');
                    html.push('</tr>');
                html.push('</thead>');
            html.push('</table>');

            // Scrollable Body
            html.push('<div class="scrollable-table-body-container">');
                html.push('<table class="table table-striped table-bordered scrollable-table-body">');
                    html.push('<tbody>');

                var total_sales_amount = 0.00;
                var total_production_cost = 0.00;
                var total_materials_cost = 0.00;
                var total_manpower_cost = 0.00;
                var total_electricity_cost = 0.00;

                var total_sa_percent = 0.00;
                var total_rm_percent = 0.00;
                var total_mp_percent = 0.00;
                var total_ec_percent = 0.00;

                var s = 0;
                var r = 0;
                var e = 0;
                var m = 0;

                // Averages ----------------
                var ave_sales = 0.00;
                var ave_prod = 0.00;
                var ave_rawmats = 0.00;
                var ave_manpower = 0.00;
                var ave_electricity = 0.00;

                var ave_s = 0;
                var ave_p = 0;
                var ave_r = 0;
                var ave_m = 0;
                var ave_e = 0;
                for (let i = 0; i < answer.length; i++) {
                    let dashboard = answer[i];
                    if (reptype == 1){
                        let dashboard_date = dashboard.date_label.split("-");
                        var formattedDate = `${dashboard_date[1]}/${dashboard_date[2]}/${dashboard_date[0]}`;
                    }else{
                        var formattedDate = dashboard.date_label;
                    }
                    dates.push(formattedDate);

                    var actual_materials_cost = Number(dashboard.raw_materials_cost) - Number(dashboard.excess_materials_cost) + Number(dashboard.return_cost);
                    // For graph
                    materialsData.push(actual_materials_cost);
                    productionData.push(dashboard.production_cost);

                    // Tabular Data Tab ---------------------------------------
                    // Raw Materials %
                    if (Number(dashboard.production_cost) !== 0) {
                        var rm_percent = actual_materials_cost / Number(dashboard.production_cost) * 100;
                        var mp_percent = Number(dashboard.manpower_cost) / Number(dashboard.production_cost) * 100;
                        var ec_percent = Number(dashboard.electricity_cost) / Number(dashboard.production_cost) * 100;
                    }else{
                        var rm_percent = 0.00;
                        var mp_percent = 0.00;
                        var ec_percent = 0.00;
                    }

                    // Average Costing
                    if (Number(dashboard.sales_amount) > 0.00){
                        ave_s += 1;
                        ave_sales += Number(dashboard.sales_amount);
                    }

                    if (Number(dashboard.production_cost) > 0.00){
                        ave_p += 1;
                        ave_prod += Number(dashboard.production_cost);
                    }

                    if (Number(dashboard.raw_materials_cost) > 0.00){
                        ave_r += 1;
                        ave_rawmats += actual_materials_cost;
                    }

                    if (Number(dashboard.manpower_cost) > 0.00){
                        ave_m += 1;
                        ave_manpower += Number(dashboard.manpower_cost);
                    }

                    if (Number(dashboard.electricity_cost) > 0.00){
                        ave_e += 1;
                        ave_electricity += Number(dashboard.electricity_cost);
                    }
                    
                    // Divisor of % Average
                    if (rm_percent && rm_percent > 0.00) {
                        r += 1;         // raw mats divisor
                    }

                    if (mp_percent && mp_percent > 0.00) {
                        m += 1;         // manpower divisor
                    }

                    if (ec_percent && ec_percent > 0.00) {
                        e += 1;         // electricity divisor
                    }
                    
                    total_sales_amount += Number(dashboard.sales_amount);
                    total_production_cost += Number(dashboard.production_cost);
                    total_materials_cost += actual_materials_cost;
                    total_manpower_cost += Number(dashboard.manpower_cost);
                    total_electricity_cost += Number(dashboard.electricity_cost);
                    
                    html.push('<tr>');
                        html.push('<td style="color:#a4dcfc;font-size:1.2em;width:200px;">'+formattedDate+'</td>');
                    // Sales amount with hover & custom function call
                    html.push('<td ' +
                        'style="text-align:right; color:#62fca3; width:175px; cursor:pointer;" ' +
                        'onmouseover="this.style.backgroundColor=\'#fccd60\'; this.style.color=\'#303030\'; this.style.fontSize=\'1.1em\'; this.style.fontWeight=\'bold\'" ' +
                        'onmouseout="this.style.backgroundColor=\'\'; this.style.color=\'#62fca3\'; this.style.fontSize=\'\'; this.style.fontWeight=\'\'" ' +
                        'onclick="salesAmountTrail(\'' + formattedDate + '\')" ' +
                        '>' + numberWithCommas(dashboard.sales_amount) + '</td>');

                    // Production Cost with hover & custom function call
                    html.push('<td ' +
                        'style="text-align:right; color:#62fca3; width:175px; cursor:pointer;" ' +
                        'onmouseover="this.style.backgroundColor=\'#fccd60\'; this.style.color=\'#303030\'; this.style.fontSize=\'1.1em\'; this.style.fontWeight=\'bold\'" ' +
                        'onmouseout="this.style.backgroundColor=\'\'; this.style.color=\'#62fca3\'; this.style.fontSize=\'\'; this.style.fontWeight=\'\'" ' +
                        'onclick="productionCostTrail(\'' + formattedDate + '\')" ' +
                        '>' + numberWithCommas(dashboard.production_cost) + '</td>');

                    html.push('<td ' +
                        'style="text-align:right; color:#ffa6c1; width:175px; cursor:pointer;" ' +
                        'onmouseover="this.style.backgroundColor=\'#fccd60\'; this.style.color=\'#303030\'; this.style.fontSize=\'1.1em\'; this.style.fontWeight=\'bold\'" ' +
                        'onmouseout="this.style.backgroundColor=\'\'; this.style.color=\'#ffa6c1\'; this.style.fontSize=\'\'; this.style.fontWeight=\'\'" ' +
                        'onclick="materialCostTrail(\'' + formattedDate + '\')" ' +
                        '>' + numberWithCommas(actual_materials_cost) + '</td>');

                        // html.push('<td style="text-align:right;color:#62fca3;width:175px;">'+numberWithCommas(dashboard.production_cost)+'</td>');
                        // html.push('<td style="text-align:right;color:#ffa6c1;width:175px;">'+numberWithCommas(actual_materials_cost)+'</td>');
                        if (rm_percent && rm_percent > 0.00) {
                            html.push('<td style="color:#ffeaa6;font-size:1em;text-align:right;width:120px;">'+numberWithCommas(rm_percent)+' %'+'</td>');
                        }else{
                            html.push('<td style="color:#fc6593;font-size:1em;width:120px;">-NaN-</td>');
                        }

                        html.push('<td style="text-align:right;color:#ffa6c1;width:120px;">'+numberWithCommas(dashboard.manpower_cost)+'</td>');
                        if (mp_percent && mp_percent > 0.00) {
                            html.push('<td style="color:#ffeaa6;font-size:1em;text-align:right;width:120px;">'+numberWithCommas(mp_percent)+' %'+'</td>');
                        }else{
                            html.push('<td style="color:#fc6593;font-size:1em;width:120px;">-NaN-</td>');
                        }                        

                        html.push('<td style="text-align:right;color:#ffa6c1;width:120px;">'+numberWithCommas(dashboard.electricity_cost)+'</td>');
                        if (ec_percent && ec_percent > 0.00) {
                            html.push('<td style="color:#ffeaa6;font-size:1em;text-align:right;width:120px;">'+numberWithCommas(ec_percent)+' %'+'</td>');
                        }else{
                            html.push('<td style="color:#fc6593;font-size:1em;width:120px;">-NaN-</td>');
                        }
                    html.push('</tr>');
                }

                if (total_production_cost > 0.00 && total_materials_cost > 0.00) {
                    total_rm_percent = (total_materials_cost / total_production_cost) * 100; // Percentage, not just a fraction
                } else {
                    total_rm_percent = 0.00; // Return 0% when there is no valid production or materials cost
                }

                if (total_production_cost > 0.00 && total_manpower_cost > 0.00) {
                    total_mp_percent = (total_manpower_cost / total_production_cost) * 100; // Percentage
                } else {
                    total_mp_percent = 0.00;
                }

                if (total_production_cost > 0.00 && total_electricity_cost > 0.00) {
                    total_ec_percent = (total_electricity_cost / total_production_cost) * 100; // Percentage
                } else {
                    total_ec_percent = 0.00;
                }

                html.push('</tbody>');

                html.push('<tfoot>');
                    html.push('<tr>');
                        html.push('<td style="color:#a4dcfc;font-size:1.2em;">SUB-TOTAL</td>');
                        html.push('<td style="text-align:right;font-size:1em;color:#62fca3;">'+numberWithCommas(total_sales_amount)+'</td>');
                        html.push('<td style="text-align:right;font-size:1em;color:#62fca3;">'+numberWithCommas(total_production_cost)+'</td>');
                        html.push('<td style="text-align:right;font-size:1em;color:#ffa6c1;">'+numberWithCommas(total_materials_cost)+'</td>');
                        html.push('<td style="text-align:right;font-size:1em;color:#ffeaa6;">'+numberWithCommas(total_rm_percent)+' %'+'</td>');
                        html.push('<td style="text-align:right;font-size:1em;color:#ffa6c1;">'+numberWithCommas(total_manpower_cost)+'</td>');
                        html.push('<td style="text-align:right;font-size:1em;color:#ffeaa6;">'+numberWithCommas(total_mp_percent)+' %'+'</td>');
                        html.push('<td style="text-align:right;font-size:1em;color:#ffa6c1;">'+numberWithCommas(total_electricity_cost)+'</td>');
                        html.push('<td style="text-align:right;font-size:1em;color:#ffeaa6;">'+numberWithCommas(total_ec_percent)+' %'+'</td>');
                    html.push('</tr>');
                    ave_prod = ave_prod / ave_p;
                    html.push('<tr>');
                        html.push('<td style="color:#a4dcfc;font-size:1.2em;">AVERAGE</td>');
                        if (ave_sales > 0.00 && ave_s > 0){
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffa6c1;">'+numberWithCommas(ave_sales / ave_s)+'</td>');
                        }else{
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffa6c1;">0.00</td>');
                        }

                        if (ave_prod > 0.00){
                            html.push('<td style="text-align:right;font-size:1.1em;color:#62fca3;">'+numberWithCommas(ave_prod)+'</td>');
                        }else{
                            html.push('<td style="text-align:right;font-size:1.1em;color:#62fca3;">0.00</td>');
                        }

                        if (ave_rawmats > 0.00 && ave_r > 0){
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffa6c1;">'+numberWithCommas(ave_rawmats / ave_r)+'</td>');
                        }else{
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffa6c1;">0.00</td>');
                        }

                        if ((ave_rawmats / ave_r) > 0.00 && ave_prod > 0.00){
                            let m_percentage = ((ave_rawmats / ave_r) / ave_prod) * 100;
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffeaa6;">' + numberWithCommas(m_percentage.toFixed(2)) + ' %</td>');
                            // html.push('<td style="text-align:right;font-size:1.3em;color:#ffeaa6;">'+numberWithCommas((ave_rawmats / ave_r) / ave_prod) * 100+' %'+'</td>');
                        }else{
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffeaa6;">0.00 %</td>');
                        }

                        if (ave_manpower > 0.00 && ave_m > 0){
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffa6c1;">'+numberWithCommas(ave_manpower / ave_m)+'</td>');
                        }else{
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffa6c1;">0.00</td>');
                        }

                        if ((ave_manpower / ave_m) > 0.00 && ave_prod > 0.00){
                            let p_percentage = ((ave_manpower / ave_m) / ave_prod) * 100;
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffeaa6;">' + numberWithCommas(p_percentage.toFixed(2)) + ' %</td>');
                            // html.push('<td style="text-align:right;font-size:1.3em;color:#ffeaa6;">'+numberWithCommas((ave_manpower / ave_m) / ave_prod)+' %'+'</td>');
                        }else{
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffeaa6;">0.00 %</td>');
                        }

                        if (ave_electricity > 0.00 && ave_e > 0){
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffa6c1;">'+numberWithCommas(ave_electricity / ave_e)+'</td>');
                        }else{
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffa6c1;">0.00</td>');
                        }

                        if ((ave_electricity / ave_e) > 0.00 && ave_prod > 0.00){
                            let e_percentage = ((ave_electricity / ave_e) / ave_prod) * 100;
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffeaa6;">' + numberWithCommas(e_percentage.toFixed(2)) + ' %</td>');
                            // html.push('<td style="text-align:right;font-size:1.3em;color:#ffeaa6;">'+numberWithCommas((ave_electricity / ave_e) / ave_prod)+' %'+'</td>');
                        }else{
                            html.push('<td style="text-align:right;font-size:1.1em;color:#ffeaa6;">0.00 %</td>');
                        }
                    html.push('</tr>');                    
                html.push('</tfoot>');

              html.push('</table>');
            html.push('</div>'); 

            $('.tabular_content').html(html.join(''));

            $("#btn-export-tabular").prop('disabled', false);
            $("#btn-print-tabular").prop('disabled', false);

            // Call chart rendering
            EchartsLinesZoomDark.init(dates, productionData, materialsData);
            EchartsScatterBasicDark.init(dates, productionData, materialsData);
        }
    });
}

// STATISTICS Tab - Production Metrics -> below Line Graph
function fetchProductionMetrics() {
    var reptype = $("#report-type").val();
    let date_range = $("#lst_date_range").val();

    let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

    let data_metrics = new FormData();
    data_metrics.append("reptype", reptype);
    data_metrics.append("start_date", start_date);
    data_metrics.append("end_date", end_date);

    $.ajax({
        url: "ajax/production_metrics.ajax.php",
        method: "POST",
        data: data_metrics,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            $(".production_metrics").empty();
            var html = [];

            html.push('<style>');
                html.push('.scrollable-table-wrapper { position: relative; width: 100%; margin: 0 auto; font-size: 1.1em; }');
                html.push('.scrollable-table-header, .scrollable-table-footer { width: 100%; table-layout: fixed; background-color: #2a3141; }');
                html.push('.scrollable-table-footer td { font-weight: bold; }');
                html.push('.scrollable-table-body-container { max-height: 400px; overflow-y: auto; width: 100%; }'); // Ensured the body container is 100% width
                html.push('.scrollable-table-body { width: 100%; table-layout: fixed; }'); // Ensured table-layout: fixed
                html.push('thead th, tfoot td { position: sticky; background-color: #2a3141; z-index: 2; top: 0; }'); // Sticky header
                html.push('.scrollable-table-wrapper table tr td, .scrollable-table-wrapper table tr th { padding-top: 4px; padding-bottom: 4px; }');
                html.push('.scrollable-table-body td, .scrollable-table-header th { text-align: right; }'); // Ensured text-align: right for both header and body
            html.push('</style>');

            html.push('<div class="scrollable-table-wrapper">');

                        // Header Table
            html.push('<table class="table table-bordered scrollable-table-header">');
                html.push('<thead>');
                    html.push('<tr>');
                        html.push('<th style="font-size:0.9em;width:160px;padding-right:5px;">Categories</th>');
                        html.push('<th style="text-align:right;font-size:0.9em;width:85px;padding-right:5px;">Prod Qty</th>');
                        html.push('<th style="text-align:right;font-size:0.9em;width:107px;padding-right:5px;">Prod Value</th>');
                        html.push('<th style="text-align:right;font-size:0.9em;width:85px;padding-right:5px;">Weight</th>');
                        html.push('<th style="text-align:right;font-size:0.9em;width:100px;padding-right:5px;">Mat Qty</th>');
                        html.push('<th style="text-align:right;font-size:0.9em;width:107px;padding-right:5px;">Mat Cost</th>');
                        html.push('<th style="text-align:right;font-size:0.9em;width:80px;padding-right:5px;">Mat %</th>');
                        html.push('<th style="text-align:right;font-size:0.9em;width:107px;padding-right:5px;">Acc Cost</th>');
                        html.push('<th style="text-align:right;font-size:0.9em;width:80px;padding-right:5px;">Acc %</th>');
                        html.push('<th style="text-align:right;font-size:0.9em;width:70px;padding-right:5px;">Head Count</th>');
                        html.push('<th style="text-align:right;font-size:0.9em;width:107px;padding-right:5px;">Man Cost</th>');
                    html.push('</tr>');
                html.push('</thead>');
            html.push('</table>');

            // Scrollable Body
            html.push('<div class="scrollable-table-body-container">');
                html.push('<table class="table table-striped table-bordered scrollable-table-body">');
                    html.push('<tbody>');
                        var total_prod_qty = 0.00;
                        var total_prod_weight = 0.00;
                        var total_prod_value = 0.00;
                        var total_material_qty = 0.00;
                        var total_material_cost = 0.00;
                        var total_accessories_cost = 0.00;
                        var total_head_count = 0.00;
                        var total_manpower_cost = 0.00;
                        var total_average_material = 0.00;
                        var total_average_accessories = 0.00;
                        var counter = 0;
                        var accounter = 0;

                        for (let i = 0; i < answer.length; i++) {
                            let prodmetrics = answer[i];
                            let category = prodmetrics.category.toUpperCase();
                            let production_cost = prodmetrics.production_cost;
                            let production_qty = prodmetrics.production_qty;
                            let production_weight = prodmetrics.production_weight;
                            let excess_cost = Number(prodmetrics.excess_cost);
                            let excess_qty = Number(prodmetrics.excess_qty);
                            let accessories_cost = Number(prodmetrics.accessories_cost);
                            let accessories_qty = Number(prodmetrics.accessories_qty);

                            // Raw Materials Cost without Accessories
                            let material_cost = Number(prodmetrics.material_cost) - (excess_cost + accessories_cost);
                            let material_qty = Number(prodmetrics.material_qty) - (excess_qty + accessories_qty);

                            let head_count = prodmetrics.head_count;
                            let manpower_cost = prodmetrics.manpower_cost;

                            total_prod_qty += Number(production_qty);
                            total_prod_weight += Number(production_weight);
                            total_prod_value += Number(production_cost);
                            total_material_qty += Number(material_qty);
                            total_material_cost += Number(material_cost);
                            total_accessories_cost += Number(accessories_cost);
                            total_head_count += Number(head_count);
                            total_manpower_cost += Number(manpower_cost);

                            html.push('<tr>');
                                html.push('<td ' +
                                    'style="text-align:right; color:#00ffff; width:160px; cursor:pointer;padding-right:5px;font-size:0.9em;" ' +
                                    'onmouseover="this.style.backgroundColor=\'#fccd60\'; this.style.color=\'#303030\'; this.style.fontSize=\'0.9em\'; this.style.fontWeight=\'bold\'" ' +
                                    'onmouseout="this.style.backgroundColor=\'\'; this.style.color=\'#00ffff\'; this.style.fontSize=\'\'; this.style.fontWeight=\'\'" ' +
                                    'onclick="productionDetails(\'' + category + '\')" ' +
                                    '>' + category + '</td>');
                                
                                if (Number(production_qty) > 0.00){    
                                    html.push('<td style="color:#62fca3;font-size:0.8em;text-align:right;width:85px;padding-right:5px;">'+numberWithCommas(production_qty)+'</td>');  
                                    html.push('<td style="color:#62fca3;font-size:0.8em;text-align:right;width:107px;padding-right:5px;">'+numberWithCommas(production_cost)+'</td>');
                                    html.push('<td style="color:orange;font-size:0.8em;text-align:right;width:85px;padding-right:5px;">'+numberWithCommas(production_weight)+'</td>');   
                                }else{
                                    html.push('<td style="color:#ffeaa6;font-size:0.8em;text-align:right;width:85px;padding-right:5px;"></td>');   
                                    html.push('<td style="color:#62fca3;font-size:0.8em;text-align:right;width:107px;padding-right:5px;"></td>');
                                    html.push('<td style="color:orange;font-size:0.8em;text-align:right;width:85px;padding-right:5px;"></td>');                                       
                                }                                 

                                if (Number(material_cost) > 0.00){ 
                                    html.push('<td style="color:#ffa6c1;font-size:0.8em;text-align:right;width:100px;padding-right:5px;">'+numberWithCommas(material_qty)+'</td>');
                                    html.push('<td style="color:#ffa6c1;font-size:0.8em;text-align:right;width:107px;padding-right:5px;">'+numberWithCommas(material_cost)+'</td>');
                                }else{
                                    html.push('<td style="color:#ffa6c1;font-size:0.8em;text-align:right;width:100px;padding-right:5px;"></td>');
                                    html.push('<td style="color:#ffa6c1;font-size:0.8em;text-align:right;width:107px;padding-right:5px;"></td>');
                                }

                                if (Number(production_cost) > 0.00){    
                                    let average_material = ((Number(material_cost) / Number(production_cost)) * 100).toFixed(2);
                                    counter++;
                                    total_average_material = total_average_material + Number(average_material);
                                    // alert(total_average_material);
                                    html.push('<td style="color:powderblue;font-size:0.8em;text-align:right;width:80px;padding-right:5px;">'+numberWithCommas(average_material)+' %</td>');    
                                }else{
                                    html.push('<td style="color:powderblue;font-size:0.8em;text-align:right;width:80px;padding-right:5px;"></td>');                                     
                                } 

                                if (Number(accessories_cost) > 0.00){ 
                                    html.push('<td style="color:#ffa6c1;font-size:0.8em;text-align:right;width:107px;padding-right:5px;">'+numberWithCommas(accessories_cost)+'</td>');
                                }else{
                                    html.push('<td style="color:#ffa6c1;font-size:0.8em;text-align:right;width:107px;padding-right:5px;"></td>');
                                }

                                if (Number(accessories_cost) > 0.00){    
                                    let average_accessories = ((Number(accessories_cost) / Number(production_cost)) * 100).toFixed(2);
                                    accounter++;
                                    total_average_accessories = total_average_accessories + Number(average_accessories);
                                    // alert(total_average_material);
                                    html.push('<td style="color:powderblue;font-size:0.8em;text-align:right;width:80px;padding-right:5px;">'+numberWithCommas(average_accessories)+' %</td>');    
                                }else{
                                    html.push('<td style="color:powderblue;font-size:0.8em;text-align:right;width:80px;padding-right:5px;"></td>');                                     
                                }

                                if (Number(head_count) > 0.00){
                                    html.push('<td style="color:#ffeaa6;font-size:0.8em;text-align:right;width:70px;padding-right:5px;">'+numberWithCommas(head_count)+'</td>');
                                }else{
                                    html.push('<td style="color:#ffeaa6;font-size:0.8em;text-align:right;width:70px;padding-right:5px;"></td>');
                                }
                                
                                if (Number(manpower_cost) > 0.00){  
                                    html.push('<td style="color:#ffeaa6;font-size:0.8em;text-align:right;width:107px;padding-right:5px;">'+numberWithCommas(manpower_cost)+'</td>');
                                }else{
                                    html.push('<td style="color:#ffeaa6;font-size:0.8em;text-align:right;width:107px;padding-right:5px;"></td>');
                                }
                            html.push('</tr>');
                        }
                    html.push('</tbody>');
                    if (counter > 0){
                        var _ave = total_material_cost/total_prod_value * 100;
                    }else{
                        var _ave = 0.00;
                    }

                    if (accounter > 0){
                        var _acc_ave = total_accessories_cost/total_prod_value * 100;
                    }else{
                        var _acc_ave = 0.00;
                    }

                    html.push('<tfoot>');
                        html.push('<tr>');
                            html.push('<td style="color:#a4dcfc;font-size:1.1em;padding-right:5px;">SUB-TOTAL</td>');
                            html.push('<td style="text-align:right;font-size:0.9em;color:#62fca3;padding-right:5px;">'+numberWithCommas(total_prod_qty)+'</td>');
                            html.push('<td style="text-align:right;font-size:0.9em;color:#62fca3;padding-right:5px;">'+numberWithCommas(total_prod_value)+'</td>');
                            html.push('<td style="text-align:right;font-size:0.9em;color:orange;padding-right:5px;">'+numberWithCommas(total_prod_weight)+'</td>');
                            html.push('<td style="text-align:right;font-size:0.9em;color:#ffa6c1;padding-right:5px;">'+numberWithCommas(total_material_qty)+'</td>');
                            html.push('<td style="text-align:right;font-size:0.9em;color:#ffa6c1;padding-right:5px;">'+numberWithCommas(total_material_cost)+'</td>');
                            html.push('<td style="text-align:right;font-size:0.9em;color:powderblue;padding-right:5px;">'+numberWithCommas(_ave.toFixed(2))+' %</td>');
                            html.push('<td style="text-align:right;font-size:0.9em;color:#ffa6c1;padding-right:5px;">'+numberWithCommas(total_accessories_cost)+'</td>');
                            html.push('<td style="text-align:right;font-size:0.9em;color:powderblue;padding-right:5px;">'+numberWithCommas(_acc_ave.toFixed(2))+' %</td>');
                            html.push('<td style="text-align:right;font-size:0.9em;color:#ffeaa6;padding-right:5px;">'+numberWithCommas(total_head_count)+'</td>');
                            html.push('<td style="text-align:right;font-size:0.9em;color:#ffeaa6;padding-right:5px;">'+numberWithCommas(total_manpower_cost)+'</td>');
                        html.push('</tr>');
                    html.push('</tfoot>');

                html.push('</table>');
            html.push('</div>'); 

            $('.production_metrics').html(html.join(''));                    
        }
    });
}

function productionCostTrail(date) {
    const [month, day, year] = date.split('/');
    let trans_date = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;

    $('#modal-production-cost').modal('show');
    $('#trans-date').text('Production Date : ' + date.toUpperCase());

    let data = new FormData();
    data.append("trans_date", trans_date);
    $.ajax({
        url: "ajax/production_cost_trail.ajax.php",
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            $(".materialCostTrailTable").DataTable().clear();
            var total_amount = 0.00;
            for (var i = 0; i < answer.length; i++) {
                let production = answer[i];
                let trans_type = production.trans_type;
                let machinedesc = production.machinedesc;

                let qty = parseInt(production.qty);
                let formattedQty = '';
                // if (trans_type == 'Excess') {
                //     formattedQty = `<span style="color:#fa7898; text-align:right; display:block;">(${numberWithCommasNoDecimal(Math.abs(qty))})</span>`;
                // } else {
                //     formattedQty = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommasNoDecimal(qty)}</span>`;
                // }

                formattedQty = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommasNoDecimal(qty)}</span>`;

                let tamount = Number(production.tamount);
                let formattedCost = '';
                // if (trans_type == 'Excess') {
                //     total_amount -= tamount;
                //     formattedCost = `<span style="color:#fa7898; text-align:right; display:block;">(${numberWithCommas(Math.abs(tamount))})</span>`;
                // } else {
                //     total_amount += tamount;
                //     formattedCost = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommas(tamount)}</span>`;
                // }

                total_amount += tamount;
                formattedCost = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommas(tamount)}</span>`;
                pc.row.add([trans_type, machinedesc, formattedQty, formattedCost]);
            }
            $('.productionCostTrailTable tfoot th').eq(3).html(
                    `<span style="font-weight:bold; color:#fcd772; display:block; text-align:right;font-size:1.2em;">${numberWithCommas(total_amount.toFixed(2))}</span>`
                );
            pc.draw();
        }
    });
}

function productionDetails(prod_category){
    let category = prod_category;
    let date_range = $("#lst_date_range").val();

    let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

    // Convert dates to MM/DD/YYYY format
    let start_date_formatted = start_date.substring(5, 7) + '/' + start_date.substring(8, 10) + '/' + start_date.substring(0, 4);
    let end_date_formatted = end_date.substring(5, 7) + '/' + end_date.substring(8, 10) + '/' + end_date.substring(0, 4);

    // Concatenate category with date range
    let display_text;
    if (start_date_formatted === end_date_formatted) {
        // Same start and end date
        display_text = `${category.toUpperCase()} [${start_date_formatted}]`;
    } else {
        // Different start and end dates
        display_text = `${category.toUpperCase()} [${start_date_formatted} - ${end_date_formatted}]`;
    }

    // Show modal and update the production category with the formatted text
    $('#modal-production-details').modal('show');
    $('#trans_prod_info').text(display_text);

    // $('#modal-production-details').modal('show');
    // $('#trans_prod_info').text(category.toUpperCase());

    let data_details = new FormData();
    data_details.append("category", category);
    data_details.append("start_date", start_date);
    data_details.append("end_date", end_date);

    // var pd = $(".productionDetailsTable").DataTable();

    $.ajax({
        url: "ajax/production_details.ajax.php",
        method: "POST",
        data: data_details,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            //$(".productionDetailsTable").DataTable().clear();
            pd.clear();
            var total_value = 0.00;
            var total_qty = 0.00;
            var total_weight = 0.00;
            for (var i = 0; i < answer.length; i++) {
                let production = answer[i];
                let catdescription = production.catdescription;

                let pdesc = production.pdesc;
                let formattedPdesc = "";
                formattedPdesc = `<span style="color:#aedefc; text-align:left; display:block;">${pdesc}</span>`;

                let wmeas = production.wmeas;

                let pweight = Number(production.pweight);
                let formttedPweight = "";
                formttedPweight = `<span style="color:orange; text-align:right; display:block;">${numberWithCommas(pweight) + " " + wmeas}</span>`;

                let qty = Number(production.qty);
                let formattedQty = '';
                total_qty += qty;
                formattedQty = `<span style="color:greenyellow; text-align:right; display:block;">${numberWithCommasNoDecimal(qty)}</span>`;

                let tamount = Number(production.tamount);
                let formattedValue = '';
                total_value += tamount;
                formattedValue = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommas(tamount)}</span>`;

                let formattedTotalWeight = '';
                let prod_weight = qty * pweight;
                total_weight += prod_weight;
                formattedTotalWeight = `<span style="color:greenyellow; text-align:right; display:block;">${numberWithCommas(prod_weight)}</span>`;

                pd.row.add([formattedPdesc,formattedValue,formattedQty,formttedPweight,formattedTotalWeight]);
            }
            $('.productionDetailsTable tfoot').html(`
            <tr>
                <th style="border:4px solid white;text-align:right; color:white;">TOTAL PRODUCTION</th>
                <th style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;font-weight:bold; color:#fcd772; text-align:right; font-size:1.2em;">
                ${numberWithCommas(total_value.toFixed(2))}
                </th>
                <th style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;font-weight:bold; color:#fcd772; text-align:right; font-size:1.2em;">
                ${numberWithCommas(total_qty.toFixed(2))}
                </th>
                <th colspan="2" style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;font-weight:bold; color:#fcd772; text-align:right; font-size:1.2em;">
                ${numberWithCommas(total_weight.toFixed(2))}
                </th>
            </tr>
            `);
            pd.draw();
        }
    });
}

function materialCostTrail(date) {
    const [month, day, year] = date.split('/');
    let trans_date = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    
    $('#modal-material-cost').modal('show');
    $('#trans_date').text('Entry Date : ' + date.toUpperCase());

    let data = new FormData();
    data.append("trans_date", trans_date);
    $.ajax({
        url: "ajax/material_cost_trail.ajax.php",
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            $(".materialCostTrailTable").DataTable().clear();
            var total_amount = 0.00;
            var atotal_amount = 0.00;
            for (var i = 0; i < answer.length; i++) {
                let matcost = answer[i];
                let trans_type = matcost.trans_type;
                let machinedesc = matcost.machinedesc;

                let qty = parseInt(matcost.qty);
                let formattedQty = '';
                if (trans_type == 'Excess') {
                    formattedQty = `<span style="color:#fa7898; text-align:right; display:block;">(${numberWithCommasNoDecimal(Math.abs(qty))})</span>`;
                } else {
                    formattedQty = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommasNoDecimal(qty)}</span>`;
                }

                let tamount = Number(matcost.tamount);
                let formattedCost = '';
                if (trans_type == 'Excess') {
                    total_amount -= tamount;
                    formattedCost = `<span style="color:#fa7898; text-align:right; display:block;">(${numberWithCommas(Math.abs(tamount))})</span>`;
                } else {
                    total_amount += tamount;
                    formattedCost = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommas(tamount)}</span>`;
                }

                let aqty = parseInt(matcost.aqty);
                let formattedAqty = '';
                if (aqty != 0){
                    if (trans_type == 'Excess') {
                        formattedAqty = `<span style="color:#fa7898; text-align:right; display:block;">(${numberWithCommasNoDecimal(Math.abs(aqty))})</span>`;
                    } else {
                        formattedAqty = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommasNoDecimal(aqty)}</span>`;
                    }  
                }
                
                let atamount = Number(matcost.atamount);
                let formattedAcost = '';
                if (atamount != 0.00){
                    if (trans_type == 'Excess') {
                        atotal_amount -= atamount;
                        formattedAcost = `<span style="color:#fa7898; text-align:right; display:block;">(${numberWithCommas(Math.abs(atamount))})</span>`;
                    } else {
                        atotal_amount += atamount;
                        formattedAcost = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommas(atamount)}</span>`;
                    }    
                }            
                mc.row.add([trans_type, machinedesc, formattedQty, formattedCost, formattedAqty, formattedAcost]);
            }
            // $('.materialCostTrailTable tfoot th').eq(3).html(
            //         `<span style="font-weight:bold; color:#fcd772; display:block; text-align:right;font-size:1.2em;">${numberWithCommas(total_amount.toFixed(2))}</span>`
            //     );
            $('.materialCostTrailTable tfoot').html(`
            <tr>
                <th colspan="2" style="border:4px solid white;text-align:right; color:#fcd772;">TOTAL MATERIALS COST</th>
                <th colspan="2" style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;font-weight:bold; color:#fcd772; text-align:right; font-size:1.2em;">
                ${numberWithCommas(total_amount.toFixed(2))}
                </th>
                <th colspan="2" style="border-right:4px solid white;border-bottom:4px solid white;border-top:4px solid white;font-weight:bold; color:#fcd772; text-align:right; font-size:1.2em;">
                ${numberWithCommas(atotal_amount.toFixed(2))}
                </th>
            </tr>
            `);
            mc.draw();
        }
    });    
}

function numberWithCommasNoDecimal(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Bind the function to keyup event
$('#tns-search').on('keyup', filterProductInventoryTable);

$('#lbl-tns-search').on('click', function () {
    $('#tns-search').val('');
    filterProductInventoryTable();
});  

function filterProductInventoryTable() {
    let keyword = $('#tns-search').val().toLowerCase().trim();
    $('.productInventoryTable tbody tr').each(function() {
        let productDesc = $(this).find('td:first').text().toLowerCase();

        if (productDesc.indexOf(keyword) > -1) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function load_inventory_periods() {
    let date_range = $("#lst_date_range").val();
    //let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
    
    var data = new FormData();
    data.append("end_date", end_date);
    $.ajax({
        url: "ajax/inventory_periods.ajax.php",
        method: "POST",
        data: data, 
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            $(".inventoryPeriodsTable").DataTable().clear();
            var ip = $(".inventoryPeriodsTable").DataTable(); // ensure `ip` is defined here if it's not globally

            var num_rec = answer.length - 1;

            var inventoryfrom = '';
            var inventoryfromnextday = '';
            var inventoryto = '';
            var inventorytonextday = '';

            for (var i = 0; i < num_rec; i++) {
                inventoryfrom = answer[i].invdate;
                inventoryto = answer[i + 1].invdate;

                formatted_inventoryfrom = answer[i].formatted_invdate;
                formatted_inventoryto = answer[i + 1].formatted_invdate;

                let button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnInvPeriod' inventoryfrom='" + inventoryfrom + "' inventoryto='" + inventoryto + "' formatted_inventoryfrom='" + formatted_inventoryfrom + "' formatted_inventoryto='" + formatted_inventoryto + "'><i class='icon-check'></i></button></td>";
                ip.row.add([formatted_inventoryfrom, formatted_inventoryto, button]);
            }

            ip.draw();
            // Last pair is color green [From - Current or Selected Date]
            $('.inventoryPeriodsTable tbody tr:last-child').attr('style', 'color: #11faac;');
        }
    });
}   

function generateAssessment(){
    let date_range = $("#lst_date_range").val();
    //let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
    
    var data = new FormData();
    data.append("end_date", end_date);
    $.ajax({
        url: "ajax/inventory_periods.ajax.php",
        method: "POST",
        data: data, 
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            var num_rec = answer.length - 1;

            var inventoryfrom = '';
            var inventoryto = '';

            for (var i = 0; i < num_rec; i++) {
                inventoryfrom = answer[i].invdate;
                inventoryto = answer[i + 1].invdate;

                formatted_inventoryfrom = answer[i].formatted_invdate;
                formatted_inventoryto = answer[i + 1].formatted_invdate;
            }
            let categorycode = $("#sel-categorycode").val();
            let tier = $('#chk-tier').is(':checked');
            assessmentMatrix(inventoryfrom, inventoryto, categorycode, tier);
        }
    });    
}

function exportToExcel() {
    var location = 'data:application/vnd.ms-excel;base64,';
    var excelTemplate = '<html> ' +
    '<head> ' +
    '<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/> ' +
    '</head> ' +
    '<body> ' +
    document.getElementById("overall_assessment").innerHTML +
    '</body> ' +
    '</html>'
    window.location.href = location + window.btoa(excelTemplate);
}

// ---------------- Materials Usage ----------------
$('#tns-search-usage').on('keyup', filterUsageTable);

$('#lbl-tns-search-usage').on('click', function () {
    $('#tns-search-usage').val('');
    filterUsageTable();
}); 

function filterUsageTable() {
    let keyword = $('#tns-search-usage').val().toLowerCase().trim();
    $('.materialsUsageTable tbody tr').each(function() {
        let productDesc = $(this).find('td:first').text().toLowerCase();

        if (productDesc.indexOf(keyword) > -1) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

// USAGE TAB
function generateUsageMatrix(){
    $("#tns-search-usage").val('');
    let date_range = $("#lst_date_range").val();
    let usagefrom = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    let usageto = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
    let categorycode = $("#sel-categorycode-usage").val();
    assessmentUsage(usagefrom, usageto, categorycode);
}

function assessmentUsage(usagefrom, usageto, categorycode) {
    let start_date = usagefrom;
    let end_date = usageto;

    // Prepare the data for the AJAX request
    let data_usage = new FormData();
    data_usage.append("start_date", start_date);
    data_usage.append("end_date", end_date);
    data_usage.append("categorycode", categorycode);

    $.ajax({
        url: "ajax/usage_assessment.ajax.php",
        method: "POST",
        data: data_usage,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(answer) {
            var html = [];
            html.push('<div class="table-responsive" style="margin-top:-10px;margin-bottom:-28px;margin-left:18px;margin-right:18px;overflow-y: auto; overflow-x: auto; max-height: 500px;">');
                html.push('<table class="table table-hover table-striped mx-auto w-auto materialsUsageTable" style="border-collapse: separate; border-spacing: 0;">');
                    html.push('<thead>');
                        html.push('<tr>');
                            html.push('<th class="table_head_left_fixed" style="position: sticky; left: 0; z-index: 10; background-color: #1f1f1f; padding-top:8px; padding-bottom:8px; min-width:400px;font-size:1.1em;">SKU DESCRIPTION</th>');
                            html.push('<th class="table_head_left_fixed" style="position: sticky; left: 0; z-index: 10; background-color: #1f1f1f; padding-top:8px;padding-bottom:8px; min-width:400px;font-size:1.1em;">Category</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#7FFF00;border-right:3px solid yellow;border-left:3px solid yellow;">Used</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#fac189;border-right:3px solid yellow;">Ending</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#aedefc;border-right:3px solid yellow;">Total Used</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:90px;color:#aedefc;border-right:3px solid yellow;">Peso Value</th>');
                        html.push('</tr>');
                    html.push('</thead>');
                    var total_peso_value = 0.00;
                    for (var i = 0; i < answer.length; i++) {
                        let matrix = answer[i];

                        let p_desc = matrix.pdesc;
                        let meas2 = matrix.meas2.toUpperCase();
                        let pdesc = p_desc + ' (' + meas2 + ')';

                        let itemid = matrix.itemid;
                        let categorycode = matrix.categorycode;

                        let used = parseInt(matrix.used_materials);
                        let ending = parseInt(matrix.ending);

                        let total_used = parseInt(matrix.total_used);
                        let formattedTotalUsed = total_used < 0 
                            ? `(${numberWithCommasNoDecimal(Math.abs(total_used))})` 
                            : numberWithCommasNoDecimal(total_used);

                        let used_amount = Number(matrix.used_amount);
                        let excess_amount = Number(matrix.excess_amount);

                        let peso_value = used_amount - excess_amount;
                        let formattedPesoValue = peso_value < 0 
                            ? `(${numberWithCommas(Math.abs(peso_value))})` 
                            : numberWithCommas(peso_value);

                        total_peso_value += peso_value;    

                        html.push('<tr>');
                            html.push('<td class="pdesc-cell" data-itemid="' + itemid + '" data-pdesc="' + pdesc + '" style="cursor:pointer;position: sticky; left: 0; z-index: 5; background-color: #1f1f1f; border-right: 1px solid white; font-size:1.3em; color:lightyellow; position:relative;">' + pdesc + '</td>');
                            html.push('<td style="position: sticky; left: 0; z-index: 5; background-color: #1f1f1f; border-right: 1px solid white; font-size:1.1em; color:lightyellow;">' + categorycode + '</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;border-left:1px solid white;color:#7FFF00;font-size:1.1em;">' + (used !== 0 ? numberWithCommasNoDecimal(used) : '') + '</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:#fac189;font-size:1.1em;">' + (ending !== 0 ? numberWithCommasNoDecimal(ending) : '') + '</td>');

                            if (total_used < 0){
                                html.push('<td style="text-align:right;color:#fa7898;font-size:1.1em;border-right:4px solid white;">' + formattedTotalUsed + '</td>');
                            } else {
                                html.push('<td style="text-align:right;color:#aedefc;font-size:1.1em;border-right:4px solid white;">' + formattedTotalUsed + '</td>');
                            }

                            if (peso_value < 0){
                                html.push('<td style="text-align:right;color:#fa7898;font-size:1.1em;border-right:4px solid white;">' + formattedPesoValue + '</td>');
                            } else {
                                html.push('<td style="text-align:right;color:#aedefc;font-size:1.1em;border-right:4px solid white;">' + formattedPesoValue + '</td>');
                            }
                        html.push('</tr>');
                    }

                    html.push('<tr>');
                        html.push('<td colspan="4" style="text-align:right;color:gold;font-size:1.3em;border:4px solid white;">TOTAL MATERIALS COST</td>');
                        html.push('<td colspan="4" style="text-align:right;color:gold;font-size:1.3em;border:4px solid white;"></td>');
                        html.push('<td style="text-align:right;color:gold;font-size:1.3em;border-right:4px solid white;border-top:4px solid white;border-bottom:4px solid white;">' + numberWithCommas(total_peso_value) + '</td>');
                    html.push('</tr>');
                html.push('</table>');
            html.push('</div>');

            $('.usage_assessment').html(html.join(''));

            // Table styling
            $('<style>.materialsUsageTable tbody td { padding-top: 2px; padding-bottom: 2px; }</style>').appendTo('head');
            $('<style>.materialsUsageTable td:nth-child(2), .materialsUsageTable th:nth-child(2) { display: none; }</style>').appendTo('head');

            // Add highlight style for SKU DESCRIPTION without shadow
            $(`<style>
                .pdesc-cell {
                    background-image: linear-gradient(120deg, #1f1f1f 0%, #333 100%);
                    transition: background 0.4s ease-in-out, transform 0.2s ease;
                }
                .pdesc-cell:hover {
                    background-color: #292929 !important;
                    background-image: linear-gradient(120deg, #3e3e3e 0%, #5e5e5e 100%);
                    color: #ffffcc !important;
                    font-weight: bold;
                    transform: scale(1.02);
                    cursor: pointer;
                }
                .pdesc-cell:active {
                    animation: clickFlash 0.3s ease;
                }
                @keyframes clickFlash {
                    0% { background-color: #ffff99; color: black; }
                    50% { background-color: #ffee58; color: #1f1f1f; }
                    100% { background-color: #1f1f1f; color: lightyellow; }
                }
            </style>`).appendTo('head');

            // Enable buttons
            $("#btn-export-usage").prop('disabled', false);
            $("#tns-search-usage").prop('disabled', false);
            $('#tns-search-usage').focus();

            // Click event - Raw Material
            $('.usage_assessment').on('click', '.pdesc-cell', function() {
                let itemid = $(this).data('itemid');
                let pdesc = $(this).data('pdesc');
                let start_date = usagefrom;
                let end_date = usageto;
                $('#raw_material').text(pdesc.toUpperCase());
                $('#modal-summary-usage').modal('show');
                var summary_usage = new FormData();
                summary_usage.append("itemid", itemid);
                summary_usage.append("start_date", start_date);
                summary_usage.append("end_date", end_date);
                $.ajax({
                    url:"ajax/usage_summary.ajax.php",
                    method: "POST",
                    data: summary_usage,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(answer){
                        $(".summaryUsageTable").DataTable().clear();
                        var total_peso_amount = 0.00;
                        for(var i = 0; i < answer.length; i++) {
                            let usage = answer[i];

                            let u_date = usage.udate;
                            let udate = u_date.split("-");
                            udate = udate[1] + "/" + udate[2] + "/" + udate[0];    

                            // let used_materials = Number(usage.used_materials) > 0 ? numberWithCommasNoDecimal(usage.used_materials) : '';
                            let used_materials = Number(usage.used_materials) > 0 
                                ? `<span style="color:#7FFF00;text-align:right; display:block;">${numberWithCommasNoDecimal(usage.used_materials)}</span>` 
                                : '';

                            // let ending = parseInt(usage.ending) > 0 ? numberWithCommasNoDecimal(parseInt(usage.ending)) : ''; 
                            let ending = Number(usage.ending) > 0 
                                ? `<span style="color:#fac189;text-align:right; display:block;">${numberWithCommasNoDecimal(usage.ending)}</span>` 
                                : '';
                            
                            let total_used = parseInt(usage.total_used);
                            let formattedTotalUsed = '';
                            if (total_used != 0){
                                if (total_used < 0) {
                                    formattedTotalUsed = `<span style="color:#fa7898; text-align:right; display:block;">(${numberWithCommasNoDecimal(Math.abs(total_used))})</span>`;
                                } else {
                                    formattedTotalUsed = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommasNoDecimal(total_used)}</span>`;
                                }
                            }

                            let used_amount = Number(usage.used_amount);
                            let excess_amount = Number(usage.excess_amount);

                            let peso_value = used_amount - excess_amount;
                            total_peso_amount += peso_value;
                            let formattedPesoValue = '';
                            if (peso_value != 0){
                                if (peso_value < 0) {
                                    formattedPesoValue = `<span style="color:#fa7898; text-align:right; display:block;">(${numberWithCommas(Math.abs(peso_value))})</span>`;
                                } else {
                                    formattedPesoValue = `<span style="color:#aedefc; text-align:right; display:block;">${numberWithCommas(peso_value)}</span>`;
                                }
                            }

                            sut.row.add([udate, used_materials, ending, formattedTotalUsed, formattedPesoValue]);
                        }
                        // $("#total_peso_amount").val(numberWithCommas(total_peso_amount.toFixed(2)));
                        $('.summaryUsageTable tfoot th').eq(3).html(
                                `<span style="font-weight:bold; color:#fcd772; display:block; text-align:right;font-size:1.2em;">${numberWithCommas(total_peso_amount.toFixed(2))}</span>`
                            );
                        sut.draw();
                    }
                });
            });
        }
    });
}

function exportToExcelUsage() {
    var location = 'data:application/vnd.ms-excel;base64,';
    var excelTemplate = '<html> ' +
    '<head> ' +
    '<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/> ' +
    '</head> ' +
    '<body> ' +
    document.getElementById("usage_assessment").innerHTML +
    '</body> ' +
    '</html>'
    window.location.href = location + window.btoa(excelTemplate);
}

function exportToExcelTabular() {
    var location = 'data:application/vnd.ms-excel;base64,';
    var excelTemplate = '<html> ' +
    '<head> ' +
    '<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/> ' +
    '</head> ' +
    '<body> ' +
    document.getElementById("tabular_content").innerHTML +
    '</body> ' +
    '</html>'
    window.location.href = location + window.btoa(excelTemplate);
}