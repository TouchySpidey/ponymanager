$('#tabs .filter-tab').click(function() {
	$('#panels .tabs').hide();
	$('#panels [tab="' + $(this).attr('tab') + '"]').show();
});
