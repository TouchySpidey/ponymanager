let ghostPizzaAddition = $('#ghostPizzaAddition').remove().removeAttr('id');
let ghostPizzaIngredient = $('#ghostPizzaIngredient').remove().removeAttr('id');
let ghostPizzaModification = $('#ghostPizzaModification').remove().removeAttr('id');
// in questo ordine
let ghostOrderItem = $('#ghostOrderItem').remove().removeAttr('id');
let req_sent = 0, last_accepted = 0;

let customer_el = $('#resultsFound .info-cliente').remove().removeAttr('hidden');

let customers_cache = {};

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

					customers_cache = {};

					if (response.results.length) {

						for (let i in response.results) {
							customer_found = customer_el.clone();
							customer_found.data('id', response.results[i].id_customer);
							customer_found.find('.nome-cliente').text(response.results[i].name);
							customer_found.find('.indirizzo-cliente').text(response.results[i].address);
							customer_found.find('.telefono-cliente').text(response.results[i].telephone);
							$('#resultsFound').append(customer_found);
							customers_cache[response.results[i].id_customer] = response.results[i];
						}

						showCustomers();

					} else {

						hideCustomers();

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

$('#hideCustomers').click(hideCustomers);

function showCustomers() {
	if ($('#resultsFound').children().length) {
		// non ha senso nascondere la timetable per mostrare un set vuoto
		$('#customersBox').show();
		$('#timetable').hide();
	}
}
function hideCustomers() {
	$('#customersBox').hide();
	$('#timetable').show();
}

function showPizzaToolbar() {
	$('#pizzeContext').show();
}

function hidePizzaToolbar() {
	$('#pizzeContext').hide();
}

function openNewCustomerModal() {
	$('#addCustomerModal').css('display', 'block');
	$('#addCustomerModal input[name="name"]').val($('#finder').val());
	$('#addCustomerModal input[name="name"]').focus();
}

function openNewOrderModal() {
	$('#addOrderModal').css('display', 'block');
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

let order, last_row = 0;

let orderTabs = {
	menu: function() {
		$('#pizzeComponent').show();
	},
	consegna: function() {
		$('#menuContext').show();
		$('#menuComponent').show();
	}
}

function order_init(tab = false) {
	$('[order-component]').hide();
	$('[order-context]').hide();
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
	$('#overwriteCustomer').hide();
	order = {
		rows: [],
		last_pizza_category: $('#elencoPiatti .piatto').first().data('elenco'),
		sconto: {
			abs: true,
			val: 5,
		},
	};

	order_init();
}

function select_category(category = false) {
	if (category) {
		order.last_pizza_category = category;
		$('#categorieContainer .categoria').removeClass('selected');
		$('#categorieContainer .categoria[data-category="' + category + '"]').addClass('selected');
		$('#elencoPiatti .piatto').hide();
		$('#elencoPiatti .piatto[data-elenco="' + category + '"]').show();
	}
}

function select_ingredients_category(category = false) {
	if (category) {
		$('#categorieIngredientiContainer .categoria').removeClass('selected');
		$('#categorieIngredientiContainer .categoria[data-category="' + category + '"]').addClass('selected');
		$('#elencoIngredienti .ingrediente').hide();
		$('#elencoIngredienti .ingrediente[data-elenco="' + category + '"]').show();
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

$('#categorieIngredientiContainer .categoria').click(function() {
	let categoria = $(this).data('category');
	select_ingredients_category(categoria);
});

function addPizzaToOrder(id_pizza, order_data = false) {
	let order_row = {};
	let pizza = pizzas[id_pizza];
	if (order_data) {
		order_row = order_data;
	} else {
		order_row = {
			piatto: pizza,
			ingredients: [],
			n: 1,
			bianca: false,
			rossa: false,
			omaggio: false,
		};
	}
	let $orderItem = ghostOrderItem.clone();
	$orderItem.find('[main]').text(pizza.name);
	$orderItem.find('[price]').text(pizza.price);
	for (let i in pizza.ingredients) {
		if (pizza.ingredients[i]) {
			if (pizza.ingredients[i] in ingredients) {
				let pizzaIngredient = ghostPizzaIngredient.clone();
				pizzaIngredient.text(ingredients[pizza.ingredients[i]].name);
				pizzaIngredient.attr('id_ingredient', pizza.ingredients[i]);
				if (order_data) {
					if (order_data.ingredients.indexOf(pizza.ingredients[i]) === -1) {
						pizzaIngredient.addClass('without-ingredient');
					}
				} else {
					order_row.ingredients.push(pizza.ingredients[i]);
				}
				$orderItem.find('[modifiche]').append(pizzaIngredient);
			}
		}
	}
	if (order_data) {
		for (let i in order_row.ingredients) {
			if (pizza.ingredients.indexOf(order_row.ingredients[i]) == -1 && (order_row.ingredients[i] in ingredients)) {
				let ingredient = ingredients[order_row.ingredients[i]];
				let $pizzaAddition = ghostPizzaAddition.clone();
				$pizzaAddition.text(ingredient.name);
				$pizzaAddition.attr('id_ingredient', ingredient.id_ingredient);
				$orderItem.find('[modifiche]').append($pizzaAddition);
			}
		}
	}
	$orderItem.data('orderRow', order_row);
	$('#listaPizze').append($orderItem);
	order.rows.push(order_row);
	return $orderItem;
}

$('#elencoPiatti .piatto').click(function() {
	let id_pizza = $(this).data('id_pizza');
	addPizzaToOrder(id_pizza);
});
$('#elencoIngredienti .ingrediente').click(function() {
	let already_selected = $(this).hasClass('selected');
	let id_ingredient = '' + $(this).data('id_ingredient'); // cast as string
	let ingredient = ingredients[id_ingredient];
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	let is_standard_ingredient = order_row.piatto.ingredients.indexOf(id_ingredient.toString()) !== -1;
	if (is_standard_ingredient) {
		if (already_selected) {
			// è un "senza"
			$selected_pizza.find('[id_ingredient="' + id_ingredient + '"]').addClass('without-ingredient');
		} else {
			// tolto ma rimesso
			$selected_pizza.find('[id_ingredient="' + id_ingredient + '"]').removeClass('without-ingredient');
		}
	} else {
		if (already_selected) {
			// aggiunta tolta
			$selected_pizza.find('[id_ingredient="' + id_ingredient + '"]').remove();
		} else {
			// nuova aggiunta
			let pizzaAddition = ghostPizzaAddition.clone();
			pizzaAddition.text(ingredient.name);
			pizzaAddition.attr('id_ingredient', id_ingredient);
			$selected_pizza.find('[modifiche]').append(pizzaAddition);
		}
	}

	let position = order_row.ingredients.indexOf(id_ingredient);
	if (already_selected) {
		if (position != -1) {
			order_row.ingredients.splice(position, 1);
		}
	} else {
		if (position == -1) {
			order_row.ingredients.push(id_ingredient);
		}
	}
	$(this).toggleClass('selected');
});

function select_pizza(element) {
	let already_selected = $(element).hasClass('selected');
	if (already_selected) {
		// deselecting
		$('#pizzeComponent').show();
		$('#ingredientiComponent').hide();
		hidePizzaToolbar();
	} else {
		// selecting new
		let order_row = $(element).data('orderRow');
		$('#pizzeComponent').hide();
		$('#ingredientiComponent').show();
		$('#elencoIngredienti .ingrediente').removeClass('selected');
		for (let i in order_row.ingredients) {
			$('#elencoIngredienti .ingrediente[data-id_ingredient="' + order_row.ingredients[i] + '"]').addClass('selected');
		}
		showPizzaToolbar();
	}
	$('#listaPizze .item').removeClass('selected');
	if (!already_selected) {
		$(element).addClass('selected');
	}
}

$('#pizzeContext [data-function="omaggio"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	order_row.omaggio = ! order_row.omaggio;
	$selected_pizza.find('[pizza-omaggio]').toggleClass('hidden');
});

$('#pizzeContext [data-function="bianca"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	order_row.bianca = ! order_row.bianca;
	if (order_row.bianca) {
		order_row.rossa = false;
		$selected_pizza.find('[pizza-rossa]').addClass('hidden');
	}
	$selected_pizza.find('[pizza-bianca]').toggleClass('hidden');
});

$('#pizzeContext [data-function="rossa"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	order_row.rossa = ! order_row.rossa;
	if (order_row.rossa) {
		order_row.bianca = false;
		$selected_pizza.find('[pizza-bianca]').addClass('hidden');
	}
	$selected_pizza.find('[pizza-rossa]').toggleClass('hidden');
});

$('#pizzeContext [data-function="meno"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	if (order_row.n > 1) {
		order_row.n--;
	}
	$selected_pizza.find('[quantity]').text(order_row.n);
});

