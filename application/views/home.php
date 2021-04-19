<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Home', ['home']) ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="flex-wrap">
			<div class="reducer">
				<div id="splash">
					<div class="title">PonyManager, Gestione semplice ed efficace</div>
					<div class="subtitle">L'unico tool online per facilitare la gestione degli ordini. Ottimizzato per gli esercizi che effettuano consegne a domicilio grazie all'integrazione con Google Maps</div>
				</div>
			</div>
			<div class="fixer">
				<div class="flex-wrap">
					<div class="card">
						<div class="general-heading">Informazioni</div>
						<div>
							email: <?= $this->session->user['email'] ?>
						</div>
					</div>
					<?php foreach ($privileges as $privilege) { ?>
						<a class="card" href="<?= site_url() ?>orders/index/company/<?= $privilege['uri_name'] ?>" style="color: black;">
							<div class="general-heading"><?= $privilege['name'] ?></div>
							<?php if (_GLOBAL_USER['email'] == $privilege['owner']) { ?>
								<div>Privilegi da proprietario</div>
							<?php } else { ?>
								<div>Accesso limitato</div>
							<?php } ?>
						</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
