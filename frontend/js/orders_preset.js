let $ghostCardShift = $('#ghostCardShift').remove().removeAttr('id');
let $ghostShiftBox = $('#ghostShiftBox').remove().removeAttr('id');
syncShifts();

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
function addShift() {
	let $shiftBox = $ghostShiftBox.clone();
	$('#modalShiftList').append($shiftBox);
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
		let $shiftBox = $ghostShiftBox.clone();
		$shiftBox.find('.js-from').val(shift.from);
		$shiftBox.find('.js-to').val(shift.to);
		$('#modalShiftList').append($shiftBox);
	}
}
function updateShifts() {
	let _shifts = [];
	$('#modalShiftList .shift-box').each(function(i, v) {
		_shifts.push({
			from: $(v).find('.js-from').val(),
			to: $(v).find('.js-to').val(),
		})
	});
	$.post('', {newShifts: _shifts}).always(function(res) {
		try {
			let json = JSON.parse(res);
			shifts = json;
			syncShifts();
		} catch(e) {

		}
	});
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
$('#editShifts').click(() => {
	$('#shiftsModal').css('display', 'flex');
});
$('#editService').click(() => {
	$('#serviceModal').css('display', 'flex');
});
