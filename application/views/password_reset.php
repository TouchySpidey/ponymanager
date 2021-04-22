<html>
<?php html_head('Reset Password', ['login']) ?>
<body translate="no" class="v-flex">
	<?= topbar(false, false) ?>
	<div id="pageContainer" class="flex-1 v-flex">
		<div class="whole-page flex-1">
			<div class="login-container">
				<?php if ($error) { ?><h4 class="login-failed"><?= $error ?></h4><?php } ?>
				<div id="signInHeading" class="v-flex">
					<div class="login-text mt-auto mb-auto">Scegli la nuova password</div>
				</div>
				<form action="<?= site_url() ?>main/reset_password/<?= $guid ?>" method="POST">
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
					<div class="input-container">
						<input type="password" name="c_password" id="c_password" required="required">
						<label for="password">Conferma Password</label>
						<div class="bar"></div>
					</div>
					<div class="d-flex">
						<button type="submit" class="ml-auto btn gblue submit-login">Avanti</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
<?= import_js('login') ?>
</html>
