<html>
<?php html_head('Forgot Password', ['login']) ?>
<body translate="no" class="d-flex">
	<div class="container">
		<div class="card">
			<?php if ($email) { ?><h4 class="mail-sent">Se l'email è corretta, ti abbiamo inviato un link per reimpostare la password</h4><?php } ?>
			<h1 class="title">Recupera password</h1>
			<form action="<?= site_url() ?>main/forgot" method="POST">
				<div class="input-container">
					<input type="type" name="email" id="email" value="<?= $email ?>" required="required">
					<label for="email">Email</label>
					<div class="bar"></div>
				</div>
				<div class="button-container">
					<button><span>prosegui</span></button>
				</div>
				<div class="footer"><a href="<?= site_url() ?>">Torna al login</a></div>
			</form>
		</div>
	</div>
</body>
</html>
