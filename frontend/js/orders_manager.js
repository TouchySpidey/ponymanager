
$(function() {
	order_reset();
	ordersFromDb();
});

let $kitchenAddition = $('#kitchenAddition').remove().removeAttr('id');
let $kitchenIngredient = $('#kitchenIngredient').remove().removeAttr('id');
let $ghostPizza = $('#ghostPizza').remove().removeAttr('id');
let $ghostPizzaAddition = $('#ghostPizzaAddition').remove().removeAttr('id');
let $ghostKitchenPrint = $('#ghostKitchenPrint').remove().removeAttr('id');
let $ghostPizzaIngredient = $('#ghostPizzaIngredient').remove().removeAttr('id');
let $ghostDelivery = $('#ghostDelivery').remove().removeAttr('id');
let deliveries = false;
// in questo ordine
let $ghostOrderItem = $('#ghostOrderItem').remove().removeAttr('id');
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
	if (category) {
		$('#elencoIngredienti .ingrediente, #elencoIngredienti .notes-container').hide();
		$('#elencoIngredienti .ingrediente[data-elenco="' + category + '"]').show();
	}
}

$('.tabs-container .tab').click(function() {
	order_init(this);
});

$('#categorieContainer .categoria').click(function() {
	let categoria = $(this).data('category');
	select_category(categoria);
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
				$pizzaAddition.text(ingredient.name);
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

function select_customer(el = false) {
	// open modal to create order
	if (el) {
		let id_customer = $(el).data('id');
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
					select_customer();
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
	newOrder.sconto = _draft.sconto;
	newOrder.notes = _draft.notes;
	if (_draft.is_delivery) {
		$('#deliveryOrder').addClass('selected');
		$('#takeawayOrder').removeClass('selected');
	} else {
		$('#takeawayOrder').addClass('selected');
		$('#deliveryOrder').removeClass('selected');
	}

	$('#order-notes').val(_draft.notes);

	$('#paymentMethods .list-block').removeClass('selected');
	$('#paymentMethods .list-block[data-id_payment="' + _draft.payment_method + '"]').addClass('selected');

	$('#pony .list-block').removeClass('selected');
	$('#pony .list-block[data-id_pony="' + _draft.cod_pony + '"]').addClass('selected');

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
		cod_pony: $('#pony .list-block.selected').data('id_pony'),
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
		// todo
		order_reset();
		select_order(data);
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

function ordersFromDb() {
	$.post(site_url + 'orders/get_todays_orders').always(function(data) {
		try {
			let json = JSON.parse(data);
			deliveries = json;
			$('#boxOrdini').empty();
			for (let i in deliveries) {
				let $delivery = $ghostDelivery.clone();
				$delivery.find('.opener').attr('id-order', deliveries[i].id_order);
				$delivery.find('.nome-cliente').text(deliveries[i].name);
				$delivery.find('.indirizzo-cliente').text(deliveries[i].city + ', ' + deliveries[i].address);
				let markerGeo = new google.maps.LatLng(deliveries[i].north, deliveries[i].east);
				let marker = new google.maps.Marker({
					position: markerGeo,
					map: map,
					title: deliveries[i].name,
					icon: '/frontend/images/icons/gmarker.png',
					label: {
						text: deliveries[i].delivery_time,
						className: 'my-g-label',
						fontSize: '22px',
						padding: '4px',
						fontWeight: 'bold',
					},
				});
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
				});
				$('#boxOrdini').append($delivery);
			}
		} catch(e) {
			console.log(e);
		}
	});
}

function kitchenPrint(_order) {
	$('#printable').empty();
	let $kitchenPrint = $ghostKitchenPrint.clone();
	if (_order.is_delivery == 1) {
		$kitchenPrint.find('[order-type][delivery]').show();
		$kitchenPrint.find('[order-type][takeaway]').hide();
	} else {
		$kitchenPrint.find('[order-type][takeaway]').show();
		$kitchenPrint.find('[order-type][delivery]').hide();
	}
	$kitchenPrint.find('[time]').text(_order.delivery_time);
	$kitchenPrint.find('[customer]').text(_order.name);
	let json_order = JSON.parse(_order.order_data);
	if ('rows' in json_order) {
		if (json_order.rows.length) {
			let rows = json_order.rows;
			for (let i in rows) {
				let row = rows[i];
				let $pizza = $ghostPizza.clone();
				let pizza = pizzas[row.id_piatto];
				$pizza.find('[pizza-name]').text(pizza.name);
				$pizza.find('[pizza-quantity]').text(row.n);
				for (let j in pizza.ingredients) {
					if (pizza.ingredients[j] in ingredients) {
						if (row.ingredients.indexOf(pizza.ingredients[j]) === -1) {
							let $ingredient = $kitchenIngredient.clone();
							let ingredient = ingredients[pizza.ingredients[j]];
							$ingredient.text(ingredients[pizza.ingredients[j]].name);
							$ingredient.addClass('without');
							$pizza.append($ingredient);
						}
					}
				}
				for (let j in row.ingredients) {
					if (pizza.ingredients.indexOf(row.ingredients[j]) === -1) {
						$addition = $kitchenAddition.clone();
						$addition.text(ingredients[row.ingredients[j]].name);
						$pizza.append($addition);
					}
				}
				$kitchenPrint.find('[pizze-container]').append($pizza);
			}
		}
	}
	console.log(json_order);
	$('#printable').append($kitchenPrint);
	window.print();
}

function promptNewOrder() {
	// is a draft available?
	if (draft) {
		// todo
		resetModalData(draft);
	}
	// open modal
	openNewOrderModal();
}
let draft = false;

function select_order(id_order) {
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

let map;

function initMap() {
	map = new google.maps.Map(document.getElementById("Gmap"), {
		center: { lat: 45.56080421699978, lng: 12.237069202199795 },
		zoom: 13,
	});
}
