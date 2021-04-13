let $kitchenAddition = $('#kitchenAddition').remove().removeAttr('id');
let $kitchenWithout = $('#kitchenWithout').remove().removeAttr('id');
let $kitchenIngredient = $('#kitchenIngredient').remove().removeAttr('id');
let $ghostPizza = $('#ghostPizza').remove().removeAttr('id');
let $ghostPizzaAddition = $('#ghostPizzaAddition').remove().removeAttr('id');
let $ghostKitchenPrint = $('#ghostKitchenPrint').remove().removeAttr('id');
let $ghostPonyPrint = $('#ghostPonyPrint').remove().removeAttr('id');
let $ghostPizzaIngredient = $('#ghostPizzaIngredient').remove().removeAttr('id');
let $ghostDelivery = $('#ghostDelivery').remove().removeAttr('id');
let deliveries = false;
let meta_deliveries = [];
// in questo ordine
let $ghostOrderItem = $('#ghostOrderItem').remove().removeAttr('id');
let req_sent = 0, last_accepted = 0;
let from = false, to = false, mode = 'all';

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
							customer_found.attr('data-id', response.results[i].id_customer);
							customer_found.find('.nome-cliente').text(response.results[i].name);
							customer_found.find('.indirizzo-cliente').text(response.results[i].address);
							customer_found.find('.telefono-cliente').text(response.results[i].telephone);
							$('#resultsFound').append(customer_found);
							customers_cache[response.results[i].id_customer] = response.results[i];
						}

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

function editPizzaModal() {
	$('#editPizzaComposition').css('display', 'block');
}

function openNewOrderModal() {
	$('#addOrderModal').css('display', 'block');
	$('#addOrderModal input[name="name"]').focus();
}

let newOrder, last_row = 0, viewOrder;

$('#takeawayOrder').click(function() {
	$('.delivery-only').hide();
	newOrder.is_delivery = 0;
	calculateOrderTotal();
});
$('#deliveryOrder').click(function() {
	$('.delivery-only').show();
	newOrder.is_delivery = 1;
	calculateOrderTotal();
});

function order_init(tab = false) {
	$('[order-component]').hide();
	$('.tabs-container').find('.tab').removeClass('selected');

	if (newOrder.last_pizza_category) {
		select_category(newOrder.last_pizza_category);
	}

	if (!tab) {
		tab = $('.tab[data-tab="consegna"]');
	}
	$(tab).addClass('selected');
	let tab_function = $(tab).data('tab');
	$('[order-component][tab="' + tab_function + '"]').show();
}

function order_reset() {
	newOrder = {
		rows: [],
		notes: '',
		last_pizza_category: $('#elencoPiatti .piatto').first().data('elenco'),
		sconto: {
			abs: true,
			val: 5,
		},
		is_delivery: 1,
	};
	$('#inputImporto').data('val', {
		string: '',
		float: 0.,
	});
	$('#listaPizze').empty();
	orderTotalUpdate(0.);
	calculateOrderTotal();
	$('#deliveryToForm input').val('');
	$('#timetable .timetable-row').removeClass('selected');
	$('#pony .pickable').removeClass('selected');
	$('#overwriteCustomer').hide();
	$('#saveNewCustomer').hide();
	$('#paymentMethods [data-id_payment=""]').click();
	$('#deliveryOrder').click();
	$('#pony [data-id_pony=""]').click();

	order_init();
}

function show_assigned() {
	resetIngredientFinder();
	let $selected_pizza = $('#listaPizze .item.selected');
	if (!$selected_pizza.length) {
		return;
	}
	let order_row = $selected_pizza.data('orderRow');
	let id_pizza = order_row.id_piatto;
	if (!(id_pizza in pizzas)) {
		return;
	}
	let pizza = pizzas[id_pizza];
	let default_ingredients = pizza.ingredients;
	let all_ingredients = order_row.ingredients;
	$('#elencoIngredienti .ingrediente, #elencoIngredienti .notes-container').hide();
	for (let i in default_ingredients) {
		$('#elencoIngredienti .ingrediente[data-id_ingredient="' + default_ingredients[i] + '"]').show();
	}
	for (let i in all_ingredients) {
		$('#elencoIngredienti .ingrediente[data-id_ingredient="' + all_ingredients[i] + '"]').show();
	}
}

function select_category(category = false) {
	resetPizzaFinder();
	if (category) {
		newOrder.last_pizza_category = category;
		$('#categorieContainer .categoria').removeClass('selected');
		$('#categorieContainer .categoria[data-category="' + category + '"]').addClass('selected');
		$('#elencoPiatti .piatto').hide();
		$('#elencoPiatti .piatto[data-elenco="' + category + '"]').show();
	}
}

