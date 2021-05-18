<html>
<?php html_head('Registrazione', ['login']) ?>
<body translate="no" class="v-flex">
	<?= topbar(false, false) ?>
	<div id="pageContainer" class="flex-1 v-flex">
		<div class="whole-page flex-1">
			<div class="login-container">
				<div class="v-flex" style="padding-bottom: 12px;">
					<div class="general-heading mt-auto mb-auto">Link scaduto</div>
				</div>
				<div class="v-flex" style="padding-bottom: 24px;">
					<div class="text-center" style="font-size: 18px; padding-bottom: 6px;">I link di registrazione durano solo 1 ora</div>
					<a class="ml-auto bluelink" style="font-size: 18px;" href="<?= site_url() ?>main/login"><i class="mdi mdi-login"></i> Login</a>
				</div>
			</div>
		</div>
	</div>
</body>
<?= import_js('login') ?>
</html>
