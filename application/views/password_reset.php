<html>
<?php html_head('Reset password', ['login']) ?>
<body translate="no" class="d-flex">
	<div class="container">
		<div class="card">
			<?php if ($error) { ?><h4 class="login-failed"><?= $error ?></h4><?php } ?>
			<h1 class="title">Reimposta password</h1>
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
					<label for="c_password">Conferma Password</label>
					<div class="bar"></div>
				</div>
				<div class="button-container">
					<button><span>Reimposta</span></button>
				</div>
				<div class="footer"><a href="<?= site_url() ?>">Torna al login</a></div>
			</form>
		</div>
	</div>
</body>
</html>