function show_notes() {
	$('#elencoIngredienti .ingrediente').hide();
	$('#elencoIngredienti .notes-container').show();
}

function select_ingredients_category(category = false) {
	resetIngredientFinder();
	if (category) {
		$('#elencoIngredienti .ingrediente, #elencoIngredienti .notes-container').hide();
		$('#elencoIngredienti .ingrediente[data-elenco="' + category + '"]').show();
	}
}

$('.tabs-container .tab').click(function() {
	order_init(this);
});

function addPizzaToOrder(id_pizza, order_data = false) {
	let order_row = {};
	let pizza = pizzas[id_pizza];
	if (order_data) {
		order_row = order_data;
	} else {
		order_row = {
			id_piatto: id_pizza,
			ingredients: [],
			n: 1,
			omaggio: false,
		};
	}
	let $orderItem = $ghostOrderItem.clone();
	$orderItem.find('[quantity]').text(order_row.n >= 1 ? parseInt(order_row.n) : order_row.n);
	$orderItem.find('[main]').text(pizza.name);
	$orderItem.find('[price]').text(pizza.price);
	for (let i in pizza.ingredients) {
		if (pizza.ingredients[i]) {
			if (pizza.ingredients[i] in ingredients) {
				let pizzaIngredient = $ghostPizzaIngredient.clone();
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
				let $pizzaAddition = $ghostPizzaAddition.clone();
				$pizzaAddition.find('[testo-ingrediente]').text(ingredient.name);
				$pizzaAddition.find('[prezzo-aggiunto]').text(ingredient.price);
				$pizzaAddition.attr('id_ingredient', ingredient.id_ingredient);
				$orderItem.find('[modifiche]').append($pizzaAddition);
			}
		}
	}
	$orderItem.data('orderRow', order_row);
	$('#listaPizze').append($orderItem);
	newOrder.rows.push(order_row);
	calculateOrderTotal();
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
	let is_standard_ingredient = pizzas[order_row.id_piatto].ingredients.indexOf(id_ingredient.toString()) !== -1;
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
			let pizzaAddition = $ghostPizzaAddition.clone();
			pizzaAddition.find('[testo-ingrediente]').text(ingredient.name);
			pizzaAddition.find('[prezzo-aggiunto]').text(ingredient.price);
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
	calculateOrderTotal();
});

function select_pizza(element) {
	$('#listaPizze .item').removeClass('selected');
	$(element).addClass('selected');

	let order_row = $(element).data('orderRow');
	$('#elencoIngredienti .ingrediente').removeClass('selected');
	for (let i in order_row.ingredients) {
		$('#elencoIngredienti .ingrediente[data-id_ingredient="' + order_row.ingredients[i] + '"]').addClass('selected');
	}
	$('#assigned').click();
	$('#pizza-notes').val(order_row.notes);
	$('#pizza-notes').off('input').on('input', function() {
		order_row.notes = this.value;
	});
	editPizzaModal();
}
$('#editPizzaComposition').on('modal-closed', function() {
	$('#listaPizze .item').removeClass('selected');
});

$('#pizzeContext [data-function="omaggio"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	order_row.omaggio = ! order_row.omaggio;
	$selected_pizza.find('[pizza-omaggio]').toggleClass('hidden');
	calculateOrderTotal();
});

$('#pizzeContext [data-function="meno"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	if (order_row.n > 1) {
		order_row.n--;
	} else {
		order_row.n = .5;
	}
	$selected_pizza.find('[quantity]').text(order_row.n);
	calculateOrderTotal();
});

$('#pizzeContext [data-function="più"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	if (order_row.n < 1) {
		order_row.n = 1;
	} else {
		order_row.n++;
	}
	$selected_pizza.find('[quantity]').text(order_row.n);
	calculateOrderTotal();
});

$('#pizzeContext [data-function="duplica"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let og_order_row = $selected_pizza.data('orderRow');
	let new_order_row = $.extend(true, {}, og_order_row);
	new_order_row.n = 1;
	let $newPizza = addPizzaToOrder(og_order_row.id_piatto, new_order_row);
	if (new_order_row.omaggio) {
		$newPizza.find('[pizza-omaggio]').removeClass('hidden');
	}
});

$('#pizzeContext [data-function="elimina"]').click(function() {
	let $selected_pizza = $('#listaPizze .item.selected');
	let order_row = $selected_pizza.data('orderRow');
	let index = newOrder.rows.indexOf(order_row);
	if (index !== -1) {
		newOrder.rows.splice(index, 1);
	}
	$selected_pizza.remove();
	$('#pizzeComponent').show();
	$('#ingredientiComponent').hide();
	closeModal('#editPizzaComposition');
	calculateOrderTotal();
});

