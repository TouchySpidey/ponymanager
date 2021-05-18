<html>
<?php html_head('Reset Password', ['login']) ?>
<body translate="no" class="v-flex">
	<div id="pageContainer" class="flex-1 v-flex">
		<div class="whole-page flex-1">
			<div class="login-container v-flex">
				<a id="logo" href="<?= site_url() ?>" class="bluelink ml-auto mr-auto">
					<img src="<?= site_url() ?>frontend/images/logos/ponymanager.svg"/>
					<span>PonyManager</span>
				</a>
				<h4 class="password-reset"><b>La password è stata reimpostata con successo.</b><br/> Tra qualche secondo verrai reindirizzato alla pagina di login. Se non funziona, <a class="bluelink" href="<?= site_url() ?>main/login">puoi clicare qui</a>.</h4>
			</div>
		</div>
	</div>
</body>
<script>
setTimeout(() => {window.location = site_url + 'main/login'}, 4000);
</script>
</html>
