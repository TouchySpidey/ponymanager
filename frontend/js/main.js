$(function() {

	$('#menuToggler').click(function() {
		$(document.body).toggleClass('menu-open');
	});
	$('#mainMenuBackdrop').click(function(e) {
		if ($(this).is(e.target)) {
			$(document.body).removeClass('menu-open');
		}
	});
	$('.w3-modal.modal-container').each(function() {
		let modal = this;
		$(modal).find('.modal-backdrop').click(function() {
			$(modal).css('display', 'none');
		});
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

});

function closeModal(el) {
	$(el).closest('.modal-container').css('display', 'none');
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
