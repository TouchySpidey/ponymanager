let $ghostCardShift = $('#ghostCardShift').remove().removeAttr('id');
let $ghostShiftBox = $('#ghostShiftBox').remove().removeAttr('id');
let $ghostPaymentBox = $('#ghostPaymentBox').remove().removeAttr('id');
syncShifts();

$(function() {
	$('#shiftsDialog').data('saveShift', () => {
		let _shifts = [];
		$('#modalShiftList .shift-box').each(function(i, v) {
			_shifts.push({
				from: $(v).find('.js-from').data('metatextfield').value,
				to: $(v).find('.js-to').data('metatextfield').value,
			})
		});
		$.post(site_url + 'orders/shifts' + company_url_suffix, {newShifts: _shifts}).always(function(res) {
			try {
				let json = JSON.parse(res);
				shifts = json;
				syncShifts();
			} catch(e) {
				console.error(e);
			}
		});
	});
});

function syncService() {
	$('#deliveryPrice').data('metatextfield').value = deliveryPrice;
	$('#one').prop('checked', serviceTakeAway).trigger('change');
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
