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
