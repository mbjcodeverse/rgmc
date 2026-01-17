document.addEventListener('DOMContentLoaded', function() {

	// Hide report type initially (Machineries tab)
	$("ul.nav-tabs-bottom li:nth-child(4)").hide();

	// Initialize date range picker for tasks tab
	$('#tsk_date_range').daterangepicker({
		ranges: {
			'All': [moment('2025-09-01'), moment()],
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(30, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'months').startOf('month'), moment().subtract(1, 'months').endOf('month')]
		},
		startDate: moment('2025-09-01'),
		endDate: moment(),
		minDate: moment('2025-09-01')
	});

    $('#cb-buildingcode, #cb-classcode, #cb-machstatus, #tsk_date_range').on("change", function(){
        generateMachineUptimeDowntimeTrend();
    }); 

    function generateMachineUptimeDowntimeTrend(){
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
					const a = answer[i];
					const machinedesc = a.machinedesc;

                    const date_reported = a.datereported;
                    // Convert 'YYYY-MM-DD' → 'MM/DD/YY'
                    const formattedDate = new Date(date_reported);
                    const mm = String(formattedDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(formattedDate.getDate()).padStart(2, '0');
                    const yy = String(formattedDate.getFullYear()).slice(-2);
                    const datereported = `${mm}/${dd}/${yy}`;

					const green_line = Number(a.green_line);
					const redline = Number(a.redline);

					const safeId = machinedesc.replace(/\s+/g, '_').replace(/[^\w\-]/g, '');
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

	// ==========================
	// TASKS TAB CLICK HANDLER
	// ==========================
	$('li.cur-tasks').click(function() {
        generateMachineUptimeDowntimeTrend();
	});    

	// Label click to reset filters
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

// ==============================
// ECharts Line Renderer Function
// ==============================
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