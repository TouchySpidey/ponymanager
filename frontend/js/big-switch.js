$('.middle-switch').change(function() {
	if (this.checked) {
		$(this).closest('.big-switch').find('.rightyOption').addClass('active');
		$(this).closest('.big-switch').find('.leftyOption').removeClass('active');
	} else {
		$(this).closest('.big-switch').find('.leftyOption').addClass('active');
		$(this).closest('.big-switch').find('.rightyOption').removeClass('active');
	}
});
$('.rightyOption').click(function() {
	$(this).closest('.big-switch').find('.middle-switch').prop('checked', true).trigger('change');
});
$('.leftyOption').click(function() {
	$(this).closest('.big-switch').find('.middle-switch').prop('checked', false).trigger('change');
});
