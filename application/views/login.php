<html>
<?php html_head('Login', ['login']) ?>
<body translate="no" class="v-flex">
	<?= topbar(false, false) ?>
	<div id="pageContainer" class="flex-1 v-flex">
		<div class="whole-page flex-1">
			<div class="login-container">
				<div class="overflow-hidden">
					<div class="dual-vis">
						<div id="signInHeading" class="vis v-flex">
							<div class="login-text mt-auto mb-auto">Accedi</div>
						</div>
						<div id="forgotPasswordHeading" class="vis">
							<div class="forgot-password"><a href="#" id="backToLogin"><i class="mdi mdi-arrow-left"></i> torna al login</a></div>
							<div class="login-text">Reset della password</div>
						</div>
					</div>
					<form action="<?= site_url() ?>main/login" method="POST">
						<div class="input-container">
							<input type="type" name="email" id="email" value="<?= $email ?>" required="required">
							<label for="email">Email</label>
							<div class="bar"></div>
						</div>
						<div class="dual-vis">
							<div id="signInContainer" class="vis">
								<div class="input-container">
									<input type="password" name="password" id="password" required="required">
									<label for="password">Password</label>
									<div class="bar"></div>
								</div>
								<div class="forgot-password"><a href="#" id="forgotPassword">Password dimenticata?</a></div>
								<div class="d-flex">
									<div class="new-account" id="signupButton"><a href="#">Crea un account</a></div>
									<button type="submit" class="ml-auto btn gblue submit-login">Avanti</button>
								</div>
							</div>
							<div id="forgotPasswordContainer" class="vis v-flex">
								<div class="v-flex flex-1">
									<div>Riceverai un'email con il link che ti permetterà di reimpostare la password. <b>Non condividerla con nessuno!</b></div>
									<div id="mailSent" class="mt-auto mb-auto mail-sent hidden">
										<div><b>Email inviata.</b></div>
										<div>Se non la trovi, controlla di aver inserito l'email corretta e prova a guardare nella cartella spam</div>
									</div>
									<button type="button" id="resetPassword" class="mt-auto ml-auto btn gblue submit-login">Reset</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
<?= import_js('login') ?>
</html>
