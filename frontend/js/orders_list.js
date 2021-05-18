$('.scroller').click(function() {
	let scrollTo = $(this).attr('tab');
	let off = $(scrollTo).offset();
	let scrollTop = off.top - window.innerHeight / 4;
	if (scrollTop < 0) scrollTop = 0;

	window.scroll({
		top: scrollTop,
		left: 0,
		behavior: 'smooth'
	});
});
let spyScrollTop = 0;
setInterval(function() {
	let last_in_sight = false;
	$('.tabs-container .tab').each(function(i, v) {
		let _top = $(v).offset().top - window.innerHeight / 4;
		if (_top <= spyScrollTop) {
			last_in_sight = v;
		}
	});
	if (last_in_sight) {
		$('.scroller').removeClass('visible');
		let attr = '#' + last_in_sight.id;
		$('.scroller[tab="' + attr + '"]').addClass('visible');
	}
}, 250);
$(window).scroll(function(ev) {
	spyScrollTop = $(window).scrollTop();
});

$('.js-orders-list-item').click(function() {
	$(this).next().find('.collapse').collapse('toggle');
});

function drawCharts() {

	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Giorno');
	data.addColumn('number', 'Euro');
	data.addRows(chart_weekdays_data);

	// Set chart options
	var options = {
		title: 'Vendite nella settimana',
		width: 500,
		height: 300
	};

	// Instantiate and draw our chart, passing in some options.
	var chart_weekdays = new google.visualization.BarChart(document.getElementById('chart_weekdays'));
	chart_weekdays.draw(data, options);

	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Mese');
	data.addColumn('number', 'Euro');
	data.addRows(chart_months_data);

	// Set chart options
	var options = {
		title: 'Vendite nell\'anno',
		width: 500,
		height: 300
	};

	// Instantiate and draw our chart, passing in some options.
	var chart_months = new google.visualization.BarChart(document.getElementById('chart_months'));
	chart_months.draw(data, options);

	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Servizio');
	data.addColumn('number', 'Euro');
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

	// Instantiate and draw our chart, passing in some options.
	var chart_payment_type = new google.visualization.BarChart(document.getElementById('chart_payment_type'));
	chart_payment_type.draw(data, options);

	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Servizio');
	data.addColumn('number', 'Euro');
	data.addRows(chart_payment_type_data);

	// Set chart options
	var options = {
		title: 'Income by order type',
		width: 500,
		height: 300
	};

	// Instantiate and draw our chart, passing in some options.
	var chart_payment_type = new google.visualization.PieChart(document.getElementById('chart_payment_type'));
	chart_payment_type.draw(data, options);
}

google.charts.load('current', {
	packages: ['corechart']
});
google.charts.setOnLoadCallback(drawCharts);
