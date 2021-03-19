let req_sent = 0, last_accepted = 0;

let customer_el = $('#resultsFound .info-cliente').remove().removeAttr('hidden');

let sendRequest = function(search) {
	req_sent++;
	$.post(site_url + 'customers/find', {
		string: search,
		uid: req_sent
	}).done(function(data) {
		try {
			let response = JSON.parse(data);
			if ('uid' in response) {
				if (last_accepted < response.uid) {
					last_accepted = response.uid;

					$('#resultsFound').empty();

					for (let i in response.results) {
						customer_found = customer_el.clone();
						customer_found.data('id', response.results[i].id_customer);
						customer_found.find('.nome-cliente').text(response.results[i].name);
						customer_found.find('.indirizzo-cliente').text(response.results[i].address);
						customer_found.find('.telefono-cliente').text(response.results[i].telephone);
						$('#resultsFound').append(customer_found);
					}
				}
			}
		} catch(e) {
			console.log(e);
		}
	});
};

let existing_timeout = false;
$('#finder').on('input', function() {
	let str_search = $(this).val();
	clearTimeout(existing_timeout);
	existing_timeout = setTimeout(function() {
		sendRequest(str_search);
	}, 40);
});

$('#addCustomer').submit(function(e) {
	e.preventDefault();

	let self = this;

	$.post($(self).attr('action'), {
		name: $(self).find('[name="name"]').val(),
		telephone: $(self).find('[name="telephone"]').val(),
		address: $(self).find('[name="address"]').val(),
	}).always(function(data) {
		try {
			let response = JSON.parse(data);
			if ('created' in response || !response.created) {
				if ('errors' in response) {
					console.log(response.errors);
				} else {
					console.log('created.');
				}
			}
		} catch(e) {
			console.log(e);
		}
		$('#addCustomerModal').css('display', 'none');
	});

	return false;
});

function openNewCustomerModal() {
	$('#addCustomerModal').css('display', 'block');
	$('#addCustomerModal input[name="name"]').val($('#finder').val());
	$('#addCustomerModal input[name="name"]').focus();
}

function openNewOrderModal() {
	$('#addOrderModal').css('display', 'block');
	$('#addOrderModal input[name="name"]').val($('#finder').val());
	$('#addOrderModal input[name="name"]').focus();
}

$('#toPrepping').click(function() {
	$('#toPrepping').addClass('orange-700');
	$('#toPrepping').removeClass('orange-50');

	$('#pendingOrders').attr('hidden', null);
	$('#sentOrders').attr('hidden', '');

	$('#toDelivering').addClass('light-blue-50');
	$('#toDelivering').removeClass('light-blue-200');
});

$('#toPrepping').trigger('click');


$('#toDelivering').click(function() {
	$('#toDelivering').removeClass('light-blue-50');
	$('#toDelivering').addClass('light-blue-200');

	$('#pendingOrders').attr('hidden', '');
	$('#sentOrders').attr('hidden', null);

	$('#toPrepping').addClass('orange-50');
	$('#toPrepping').removeClass('orange-700');
});

let order;

let orderTabs = {
	menu: function() {
		$('#pizzeContext').removeClass('hidden');
		$('#pizzeComponent').removeClass('hidden');
	},
	consegna: function() {
		$('#menuContext').removeClass('hidden');
		$('#menuComponent').removeClass('hidden');
	}
}

function order_init(tab = false) {
	$('[order-component]').addClass('hidden');
	$('[order-context]').addClass('hidden');
	$('.tabs-container').find('.tab').removeClass('selected');

	if (order.last_pizza_category) {
		select_category(order.last_pizza_category);
	}

	if (!tab) {
		tab = $('.tab[data-tab="consegna"]');
	}
	$(tab).addClass('selected');
	let tab_function = $(tab).data('tab');
	orderTabs[tab_function]();
}

function order_reset() {
	$('[name="name"]').val('');
	$('[name="address"]').val('');
	$('[name="telephone"]').val('');
	order = {
		rows: [],
		last_pizza_category: $('#elencoPiatti .piatto').first().data('elenco'),
	};

	order_init();
}

function select_category(category = false) {
	if (category) {
		order.last_pizza_category = category;
		$('#elencoPiatti .piatto').addClass('hidden');
		$('#elencoPiatti .piatto[data-elenco="' + category + '"]').removeClass('hidden');
	}
}

order_reset();

$('.tabs-container .tab').click(function() {
	order_init(this);
});

$('#categorieContainer .categoria').click(function() {
	let categoria = $(this).data('category');
	select_category(categoria);
});

function select_customer(el) {
	// open modal to create order
	let id_customer = $(el).data('id');
	$('#addOrderModal [name="id_customer"]').val(id_customer);

	openNewOrderModal();
}

function select_order(el) {
	// open modal to view/edit order

}