$('#pizzeContext [data-function="più"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	order_row.n++;
	$selected_pizza.find('[quantity]').text(order_row.n);
});

$('#pizzeContext [data-function="duplica"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let og_order_row = $selected_pizza.data('orderRow');
	let new_order_row = $.extend(true, {}, og_order_row);
	new_order_row.n = 1;
	let $newPizza = addPizzaToOrder(og_order_row.piatto.id_pizza, new_order_row);
	if (new_order_row.bianca) {
		$newPizza.find('[pizza-bianca]').removeClass('hidden');
	}
	if (new_order_row.rossa) {
		$newPizza.find('[pizza-rossa]').removeClass('hidden');
	}
	if (new_order_row.omaggio) {
		$newPizza.find('[pizza-omaggio]').removeClass('hidden');
	}
});

$('#pizzeContext [data-function="elimina"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	let index = order.rows.indexOf(order_row);
	if (index !== -1) {
		order.rows.splice(index, 1);
	}
	$selected_pizza.remove();
	$('#pizzeComponent').show();
	$('#ingredientiComponent').hide();
	hidePizzaToolbar();
});

function select_customer(el) {
	// open modal to create order
	let id_customer = $(el).data('id');
	$('#addOrderModal [name="id_customer"]').val(id_customer);
	let customer = customers_cache[id_customer];
	$('#addOrderModal [name="name"]').val(customer.name);
	$('#addOrderModal [name="doorbell"]').val(customer.doorbell);
	$('#addOrderModal [name="address"]').val(customer.address);
	$('#addOrderModal [name="telephone"]').val(customer.telephone);
	hideCustomers();
	$('#overwriteCustomer').hide();
}

