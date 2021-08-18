let $ghostCardShift = $('#ghostCardShift').remove().removeAttr('id');
let $ghostCardPayment = $('#ghostCardPayment').remove().removeAttr('id');
let $ghostShiftBox = $('#ghostShiftBox').remove().removeAttr('id');
let $ghostPaymentBox = $('#ghostPaymentBox').remove().removeAttr('id');
syncShifts();
syncPayments();

$(function() {
	$('#shiftsDialog').data('saveShift', () => {
		let _shifts = [];
		$('#modalShiftList .shift-box').each(function(i, v) {
			_shifts.push({
				from: $(v).find('.js-from').data('metatextfield').value,
				to: $(v).find('.js-to').data('metatextfield').value,
			});
		});
		$.post(site_url + 'company/shifts' + company_url_suffix, {newShifts: _shifts}).always(function(res) {
			try {
				let json = JSON.parse(res);
				shifts = json;
				syncShifts();
			} catch(e) {
				console.error(e);
			}
		});
	});
	$('#paymentsDialog').data('savePayment', () => {
		let _payments = [];
		$('#modalPaymentList .shift-box').each(function(i, v) {
			_payments.push($(v).find('.js-description').data('metatextfield').value);
		});
		$.post(site_url + 'company/payments' + company_url_suffix, {newPayments: _payments}).always(function(res) {
			try {
				let json = JSON.parse(res);
				payments = json;
				syncPayments();
			} catch(e) {
				console.error(e);
			}
		});
	});
	$('#serviceDialog').data('saveService', () => {
		let _service_price = null;
		let _service_default = false; // delivery
		_service_default = $('#one').prop('checked');
		_service_price = $('#deliveryPrice').data('metatextfield').value;
		$.post(site_url + 'company/service').always((res) => {
			console.log(res);
			syncService();
		});
	});

	syncService();
});

function syncPayments() {
	$('#paymentList').empty();
	$('#modalPaymentList').empty();
	for (let i in payments) {
		let payment = payments[i];
		let $payment = $ghostCardPayment.clone();
		$payment.find('.js-description').text(payment.description);
		$('#paymentList').append($payment);
		let $paymentBox = addPayment();
		$paymentBox.find('.js-description').data('metatextfield').value = payment.description;
		$('#modalPaymentList').append($paymentBox);
	}
}
function syncService() {
	$('#deliveryPrice').data('metatextfield').value = deliveryPrice;
	$('#one').prop('checked', serviceTakeAway).trigger('change');
	$('#serviceTypeChip .js-info').text(serviceTakeAway ? 'Takeaway' : 'Delivery');
	$('#servicePriceChip .js-info').text('€' + deliveryPrice);
}

function addPayment() {
	let $paymentBox = $ghostPaymentBox.clone();
	$paymentBox.find('.mdc-text-field').each((i, el) => $(el).data('metatextfield', mdc.textField.MDCTextField.attachTo(el)));
	$paymentBox.find('.js-remove-payment').click(() => {
		$paymentBox.remove();
	});
	$('#modalPaymentList').append($paymentBox);
	return $paymentBox;
}

function addShift() {
	let $shiftBox = $ghostShiftBox.clone();
	$shiftBox.find('.mdc-text-field').each((i, el) => $(el).data('metatextfield', mdc.textField.MDCTextField.attachTo(el)));
	$shiftBox.find('.js-remove-range').click(() => {
		$shiftBox.remove();
	});
	$('#modalShiftList').append($shiftBox);
	return $shiftBox;
}
function syncShifts() {
	$('#shiftList').empty();
	$('#modalShiftList').empty();
	for (let i in shifts) {
		let shift = shifts[i];
		let $shift = $ghostCardShift.clone();
		$shift.find('.js-from').text(shift.from);
		$shift.find('.js-to').text(shift.to);
		$('#shiftList').append($shift);
		let $shiftBox = addShift();
		$shiftBox.find('.js-from').data('metatextfield').value = shift.from;
		$shiftBox.find('.js-to').data('metatextfield').value = shift.to;
		$('#modalShiftList').append($shiftBox);
	}
}

function syncGeo() {
	let resetMapMarker = new google.maps.LatLng(geoShop.north, geoShop.east);
	shopMarker.setPosition(resetMapMarker);
	map.panTo(resetMapMarker);
}
function updateGeo() {
	let _geoShop = {
		north: shopMarker.getPosition().lat(),
		east: shopMarker.getPosition().lng(),
	};
	let latlng = _geoShop.north + ',' + _geoShop.east;
	$.post().always(function() {
		geoShop = _geoShop;
		let resetMapMarker = new google.maps.LatLng(geoShop.north, geoShop.east);
		map.panTo(resetMapMarker);
		let newStaticUrl = staticUrlPrefix + latlng + staticUrlMiddle + latlng + staticUrlSuffix;
		$('#staticMap').css('background-image', 'url(' + newStaticUrl + ')');
		syncGeo();
	})
}

let map;
let autocomplete;
let shopMarker;

function initMap() {
	let markerGeo = new google.maps.LatLng(geoShop.north, geoShop.east);
	map = new google.maps.Map(document.getElementById('Gmap'), {
		center: { lat: geoShop.north, lng: geoShop.east },
		zoom: 13,
		gestureHandling: 'greedy',
	});
	shopMarker = new google.maps.Marker({
		position: markerGeo,
		map: map,
		title: 'Calima',
		icon: site_url + 'frontend/images/icons/blue_dot.svg',
		zIndex: 2,
	});

	const input = document.getElementById('gfinder');
	const options = {
		// componentRestrictions: { country: 'it' },
		fields: ['address_components', 'geometry'],
		origin: markerGeo,
		strictBounds: false,
		types: ['address'],
	};
	autocomplete = new google.maps.places.Autocomplete(input, options);
	autocomplete.addListener('place_changed', () => {
		const place = autocomplete.getPlace();
		if (!place.geometry || !place.geometry.location) {
			return;
		}
		map.panTo(place.geometry.location);
		shopMarker.setPosition(place.geometry.location);
	});
	map.addListener('click', (mapsMouseEvent) => {
		shopMarker.setPosition(mapsMouseEvent.latLng);
		map.panTo(mapsMouseEvent.latLng);
	});
}
$('#editMap').click(() => {
	$('#positionModal').css('display', 'flex');
});
$('#editPayments').click(() => {
	$('#paymentsModal').css('display', 'flex');
});
