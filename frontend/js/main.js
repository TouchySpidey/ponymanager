$(function() {

	$('#menuToggler').click(function() {
		$(document.body).toggleClass('menu-open');
	});
	$('#mainMenuBackdrop').click(function(e) {
		if ($(this).is(e.target)) {
			$(document.body).removeClass('menu-open');
		}
	});
	$('.modal-backdrop').click(function() {
		closeModal(this);
	});
	$('[open-filters]').click(function(ev) {
		let dropdownTrigger = this;
		if ($(dropdownTrigger).closest('.dropdown').hasClass('open')) {
			$(dropdownTrigger).closest('.dropdown').removeClass('open');
		} else {
			$(dropdownTrigger).closest('.dropdown').addClass('open');
			$(document).on('click', function(e) {
				if ($(e.target).closest('.dropdown').length == 0) {
					$(dropdownTrigger).closest('.dropdown').removeClass('open');
					$(document).off('click');
				}
			});
		}
	});
	$('.pick-one-of-these .pickable').click(function() {
		$(this).closest('.pick-one-of-these').find('.pickable').removeClass('selected');
		$(this).addClass('selected');
	})

});

function closeModal(el) {
	$modal = $(el).closest('.modal-container');
	$modal.css('display', 'none');
	var closeEvent; // The custom event that will be created
	if(document.createEvent){
		closeEvent = document.createEvent("HTMLEvents");
		closeEvent.initEvent("modal-closed", true, true);
		closeEvent.eventName = "modal-closed";
		$modal.get(0).dispatchEvent(closeEvent);
	} else {
		closeEvent = document.createEventObject();
		closeEvent.eventName = "modal-closed";
		closeEvent.eventType = "modal-closed";
		$modal.get(0).fireEvent("on" + closeEvent.eventType, closeEvent);
	}
}



function string_similarity(str1, str2) {
	str1 = str1.toUpperCase();
	str2 = str2.toUpperCase();
	let pairs1 = [];
	for (let i = 0; i < str1.length - 1; i++) {
		let pair = str1.substr(i, 2);
		if (pair.indexOf(' ') == -1) {
			pairs1.push(pair);
		}
	}
	let pairs2 = [];
	for (let i = 0; i < str2.length - 1; i++) {
		let pair = str2.substr(i, 2);
		if (pair.indexOf(' ') == -1) {
			pairs2.push(pair);
		}
	}
	let union = pairs1.length + pairs2.length;
	let intersection = 0;
	for (let i in pairs1) {
		let p1 = pairs1[i];
		for (let j = 0; j < pairs2.length; j++) {
			let p2 = pairs2[j];
			if (p1 == p2) {
				intersection++;
				pairs2.splice(j, 1);
				break;
			}
		}
	}

	return 2 * intersection / union;
}