function saveCustomer(brandNew = false) {
	let customer = {
		id_customer: brandNew ? $('#deliveryTo [name="id_customer"]').val() : null,
		name: $('#deliveryTo [name="name"]').val(),
		doorbell: $('#deliveryTo [name="doorbell"]').val(),
		telephone: $('#deliveryTo [name="telephone"]').val(),
		address: $('#deliveryTo [name="address"]').val(),
	};
	$.post(site_url + 'customers/add_or_edit_customer', customer).always(function(data) {
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
	});
	$('#overwriteCustomer').hide();
}

$('#overwriteCustomer').click(function() {
	saveCustomer(true);
});
$('#saveNewCustomer').click(function() {
	saveCustomer(false);
});

$('#deliveryTo input').on('input', function() {
	$('#overwriteCustomer').hide();
	let id_customer = $('#deliveryTo [name="id_customer"]').val();
	if (id_customer && id_customer in customers_cache) {
		let og_customer = customers_cache[id_customer];
		for (let k in og_customer) {
			let el_k = $('#deliveryTo [name="' + k + '"]');
			if (el_k.length) {
				let n_k = el_k.val();
				if (og_customer[k] != n_k) {
					$('#overwriteCustomer').show();
				}
			}
		}
	}
});

$('#categorieIngredientiContainer .categoria').first().click();

function select_order(el) {
	// open modal to view/edit order

}
