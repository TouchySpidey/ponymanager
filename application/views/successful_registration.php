<html>
<?php html_head('Registrazione', ['login']) ?>
<body translate="no" class="v-flex">
	<?= topbar(false, false) ?>
	<div id="pageContainer" class="flex-1 v-flex">
		<div class="whole-page flex-1">
			<div class="login-container">
				<div class="v-flex" style="padding-bottom: 12px;">
					<div class="general-heading mt-auto mb-auto">Account registrato</div>
				</div>
				<div class="v-flex" style="padding-bottom: 24px;">
					<div class="text-center" style="font-size: 18px; padding-bottom: 6px;">Ti confermiamo l'avvenuta registrazione del tuo account. Effettua il login per accedere a PonyManager!</div>
					<a class="ml-auto bluelink" style="font-size: 18px;" href="<?= site_url() ?>"><i class="mdi mdi-login"></i> Login</a>
				</div>
			</div>
		</div>
	</div>
</body>
<?= import_js('login') ?>
</html>
