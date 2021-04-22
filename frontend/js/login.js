if ('serviceWorker' in navigator) {
	navigator.serviceWorker.register(site_url + 'service-worker.js?v6', {
		scope: '.'
	}).then(function(registration) {
		console.log('Service Worker Registered with scope: ' + registration.scope);
	}, function(err) {
		console.log('Service Worker Registration FAILED: ' + err);
	});
}

$('#forgotPassword, #backToLogin').click(function() {
	$('.dual-vis').toggleClass('toggled');
});
$('#resetPassword').click(function() {
	$.post(site_url + 'main/forgot', {email: $('#email').val()}).always(function() {
		$('#mailSent').removeClass('hidden');
	});
});
