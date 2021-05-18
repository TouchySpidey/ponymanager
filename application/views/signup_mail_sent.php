<html>
<?php html_head('Registrazione', ['login']) ?>
<body translate="no" class="v-flex">
	<?= topbar(false, false) ?>
	<div id="pageContainer" class="flex-1 v-flex">
		<div class="whole-page flex-1">
			<div class="login-container">
				<div class="v-flex" style="padding-bottom: 12px;">
					<div class="general-heading mt-auto mb-auto">Controlla le tue email!</div>
				</div>
				<div class="v-flex" style="padding-bottom: 24px;">
					<div class="text-center" style="font-size: 18px; padding-bottom: 6px;">Abbiamo mandato all'indirizzo <b><?= $email ?></b> l'email con il link per confermare la registrazione.</div>
					<div class="text-center">Se non la trovi, ti invitiamo a controllare nella Spam</div>
				</div>
				<div class="d-flex">
					<a class="bluelink" style="font-size: 18px;" href="<?= site_url() ?>"><i class="mdi mdi-arrow-left"></i> Indietro</a>
					<a class="ml-auto bluelink" style="font-size: 18px;" href="<?= site_url() ?>main/login"><i class="mdi mdi-login"></i> Login</a>
				</div>
			</div>
		</div>
	</div>
</body>
<?= import_js('login') ?>
</html>
