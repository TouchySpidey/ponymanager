<html>
<?php html_head('Registrati', ['login']) ?>
<body translate="no" class="v-flex">
	<?= topbar(false, false) ?>
	<div id="pageContainer" class="flex-1 v-flex">
		<div class="whole-page flex-1">
			<div class="login-container">
				<div class="text-center">
					<?php if ($_email_existing) { ?>
						<div id="_email_existing" class="signup-error">L'email inserita è già associata ad un account esistente. <a href="<?= site_url() ?>main/login" class="bluelink">Clicca qui per accedere</a></div>
					<?php } ?>
					<?php if ($_form_incomplete) { ?>
						<div id="_form_incomplete" class="signup-error">Completa tutti i campi prima di continuare</div>
					<?php } ?>
					<?php if ($_password_short) { ?>
						<div id="_password_short" class="signup-error">La password deve contenere almeno 6 caratteri</div>
					<?php } ?>
					<?php if ($_password_must_match) { ?>
						<div id="_password_must_match" class="signup-error">Le password devono coincidere</div>
					<?php } ?>
					<?php if ($_accept_terms) { ?>
						<div id="_accept_terms" class="signup-error">Accetta i termini e le condizioni per registrarti al servizio</div>
					<?php } ?>
				</div>
				<div class="v-flex">
					<div class="login-text mt-auto mb-auto">Registrazione</div>
				</div>
				<form action="<?= site_url() ?>main/signup" method="POST">
					<div class="input-container">
						<input type="type" name="email" id="email" required="required">
						<label for="email">Email</label>
						<div class="bar"></div>
					</div>
					<div class="input-container">
						<input type="password" name="password" id="password" required="required">
						<label for="password">Password</label>
						<div class="bar"></div>
					</div>
					<div class="input-container">
						<input type="password" name="cpassword" id="cpassword" required="required">
						<label for="cpassword">Conferma Password</label>
						<div class="bar"></div>
					</div>
					<div class="">
						<label class="text-left"><input type="checkbox" name="accept_terms" required="required"> Cliccando su "Accetta e iscriviti", accetti i <a href="<?= site_url() ?>main/cookies" class="bluelink">termini e condizioni</a> di PonyManager.com</label>
					</div>
					<div class="d-flex" style="padding-top: 42px;">
						<div class="new-account" id="signupButton"><a class="bluelink" href="<?= site_url() ?>main/login">Hai già un account?</a></div>
						<button type="submit" class="ml-auto btn gblue submit-login">Accetta e iscriviti</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
<?= import_js('login') ?>
</html>
