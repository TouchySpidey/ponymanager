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
