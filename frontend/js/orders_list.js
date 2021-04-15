$('.js-orders-list-item').click(function() {
	$(this).next().find('.collapse').collapse('toggle');
});

function drawCharts() {

	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Dimension');
	data.addColumn('number', 'Metric');
	data.addRows(chart_weekdays_data);

	// Set chart options
	var options = {
		title: 'How much income over the week',
		width: 500,
		height: 300
	};

	// Instantiate and draw our chart, passing in some options.
	var chart_weekdays = new google.visualization.BarChart(document.getElementById('chart_weekdays'));
	chart_weekdays.draw(data, options);

	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Dimension');
	data.addColumn('number', 'Metric');
	data.addRows(chart_delivery_type_data);

	// Set chart options
	var options = {
		title: 'Income by order type',
		width: 500,
		height: 300
	};

	// Instantiate and draw our chart, passing in some options.
	var chart_delivery_type = new google.visualization.PieChart(document.getElementById('chart_delivery_type'));
	chart_delivery_type.draw(data, options);
}

google.charts.load('current', {
	packages: ['corechart']
});
google.charts.setOnLoadCallback(drawCharts);