let newCustomer = {
	name: '',
	doorbell: '',
	city: '',
	address: '',
	telephone: '',
};

function select_customer(id_customer = false) {
	// open modal to create order
	if (id_customer) {
		$('#deliveryTo [name="id_customer"]').val(id_customer);
		let customer = customers_cache[id_customer];
		$('#deliveryTo [name="name"]').val(customer.name);
		$('#deliveryTo [name="doorbell"]').val(customer.doorbell);
		$('#deliveryTo [name="city"]').val(customer.city);
		$('#deliveryTo [name="address"]').val(customer.address);
		$('#deliveryTo [name="telephone"]').val(customer.telephone);

		$('#saveNewCustomer').hide();
		$('#deliveryTo input').off('input change');
		$('#deliveryTo input').on('input change', function() {
			let changed = false;
			$('#deliveryTo input').each(function() {
				let n = $(this).attr('name');
				let v = $(this).val();
				if (customer[n] != v) {
					changed = true;
				}
			});
			if (changed) {
				$('#overwriteCustomer').show();
			} else {
				$('#overwriteCustomer').hide();
			}
		}).trigger('change');
	} else {
		$('#saveNewCustomer').hide();
		for (let k in newCustomer) {
			$('#deliveryTo [name="' + k + '"]').val(newCustomer[k]);
		}
		$('#deliveryTo input').off('input change');
		$('#deliveryTo input').on('input change', function() {
			let n = $(this).attr('name');
			newCustomer[n] = $(this).val();
			let compiled = newCustomer.name.length > 0;
			if (compiled) {
				$('#saveNewCustomer').show();
			} else {
				$('#saveNewCustomer').hide();
			}
		}).trigger('change');
	}
}
select_customer(false);

