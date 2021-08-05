$('#newCompanyButton').click(function() {
	$('#newCompanyModal').css('display', 'flex');
});
let calcTag = true;
$('#shopName').on('input change', () => {
	if (calcTag) {
		$('#shopTag').val($('#shopName').val().toLowerCase().replace(/\s/g, '_').replace(/[^a-z0-9_]/g, ''));
		checkTagValidity();
	}
});
$('#shopTag').on('input change', () => {
	calcTag = false;
	$('#shopTag').off('input change');
});
$(function() {
	$('#changePasswordDialog').data('savePassword', () => {
		$.post(site_url + 'main/change_password', {
			old: $('#changePasswordOld').data('metatextfield').value,
			new: $('#changePasswordNew').data('metatextfield').value,
			confirm: $('#changePasswordConfirm').data('metatextfield').value,
		}).done(data => {
			console.log(data);
			console.log('Login riuscito, verrai reindirizzato al login entro qualche secondo');
			setTimeout(() => {location.href = site_url + 'main/login'}, 3500);
		}).always(() => {

		});
	});
	$('#changePasswordDialog').data('cancel', () => {
		$('#changePasswordDialog .mdc-text-field').each((i, el) => $(el).data('metatextfield').value = '');
	});
});
let openPasswordDialog = () => {
	$('#changePasswordDialog').data('metadialog').open();
}
$('#changePassword').click(openPasswordDialog);
// $('#makeLocal').click(() => {
// 	$.post(site_url + 'main/createCompany', {
// 		tag: $('#shopTag').val(),
// 		name: $('#shopName').val(),
// 	}).always((data) => {
// 		console.log(data);
// 	});
// });

function checkTagValidity() {
	let tag = $('#shopTag').val();
	return;
	$.post()
	.always((data) => {
		console.log(data);
	});
}
