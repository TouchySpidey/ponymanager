
$('#forgotPassword, #backToLogin').click(function() {
	$('.dual-vis').toggleClass('toggled');
});
$('#resetPassword').click(function() {
	$.post(site_url + 'main/forgot', {email: $('#email').val()}).always(function() {
		$('#mailSent').removeClass('hidden');
	});
});