function saveCustomer(brandNew = false) {
	let customer = {
		id_customer: brandNew ? $('#deliveryTo [name="id_customer"]').val() : null,
		name: $('#deliveryTo [name="name"]').val(),
		doorbell: $('#deliveryTo [name="doorbell"]').val(),
		telephone: $('#deliveryTo [name="telephone"]').val(),
		address: $('#deliveryTo [name="address"]').val(),
		city: $('#deliveryTo [name="city"]').val(),
	};
	$.post(site_url + 'customers/add_or_edit_customer', customer).always(function(data) {
		try {
			let response = JSON.parse(data);
			if ('created' in response || !response.created) {
				if ('errors' in response) {
					console.log(response.errors);
				} else {
					$('#deliveryToForm input').val('');
					// todo
					customers_cache[response.id_customer] = response.customer_data;
					select_customer(response.id_customer);
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

function resetModalData(_draft) {
	$('#deliveryToForm [name="id_delivery"]').val(_draft.id_order)
	// newOrder.sconto = _draft.sconto;
	// newOrder.notes = _draft.notes;
	// newOrder.is_delivery = _draft.is_delivery;
	// newOrder.cod_pony = _draft.cod_pony;
	// newOrder.id_order = _draft.id_order;
	newOrder = $.extend(true, {}, _draft);
	if (_draft.is_delivery) {
		$('#deliveryOrder').addClass('selected');
		$('#takeawayOrder').removeClass('selected');
		$('.delivery-only').show();
	} else {
		$('#takeawayOrder').addClass('selected');
		$('#deliveryOrder').removeClass('selected');
		$('.delivery-only').hide();
	}

	if (_draft.id_order) {
		$('#orderTabs .tab[data-tab="stampa"]').show();
	} else {
		$('#orderTabs .tab[data-tab="stampa"]').hide();
	}

	$('#order-notes').val(_draft.notes);

	$('#paymentMethods .list-block').removeClass('selected');
	$('#paymentMethods .list-block[data-id_payment="' + _draft.payment_method + '"]').addClass('selected');

	$('#pony .js-pony').removeClass('selected');
	$('#pony .js-pony[data-id_pony="' + _draft.cod_pony + '"]').addClass('selected');

	$('#timetable .timetable-row').removeClass('selected');
	$('#timetable .timetable-row[data-time="' + _draft.delivery_time + '"]').addClass('selected');

	for (let k in _draft) {
		$('#deliveryTo input[name="' + k + '"]').val(_draft[k]);
	}

	$('#listaPizze').empty();
	newOrder.rows = [];
	for (let i in _draft.rows) {
		let order_row = _draft.rows[i];
		addPizzaToOrder(order_row.id_piatto, order_row);
	}

	calculateOrderTotal();
}
function patchOrder() {
	let _draft = $.extend(true, {}, newOrder);
	let formData = {
		id_order: $('#deliveryToForm [name="id_delivery"]').val(),
		rows: _draft.rows,
		sconto: _draft.sconto,
		notes: $('#order-notes').val(),
		is_delivery: 0 + $('#deliveryOrder').hasClass('selected'),
		payment_method: $('#paymentMethods .list-block.selected').data('id_payment'),
		cod_pony: $('#pony .js-pony.selected').data('id_pony'),
		delivery_time: $('#timetable .timetable-row.selected').data('time'),
	};
	$('#deliveryTo input').each(function(i, v) {
		let name = $(v).attr('name');
		let val = $(v).val();
		formData[name] = val;
	});
	return formData;
}

$('#sendOrder').click(function() {
	let formData = patchOrder();
	$.post(site_url + 'orders/add_or_edit_order', formData).always(function(data) {
		ordersFromDb();
		order_reset();
		closeModal('#addOrderModal');
	});
});

$('#tastiera [tasto]').click(function() {
	let fun = $(this).data('function');
	let input = $('#inputImporto').data('val');
	if (!input) {
		input = {
			string: '0',
			float: 0.,
		}
		$('#inputImporto').data('val', input);
	}
	switch (fun) {
		case '<':
		input.string = input.string.substring(0, input.string.length - 1);
		if (input.string == '') {
			input.string = '0';
		}
		break;
		case 'C':
		input.string = '0';
		break;
		default:
		if (input.string == '0') {
			input.string = '';
		}
		input.string += fun;
		break;
	}
	input.float = parseFloat(input.string);
	let total = $('#totale').data('val');
	$('#inputImporto').text(input.string);
	calculateChange();
});

function calculateChange() {
	let total = $('#totale').data('val');
	let paid = $('#inputImporto').data('val');
	let change = paid.float - total.float;
	$('#restoCalcolato').text(change?.toString() ? change.toString() : '0');
}

function orderTotalUpdate(newV) {
	$('#totale').data('val', {
		string: newV?.toString ? newV.toString() : '0',
		float: newV,
	});
	$('#totale').text(newV.toString());
	calculateChange();
}

function calculateOrderTotal() {
	let rows = newOrder.rows;
	let total = 0, subtotal = 0;
	if (newOrder.is_delivery == 1) {
		subtotal += 1.5;
	}
	for (let i in rows) {
		let price = 0;
		let row = rows[i];
		if (!row.omaggio) {
			let pizza = pizzas[row.id_piatto];
			price += parseFloat(pizza.price);
			if (('ingredients' in row) && row.ingredients.length) {
				for (let i in row.ingredients) {
					let id_ingredient = row.ingredients[i];
					if (id_ingredient && (id_ingredient in ingredients) && pizza.ingredients.indexOf(id_ingredient) === -1) {
						let ingredient = ingredients[id_ingredient];
						price += parseFloat(ingredient.price);
					}
				}
			}
			price *= parseFloat(row.n);
		}
		subtotal += price;
	}
	total = subtotal;
	orderTotalUpdate(total);
	$('#totaleOrdine [tot-text]').text(total.toString());
}

function purgeMetaDeliveries() {
	for (let i in meta_deliveries) {
		meta_deliveries[i].marker.setMap(null);
	}
	delete meta_deliveries;
	meta_deliveries = [];
}

function ordersFromDb() {
	$.post(site_url + 'orders/get_todays_orders').always(function(data) {
		try {
			let json = JSON.parse(data);
			deliveries = json;
			$('#scrollTimeFilter .timetable-row .order-n').text('');
			$('#timetable .timetable-row .order-n').text('');
			$('#listaOrdini').empty();
			purgeMetaDeliveries();
			for (let i in deliveries) {
				let $delivery = $ghostDelivery.clone();
				$delivery.find('.opener').attr('id-order', deliveries[i].id_order);
				$delivery.find('.nome-cliente').text(deliveries[i].name);
				let markerGeo = new google.maps.LatLng(deliveries[i].north, deliveries[i].east);
				let marker = new google.maps.Marker({
					position: markerGeo,
					map: map,
					title: deliveries[i].name,
					icon: site_url + 'frontend/images/icons/map_marker.svg',
					label: {
						text: deliveries[i]?.delivery_time ? deliveries[i].delivery_time : 'X',
						fontSize: '18px',
						fontWeight: 'bold',
					},
					zIndex: 1,
				});
				if (deliveries[i].delivery_time) {
					let d_n = $('#timetable .timetable-row[data-time="' + deliveries[i].delivery_time + '"] .order-n.n-consegne').text();
					let d_p = $('#timetable .timetable-row[data-time="' + deliveries[i].delivery_time + '"] .order-n.delivery.n-pizze').text();
					let t_p = $('#timetable .timetable-row[data-time="' + deliveries[i].delivery_time + '"] .order-n.takeaway.n-pizze').text();
					d_n = parseInt(d_n);
					if (!d_n) {
						d_n = 0;
					}
					d_p = parseInt(d_p);
					if (!d_p) {
						d_p = 0;
					}
					t_p = parseInt(t_p);
					if (!t_p) {
						t_p = 0;
					}
					if (deliveries[i].is_delivery) {
						d_n++;
						d_p += deliveries[i].rows.length;
					} else {
						t_p += deliveries[i].rows.length;
					}

					$('#timetable .timetable-row[data-time="' + deliveries[i].delivery_time + '"] .order-n.n-consegne').text(d_n);
					$('#timetable .timetable-row[data-time="' + deliveries[i].delivery_time + '"] .order-n.delivery.n-pizze').text(d_p);
					$('#timetable .timetable-row[data-time="' + deliveries[i].delivery_time + '"] .order-n.takeaway.n-pizze').text(t_p);
					$('#scrollTimeFilter .timetable-row[data-time="' + deliveries[i].delivery_time + '"] .order-n.n-consegne').text(d_n);
					$('#scrollTimeFilter .timetable-row[data-time="' + deliveries[i].delivery_time + '"] .order-n.delivery.n-pizze').text(d_p);
					$('#scrollTimeFilter .timetable-row[data-time="' + deliveries[i].delivery_time + '"] .order-n.takeaway.n-pizze').text(t_p);
				}
				meta_deliveries.push({
					listItem: $delivery,
					order_data: deliveries[i],
					marker: marker,
					dismissed: deliveries[i].dismissed,
					takeaway:!deliveries[i].is_delivery,
					delivery: !!deliveries[i].is_delivery,
					delivery_time: deliveries[i].delivery_time,
					all: true,
				});
				if (deliveries[i].is_delivery) {
					$delivery.find('.indirizzo-cliente').text((deliveries[i].city ? deliveries[i].city + ', ' : '') + deliveries[i].address);

					$delivery.find('.panner').click(() => {
						map.panTo(marker.getPosition());
					});
					marker.addListener('mouseover', () => {
						$delivery.addClass('yellow');
					});
					marker.addListener('mouseout', () => {
						$delivery.removeClass('yellow');
					});
					marker.addListener('click', () => {
						let checked = $delivery.find('.js-deliverable').prop('checked');
						$delivery.find('.js-deliverable').prop('checked', !checked);
						mayDisableMaster();
					});
				} else {
					$delivery.find('.panner').remove();
				}
				$('#listaOrdini').append($delivery);
			}
			filterOrders(mode, from, to);
		} catch(e) {
			console.log(e);
		}
	});
}

$('#ordersFilters .filter-tab').click(function() {
	mode = $(this).attr('type');
	filterOrders(mode, from, to);
});
$('#ordersFilters .filter-tab[type="all"]').click();

function filterOrders(mode = false, from = false, to = false) {
	if (from > to) {
		let dummy = to;
		to = from;
		from = dummy;
	}

	$('#scrollTimeFilter .timetable-row').each(function(i, v) {
		if (from && to && $(v).data('time') >= from && $(v).data('time') <= to) {
			$(v).addClass('gblue');
		} else {
			$(v).removeClass('gblue');
		}
	});

	for (let i in meta_deliveries) {
		let t = meta_deliveries[i].delivery_time;
		if (mode == 'dismissed') {
			if (meta_deliveries[i][mode] && (!t || !from && !to || t >= from && t <= to)) {
				meta_deliveries[i].marker.setMap(map);
				meta_deliveries[i].listItem.show();
			} else {
				meta_deliveries[i].marker.setMap(null);
				meta_deliveries[i].listItem.hide();
			}
		} else {
			if (!meta_deliveries[i]['dismissed'] && meta_deliveries[i][mode] && (!t || !from && !to || t >= from && t <= to)) {
				meta_deliveries[i].marker.setMap(map);
				meta_deliveries[i].listItem.show();
			} else {
				meta_deliveries[i].marker.setMap(null);
				meta_deliveries[i].listItem.hide();
			}
		}
	}
	mayDisableMaster();
}

$('#scrollTimeFilter .timetable-row').click(function() {
	let $another = $('#scrollTimeFilter').find('.timetable-row.filtering-range');
	mode = $('#ordersFilters .filter-tab.selected').attr('type');
	from = $(this).data('time');
	if ($another.length) {
		if ($another.length > 1) {
			// ce ne sono due!
			$another.removeClass('filtering-range');
			to = from;
		} else {
			// ce n'è un altro
			to = $another.data('time');
			if (from == to) {
				// ho cliccato esattamente quello già selezionato, quindi tolgo i filtri
				from = false;
				to = false;
			}
		}
	} else {
		// è il primo
		to = from;
	}
	filterOrders(mode, from, to);
	$(this).addClass('filtering-range');
});

function mayDisableMaster() {
	let allSelected = true;
	let atLeastOneSelected = false;
	for (let i in meta_deliveries) {
		let t = meta_deliveries[i].delivery_time;
		if (meta_deliveries[i][mode] && (!from && !to || t >= from && t <= to)) {
			let chk = meta_deliveries[i].listItem.find('.js-deliverable').prop('checked');
			if (!chk) {
				allSelected = false;
			} else {
				atLeastOneSelected = true;
			}
		}
	}
	$('#selectAllVisibleOrders').prop('checked', allSelected);
	if (atLeastOneSelected) {
		$('#ordersControl').show();
	} else {
		$('#ordersControl').hide();
	}
}

$('.dropdown-opener').click(function() {
	$(this).toggleClass('open');
});

function selectPony(id_pony) {
	let n_ordini = 0, ids_to_assign = [];
	for (let i in meta_deliveries) {
		let t = meta_deliveries[i].delivery_time;
		if (meta_deliveries[i][mode] && (!from && !to || t >= from && t <= to)) {
			let chk = meta_deliveries[i].listItem.find('.js-deliverable').prop('checked');
			if (chk) {
				n_ordini++;
				ids_to_assign.push(meta_deliveries[i].order_data.id_order);
			}
		}
	}
	if (id_pony in ponies) {
		if (confirm('Stai assegnando ' + n_ordini + ' ordini a ' + ponies[id_pony].name)) {
			$.post(site_url + '/orders/assign_orders', {ids: ids_to_assign, cod_pony: id_pony}).always(function() {
				ordersFromDb();
			});
		}
	} else {
		alert('Errore! pony non trovato!');
	}
}

function filterPizzasByName(toSearch) {
	if (toSearch.length) {
		$('#categorieContainer .categoria:not(.finder-container)').removeClass('selected');
		$('#categorieContainer .categoria.finder-container').addClass('selected');
		$('#elencoPiatti .piatto').hide();
		$('#elencoPiatti .piatto').each(function(i, v) {
			let id_piatto = $(v).attr('data-id_pizza');
			if (id_piatto in pizzas) {
				let piatto = pizzas[id_piatto];
				if (piatto.name.toLowerCase().indexOf(toSearch.toLowerCase()) !== -1) {
					// è una sottostringa
					$(v).show();
				}
			}
		});
	} else {
		$('#categorieContainer .categoria.finder-container').removeClass('selected');
		$('#pizzaFinder').val('');
	}
}

function filterIngredientsByName(toSearch) {
	if (toSearch.length) {
		$('#categorieIngredientiContainer .categoria:not(.finder-container)').removeClass('selected');
		$('#categorieIngredientiContainer .categoria.finder-container').addClass('selected');
		$('#elencoIngredienti .ingrediente').hide();
		$('#elencoIngredienti .ingrediente').each(function(i, v) {
			let id_ingredient = $(v).attr('data-id_ingredient');
			if (id_ingredient in ingredients) {
				let ingredient = ingredients[id_ingredient];
				if (ingredient.name.toLowerCase().indexOf(toSearch.toLowerCase()) !== -1) {
					// è una sottostringa
					$(v).show();
				}
			}
		});
	} else {
		$('#categorieIngredientiContainer .categoria.finder-container').removeClass('selected');
		$('#ingredientFinder').val('');
	}
}

$('#ingredientFinder').on('input change', function() {
	let str = $(this).val();
	filterIngredientsByName(str);
});

function resetIngredientFinder() {
	$('#ingredientFinder').val('').trigger('change');
}

$('#pizzaFinder').on('input change', function() {
	let str = $(this).val();
	filterPizzasByName(str);
});

function resetPizzaFinder() {
	$('#pizzaFinder').val('').trigger('change');
}

$('#selectAllVisibleOrders').change(function() {
	if (this.checked) {
		// accendi tutti
		let atLeastOneSelected = false;
		for (let i in meta_deliveries) {
			let t = meta_deliveries[i].delivery_time;
			if (meta_deliveries[i][mode] && (!from && !to || t >= from && t <= to)) {
				meta_deliveries[i].listItem.find('.js-deliverable').prop('checked', true);
				atLeastOneSelected = true;
			}
		}
		if (atLeastOneSelected) {
			$('#ordersControl').show();
		}
	} else {
		// spegni tutti
		for (let i in meta_deliveries) {
			let t = meta_deliveries[i].delivery_time;
			if (meta_deliveries[i][mode] && (!from && !to || t >= from && t <= to)) {
				meta_deliveries[i].listItem.find('.js-deliverable').prop('checked', false);
			}
		}
		$('#ordersControl').hide();
	}
});

function dismissSelected() {
	let n_ordini = 0, ids_to_dismiss = [];
	for (let i in meta_deliveries) {
		let t = meta_deliveries[i].delivery_time;
		if (meta_deliveries[i][mode] && (!from && !to || t >= from && t <= to)) {
			let chk = meta_deliveries[i].listItem.find('.js-deliverable').prop('checked');
			if (chk) {
				n_ordini++;
				ids_to_dismiss.push(meta_deliveries[i].order_data.id_order);
			}
		}
	}
	if (confirm('Stai segnando ' + n_ordini + ' ordini come conclusi')) {
		$.post(site_url + '/orders/dismiss_orders', {ids: ids_to_dismiss}).always(function() {
			ordersFromDb();
		});
	}
}

function ponyPrintSelected() {
	for (let i in meta_deliveries) {
		let t = meta_deliveries[i].delivery_time;
		if (meta_deliveries[i][mode] && (!from && !to || t >= from && t <= to)) {
			let chk = meta_deliveries[i].listItem.find('.js-deliverable').prop('checked');
			if (chk) {
				ponyPrint(meta_deliveries[i].order_data);
			}
		}
	}
}

function ponyPrint(_order = false) {
	$('#printable').empty();
	if (!_order) {
		_order = patchOrder();
	}

	let $ponyPrint = $ghostPonyPrint.clone();

	if (_order.is_delivery == 1) {
		$ponyPrint.find('.data').text(_order.delivery_date);
		$ponyPrint.find('[order-type][delivery]').show();
		$ponyPrint.find('[order-type][takeaway]').hide();
	} else {
		$ponyPrint.find('[order-type][takeaway]').show();
		$ponyPrint.find('[order-type][delivery]').hide();
	}
	$ponyPrint.find('[time]').text(_order.delivery_time);
	$ponyPrint.find('[totale-ordine]').text(_order.total_price);
	$ponyPrint.find('[address]').text((_order.city ? _order.city + ', ' : '') + _order.address);
	$ponyPrint.find('[doorbell]').text(_order.doorbell);
	$ponyPrint.find('[customer]').text(_order.name);
	$ponyPrint.find('[telephone]').text(_order.telephone);

	if (_order.notes) {
		$ponyPrint.find('[text-notes]').text(_order.notes);
	} else {
		$ponyPrint.find('.notes').remove();
	}
	if ('rows' in _order) {
		if (_order.rows.length) {
			let rows = _order.rows;
			for (let i in rows) {
				let row = rows[i];
				let $pizza = $ghostPizza.clone();
				let pizza = pizzas[row.id_piatto];
				$pizza.find('[pizza-name]').text(pizza.name);
				$pizza.find('[pizza-quantity]').text(row.n >= 1 ? parseInt(row.n) : row.n);
				for (let j in pizza.ingredients) {
					if (pizza.ingredients[j] in ingredients) {
						if (row.ingredients.indexOf(pizza.ingredients[j]) === -1) {
							// without
							let $ingredient = $kitchenWithout.clone();
							let ingredient = ingredients[pizza.ingredients[j]];
							$ingredient.find('[without-name]').text(ingredients[pizza.ingredients[j]].name);
							$ingredient.addClass('without');
							$pizza.append($ingredient);
						}
					}
				}
				for (let j in row.ingredients) {
					if (pizza.ingredients.indexOf(row.ingredients[j]) === -1) {
						$addition = $kitchenAddition.clone();
						$addition.find('[addition-name]').text(ingredients[row.ingredients[j]].name);
						$pizza.append($addition);
					}
				}
				$ponyPrint.find('[pizze-container]').append($pizza);
			}
		}
	}

	$('#printable').append($ponyPrint);
	new QRCode(document.getElementById('qrcode_printable'), {
		text: 'https://www.ponymanager.com/pony/qr/' + _order.id_order + '/' + deliveries[_order.id_order].guid,
		colorDark : "#000000",
		colorLight : "#ffffff",
		correctLevel : QRCode.CorrectLevel.H
	});

	window.print();

}

function kitchenPrintSelected() {
	for (let i in meta_deliveries) {
		let t = meta_deliveries[i].delivery_time;
		if (meta_deliveries[i][mode] && (!from && !to || t >= from && t <= to)) {
			let chk = meta_deliveries[i].listItem.find('.js-deliverable').prop('checked');
			if (chk) {
				kitchenPrint(meta_deliveries[i].order_data);
			}
		}
	}
}

function kitchenPrint(_order = false) {
	$('#printable').empty();
	if (!_order) {
		_order = patchOrder();
	}
	let $kitchenPrint = $ghostKitchenPrint.clone();
	$kitchenPrint.find('.data').text(_order.delivery_date);
	if (_order.is_delivery == 1) {
		$kitchenPrint.find('[order-type][delivery]').show();
		$kitchenPrint.find('[order-type][takeaway]').hide();
	} else {
		$kitchenPrint.find('[order-type][takeaway]').show();
		$kitchenPrint.find('[order-type][delivery]').hide();
	}
	$kitchenPrint.find('[time]').text(_order.delivery_time);
	$kitchenPrint.find('[travel_duration]').text(_order.travel_duration);
	$kitchenPrint.find('[customer]').text(_order.name);
	if ('rows' in _order) {
		if (_order.rows.length) {
			let rows = _order.rows;
			for (let i in rows) {
				let row = rows[i];
				let $pizza = $ghostPizza.clone();
				let pizza = pizzas[row.id_piatto];
				$pizza.find('[pizza-name]').text(pizza.name);
				$pizza.find('[pizza-quantity]').text(row.n >= 1 ? parseInt(row.n) : row.n);
				for (let j in pizza.ingredients) {
					if (pizza.ingredients[j] in ingredients) {
						if (row.ingredients.indexOf(pizza.ingredients[j]) === -1) {
							// without
							let $ingredient = $kitchenWithout.clone();
							let ingredient = ingredients[pizza.ingredients[j]];
							$ingredient.find('[without-name]').text(ingredients[pizza.ingredients[j]].name);
							$ingredient.addClass('without');
							$pizza.find('.stackable-stuff').append($ingredient);
						}
					}
				}
				for (let j in row.ingredients) {
					if (pizza.ingredients.indexOf(row.ingredients[j]) === -1) {
						$addition = $kitchenAddition.clone();
						$addition.find('[addition-name]').text(ingredients[row.ingredients[j]].name);
						$pizza.find('.stackable-stuff').append($addition);
					}
				}
				if (row.notes) {
					$pizza.find('[text-notes]').text(row.notes);
				} else {
					$pizza.find('.notes').remove();
				}
				$kitchenPrint.find('[pizze-container]').append($pizza);
			}
		}
	}
	$('#printable').append($kitchenPrint);
	window.print();
}

function promptNewOrder() {
	// is a draft available?
	if (draft) {
		order_reset();
		resetModalData(draft);
		draft = false;
	}
	// open modal
	openNewOrderModal();
}
let draft = false;

function selectOrder(id_order) {
	// was creating an order? let's save the draft
	if (!draft) {
		draft = patchOrder();
	}
	// fill the order modal with the order data
	if (id_order in deliveries) {
		let order_data = deliveries[id_order];
		resetModalData(order_data);
	}
	// open modal to view/edit order
	openNewOrderModal();
}

function delete_order() {
	let _draft = patchOrder();
	if (_draft.id_order) {
		if (confirm('ATTENZIONE! Eliminare questo ordine?')) {
			$.post(site_url + '/orders/delete_order', _draft).always(function(data) {
				ordersFromDb();
			});
			closeModal('#addOrderModal');
			order_reset();
		}
	} else {
		if (confirm('Annullare la bozza d\'ordine?')) {
			closeModal('#addOrderModal');
			order_reset();
		}
	}
}

let map;

function initMap() {
	let markerGeo = new google.maps.LatLng(geoShop.north, geoShop.east);
	map = new google.maps.Map(document.getElementById("Gmap"), {
		center: { lat: geoShop.north, lng: geoShop.east },
		zoom: 13,
	});
	let center_marker = new google.maps.Marker({
		position: markerGeo,
		map: map,
		title: 'Calima',
		icon: site_url + 'frontend/images/icons/blue_dot.svg',
		zIndex: 2,
	});


	let neBound = new google.maps.LatLng(geoShop.north + 0.04, geoShop.east + 0.04);
	let swBound = new google.maps.LatLng(geoShop.north - 0.04, geoShop.east - 0.04);
	// let sw_marker = new google.maps.Marker({
	// 	position: swBound,
	// 	map: map,
	// 	title: 'Calima',
	// 	icon: site_url + 'frontend/images/icons/blue_dot.svg',
	// 	zIndex: 2,
	// });
	// let ne_marker = new google.maps.Marker({
	// 	position: neBound,
	// 	map: map,
	// 	title: 'Calima',
	// 	icon: site_url + 'frontend/images/icons/blue_dot.svg',
	// 	zIndex: 2,
	// });
	let acBound = new google.maps.LatLngBounds(swBound, neBound);
	const input = document.getElementById("gfinder");
	const options = {
		bounds: acBound,
		// componentRestrictions: { country: "it" },
		fields: ["address_components", "geometry"],
		origin: markerGeo,
		strictBounds: false,
		types: ["address"],
	};
	const autocomplete = new google.maps.places.Autocomplete(input, options);




	$(function() {
		order_reset();
		ordersFromDb();
	});
}
