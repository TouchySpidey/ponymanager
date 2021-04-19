<html>
<?php html_head('Login', ['login']) ?>
<body translate="no" class="d-flex">

	<script>
	if ('serviceWorker' in navigator) {
		navigator.serviceWorker.register('<?= site_url() ?>service-worker.js?v4', {
			scope: '.'
		}).then(function(registration) {
			console.log('Service Worker Registered with scope: ' + registration.scope);
		}, function(err) {
			console.log('Service Worker Registration FAILED: ' + err);
		});
	}
	</script>

	<div class="container">
		<div class="card">
			<?php if ($email) { ?><h4 class="login-failed">Email o password non corretti</h4><?php } ?>
			<h1 class="title">Login</h1>
			<form action="<?= site_url() ?>" method="POST">
				<div class="input-container">
					<input type="type" name="email" id="email" value="<?= $email ?>" required="required">
					<label for="email">Email</label>
					<div class="bar"></div>
				</div>
				<div class="input-container">
					<input type="password" name="password" id="password" required="required">
					<label for="password">Password</label>
					<div class="bar"></div>
				</div>
				<div class="button-container">
					<button><span>accedi</span></button>
				</div>
				<div class="footer"><a href="<?= site_url() ?>main/forgot">Password dimenticata</a></div>
			</form>
		</div>
	</div>
</body>
<script>
$('#email').focus();
</script>
</html>
